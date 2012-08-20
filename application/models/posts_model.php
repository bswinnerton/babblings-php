<?php

class Posts_model extends CI_Model
{
	
	public function __construct()
	{
		$this->load->database();
	}
	
	public function getPosts($slug = FALSE)
	{
		if ($slug === FALSE)
		{
			$this->db->select('id_post, type, title, content, width, height, width_thumbnail, height_thumbnail');
			$this->db->where('is_deleted !=', 1);
			$this->db->where('status', 'active');
			$this->db->order_by("date_created", "desc");
			
			$query = $this->db->get('posts');
			return $query->result_array();
		}
		
		$query = $this->db->get_where('posts', array('id_post' => $slug));
		return $query->result_array();
	}
	
	public function addPost()
	{	
		// Initialize s3 support
		$this->load->library('s3');
		$s3 = new S3();
		
		//$this->load->helper('url');
		//$slug = url_title($this->input->post('title'), 'dash', TRUE);

		// todo: add handler to only run this if image
		
		// Format image URI
		$prettyImagePath = 'images/posts/';
		$prettyThumbnailPath = 'images/thumbnails/posts/';
		$localImagePath = ($this->config->item('storage') === 's3' ? 'temp/' : $prettyImagePath);
		$name = uniqid();
		$extension = pathinfo($this->input->post('content'));
		$image = $name.'.'.$extension['extension'];
		$thumbnail = $name.'_thumb'.'.'.$extension['extension'];
		
		// Push contents to local file
		$remoteImage = file_get_contents($this->input->post('content'));
		file_put_contents($localImagePath.$image, $remoteImage);
		
		// Get width and height to store in db
		$imageData = getimagesize($localImagePath.$image);
		$width = $imageData[0];
		$height = $imageData[1];
		$ratio = $height / $width;
		$adjustedHeight = ceil($ratio * 280);
		
		$this->createThumbnail($localImagePath.$image, $adjustedHeight);
		$s3Image = $s3->putObject($s3->inputFile($localImagePath.$image, false), $this->config->item('bucket', 's3'), $prettyImagePath.$image, S3::ACL_PUBLIC_READ);
		$s3Thumbnail = $s3->putObject($s3->inputFile($localImagePath.$thumbnail, false), $this->config->item('bucket', 's3'), $prettyThumbnailPath.$image, S3::ACL_PUBLIC_READ);
		
		// Data to be pushed to db
		$data = array (
			'id_author' => "1",
			'status' => "active",
			'date_created' => date('Y-m-d H:i:s'),
			'type' => "image",
			'content' => $image,
			'original_path' => $this->input->post('content'),
			'width' => $width,
			'height' => $height,
			'width_thumbnail' => '280',
			'height_thumbnail' => $adjustedHeight
		);
		
		if ($this->config->item('storage') == 's3')
		{
			// Add to s3 and remove local files
			if($s3Image && $s3Thumbnail) 
			{
				unlink($localImagePath.$image);
				unlink($localImagePath.$thumbnail);
				return $this->db->insert('posts', $data);
			} else {
				echo "Failed to upload images";
			}

		} else
		{
			// Moves thumbnail from temp to public folder
			rename($localImagePath.$thumbnail, $prettyThumbnailPath.$image);
			return $this->db->insert('posts', $data);
		}
	}
	
	public function createThumbnail($source, $height)
	{
		$this->load->library('image_lib');
		
		$thumbnail['image_library'] = 'GD2';
		$thumbnail['source_image']	= $source;
		$thumbnail['create_thumb'] = TRUE;
		$thumbnail['maintain_ratio'] = TRUE;
		$thumbnail['width'] = '280';
		$thumbnail['height'] = $height;
		
		$this->load->library('image_lib', $thumbnail);
		$this->image_lib->initialize($thumbnail);

		$this->image_lib->resize();
		
		if (!$this->image_lib->resize())
		{
		    echo $this->image_lib->display_errors();
		}
		
		return TRUE;
	}
	
}
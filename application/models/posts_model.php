<?php

class Posts_model extends CI_Model
{
	
	public function __construct()
	{
		// Load database no matter what
		$this->load->database();
	}
	
	public function getPosts($slug = FALSE)
	{
		// Check to see if we're looking for one specific post
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
	
	public function getName($image)
	{
		// Format image URI
		$imagePath = 'images/posts/';
		$thumbnailPath = 'images/thumbnails/posts/';
		
		$name = md5($image);
		$extension = pathinfo($this->input->post('content'));
		$image = $name.'.'.$extension['extension'];
		
		return array('image' => $imagePath.$image, 'thumbnail' => $thumbnailPath.$image, 'filename' => $image);
	}
	
	public function getType()
	{
		$content = $this->input->post('content');
		
		if (preg_match('/(\.jpg|\.png|\.bmp|\.gif)$/', $content))
		{
			return "image";
		}
		elseif (strpos($content, "youtube.com") !== FALSE)
		{
			return "youtube";
		}
		elseif (strpos($content, "vimeo.com") !== FALSE)
		{
			return "vimeo";
		}
		elseif (strpos($content, "spotify:track") !== FALSE)
		{
			return "spotify";
		}
		else 
		{
			return "text";
		}
	}
	
	public function addImagePost()
	{
		$this->load->model('files_model');
		
		// Get name and assign to image and thumbnail
		$name = $this->getName($this->input->post('content'));
		$filename = $name['filename'];
		$image = $name['image'];
		$thumbnail = $name['thumbnail'];
		
		// Get size and assign width, height and thumbnail height
		$size = $this->files_model->getSize($image);
		$width = $size['width'];
		$height = $size['height'];
		$thumbnailHeight = $size['adjustedHeight'];
		
		// Data to be pushed to database
		$data = array (
			'id_author' => "1",
			'status' => "active",
			'date_created' => date('Y-m-d H:i:s'),
			'type' => "image",
			'content' => $filename,
			'original_path' => $this->input->post('content'),
			'width' => $width,
			'height' => $height,
			'width_thumbnail' => $this->config->item('contentBox_width'),
			'height_thumbnail' => $thumbnailHeight
		);
		
		return $this->db->insert('posts', $data);
	}
	
	public function addYoutubePost()
	{
		preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $this->input->post('content'), $youtubeID);
		
		// Data to be pushed to database
		$data = array (
			'id_author' => "1",
			'status' => "active",
			'date_created' => date('Y-m-d H:i:s'),
			'type' => "youtube",
			'content' => $youtubeID[0],
			'original_path' => $this->input->post('content'),
			'width' => '280',
			'height' => '158'
		);
		
		return $this->db->insert('posts', $data);
	}
	
	public function addVimeoPost()
	{
		preg_match("/(?:www.)?(\w*).com\/(\d*)/", $this->input->post('content'), $vimeoID);
		
		// Data to be pushed to database
		$data = array (
			'id_author' => "1",
			'status' => "active",
			'date_created' => date('Y-m-d H:i:s'),
			'type' => "vimeo",
			'content' => $vimeoID[2],
			'original_path' => $this->input->post('content'),
			'width' => '280',
			'height' => '158'
		);
		
		return $this->db->insert('posts', $data);
	}
	
	public function addSpotifyPost()
	{
		preg_match("/spotify:track:([a-zA-Z0-9]{22})/", $this->input->post('content'), $spotifyID);
		
		// Data to be pushed to database
		$data = array (
			'id_author' => "1",
			'status' => "active",
			'date_created' => date('Y-m-d H:i:s'),
			'type' => "spotify",
			'content' => $spotifyID[1],
			'original_path' => $this->input->post('content')
		);
		
		return $this->db->insert('posts', $data);
	}
	
	public function addTextPost()
	{
		// Data to be pushed to database
		$data = array (
			'id_author' => "1",
			'status' => "active",
			'date_created' => date('Y-m-d H:i:s'),
			'type' => "text",
			'content' => $this->input->post('content')
		);
		
		return $this->db->insert('posts', $data);
	}
	
}
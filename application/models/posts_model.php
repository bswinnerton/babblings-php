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
			$this->db->select('type, title, content');
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
		$localPath = 'temp/';
		$s3Path = 'images/posts/';
		$name = uniqid().".";
		$extension = pathinfo($this->input->post('content'));
		$image = $name.$extension['extension'];
		
		// Push contents to local file
		$remoteImage = file_get_contents($this->input->post('content'));
		file_put_contents($localPath.$image, $remoteImage);
		
		// Get width and height to store in db
		$imageData = getimagesize($localPath.$image);
		$width = $imageData[0];
		$height = $imageData[1];
		
		
		// Add to s3 and remove local file
		$s3->putObject($s3->inputFile($localPath.$image, false), $this->config->item('bucket', 's3'), $s3Path.$image, S3::ACL_PUBLIC_READ);
		unlink($localPath.$image);
		
		// Data to be pushed to db
		$data = array (
			'id_author' => "1",
			'status' => "active",
			'date_created' => date('Y-m-d H:i:s'),
			'type' => "image",
			'content' => $image,
			'original_path' => $this->input->post('content'),
			'width' => $width,
			'height' => $height
		);

		return $this->db->insert('posts', $data);
	}
	
}
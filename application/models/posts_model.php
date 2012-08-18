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
		$this->config->load('s3', TRUE);
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
		
		// Add to s3 and remove local file
		$s3->putObject($s3->inputFile($localPath.$image, false), "s3.babblin.gs", $s3Path.$image, S3::ACL_PUBLIC_READ);
		unlink($localPath.$image);
		
		// Data to be pushed to db
		$data = array (
			'type' => "image",
			'date_created' => date('Y-m-d H:i:s'),
			'content' => $image,
			'original_path' => $this->input->post('content'),
			'id_author' => "1",
			'status' => "active"
		);

		return $this->db->insert('posts', $data);
	}
	
}
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
		$this->load->model('files_model');
		
		// Get name and assign to image and thumbnail
		$name = $this->getName();
		$image = $name['image'];
		$thumbnail = $name['thumbnail'];
		
		$size = $this->files_model->getSize($name['image']);
		$width = $size['width'];
		$height = $size['height'];
		$adjustedHeight = $size['adjustedHeight'];
		
		//$this->load->helper('url');
		//$slug = url_title($this->input->post('title'), 'dash', TRUE);

		// todo: add handler to only run this if image
		
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
		
		return $this->db->insert('posts', $data);
	}
	
	public function getName()
	{
		// Format image URI
		$prettyImagePath = 'images/posts/';
		$prettyThumbnailPath = 'images/thumbnails/posts/';
		$localImagePath = ($this->config->item('storage') === 's3' ? 'temp/' : $prettyImagePath);
		$localThumbnailPath = ($this->config->item('storage') === 's3' ? 'temp/' : $prettyThumbnailPath);
		
		$name = uniqid();
		$extension = pathinfo($this->input->post('content'));
		$image = $name.'.'.$extension['extension'];
		$thumbnail = $name.'_thumb'.'.'.$extension['extension'];
		
		return array('image' => $localImagePath.$image, 'thumbnail' => $localThumbnailPath.$thumbnail);
	}
	
}
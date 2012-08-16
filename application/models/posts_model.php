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
			$this->db->order_by("date_created", "asc");
			
			$query = $this->db->get('posts');
			return $query->result_array();
		}
		
		$query = $this->db->get_where('posts', array('id_post' => $slug));
		return $query->result_array();
	}
	
	public function formatPosts($data)
	{
		foreach ($data as &$dataPoint)
		{
			foreach ($dataPoint as &$postItem)
			{
				switch ($postItem['type'])
				{
					case "image":
						$postItem['content'] = "<img src=\"".$postItem['content']."\" width=\"280\" />";
						break;
					case "youtube":
						$postItem['content'] = "<iframe width=\"280\" height=\"158\" src=\"http://www.youtube.com/embed/".$postItem['content']."?showinfo=0\" frameborder=\"0\"></iframe>";
						break;
					case "vimeo":
						$postItem['content'] = "<iframe src=\"http://player.vimeo.com/video/".$postItem['content']."\" width=\"280\" height=\"158\" frameborder=\"0\" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>";
						break;
					case "text":
						$postItem['content'] = "<div id=\"text\">".$postItem['content']."</div>";
						break;
					case "audio":
						$postItem['content'] = "";
						break;
				}
			}
		}
		return $data;
	}
	
	public function addPost()
	{
		//$this->load->helper('url');
		//$slug = url_title($this->input->post('title'), 'dash', TRUE);

		// todo: add handler to only run this if image
		$url = file_get_contents($this->input->post('content'));
		$extension = pathinfo($this->input->post('content'));
		$image = 'images/'.uniqid("img").".".$extension['extension'];
		file_put_contents($image, $url);
        $data = array (
			'type' => "image",
			'content' => "/".$image,  // temporary fix for absolute path
			'original_path' => $this->input->post('content'),
			'id_author' => "1",
			'status' => "active"
		);

		return $this->db->insert('posts', $data);
	}
	
}

?>
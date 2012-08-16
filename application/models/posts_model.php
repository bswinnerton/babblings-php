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
			
			$query = $this->db->get('posts');
			return $query->result_array();
		}
		
		$query = $this->db->get_where('posts', array('id_post' => $slug));
		return $query->row_array();
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
		$this->load->helper('url');

		$slug = url_title($this->input->post('title'), 'dash', TRUE);

		$data = array(
			'id_author' => "1",
			'type' => "text",
			'status' => "active",
			'slug' => $slug,
			'content' => $this->input->post('content')
		);

		return $this->db->insert('posts', $data);
	}
	
}

?>
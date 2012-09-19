<?php

class Posts extends CI_Controller
{
	// Constructor
	function __construct()
	{
		//ini_set('memory_limit', '-1');
		parent::__construct();
		
		$this->config->load('s3', TRUE);
		$this->load->model('posts_model');
	}
	
	// View all posts
	public function index()
	{
		$data['post'] = $this->posts_model->getPosts();
		
		$this->load->view('header');
		$this->load->view('posts/index', $data);
		$this->load->view('footer');
	}
	
	// View single post
	public function view($slug)
	{
		$data['post'] = $this->posts_model->getPosts($page = FALSE, $slug);
		
		if (empty($data['post']))
		{
			show_404();
		}
		
		$this->load->view('header', $data);
		$this->load->view('posts/view', $data);
		$this->load->view('footer');
	}
	
	// View page
	public function page($page)
	{
		$data['post'] = $this->posts_model->getPosts($page, $slug = FALSE);
		
		$this->load->view('posts/page', $data);
	}
	
	// Create post
	public function create()
	{
		$this->load->model('files_model');
		$this->load->library('form_validation');
		$this->load->helper('form');

		$this->form_validation->set_rules('content', 'Content', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			$this->load->view('header');	
			$this->load->view('posts/create');
			$this->load->view('footer');

		}
		else
		{
			$type = $this->posts_model->getType();
			
			switch ($type)
			{
				case "image":
					if ($this->files_model->upload() === TRUE)
					{
						$this->posts_model->addImagePost();
						if ($this->config->item('storage') == 's3')
						{
							$this->files_model->cleanTemp();
						}
						$this->load->view('header');	
						$this->load->view('posts/success');
						$this->load->view('footer');
					} else {
						$this->load->view('header');
						$this->load->view('posts/failure');
						$this->load->view('footer');
					}
					break;
				case "youtube":
					$this->posts_model->addYoutubePost();
					$this->load->view('header');	
					$this->load->view('posts/success');
					$this->load->view('footer');
					break;
				case "vimeo":
					$this->posts_model->addVimeoPost();
					$this->load->view('header');	
					$this->load->view('posts/success');
					$this->load->view('footer');
					break;
				case "spotify":
					$this->posts_model->addSpotifyPost();
					$this->load->view('header');	
					$this->load->view('posts/success');
					$this->load->view('footer');
					break;
				case "text":
					$this->posts_model->addTextPost();
					$this->load->view('header');	
					$this->load->view('posts/success');
					$this->load->view('footer');
					break;
			}
		}
	}
	
}
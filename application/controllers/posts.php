<?php

class Posts extends CI_Controller
{
	// Constructor
	function __construct()
	{
		//ini_set('memory_limit', '-1');
		parent::__construct();
		
		$this->config->load('s3', TRUE);
	}
	
	// View all posts
	public function index()
	{	
		$this->load->model('posts_model');
		$data['post'] = $this->posts_model->getPosts();
		
		$this->load->view('header');
		$this->load->view('posts/index', $data);
		$this->load->view('footer');
	}
	
	// View single post
	public function view($slug)
	{	
		$this->load->model('posts_model');
		$data['post'] = $this->posts_model->getPosts($slug);
		
		if (empty($data['post']))
		{
			show_404();
		}
		
		$this->load->view('header');
		$this->load->view('posts/view', $data);
		$this->load->view('footer');
	}
	
	// Create post
	public function create()
	{
		$this->load->model('posts_model');
		$this->load->model('files_model');
		$this->load->library('form_validation');
		$this->load->library('image_lib');
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
						//$this->load->view('posts/preview');
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
					//$this->load->view('posts/preview');
					$this->load->view('footer');
					break;
				case "vimeo":
					$this->posts_model->addVimeoPost();
					$this->load->view('header');	
					$this->load->view('posts/success');
					//$this->load->view('posts/preview');
					$this->load->view('footer');
					break;
				case "text":
					$this->posts_model->addTextPost();
					$this->load->view('header');	
					$this->load->view('posts/success');
					//$this->load->view('posts/preview');
					$this->load->view('footer');
					break;
			}
		}
	}
	
}
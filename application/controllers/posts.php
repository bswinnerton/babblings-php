<?php

class Posts extends CI_Controller
{
	// Constructor
	function __construct()
	{
		parent::__construct();
		$this->load->model('posts_model');
	}
	
	// View all posts
	public function index()
	{
		$data['post'] = $this->posts_model->getPosts();
		$formattedData['post'] = $this->posts_model->formatPosts($data);
		
		$this->load->view('header');
		$this->load->view('posts/index', $formattedData);
		$this->load->view('footer');
	}
	
	// View single post
	public function view($slug)
	{
		$data['post'] = $this->posts_model->getPosts($slug);
		$formattedData['post'] = $this->posts_model->formatPosts($data);
		
		if (empty($data['post']))
		{
			show_404();
		}
		
		$this->load->view('header');
		$this->load->view('posts/view', $formattedData);
		$this->load->view('footer');
	}
	
	// Create post
	public function create()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('content', 'Content', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			$this->load->view('header');	
			$this->load->view('posts/create');
			$this->load->view('footer');

		}
		else
		{
			$this->postsModel->addPost();
			
			$this->load->view('header');	
			$this->load->view('posts/success');
			$this->load->view('footer');
		}
	}
	
}

?>
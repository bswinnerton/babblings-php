<?php

class Posts extends CI_Controller
{
	// Constructor
	function __construct()
	{
		parent::__construct();
		$this->load->model('posts_model');
		$this->config->load('s3', TRUE);
	}
	
	// View all posts
	public function index()
	{
		$this->load->helper('format');
		$environment = $this->config->item('storage') === 's3' ? 'http://'.$this->config->item('bucket', 's3') : '';
		
		$data['post'] = formatType($this->posts_model->getPosts(), $environment, 'all');
		
		$this->load->view('header');
		$this->load->view('posts/index', $data);
		$this->load->view('footer');
	}
	
	// View single post
	public function view($slug)
	{
		$this->load->helper('format');
		$environment = $this->config->item('storage') === 's3' ? 'http://'.$this->config->item('bucket', 's3') : '/';
		
		$data['post'] = formatType($this->posts_model->getPosts($slug), $environment, 'single');
		
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
			$this->posts_model->addPost();
			
			$this->load->view('header');	
			$this->load->view('posts/success');
			$this->load->view('footer');
		}
	}
	
}

?>
<?php

class Login extends CI_Controller
{
	// Constructor
	function __construct()
	{
		parent::__construct();
	}
	
	// View login page
	public function index()
	{
		$this->load->view('header');
		$this->load->view('underConstruction');
		$this->load->view('footer');
	}
}
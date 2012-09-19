<?php

class Help extends CI_Controller
{
	// Constructor
	function __construct()
	{
		parent::__construct();
	}
	
	// View help page
	public function index()
	{
		$this->load->view('header');
		$this->load->view('underConstruction');
		$this->load->view('footer');
	}
}
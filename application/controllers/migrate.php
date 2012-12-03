<?php defined("BASEPATH") or exit("No direct script access allowed");

class Migrate extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		$this->load->library('migration');
	}

	public function index()
	{
		if(ENVIRONMENT == 'development')
		{
			$this->output->enable_profiler(TRUE);
			if(!$this->migration->current())
			{
				show_error($this->migration->error_string());
			} else
			{
				echo "Successfully updated database schema";
			}
		}
		else
		{
			echo "Site is in production mode. You'll need to switch to development mode first.";
		}
	}

	public function version($slug = NULL)
	{
		if(ENVIRONMENT == 'development')
        {
            $this->output->enable_profiler(TRUE);
            if(isset($slug))
            {
                if(!$this->migration->version($slug))
				{
					show_error($this->migration->error_string());
				}
				else
				{
					echo "Successfully updated database schema";
				}
            }
        }
        else
        {
            echo "Site is in production mode. You'll need to switch to development mode first.";
        }
	}

}

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Is_Hidden extends CI_Migration {

	public function up()
	{
		//ALTER TABLE posts ADD is_hidden tinyint(1) NOT NULL DEFAULT 0 AFTER is_deleted;
		$fields = array(
			'is_hidden' => array(
        		'type' => 'tinyint',
            	'constraint' => 1, 
            	'null' => FALSE,
            	'default' => 0,
				'after' => 'is_deleted'
        	)
		);
		$this->dbforge->add_column('posts', $fields);
	}

	public function down()
	{
		$this->dbforge->drop_column('posts', 'is_hidden');
	}
}

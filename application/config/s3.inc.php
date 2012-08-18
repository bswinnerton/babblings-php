<?php

/*
CodeIgniter Amazon S3 library
=============================

This is an adjusted version of the Amazon S3 SDK by tpyo. It allows
loading the library in a CodeIgniter like manner. Edit the s3.php
config file and load the library:

	$this->load->library('s3');
	
The interface of the class has not been adjusted, the original
documentation is located at:

https://github.com/tpyo/amazon-s3-php-class/blob/master/README.txt

Be sure to place this in /application/config/development or /application/config/production once configured

*/

/*
| -------------------------------------------------------------------
| Amazon S3 Configuration
| -------------------------------------------------------------------
*/

$config['accessKey'] = "";
$config['secretKey'] = "";
$config['useSSL'] = FALSE;

$config['bucket'] = "";
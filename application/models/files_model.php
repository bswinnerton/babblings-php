<?php

class Files_model extends CI_Model
{
	
	public function upload()
	{
		$this->load->model('posts_model');
		
		// Get name and assign to image and thumbnail
		$name = $this->posts_model->getName($this->input->post('content'));
		$image = $name['image'];
		$thumbnail = $name['thumbnail'];
		
		// Push contents to local file
		$remoteImage = file_get_contents($this->input->post('content'));
		file_put_contents($image, $remoteImage);
		
		// Get thumbnail height from master file
		$size = $this->getSize($image);
		$thumbnailHeight = $size['adjustedHeight'];
		
		// Create thumbnail from local file
		$this->createThumbnail($image, $thumbnail, $thumbmailHeight);
		
		// Check if s3 storage is enabled from config
		if ($this->config->item('storage') == 's3')
		{
			// Prepare s3 library
			$this->load->library('s3');
			$s3 = new S3();
			
			// Store s3 upload command to variable
			$s3Image = $s3->putObject($s3->inputFile($image, false), $this->config->item('bucket', 's3'), $image, S3::ACL_PUBLIC_READ);
			$s3Thumbnail = $s3->putObject($s3->inputFile($thumbnail, false), $this->config->item('bucket', 's3'), $thumbnail, S3::ACL_PUBLIC_READ);
			
			// Return s3 commands
			return $s3Image && $s3Thumbnail;
		} else {
			// If s3 isn't enabled, check to see if local files exist, return false if not
			return file_exists($image) && file_exists($thumbnail);
		}
		
	}
	
	public function getSize($file)
	{
		$imageData = getimagesize($file);
		$width = $imageData[0];
		$height = $imageData[1];
		$ratio = $height / $width;
		$thumbnailHeight = ceil($ratio * 280);
		
		return array('width' => $width, 'height' => $height, 'adjustedHeight' => $thumbnailHeight, 'ratio' => $ratio);
	}
	
	public function createThumbnail($source, $destination, $height)
	{
		// Load CI image library
		$this->load->library('image_lib');
		
		// Define CI image lib variables
		$thumbnail['image_library'] = 'GD2';
		$thumbnail['source_image']	= $source;
		$thumbnail['new_image'] = $destination;
		$thumbnail['maintain_ratio'] = TRUE;
		$thumbnail['width'] = '280';
		$thumbnail['height'] = $height;
		
		// Call the image lib
		$this->load->library('image_lib', $thumbnail);
		$this->image_lib->initialize($thumbnail);

		// Resize passed in original to create thumbnail
		$this->image_lib->resize();
		
		// Check for errors
		if (!$this->image_lib->resize())
		{
		    echo $this->image_lib->display_errors();
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function cleanTemp()
	{
		$this->load->model('posts_model');
		
		// Get name and assign to image and thumbnail
		$name = $this->posts_model->getName($this->input->post('content'));
		$image = $name['image'];
		$thumbnail = $name['thumbnail'];
		
		// Remove original files
		unlink($image);
		unlink($thumbnail);
	}
	
}
<?php

class Files_model extends CI_Model
{
	
	public function __construct()
	{
		// Initialize s3 support
		$this->load->library('s3');
	}
	
	public function upload()
	{
		// Initialize s3 object
		$s3 = new S3();
		
		// Get name and assign to image and thumbnail
		$this->load->model('posts_model');
		$name = $this->posts_model->getName();
		$image = $name['image'];
		$thumbnail = $name['thumbnail'];
		
		// Push contents to local file
		$remoteImage = file_get_contents($this->input->post('content'));
		file_put_contents($image, $remoteImage);
		
		$size = $this->getSize($image);
		$adjustedHeight = $size['adjustedHeight'];
		$this->createThumbnail($image, $adjustedHeight);
		
		$s3Image = $s3->putObject($s3->inputFile($image, false), $this->config->item('bucket', 's3'), $image, S3::ACL_PUBLIC_READ);
		$s3Thumbnail = $s3->putObject($s3->inputFile($thumbnail, false), $this->config->item('bucket', 's3'), $thumbnail, S3::ACL_PUBLIC_READ);
		
		if ($this->config->item('storage') == 's3')
		{
			unlink($image);
			unlink($thumbnail);
		} else {
			// Moves thumbnail from temp to public folder
			rename($thumbnail, $image);
		}
		
		// return true if uploaded, false if failed
		if ($s3Image && $s3Thumbnail)
		{
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function getSize($file)
	{	
		$imageData = getimagesize($file);
		$width = $imageData[0];
		$height = $imageData[1];
		$ratio = $height / $width;
		$adjustedHeight = ceil($ratio * 280);
		
		return array('width' => $width, 'height' => $height, 'adjustedHeight' => $adjustedHeight, 'ratio' => $ratio);
	}
	
	public function createThumbnail($source, $height)
	{
		$this->load->library('image_lib');
		
		$thumbnail['image_library'] = 'GD2';
		$thumbnail['source_image']	= $source;
		$thumbnail['create_thumb'] = TRUE;
		$thumbnail['maintain_ratio'] = TRUE;
		$thumbnail['width'] = '280';
		$thumbnail['height'] = $height;
		
		$this->load->library('image_lib', $thumbnail);
		$this->image_lib->initialize($thumbnail);

		$this->image_lib->resize();
		
		if (!$this->image_lib->resize())
		{
		    echo $this->image_lib->display_errors();
		}
		
		return TRUE;
	}
	
}
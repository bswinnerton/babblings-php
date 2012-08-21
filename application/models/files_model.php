<?php

class Files_model extends CI_Model
{
	
	public function uploadFile()
	{
		// Initialize s3 support
		$this->load->library('s3');
		$s3 = new S3();
		
		// Format image URI
		$prettyImagePath = 'images/posts/';
		$prettyThumbnailPath = 'images/thumbnails/posts/';
		$localImagePath = ($this->config->item('storage') === 's3' ? 'temp/' : $prettyImagePath);
		$name = uniqid();
		$extension = pathinfo($this->input->post('content'));
		$image = $name.'.'.$extension['extension'];
		$thumbnail = $name.'_thumb'.'.'.$extension['extension'];
		
		// Push contents to local file
		$remoteImage = file_get_contents($this->input->post('content'));
		file_put_contents($localImagePath.$image, $remoteImage);
		
		// Get width and height to store in db
		$imageData = getimagesize($localImagePath.$image);
		$width = $imageData[0];
		$height = $imageData[1];
		$ratio = $height / $width;
		$adjustedHeight = ceil($ratio * 280);
		
		$this->createThumbnail($localImagePath.$image, $adjustedHeight);
		$s3Image = $s3->putObject($s3->inputFile($localImagePath.$image, false), $this->config->item('bucket', 's3'), $prettyImagePath.$image, S3::ACL_PUBLIC_READ);
		$s3Thumbnail = $s3->putObject($s3->inputFile($localImagePath.$thumbnail, false), $this->config->item('bucket', 's3'), $prettyThumbnailPath.$image, S3::ACL_PUBLIC_READ);
		
		if ($this->config->item('storage') == 's3')
		{
			// Add to s3 and remove local files
			if ($s3Image && $s3Thumbnail) 
			{
				unlink($localImagePath.$image);
				unlink($localImagePath.$thumbnail);
			} else {
				echo "Failed to upload images";
			}

		} else
		{
			// Moves thumbnail from temp to public folder
			rename($localImagePath.$thumbnail, $prettyThumbnailPath.$image);
		}
		
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
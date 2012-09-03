<?php

$this->load->helper('format');
$environment = $this->config->item('storage') === 's3' ? 'http://'.$this->config->item('bucket', 's3') : '';
		
?>

<div id="container">
	<?php 
	foreach ($post as $postData): 
		if ($postData['type'] == "text") {
	?>
    	<div class="contentBox"><?php echo formatType($postData['id_post'], $postData['type'], $postData['content'], $postData['width_thumbnail'], $postData['height_thumbnail'], 'all', $environment); ?></div>
	<?php 
		} else {
	?>
		<div class="contentBox" id="noText"><?php echo formatType($postData['id_post'], $postData['type'], $postData['content'], $postData['width_thumbnail'], $postData['height_thumbnail'], 'all', $environment); ?></div>
	<?php 
		}
	endforeach;
	?>
</div>
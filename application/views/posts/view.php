<?php

$this->load->helper('format');
$environment = $this->config->item('storage') === 's3' ? 'http://'.$this->config->item('bucket', 's3') : '';
		
?>

<div id="containerSingleOuter">
	<div id="containerSingleInner">
		<?php foreach ($post as $postData): ?>
	    <div class="contentBox" id="noText"><?php echo formatType($postData['id_post'], $postData['type'], $postData['content'], $postData['width'], $postData['height'], 'single', $environment); ?></div>
		<?php endforeach; ?>
	</div>
	<div class="clear"></div>
</div>
<?php

$this->load->helper('format');
$environment = $this->config->item('storage') === 's3' ? 'http://'.$this->config->item('bucket', 's3') : '';
		
?>

<div class="containerSingleOuter">
	<div class="containerSingleInner">
		<?php foreach ($post as $postData): ?>
	    <div id="contentBox"><?php echo formatType($postData['id_post'], $postData['type'], $postData['content'], $postData['width'], $postData['height'], 'single', $environment); ?></div>
		<?php endforeach; ?>
	</div>
	<div class="clear"></div>
</div>
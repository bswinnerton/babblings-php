<div class="container">
	<?php foreach ($post as $postItem): foreach ($postItem as $postValue): ?>
    <div id="contentBox"><?php echo $postValue['content']; ?></div>
	<?php endforeach; endforeach; ?>
</div>
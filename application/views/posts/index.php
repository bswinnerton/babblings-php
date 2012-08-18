<div class="container">
	<?php foreach ($post as $postItem): ?>
    <div id="contentBox"><a href="posts/view/<?php echo $postItem['id_post']; ?>"><?php echo $postItem['content']; ?></a></div>
	<?php endforeach; ?>
</div>
<?php echo validation_errors(); ?>
	
<div id="textbar">
	<?php echo form_open('posts/create') ?>
		<input type="text" name="content" value="enter your text, image, or video" onfocus="if (this.value == 'enter your text, image, or video') {this.value = '';}" onblur="if (this.value == '') {this.value = 'enter your text, image, or video';}" />
	</form>
</div>
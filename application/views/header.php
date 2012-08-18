<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Author" content="Brooks Swinnerton, brooks@rockthepost.com" />
	<title>babblings of thee</title>
	<link rel="stylesheet" href="/css/reset.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/css/style.css" type="text/css" media="screen" />
	<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.pack.js"></script>
	<script type="text/javascript" src="/js/jquery.masonry.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){	
			//$('.container').fadeIn();
			
			/*$(window).scroll(function() {
				var bgHeight = 1200;
				var documentHeight = $(document).height();
				var windowHeight = $(window).height();
				var offset = $(window).scrollTop();
    				
				var maxScroll = documentHeight - windowHeight;
				var percentOffset = offset / maxScroll;
				var percentToPixels = -(bgHeight-windowHeight)*percentOffset;
    				
				$('body').css({backgroundPosition: '50% '+percentToPixels+'px'});
			});*/
			
			// Masonry plugin
			var $container = $('.container');
			$container.imagesLoaded(function(){
			  $container.masonry({
			    itemSelector : '#contentBox',
			    columnWidth : 314
			  });
			});
			
			// Submit on enter for posts/create/
			$(function(){
			    $('input').keydown(function(e){
			        if (e.keyCode == 13) {
			            $(this).parents('form').submit();
			            return false;
			        }
			    });
			});
		});
	</script>
</head>
<body>
<center><a href="/"><img id="logo" src="/images/logo.png" /></a></center>
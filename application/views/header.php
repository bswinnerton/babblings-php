<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Author" content="Brooks Swinnerton, brooks@rockthepost.com" />
	<meta name="Description" content="babbles of the internet, all in one place." />
	<title>babblings of thee</title>
	<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
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
				$container.masonry({
				itemSelector : '#contentBox',
				columnWidth : 314
			});
			
			// Endless scroll
			(function(){

			    //inner functions will be aware of this
			    var currentPage = 1,
			        currentXHR;

			        $(window).scroll(function() {

			        if($(window).scrollTop() + $(window).height() > $(document).height() - 500) {

			            if (currentXHR) {
			                return;
			            }

			            currentXHR = $.ajax({
			                type: "GET",
			                url: "posts/page/" + currentPage++,
			                data: "",
			                success: function(results){
			                    $(".container").append(results).masonry('reload');
			                },
			                complete: function() {
			                    currentXHR = null;
			                }
			            })

			        }

			    });

			})();
			
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
	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-34310072-1']);
	  _gaq.push(['_trackPageview']);

	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
</head>
<body>
	<div class="header">
		<div class="header_inner"><h1><a href="http://localhost">babblings</a></h1>
	</div>
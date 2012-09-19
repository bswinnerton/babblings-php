<?php

$this->load->helper('url');
$environment = $this->config->item('storage') === 's3' ? 'http://'.$this->config->item('bucket', 's3') : '';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Author" content="Brooks Swinnerton, brooks@rockthepost.com" />
	<meta name="Description" content="babblings of the internet, all in one place." />
	<meta property="og:title" content="babblings of the internet" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="<?php echo site_url().$this->uri->uri_string(); ?>" />
	<meta property="og:image" content="<?php echo $environment."/images/posts/".$post[0]['content']; ?>" />
	<meta property="og:site_name" content="babblings." />
	<meta property="og:description" content="babblings of the internet" />
	<title>babblings of thee</title>
	<link href="http://fonts.googleapis.com/css?family=Lobster" rel="stylesheet" type="text/css" />
	<link href="http://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="/css/reset.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/css/style.css" type="text/css" media="screen" />
	<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.pack.js"></script>
	<script type="text/javascript" src="/js/jquery.masonry.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			
			// Masonry plugin
			var $container = $('#container');
				$container.masonry({
				itemSelector : '.contentBox',
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
			                    $("#container").append(results).masonry('reload');
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
	<div id="header">
		<div id="header_inner">
			<div id="logo">
				<h1><a href="<?php echo base_url(); ?>">babblings</a></h1>
			</div>
			<div id="links">
				<ul>
					<li><a href="<?php echo base_url(); ?>login">Login</a></li>
					<li><a href="<?php echo base_url(); ?>help">Help</a></li>
					<li><a href="<?php echo base_url(); ?>blog">Blog</a></li>
				</ul>
			</div>
		</div>
	</div>
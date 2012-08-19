<?php

function formatType($id, $type, $content, $width, $height, $view, $environment)
{	
	if ($width > 960)
	{
		$ratio = $height / $width;
		$width = 960;
		$height = $ratio * 960;
	}
	$ratio = $height / $width;
	$adjustedHeight = ceil($ratio * 280);
	
	switch ($type)
	{
		case "image":
			$content = $view == "all" ? "<a href=\"posts/view/".$id."\"><img src=\"".$environment."/images/posts/".$content."\" width=\"280\" height=\"".$adjustedHeight."\" /></a>" : "<img src=\"".$environment."/images/posts/".$content."\" width=\"".$width."\" height=\"".$height."\" />";
			break;
		case "youtube":
			$content = "<iframe width=\"280\" height=\"158\" src=\"http://www.youtube.com/embed/".$content."?showinfo=0\" frameborder=\"0\"></iframe>";
			break;
		case "vimeo":
			$content = "<iframe width=\"280\" height=\"158\" src=\"http://player.vimeo.com/video/".$content."\" frameborder=\"0\" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>";
			break;
		case "text":
			$content = "<div id=\"text\">".$content."</div>";
			break;
		case "audio":
			$content = "";
			break;
	}
	return $content;
}
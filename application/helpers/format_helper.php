<?php

function formatType($id, $type, $content, $width, $height, $view, $environment)
{	
	if ($type === "image") {
		if ($width > 960)
		{
			$ratio = $height / $width;
			$width = 960;
			$height = $ratio * 960;
		}
		$ratio = $height / $width;
		$adjustedHeight = ceil($ratio * 280);
	}
	
	switch ($type)
	{
		case "image":
			$content = $view == "all" ? "<a href=\"posts/view/".$id."\"><img src=\"".$environment."/images/thumbnails/posts/".$content."\" width=\"280\" height=\"".$adjustedHeight."\" /></a>" : "<img src=\"".$environment."/images/posts/".$content."\" width=\"".$width."\" height=\"".$height."\" />";
			break;
		case "youtube":
			$content = "<iframe width=\"280\" height=\"158\" src=\"http://www.youtube.com/embed/".$content."?showinfo=0\" frameborder=\"0\"></iframe>";
			break;
		case "vimeo":
			$content = "<iframe width=\"280\" height=\"158\" src=\"http://player.vimeo.com/video/".$content."?portrait=0\" frameborder=\"0\" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>";
			break;
		case "text":
			$content = "<div id=\"text\">".$content."</div>";
			break;
		case "spotify":
			$content = "<iframe src=\"https://embed.spotify.com/?uri=spotify:track:".$content."\" width=\"280\" height=\"80\" frameborder=\"0\" allowtransparency=\"true\"></iframe>";
			break;
	}
	return $content;
}
<?php

function formatType(array $post, $environment, $view)
{
	foreach ($post as &$postItem)
	{
		$width = $postItem["width"];
		$height = $postItem["height"];
		$ratio = $height / $width;
		$adjustedHeight = ceil($ratio * 280);
		
		switch ($postItem["type"])
		{
			case "image":
				$postItem["content"] = $view == "all" ? "<img src=\"".$environment."/images/posts/".$postItem["content"]."\" width=\"280\" height=\"".$adjustedHeight."\" />" : "<img src=\"".$environment."/images/posts/".$postItem["content"]."\" width=\"".$postItem["width"]."\" height=\"".$postItem["height"]."\" />";
				break;
			case "youtube":
				$postItem["content"] = "<iframe width=\"280\" height=\"158\" src=\"http://www.youtube.com/embed/".$postItem["content"]."?showinfo=0\" frameborder=\"0\"></iframe>";
				break;
			case "vimeo":
				$postItem["content"] = "<iframe width=\"280\" height=\"158\" src=\"http://player.vimeo.com/video/".$postItem["content"]."\" frameborder=\"0\" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>";
				break;
			case "text":
				$postItem["content"] = "<div id=\"text\">".$postItem["content"]."</div>";
				break;
			case "audio":
				$postItem["content"] = "";
				break;
		}
	}
	return $post;
}
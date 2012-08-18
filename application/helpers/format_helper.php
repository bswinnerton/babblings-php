<?php

function formatType(array $post, $environment)
{
	echo $environment;
	
	foreach ($post as &$postItem)
	{
		switch ($postItem['type'])
		{
			case "image":
				$postItem['content'] = "<img src=\"http://".$environment."/images/posts/".$postItem['content']."\" width=\"280\" />";
				break;
			case "youtube":
				$postItem['content'] = "<iframe width=\"280\" height=\"158\" src=\"http://www.youtube.com/embed/".$postItem['content']."?showinfo=0\" frameborder=\"0\"></iframe>";
				break;
			case "vimeo":
				$postItem['content'] = "<iframe width=\"280\" height=\"158\" src=\"http://player.vimeo.com/video/".$postItem['content']."\" frameborder=\"0\" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>";
				break;
			case "text":
				$postItem['content'] = "<div id=\"text\">".$postItem['content']."</div>";
				break;
			case "audio":
				$postItem['content'] = "";
				break;
		}
	}
	return $post;
}
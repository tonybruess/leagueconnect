<?php

    function bbcode($text) {
    $bbcode = array(
        //Text Apperence
        '#\[b\](.*?)\[/b\]#si' => '<b>\\1</b>',
        '#\[i\](.*?)\[/i\]#si' => '<i>\\1</i>',
        '#\[u\](.*?)\[/u\]#si' => '<u>\\1</u>',
        '#\[s\](.*?)\[/s\]#si' => '<strike>\\1</strike>',
        //Font Color
        '#\[color=(.*?)\](.*?)\[/color\]#si' => '<font color="\\1">\\2</font>',
        //Text Effects
        '#\[marquee\](.*?)\[/marquee\]#si' => '<marquee>\\1</marquee>',
        //Other
        '#\[code\](.*?)\[/code]#si' => '<dl class="codebox"><dt>Code:</dt><dd><code>\\1</code></dd></dl>',
        '#\[url=http://(.*?)\](.*?)\[/url]#si' => '<a href="\\1" target="_blank">\\2</a>',
        '#\[quote\](.*?)\[/quote\]#si' => '<blockquote><div><cite>Someone wrote:</cite>\\1</div></blockquote>',
        '#\[img\](.*?)\[/img\]#si' => '<img src="\\1">',
        '#\[email\](.*?)\[/email\]#si' => '<a href="mailto:\\1">\\1</a>'
    );
    	$newtext = preg_replace(array_keys($bbcode), array_values($bbcode), $text);
  		$newtext = nl2br($newtext,FALSE); //new line to <br>
  		return $newtext;
	}

?>
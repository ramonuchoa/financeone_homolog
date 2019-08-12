<?php
	function conv_page($content) {
		if (strpos($content, "<p><!-- conversor -->") !== FALSE) {
			$content = str_replace("<p><!-- conversor -->", conv_page_build(), $content);
		} else {
			if (strpos($content, "<!-- conversor -->") !== FALSE) {
				$content = str_replace("<!-- conversor -->", conv_page_build(), $content);
			}
		}
		return $content;
	}
?>

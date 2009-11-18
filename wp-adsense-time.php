<?php

/*
Plugin Name: Adsense Time
Plugin URI: http://github.com/fwenzel/wp-adsense-time
Description: This plugin allows you to include Google Adsense blocks in blog entries older than a specific value.
Version: 1.0
Author: Fred Wenzel
Author URI: http://fredericiana.com/
*/

/** OPTIONS */

// how old do you want the entry to be before showing ads? (days)
define('ADSENSE_TIME', 1);

/* EXAMPLE ad block: copy your ad code from Adsense here */
define('AD_BLOCK', <<<EOF
<script type="text/javascript"><!--
google_ad_client = "pub-12345";
google_ad_width = 468;
google_ad_height = 60;
google_ad_format = "468x60_as";
google_ad_type = "text";
google_ad_channel = "12345";
google_color_border = "DDDDDD";
google_color_bg = "FFFFFF";
google_color_link = "333333";
google_color_text = "777777";
google_color_url = "0066CC";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
EOF
);
/** END OF OPTIONS */

function adsense_time($posttext) { // show some ads
	global $single, $posts;
    if (!$single) return $posttext;
	if (strtotime('+'.ADSENSE_TIME.' day', get_the_time('U')) > time()) return $posttext;
    if ($posts[0]->post_status == 'static') return $posttext; // no adsense on static pages
	
	$absatz = strpos($posttext, '</p>');
	if ($absatz === false) {
		$returntext = AD_BLOCK."<br />\n\n".$posttext;
	} else {
		$absatz += 4;
		$returntext = substr($posttext, 0, $absatz);
		$returntext .= "\n".AD_BLOCK."<br />\n";
		$returntext .= substr($posttext, $absatz);
	}
	
	return $returntext;
}

add_filter('the_content', 'adsense_time');


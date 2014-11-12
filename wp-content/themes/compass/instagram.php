<?php
$rss = new DOMDocument();
// $rss->load('http://instagr.am/tags/compasscares/feed/recent.rss');
$rss->load('http://widget.websta.me/rss/tag/compasscares');
$feed = array();

foreach ($rss->getElementsByTagName('item') as $node) {
	array_push($feed, $node->getElementsByTagName('description')->item(0)->nodeValue);
}

$i = 0;

$display_count = min(count($feed), 20);

$imgpattern = '/src="(.*?)"/i';
for($x = 0; $x < $display_count; $x++) {
					//foreach ($feed as $link_code) {
	if( $i == 0 ) {
		echo '<section class="item">';

	}
	$link_code = $feed[$x];
	preg_match_all($imgpattern, $link_code, $links);
	echo '<a href="'.$links[1][1].'" rel="lightbox" class="span4 roundy"><img src="'.$links[1][1].'"/></a>';					
	if( $i == 3 || $x == $display_count - 1 ) {
		echo '</section>';
		$i = 0;

	}
	else {
		$i++;
	}
}
?>

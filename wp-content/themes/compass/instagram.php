<?php
			 	$rss = new DOMDocument();
			 	// $rss->load('http://followgram.me/lovepastry/rss');
			 	$rss->load('http://instagr.am/tags/compasscares/feed/recent.rss');
			 	$feed = array();

				foreach ($rss->getElementsByTagName('item') as $node) {
					array_push($feed, $node->getElementsByTagName('description')->item(0)->nodeValue);
				}

				$i = 0;
				$display_count = 20;

				$imgpattern = '/src="(.*?)"/i';
				for($x = 0; $x < $display_count; $x++) {
					//foreach ($feed as $link_code) {
					if( $i == 0 ) {
						echo '<div class="item">';
					}
					$link_code = $feed[$x];
					preg_match($imgpattern, $link_code, $links);
					echo '<a href="'.$links[1].'" rel="lightbox"><img src="'.$links[1].'"/></a>';					
					if( $i == 4 ) {
						echo '</div>';
						$i = 0;

					}
					else {
						$i++;
					}
				}	
			?>
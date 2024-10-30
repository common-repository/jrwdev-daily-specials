<?php
/**
 * @package JRWDEV Daily Specials
 * @since version 1.0
 */
 
 /* ------------------------------------------------------------------
 * Do Not Allow Direct Script Access
 * --------------------------------------------------------------- */
if (!function_exists ('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

function JRWDEV_dailyspecials_shortcode($atts, $content=null) {
	extract(shortcode_atts( array('title' => ''), $atts));
	$title = ( $title ) ? $title : "Daily Specials";
	if ( $title ) { echo $title; }
	$loop = new WP_Query( array( 'post_type' => 'daily_specials' ) );

	if ( $loop->have_posts() ) : 
		echo '<div class="ds-cycle">';
		while ( $loop->have_posts() ) : $loop->the_post();
				$output[] = "";
				$weekday = get_field('weekday');
				foreach($weekday as $day):
					switch ($day) {
						case "Sunday" :
							$order = 0;
							break;
						case "Monday" :
							$order = 1;
							break;
						case "Tuesday" :
							$order = 2;
							break;
						case "Wednesday" :
							$order = 3;
							break;
						case "Thursday" :
							$order = 4;
							break;
						case "Friday" :
							$order = 5;
							break;
						case "Saturday" :
							$order = 6;
							break;
						default:
							$order = 1;
					}
					$output[$order] .= "<div class='daily-specials-each'>";
					$output[$order] .= "<h1>".$day."</h1>";
					$output[$order] .= "<h3>".get_the_title()."</h3>";
					$output[$order] .= "<p>".get_field('description')."</p>";
					$output[$order]	.= "<p class='price-text'>Special Price:</p>";
					$output[$order] .= "<p class='price'>".format_as_price(get_field('price'))."</p>";
					$output[$order] .= "<div class='clear'></div>";
					$output[$order] .= "</div>";
				endforeach;
			ksort($output);
			foreach ($output as $key => $value) :
				if ($value == "") unset($output[$key]);
			endforeach;
			foreach ($output as $key => $value) :
				// if ($key == date("w")) :	
					echo $value;
				// endif;
			endforeach;
		endwhile; 
		echo "</div>";
	endif;
}
add_shortcode('daily-specials', 'JRWDEV_dailyspecials_shortcode');
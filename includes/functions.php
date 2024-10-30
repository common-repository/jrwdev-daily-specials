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

if(!function_exists('format_as_price')){
	function format_as_price($price, $symbol="$", $format=2) {
		$formatted_price = number_format(round($price,2), $format, '.', ',');
		$formatted_price = $symbol.$formatted_price;
		return $formatted_price;
	}
}
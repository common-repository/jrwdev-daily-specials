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
// The register_post_type() function is not to be used before the 'init'.
add_action( 'init', 'JRWDEV_dailyspecials_init' );

/* Here's how to create your customized labels */
function JRWDEV_dailyspecials_init() {

	$specials_labels = array(
		'name' => _x( 'Daily Specials', 'post type general name' ), // Tip: _x('') is used for localization
		'singular_name' => _x( 'Daily Specials', 'post type singular name' ),
		'add_new' => _x( 'Add New', 'daily special' ),
		'add_new_item' => __( 'Add New Daily Special' ),
		'edit_item' => __( 'Edit Daily Special' ),
		'new_item' => __( 'New Daily Special' ),
		'view_item' => __( 'View Daily Special' ),
		'search_items' => __( 'Search Daily Special' ),
		'not_found' =>  __( 'No daily specials found' ),
		'not_found_in_trash' => __( 'No daily specials found in Trash' ),
		'parent_item_colon' => ''
	);
	// Create an array for the $args
	$specials_args = array( 'labels' => $specials_labels, /* NOTICE: the $labels variable is used here... */
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => true,
		'has_archive' => 'daily-specials',
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'page-attributes' )
	); 
	register_post_type( 'daily_specials', $specials_args ); /* Register it and move on */

	// Add new taxonomy, hierarchical
	$specials_taxonomy_labels = array(
		'name' => _x( 'Special Categories', 'taxonomy general name' ),
		'singular_name' => _x( 'Category', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Categories' ),
		'popular_items' => __( 'Popular Categories' ),
		'all_items' => __( 'All Categories' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit Category' ),
		'update_item' => __( 'Update Category' ),
		'add_new_item' => __( 'Add New Category' ),
		'new_item_name' => __( 'New Category Name' ),
		'separate_items_with_commas' => __( 'Separate categories with commas' ),
		'add_or_remove_items' => __( 'Add or remove categories' ),
		'choose_from_most_used' => __( 'Choose from the most used categories' )
	);
	register_taxonomy( 'daily-special-category', 'daily_specials', array(
		'hierarchical' => true,
		'labels' => $specials_taxonomy_labels, /* NOTICE: the $labels variable here */
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'daily-special-category' ),
	));
}

//add a custom message to the post message function
add_filter('post_updated_messages', 'daily_specials_updated_messages');
function daily_specials_updated_messages( $messages ) {
	global $post;
	$post_ID = $post->ID;

	$messages['daily_specials'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Daily Special updated. <a href="%s">View Daily Special</a>'), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Daily Special updated.'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Daily Special restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Daily Special published. <a href="%s">View Daily Special</a>'), esc_url( get_permalink($post_ID) ) ),
		7 => __('Daily Special saved.'),
		8 => sprintf( __('Daily Special submitted. <a target="_blank" href="%s">Preview Daily Special</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Daily Special scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Daily Special</a>'),
		  // translators: Publish box date format, see http://php.net/date
		  date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Daily Special draft updated. <a target="_blank" href="%s">Preview Daily Special</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);

	return $messages;
}

// Add some custom columns to our specials post type to make it easier to see the details at a glance 
add_filter( 'manage_daily_specials_posts_columns', 'daily_specials_columns_head' );
add_action( 'manage_daily_specials_posts_custom_column', 'daily_specials_columns_content', 10, 2 );
function daily_specials_columns_head( $columns ) {
 
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Special' ),
		'special-day' => __( 'Weekday' ),
		'special-price' => __( 'Sale Price/Discount' ),
		'category' => __( 'Category' ),
		'order' => __( 'Display Order' ),
		'date' => __( 'Date' )
	);
 
	return $columns;
}

function daily_specials_columns_content( $column, $post_id ) {
	global $post;
 
	switch( $column ) {
 
		/* If displaying the 'weekday' column. */
		case 'special-day' :
 
			/* Get the post meta. */
			$weekday = get_post_meta( $post_id, 'weekday', true );
 
			/* If no duration is found, output a default message. */
			if ( empty( $weekday ) )
				echo __( 'Unknown' );
 
			/* If there is a duration, append 'minutes' to the text string. */
			else
				echo join( ', ', $weekday );
 
			break;
 
 		/* If displaying the 'price/discout' column. */
		case 'special-price' :
 
			/* Get the post meta. */
			$price = get_post_meta( $post_id, 'price', true );
 
			/* If no duration is found, output a default message. */
			if ( empty( $price ) )
				echo __( 'N/A' );
 
			/* If there is a duration, append 'minutes' to the text string. */
			else
				echo( $price );
 
			break;
 
		/* If displaying the 'category' column. */
		case 'category' :
 
			/* Get the genres for the post. */
			$terms = get_the_terms( $post_id, 'daily-special-category' );
 
			/* If terms were found. */
			if ( !empty( $terms ) ) {
 
				$out = array();
 
				/* Loop through each term, linking to the 'edit posts' page for the specific term. */
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'daily-special-category' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'daily-special-category', 'display' ) )
					);
				}
 
				/* Join the terms, separating them with a comma. */
				echo join( ', ', $out );
			}
 
			/* If no terms were found, output a default message. */
			else {
				_e( 'No Categories' );
			}
 
			break;

		/* If displaying the 'order' column. */
		case 'order' :
 
			/* Get the post meta. */
			$order = $post->menu_order;
 
			/* If no duration is found, output a default message. */
			if ( empty( $order ) )
				echo __( 'Unknown' );
 
			/* If there is a duration, append 'minutes' to the text string. */
			else
				echo $order;
 
			break;
 
 
		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}
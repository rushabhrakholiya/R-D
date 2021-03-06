<?php
/*
Plugin Name: Deep Thoughts Functionality
Description: API Modifications for my Deep Thoughts React Native app.
Author: Jeffrey Gould
Version: 0.1
Author URI: http://jrgould.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


add_action( 'rest_api_init', 'dt_register_api_hooks' );
function dt_register_api_hooks() {

	// Add the plaintext content to posts
	register_api_field(
		'post',
		'plaintext',
		array(
			'get_callback'    => 'dt_return_plaintext_content',
		)
	);

	// Add deep-thoughts/v1/get-all-post-ids route
	register_rest_route( 'deep-thoughts/v1', '/get-all-post-ids/', array(
		'methods' => 'GET',
		'callback' => 'dt_get_all_post_ids',
	) );


}

// Return plaintext content for posts
function dt_return_plaintext_content( $object, $field_name, $request ) {
	return strip_tags( html_entity_decode( $object['content']['rendered'] ) );
}


// Return all post IDs
function dt_get_all_post_ids() {
	if ( false === ( $all_post_ids = get_transient( 'dt_all_post_ids' ) ) ) {
	    $all_post_ids = get_posts( array(
		    'numberposts' => -1,
		    'post_type'   => 'post',
		    'fields'      => 'ids',
	    ) );
		// cache for 2 hours
		set_transient( 'dt_all_post_ids', $all_post_ids, 60*60*2 );
	}

	return $all_post_ids;
}
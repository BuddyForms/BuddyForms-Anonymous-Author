<?php

/**
 * Registers the 'authors' taxonomy for users.  This is a taxonomy for the 'user' object type rather than a
 * post being the object type.
 */
add_action( 'init', 'my_register_user_taxonomy', 0 );
function my_register_user_taxonomy() {

	$buddyforms = get_option( 'buddyforms_forms' );

	$objects = array();
	$objects[] = 'user';

	if( isset( $buddyforms ) ){
		foreach (  $buddyforms as $form_slug => $buddyform){
			if( isset( $buddyform['post_type'] ) && $buddyform['post_type'] != 'bf_submissions' ){
				$objects[] = $buddyform['post_type'];
			}
		}
	}

	register_taxonomy(
		'bf_a_author',
		$objects,
		array(
			'show_ui' => false,
			'public' => false,
		)
	);

}

add_action( 'buddyforms_process_submission_end', 'my_save_user_profession_terms', 10 );
function my_save_user_profession_terms( $args) {

	$current_user_id = get_current_user_id();
	extract($args);

	update_post_meta( $post_id, 'bf_anonymous_author', $current_user_id);

	wp_set_object_terms( $current_user_id, array( $post_id ), 'bf_a_author', false);

	clean_object_term_cache( $current_user_id, 'bf_a_author' );

}
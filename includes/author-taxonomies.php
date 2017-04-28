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
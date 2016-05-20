<?php

// Change the author display name
function bf_anonymous_the_author( $authordata ) {
	global $post;

	if ( is_admin() ) {
		return $authordata;
	}

	$annonymus_author_id = get_post_meta( $post->ID, 'anonymousauthor' );

	if ( ! isset( $annonymus_author_id[0][0] ) ) {
		return $authordata;
	}


	$user_info = get_userdata( $annonymus_author_id[0][0] );

	return $user_info->display_name;
}

add_filter( 'the_author', 'bf_anonymous_the_author', 10, 1 );


// Change the author link
function bf_anonymous_the_author_link( $link, $author_id, $author_nicename ) {
	global $post;

	if ( is_admin() ) {
		return $link;
	}

	$annonymus_author_id = get_post_meta( $post->ID, 'anonymousauthor' );

	if ( ! isset( $annonymus_author_id[0][0] ) ) {
		return $link;
	}


	remove_filter( 'author_link', 'bf_anonymous_the_author_link', 10, 3 );
	$link = get_author_posts_url( $annonymus_author_id[0][0] );
	add_filter( 'author_link', 'bf_anonymous_the_author_link', 10, 3 );

	return $link;
}

add_filter( 'author_link', 'bf_anonymous_the_author_link', 10, 3 );


// Change the author avatar
function bf_anonymous_get_avatar_data( $args, $id_or_email ) {
	global $post;

	if ( is_admin() ) {
		return $args;
	}

	$annonymus_author_id = get_post_meta( $post->ID, 'anonymousauthor' );

	if ( ! isset( $annonymus_author_id[0][0] ) ) {
		return $args;
	}

	remove_filter( 'get_avatar_data', 'bf_anonymous_get_avatar_data', 10, 2 );
	$args = get_avatar_data( $annonymus_author_id[0][0] );
	add_filter( 'get_avatar_data', 'bf_anonymous_get_avatar_data', 10, 2 );

	return $args;

}
add_filter( 'get_avatar_data', 'bf_anonymous_get_avatar_data', 10, 2 );

function buddyforms_anonymous_pre_get_posts($query) {

	if ( !is_author() )
		return;

	if ( !$query->is_main_query() )
		return;

	$anonymous_author_name = get_option( 'buddyforms_anonymous_author_settings' );

	$args = array(
		'meta_query' => array(
			array(
				'key' => 'anonymousauthor',
			)
		)
	);

	// Get all anonymous author posts
	$anonymous_author_posts = new WP_Query( $args );

	// We need an array of post id's to exclude this posts from the main query
	$anonymous_author_posts_array = wp_list_pluck( $anonymous_author_posts->posts, 'ID' );


	if($anonymous_author_name != $query->query['author_name'] ) {

		// Exclude anonymous posts
		$query->set( 'post__not_in', $anonymous_author_posts_array );

	} else {

		global $wp_query;

		wp_reset_query();

		$args = array(
			'author_name' => $anonymous_author_name,
		);

		// Get all anonymous author posts
		$wp_query2 = new WP_Query( $args );

		// start putting the contents in the new object
		if(is_array($anonymous_author_posts->posts) && is_array($wp_query2->posts)){
			$result = new WP_Query();
			$result->posts = array_merge( $anonymous_author_posts->posts, $wp_query2->posts );
			$result->post_count = count( $result->posts );
			$wp_query = $result;
		}
	}

	//we remove the actions hooked on the '__after_loop' (post navigation)
	remove_all_actions ( '__after_loop');
}
add_action('pre_get_posts','buddyforms_anonymous_pre_get_posts');
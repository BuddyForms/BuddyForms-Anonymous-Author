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


// Add the form element to the form elements sidebar
function bf_anonymous_add_form_element_to_sidebar( $sidebar_elements ) {
	global $post;

	if ( $post->post_type != 'buddyforms' ) {
		return;
	}

	$sidebar_elements[] = new Element_HTML( '<p><a href="#" data-fieldtype="anonymousauthor" data-unique="unique" class="bf_add_element_action">Anonymous Author</a></p>' );

	return $sidebar_elements;
}
add_filter( 'buddyforms_add_form_element_to_sidebar', 'bf_anonymous_add_form_element_to_sidebar', 1, 2 );

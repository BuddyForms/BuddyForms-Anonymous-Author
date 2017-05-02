<?php

//
// Add the anonymous author id as post author if anonymous is set
//
add_filter( 'buddyforms_the_author_id', 'buddyforms_anonymous_the_author_id',10 , 2 );
function buddyforms_anonymous_the_author_id($author_id, $form_slug){

	// Check if anonymous author is set
	if( ! isset( $_POST['anonymousauthor'] ) ){
		return $author_id;
	}

	// Check if this form does have an anonymous author form element and return the anonymous author id from the form element.
	// We not want to make it possible to manipulate this data and not grab it from the form directly.
	$anonymousauthor = buddyforms_get_form_field_by_slug($form_slug, 'anonymousauthor');
	if( isset( $anonymousauthor['author_id'] ) ){
		return $anonymousauthor['author_id'];
	}

	return $author_id;

}

//
// Add the anonymous author to the post loop query if the user visit his own posts
//
add_filter('buddyforms_the_lp_query', 'buddyforms_anonymous_the_lp_query');
function buddyforms_anonymous_the_lp_query( $the_lp_query ){

	$form_slug = $the_lp_query->query_vars['form_slug'];
	$anonymousauthor = buddyforms_get_form_field_by_slug($form_slug, 'anonymousauthor');

	if( ! isset( $anonymousauthor ) ){
		return $the_lp_query;
	}

	//
	// Get all posts from the anonymous author with post meta bf_anonymous_author set to the current user
	//
	$query_args['author'] =  $anonymousauthor['author_id'];
	$query_args['meta_query'] = array(
		array(
			'key'     => 'bf_anonymous_author',
			'value'   => get_current_user_id(),
		)
	);
	$authorposts = get_posts($query_args);

	//combine queries
	$mergedposts = array_merge( $the_lp_query->posts, $authorposts );

	//create a new query only of the post ids
	$postids = array();
	foreach( $mergedposts as $item ) {
		$postids[]=$item->ID;
	}

	//remove duplicate post ids
	$uniqueposts = array_unique($postids);

	//new query of only the unique post ids on the merged queries from above
	$posts = get_posts(array(
		'post__in' => $uniqueposts,
	));

	$the_lp_query->post_count = count( $posts );
	$the_lp_query->posts = $posts;

	return $the_lp_query;
}

//
// Manage edit and delete for anonymous posts
//
add_filter('buddyforms_user_can_edit', 'buddyforms_anonymous_user_can_edit', 10, 3);
function buddyforms_anonymous_user_can_edit($user_can_edit, $form_slug, $post_id ){

	$bf_anonymous_author = get_post_meta( $post_id, 'bf_anonymous_author', true );

	if( isset( $bf_anonymous_author ) && $bf_anonymous_author == get_current_user_id() ){
		$user_can_edit = true;
	}

	return $user_can_edit;

}

//
// Process the post and save the post meta and taxonomy
//
add_action( 'buddyforms_process_submission_end', 'buddyforms_save_user_profession_terms', 10 );
function buddyforms_save_user_profession_terms( $args ) {

	// Check if anonymous author is set
	if( ! isset( $_POST['anonymousauthor'] ) ){
		return;
	}

	// Extract the arguments
	extract($args);

	// Make sure this form does have an anonymous author form element
	$anonymousauthor = buddyforms_get_form_field_by_slug($form_slug, 'anonymousauthor');
	if( ! $anonymousauthor ){
		return;
	}

	// Get the current user id to save the real author as post meta
	$current_user_id = get_current_user_id();
	update_post_meta( $post_id, 'bf_anonymous_author', $current_user_id);

}
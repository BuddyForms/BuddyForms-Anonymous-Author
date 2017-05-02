<?php

add_filter( 'buddyforms_the_author_id', 'buddyforms_anonymous_the_author_id',10 , 2 );
function buddyforms_anonymous_the_author_id($author_id, $form_slug){

	if( ! isset( $_POST['anonymousauthor'] ) ){
		return $author_id;
	}

	$anonymousauthor = buddyforms_get_form_field_by_slug($form_slug, 'anonymousauthor');

	if( isset( $anonymousauthor['author_id'] ) ){
		return $anonymousauthor['author_id'];
	}

	return $author_id;

}

add_filter('buddyforms_the_lp_query', 'buddyforms_anonymous_the_lp_query');
function buddyforms_anonymous_the_lp_query( $the_lp_query ){


	$form_slug = $the_lp_query->query_vars['form_slug'];
	$post_type = $the_lp_query->query_vars['post_type'];
	$anonymousauthor = buddyforms_get_form_field_by_slug($form_slug, 'anonymousauthor');


	$query_args['author'] =  $anonymousauthor['author_id'];
	$query_args['meta_query'] = array(
		array(
			'key'     => 'bf_anonymous_author',
			'value'   => get_current_user_id(),
		)
	);

	$authorposts = get_posts($query_args);


	$mergedposts = array_merge( $the_lp_query->posts, $authorposts ); //combine queries

	$postids = array();
	foreach( $mergedposts as $item ) {
		$postids[]=$item->ID; //create a new query only of the post ids
	}
	$uniqueposts = array_unique($postids); //remove duplicate post ids

	$posts = get_posts(array(
		'post__in' => $uniqueposts, //new query of only the unique post ids on the merged queries from above
	));


	$the_lp_query->post_count = count( $posts );
	$the_lp_query->posts = $posts;

	return $the_lp_query;
}


add_filter('buddyforms_user_can_edit', 'buddyforms_anonymous_user_can_edit', 10, 3);
function buddyforms_anonymous_user_can_edit($user_can_edit, $form_slug, $post_id ){

	return true;

}
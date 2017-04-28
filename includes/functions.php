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

add_filter('buddyforms_the_loop_author_id', 'buddyforms_anonymous_the_loop_author_id',10 ,2 );
function buddyforms_anonymous_the_loop_author_id($author_id, $form_slug){

	$anonymousauthor = buddyforms_get_form_field_by_slug($form_slug, 'anonymousauthor');

	if( isset( $anonymousauthor['author_id'] ) ){
		return $author_id . ', ' . $anonymousauthor['author_id'];
	}

	return $author_id;
}

add_filter('buddyforms_user_can_edit', 'buddyforms_anonymous_user_can_edit', 10, 3);
function buddyforms_anonymous_user_can_edit($user_can_edit, $form_slug, $post_id ){

	return true;

}
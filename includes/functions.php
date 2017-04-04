<?php

add_filter( 'buddyforms_the_author_id', 'buddyforms_anonymous_the_author_id',10 ,2 );
function buddyforms_anonymous_the_author_id($forms_slug, $post_id){

	return 2;
}

add_filter('buddyforms_the_loop_author_id', 'buddyforms_anonymous_the_loop_author_id',10 ,1 );
function buddyforms_anonymous_the_loop_author_id($form_slug){

	return 2;
}



add_filter('buddyforms_user_can_edit', 'buddyforms_anonymous_user_can_edit');

function buddyforms_anonymous_user_can_edit(){

	return true;
}
<?php

/*
 * Create the new form builder form element
 * If you click on the sidebar form elment link this function will create the form builder element.
 *
 * Use buddyforms_form_element_add_field filter to add fields to the form element group
 * $form_fields    --> All form element fields
 * $form_slug      --> The form slug
 * $field_type     --> The field type you have defined before in the link
 * $field_id       --> A uique field ID automatically created
 */

function bf_anonymous_form_builder_form_element( $form_fields, $form_slug, $field_type, $field_id ) {
	global $post;

	// Get the form options
	$buddyform = get_post_meta( $post->ID, '_buddyforms_options', true );

	// Only get in action if the new form element type is processed.
	// I use a switch statement because you can have many form elements.
	switch ( $field_type ) {
		// Make sure we are on the correct form element type
		case 'anonymousauthor':

			unset( $form_fields );
			$name                           = isset( $buddyform['form_fields'][ $field_id ]['name'] ) ? stripcslashes( $buddyform['form_fields'][ $field_id ]['name'] ) : '';
			$form_fields['general']['name'] = new Element_Textbox( '<b>' . __( 'Name', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][name]", array(
				'class'    => "use_as_slug",
				'data'     => $field_id,
				'value'    => $name,
				'required' => 1
			) );
			$form_fields['general']['slug'] = new Element_Hidden( "buddyforms_options[form_fields][" . $field_id . "][slug]", 'anonymousauthor' );
			$form_fields['general']['type'] = new Element_Hidden( "buddyforms_options[form_fields][" . $field_id . "][type]", $field_type );

			// Create a variable for your field value
			$anonymousauthor = false;

			// Check if your field exists and assign the value to your variable
			if ( isset( $buddyform['form_fields'][ $field_id ]['anonymousauthor'] ) ) {
				$anonymousauthor = $buddyform['form_fields'][ $field_id ]['anonymousauthor'];
			}

			$form_fields['general']['anonymousauthor'] = new Element_Textbox(
				__( 'The Anonymous Author ID', 'buddyforms' ), // The field label
				"buddyforms_options[form_fields][" . $field_id . "][anonymousauthor]", // Field option name
				array(
					'value' => $anonymousauthor, // The value
					'class' => '', // Additional class name
					'id'    => $field_id // The field id
				)
			);

			// Add more form elements

			break;
	}

	// Return the form fields
	return $form_fields;
}
add_filter( 'buddyforms_form_element_add_field', 'bf_anonymous_form_builder_form_element', 1, 5 );

/*
 * Display the new form element in the frontend form
 *
 */
function bf_anonymous_create_frontend_element( $form, $form_args ) {

	// Extract the form args
	extract( $form_args );

	// Make sure the form element has a type value
	if ( ! isset( $customfield['type'] ) ) {
		return $form;
	}

	// Switch statement to find the form element type we'd like to display
	switch ( $customfield['type'] ) {
		case 'anonymousauthor':


			// Add the checkbox to select anonymous author
			$form->addElement(
				new Element_Checkbox(
					$customfield['name'],
					$customfield['slug'],
					array(
						$customfield['anonymousauthor'] => $customfield['name'] ),
					array(  'value'   => $customfield_val,
					        'class'   => '',
					        'data-id' => $customfield['slug']
					)
				)
			);

			break;
	}

	// Return the form element
	return $form;
}

add_filter( 'buddyforms_create_edit_form_display_element', 'bf_anonymous_create_frontend_element', 1, 2 );
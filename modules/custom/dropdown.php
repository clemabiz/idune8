function shift8_dependent_dropdown($form, &$form_state) {
    // This is where you will build your form options and call other functions to generate options

    
}

function shift8_dependent_dropdown_reset($form, $form_state) {
    // This function will be called when the end-user wants to "Reset" the form and return all the selection options to default
}

function shift8_dependent_dropdown_validate($form, &$form_state) {
    // This function is where you validate the input. This acts as a validation function after the user hits submit and can block data from being passed to the submit functions if it is invalid.
}

function shift8_dependent_dropdown_submit($form, &$form_state) {
    // This is the submit function and is responsible for taking all the input data and does whatever you want with it. You can build a dynamic query and display search results in this function, for exmaple
}






        $options_first = _shift8_get_first_dropdown_options();
        $form['dropdown_first'] = array(
	        '#type' => 'select',
	        '#title' => 'Category',
	        '#prefix' => '<div id="dropdown-first-replace" class="form-select">',
	        '#suffix' => '</div>',
	        '#options' => $options_first,
	        '#default_value' => '-- Select Category --',
	        '#ajax' => array(
	                'callback' => 'shift8_dependent_dropdown_callback',
	                'wrapper' => 'dropdown-second-replace',
	                ),
	        );

        // Second option
        $form['dropdown_second'] = array(
            '#type' => 'select',
            '#title' => 'Second Option',
            '#prefix' => '<div id="dropdown-second-replace" class="form-select">',
            '#suffix' => '</div>',
            '#options' => isset($form_state['values']['dropdown_first']) ? _shift8_get_second_dropdown_options($selected) : 0,
            '#default_value' => '',
            '#ajax' => array(
                    'callback' => 'shift8_dependent_dropdown_callback_second',
                    'wrapper' => 'dropdown-third-replace',
                    ),
            );

	function shift8_get_second_dropdown_query($firstchoice) {
	    if ($_SESSION['first_selected']) {
	        $query = db_select('taxonomy_term_data');
	        $subquery = db_select('field_data_field_custom');
	        $subquery->fields('field_data_field_custom', array('entity_id',));
	        $subquery->condition('field_custom_tid', $first_selected_option, 'IN');
	        $query->fields('taxonomy_term_data', array('tid', 'name', 'vid',));
	        $query->condition('tid', $subquery, 'IN');
	        $query->orderBy('name', 'ASC');
	        $results = $query->execute();
	        $result_name = array();
	        $result_name[] = '-- Select Third Option --';
	        foreach ($results as $result) {
	            $result_name[] = $result->name;
	        }
	        return $result_name;
	    }            
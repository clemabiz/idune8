<?php


    function mymodule_country_state_city($form, & $form_state, $no_js_use = FALSE) {
        $options_first = _profile_myprofile_get_first_dropdown_options();
        if (isset($form_state['values']['state_of_residence'])) {
            $selected = isset($form_state['values']['country_of_residence']) ? $form_state['values']['country_of_residence'] : key($options_first);
        } else if ($profile_country_residence_country_id > 0) {
            $selected = $profile_country_residence_country_id; 
            // $profile_country_residence_country_id is the default value 
        }
        //$options_second = _ajax_example_get_second_dropdown_options($selected);

        $form['country_of_residence'] = array(
            '#type' => 'select',
            '#title' => 'Country of Residence',
            '#options' => $options_first,
            '#default_value' => $selected,
            '#required' => TRUE,
        // Bind an ajax callback to the change event (which is the default for the
        // select form type) of the first dropdown. It will replace the second
        // dropdown when rebuilt
        '#ajax' => array(
        // When 'event' occurs, Drupal will perform an ajax request in the
        // background. Usually the default value is sufficient (eg. change for
        // select elements), but valid values include any jQuery event,
        // most notably 'mousedown', 'blur', and 'submit'.
            'event' => 'change',
            'callback' => 'ajax_example_dependent_dropdown_callback',
            'wrapper' => 'dropdown-second-replace', ), );
        if (isset(
        $form_state['values']['state_of_residence'])) {
            $selected_state = isset($form_state['values']['state_of_residence']) ? $form_state['values']['state_of_residence'] : key($options);
        } else if ($profile_country_residence_state_id > 0) {
            $selected_state = $profile_country_residence_state_id;
        } else {
            $selected_state = 0; // 0 is default value
        }

        $form['state_of_residence'] = array(
            '#type' => 'select',
            '#title' => 'State of Residence',
            '#required' => TRUE,
        // The entire enclosing div created here gets replaced when country_of_residence
        // is changed.
            '#prefix' => '<div id="dropdown-second-replace">',
            '#suffix' => '</div>',
        //    // when the form is rebuilt during ajax processing, the $selected variable
        //    // will now have the new value and so the options will change
            '#options' => _ajax_example_get_second_dropdown_options($selected),
            '#default_value' => $selected_state,
            '#ajax' => array(
        // When 'event' occurs, Drupal will perform an ajax request in the
        // background. Usually the default value is sufficient (eg. change for
        // select elements), but valid values include any jQuery event,
        // most notably 'mousedown', 'blur', and 'submit'.
            'event' => 'change',
            'callback' => 'ajax_example_dependent_dropdown1_callback',
            'wrapper' => 'dropdown-third-replace', ), );

        $form['city_of_residence'] = array(
            '#type' => 'select',
            '#title' => 'City of Residence',
            '#required' => TRUE,
        //    // The entire enclosing div created here gets replaced when country_of_residence
        //    // is changed.
            '#prefix' => '<div id="dropdown-third-replace">',
            '#suffix' => '</div>',
        //    // when the form is rebuilt during ajax processing, the $selected variable
        //    // will now have the new value and so the options will change
            '#options' => _ajax_example_get_third_dropdown_options($selected_state),
            '#default_value' => $profile_country_residence_city_id, );
        return $form;
    }

    function ajax_example_dependent_dropdown_callback($form, $form_state) {
        $commands = array();
        $commands[] = ajax_command_replace("#dropdown-second-replace", render($form['state_of_residence']));
        $commands[] = ajax_command_replace("#dropdown-third-replace", render($form['city_of_residence']));
        return array('#type' => 'ajax', '#commands' => $commands);
        //return $form['state_of_residence'];
    }

    function ajax_example_dependent_dropdown1_callback($form, $form_state) {
        return $form['city_of_residence'];
    }
    /**
     * Helper function to populate the first dropdown. This would normally be
     * pulling data from the database.
     *
     * @return array of options
     */
    function _profile_myprofile_get_first_dropdown_options() {
        $country_query_result1 = db_query("SELECT country_id,country_name FROM country ORDER BY country_name ASC");
        $country_array = array();
        foreach($country_query_result1 as $row1) {
            $country_array[$row1 -> country_id] = $row1 -> country_name;
        }
        return $country_array;
    }
    /**
     * Helper function to populate the second dropdown. This would normally be
     * pulling data from the database.
     *
     * @param key. This will determine which set of options is returned.
     *
     * @return array of options
     */
    function _ajax_example_get_second_dropdown_options($key = '') {
        ///$i = 1;
        $options = array();
        $country_query_result1 = db_query("SELECT country_id FROM country ORDER BY country_id ASC");

        foreach($country_query_result1 as $row1) {
            $country_id = $row1 -> country_id;
            //    $country_id = $key;

            $state_query_result2 = db_query("SELECT state_id,state_name FROM state WHERE country_id = :country_id", array(':country_id' => $country_id));
            $state_array = array();
            $state_array[0] = '-- Select State --';
            foreach($state_query_result2 as $row2) {
                $state_array[$row2 -> state_id] = $row2 -> state_name;
            }
            $options[$country_id] = $state_array;
        }
        if (isset($options[$key])) {
            return $options[$key];
        } else {
            $options1 = array('0' => '-- Select State --');
            return $options1;
        }
    }

    function
     _ajax_example_get_third_dropdown_options($key = '') {
        ///$i = 1;
        $options_second = array();
        $country_query_result1 = db_query("SELECT country_id FROM country ORDER BY country_id ASC");
        $state_array1 = array();
        foreach($country_query_result1 as $row1) {
            $country_id = $row1 -> country_id;

            $state_query_result2 = db_query("SELECT state_id FROM state WHERE country_id = :country_id", array(':country_id' => $country_id));
            $state_array = array();
            foreach($state_query_result2 as $row2) {
                $state_id = $row2 -> state_id;
                $state_query_result3 = db_query("SELECT city_id,city_name FROM city WHERE state_id = :state_id", array(':state_id' => $state_id));
                $state_array3 = array();
                $state_array3[0] = '-- Select City --';
                foreach($state_query_result3 as $row3) {
                    $state_array3[$row3 -> city_id] = $row3 -> city_name;
                }
                $options_second[$state_id] = $state_array3;
            }
        }


        if (isset($options_second[$key])) {
            return $options_second[$key];
        } else {
            $options1 = array('0' => '-- Select City --');
            return $options1;
        }
    }
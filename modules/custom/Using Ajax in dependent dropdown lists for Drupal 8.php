<?php


// Suppose you have two drop-down lists, you need to changed in the second value depending on the choice of the first. You need to first add the attribute list #ajax. Attribute #ajax accepts an array as the value. The array contain keys 'callback' and 'wrapper'. Parameter 'callback' may include: array, the first value is object, and the second value - the name of the function of the object

// 'callback' => [$this, 'changeOptionsAjax']
// way to function in the class

// 'callback' => '\Drupal\helloworld\Form\helloworldForm::changeOptionsAjax'
// or function name with two colons before, if the function belongs to this class

// 'callback' => '::changeOptionsAjax'
// Any of these ways will work. Next in parameter 'wrapper ' need specify 'id' of form element, in which we need to change the value

// 'wrapper' => 'second_field_wrapper' 
// Then wrap the second element in our div with attribute 'id', that we wrote a parameter 'wrapper'
 

// '#prefix' => '<div id="second_field_wrapper">', '#suffix' => '</div>'
// Then proceed to the creation of the callback function, which will return the item to change. In our case the second element

// return $form['second_field'];
// We also need to write a function, which will choose values for the second element depending on the value of the first selected item. For this we will forward variable $form_state in function, from this variable we will get value of the first element and return an array of values that will be in the second element. Thats all. Now choosing the first dropdown list any value - in the second list using AJAX will be changed value.


/**
 * @file
 * Contains \Drupal\helloworld\Form\helloworldForm.
 */

namespace Drupal\helloworld\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class helloworldForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'helloworld_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
	$options = [
	  'node' => 'Node',
	  'user' => 'User'
	];
	$form['first_field'] = [
      '#type' => 'select',
      '#title' => t('First field'),
      '#options' => $options,
	  '#ajax' => [
		'callback' => [$this, 'changeOptionsAjax'],
		// 'callback' => '\Drupal\helloworld\Form\helloworldForm::changeOptionsAjax',
		// 'callback' => '::changeOptionsAjax',
		'wrapper' => 'second_field_wrapper',
	  ],
    ];
    $form['second_field'] = [
      '#type' => 'select',
	  '#title' => t('Second field'),
      '#options' => $this->getOptions($form_state),
      '#prefix' => '<div id="second_field_wrapper">',
      '#suffix' => '</div>',
    ];
    
	$form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Submit',
    ];
    return $form;
  }
  
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {}

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {}
  
  /**
   * Ajax callback to change options for second field.
   */
  public function changeOptionsAjax(array &$form, FormStateInterface $form_state) {
	return $form['second_field'];
  }

  /**
   * Get options for second field.
   */
  public function getOptions(FormStateInterface $form_state) {
	if ($form_state->getValue('first_field') == 'user') {
      $options = [
	    'admin' => 'Admin',
		'manager' => 'Manager'
      ];
    }
    else {
      $options = [
	    'article' => 'Article',
		'basic_page' => 'Basic page'
      ];
    }
	return $options;
  }
  
}
?>
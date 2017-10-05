<?php

namespace Drupal\vehicle\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Language\Language;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;

/**
 * Form controller for the vehicle entity edit forms.
 *
 * @ingroup vehicle
 */
class VehicleForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\vehicle\Entity\Vehicle */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    $form['langcode'] = array(
      '#title' => $this->t('Language'),
      '#type' => 'language_select',
      '#default_value' => $entity->getUntranslated()->language()->getId(),
      '#languages' => Language::STATE_ALL,
    );

    $form['candidate_dob'] = array (
      '#type' => 'date',
      '#title' => t('DOB'),
      '#required' => TRUE,
    );





/*
    #--------------------------------------
    # define your drupal 'fieldset' element
    #--------------------------------------
    $form['name'] = array(
      '#type' => 'fieldset',
      '#title' => t(''),
      '#prefix' => '<div class="col1">',
      '#suffix' => '</div>',
    );

    #----------------------------------------
    # define another textfield as a sub-array
    # of your fieldset elements
    #----------------------------------------
    $form['name']['title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#size' => 40,
      '#required' => TRUE, // Added
      '#description' => t('Enter your Title!'),
//      '#prefix' => '<div class="col1">',
//      '#suffix' => '</div>',
    );

    #--------------------------------------------------------
    # create a form element as a sub-array of your
    # top-level fieldset element; notice that this element is
    # declared as "$form['name']['firstname']" -- a sub-array
    # of the fieldset element.
    #--------------------------------------------------------
    $form['name']['make'] = array(
      '#type' => 'textfield',
      '#title' => t('Make'),
      '#size' => 40,
      '#required' => TRUE, // Added
      '#description' => t('Enter your Make!'),
//      '#prefix' => '<div class="col1">',
//      '#suffix' => '</div>',
    );
    
    #----------------------------------------
    # define another textfield as a sub-array
    # of your fieldset elements
    #----------------------------------------
    $form['name']['model'] = array(
      '#type' => 'textfield',
      '#title' => t('Model'),
      '#size' => 40,
      '#required' => TRUE, // Added
      '#description' => t('Enter your Model!'),
//      '#prefix' => '<div class="col1">',
//      '#suffix' => '</div>',
    );

*/

  $options = array(); 
  
  //$options = getTaxOptions($tax);
  
/*  $options = [
    'node' => 'Node',
    'user' => 'User'
  ];
*/
 
$tax = "make";  
$options = array();
  $terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree($tax, $parent = 0, $max_depth = NULL, $load_entities = FALSE);
    foreach ($terms as $term) {
      $options[] = $term->name;
    }  
 

/*
  $form['category_default'] = array(
    '#type' => 'select',
    '#title' => t('Default category'),
    '#options' => taxonomy_allowed_values(field_info_field('make')),
    '#description' => t('The selected category will be shown by default on listing pages.')
  );

*/
/*    // First option
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
*/
    $form['first_field'] = array(
      '#type' => 'select',
      '#title' => t('First field'),
      '#options' => $options,
      '#ajax' => array(
        'callback' => [$this, 'changeOptionsAjax'],
        // 'callback' => '\Drupal\helloworld\Form\helloworldForm::changeOptionsAjax',
        // 'callback' => '::changeOptionsAjax',
        'wrapper' => 'second_field_wrapper',
      ),
    );

    $form['second_field'] = array(
      '#type' => 'select',
    '#title' => t('Second field'),
      '#options' => $this->getOptions($form_state),
      '#prefix' => '<div id="second_field_wrapper">',
      '#suffix' => '</div>',
    );

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    $values = $form_state->getValues();

    // Require non-empty FID.
    $fid = trim($values['fid']);
    if (empty($fid)) {
      $form_state->setErrorByName('fid', $this->t('Photo must not be empty'));
    }
 
    if (strlen($form_state->getValue('candidate_number')) < 10) {
      $form_state->setErrorByName('candidate_number', $this->t('Mobile number is too short.'));
    }





  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $form_state->setRedirect('entity.vehicle.collection');
    $entity = $this->getEntity();
    $entity->save();
  }
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
  
/*//    if ($form_state->getValue('first_field') == 'user') {
//    if ($form_state->getValue('first_field') == 'benz') {
      if ($form_state->isValueEmpty('first_field')) {
//      if ($form_state->hasValue('first_field')) {
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
*/
/*
    $tax="";

    if ($form_state->hasValue('first_field')) {
//    if ($form_state->getValue('first_field') == "toyota") {
      $tax="toyota";
    elseif ($form_state->getValue('first_field') == "nissan")  {
      $tax="nissan";
    } 
    else {
      $tax="make";
    }

*/

/*  $tax="toyota";
  $options = array();
  $n=($form_state->getValue('first_field'));
  //  echo $n;
  switch (n) {
      case "Audi":
          $tax="Audi";
          echo $tax;
          break;
      case "BMW":
          $tax="BMW";
          echo $tax;
          break;
      case "Benz":
          $tax="benz";
          echo $tax;
          break;
      case "Nissan":
          $tax="nissan";
          echo $tax;
          break;
      case "Toyota":
          $tax="toyota";
          echo $tax;
          break;
      default:
          $tax="title";
          echo $tax;
  }
*/

  $tax=$form_state->getValue('first_field');
  print_r($tax);
  $terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree($tax, $parent = 0, $max_depth = NULL, $load_entities = FALSE);
    foreach ($terms as $term) {
      $options[] = $term->name;
    }  

  return $options;
}

public function getTaxOptions(string $tax) {
  $options = array();
  $terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree($tax, $parent = 0, $max_depth = NULL, $load_entities = FALSE);
    foreach ($terms as $term) {
      $options[] = $term->name;
    }
}



  public function mymodule_relationship_most_recent_content($context = NULL, $config) {
    // Read the user ID from the context. If you have multiple context inputs,
    // $context will be an array of contexts. But there is only one here.
    if (empty($context) || empty($context->data) || empty($context->data->uid)) {
      // If there is a problem, return an empty CTools context. This is also
      // used by CTools to determine the output data type of this plugin.
      return ctools_context_create_empty('node', NULL);
    }
    $uid = $context->data->uid;

    // Locate the most recent content node created by this user.
    $nid = db_select('node', 'n')
      ->fields('n', array('nid'))
      ->condition('uid', $uid)
      ->orderBy('created', 'DESC')
      ->range(0,1)
      ->execute()
      ->fetchField();

    // Load the node item if possible.
    if (!$nid) {
      return ctools_context_create_empty('node', NULL);
    }
    $node = node_load($nid);
    if (!$node) {
      return ctools_context_create_empty('node', NULL);
    }

    // Return the found node in a CTools context.
    return ctools_context_create('node', $node);
  }

public function shift8_dependent_dropdown_callback_second($form, $form_state) {
        $commands[] = ajax_command_insert('#dropdown-third-replace', drupal_render($form['dropdown_third']));
        return array('#type' => 'ajax', '#commands' => $commands);
}  

public function shift8_get_second_dropdown_query($firstchoice) {
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
  }

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
  }

  function shift8_dependent_dropdown_callback_second($form, $form_state) {
        $commands[] = ajax_command_insert('#dropdown-third-replace', drupal_render($form['dropdown_third']));
        return array('#type' => 'ajax', '#commands' => $commands);
}

  public function _shift8_get_first_dropdown_options($vocabulary_name) {

    $vocabulary_name = 'make'; //name of your vocabulary
    $query = \Drupal::entityQuery('taxonomy_term');
    $query->condition('vid', $vocabulary_name);
    $query->sort('weight');
    $tids = $query->execute();
    $terms = Term::loadMultiple($tids);
//    $output = '<ul>';
    $options = [];
    foreach($terms as $term) {
      $options[$term->name] = $term->name;
    }  
    
    return $options;

  }
  
}

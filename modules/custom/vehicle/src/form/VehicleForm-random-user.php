<?php

namespace Drupal\vehicle\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Language\Language;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ChangedCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Form\FormBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form controller for the vehicle entity edit forms.
 *
 * @ingroup vehicle
 */
class VehicleForm extends ContentEntityForm {

  public function getFormId() {
    return 'VehicleForm';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\vehicle\Entity\Vehicle */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    $form['user_name'] = array(
      '#type' => 'textfield',
      '#title' => 'Username',
      '#description' => 'Please enter in a username',
      '#ajax' => array(
        // Function to call when event on form element triggered.
        'callback' => 'Drupal\vehicle\Form::usernameValidateCallback',
        // Effect when replacing content. Options: 'none' (default), 'slide', 'fade'.
        'effect' => 'fade',
        // Javascript event to trigger Ajax. Currently for: 'onchange'.
        'event' => 'change',
        'progress' => array(
          // Graphic shown to indicate ajax. Options: 'throbber' (default), 'bar'.
          'type' => 'throbber',
          // Message to show along progress graphic. Default: 'Please wait...'.
          'message' => NULL,
          ),
        ),
      );
    
    $form['random_user'] = array(
      '#type' => 'button',
      '#value' => 'Random Username',
      '#ajax' => array(
        'callback' => 'Drupal\vehicle\Form::randomUsernameCallback',
        'event' => 'click',
        'progress' => array(
          'type' => 'throbber',
          'message' => 'Getting Random Username',
        ),
      ),
    );

    $options = [];
    $terms = [];
    $term_data = [];
    $vid = 'make';

    $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid, $parent = 0, $max_depth = NULL, $load_entities = FALSE);

//    $brands = $this->termStorage->loadTree('make', 0, NULL, TRUE);    

    foreach ($terms as $term)
    {
      $term_data[$term->tid] = $term->name;
        $term_data[] = array(
          "id" => $term->tid,
          "vid" => $term->vid,
          "name" => $term->name
        );
       $options[$term->tid] = $term->name;
      }

      dpm($options);
      dpm($term_data);

      $form['make'] = [
        '#type' => 'select',
        '#title' => t('Make'),
//        '#default_value' => is_null($form_state->getValue('make')) ? '-select Make-' : $form_state->getValue('make'),
        '#default_value' => '-select Make-',
        '#options' => $options,
/*        '#value' => [ if (!empty($form_state['values']['make']){
        $form_state['values']['make'];
    }else{
        '- no item selected -';
    })]
*/
//        '#value' => !empty($form_state['values']['make']) ? $form_state['values']['make'] : '- no item selected -',
//        '#value' => $form_state['values']['make'],
//       #value' => (if ($form_state->isValueEmpty('foo')) {}
        '#value' => $form_state->getValue('make'),
        '#ajax' => array(
          // Function to call when event on form element triggered.
          'callback' => [$this, 'makeValidateCallback'],
//          'callback' => [$this, 'selectModelsAjax'],
          //'callback' => '\Drupal\vehicle\Form::changeOptionsAjax',
          // 'callback' => '::changeOptionsAjax',
          'wrapper' => 'model_wrapper',
          // Effect when replacing content. Options: 'none' (default), 'slide', 'fade'.
          'effect' => 'fade',
          // Javascript event to trigger Ajax. Currently for: 'onchange'.
          'event' => 'change',   
          'progress' => array(
            // Graphic shown to indicate ajax. Options: 'throbber' (default), 'bar'.
            'type' => 'throbber',
            // Message to show along progress graphic. Default: 'Please wait...'.
            'message' => t('Please wait...hoooooooooooooo'),
            'method' => 'replace',
            ),
        ),
      ];
/*      if(form.fieldname.selectedIndex > 0) {
        // an option has been selected
      } else {
        // no option selected
      }      
*/
      if(form.make.selectedIndex > 0) {
        dpm('an option has been selected');
        dpm('make = ' . $form_state->getValue('make'));
      } else {
        dpm('no option selected');
        dpm('make = ' . $form_state->getValue('make'));
      }      
     
      $form['model'] = [
        '#type' => 'select',
        '#title' => t('Model'),
        '#options'   => ['_none' => $this->t('- Select the Make first -')],
        '#prefix' => '<div id="model_wrapper">',
        '#suffix' => '</div>',
        ];

      
        
      return $form;
    }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    parent::save($form, $form_state);

    $entity = $this->getEntity();
    $entity_type = $entity->getEntityType();

    $arguments = [
    '@entity_type' => $entity_type->getLowercaseLabel(),
    '%entity' => $entity->label(),
    'link' => $entity->toLink($this->t('View'), 'canonical')->toString(),
    ];

    $this->logger($entity->getEntityTypeId())->notice('The @entity_type %entity has been saved.', $arguments);
    drupal_set_message($this->t('The @entity_type %entity has been saved.', $arguments));

    $form_state->setRedirectUrl($entity->toUrl('canonical'));
  }   

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

/*    if (strlen($form_state->getValue('phone_number')) < 3) {
      $form_state->setErrorByName('phone_number', $this->t('The phone number is too short. Please enter a full phone number.'));
    }
*/
  }

  public function usernameValidateCallback(array &$form, FormStateInterface $form_state) {
    // Instantiate an AjaxResponse Object to return.
    $ajax_response = new AjaxResponse();
    
    // Check if Username exists and is not Anonymous User (''). 
    if (user_load_by_name($form_state->getValue('user_name')) && $form_state->getValue('user_name') != false) {
      $text = 'User Found';
      $color = 'green';
    } else {
      $text = 'No User Found';
      $color = 'red';
    }
    
    // Add a command to execute on form, jQuery .html() replaces content between tags.
    // In this case, we replace the desription with wheter the username was found or not.
    $ajax_response->addCommand(new HtmlCommand('#edit-user-name--description', $text));
    
    // CssCommand did not work.
    //$ajax_response->addCommand(new CssCommand('#edit-user-name--description', array('color', $color)));
    
    // Add a command, InvokeCommand, which allows for custom jQuery commands.
    // In this case, we alter the color of the description.
    $ajax_response->addCommand(new InvokeCommand('#edit-user-name--description', 'css', array('color', $color)));
    
    // Return the AjaxResponse Object.
    return $ajax_response;
  }

  public function randomUsernameCallback(array &$form, FormStateInterface $form_state) {
    // Get all User Entities.
    $all_users = entity_load_multiple('user');
    
    // Remove Anonymous User.
    array_shift($all_users);
    
    // Pick Random User.
    $random_user = $all_users[array_rand($all_users)];

    // Instantiate an AjaxResponse Object to return.
    $ajax_response = new AjaxResponse();
    
    // ValCommand does not exist, so we can use InvokeCommand.
    $ajax_response->addCommand(new InvokeCommand('#edit-user-name', 'val' , array($random_user->get('name')->getString())));
    
    // ChangedCommand did not work.
    //$ajax_response->addCommand(new ChangedCommand('#edit-user-name', '#edit-user-name'));
    
    // We can still invoke the change command on #edit-user-name so it triggers Ajax on that element to validate username.
    $ajax_response->addCommand(new InvokeCommand('#edit-user-name', 'change'));
    
    // Return the AjaxResponse Object.
    return $ajax_response;
  }

  public function changeOptionsAjax(array &$form, FormStateInterface $form_state) {

    $options = [];
    $vocabulary = $form_state->getValue('make');
//    $vocabulary = 'title';

    dpm("vocabulary= " . $vocabulary);

    switch ($form_state->getValue('make')) {
      case 'Benz':
      $vocabulary = 'benz';
      break;
      case 'BMW':
      $vocabulary = 'bmw';
      break;
      case 'Toyota':
      $vocabulary = 'toyota';
      break;
    }

    $models = $this->termStorage->loadTree($vocabulary, 0, NULL, TRUE);
    if ($models) {
      foreach ($models as $model) {
        $options[$model->id()] = $model->getName();
      }
    }
    $form['model']['#options'] = $options;

    dpm("Model = " .  $form['model']);

    return $form['model'];
  }  


  public function makeValidateCallback(array &$form, FormStateInterface $form_state) {
    // Instantiate an AjaxResponse Object to return.
    $ajax_response = new AjaxResponse();
    
    // Check if Username exists and is not Anonymous User (''). 
    if ($form_state->getValue('make') != false) {
      $text = 'User Found';
      $color = 'green';
    } else {
      $text = 'No User Found';
      $color = 'red';
    }
    
    // Add a command to execute on form, jQuery .html() replaces content between tags.
    // In this case, we replace the desription with wheter the username was found or not.
    $ajax_response->addCommand(new HtmlCommand('#edit-Make--value', $text));
    
    // CssCommand did not work.
    //$ajax_response->addCommand(new CssCommand('#edit-user-name--description', array('color', $color)));
    
    // Add a command, InvokeCommand, which allows for custom jQuery commands.
    // In this case, we alter the color of the description.
    $ajax_response->addCommand(new InvokeCommand('#edit-Make--value', 'css', array('color', $color)));
    
    // Return the AjaxResponse Object.
    return $ajax_response;
  }










}

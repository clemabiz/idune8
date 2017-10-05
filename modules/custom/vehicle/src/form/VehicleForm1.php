<?php

namespace Drupal\vehicle\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * VehicleForm.
 */
class VehicleForm extends ContentEntityForm {
//  class VehicleForm extends FormBase {
  /**
   * The term Storage.
   *
   * @var \Drupal\taxonomy\TermStorageInterface
   */
  protected $termStorage;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeManagerInterface $entity) {
    $this->termStorage = $entity->getStorage('taxonomy_term');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    return new static(
    // Load the service required to construct this class.
    $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'vehicle_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $params = NULL) {

    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    $terms = $this->termStorage->loadTree('make', 0, NULL, TRUE);
    $options = [];
    if ($terms) {
      foreach ($terms as $term) {
        $options[$term->getName()] = $term->getName();
      }
    }
    $form['make'] = array(
      '#type'    => 'select',
      '#title'   => $this->t('make'),
      '#options' => $options,
      '#ajax'    => array(
        'callback' => [$this, 'selectModelsAjax'],
        'wrapper'  => 'model_wrapper',
      ),
    );

    $form['model'] = array(
      '#type'      => 'select',
      '#title'     => $this->t('Model'),
      '#options'   => ['_none' => $this->t('- Select a make first -')],
      '#prefix'    => '<div id="model_wrapper">',
      '#suffix'    => '</div>',
//      '#validated' => TRUE,
    );


    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
 
    // Assert the model is valid
    if (!$form_state->getValue('model') || empty($form_state->getValue('model'))) {
        $form_state->setErrorByName('[model]', $this->t('Model is required.'));
    }







  /*  
    if (isset($form['#options']) && isset($form['#value'])) {
      dpm();
    }

  */
 
/*
    $form_state_complete_form=$form_state->getCompleteForm();
    if(empty($form_state_complete_version)){
        $form ['source']['migration_ids']['#options'] = $this->getDestinationIds("d6");
        dpm('form empty');
     }
      else  {
       $form ['source']['migration_ids']['#options'] = $this->getDestinationIds($form_state_complete_version);
        dpm('form ok');
      }
    }

*/
    $form_state_complete_form=$form_state->getCompleteForm();
    if(empty($form_state_complete_version)){
        $form ['model'] = $form_state->getValue('make');
        dpm('form empty' . $form_state_complete_form=$form_state->getCompleteForm());
     }
/*      else  {
       $form ['model'] = $this->getDestinationIds($form_state_complete_version);
        dpm('form ok');
    }
*/
/*
    $errors = drupal_get_messages(); // (dpm()'s has to go AFTER this line--they get cleared)
    foreach ($errors as $type => $id) {
      foreach ($id as $message){
        // Loop through individual messages, looking for ones to remove or replace
        if (test_for_invalid_error($message)===FALSE){
            drupal_set_message($message,$type);
        } elseif (test_for_invalid_error($message)!==TRUE){
            drupal_set_message(test_for_invalid_error($message), $type);
        }
      }
    }
}
  }
function test_for_invalid_error($message){
    if (strpos($message, 'An illegal choice has been detected. Please contact the site administrator.') !== false) {
        return t('Please select a category!');
    }
    return FALSE;

*/
}


  /**
   * {@inheritdoc}

  public function save(array $form, FormStateInterface $form_state) {
    $status = parent::save($form, $form_state);
    //dpm($status);
    $entity = $this->entity;
    if ($status == SAVED_UPDATED) {
      drupal_set_message($this->t('The contact %feed has been updated.', ['%feed' => $entity->toLink()->toString()]));
    } else {
      drupal_set_message($this->t('The contact %feed has been added.', ['%feed' => $entity->toLink()->toString()]));
    }

    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    return $status;

  }

   */
  ///////////////////////////////////////////////////////////////////////////////////////////////////

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
   * Called via Ajax to populate the Model field according make.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form model field structure.
   */
  public function selectModelsAjax(array &$form, FormStateInterface $form_state) {
    $options = [];

    $vocabulary = 'title';
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

    return $form['model'];
  }
}
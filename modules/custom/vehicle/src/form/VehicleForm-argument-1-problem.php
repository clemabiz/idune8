<?php

namespace Drupal\vehicle\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\ContentEntityForm;
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
  
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    return new static(
    // Load the service required to construct this class.
    $container->get('entity_type.manager')
    );
  }
 */
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'vehicle_form';
  }

  /**
   * {@inheritdoc}
   
  public function buildForm(array $form, FormStateInterface $form_state, $params = NULL) {
  */

  public function buildForm(array $form, FormStateInterface $form_state) {

    /* @var $entity \Drupal\vehicle\Entity\Vehicle */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    $terms = $this->termStorage->loadTree('make', 0, NULL, TRUE);
    $options = [];
      
    if ($terms) {
      foreach ($terms as $term) {
        $options[$term->getname()] = $term->getName();
      }
    }
      
//    dpm($options);

/*    $options = [];
    if ($terms) {
      {
        $term_data[$term->tid] = $term->name;
          $term_data[] = array(
            "id" => $term->tid,
            "vid" => $term->vid,
            "name" => $term->name
          );
         $options[$term->tid] = $term->name;
      }
    } 

*/
    $form['make'] = array(
      '#type'    => 'select',
      '#title'   => $this->t('Make'),
      '#options' => $options,
      '#ajax'    => array(
        'callback' => [$this, 'selectModelsAjax'],
        'wrapper'  => 'model_wrapper',
      ),
    );

    $form['model'] = array(
      '#type'      => 'select',
      '#title'     => $this->t('Model'),
      '#options'   => ['_none' => $this->t('-Select a Make first-')],
      '#prefix'    => '<div id="model_wrapper">',
      '#suffix'    => '</div>',
      '#validated' => TRUE,
    );

/*    $form['actions']['submit'] = [
      '#type'  => 'submit',
      '#value' => $this->t('Send'),
    ];
*/
    dpm($form_state->getValue('make'));
    dpm($form_state->getValue('model'));

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $status = parent::save($form, $form_state);
dpm($status);
    $entity = $this->entity;
    if ($status == SAVED_UPDATED) {
      drupal_set_message($this->t('The contact %feed has been updated.', ['%feed' => $entity->toLink()->toString()]));
    } else {
      drupal_set_message($this->t('The contact %feed has been added.', ['%feed' => $entity->toLink()->toString()]));
    }

    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    return $status;
  }



/*  
   * {@inheritdoc}

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

*/  /**
   * {@inheritdoc}
  
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }
  */

  /**
   * Called via Ajax to populate the Model field according brand.
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
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
    $brands = $this->termStorage->loadTree('make', 0, NULL, TRUE);
    $options = [];
    if ($brands) {
      foreach ($brands as $brand) {
        $options[$brand->getName()] = $brand->getName();
      }
    }
    $form['brand'] = array(
      '#type'    => 'select',
      '#title'   => $this->t('brand'),
      '#options' => $options,
      '#ajax'    => array(
        'callback' => [$this, 'selectModelsAjax'],
        'wrapper'  => 'model_wrapper',
      ),
    );

    $form['model'] = array(
      '#type'      => 'select',
      '#title'     => $this->t('Model'),
      '#options'   => ['_none' => $this->t('- Select a brand before -')],
      '#prefix'    => '<div id="model_wrapper">',
      '#suffix'    => '</div>',
      '#validated' => TRUE,
    );

    $form['actions']['submit'] = [
      '#type'  => 'submit',
      '#value' => $this->t('Send'),
    ];

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
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

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
    switch ($form_state->getValue('brand')) {
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
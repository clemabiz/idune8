<?php

namespace Drupal\vehicle\Form;


use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\DependencyInjection\ContainerInterface;
// Traits
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;





/**
 * VehicleForm.
 */
class VehicleForm extends ContentEntityForm {
      use StringTranslationTrait;

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
   
  public function buildForm(array $form, FormStateInterface $form_state, $params = NULL) {
  */

  public function buildForm(array $form, FormStateInterface $form_state) {

    /* @var $entity \Drupal\vehicle\Entity\Vehicle */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    // The #ajax attribute used in the temperature input element defines an ajax
    // callback that will invoke the 'updateColor' method on this form object.
    // Whenever the temperature element changes, it will invoke this callback
    // and replace the contents of the 'color_wrapper' container with the
    // results of this method call.
    $form['temperature'] = [
      '#title' => $this->t('Temperature'),
      '#type' => 'select',
      '#options' => $this->getColorTemperatures(),
      '#empty_option' => $this->t('- Select a color temperature -'),
      '#ajax' => [
        // Could also use [get_class($this), 'updateColor'].
        'callback' => '::updateColor',
        'wrapper' => 'color-wrapper',
      ],
    ];

    // Add a wrapper that can be replaced with new HTML by the ajax callback.
    // This is given the ID that was passed to the ajax callback in the '#ajax'
    // element above.
    $form['color_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'color-wrapper'],
    ];

    // Add a color element to the color_wrapper container using the value
    // from temperature to determine which colors to include in the select
    // element.
    $temperature = $form_state->getValue('temperature');
    if (!empty($temperature)) {
      $form['color_wrapper']['color'] = [
        '#type' => 'select',
        '#title' => $this->t('Color'),
        '#options' => $this->getColorsByTemperature($temperature),
      ];
    }
/*
    // Add a submit button that handles the submission of the form.
    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Submit'),
      ],
    ];

*/    return $form;
  }

  /**
   * Ajax callback for the color dropdown.
   */
  public function updateColor(array $form, FormStateInterface $form_state) {
    return $form['color_wrapper'];
  }

  /**
   * Returns colors that correspond with the given temperature.
   *
   * @param string $temperature
   *   The color temperature for which to return a list of colors. Can be either
   *   'warm' or 'cool'.
   *
   * @return array
   *   An associative array of colors that correspond to the given color
   *   temperature, suitable to use as form options.
   */
  protected function getColorsByTemperature($temperature) {
    return $this->getColors()[$temperature]['colors'];
  }

  /**
   * Returns a list of color temperatures.
   *
   * @return array
   *   An associative array of color temperatures, suitable to use as form
   *   options.
   */
  protected function getColorTemperatures() {
    return array_map(function ($color_data) {
      return $color_data['name'];
    }, $this->getColors());
  }

  /**
   * Returns an array of colors grouped by color temperature.
   *
   * @return array
   *   An associative array of color data, keyed by color temperature.
   */
  protected function getColors() {
    return [
      'warm' => [
        'name' => $this->t('Warm'),
        'colors' => [
          'red' => $this->t('Red'),
          'orange' => $this->t('Orange'),
          'yellow' => $this->t('Yellow'),
        ],
      ],
      'cool' => [
        'name' => $this->t('Cool'),
        'colors' => [
          'blue' => $this->t('Blue'),
          'purple' => $this->t('Purple'),
          'green' => $this->t('Green'),
        ],
      ],
    ];
  }


  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    // Assert the make is valid
    if (!$form_state->getValue('make') || empty($form_state->getValue('make'))) {
        $form_state->setErrorByName('[make]', $this->t('Your Make is mandatory.'));
    }

    // Assert the model is valid
    if (!$form_state->getValue('model') || empty($form_state->getValue('model'))) {
        $form_state->setErrorByName('[model]', $this->t('Your model is required..'));
    }

    // Assert the body is valid
    if (!$form_state->getValue('body') || empty($form_state->getValue('body'))) {
        $form_state->setErrorByName('[body]', $this->t('Your body is mandatory.'));
    }

    // If validation errors, add inline errors
    if ($errors = $form_state->getErrors()) {
      // Add error to fields using Symfony Accessor
      $accessor = PropertyAccess::createPropertyAccessor();
      foreach ($errors as $field => $error) {
        if ($accessor->getValue($form, $field)) {
          $accessor->setValue($form, $field.'[#prefix]', '<div class="form-group error">');
          $accessor->setValue($form, $field.'[#suffix]', '<div class="input-error-desc">' .$error. '</div></div>');
        }
      }
    }
  }

  /**
   * {@inheritdoc}

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
   */


  /*  
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
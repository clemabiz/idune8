<?php

namespace Drupal\vehicle\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Language\Language;
use Drupal\Core\Form\FormStateInterface;

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

    $options = [];
    $vid = 'make';
    $brands =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid, $parent = 0, $max_depth = NULL, $load_entities = FALSE);
    foreach ($brands as $brand) {
      $options[$brand->name] = $brand->name;
      //  $brand_data[] = array(
      //  "id" => $brand->tid,
      //  "name" => $brand->name
      //);
    }

    $form['make'] = [
      '#type' => 'select',
      '#title' => t('Make'),
      '#options' => $options,
      '#ajax' => [
        'callback' => [$this, 'selectModelsAjax'],
        'wrapper' => 'model_wrapper',
        ],
      ];
      $form['model'] = [
        '#type' => 'select',
        '#title' => t('Model'),
        '#options' => $this->getOptions($form_state),
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

  public function test(array &$form, FormStateInterface $form_state) {
    if (!isset($options[$v])) {
      //form_error($elements, $t('An illegal choice has been detected. Please contact the site administrator.'));
      watchdog('form', 'Illegal choice %choice in !name element.', array('%choice' => $v, '!name' => empty($elements['#title']) ? $elements['#parents'][0] : $elements['#title']), WATCHDOG_ERROR);
            }
  }
}

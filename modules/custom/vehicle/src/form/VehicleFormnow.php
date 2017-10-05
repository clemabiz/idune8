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

/*    $options = [
      'node' => 'Node',
      'user' => 'User'
    ];

    $brands = $this->termStorage->loadTree('make', 0, NULL, TRUE);
    $options = [];
    if ($brands) {
      foreach ($brands as $brand) {
        $options[$brand->getName()] = $brand->getName();
      }
    }
*/
   /* $options = [];
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
        'callback' => [$this, 'changeOptionsAjax'],
        // 'callback' => '\Drupal\helloworld\Form\helloworldForm::changeOptionsAjax',
        // 'callback' => '::changeOptionsAjax',
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
*/

    return $form;
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
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {}

  /**
   * Ajax callback to change options for model.
   */
  public function changeOptionsAjax(array &$form, FormStateInterface $form_state) {
  return $form['model'];
  }

  /**
   * Get options for model.
   */
  public function getOptions(FormStateInterface $form_state) {
  if ($form_state->getValue('make') == 'user') {
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

/*
Parameters
8.3.x TermStorage.php 
public TermStorage::loadTree($vid, $parent = 0, $max_depth = NULL, $load_entities = FALSE)
string $vid: Vocabulary ID to retrieve terms for.
int $parent: The term ID under which to generate the tree. If 0, generate the tree for the entire vocabulary.
int $max_depth: The number of levels of the tree to return. Leave NULL to return all levels.
bool $load_entities: If TRUE, a full entity load will occur on the term objects. Otherwise they are partial objects queried directly from the {taxonomy_term_data} table to save execution time and memory consumption when listing large numbers of terms. Defaults to FALSE.
Return value
object[]|\Drupal\taxonomy\TermInterface[] An array of term objects that are the children of the vocabulary $vid.
Overrides TermStorageInterface::loadTree

*/

}

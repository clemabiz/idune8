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
/*
    //$tree= taxonomy_get_nested_tree(2,10);//In this case only the first term id 2 is covered
    $tree = [$this->taxonomy_get_nested_tree(2)];
    $output = [$this->output_taxonomy_nested_tree_array($tree)];
    print_r('Output = ' . $output);
    dpm('dpm Output = ' .  $output);

*/

//    $parents = $this->termStorage->loadAllParents($term->id(1));
/* 
    $vid = 'my_taxonomy';
    $terms = $this->termStorage->loadTree($vid, 0, NULL, TRUE);
    foreach ($terms as $term) {
     $term_data[] = array(
      "id" => $term->tid,
      "name" => $term->name
     );
     $parents=$term_data;
     dpm($term_data);
    }
*/



    $vid = 'food';
//    $terms = $this->termStorage->loadAllChildren($id, $max_relative_depth = NULL);
//    $terms = $this->termStorage->loadAllParents($term->tid(1));
    $terms = $this->termStorage->loadTree($vid, $parent = 0, $max_depth = 2, $load_entities = FALSE);
    $options = [];
    if ($terms) {
      foreach ($terms as $term) {
        $term_data[$term->name] = array(
          "id" => $term->tid,
          "name" => $term->name
        );
      //$value = $term->get('field_example')->getValue();
//      var_dump($term);
      print_r('term_data = ' . $term->name . '<br>');

        $options[$term->name] = $term->name;

      }
    }

//        dpm($term_data);

/*
    $form['make'] = array(
      '#type'    => 'select',
      '#title'   => $this->t('make'),
      '#options' => $options,
      '#ajax'    => array(
        'callback' => [$this, 'selectModelsAjax'],
        'wrapper'  => 'model_wrapper',
      ),
    );

    $category = $form_state->getValue('category_select');
    // Disable caching on this form.
    //$form_state->setCached(FALSE);

    $form['model'] = array(
      '#type'      => 'select',
      '#title'     => $this->t('Model'),
      '#options'   => ['_none' => $this->t('- Select a make first -')],
      '#prefix'    => '<div id="model_wrapper">',
      '#suffix'    => '</div>',
//      '#validated' => TRUE,
    );

*/
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  public function taxonomy_get_nested_tree($terms = array(), $max_depth = NULL, $parent = 0, $parents_index = array(), $depth = 0) {

    if (is_int($terms)) {
      //$terms = taxonomy_get_tree($terms); //Function for drupal 7
      $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree("vocabulary_id", $parent = $terms, $max_depth = NULL, $load_entities = FALSE);

    }

    foreach($terms as $term) {
      foreach($term->parents as $term_parent) {
        if ($term_parent == $parent) {
          $return[$term->tid] = $term;
        }
        else {
          $parents_index[$term_parent][$term->tid] = $term;
        }
      }
    }

    foreach($return as &$term) {
      if (isset($parents_index[$term->tid]) && (is_null($max_depth) || $depth < $max_depth)) {
        $term->children = taxonomy_get_nested_tree($parents_index[$term->tid], $max_depth, $term->tid, $parents_index, $depth + 1);
      }
    }

    return $return;
  }

  function output_taxonomy_nested_tree_array($tree) {

    if (count($tree)) {

      foreach ($tree as $term) {

        $actual_data_array_to_add = array();
        $actual_data_array_to_add['text'] = $term->name;
        $actual_data_array_to_add['tid'] = $term->tid;

        if ($term->children) {

          $actual_data_array_to_add['nodes'] = output_taxonomy_nested_tree_array($term->children);
        }

        $array_to_return[] = $actual_data_array_to_add;

      }

    }

    return $array_to_return;
    


}

}
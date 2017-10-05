<?php
namespace Drupal\vehicle\Form;
use Drupal\Core\Form\FormBase;
class VeHicleForm extends FormBase {
  public function getFormId() {
    return 'vehicle_form';
  }
  public function buildForm(array $form, array &$form_state) {
    // Initialize the counter if it hasn't been set.
    if (!isset($form_state['fields'])) {
      // Nested this deep to avoid conflicts with other modules
      $form_state['fields'] = array(
        'vehicle' => array(
          'foo' => array(
            'items_count' => 1
          )
        )
      );
    }
    $max = $form_state['fields']['vehicle']['foo']['items_count'];
    $form['foo'] = array(
      '#tree' => TRUE,
      '#prefix' => '<div id="foo-replace">',
      '#suffix' => '</div>'
    );
    // Add elements that don't already exist
    for ($delta = 0; $delta < $max; $delta++) {
      if (!isset($form['foo'][$delta])) {
        $element = array(
          '#type' => 'textfield'
        );
        $form['foo'][$delta] = $element;
      }
    }
    $form['add'] = array(
      '#type' => 'submit',
      '#name' => 'add',
      '#value' => t('Add'),
      '#submit' => array(array($this, 'addMoreSubmit')),
      '#ajax' => array(
        'callback' => array($this, 'addMoreCallback'),
        'wrapper' => 'foo-replace',
        'effect' => 'fade',
      ),
    );
    return $form;
  }
  public function addMoreSubmit(array &$form, array &$form_state) {
    $form_state['fields']['vehicle']['foo']['items_count']++;
    $form_state['rebuild'] = TRUE;
  }
  public function addMoreCallback(array &$form, array &$form_state) {
    return $form['foo'];
  }
  public function validateForm(array &$form, array &$form_state) {
  }
  public function submitForm(array &$form, array &$form_state) {
  }
}
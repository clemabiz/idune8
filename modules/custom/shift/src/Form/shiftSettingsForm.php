<?php

namespace Drupal\shift\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class shiftSettingsForm.
 */
class shiftSettingsForm extends FormBase {
  /**
   * Get From ID.
   */
  public function getFormId() {
    return 'shift_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Empty implementation of the abstract submit class.
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['shift_settings']['#markup'] = 'Settings form for shift. Manage field settings here.';
    return $form;
  }

}

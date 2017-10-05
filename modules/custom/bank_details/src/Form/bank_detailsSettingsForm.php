<?php

namespace Drupal\bank_details\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class bank_detailsSettingsForm.
 */
class bank_detailsSettingsForm extends FormBase {
  /**
   * Get From ID.
   */
  public function getFormId() {
    return 'bank_details_settings';
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
    $form['bank_details_settings']['#markup'] = 'Settings form for bank_details. Manage field settings here.';
    return $form;
  }

}

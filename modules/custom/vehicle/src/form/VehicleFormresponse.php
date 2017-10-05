<?php

namespace Drupal\vehicle\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Language\Language;
use Drupal\Core\Form\FormStateInterface;

use Drupal\taxonomy\Entity\Vocabulary;
use Drupal\taxonomy\Entity\Term;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AlertCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\UpdateBuildIdCommand;

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

/*
    $storage = \Drupal::service('entity_type.manager')->getStorage('taxonomy_term');
    $parents = $storage ->loadAllParents($term->id());
    
*/
  
  $vocabularies = Vocabulary::load();
  $vocabulariesList = [];
  foreach ($vocabularies as $vid => $vocablary) {
    $vocabulariesList[$vid] = $vocablary->get('name');
    echo 'vocabulariesList[ ' . $vid . ' ] = ' . $vocabulariesList[$vid] . '<br />';

  }
  //print_r($vocabulariesList);
  
/*  $vocabularies = term::load();
  $vocabulariesList = [];
  foreach ($vocabularies as $vid => $vocablary) {
    $vocabulariesList[$vid] = $vocablary->get('name');
    echo 'TermsList[ ' . $vid . ' ] = ' . $vocabulariesList[$vid] . '<br />';

  }*/


}




  public static function respondToAjax(array $form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $message = 'Your phone number is ' . $form_state->getValue('phone');
    $submit_selector = 'form:has(input[name=form_build_id][value='
      . $form['#build_id'] . '])';
 
    $response->addCommand(new AlertCommand($message));
    $response->addCommand(new UpdateBuildIdCommand($form['#build_id_old'], $form['#build_id']));
    $response->addCommand(new InvokeCommand($submit_selector, 'submit'));
 
    return $response;
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


}

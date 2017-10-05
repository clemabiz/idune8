<?php

namespace Drupal\shift\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Language\Language;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the shift entity edit forms.
 *
 * @ingroup shift
 */
class shiftForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\shift\Entity\shift */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    $form['langcode'] = array(
      '#title' => $this->t('Language'),
      '#type' => 'language_select',
      '#default_value' => $entity->getUntranslated()->language()->getId(),
      '#languages' => Language::STATE_ALL,
    );

/*// Horizontal tabs container
  $form['group_tabs'] = array(
    '#type'     => 'horizontal_tabs',
    '#attached' => array('library' => array(array('field_group', 'horizontal-tabs'))), // Converts fieldsets into tabs
  );
 
  // My Reviews tab
  $form['group_user_reviews'] = array(
    '#type'  => 'fieldset',
    '#title' => t('My Reviews'),
    '#group' => 'group_tabs',
  );
 
  // Field - username (hardcoded)
  $form['group_user_reviews']['name'] = array(
    '#type' => 'textfield',
    '#title' => t('My text field'),
    '#group' => 'group_user_reviews',
  );
 
  // Field - submit button
  $form['group_user_reviews']['submit'] = array(
    '#type'  => 'button',
    '#value' => t('Create My Reviews Widget'),
    '#group' => 'group_user_reviews',
  );
 
  // My Favorites tab
  $form['group_favorites'] = array(
    '#type'  => 'fieldset',
    '#title' => t('My Favorites'),
    '#group' => 'group_tabs',
  );
 
  $form['group_favorites']['submit'] = array(
    '#type'  => 'button',
    '#value' => t('Create My Favorites Widget'),
    '#group' => 'group_favorites',
  );
*/
//$form = array();
$form['my_field'] = array(
  '#type' => 'horizontal_tabs',
  '#tree' => TRUE,
  '#prefix' => '<div id="unique-wrapper">',
  '#suffix' => '</div>',
);
$items = array(
  array(
    'nid' => 1,
    'name' => 'Item 1'
  ),
  array(
    'nid' => 2,
    'name' => 'Item 2'
  ),
  array(
    'nid' => 3,
    'name' => 'Item 3'
  ),
  array(
    'nid' => 4,
    'name' => 'Item 4'
  ),
);
$counter = 1;
foreach ($items as $item) {
  $nid = $item['nid'];
  $form['my_field']['stuff'][$nid] = array(
    '#type' => 'fieldset',
    '#title' => t('Item @no', array('@no' => $counter)),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['my_field']['stuff'][$nid]['form']['item_id'] = array(
    '#type' => 'value',
    '#value' => $item['nid'],
  );
  $form['my_field']['stuff'][$nid]['form']['item_name'] = array(
    '#type' => 'value',
    '#value' => $item['name'],
  );
  $form['my_field']['stuff'][$nid]['form']['remove'] = array(
      '#type' => 'checkbox',
      '#title' => t('Remove this item'),
      '#weight' => -51,
  );
  
// more field definition

  $form['my_field']['stuff'][$nid]['form']['#tree'] = TRUE;
  $form['my_field']['stuff'][$nid]['form']['#parents'] = array('my_field', 'stuff', $nid, 'form');
  $counter++;
}
/*    
    // under certain circumstances you have to attach the horizontal tabs library manually
    $form['#attached']['library'][] = 'field_group/formatter.horizontal_tabs';

    #--------------------------------------
    # define your drupal 'fieldset' element
    #--------------------------------------
    $form['name'] = array(
      '#type' => 'fieldset',
      '#title' => t('Name aaa'),
      '#prefix' => '<div class="col1">',
      '#suffix' => '</div>',
    );

    #--------------------------------------------------------
    # create a form element as a sub-array of your
    # top-level fieldset element; notice that this element is
    # declared as "$form['name']['firstname']" -- a sub-array
    # of the fieldset element.
    #--------------------------------------------------------
    $form['name']['make'] = array(
      '#type' => 'textfield',
      '#title' => t('Make'),
      '#size' => 40,
      '#required' => TRUE, // Added
      '#description' => t('Enter your name!'),
//      '#prefix' => '<div class="col1">',
//      '#suffix' => '</div>',
    );
    
    #----------------------------------------
    # define another textfield as a sub-array
    # of your fieldset elements
    #----------------------------------------
    $form['name']['model'] = array(
      '#type' => 'textfield',
      '#title' => t('Model'),
      '#size' => 40,
      '#required' => TRUE, // Added
      '#description' => t('Enter your name!'),
//      '#prefix' => '<div class="col1">',
//      '#suffix' => '</div>',
    );

*/    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $form_state->setRedirect('entity.shift.collection');
    $entity = $this->getEntity();
    $entity->save();
  }

}

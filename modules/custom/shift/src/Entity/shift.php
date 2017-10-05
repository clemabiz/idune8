<?php

namespace Drupal\shift\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\shift\shiftInterface;

/**
 * Defines the shift entity.
 *
 *
 * @ContentEntityType(
 *   id = "shift",
 *   label = @Translation("shift entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\shift\Entity\Controller\shiftListBuilder",
 *     "views_data" = "Drupal\shift\shiftViewsData",
 *     "form" = {
 *       "add" = "Drupal\shift\Form\shiftForm",
 *       "edit" = "Drupal\shift\Form\shiftForm",
 *       "delete" = "Drupal\shift\Form\shiftDeleteForm",
 *     },
 *     "access" = "Drupal\shift\shiftAccessControlHandler",
 *   },
 *   list_cache_contexts = { "user" },
 *   base_table = "shift",
 *   admin_permission = "administer shift entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/shift/{shift}",
 *     "edit-form" = "/shift/{shift}/edit",
 *     "delete-form" = "/shift/{shift}/delete",
 *     "collection" = "/shift/list"
 *   },
 *   field_ui_base_route = "shift.shift_settings",
 * )
 *
 */
class shift extends ContentEntityBase implements shiftInterface {


  /**
   * {@inheritdoc}
   *
   * When a new entity instance is added, set the user_id entity reference to
   * the current user as the creator of the instance.
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
//    $values += array(
//      'user_id' => \Drupal::currentUser()->id(),
//    );
  }


  /**
   * {@inheritdoc}
   *
   * Define the field properties here.
   *
   * Field name, type and size determine the table structure.
   *
   * In addition, we can define how the field and its content can be manipulated
   * in the GUI. The behaviour of the widgets used can be determined here.
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    // Standard field, used as unique if primary index.
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the shift entity.'))
      ->setReadOnly(TRUE);

    // Standard field, unique outside of the scope of the current project.
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the shift entity.'))
      ->setReadOnly(TRUE);


    //shift Owner
    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User Owner'))
      ->setDescription(t('shift owner.'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'entity_reference',
        'weight' => -8,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ),
        'weight' => -8,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    //A Make
    $fields['make'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Make'))
      ->setDescription(t('The Make.'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 60,
        'text_processing' => 0,
        'required' => TRUE,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -6,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -6,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    //A Model
    $fields['model'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Model'))
      ->setDescription(t('The Model.'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 60,
        'text_processing' => 0,
        'required' => TRUE,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -6,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -6,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    //A Date 
    $fields['start_date'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Start date'))
      ->setDescription(t('The date that the survey is started.'))
      ->setSettings(array(
        'datetime_type', 'date',
        'date_format' => 'd-m-Y',
      //  'date_date_format' => $date_format,
        'default_value' => '',
        'required' => TRUE,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['start_datetime'] = BaseFieldDefinition::create('datetime')
    ->setLabel('Package Start Time')
    ->setSetting('datetime_type', 'datetime')
    ->setSetting('timezone_override', '')
    ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'datetime',
        'weight' => 1,
    ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['end_datetime'] = BaseFieldDefinition::create('datetime')
    ->setLabel('Package End Time')
    ->setSetting('datetime_type', 'datetime')
    ->setSetting('timezone_override', '')
    ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'datetime',
        'weight' => 2,
    ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    //A Body
    $fields['body'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Body'))
      ->setDescription(t('The Body.'))
      ->setSettings(array(
        'allowed_values' => array(
          'female' => 'female',
          'male' => 'male',
          'Coupe' => 'Coupe',
          'Sedan'  => 'Sedan',
          'Convertible'=>   'Convertible',
          'Hatchbac'=> 'Hatchbac',
          'Station wagon'  => 'Station wagon',
          'SUV'=> 'SUV',
          'Minivan'=> 'Minivan',
          'Full-size van'=> 'Full-size van',
          'Pick-up'=> 'Pick-up',
        ), 
      ))
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -6,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -6,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // Gender field for the contact.
    // ListTextType with a drop down menu widget.
    // The values shown in the menu are 'male' and 'female'.
    // In the view the field content is shown as string.
    // In the form the choices are presented as options list.
    $options = array();
    $terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('title', $parent = 0, $max_depth = NULL, $load_entities = FALSE);
    foreach ($terms as $term) {
      $options[] = $term->name;
    }
    $fields['gender'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Gender'))
      ->setDescription(t('The gender of the inventory entity.'))
      ->setSettings(array('allowed_values' => $options
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

      $fields['fid'] = BaseFieldDefinition::create('file')
      ->setLabel(t('Photo (fid)'))
    //->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED) //to add multiple images     
      ->setDescription(t('The photo'))
      ->setSetting('target_type', 'file')
      ->setSetting('file_extensions', 'jpg jpeg png gif')
      ->setSetting('file_directory', 'sites\images')
      ->setSetting('max_filesize', 1024*1024)
      ->setDisplayOptions('view', array(
        'type' => 'file', 'weight' => -8,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'file', 'weight' => -8,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'))
      ->setDescription(t('The language code of ContentEntityExample entity.'));
    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));      

    return $fields;
 
  }
/*
  'allowed_values' => array(),
  'allowed_values_function' => '_my_feature_module_options_list',

  public static function _my_feature_module_options_list() {
    $results = db_query("SELECT Name, Label FROM {list_table}");
   
    $options = array();
    foreach ($results AS $result) {
      $options[$result->value] = t($result->label);
    return $options;
    }
  }


Link =
  https://drupal.stackexchange.com/questions/125486/how-to-determine-taxonomy-vocabularies-related-to-a-specific-content-type-in-dru

    $vids = array();
  foreach (\Drupal::entityManager()->getFieldDefinitions('node', $node_type) as $field_definition) {
    if ($field_definition->getType() == 'taxonomy_term_reference') {
      foreach ($field_definition->getSetting('allowed_values') as $allowed_values) {
        $vids[] = $allowed_values['vocabulary'];
      }
    }
  }

    $tree = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('tags', $parent = 0, $max_depth = NULL, $load_entities = FALSE);

$terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('menu');
foreach ($terms as $term) {
    //var_dump($term->tid);
}


*/

}





<?php

namespace Drupal\vehicle\Entity;

use Drupal\user\UserInterface;
use Drupal\vehicle\VehicleInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityStorageInterface;
/**
 * Defines the Vehicle entity.
 *
 *
 * @ContentEntityType(
 *   id = "vehicle",
 *   label = @Translation("Vehicle entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\vehicle\Entity\Controller\VehicleListBuilder",
 *     "views_data" = "Drupal\vehicle\VehicleViewsData",
 *     "form" = {
 *       "add" = "Drupal\vehicle\Form\VehicleForm",
 *       "edit" = "Drupal\vehicle\Form\VehicleForm",
 *       "delete" = "Drupal\vehicle\Form\VehicleDeleteForm",
 *     },
 *     "access" = "Drupal\vehicle\VehicleAccessControlHandler",
 *   },
 *   list_cache_contexts = { "user" },
 *   base_table = "vehicle",
 *   admin_permission = "administer vehicle entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/vehicle/{vehicle}",
 *     "edit-form" = "/vehicle/{vehicle}/edit",
 *     "delete-form" = "/vehicle/{vehicle}/delete",
 *     "collection" = "/vehicle/list"
 *   },
 *   field_ui_base_route = "vehicle.vehicle_settings",
 * )
 *
 */
class Vehicle extends ContentEntityBase implements VehicleInterface {


  /**
   * {@inheritdoc}
   *
   * When a new entity instance is added, set the user_id entity reference to
   * the current user as the creator of the instance.
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
   $values += array(
     'user_id' => \Drupal::currentUser()->id(),
   );
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
      ->setDescription(t('The ID of the Vehicle entity.'))
      ->setReadOnly(TRUE);

    // Standard field, unique outside of the scope of the current project.
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Vehicle entity.'))
      ->setReadOnly(TRUE);

/*
    //Vehicle Owner
    $fields['vehicle_owner'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Vehicle Owner'))
      ->setDescription(t('Vehicle owner.'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')

      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'entity_reference',
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'autocomplete_type' => 'tags',
          'placeholder' => '',
          'weight' => 0,
        ),
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);


    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Contact entity.'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -6,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -6,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
*/
    //A Make
    // ListTextType with a drop down menu widget.
    // The values shown in the menu are 'male' and 'female'.
    // In the view the field content is shown as string.
    // In the form the choices are presented as options list.

    $vid = 'make';
    $options = array();
    $terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree($vid, $parent = 0, $max_depth = NULL, $load_entities = FALSE);
    foreach ($terms as $term) {
      $options[$term->name] = $term->name;
    }
    $fields['make'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Make'))
      ->setDescription(t('The Make.'))
      ->setSettings(array('allowed_values' => $options))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 60,
//        'required' => TRUE,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'list_default',
      ))
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => 1,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    //A Model
    // ListTextType with a drop down menu widget.
    // The values shown in the menu are 'male' and 'female'.
    // In the view the field content is shown as string.
    // In the form the choices are presented as options list.
    $vid = 'bmw';
    $fields['model'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Model'))
      ->setDescription(t('The Model.'))
      ->setSettings(array(
//        'allowed_values' => $options1))
        'settings' => array(
          'allowed_values' => array(),
          'allowed_values_function' => '_my_feature_module_options_list'),
      ))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 60,
//        'required' => TRUE,
      ))

      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'list_default',
      ))

      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => 2,
     ))

      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);


    // body field for the contact.
    // ListTextType with a drop down menu widget.
    // The values shown in the menu are 'male' and 'female'.
    // In the view the field content is shown as string.
    // In the form the choices are presented as options list.

    $body = array();
    $terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('Body', $parent = 0, $max_depth = NULL, $load_entities = FALSE);
    foreach ($terms as $term) {
      $body[$term->name] = $term->name;
    }
    $fields['body'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('body'))
      ->setDescription(t('The body of the entity.'))
      ->setSettings(array('allowed_values' => $body
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
    /*** 'body' end ***/

/*    // Gender field for the contact.
    // ListTextType with a drop down menu widget.
    // The values shown in the menu are 'male' and 'female'.
    // In the view the field content is shown as string.
    // In the form the choices are presented as options list.
    $fields['gender'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Gender'))
      ->setDescription(t('The gender of the Contact entity.'))
      ->setSettings(array(
        'allowed_values' => array(
          'female' => 'female',
          'male' => 'male',
        ),
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'list_default',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
*/

    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'))
      ->setDescription(t('The language code of inventory entity.'));
    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));
    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

  function _my_feature_module_options_list(FieldStorageConfig $definition, ContentEntityInterface $entity = NULL, $cacheable) {

//  function _my_feature_module_options_list($vid) {

    $options1 = array();
    $terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree($vid, $parent = 0, $max_depth = NULL, $load_entities = FALSE);
//    $results = db_query("SELECT value, label FROM {my_custom_table}");
   
    foreach ($results AS $result) {
      $options1[$result->value] = t($result->label);
    }
    dpm('yahooooooooo');
    drupal_set_message($this->t('_my_feature_module_options_list.'));   
    return $options;
  }








/*
  public static function _my_feature_module_options_list() {
    $results = db_query("SELECT Name, Label FROM {list_table}");
   
    $options = array();
    foreach ($results AS $result) {
      $options[$result->value] = t($result->label);
    }
  }
*/

}

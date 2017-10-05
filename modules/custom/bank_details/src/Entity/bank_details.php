<?php

namespace Drupal\bank_details\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\bank_details\bank_detailsInterface;

/**
 * Defines the bank_details entity.
 *
 *
 * @ContentEntityType(
 *   id = "bank_details",
 *   label = @Translation("bank_details entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\bank_details\Entity\Controller\bank_detailsListBuilder",
 *     "views_data" = "Drupal\bank_details\bank_detailsViewsData",
 *     "form" = {
 *       "add" = "Drupal\bank_details\Form\bank_detailsForm",
 *       "edit" = "Drupal\bank_details\Form\bank_detailsForm",
 *       "delete" = "Drupal\bank_details\Form\bank_detailsDeleteForm",
 *     },
 *     "access" = "Drupal\bank_details\bank_detailsAccessControlHandler",
 *   },
 *   list_cache_contexts = { "user" },
 *   base_table = "bank_details",
 *   admin_permission = "administer bank_details entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/bank_details/{bank_details}",
 *     "edit-form" = "/bank_details/{bank_details}/edit",
 *     "delete-form" = "/bank_details/{bank_details}/delete",
 *     "collection" = "/bank_details/list"
 *   },
 *   field_ui_base_route = "bank_details.bank_details_settings",
 * )
 *
 */
class bank_details extends ContentEntityBase implements bank_detailsInterface {


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
      ->setDescription(t('The ID of the bank_details entity.'))
      ->setReadOnly(TRUE);

    // Standard field, unique outside of the scope of the current project.
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the bank_details entity.'))
      ->setReadOnly(TRUE);


    //bank_details Owner
    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User Owner'))
      ->setDescription(t('bank_details owner.'))
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

    //Bank Name
    $fields['bank'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Bank'))
      ->setDescription(t('The Bank.'))
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

    //A branch
    $fields['branch'] = BaseFieldDefinition::create('string')
      ->setLabel(t('branch'))
      ->setDescription(t('The branch.'))
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
    
    //A bsb
    $fields['bsb'] = BaseFieldDefinition::create('string')
      ->setLabel(t('bsb'))
      ->setDescription(t('The bsb.'))
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
    
    //A account_no
    $fields['account_no'] = BaseFieldDefinition::create('string')
      ->setLabel(t('account_no'))
      ->setDescription(t('The account_no.'))
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
    
    //A account_holder
    $fields['account_holder'] = BaseFieldDefinition::create('string')
      ->setLabel(t('account_holder'))
      ->setDescription(t('The account_holder.'))
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
    
    //A amount
    $fields['amount'] = BaseFieldDefinition::create('string')
      ->setLabel(t('amount'))
      ->setDescription(t('The amount.'))
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
}


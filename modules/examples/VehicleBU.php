<?php

namespace Drupal\vehicle\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\vehicle1\VehicleInterface;

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
      ->setDescription(t('The ID of the Vehicle entity.'))
      ->setReadOnly(TRUE);

    // Standard field, unique outside of the scope of the current project.
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Vehicle entity.'))
      ->setReadOnly(TRUE);


    //Vehicle Owner
    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User Owner'))
      ->setDescription(t('Vehicle owner.'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')

      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'entity_reference',
        'weight' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ),
        'weight' => 0,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    /*** 'user_id' end ***/

    //A Make
    
    // Make field for the contact.
    // ListTextType with a drop down menu widget.
    // The values shown in the menu are 'male' and 'female'.
    // In the view the field content is shown as string.
    // In the form the choices are presented as options list.
    // 
    
/*    $tax = "make";  
    $make = array();
    $terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree($tax, $parent = 0, $max_depth = NULL, $load_entities = FALSE);
    foreach ($terms as $term) {
      $make[$term->name] = $term->name;
    }
*/
    $fields['make'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Make'))
      ->setDescription(t('The make of the entity.'))
//      ->setSettings(array('allowed_values' => $make))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 60,
        'text_processing' => 0,
        'required' => TRUE,
      ))

      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 1,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => 1,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    /*** 'make' end ***/

    // Model field for the contact.
    // ListTextType with a drop down menu widget.
    // The values shown in the menu are 'male' and 'female'.
    // In the view the field content is shown as string.
    // In the form the choices are presented as options list.
  /*  $tax = "toyota";  
    $model = array();
    $terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree($tax, $parent = 0, $max_depth = NULL, $load_entities = FALSE);
    foreach ($terms as $term) {
      $model[$term->name] = $term->name;
    }
*/
    $fields['model'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Model'))
      ->setDescription(t('The model of the entity.'))
//      ->setSettings(array('allowed_values' => $model))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 60,
        'text_processing' => 0,
        'required' => TRUE,
      ))

      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 5,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => 2,
     ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    /*** 'model' end ***/

    $body = array();
    $terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('body', $parent = 0, $max_depth = NULL, $load_entities = FALSE);
    foreach ($terms as $term) {
      $body[$term->name] = $term->name;
    }
    $fields['body'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Body Type'))
      ->setDescription(t('The Body Type.'))
      ->setSettings(array('allowed_values' => $body
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 3,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => 3,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    /*** 'body' end ***/

    // Gender field for the contact.
    // ListTextType with a drop down menu widget.
    // The values shown in the menu are 'male' and 'female'.
    // In the view the field content is shown as string.
    // In the form the choices are presented as options list.
    $gender = array();
    $terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('gender', $parent = 0, $max_depth = NULL, $load_entities = FALSE);
    foreach ($terms as $term) {
      $gender[] = $term->name;
    }
    $fields['gender'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Gender'))
      ->setDescription(t('The gender of the entity.'))
      ->setSettings(array('allowed_values' => $gender
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => 4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    /*** 'gender' end ***/

    // title field for the contact.
    // ListTextType with a drop down menu widget.
    // The values shown in the menu are 'male' and 'female'.
    // In the view the field content is shown as string.
    // In the form the choices are presented as options list.
    $title = array();
    $terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('title', $parent = 0, $max_depth = NULL, $load_entities = FALSE);
    foreach ($terms as $term) {
      $title[] = $term->name;
    }
    $fields['title'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Title'))
      ->setDescription(t('The title of the entity.'))
      ->setSettings(array('allowed_values' => $title
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 5,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => 5,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    /*** 'title' end ***/
 
  $fields['image'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Image'))
      ->setRequired(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'weight' => 2,
      ])
      ->setDisplayOptions('form', ['weight' => 2]);

    /*** 'Vehicle Owner Photo' ***/
    //->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED) //to add multiple images     
    $fields['fid'] = BaseFieldDefinition::create('file')
      ->setLabel(t('Photo (fid)'))
      ->setDescription(t('The photo'))
      ->setSetting('target_type', 'file')
      ->setSetting('file_extensions', 'jpg jpeg png gif')
      ->setSetting('file_directory', 'sites\images')
      ->setSetting('max_filesize', 1024*1024)
      ->setDisplayOptions('view', array(
        'type' => 'file', 
        'weight' => 8,
      ))
      ->setDisplayOptions('view', array(
//        'label' => 'above',
//        'type' => 'string',
        'weight' => 8,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'file', 
        'weight' => 8,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);
    /*** 'fid' end ***/


    /*$fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Email Address'))
      ->setDescription(t('The Email Address.'))
      ->setSettings(array(
          'max_length' => 50,
          'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
          'label' => 'above',
          'type' => 'string',
          'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
          'type' => 'email_default',
          'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
     
    $fields['telephone'] = BaseFieldDefinition::create('telephone')
      ->setLabel(t('Telephone Number'))
      ->setDescription(t('The Telephone Number.'))
      ->setSettings(array(
          'max_length' => 50,
          'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
          'label' => 'above',
          'type' => 'string',
          'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
          'type' => 'telephone_default',
          'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
     
    $fields['address'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Address'))
      ->setDescription(t('The Address.'))
      ->setSettings(array(
          'max_length' => 255,
          'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
          'label' => 'above',
          'type' => 'string',
          'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
          'type' => 'string_textarea',
          'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
*/

    // body field for the contact.
    // ListTextType with a drop down menu widget.
    // The values shown in the menu are 'male' and 'female'.
    // In the view the field content is shown as string.
    // In the form the choices are presented as options list.
    // $body = array();


    // $terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('Toyota', $parent = 0, $max_depth = NULL, $load_entities = FALSE);
    // foreach ($terms as $term) {
    //   $body[] = $term->name;
    // }
    // $fields['body'] = BaseFieldDefinition::create('list_string')
    //   ->setLabel(t('body'))
    //   ->setDescription(t('The body of the entity.'))
    //   ->setSettings(array('allowed_values' => $body
    //   ))
    //   ->setDisplayOptions('view', array(
    //     'label' => 'above',
    //     'type' => 'string',
    //     'weight' => -4,
    //   ))
    //   ->setDisplayOptions('form', array(
    //     'type' => 'options_select',
    //     'weight' => -4,
    //   ))
    //   ->setDisplayConfigurable('form', TRUE)
    //   ->setDisplayConfigurable('view', TRUE);
    /*** 'body' end ***/

//     //A Date 
//     $fields['start_date'] = BaseFieldDefinition::create('datetime')
//       ->setLabel(t('Start date'))
//       ->setDescription(t('The date that the survey is started.'))
//       ->setSettings(array(
//         'datetime_type', 'date',
//         'date_format' => 'd-m-Y',
//       //  'date_date_format' => $date_format,
//         'default_value' => '',
//         'required' => TRUE,
//       ))
//       ->setDisplayOptions('view', array(
//         'label' => 'above',
//         'type' => 'string',
//         'date_format' => 'd-m-Y',
// //        'weight' => -5,
//       ))
//       ->setDisplayOptions('form', array(
//         'type' => 'options_select',
//         'date_format' => 'd-m-Y',
// //        'weight' => -5,
//       ))
//       ->setDisplayConfigurable('form', TRUE)
//       ->setDisplayConfigurable('view', TRUE);
//     /*** 'Start Date' end ***/

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


  /**
   * Called via Ajax to populate the Model field according brand.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form model field structure.
   */
  public function selectModelsAjax(array &$form, FormStateInterface $form_state) {
    $options = [];

    $vocabulary = 'title';
    switch ($form_state->getValue('brand')) {
      case 'Benz':
        $vocabulary = 'benz';
        break;
      case 'BMW':
        $vocabulary = 'bmw';
        break;
      case 'Toyota':
        $vocabulary = 'toyota';
        break;
    }

    $models = $this->termStorage->loadTree($vocabulary, 0, NULL, TRUE);
    if ($models) {
      foreach ($models as $model) {
        $options[$model->id()] = $model->getName();
      }
    }
    $form['model']['#options'] = $options;

    return $form['model'];
  }
    // $options = array();
    // $terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('title', $parent = 0, $max_depth = NULL, $load_entities = FALSE);
    // foreach ($terms as $term) {
    //   $options[] = $term->name;
    // }



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





<?php

namespace Drupal\vehicle\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Ajax\CommandInterface;
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

   
    $form['month'] = [
      '#type' => 'select',
      '#title' => $this->t('Select month'),
      '#options' => [
        $this->t('January'),
        $this->t('February'),
        $this->t('March'),
      ],
      '#ajax' => [
        'callback' => [$this, 'extraField'],
        'event' => 'change',
        'wrapper' => 'week-day',
      ],
    ];

    // Disable caching on this form.
    $form_state->setCached(FALSE);

    $form['week_day'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'week-day'],
    ];

    if ($form_state->getUserInput()['_triggering_element_name'] == 'month') {
      $month = $form_state->getValue('month');
      $hour_options = [];

      if ($month == '0') {
        $hour_options[0] = $this->t('08:00');
        $hour_options[1] = $this->t('09:00');
        $hour_options[2] = $this->t('10:00');
      }

      if ($month == '1') {
        $hour_options[0] = $this->t('12:00');
        $hour_options[1] = $this->t('13:00');
        $hour_options[2] = $this->t('14:00');
      }

      if ($month == '2') {
        $hour_options[0] = $this->t('18:00');
        $hour_options[1] = $this->t('19:00');
        $hour_options[2] = $this->t('20:00');
      }

      $form['week_day']['hour'] = [
        '#type' => 'select',
        '#title' => $this->t('Choose hours @month', ['@month' => $month]),
        '#options' => $hour_options,
        '#ajax' => [
          'callback' => [$this, 'choosePerson'],
          'event' => 'change',
          'wrapper' => 'person-container',
        ],
      ];

      $form['week_day']['person_container'] = [
        '#type' => 'container',
        '#attributes' => ['id' => 'person-container'],
      ];

      $form['week_day']['person_container']['person'] = [
        '#type' => 'select',
        '#title' => $this->t('Choose person'),
        '#options' => ['John', 'Sarah', 'Peter'],
      ];
    }

    if ($form_state->getUserInput()['_triggering_element_name'] == 'hour') {
      $form['week_day']['person_container']['person'] = [
        '#type' => 'select',
        '#title' => $this->t('Choose person'),
        '#options' => ['Hans', 'Frank', 'Emma'],
      ];
    }

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

  public function extraField(array &$form, FormStateInterface $form_state) {
    return $form['week_day'];
  }

  public function choosePerson(array &$form, FormStateInterface $form_state) {
    return $form['week_day']['person_container'];
  }

}

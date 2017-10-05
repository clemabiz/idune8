<?php

namespace Drupal\bank_details\Entity\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Routing\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a list controller for bank_details entity.
 *
 * @ingroup bank_details
 */
class bank_detailsListBuilder extends EntityListBuilder {

  /**
   * The url generator.
   *
   * @var \Drupal\Core\Routing\UrlGeneratorInterface
   */
  protected $urlGenerator;


  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity.manager')->getStorage($entity_type->id()),
      $container->get('url_generator')
    );
  }

  /**
   * Constructs a new bank_detailsListBuilder object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   *   The entity storage class.
   * @param \Drupal\Core\Routing\UrlGeneratorInterface $url_generator
   *   The url generator.
   */
  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, UrlGeneratorInterface $url_generator) {
    parent::__construct($entity_type, $storage);
    $this->urlGenerator = $url_generator;
  }


  /**
   * {@inheritdoc}
   *
   * We override ::render() so that we can add our own content above the table.
   * parent::render() is where EntityListBuilder creates the table using our
   * buildHeader() and buildRow() implementations.
   */
  public function render() {
    $build['description'] = array(
      '#markup' => $this->t('Content Entity Example implements a bank_detailss model. These bank_detailss are fieldable entities. You can manage the fields on the <a href="@adminlink">bank_detailss admin page</a>.', array(
        '@adminlink' => $this->urlGenerator->generateFromRoute('bank_details.bank_details_settings'),
      )),
    );
    $build['table'] = parent::render();
    return $build;
  }

  /**
   * {@inheritdoc}
   *
   * Building the header and content lines for the bank_details list.
   *
   * Calling the parent::buildHeader() adds a column for the possible actions
   * and inserts the 'edit' and 'delete' links as defined for the entity type.
   */
  public function buildHeader() {
    $header['id'] = $this->t('bank_detailsID');
    $header['bank'] = $this->t('Bank');
    $header['branch'] = $this->t('Branch');
    $header['bsb'] = $this->t('BSB');
    $header['account_no'] = $this->t('Account No.');
    $header['account_holder'] = $this->t('account_holder');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\bank_details\Entity\bank_details */
    $row['id'] = $entity->id();
    $row['bank'] = $entity->bank->value;
    $row['branch'] = $entity->branch->value;
    $row['bsb'] = $entity->bsb->value;
    $row['account_no'] = $entity->account_number->value;
    $row['account_holder'] = $entity->account_holder->value;

    return $row + parent::buildRow($entity);
  }
}

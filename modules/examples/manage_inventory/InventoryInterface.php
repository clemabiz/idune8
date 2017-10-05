<?php
namespace Drupal\inventory;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;
/**
 * Provides an interface defining a Inventory entity.
 * @ingroup inventory
 */
interface InventoryInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {
}
?>
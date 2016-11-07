<?php

namespace Drupal\eventcreator;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Anonreg entity.
 *
 * @see \Drupal\eventcreator\Entity\anonreg.
 */
class anonregAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\eventcreator\anonregInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished anonreg entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published anonreg entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit anonreg entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete anonreg entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add anonreg entities');
  }

}

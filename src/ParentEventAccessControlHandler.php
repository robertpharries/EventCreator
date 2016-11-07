<?php

namespace Drupal\eventcreator;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Parent Event entity.
 *
 * @see \Drupal\eventcreator\Entity\ParentEvent.
 */
class ParentEventAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\eventcreator\ParentEventInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished parent event entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published parent event entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit parent event entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete parent event entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add parent event entities');
  }

}

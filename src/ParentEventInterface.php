<?php

namespace Drupal\eventcreator;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Parent Event entities.
 *
 * @ingroup eventcreator
 */
interface ParentEventInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Parent Event name.
   *
   * @return string
   *   Name of the Parent Event.
   */
  public function getName();

  /**
   * Sets the Parent Event name.
   *
   * @param string $name
   *   The Parent Event name.
   *
   * @return \Drupal\eventcreator\ParentEventInterface
   *   The called Parent Event entity.
   */
  public function setName($name);

  /**
   * Gets the Parent Event creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Parent Event.
   */
  public function getCreatedTime();

  /**
   * Sets the Parent Event creation timestamp.
   *
   * @param int $timestamp
   *   The Parent Event creation timestamp.
   *
   * @return \Drupal\eventcreator\ParentEventInterface
   *   The called Parent Event entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Parent Event published status indicator.
   *
   * Unpublished Parent Event are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Parent Event is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Parent Event.
   *
   * @param bool $published
   *   TRUE to set this Parent Event to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\eventcreator\ParentEventInterface
   *   The called Parent Event entity.
   */
  public function setPublished($published);

}

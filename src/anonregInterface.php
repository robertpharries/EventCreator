<?php

namespace Drupal\eventcreator;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Anonreg entities.
 *
 * @ingroup eventcreator
 */
interface anonregInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Anonreg name.
   *
   * @return string
   *   Name of the Anonreg.
   */
  public function getName();

  /**
   * Sets the Anonreg name.
   *
   * @param string $name
   *   The Anonreg name.
   *
   * @return \Drupal\eventcreator\anonregInterface
   *   The called Anonreg entity.
   */
  public function setName($name);

  /**
   * Gets the Anonreg creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Anonreg.
   */
  public function getCreatedTime();

  /**
   * Sets the Anonreg creation timestamp.
   *
   * @param int $timestamp
   *   The Anonreg creation timestamp.
   *
   * @return \Drupal\eventcreator\anonregInterface
   *   The called Anonreg entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Anonreg published status indicator.
   *
   * Unpublished Anonreg are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Anonreg is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Anonreg.
   *
   * @param bool $published
   *   TRUE to set this Anonreg to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\eventcreator\anonregInterface
   *   The called Anonreg entity.
   */
  public function setPublished($published);

}

<?php

/**
 * @file
 * Contains parent_event.page.inc.
 *
 * Page callback for Parent Event entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Parent Event templates.
 *
 * Default template: parent_event.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_parent_event(array &$variables) {
  // Fetch ParentEvent Entity Object.
  $parent_event = $variables['elements']['#parent_event'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}

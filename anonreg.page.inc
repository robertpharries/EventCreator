<?php

/**
 * @file
 * Contains anonreg.page.inc.
 *
 * Page callback for Anonreg entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Anonreg templates.
 *
 * Default template: anonreg.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_anonreg(array &$variables) {
  // Fetch anonreg Entity Object.
  $anonreg = $variables['elements']['#anonreg'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}

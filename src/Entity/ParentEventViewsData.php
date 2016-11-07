<?php

namespace Drupal\eventcreator\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Parent Event entities.
 */
class ParentEventViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['parent_event']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Parent Event'),
      'help' => $this->t('The Parent Event ID.'),
    );

    return $data;
  }

}

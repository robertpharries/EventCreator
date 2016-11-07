<?php

namespace Drupal\eventcreator\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Anonreg entities.
 */
class anonregViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['anonreg']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Anonreg'),
      'help' => $this->t('The Anonreg ID.'),
    );

    return $data;
  }

}

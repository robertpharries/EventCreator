<?php

namespace Drupal\eventcreator\Form;

use Drupal\Core\Routing;

use Drupal\node\Entity\Node;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\eventcreator\Entity\ParentEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class EventEditForm.
 *
 * @package Drupal\eventcreator\Form
 */
class EventEditForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'event_edit_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $pid = \Drupal::routeMatch()->getParameter('pid');
    $parent = ParentEvent::load($pid);

    $form['event-name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Event name'),
      '#required' => TRUE,
      '#default_value' => $parent->get('name')->value,
    );

    if($parent->get('field_attendeeid')->value > 0) {
      $att = Node::load($parent->get('field_attendeeid')->value);

      $form['separator'] = array(
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $this->t('<br><br>'),
      );

      $form['event-desc-att'] = array(
        '#type' => 'textarea',
        '#title' => $this->t('Attendee Event Description'),
        '#default_value' => $att->get('field_description')->value,
        '#required' => TRUE,
      );

      $form['event-date-att'] = array(
        '#type' => 'datetime',
        '#title' => $this->t('Attendee Date'),
        '#default_value' => new DrupalDateTime($att->get('field_original_date')->value),
        '#required' => TRUE,
      );

      if(!($parent->get('field_volunteerid')->value > 0)) {
        $form['separator1'] = array(
          '#type' => 'html_tag',
          '#tag' => 'p',
          '#value' => $this->t('<br><br>'),
        );

        $form['event-venue'] = array(
          '#type' => 'textfield',
          '#description' => $this->t('E.g. 1 Hargreaves St, Unanderra NSW 2526'),
          '#title' => $this->t('Venue'),
          '#default_value' => $att->get('field_venue')->value,
          '#required' => TRUE,
        );

        $form['event-status'] = [
          '#type' => 'select',
          '#title' => $this->t('Select status'),
          '#options' => [
            '1' => $this->t('Going ahead'),
            '2' => $this->t('Pending'),
            '3' => $this->t('Cancelled'),
          ],
          '#default_value' => $att->get('field_status_int')->value,
        ];
      }
    }

    if($parent->get('field_volunteerid')->value > 0) {
      $vol = Node::load($parent->get('field_volunteerid')->value);

      $form['separator2'] = array(
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $this->t('<br><br>'),
      );

      $form['event-desc-vol'] = array(
        '#type' => 'textarea',
        '#title' => $this->t('Volunteer Event Description'),
        '#default_value' => $vol->get('field_description')->value,
        '#required' => TRUE,
      );

      $form['event-date-vol'] = array(
        '#type' => 'datetime',
        '#title' => $this->t('Volunteer Date'),
        '#default_value' => new DrupalDateTime($vol->get('field_original_date')->value),
        '#required' => TRUE,
      );

      $form['separator3'] = array(
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $this->t('<br><br>'),
      );

      $form['event-venue'] = array(
        '#type' => 'textfield',
        '#description' => $this->t('E.g. 1 Hargreaves St, Unanderra NSW 2526'),
        '#title' => $this->t('Venue'),
        '#default_value' => $vol->get('field_venue')->value,
        '#required' => TRUE,
      );

      $form['event-status'] = [
        '#type' => 'select',
        '#title' => $this->t('Select status'),
        '#options' => [
          '1' => $this->t('Going ahead'),
          '2' => $this->t('Pending'),
          '3' => $this->t('Cancelled'),
        ],
        '#default_value' => $vol->get('field_status_int')->value,
      ];
    }

    $form['submit-event'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save Event'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $pid = \Drupal::routeMatch()->getParameter('pid');
    $parent = ParentEvent::load($pid);

    // Grab the data from the form
    $status_strings = array("Going ahead", "Pending", "Cancelled");
    $event_name = $form_state->getValue('event-name');
    $event_venue = $form_state->getValue('event-venue');
    $event_status = $form_state->getValue('event-status');

    $att = NULL;
    $vol = NULL;

    if($parent->get('field_attendeeid')->value > 0) { 
      $event_desc_att = $form_state->getValue('event-desc-att');
      $event_date_att = $form_state->getValue('event-date-att');
      $date_list_att = explode(" ", $event_date_att);

      $att = Node::load($parent->get('field_attendeeid')->value);

      $att->title = $event_name.' Attendee';
      $att->field_description = $event_desc_att;
      $att->field_original_date = $event_date_att;
      $att->field_text_date = $date_list_att[0];
      $att->field_text_time = $date_list_att[1];
      $att->field_venue = $event_venue;
      $att->field_status = $status_strings[intval($event_status)-1];
      $att->field_status_int = $event_status;
      $att->save();
    }

    if($form_state->hasValue('event-desc-vol')) { 
      $event_date_vol = $form_state->getValue('event-date-vol');
      $event_desc_vol = $form_state->getValue('event-desc-vol');
      $date_list_vol = explode(" ", $event_date_vol);

      $vol = Node::load($parent->get('field_volunteerid')->value);

      $vol->title = $event_name.' Volunteer';
      $vol->field_description = $event_desc_vol;
      $vol->field_original_date = $event_date_vol;
      $vol->field_text_date = $date_list_vol[0];
      $vol->field_text_time = $date_list_vol[1];
      $vol->field_venue = $event_venue;
      $vol->field_status = $status_strings[intval($event_status)-1];
      $vol->field_status_int = $event_status;
      $vol->save();
    }

    global $base_url;
    // Once we've saved and verified everything, we can redirect and set a success message!
    drupal_set_message($this->t('Successfully edited event: @event', array('@event' => $event_name)));
    $url = $base_url;
    
    if($att && !$vol) {
      $url = $url . '/event/view/' . $att->id();
    } else if($vol && !$att) {
      $url = $url . '/event/view/' . $vol->id();
    } else if ($vol && $att) {
      $url = $url . '/event/view/' . $att->id() . '/' . $vol->id();
    }
  
    $response = new RedirectResponse($url);
    $response->send();
  }

}

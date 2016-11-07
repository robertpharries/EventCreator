<?php

namespace Drupal\eventcreator\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

use Drupal\node\Entity\Node;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormBase;
use Drupal\eventcreator\Entity\ParentEvent;
use Drupal\assetmanage\Entity\Asset;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Form controller for Parent Event edit forms.
 *
 * @ingroup eventcreator
 */
class ParentEventForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\eventcreator\Entity\ParentEvent */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    $currentPath = \Drupal::service('path.current')->getPath();
    $splitPath = explode('/', $currentPath);

    if(end($splitPath) == 'add') {
      $form['event-name'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Event name'),
        '#required' => TRUE,
      );

      $form['checkAtt'] = array(
        '#type' => 'checkbox',
        '#title' => $this->t('Attendee'),
        '#states' => array(
          'optional' => array(
            ':input[name="checkVol"]' => array('checked' => TRUE),
            )
          )
      );

      $form['event-desc-att'] = array(
        '#type' => 'textarea',
        '#title' => $this->t('Attendee Event Description'),
        '#states' => array(
          'visible' => array(
            ':input[name="checkAtt"]' => array('checked' => TRUE),
          ),
          'optional' => array(
            ':input[name="checkAtt"]' => array('checked' => FALSE),
          )
        )
      );

      $form['event-date-att'] = array(
        '#type' => 'datetime',
        '#title' => $this->t('Attendee Date'),
        "#default_value" => new DrupalDateTime(date('o-m-d H:i:s')),
        '#states' => array(
          'visible' => array(
            ':input[name="checkAtt"]' => array('checked' => TRUE),
          ),
          'optional' => array(
            ':input[name="checkAtt"]' => array('checked' => FALSE),
          )
        )
      );

      $form['checkVol'] = array(
        '#type' => 'checkbox',
        '#title' => $this->t('Volunteer'),
        '#states' => array(
          'optional' => array(
            ':input[name="checkAtt"]' => array('checked' => TRUE),
            )
          )
      );

       $form['event-desc-vol'] = array(
        '#type' => 'textarea',
        '#title' => $this->t('Volunteer Event Description'),
        '#states' => array(
          'visible' => array(
            ':input[name="checkVol"]' => array('checked' => TRUE),
          ),
          'optional' => array(
            ':input[name="checkVol"]' => array('checked' => FALSE),
          )
        )
      );

      $form['event-date-vol'] = array(
        '#type' => 'datetime',
        '#title' => $this->t('Volunteer Date'),
        "#default_value" => new DrupalDateTime(date('o-m-d H:i:s')),
        '#states' => array(
          'visible' => array(
            ':input[name="checkVol"]' => array('checked' => TRUE),
          ),
          'optional' => array(
            ':input[name="checkVol"]' => array('checked' => FALSE),
          )
        )
      );

      $form['event-venue'] = array(
        '#type' => 'textfield',
        '#description' => $this->t('E.g. 1 Hargreaves St, Unanderra NSW 2526'),
        '#title' => $this->t('Venue'),
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
      ];
    } else {
      $form['event-name'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Event name'),
        '#required' => TRUE,
        '#default_value' => $entity->get('name')->value,
      );

      if($entity->get('field_attendeeid')->value > 0) {
        $att = Node::load($entity->get('field_attendeeid')->value);

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

        if(!($entity->get('field_volunteerid')->value > 0)) {
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

      if($entity->get('field_volunteerid')->value > 0) {
        $vol = Node::load($entity->get('field_volunteerid')->value);

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
    }

    return $form;
  }

  // /**
  //  * {@inheritdoc}
  //  */
  // public function validateForm(array &$form, FormStateInterface $form_state) {
  //   $event_include_att = $form_state->getValue('checkAtt');
  //   $event_include_vol = $form_state->getValue('checkVol');
  //   if($event_include_vol == 0 && $event_include_att == 0)
  //   {
  //     $form_state->setErrorByName('event-include', $this->t("Must select at least one event to create"));
  //   }
  // }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity; 
    $status = parent::save($form, $form_state);
    global $base_url;

    if($status == SAVED_NEW) {
      drupal_set_message($this->t('Created the %label Parent Event.', [
        '%label' => $entity->label(),
      ]));

      // Grab the data from the form
      $status_strings = array("Going ahead", "Pending", "Cancelled");
      $event_name = $form_state->getValue('event-name');
      $event_venue = $form_state->getValue('event-venue');
      $event_status = $form_state->getValue('event-status');

      if($form_state->hasValue('event-desc-att')) { 
        $event_desc_att = $form_state->getValue('event-desc-att');
      } else {
        $event_desc_att = "";
      }

      if($form_state->hasValue('event-desc-vol')) { 
       $event_desc_vol = $form_state->getValue('event-desc-vol');
      } else {
        $event_desc_vol = "";
      }

      if($form_state->hasValue('event-date-att')) { 
        $event_date_att = $form_state->getValue('event-date-att');
      } else {
        $event_date_att = "";
      }

      if($form_state->hasValue('event-date-vol')) { 
       $event_date_vol = $form_state->getValue('event-date-vol');
      } else {
        $event_date_vol = "";
      }

      $event_include_att = $form_state->getValue('checkAtt');
      $event_include_vol = $form_state->getValue('checkVol');

      //split the date to array. Original format: "yyyy-mm-dd hh:mm:ss Country/City"
      $date_list_vol = explode(" ", $event_date_vol);
      $date_list_att = explode(" ", $event_date_att);
      
      //\Drupal::logger('eventcreator')->error($event_include[1]);

      $att = NULL;
      $vol = NULL;
      if($event_include_att > 0)
      {
        // Now for the two sub events
        $att = Node::create(array(
            'type' => 'attendeeevent',
            'title' => $event_name.' Attendee',
            'langcode' => 'en',
            'uid' => '1',
            'field_original_date' => $event_date_att,
            'field_text_date' => $date_list_att[0],
            'field_text_time' => $date_list_att[1],
            'field_description' => $event_desc_att,
            'field_venue' => $event_venue,
            'field_status_int' => $event_status,
            'field_status' => $status_strings[intval($event_status)-1],
            'field_parentid' => 0,
            'field_edit_link' => "",
        ));
        $att->save();
      }
      if($event_include_vol > 0)
      {
        $vol = Node::create(array(
            'type' => 'volunteerevent',
            'title' => $event_name.' Volunteer',
            'langcode' => 'en',
            'uid' => '1',
            'field_original_date' => $event_date_vol,
            'field_text_date' => $date_list_vol[0],
            'field_text_time' => $date_list_vol[1],
            'field_description' => $event_desc_vol,
            'field_venue' => $event_venue,
            'field_status_int' => $event_status,
            'field_status' => $status_strings[intval($event_status)-1],
            'field_parentid' => 0,
            'field_edit_link' => "",
        ));
        $vol->save();
      }

      // Sets the ID's, if no event created of that type sets to 0
      $vid = 0;
      $aid = 0;

      if($vol) {
        $vid = $vol->id();
      }
      if($att) {
        $aid = $att->id();
      }

      $entity->label = $event_name;
      $entity->name = $event_name;
      $entity->field_attendeeid = $aid;
      $entity->field_volunteerid = $vid;
      $entity->save();

      // Edits the events to have the newly created parent event's id
      if($vol) {
        $vol->field_parentid = $entity->id();
        $vol->field_edit_link = $base_url . "/event/" . $entity->id() . "/edit/"; 
        $vol->field_edit_link->title = "Edit Event";
        $vol->field_assetlink = $base_url . "/event/" . $entity->id() . "/assetlist/"; 
        $vol->field_assetlink->title = "View Asset List";
        $vol->save();
        $entity->field_volunteer_event_link = $base_url . "/node/" . $vid;
        $entity->field_volunteer_event_link->title = "View Volunteer Event";
      }

      if($att) {
        $att->field_parentid = $entity->id();
        $att->field_edit_link = $base_url . "/event/" . $entity->id() . "/edit/"; 
        $att->field_edit_link->title = "Edit Event";
        $att->field_assetlink = $base_url . "/event/" . $entity->id() . "/assetlist/"; 
        $att->field_assetlink->title = "View Asset List";
        $att->save();
        $entity->field_attendee_event_link = $base_url . "/node/" . $aid;
        $entity->field_attendee_event_link->title = "View Attendee Event";
      }

      $entity->save();

      // Once we've saved and verified everything, we can redirect and set a success message!
      drupal_set_message($this->t('Successfully created event: @event', array('@event' => $event_name)));
      $url = '';
      
      if($att && !$vol) {
        $url = 'view/' . $att->id();
      } else if($vol && !$att) {
        $url = 'view/' . $vol->id();
      } else if ($vol && $att) {
        $url = 'view/' . $att->id() . '/' . $vol->id();
      }

      $response = new RedirectResponse($url);
      $response->send();
    } else {
      drupal_set_message($this->t('Saved the %label Parent Event.', [
        '%label' => $entity->label(),
      ]));

      // Grab the data from the form
      $status_strings = array("Going ahead", "Pending", "Cancelled");
      $event_name = $form_state->getValue('event-name');
      $event_venue = $form_state->getValue('event-venue');
      $event_status = $form_state->getValue('event-status');

      $att = NULL;
      $vol = NULL;

      if($entity->get('field_attendeeid')->value > 0) { 
        $event_desc_att = $form_state->getValue('event-desc-att');
        $event_date_att = $form_state->getValue('event-date-att');
        $date_list_att = explode(" ", $event_date_att);

        $att = Node::load($entity->get('field_attendeeid')->value);

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

        $vol = Node::load($entity->get('field_volunteerid')->value);

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
      
      $entity->save();

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

    // $form_state->setRedirect('entity.parent_event.canonical', ['parent_event' => $entity->id()]);
  }

}

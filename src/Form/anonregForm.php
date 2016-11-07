<?php

namespace Drupal\eventcreator\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\Entity;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityMalformedException;
use Drupal\Core\Entity\EntityStorageInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Drupal\identity_contact\Entity\Contact;
use Drupal\rng\Entity\Registration;
use Drupal\rng\Entity\RegistrationType;
use Drupal\rng\Entity\Registrant;
use Drupal\rng\RegistrationTypeInterface;
use Drupal\eventcreator\Entity\ParentEvent;
use Drupal\node\Entity\Node;

/**
 * Form controller for Anonreg edit forms.
 *
 * @ingroup eventcreator
 */
class anonregForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\eventcreator\Entity\anonreg */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    global $base_url;

    $entity = $this->entity;

    $pevent = $entity->field_event->entity;

    if($pevent->get('field_attendeeid')->value == 0) {
      drupal_set_message($this->t('There is no attendee event to subscribe for event: @event', array('@event' => $pevent->get('name')->value)), 'error');

      $response = new RedirectResponse($base_url . "/anonreg/add");
      $response->send();
    } else {

      $newCon = Contact::create([
        'label' => $form_state->getValue('field_attendee_name'),
        'mail' => $form_state->getValue('field_attendee_email'),
        'owner' => 1,
      ]);
      $newCon->save();
      
      $rego = Registration::create(array('type' => RegistrationType::load('registration')->bundle()));
      $rego->event = Node::load($pevent->get('field_attendeeid')->value);
      $rego->addIdentity($newCon);
      $rego->save();

      // $registrant = Registrant::create([
      //   'registration' => $rego,
      // ]);
      // $registrant->setIdentity($newCon);
      // $registrant->save();

      drupal_set_message($this->t('Subscribed @name for event: @event, as an attendee', array('@name' => $newCon->get('label')->value ,'@event' => $pevent->get('name')->value)));

      $response = new RedirectResponse($base_url . "/anonreg/add");
      $response->send();
    }
  }

}


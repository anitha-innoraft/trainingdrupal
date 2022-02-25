<?php

namespace Drupal\mymodule\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Locale\CountryManager as CountryManager;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Simpleform class using simple form api.
 */
class SimpleForm extends FormBase {

  /**
   * Messenger variable.
   *
   * @var object
   */
  protected $messenger;

  /**
   * Call the messenger using container interface for DI.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   Call the container Interface.
   *
   * @return object
   *   It return the messenger functions.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger')
    );

  }

  /**
   * Construct function to call messenger.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   Call the messenger interface.
   */
  public function __construct(MessengerInterface $messenger) {
    $this->messenger = $messenger;
  }

  /**
   * Get the formid function.
   *
   * @return string
   *   It return the formid.
   */
  public function getFormId() {
    return 'Simple_Form';
  }

  /**
   * Buildform function to create a form fields.
   *
   * @param array $form
   *   It return the form fields as array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   It return the stored values of form.
   *
   * @return array
   *   Form is returned as array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $countries = CountryManager::getStandardList();
    $country_array[''] = 'select';
    foreach ($countries as $key => $value) {
      $country_name = (string) $value;
      $country_array[$country_name] = $country_name;
    }
    $formValues = $form_state->get('storelocations');
    if (empty($formValues)) {
      $formValues = $form_state->set('storelocations', 1);
    }

    $form['country'] = [
      '#type' => 'select',
      '#title' => $this->t('Country'),
      '#options' => $country_array,
      '#key_type' => 'associative',
    ];
    $form['locations'] = [
      '#type' => 'container',
      '#tree' => TRUE,
      '#prefix' => '<div id="locations">',
      '#suffix' => '</div>',
    ];
    if ($formValues) {
      for ($i = 0; $i < $formValues; $i++) {
        $form['locations'][$i] = [
          '#type' => 'fieldset',
          '#tree' => TRUE,
          '#prefix' => 'Location' . $i,
        ];

        $form['locations'][$i]['state'] = [
          '#title' => t('State'),
          '#type' => 'textfield',
        ];
        $form['locations'][$i]['city'] = [
          '#title' => t('City'),
          '#type' => 'textfield',
        ];
      }
    }
    $form['add_another_location'] = [
      '#type' => 'submit',
      '#value' => t('Add A Location'),
      '#submit' => ['::addOne'],
      '#href' => '',
      '#ajax' => [
        'callback' => '::customAjaxAddLocation',
        'wrapper' => 'locations',
      ],
    ];
    $form_state->setCached(FALSE);
    $form['submit'] = [
      '#value' => t('Save'),
      '#type' => 'submit',
    ];

    return $form;

  }

  /**
   * Custom function to call the location fieldset.
   */
  public function customAjaxAddLocation(array &$form, FormStateInterface $form_state) {
    return $form['locations'];
  }

  /**
   * Custom function to increment the value of fieldset and stored in formstate.
   */
  public function addOne(array &$form, FormStateInterface $form_state) {
    $formValues = $form_state->get('storelocations');
    $add_button = $formValues + 1;
    $form_state->set('storelocations', $add_button);
    $form_state->setRebuild();
  }

  /**
   * Validate the form using below function.
   *
   * @param array $form
   *   It will return the form field as array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Get the field values using that variable.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    $locations = $form_state->getValue('locations');
    $country = $form_state->getValue('country');

    if ($country == "") {
      $form_state->setErrorByName('country', $this->t('Country is required'));
    }

    foreach ($form_state->getValue('locations') as $value) {
      for ($i = 0; $i < count($value); $i++) {
        if (($value['state'] == '') || (!ctype_alpha($value['state']))) {
          $form_state->setErrorByName('locations[$i][state]', $this->t('Please enter valid State. State should be alphabets'));
        }
        if (($value['city'] == '') || (!ctype_alpha($value['city']))) {
          $form_state->setErrorByName('locations[$i][city]', $this->t('Please enter valid City. City should be alphabets'));
        }
      }
    }
  }

  /**
   * Submit form function to display the submitted values.
   *
   * @param array $form
   *   It will return the form field as array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Get the field values using that variable.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $country = $form_state->getValue('country');
    $this->messenger->addMessage("Your selected country is: " . $country);
    $this->messenger->addMessage("Below are the locations you have entered:");
    foreach ($form_state->getValue('locations') as $value) {
      for ($i = 0; $i < count($value); $i++) {
        // var_dump($value);
        $this->messenger->addMessage($value['state'] . "," . $value['city']);
      }
    }
  }

}

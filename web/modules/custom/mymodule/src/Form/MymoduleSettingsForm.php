<?php

namespace Drupal\mymodule\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Mymodule settings form class is for form with configuration.
 */
class MymoduleSettingsForm extends ConfigFormBase {

  /**
   * Method to call the module settings.
   *
   * @return array
   *   It will return the array of settings.
   */
  protected function getEditableConfigNames() {
    return [
      'mymodule.settings',
    ];
  }

  /**
   * Method to call the formid.
   *
   * @return string
   *   It will return the form id.
   */
  public function getFormId() {
    return 'Settings_Form';
  }

  /**
   * Buildform method to create the form.
   *
   * @param array $form
   *   It will return the form fields.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Get the stored values of form fields.
   *
   * @return object
   *   Return the form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('mymodule.settings');
    $country = $config->get('country');
    $city = $config->get('city');
    $cntry = empty($country) ? 'India' : $country;
    $cty = empty($city) ? 'Kolkata' : $city;
    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#default_value' => $cntry,
    ];
    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#default_value' => $cty,
    ];
    $form['apikey'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Api Key'),
      '#default_value' => $config->get('apikey'),
    ];
    $form['apiendpoint'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Api Endpoint'),
      '#default_value' => $config->get('apiendpoint'),
    ];

    return parent::buildForm($form, $form_state);

  }

  /**
   * Method to save the form values.
   *
   * @param array $form
   *   It will return the form fields.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Get the stored values of form fields.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('mymodule.settings')
      ->set('country', $form_state->getValue('country'))
      ->set('city', $form_state->getValue('city'))
      ->set('apikey', $form_state->getValue('apikey'))
      ->set('apiendpoint', $form_state->getValue('apiendpoint'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}

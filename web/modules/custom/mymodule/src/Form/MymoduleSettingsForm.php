<?php

namespace Drupal\mymodule\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Mymodule settings form class is for form with configuration.
 */
class MymoduleSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'mymodule.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'Settings_Form';
  }

  /**
   * {@inheritdoc}
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
   * {@inheritdoc}
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

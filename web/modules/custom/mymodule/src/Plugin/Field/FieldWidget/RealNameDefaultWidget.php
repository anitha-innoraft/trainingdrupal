<?php

namespace Drupal\myplugin\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implementation of RealNameDefaultWidget.
 *
 * @FieldWidget(
 *   id = "RealNameDefaultWidget",
 *   label = @Translation("Real Name"),
 *   field_types = {
 *     "real_name"
 *   }
 * )
 */
class RealNameDefaultWidget extends WidgetBase {

  /**
   * Define the form for the field type.
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['first_name'] = [
      '#type' => 'textfield',
      '#title' => t('First Name'),
      '#default_value' => empty($items[$delta]->first_name) ? NULL : $items[$delta]->first_name,
      '#required' => TRUE,
    ];

    $element['last_name'] = [
      '#type' => 'textfield',
      '#title' => t('Last Name'),
      '#default_value' => empty($items[$delta]->last_name) ? NULL : $items[$delta]->last_name,
      '#required' => TRUE,
    ];

    return $element;

  }

}

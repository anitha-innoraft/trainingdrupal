<?php

namespace Drupal\myplugin\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Implementation of the 'RealNameDefaultFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "RealNameDefaultFormatter",
 *   label = @Translation("Real Name"),
 *   field_types = {
 *     "real_name"
 *   }
 * )
 */
class RealNameDefaultFormatter extends FormatterBase {

  /**
   * Field type is showed.
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#type' => 'markup',
        '#markup' => $item->first_name . ' ' . $item->last_name,
      ];
    }

    return $elements;

  }

}

<?php

namespace Drupal\myplugin\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Implementation of the 'StarRatingFormatter'.
 *
 * @FieldFormatter(
 *   id = "StarRating",
 *   label = @Translation("Star Rating"),
 *   field_types = {
 *     "decimal"
 *   }
 * )
 */
class StarRating extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
    foreach ($items as $delta => $item) {
      $highlighted_stars = str_repeat('<span class="star on"></span>', (int) $item->value);
      $whole = floor($item->value);
      $fraction = $item->value - $whole;

      if ($fraction == '0.5') {
        $half_star = '<span class="star half"></span>';
        $normalstr = (int) $item->value + 1;
      }
      else {
        $half_star = "";
        $normalstr = (int) $item->value;
      }
      $normal_stars = str_repeat('<span class="star"></span>', 5 - (int) $normalstr);
      $element[$delta] = ['#markup' => '<div class="stars">' . $highlighted_stars . $half_star . $normal_stars . '</div>'];

    }
    return $element;
  }

}

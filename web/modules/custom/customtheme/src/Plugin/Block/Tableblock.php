<?php

namespace Drupal\customtheme\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a block with Table.
 *
 * @Block(
 *   id = "Table_block",
 *   admin_label = @Translation("Table Block"),
 * )
 */
class Tableblock extends BlockBase {

  /**
   * {@inheritDoc}
   */
  public function build() {
    return [
      '#type' => 'inline_template',
      '#template' => "{% trans %} Hello {% endtrans %} <table><thead><tr><th>{{name}}</th><th>{{name}}</th><tr></thead><tbody><tr><td>Anitha</td><td>Govindarajan</td></tr></tbody></table>",
      '#context' => [
        'name' => 'FirstName',
        'lname' => 'LastName',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

}

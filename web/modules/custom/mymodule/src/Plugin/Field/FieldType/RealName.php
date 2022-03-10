<?php

namespace Drupal\myplugin\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface as StorageDefinition;

/**
 * Plugin implementation of the 'Real Name' field type.
 *
 * @FieldType(
 *   id = "real_name",
 *   label = @Translation("Real Name"),
 *   description = @Translation("Stores the first name and last name"),
 *   default_widget = "RealNameDefaultWidget",
 *   default_formatter = "RealNameDefaultFormatter"
 * )
 */
class RealName extends FieldItemBase {

  /**
   * Field type properties definition.
   */
  public static function propertyDefinitions(StorageDefinition $storage) {

    $properties = [];

    $properties['first_name'] = DataDefinition::create('string')
      ->setLabel(t('First name'));

    $properties['last_name'] = DataDefinition::create('string')
      ->setLabel(t('Last name'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(StorageDefinition $storage) {

    $columns = [];
    $columns['first_name'] = [
      'type' => 'char',
      'length' => 255,
    ];
    $columns['last_name'] = [
      'type' => 'char',
      'length' => 255,
    ];

    return [
      'columns' => $columns,
      'indexes' => [],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $isEmpty = empty($this->get('first_name')->getValue()) && empty($this->get('last_name')->getValue());
    return $isEmpty;
  }

}

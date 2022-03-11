<?php

namespace Drupal\myplugin\Plugin\Action;

use Drupal\Core\Action\ActionBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Messenger\MessengerInterface;

/**
 * Create custom action.
 *
 * @Action(
 *   id = "featured_node_action",
 *   label = @Translation("Featured Node"),
 *   type = "node"
 * )
 */
class FeaturedNodeAction extends ActionBase implements ContainerFactoryPluginInterface {

 /**
   * Messenger Interface.
   *
   * @var Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('messenger')
    );
  }

  /**
   * Construct.
   *
   * @param array $configuration
   *   Plugin Configurations.
   * @param mixed $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param Drupal\Core\Messenger\MessengerInterface $messenger
   *   An Messenger.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MessengerInterface $messenger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public function execute($node = NULL) {
    if ($node) {
      $nid = $node->id();
      $node = Node::load($nid);
      $node->field_feaured_node = 1;
      $node->save();

      //\Drupal::messenger()->addStatus('Featured node is added');
      $this->messenger()->addStatus('Featured node is added..');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    /** @var \Drupal\node\NodeInterface $object */
    $result = $object->access('create', $account, TRUE);
    return $return_as_object ? $result : $result->isAllowed();
  }

}

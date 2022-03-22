<?php

namespace Drupal\myplugin\Plugin\Action;

use Drupal\Core\Action\ActionBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\Entity\Node;

/**
 * Create custom action.
 *
 * @Action(
 *   id = "featured_node_action",
 *   label = @Translation("Featured Node"),
 *   type = "node"
 * )
 */
class FeaturedNodeAction extends ActionBase {

  /**
   * {@inheritdoc}
   */
  public function execute($node = NULL) {
    if ($node) {
      $nid = $node->id();
      $node = Node::load($nid);
      $node->field_feaured_node = 1;
      $node->save();

      \Drupal::messenger()->addStatus('Featured node is added');
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

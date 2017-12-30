<?php

namespace Drupal\bt_cms;

use Drupal\node\Entity\NodeRouteProvider;
use Drupal\Core\Entity\EntityTypeInterface;
//use Drupal\Core\Entity\Routing\EntityRouteProviderInterface;
use Symfony\Component\Routing\Route;
//use Symfony\Component\Routing\RouteCollection;

/**
 * Provides routes for nodes.
 */
class AlterNodeRouteProvider extends NodeRouteProvider {

  /**
   * {@inheritdoc}
   */
  public function getRoutes(EntityTypeInterface $entity_type) {
    $route_collection = parent::getRoutes($entity_type);

    $route = (new Route('/node/{node}/bt_block_form'))
        ->setDefault('_entity_form', 'node.bt_block_form')
        ->setRequirement('_entity_access', 'node.update')
        ->setRequirement('node', '\d+')
        ->setOption('_node_operation_route', TRUE);
    $route_collection->add('entity.node.bt_block_form', $route);

    $route = (new Route('/node/{node}/bt_by_default'))
        ->setDefault('_entity_form', 'node.bt_by_default')
        ->setRequirement('_entity_access', 'node.update')
        ->setRequirement('node', '\d+')
        ->setOption('_node_operation_route', TRUE);
    $route_collection->add('entity.node.bt_by_default', $route);

    return $route_collection;
  }
}
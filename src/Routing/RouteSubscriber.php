<?php

namespace Drupal\bt_cms\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  public function alterRoutes(RouteCollection $collection) {
    // Remove unused comment route.
    //$collection->remove(['comment.admin_approval']);

    // Change path of comment.admin.
    if ($route = $collection->get('comment.admin')) {
      $route->setPath('/app/website/comments');
    }

    // Change path '/node/add' to '/app/website/content/add'.
    if ($route = $collection->get('node.add_page')) {
      $route->setPath('/app/website/content/create');
      $route->setDefault('_controller', '\Drupal\bt_cms\Controller\CustomNodeController::addPage');
      $route->setDefault('_title', 'Create Content');
    }
    // Change path '/node/add/{node_type}'
    // to '/app/website/content/add/{node_type'.
    if ($route = $collection->get('node.add')) {
      $route->setPath('/app/website/content/create/{node_type}');
    }
    // Change path system.admin_content.
    if ($route = $collection->get('system.admin_content')) {
      $route->setPath('/app/website/content/');
    }
  }

}

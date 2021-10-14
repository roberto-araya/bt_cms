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
    // Change path of comment.admin.
    if ($route = $collection->get('comment.admin')) {
      $route->setPath('/app/website/content/comments');
      $route->setOption('_admin_route', TRUE);
    }

    if ($route = $collection->get('comment.admin_approval')) {
      $route->setPath('/app/website/content/comments/approval');
      $route->setOption('_admin_route', TRUE);
    }

    // Change path '/node/add' to '/app/website/content/add'.
    if ($route = $collection->get('node.add_page')) {
      $route->setPath('/app/website/content/create');
      $route->setDefaults([
        '_controller' => '\Drupal\bt_cms\Controller\CustomNodeController::addPage',
        '_title' => 'Create Content',
      ]);
    }
    // Change path '/node/add/{node_type}'
    // to '/app/website/content/add/{node_type}'.
    if ($route = $collection->get('node.add')) {
      $route->setPath('/app/website/content/create/{node_type}');
    }
    // Change path system.admin_content.
    if ($route = $collection->get('system.admin_content')) {
      $route->setPath('/app/website/content');
    }

    // $collection->remove(['system.admin_content']);
    // Change path.
    if ($route = $collection->get('content_moderation.admin_moderated_content')) {
      $route->setPath('/app/website/content/moderated');
      $route->setOption('_admin_route', TRUE);
    }

  }

}

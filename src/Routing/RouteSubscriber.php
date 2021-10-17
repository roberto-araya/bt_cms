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
    $routes = [
      'comment.admin' => '/app/website/content/comments',
      'comment.admin_approval' => '/app/website/content/comments/approval',
      'block.admin_display' => '/admin/appearance/block',
      'block_content.add_page' => '/app/website/block/add',
      'block_content.add_form' => '/app/website/block/add/{block_content_type}',
      'entity.block_content.collection' => '/app/website/blocks',
      'entity.block_content.canonical' => '/app/website/block/{block_content}',
      'entity.block_content.edit_form' => '/app/website/block/{block_content}',
      'entity.block_content.delete_form' => '/app/website/block/{block_content}/delete',
      'node.add_page' => '/app/website/content/create',
      'node.add' => '/app/website/content/create/{node_type}',
      'system.admin_content' => '/app/website/content',
      'content_moderation.admin_moderated_content' => '/app/website/content/moderated',
    ];

    foreach ($routes as $route_name => $path) {
      if ($route = $collection->get($route_name)) {
        $route->setPath($path);
      }

      if ($route_name == 'comment.admin' ||
          $route_name == 'comment.admin_approval' ||
          $route_name == 'content_moderation.admin_moderated_content') {
        $route->setOption('_admin_route', TRUE);
      }

      if ($route_name == 'node.add_page') {
        $route->setDefaults([
          '_controller' => '\Drupal\bt_cms\Controller\CustomNodeController::addPage',
          '_title' => 'Create Content',
        ]);
      }
    }
  }

}

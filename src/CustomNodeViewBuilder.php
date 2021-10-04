<?php

namespace Drupal\bt_cms;

use Drupal\node\NodeViewBuilder;
use Drupal\node\NodeInterface;

/**
 * View builder handler for nodes.
 */
class CustomNodeViewBuilder extends NodeViewBuilder {

  /**
   * Build the default links (Read more) for a node.
   *
   * @param \Drupal\node\NodeInterface $entity
   *   The node object.
   * @param string $view_mode
   *   A view mode identifier.
   *
   * @return array
   *   An array that can be processed by drupal_pre_render_links().
   */
  protected static function buildLinks(NodeInterface $entity, $view_mode) {
    $links = [];

    // Always display a read more link on teasers because we have no way
    // to know when a teaser view is different than a full view.
    $view_modes = [
      'teaser',
      'bt_box',
      'bt_card',
      'bt_credential',
      'bt_index',
      'bt_miniature',
      'bt_poster',
      'bt_teaser_image',
    ];

    if (in_array($view_mode, $view_modes)) {
      $node_title_stripped = strip_tags($entity->label());
      $links['node-readmore'] = [
        'title' => t(
          'Read more<span class="visually-hidden"> about @title</span>', [
            '@title' => $node_title_stripped,
          ]
        ),
        'url' => $entity->toUrl(),
        'language' => $entity->language(),
        'attributes' => [
          'rel' => 'tag',
          'title' => $node_title_stripped,
        ],
      ];
    }

    return [
      '#theme' => 'links__node__node',
      '#links' => $links,
      '#attributes' => ['class' => ['links', 'inline']],
    ];
  }

}

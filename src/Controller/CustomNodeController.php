<?php

namespace Drupal\bt_cms\Controller;

use Drupal\node\Controller\NodeController;

/**
 * Returns responses for Node routes.
 */
class CustomNodeController extends NodeController {

  /**
   * Alter list excluding faq and forum.
   */
  public function addPage() {
    $build = parent::addPage();
    unset($build['#content']['faq']);
    unset($build['#content']['forum']);
    unset($build['#content']['article']);
    unset($build['#content']['page']);
    return $build;
  }

}

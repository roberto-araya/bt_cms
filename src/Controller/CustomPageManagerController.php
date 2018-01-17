<?php

namespace Drupal\bt_cms\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\page_manager\PageInterface;

/**
 * Provides route controllers for Page Manager.
 */
class CustomPageManagerController extends ControllerBase {

  /**
   * Enables or disables a Page.
   *
   * @param \Drupal\page_manager\PageInterface $page
   *   The page entity.
   * @param string $op
   *   The operation to perform, usually 'enable' or 'disable'.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   A redirect back to the pages list page.
   */
  public function pageOperation(PageInterface $page, $op) {
    $page->$op()->save();

    if ($op == 'enable') {
      drupal_set_message(t('The %label page has been enabled.', ['%label' => $page->label()]));
    }
    elseif ($op == 'disable') {
      drupal_set_message(t('The %label page has been disabled.', ['%label' => $page->label()]));
    }

    return $this->redirect('bt_page_drag_and_drop');
  }

}

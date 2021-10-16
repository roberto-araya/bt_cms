<?php

namespace Drupal\bt_cms\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Link;
use Drupal\Core\Config\ConfigFactory;

/**
 * CMS Breadcrumbs.
 *
 * @package Drupal\bt_cms\Breadcrumb
 */
class BreadcrumbBuilder implements BreadcrumbBuilderInterface {

  /**
   * The Site name.
   *
   * @var string
   */
  protected $siteName;

  /**
   * The routes that will change their breadcrumbs.
   *
   * @var array
   */
  private $routes = [
    'entity.node.edit_form',
    'node.add',
    'node.add_page',
    'bt_cms.add_block',
    'block_content.add_form',
    'bt_core.app',
    'bt_cms.website',
    'system.admin_content',
    'bt_cms.website_blocks',
    'view.bt_files.page_1',
  ];

  /**
   * Class constructor.
   */
  public function __construct(ConfigFactory $configFactory) {
    $this->siteName = $configFactory->get('system.site')->get('name');
  }

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    $match = $this->routes;
    if (in_array($route_match->getRouteName(), $match)) {
      if ($route_match->getRouteName() == 'entity.node.edit_form') {
        if ($route_match->getParameters()->get('node')->bundle() != 'faq' || $route_match->getParameters()->get('node')->bundle() != 'forum') {
          return TRUE;
        }
        else {
          return FALSE;
        }
      }
      elseif ($route_match->getRouteName() == 'node.add') {
        if ($route_match->getParameters()->get('node_type')->get('type') != 'faq' || $route_match->getParameters()->get('node_type')->get('type') != 'forum') {
          return TRUE;
        }
        else {
          return FALSE;
        }
      }
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match) {
    $route = $route_match->getRouteName();
    $breadcrumb = new Breadcrumb();
    $breadcrumb->addCacheContexts(["url"]);

    if ($route == 'bt_cms.website') {
      $breadcrumb->addLink(Link::createFromRoute($this->siteName, 'bt_core.app'));
    }
    else {
      $breadcrumb->addLink(Link::createFromRoute($this->siteName, 'bt_core.app'));
      $breadcrumb->addLink(Link::createFromRoute('Website', 'bt_cms.website'));
    }

    if (in_array(
        $route,
        ['entity.node.edit_form', 'node.add', 'node.add_page'])
      ) {
      $breadcrumb->addLink(Link::createFromRoute('Content', 'system.admin_content'));
      if ($route == 'node.add') {
        $breadcrumb->addLink(Link::createFromRoute('Create Content', 'node.add_page'));
      }
    }

    if (in_array($route, ['bt_cms.add_block', 'block_content.add_form'])) {
      $breadcrumb->addLink(Link::createFromRoute('Blocks', 'bt_cms.website_blocks'));
      if ($route == 'block_content.add_form') {
        $breadcrumb->addLink(Link::createFromRoute('Add Block', 'bt_cms.add_block'));
      }
    }

    return $breadcrumb;
  }

}

<?php

namespace Drupal\bt_cms\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Session\AccountProxy;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Link;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\Entity\User;

class BreadcrumbBuilder implements BreadcrumbBuilderInterface {

    /**
     * @var AccountInterface
     */
    protected $account;

    /**
     * The routes that will change their breadcrumbs.
     */
    private $routes = array(
        'entity.node.edit_form',
        'node.add',
        'node.add_page',
        'bt_add_block',
        'block_content.add_form',
        'page_manager.page_view_app_app-panels_variant-0',
        'page_manager.page_view_app_website_app_website-panels_variant-0',
        'page_manager.page_view_app_website_content_app_website_content-panels_variant-0',
        'page_manager.page_view_app_website_content_app_website_content-panels_variant-1',
        'page_manager.page_view_app_website_comments_app_website_comments-panels-variant-0',
        'page_manager.page_view_app_website_documents_app_website_documents-panels_variant-0',
        'page_manager.page_view_app_website_documents_app_website_documents-panels_variant-1',
        'page_manager.page_view_app_website_media_app_website_media-panels_variant-0',
        'page_manager.page_view_app_website_media_app_website_media-panels_variant-1',
        'page_manager.page_view_app_website_polls_app_website_polls-panels_variant-0',
        'page_manager.page_view_app_website_foros_app_website_foros-panels_variant-0',
        'page_manager.page_view_app_website_blocks_app_website_blocks-panels_variant-0',
        'poll.poll_add',
        'entity.poll.edit_form',
        'bt_page_drag_and_drop',
        'bt_create_ipe_page',
        'bt_edit_ipe_page',
        'bt_delete_ipe_page',
    );

    /**
     * Class constructor.
     */
    public function __construct(AccountProxy $current_user) {
        $user_id = $current_user->id();
        $user_account = User::load($user_id);
        $this->account = $user_account;
    }

    /**
    * {@inheritdoc}
    */
    public function applies(RouteMatchInterface $attributes) {
        $match = $this->routes;
        if (in_array($attributes->getRouteName(),$match)) {
            return TRUE;
        }else{
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
        $site_name = \Drupal::config('system.site')->get('name');

        if ($route == 'page_manager.page_view_app_app-panels_variant-0') {
            $breadcrumb->addLink(Link::createFromRoute('Home', 'page_manager.page_view_ipe_home_ipe_home-panels_variant-0'));
        }elseif ($route == 'page_manager.page_view_app_website_app_website-panels_variant-0') {
            $breadcrumb->addLink(Link::createFromRoute($site_name, 'page_manager.page_view_app_app-panels_variant-0'));
        }else{
            $breadcrumb->addLink(Link::createFromRoute($site_name, 'page_manager.page_view_app_app-panels_variant-0'));
            $breadcrumb->addLink(Link::createFromRoute('Website', 'page_manager.page_view_app_website_app_website-panels_variant-0'));
        }
        switch ($route) {
            case 'entity.node.edit_form':
                $breadcrumb->addLink(Link::createFromRoute('Content', 'page_manager.page_view_app_website_content_app_website_content-panels_variant-0'));
                break;
            case 'node.add':
                $node_type = $route_match->getParameters()->get('node_type')->get('type');
                if ($node_type != 'faq' && $node_type != 'forum') {
                    $breadcrumb->addLink(Link::createFromRoute('Content', 'page_manager.page_view_app_website_content_app_website_content-panels_variant-0'));
                    $breadcrumb->addLink(Link::createFromRoute('Create Content', 'node.add_page'));
                }
                break;
            case 'node.add_page':
                $breadcrumb->addLink(Link::createFromRoute('Content', 'page_manager.page_view_app_website_content_app_website_content-panels_variant-0'));
                break;
            case 'bt_add_block':
                $breadcrumb->addLink(Link::createFromRoute('Blocks', 'page_manager.page_view_app_website_blocks_app_website_blocks-panels_variant-0'));
                break;
            case 'block_content.add_form':
                $breadcrumb->addLink(Link::createFromRoute('Blocks', 'page_manager.page_view_app_website_blocks_app_website_blocks-panels_variant-0'));
                $breadcrumb->addLink(Link::createFromRoute('Add Block', 'bt_add_block'));
                break;
        }
        $pages = array(
            'bt_create_ipe_page',
            'bt_edit_ipe_page',
            'bt_delete_ipe_page',
        );
        if (in_array($route, $pages)) {
            $breadcrumb->addLink(Link::createFromRoute('Pages', 'bt_page_drag_and_drop'));
        }

        return $breadcrumb;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 24-11-17
 * Time: 19:27
 */

namespace Drupal\bt_cms\Plugin\Menu\LocalTask;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\Core\Path\PathValidatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContentDeriverLocalTask extends DeriverBase implements ContainerDeriverInterface {

    /**
     * The path.validator service.
     *
     * @var Drupal\Core\Path\PathValidatorInterface;
     */
    protected $path_validator;

    /**
     *
     * @param Drupal\Core\Path\PathValidatorInterface $path_validator;
     *
     */
    public function __construct(PathValidatorInterface $path_validator) {
        $this->path_validator = $path_validator;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container, $base_plugin_id) {
        return new static(
            $container->get('path.validator')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getDerivativeDefinitions($base_plugin_definition) {
        $route = $this->path_validator->getUrlIfValid('/app/website/content')->getRouteName();
        ksm($route);

        // Implement dynamic logic to provide values for the same keys as in example.links.task.yml.
        $this->derivatives[$route] = $base_plugin_definition;
        $this->derivatives[$route]['route_name'] = $route;

        return $this->derivatives;
    }
}
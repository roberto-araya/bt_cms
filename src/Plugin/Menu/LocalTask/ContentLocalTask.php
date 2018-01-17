<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 24-11-17
 * Time: 15:10
 */

namespace Drupal\bt_cms\Plugin\Menu\LocalTask;

use Drupal\Core\Menu\LocalTaskDefault;
//use Drupal\Core\Path\PathValidatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;


class ContentLocalTask extends LocalTaskDefault {

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return ['user.roles'];
  }
}
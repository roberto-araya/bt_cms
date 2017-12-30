<?php
/**
 *
 */

namespace Drupal\bt_cms\Controller;

use Drupal\Core\Url;
use Drupal\page_manager\Entity\Page;

class UserIpePageList
{

    function build() {
        // bring all the pages id that have as id the prefix 'ipe'.
        $query = \Drupal::entityQuery('page')->condition('id', 'ipe_', 'CONTAINS');
        $pages_ids = $query->execute();

        // load all pages
        $pages = Page::loadMultiple($pages_ids);

        // prepare the table header
        $header['name'] = t('Title');
        $header['description'] = t('Description');
        $header['path'] = t('Path');
        $header['operations'] = t('Operations');

        // pre build table
        $build['table'] = array(
            '#type' => 'table',
            '#header' => $header,
            '#title' => '',
            '#rows' => array(),
            '#empty' => t('There are currently no pages. <a href=":url">Add a new page.</a>', [':url' => Url::fromRoute('bt_create_ipe_page')->toString()]),
            //'#cache' => [
            //    'contexts' => $this->entityType->getListCacheContexts(),
            //    'tags' => $this->entityType->getListCacheTags(),
            //],
        );

        // build the rows of the table

        foreach ($pages as $entity) {
            // build operations links.
            $operations = array();
            if (!$entity->status() && $entity->hasLinkTemplate('enable')) {
                $url = new Url('bt_enable_disable_ipe_page', ['page' => $entity->id(), 'op' => 'enable']);
                $operations['enable'] = array(
                    'title' => t('Enable'),
                    'weight' => -10,
                    'url' => $url,
                );
            }
            if ($entity->access('update') && $entity->hasLinkTemplate('edit-form')) {
                $url = new Url('bt_edit_ipe_page', ['page' => $entity->id()]);
                $operations['edit'] = array(
                    'title' => t('Edit'),
                    'weight' => 10,
                    'url' => $url,
                );
            }
            if ($entity->status() && $entity->hasLinkTemplate('disable')) {
                $url = new Url('bt_enable_disable_ipe_page', ['page' => $entity->id(), 'op' => 'disable']);
                $operations['disable'] = array(
                    'title' => t('Disable'),
                    'weight' => 40,
                    'url' => $url,
                );
            }
            if ($entity->access('delete') && $entity->hasLinkTemplate('delete-form')) {
                //
                $url = new Url('bt_delete_ipe_page', ['page' => $entity->id()]);
                $operations['delete'] = array(
                    'title' => t('Delete'),
                    'weight' => 100,
                    'url' => $url,
                );
            }
            $render_operations = [
                '#type' => 'operations',
                '#links' => $operations,
            ];


            if ($entity->status()) {
                // build label
                $label = [
                    'data' => [
                        '#type' => 'link',
                        '#url' => Url::fromUserInput(rtrim($entity->getPath(), '/')),
                        '#title' => $entity->label(),
                    ]
                ];
            } else {
                $label = $entity->label();
            }

            // fill row
            $row['name'] = $label;
            $row['description'] = $entity->get('description');
            $row['path'] = $entity->getPath();
            $row['operations']['data'] = $render_operations;
            //$row['operations']['data'] = $this->buildOperations($entity);

            $disable = array();
            if ($entity->status()) {
                $build['table']['#rows'][$entity->id()] = $row;
            }else{
                $disable = [$entity->id() => $row];
            }

        }
        $build['table']['#rows'] = array_merge($build['table']['#rows'],$disable);
        // Only add the pager if a limit is specified.
        //if ($this->limit) {
        //    $build['pager'] = array(
        //        '#type' => 'pager',
        //    );
        //}
        return $build;
        // cargar las páginas con $entity->load() para preparar los datos para la tabla
        // cargar las operaciones y prepararlas para la tabla

        // construir y retornar una tabla con las páginas.
    }
}
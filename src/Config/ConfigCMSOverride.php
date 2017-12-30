<?php
/**
 *
 */

namespace Drupal\bt_cms\Config;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryOverrideInterface;

/**
 * Example configuration override.
 */
class ConfigCMSOverride implements ConfigFactoryOverrideInterface {

    public function loadOverrides($names) {
        $overrides = array();

        if (in_array('media_entity.bundle.image', $names)) {
            //$overrides['media_entity.bundle.image']['label'] = 'Imagen';
            //$overrides['media_entity.bundle.image']['description'] = 'Almacene una imagen localmente en su biblioteca multimedia.';
        }
        if (in_array('field.field.media.image.image', $names)) {
            //$overrides['field.field.media.image.image']['label'] = 'Imagen';
            //$overrides['field.field.media.image.image']['description'] = 'Suba una imagen a su catálogo de imágenes de la aplicación.';
            $overrides['field.field.media.image.image']['settings']['file_directory'] = 'media_imagen';
            $overrides['field.field.media.image.image']['settings']['alt_field'] = TRUE;
            $overrides['field.field.media.image.image']['settings']['alt_field_required'] = FALSE;
            $overrides['field.field.media.image.image']['settings']['title_field'] = TRUE;
            $overrides['field.field.media.image.image']['settings']['title_field_required'] = FALSE;
        }
        if (in_array('core.entity_view_mode.media.slick', $names)) {
            $overrides['core.entity_view_mode.media.slick']['label'] = 'Slideshow';
        }
        if (in_array('core.entity_view_mode.media.library_thumbnail', $names)) {
            //$overrides['core.entity_view_mode.media.library_thumbnail']['label'] = 'Miniatura';
        }
        // Workbench moderations settings
        $third_party_settings = [
            'workbench_moderation' => [
                'enabled' => TRUE,
                'allowed_moderation_states' => ['published', 'archived', 'draft', 'needs_review'],
                'default_moderation_state' => 'draft'
            ]
        ];
        // Add workbench moderation to image bundle
        if (in_array('media_entity.bundle.image', $names)) {
            $overrides['media_entity.bundle.image']['third_party_settings'] = $third_party_settings;
        }
        // Add workbench moderation to slideshow bundle
        if (in_array('media_entity.bundle.slideshow', $names)) {
            $overrides['media_entity.bundle.slideshow']['third_party_settings'] = $third_party_settings;
        }
        return $overrides;
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheSuffix() {
        return 'ConfigCMSOverride';
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheableMetadata($name) {
        return new CacheableMetadata();
    }

    /**
     * {@inheritdoc}
     */
    public function createConfigObject($name, $collection = StorageInterface::DEFAULT_COLLECTION) {
        return NULL;
    }

}
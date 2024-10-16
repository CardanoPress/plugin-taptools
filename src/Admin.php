<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace PBWebDev\CardanoPress\TapTools;

use CardanoPress\Foundation\AbstractAdmin;
use CardanoPress\Dependencies\ThemePlate\CPT\PostType;

class Admin extends AbstractAdmin
{
    public const OPTION_KEY = 'cp-taptools';

    protected function initialize(): void
    {
    }

    public function setupHooks(): void
    {
        $this->settingsPage('CardanoPress - TapTools', [
            'parent' => 'edit.php?post_type=taptool',
            'menu_title' => 'Settings',
        ]);
        $this->registerPostType();

        add_action('init', function () {
            $this->settingsFields();
        }, 11);
        add_action(Installer::DATA_PREFIX . 'activating', [$this, 'pluginActivating']);
    }

    public function pluginActivating(): void
    {
        $this->registerPostType();
        flush_rewrite_rules();
    }

    public function settingsFields(): void
    {
        $this->optionFields(__('API Key', 'cardanopress-governance'), [
            'data_prefix' => 'api_',
            'fields' => [
                'key' => [
                    'type' => 'text',
                ],
            ],
        ]);
    }

    private function registerPostType(): void
    {
        $postType = new PostType('taptool', [
            'menu_position' => 5,
            'menu_icon' => 'dashicons-chart-area',
            'supports' => ['title'],
            'public' => false,
            'show_ui' => true,
        ]);

        $postType->labels(__('Widget', 'cardanopress-taptools'), __('Widgets', 'cardanopress-taptools'));
        $postType->register();
    }
}

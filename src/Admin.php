<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace PBWebDev\CardanoPress\TapTools;

use CardanoPress\Foundation\AbstractAdmin;
use CardanoPress\Dependencies\ThemePlate\CPT\PostType;
use CardanoPress\Dependencies\ThemePlate\Meta\PostMeta;

class Admin extends AbstractAdmin
{
    public const OPTION_KEY = 'cp-taptools';

    protected function initialize(): void
    {
    }

    public function setupHooks(): void
    {
        add_filter('post_type_labels_taptool', [$this, 'postTypeLabels']);

        $this->settingsPage('CardanoPress - TapTools', [
            'parent' => 'edit.php?post_type=taptool',
            'menu_title' => 'Settings',
        ]);
        $this->registerPostType();
        $this->poolSettingsMetaBox();

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
        $this->optionFields(__('API Key', 'cardanopress-taptools'), [
            'data_prefix' => 'api_',
            'fields' => [
                'key' => [
                    'type' => 'text',
                ],
            ],
        ]);
    }

    public function postTypeLabels(object $labels): object
    {
        $labels->menu_name = __('Tap Tools', 'cardanopress-taptools');

        return $labels;
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

    private function poolSettingsMetaBox(): void
    {
        $postMeta = new PostMeta(__('TapTools Widget Settings', 'cardanopress-taptools'), [
            'data_prefix' => 'widget_',
        ]);

        $postMeta->fields([
            'display' => [
                'title' => __('Display', 'cardanopress-taptools'),
                'type' => 'radio',
                'options' => [
                    'single' => 'Single Token',
                    'multiple' => 'Multiple Tokens',
                ],
                'default' => 'single',
            ],
            'token' => [
                'title' => __('Token', 'cardanopress-taptools'),
                'description' => __('Unit (policy + hex name)', 'cardanopress-taptools'),
                'type' => 'text',
                'show_on' => [
                    'operator' => 'contains',
                    'key' => '#themeplate_widget_display',
                    'value' => 'single',
                ],
            ],
            'style' => [
                'title' => __('Style', 'cardanopress-taptools'),
                'type' => 'radio',
                'options' => [
                    'table' => 'Table',
                    'ticker' => 'Ticker',
                ],
                'default' => 'table',
                'show_on' => [
                    'operator' => 'contains',
                    'key' => '#themeplate_widget_display',
                    'value' => 'multiple',
                ],
            ],
            'type' => [
                'title' => __('Type', 'cardanopress-taptools'),
                'type' => 'radio',
                'options' => [
                    'top' => 'Top 10',
                    'custom' => 'Custom',
                ],
                'default' => 'top',
                'show_on' => [
                    'operator' => 'contains',
                    'key' => '#themeplate_widget_display',
                    'value' => 'multiple',
                ],
            ],
            'tokens' => [
                'title' => __('Tokens', 'cardanopress-taptools'),
                'description' => __('Unit (policy + hex name)<br><br>One per line.', 'cardanopress-taptools'),
                'type' => 'textarea',
                'show_on' => [[
                    [
                        'operator' => 'contains',
                        'key' => '#themeplate_widget_display',
                        'value' => 'multiple',
                    ],
                    [
                        'operator' => 'contains',
                        'key' => '#themeplate_widget_type',
                        'value' => 'custom',
                    ],
                ]],
            ]
        ]);

        $postMeta->location('taptool')->create();
    }
}

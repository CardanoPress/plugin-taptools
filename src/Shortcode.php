<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace PBWebDev\CardanoPress\TapTools;

use CardanoPress\Interfaces\HookInterface;

class Shortcode implements HookInterface
{
    protected Application $application;

    public function __construct()
    {
        $this->application = Application::getInstance();
    }

    public function setupHooks(): void
    {
        add_shortcode('cp-taptools_widget', [$this, 'doWidget']);
    }

    public function doWidget(array $attributes): string
    {
        $args = shortcode_atts([
            'id' => '',
        ], $attributes);

        if (empty($args['id'])) {
            return '';
        }

        $display = get_post_meta($args['id'], 'widget_display', true);

        if ('single' === $display) {
            $token = get_post_meta($args['id'], 'widget_token', true);

            return print_r($token, true);
        }

        $style = get_post_meta($args['id'], 'widget_style', true);
        $type = get_post_meta($args['id'], 'widget_type', true);

        return print_r(compact('style', 'type'), true);
    }
}

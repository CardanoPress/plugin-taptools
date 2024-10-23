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
    protected Api $api;

    public function __construct()
    {
        $this->application = Application::getInstance();
        $this->api = new Api($this->application->option('api_key'));
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
            $result = $this->api->getTokenPrices([$token]);

            return $result[$token];
        }

        $style = get_post_meta($args['id'], 'widget_style', true);
        $type = get_post_meta($args['id'], 'widget_type', true);

        if ('top' === $type) {
            $result = $this->api->getTopMarketCapTokens();
            $result = array_column($result, 'price', 'unit');
        } else {
            $tokens = get_post_meta($args['id'], 'widget_tokens', true);
            $tokens = array_filter(preg_split('/\r\n|[\r\n]/', $tokens));
            $result = $this->api->getTokenPrices($tokens);
        }

        return print_r(compact('result', 'style', 'type'), true);
    }
}

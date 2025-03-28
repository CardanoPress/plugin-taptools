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

        $display = get_post_meta((int) $args['id'], 'widget_display', true);

        if ('single' === $display) {
            $token = get_post_meta((int) $args['id'], 'widget_token', true);
            $asset = $this->api->getTokenById($token);

            if (empty($asset) || empty($asset['asset'])) {
                return '';
            }

            $result = $this->api->getTokenPrices([$token]);
            $result = $result[$token];
            $token = $asset['asset'];

            ob_start();
            $this->application->template('label', compact('result', 'token'));

            return ob_get_clean();
        }

        $style = get_post_meta((int) $args['id'], 'widget_style', true);
        $type = get_post_meta((int) $args['id'], 'widget_type', true);

        if ('top' === $type) {
            $result = $this->api->getTopMarketCapTokens();
            $result = array_column($result, 'price', 'unit');
        } else {
            $tokens = get_post_meta((int) $args['id'], 'widget_tokens', true);
            $tokens = array_filter(preg_split('/\r\n|[\r\n]/', $tokens));
            $result = $this->api->getTokenPrices($tokens);
        }

        $tokens = array_keys($result);
        $tokens = array_combine($tokens, array_map(function ($unit) {
            $asset = $this->api->getTokenById($unit);

            if (empty($asset) || empty($asset['asset'])) {
                return array();
            }

            return $asset['asset'];
        }, $tokens));

        ob_start();
        $this->application->template($style, compact('result', 'tokens'));

        return ob_get_clean();
    }
}

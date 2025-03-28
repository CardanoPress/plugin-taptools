<?php // phpcs:ignore PSR1.Files.SideEffects.FoundWithSymbols

/**
 * Plugin Name: CardanoPress - TapTools
 * Plugin URI:  https://github.com/CardanoPress/plugin-taptools
 * Author:      CardanoPress
 * Author URI:  https://cardanopress.io
 * Description: A CardanoPress extension for TapTools
 * Version:     0.1.0
 * License:     GPL-2.0-only
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain: cardanopress-taptools
 *
 * Requires at least: 5.9
 * Requires PHP:      7.4
 *
 * Requires Plugins: cardanopress
 *
 * @package ThemePlate
 * @since   0.1.0
 */

// Accessed directly
if (! defined('ABSPATH')) {
    exit;
}

use PBWebDev\CardanoPress\TapTools\Application;
use PBWebDev\CardanoPress\TapTools\Installer;

/* ==================================================
Global constants
================================================== */

if (! defined('CP_TAPTOOLS_FILE')) {
    define('CP_TAPTOOLS_FILE', __FILE__);
}

// Load the main plugin class
require_once plugin_dir_path(CP_TAPTOOLS_FILE) . 'dependencies/vendor/autoload_packages.php';

// Instantiate the updater
// phpcs:ignore Generic.Files.LineLength.TooLong
EUM_Handler::run(CP_TAPTOOLS_FILE, 'https://raw.githubusercontent.com/CardanoPress/plugin-taptools/main/update-data.json');

// Instantiate
function cpTapTools(): Application
{
    static $application;

    if (null === $application) {
        $application = new Application(CP_TAPTOOLS_FILE);
    }

    return $application;
}

cpTapTools()->setupHooks();
(new Installer(cpTapTools()))->setupHooks();

<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://boomdevs.com
 * @package           Wpbm
 *
 * @wordpress-plugin
 * Plugin Name:       Product Badge Manager For WooCommerce
 * Plugin URI:        https://boomdevs.com/product/woocommerce-product-badge-manager/
 * Description:       Product badge manager for WooCommerce let's you create unlimited promotional prodcut badges and assign them with your WooCommerce product based on category, popularity, sale, time etc and also filter product archives using badges.
 * Version:           1.2.4
 * Author:            BoomDevs
 * Author URI:        https://boomdevs.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpbm
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

require __DIR__ . '/vendor/autoload.php';

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

/**
 * Plugin basic information.
 */
define('WPBM_DIR', plugin_dir_path(__FILE__));
define('WPBM_BASE_NAME', plugin_basename(__FILE__));
define('WPBM_NAME', 'wpbm');
define('WPBM_FULL_NAME', 'Product Badge Manager For WooCommerce');
define('WPBM_VERSION', '1.2.4');




/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function appsero_init_tracker_product_badge_manager_for_woocommerce()
{
    if (!class_exists('Appsero\Client')) {
        require_once __DIR__ . '/appsero/src/Client.php';
    }

    $client = new Appsero\Client(
        '16aee88b-ffe8-46bc-be57-976261b14a34',
        'Product Badge Manager For Woocommerce',
        __FILE__
    );

    // Active insights
    $client->insights()->init();
}

appsero_init_tracker_product_badge_manager_for_woocommerce();

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpbm-activator.php
 */
function activate_wpbm()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wpbm-activator.php';
    Wpbm_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpbm-deactivator.php
 */
function deactivate_wpbm()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wpbm-deactivator.php';
    Wpbm_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_wpbm');
register_deactivation_hook(__FILE__, 'deactivate_wpbm');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-wpbm.php';

do_action('woocommerce_product_badge_manager/loaded');

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */

add_action('plugins_loaded', 'run_wpbm', 2);

// Show admin notice if required plugin isn't activate
if (!is_plugin_active('woocommerce/woocommerce.php')) {
    add_action('all_admin_notices', 'wpbm_woocommerce_required_notice');
    function wpbm_woocommerce_required_notice()
    {
        $plugin_data = get_plugin_data(__FILE__);

        echo sprintf(
            '<div class="error">
                <p><strong>%1$s:</strong> You must install <a href="https: //wordpress.org/plugins/woocommerce/"><strong>%2$s</strong></a> to use %1$s.</p>
            </div>',
            $plugin_data['Name'],
            __('WooCommerce', 'wpbm')
        );
    }
}

// check up and load all required file
if (is_plugin_active('woocommerce/woocommerce.php')) {
    function run_wpbm() {
        $plugin = new Wpbm();
        $plugin->run();
    }
}
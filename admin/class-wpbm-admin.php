<?php
defined( 'ABSPATH' ) || exit;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://boomdevs.com
 * @package    Wpbm
 * @subpackage Wpbm/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpbm
 * @subpackage Wpbm/admin
 * @author     BoomDevs <contact@boomdevs.com>
 */
class Wpbm_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpbm_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpbm_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name . 'admin', plugin_dir_url( __FILE__ ) . 'css/wpbm-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpbm_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpbm_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpbm-admin.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * Custom links for pro buttons
     *
     * @param $actions
     * @return array
     */
    function wpbm_add_action_plugin( $actions )
    {
		if(! class_exists('Woocommerce_Product_Badge_Manager_Pro')){
			$prolink = array(
				'<a class="wpbm_pro_button" target="_blank" href="https://codecanyon.net/item/woocommerce-product-badge-manager/11736039">' . __('Go Pro', 'wpbm') . '</a>',
			);
			$actions = array_merge( $actions, $prolink );
		}
        return $actions;
    }

}

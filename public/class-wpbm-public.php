<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://boomdevs.com
 *
 * @package    Wpbm
 * @subpackage Wpbm/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wpbm
 * @subpackage Wpbm/public
 * @author     BoomDevs <contact@boomdevs.com>
 */
class Wpbm_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		// Tooltipster assets
        wp_enqueue_style( $this->plugin_name . '-tooltipster', plugin_dir_url( __FILE__ ) . 'css/tooltipster.css', array(), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name . '_free_public', plugin_dir_url( __FILE__ ) . 'css/wpbm-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

        // Tooltipster assets
        wp_enqueue_script( $this->plugin_name . '-tooltipster', plugin_dir_url( __file__ ) . 'js/jquery.tooltipster.min.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name . '_free_public', plugin_dir_url( __file__ ) . 'js/wpbm-public.js', array( 'jquery' ), $this->version, false );

	}

}

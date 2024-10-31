<?php
defined( 'ABSPATH' ) || exit;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://boomdevs.com
 * @package    Wpbm
 * @subpackage Wpbm/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @package    Wpbm
 * @subpackage Wpbm/includes
 * @author     BoomDevs <contact@boomdevs.com>
 */
class Wpbm {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @access   protected
	 * @var      Wpbm_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

    /**
     * The real name of this plugin.
     *
     * @access   protected
     * @var      string    $plugin_full_name    The full punctual name of this plugin.
     */
	protected $plugin_full_name;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 */
	public function __construct() {
		if ( defined( 'WPBM_VERSION' ) ) {
			$this->version = WPBM_VERSION;
		} else {
			$this->version = '1.2.4';
		}

        if ( defined( 'WPBM_FULL_NAME' ) ) {
            $this->plugin_full_name = WPBM_FULL_NAME;
        } else {
            $this->plugin_full_name = 'Product Badge Manager For WooCommerce';
        }

        if ( defined( 'WPBM_NAME' ) ) {
            $this->plugin_name = WPBM_NAME;
        } else {
            $this->plugin_name = 'wpbm';
        }


		$this->load_dependencies();
		$this->set_locale();
		$this->register_settings();
		$this->register_custom_posts();
		$this->register_meta_boxes();
		$this->register_shortcodes();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wpbm_Loader. Orchestrates the hooks of the plugin.
	 * - Wpbm_i18n. Defines internationalization functionality.
	 * - Wpbm_Admin. Defines all hooks for the admin area.
	 * - Wpbm_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once WPBM_DIR . 'includes/class-wpbm-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once WPBM_DIR . 'includes/class-wpbm-i18n.php';

        /**
         * Codestar library
         */
        require_once WPBM_DIR . 'libs/codestar-framework/codestar-framework.php';

        /**
         * Metabox.io library
         */
        require_once WPBM_DIR . 'libs/meta-box/meta-box.php';

        /**
         * The class responsible for loading all the admin settings of the plugin.
         */
        require_once WPBM_DIR . 'includes/class-wpbm-settings.php';

        /**
         * The class responsible for defining all custom posts actions.
         */
        require_once WPBM_DIR . 'includes/class-wpbm-custom-post.php';

        /**
         * The class responsible for defining all custom meta boxes.
         */
        require_once WPBM_DIR . 'includes/class-wpbm-meta-box.php';

        /**
         * The class responsible for defining all custom shortcodes.
         */
        require_once WPBM_DIR . 'includes/class-wpbm-shortcode.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once WPBM_DIR . 'admin/class-wpbm-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once WPBM_DIR . 'public/class-wpbm-public.php';

        /**
         * Public facing WooCommerce helper of the plugin.
         */
        require_once WPBM_DIR . 'public/class-wpbm-woocommerce-public.php';


		$this->loader = new Wpbm_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wpbm_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wpbm_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

    /**
     * Register plugin settings.
     *
     * @access   private
     */
    private function register_settings() {
        $plugin_settings = new Wpbm_Settings( $this->plugin_full_name );

        $plugin_settings->register_admin_settings_panel();
    }

    /**
     * Register plugin custom posts.
     *
     * @access   private
     */
    private function register_custom_posts() {
        $custom_posts = new Wpbm_Custom_Post();

        $this->loader->add_action( 'init', $custom_posts, 'register_badge_custom_post' );
    }

    /**
     * Register plugin meta boxes.
     *
     * @access   private
     */
    private function register_meta_boxes() {
        $meta_boxes = new Wpbm_Meta_Box();

        $this->loader->add_filter( 'rwmb_meta_boxes', $meta_boxes, 'register_badge_meta_boxes' );
        $this->loader->add_filter( 'rwmb_meta_boxes', $meta_boxes, 'register_product_meta_boxes' );
    }

    /**
     * Register plugin shortcodes.
     *
     * @access   private
     */
    private function register_shortcodes() {
        $shortcodes = new Wpbm_Shortcode();

        add_shortcode( 'woo_pro_badges', array( $shortcodes, 'create_product_single_page_badge_shortcode' ) );
        add_shortcode( 'woo_pro_badges_extended', array( $shortcodes, 'create_product_single_page_badge_extended_shortcode' ) );
        add_shortcode( 'woo_pro_badges_catalogue_extended', array( $shortcodes, 'create_product_catalogue_page_badge_extended_shortcode' ) );
        add_shortcode( 'woo_author_badges', array( $shortcodes, 'create_author_badges_shortcode' ) );
    }

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wpbm_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

        $this->loader->add_filter( 'plugin_action_links_'.WPBM_BASE_NAME, $plugin_admin,'wpbm_add_action_plugin', 15, 2);

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wpbm_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$woocommerce_helper = new Wpbm_WooCommerce_Public();

        // Insert badge in WooCommerce pages
		$woocommerce_helper->insert_badges_into_product_single_page();
        $woocommerce_helper->insert_badges_into_product_loop();
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Wpbm_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

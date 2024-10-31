<?php
defined( 'ABSPATH' ) || exit;

require_once WPBM_DIR . 'includes/class-wpbm-settings.php';

/**
 * Handle plugin custom posts for this plugin.
 *
 * @link       https://boomdevs.com
 * @package    Wpbm
 * @subpackage Wpbm/includes
 */

/**
 * Handle plugin custom posts
 *
 * This class defines all code necessary to define custom posts for this plugin.
 *
 * @package    Wpbm
 * @subpackage Wpbm/includes
 * @author     BoomDevs <contact@boomdevs.com>
 */
class Wpbm_Custom_Post {
    public static $badge_post_type = 'woo_product_badges';
    public static $custom_slug;

    public function __construct() {
         $settings = Wpbm_Settings::get_settings();
         if($settings && array_key_exists('custom_badge_permalink', $settings) && $settings['custom_badge_permalink'] !== '') {
             Wpbm_Custom_Post::$custom_slug = array('slug' => $settings['custom_badge_permalink']);
         }
    }

    /**
     * Register badge post type.
     */
    public function register_badge_custom_post() {
        // Set UI labels for badge custom Post Type
        $labels = array(
            'name'                  => __( 'Woo Product Badges', 'wpbm' ),
            'singular_name'         => __( 'Product Badge', 'wpbm' ),
            'menu_name'             => __( 'Woo Product Badges', 'wpbm' ),
            'parent_item_colon'     => __( 'Parent Badge', 'wpbm' ),
            'all_items'             => __( 'All Badges', 'wpbm' ),
            'view_item'             => __( 'View Badge', 'wpbm' ),
            'add_new_item'          => __( 'Add New Badge', 'wpbm' ),
            'add_new'               => __( 'Add New Badge', 'wpbm' ),
            'edit_item'             => __( 'Edit Badge', 'wpbm' ),
            'update_item'           => __( 'Update Badge', 'wpbm' ),
            'search_items'          => __( 'Search Badges', 'wpbm' ),
            'not_found'             => __( 'No Badges Found', 'wpbm' ),
            'not_found_in_trash'    => __( 'No Badges Found In Trash', 'wpbm' ),
            'new_item'              => __( 'New Badge', 'wpbm' ),
        );

        // Set other options for badge custom Post Type
        $args = array(
            'label'               => __( 'Woo Product Badges', 'wpbm' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'thumbnail', 'editor' ),
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'rewrite' => Wpbm_Custom_Post::$custom_slug,
            'show_in_admin_bar'   => true,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'query_var'           => true,
        );

        // Registering your Custom Post Type
        register_post_type( Wpbm_Custom_Post::$badge_post_type, $args );
    }
}
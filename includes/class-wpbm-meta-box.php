<?php
defined( 'ABSPATH' ) || exit;

require_once WPBM_DIR . 'includes/class-wpbm-custom-post.php';

/**
 * Handle plugin meta boxes.
 *
 * @link       https://boomdevs.com
 *
 * @package    Wpbm
 * @subpackage Wpbm/includes
 */

/**
 * Handle plugin meta boxes.
 *
 * This class defines all code necessary to define plugin meta boxes.
 *
 * @package    Wpbm
 * @subpackage Wpbm/includes
 * @author     BoomDevs <contact@boomdevs.com>
 */
class Wpbm_Meta_Box {
    /**
     * The ID of this plugin.
     *
     * @var string $plugin_name The ID of this plugin.
     */
    public static $plugin_name = WPBM_NAME;

    /**
     * Register badge meta boxes.
     *
     * @param array $meta_boxes List of existing meta box's.
     *
     * @return array List of meta boxes.
     */
    public function register_badge_meta_boxes( $meta_boxes ) {
        $prefix = Wpbm_Meta_Box::$plugin_name . '-';

        $badge_condition_fields = apply_filters('wpbm_badge_conditions', []);
        if(!$badge_condition_fields) {
            $badge_condition_fields = array(
                [
                    'type' => 'heading',
                    'class' => 'pro_only_heading',
                    'desc' => __( 'To unlock Badge condition, <a href="https://codecanyon.net/item/woocommerce-product-badge-manager/11736039" target="_blank"><b>Upgrade To Pro!</b></a>', 'wpbm' ),
                ],
                [
                    'type' => 'switch',
                    'name' => __( 'Conditions', 'wpbm' ),
                    'desc' => __( 'Enable to show badge based on multiple available conditions.', 'wpbm' ),
                    'id'   => $prefix . 'badge_condition_expiration',
                    'disabled' => true,
                ],
                [
                    'type' => 'number',
                    'id'   => $prefix . 'products_newer_than_condition',
                    'name' => __( 'Products newer than', 'wpbm' ),
                    'desc' => __( 'Visible on products newer than X hour.', 'wpbm' ),
                    'disabled' => true,
                ],
                [
                    'type' => 'number',
                    'id'   => $prefix . 'sold_more_than_condition',
                    'name' => __( 'Sold more than', 'wpbm' ),
                    'desc' => __( 'Visible on products sold more than X times.', 'wpbm' ),
                    'disabled' => true,
                ],
                [
                    'type' => 'number',
                    'id'   => $prefix . 'minimum_review_condition',
                    'name' => __( 'Minimum rating', 'wpbm' ),
                    'disabled' => true,
                ],
                [
                    'type' => 'number',
                    'id'   => $prefix . 'maximum_review_condition',
                    'name' => __( 'Maximum rating', 'wpbm' ),
                    'disabled' => true,
                ],
                [
                    'type' => 'number',
                    'id'   => $prefix . 'stock_less_condition',
                    'name' => __( 'Stock less than', 'wpbm' ),
                    'disabled' => true,
                ],
            );
        }

        $meta_boxes[] = [
            'title'      => __( 'Badge conditions', 'wpbm' ),
            'id'         => 'badge_conditions',
            'post_types' => [Wpbm_Custom_Post::$badge_post_type],
            'context'    => 'normal',
            'priority'   => 'high',
            'fields'     => [
                ...$badge_condition_fields
            ],
        ];

        $badge_meta_fields = apply_filters('wpbm_badge_meta', []);
        if(!$badge_meta_fields) {
            $badge_meta_fields = array(
                [
                    'type'       => 'select_advanced',
                    'id'         => $prefix . 'woo_pro_badges_att_term',
                    'name'       => __( 'Select product attribute terms <strong>( Pro )</strong>', 'wpbm' ),
                    'desc'       => __( 'This badge will be visible to the products with the selected attribute values.', 'wpbm' ),
                    'multiple'   => true,
                    'class'      => 'pro_only',
                    'placeholder' => __( 'Select product attribute terms', 'wpbm' ),
                     'disabled' => true,
                ],
                [
                    'type'       => 'select_advanced',
                    'id'         => $prefix . 'woo_pro_badges_att',
                    'name'       => __( 'Select product attributes <strong>( Pro )</strong>', 'wpbm' ),
                    'desc'       => __( 'This badge will be visible to the products with the selected attributes.', 'wpbm' ),
                    'multiple'   => true,
                    'class'      => 'pro_only',
                    'placeholder' => __( 'Select product attributes', 'wpbm' ),
                    'disabled' => true,
                ],
                [
                    'type' => 'text',
                    'id'   => $prefix . 'custom_badge_link',
                    'name' => __( 'Badge custom link <strong>( Pro )</strong>', 'wpbm' ),
                    'desc' => __( 'Badge single page custom permalink URL.', 'wpbm' ),
                     'disabled' => true,
                    'class'      => 'pro_only',
                ],
            );
        }

        $meta_boxes[] = [
            'title'      => __( 'Badge meta', 'wpbm' ),
            'id'         => 'badge_meta',
            'post_types' => [Wpbm_Custom_Post::$badge_post_type],
            'context'    => 'normal',
            'priority'   => 'high',
            'fields'     => [
                [
                    'type'       => 'taxonomy_advanced',
                    'id'         => $prefix . 'woo_pro_badges_cat',
                    'name'       => __( 'Select product category', 'wpbm' ),
                    'desc'       => __( 'This badge will be visible to the products with the selected categories.', 'wpbm' ),
                    'taxonomy'   => 'product_cat',
                    'field_type' => 'select_advanced',
                    'multiple'   => true,
                    'placeholder' => __( 'Select product categories', 'wpbm' ),
                ],
                [
                    'type'       => 'user',
                    'id'         => $prefix . 'woo_pro_published_author',
                    'name'       => __( 'Select sellers', 'wpbm' ),
                    'desc'       => __( 'This badge will be visible across all the products published by the selected authors', 'wpbm' ),
                    'field_type' => 'select_advanced',
                    'multiple'   => true,
                    'placeholder' => __( 'Select product sellers', 'wpbm' ),
                ],
                ...$badge_meta_fields
            ],
        ];

        return $meta_boxes;
    }

    /**
     * Register product meta boxes.
     *
     * @param array $meta_boxes List of existing meta box's.
     *
     * @return array List of meta boxes.
     */
    public function register_product_meta_boxes( $meta_boxes ) {
        $prefix = Wpbm_Meta_Box::$plugin_name . '-';

        $meta_boxes[] = [
            'title'      => __( 'Product badge manager meta', 'wpbm' ),
            'id'         => 'product_meta',
            'post_types' => ['product'],
            'context'    => 'normal',
            'priority'   => 'high',
            'fields'     => [
                [
                    'type'        => 'post',
                    'id'          => $prefix . 'wpbm_choose_badge',
                    'name'        => __( 'Select badges', 'wpbm' ),
                    'desc'        => __( 'You can directly pick badges to assign to this product.', 'wpbm' ),
                    'field_type'  => 'select_advanced',
                    'placeholder' => __( 'Select product badges', 'wpbm' ),
                    'post_type'   => [Wpbm_Custom_Post::$badge_post_type],
                    'multiple'    => true,
                ],
                [
                    'type'        => 'post',
                    'id'          => $prefix . 'wpbm_exclude_badge',
                    'name'        => __( 'Exclude badges', 'wpbm' ),
                    'desc'        => __( 'This will overwrite all condition and these selected badges will not be visible to this product.', 'wpbm' ),
                    'field_type'  => 'select_advanced',
                    'placeholder' => __( 'Select product badges', 'wpbm' ),
                    'post_type'   => [Wpbm_Custom_Post::$badge_post_type],
                    'multiple'    => true,
                ],
            ],
        ];

        return $meta_boxes;
    }
}
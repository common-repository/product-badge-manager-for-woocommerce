<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 */


defined('ABSPATH') || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if (!function_exists('wc_get_gallery_image_html')) {
    return;
}

require_once WPBM_DIR . 'includes/class-wpbm-settings.php';

global $product;
$settings = Wpbm_Settings::get_settings();

$columns = apply_filters('woocommerce_product_thumbnails_columns', 4);
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes = apply_filters(
    'woocommerce_single_product_image_gallery_classes',
    array(
        'woocommerce-product-gallery',
        'woocommerce-product-gallery--' . ($post_thumbnail_id ? 'with-images' : 'without-images'),
        'woocommerce-product-gallery--columns-' . absint($columns),
        'images',
    )
);

function render_wpbm_badge($settings) {
    $relative_parent = $settings['spp_badge_position_section']['spp_remove_relative_wrapper'];
    $addi_class = '';
    if(!$relative_parent)
        $addi_class = ' spp_badge_wrapper_selector';

    $html = '<div class="spp_badge_container spp_badge_wrapper '.$addi_class.'">';
    $html .= do_shortcode('[woo_pro_badges_extended floating="true"]');
    $html .= '</div>';
    return $html;
}
?>
<div class="<?php
echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); ?>" data-columns="<?php
echo esc_attr($columns); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
    <?php echo render_wpbm_badge($settings); ?>
    <div class="woocommerce-product-gallery__wrapper">
        <?php
        // Render wpbm badge
        if ($post_thumbnail_id) {
            $html = wc_get_gallery_image_html($post_thumbnail_id, true);
        } else {
            $html = '<div class="woocommerce-product-gallery__image--placeholder">';
            $html .= sprintf(
                '<img src="%s" alt="%s" class="wp-post-image" />',
                esc_url(wc_placeholder_img_src('woocommerce_single')),
                esc_html__('Awaiting product image', 'woocommerce')
            );
            $html .= '</div>';
        }

        echo apply_filters(
            'woocommerce_single_product_image_thumbnail_html',
            $html,
            $post_thumbnail_id
        );

        do_action('woocommerce_product_thumbnails');
        ?>
    </div>
</div>
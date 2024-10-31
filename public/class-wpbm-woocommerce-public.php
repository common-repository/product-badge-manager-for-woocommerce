<?php
defined( 'ABSPATH' ) || exit;

require_once WPBM_DIR . 'includes/class-wpbm-settings.php';

/**
 * The public-facing WooCommerce functionality of the plugin.
 *
 * @link       https://boomdevs.com
 * @package    Wpbm
 * @subpackage Wpbm/public
 */

/**
 * The public-facing WooCommerce functionality of the plugin.
 *
 * Defines all the WooCommerce public facing functions and actions for the plugin.
 *
 * @package    Wpbm
 * @subpackage Wpbm/public
 * @author     BoomDevs <contact@boomdevs.com>
 */
class Wpbm_WooCommerce_Public {
    /**
     * Inserts badges inside product single page image.
     *
     * @param $image_html string Product single page image html markup.
     *
     * @return mixed
     */
    public function add_woo_pro_badges_on_product_single_page_image() {
        $settings = Wpbm_Settings::get_settings();

        // Get settings
        $margintop = $settings['spp_badge_position_section']['spp_badge_top_margin'];
        $marginbottom = $settings['spp_badge_position_section']['spp_badge_bottom_margin'];
        $marginleft = $settings['spp_badge_position_section']['spp_badge_left_margin'];
        $marginright = $settings['spp_badge_position_section']['spp_badge_right_margin'];
        $centered = $settings['spp_badge_position_section']['spp_horizontally_centered'];
        $topprio = $settings['spp_badge_position_section']['spp_topbottom_priority'];
        $leftprio= $settings['spp_badge_position_section']['spp_leftright_priority'];
        $spp_vertically_showing= $settings['spp_badge_position_section']['spp_vertically_showing'];

        $margin_left_auto = 'margin-left: auto !important;';
        $margin_right_auto = 'margin-right: auto !important;';

        if($spp_vertically_showing == '1'){
            ?>
            <style>
                .spp_badge_container.spp_badge_wrapper .badge_post_main {
                    display: flex;
                    flex-direction: column;
                }
            </style>
            <?php
        }

        // Add styles based on settings
        echo '
			<style type="text/css">
                .spp_badge_wrapper {';
                    if ($topprio === 'bottom') {
                        echo esc_html( 'bottom: '.$marginbottom.';' );
                    } else {
                        echo esc_html( 'top: '.$margintop.';' );
                    }
                    if ($centered) {
                        echo 'text-align: center';
                    } else {
                        if ($leftprio === 'left') {
                            echo esc_html( 'left: '.$marginleft.';' );
                            echo 'text-align: left';
                        }elseif($leftprio === 'center'){
                            echo 'text-align: center';
                        }else {
                            echo esc_html( 'right: '.$marginright.';' );
                            echo 'text-align: right';
                        }
                    }
                echo'
			    }
                .spp_badge_wrapper .badge_post_main img {';
                    if ( !$centered ) {
                        if ( $leftprio === 'left' ) {
                            echo esc_html($margin_right_auto);
                        }elseif( $leftprio === 'center' ){
                            echo esc_html($margin_right_auto);
                            echo esc_html($margin_left_auto);
                        }else {
                            echo esc_html($margin_left_auto);
                        }
                    }
                echo'
			</style>';

        // Render shortcode
//        echo sprintf(
//            '%s%s%s',
//            sprintf( '<div class="spp_badge_container spp_badge_wrapper %s">', esc_html( $addi_class ) ),
//            do_shortcode( '[woo_pro_badges_extended floating="true"]' ),
//            '</div>'
//        );

        // return $img_html;
    }

    /**
     * Register image template
     */

    public function register_product_image_template( $template, $template_name, $template_path ) {
        $this->add_woo_pro_badges_on_product_single_page_image();
        // Check if template is overwritten from theme
        $theme_template = locate_template( 'templates/single-product/product-image.php' );
        if ( $theme_template !== '' && $template_name === 'single-product/product-image.php') {
            return $theme_template;
        }

        // Load template form plugin if not overwritten from theme
        if($template_name === 'single-product/product-image.php' && file_exists( WPBM_DIR . 'templates/single-product/product-image.php' ) ) {
            return WPBM_DIR . 'templates/single-product/product-image.php';
        }

        return $template;
    }

    /**
     * Insert badges into product single page DOM.
     */
    public function insert_badges_into_product_single_page() {
        $settings = Wpbm_Settings::get_settings();

        $product_badge_show = $settings['spp_show_badge'];
        $badge_position = $settings['spp_badge_position_section']['spp_badge_position'];

        if ( $product_badge_show == true ) {
            $float_top = $settings['spp_badge_position_section']['spp_float_badge'];

            if( $float_top == true ) {
                // Insert badges on image
                add_filter( 'woocommerce_locate_template', array($this, 'register_product_image_template' ), 10, 3);
//                add_action( 'woocommerce_product_thumbnails', array( $this, 'add_woo_pro_badges_on_product_single_page_image' ) );
            } else {
                // Insert badges on page
                function add_woo_pro_badges_on_product_single_page() {
                    $html = '<div class="spp_badge_container">';
                    $html .= do_shortcode('[woo_pro_badges_extended]');
                    $html .= '</div>';
                    return $html;
                }

                function render_woo_badge_position_before_title() {
                    echo add_woo_pro_badges_on_product_single_page();
                    the_title( '<h3 class="product_title entry-title">', '</h3>' );
                }

                function render_woo_badge_position_after_title() {
                    the_title( '<h3 class="product_title entry-title">', '</h3>' );
                    echo add_woo_pro_badges_on_product_single_page();
                }

                if($badge_position === '6') {
                    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
                    add_action('woocommerce_single_product_summary', 'render_woo_badge_position_after_title', 5);
                }  elseif($badge_position === '4') {
                    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
                    add_action('woocommerce_single_product_summary', 'render_woo_badge_position_before_title', 5);
                }
            }
        }

        // Add global styles for loop badges
        add_action( 'wp_head', array( $this, 'add_badges_styles_for_single_product' ) );
    }

    /**
     * Style for product single page badge image
     */
    public function add_badges_styles_for_single_product(){
        $settings = Wpbm_Settings::get_settings();
        $badge_width = $settings['spp_badge_appearance_section']['spp_badge_width'];
        $badge_alignment = $settings['spp_badge_position_section']['spp_float_badge'] === '0' ? $settings['spp_badge_position_section']['spp_badge_alignment'] : '';
        $centered = $settings['spp_badge_position_section']['spp_horizontally_centered'];
        $badge_inline_block= $centered ? 'inline-block' : 'block';
        echo sprintf('<style> 
            .spp_badge_container .badge_post_main img{
                width: %1$spx !important;
                display: %2$s !important;
            }
            .spp_badge_container .badge_post_main {
                text-align: %3$s;
            } 
        </style>', $badge_width, $badge_inline_block, $badge_alignment
        );

    }

    /**
     * Insert badges inside product loop image DOM.
     */
    public function add_woo_pro_badges_on_product_loop_image() {
        $settings = Wpbm_Settings::get_settings();

        $relative_parent = $settings['cpp_badge_position_section']['cpp_remove_relative_wrapper'];

        $addi_class = '';
        if(!$relative_parent)
            $addi_class = ' cpp_badge_wrapper_selector';

        // Render shortcode
        echo sprintf(
            '%s%s%s',
            sprintf( '<div class="cpp_badge_container cpp_badge_wrapper %s">', esc_html( $addi_class ) ),
            do_shortcode( '[woo_pro_badges_catalogue_extended floating="true"]' ),
            '</div>'
        );
    }

    /**
     * Insert badges inside product loop DOM.
     */
    public function insert_badges_into_product_loop() {
        $settings = Wpbm_Settings::get_settings();

        // Determine if badge should sit on top of product image
        $cpp_show_badge = $settings['cpp_show_badge'];
        if ( $cpp_show_badge ) {
            $float_top = $settings['cpp_badge_position_section']['cpp_float_badge'];

            if( $float_top == 1 ) {
                // Push badge inside product loop image DOM
                add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'add_woo_pro_badges_on_product_loop_image' ), 9 );

                // Register styles
                add_action( 'wp_head', array( $this, 'add_floating_badges_styles_for_product_loop' ) );
            } else {
                function add_woo_pro_badges_on_product_loop() {
                    // Render badge inside product loop DOM.
                    echo sprintf(
                        '%s%s%s',
                        sprintf( '<div class="cpp_badge_container">'),
                        do_shortcode( wp_kses_post('[woo_pro_badges_catalogue_extended]') ),
                        '</div>'
                    );
                }

                // Get badge position
                $badge_position = $settings['cpp_badge_position_section']['cpp_badge_position'];

                if($badge_position === '10') {
                    add_action('woocommerce_before_shop_loop_item_title', 'add_woo_pro_badges_on_product_loop', 11);
                } elseif( $badge_position === '11' ) {
                    add_action('woocommerce_after_shop_loop_item_title', 'add_woo_pro_badges_on_product_loop', 4);
                }
            }

            // Register scripts
            $tooltip_shop = $settings['cpp_badge_appearance_section']['cpp_show_tooltip'];
            $tooltip_single = $settings['spp_badge_appearance_section']['spp_show_tooltip'];
            
            if ($tooltip_shop == 1 || $tooltip_single == 1) {
                add_action('wp_footer', array($this, 'add_badges_scripts_for_product_loop_and_single'));
            }
            
            // Add global styles for loop badges
            add_action( 'wp_head', array( $this, 'add_badges_styles_for_product_loop' ) );
        }
    }

    /**
     * Style for products loop image
     */
    public function add_badges_styles_for_product_loop() {
        $settings = Wpbm_Settings::get_settings();
        $badge_width = $settings['cpp_badge_appearance_section']['cpp_badge_width'];
        $centered = $settings['cpp_badge_position_section']['cpp_horizontally_centered'];
        $badge_inline_block= $centered ? 'inline-block' : 'block';
        echo sprintf('<style>
            .cpp_badge_container .badge_post_main img{
                width: %1$spx !important;
                display: %2$s !important;
            }
        </style>', $badge_width, $badge_inline_block
        );
    }

    /**
     * Insert badges styles for product loop in DOM.
     */
    public function add_floating_badges_styles_for_product_loop() {
        $settings = Wpbm_Settings::get_settings();

        $margintop = $settings['cpp_badge_position_section']['cpp_badge_top_margin'];
        $marginbottom = $settings['cpp_badge_position_section']['cpp_badge_bottom_margin'];
        $marginleft = $settings['cpp_badge_position_section']['cpp_badge_left_margin'];
        $marginright = $settings['cpp_badge_position_section']['cpp_badge_right_margin'];
        $centered = $settings['cpp_badge_position_section']['cpp_horizontally_centered'];
        $topprio = $settings['cpp_badge_position_section']['cpp_topbottom_priority'];
        $leftprio = $settings['cpp_badge_position_section']['cpp_leftright_priority'];
        $cpp_vertically_showing = $settings['cpp_badge_position_section']['cpp_vertically_showing'];
        
        $margin_left_auto = 'margin-left: auto !important;';
        $margin_right_auto = 'margin-right: auto !important;';

        if($cpp_vertically_showing == '1'){
            ?>
            <style>
                .cpp_badge_container.cpp_badge_wrapper .badge_post_main {
                    display: flex;
                    flex-direction: column;
                }
            </style>
            <?php
        }
        
        echo '
        <style type="text/css">
            .cpp_badge_wrapper {';
                if ( $topprio === 'bottom' ) {
                    echo esc_html('bottom: '.$marginbottom.';');
                } else {
                    echo esc_html( 'top: '.$margintop.';' );
                }
                if ( $centered ) {
                    echo 'text-align: center';
                } else {
                    if ( $leftprio === 'left' ) {
                        echo esc_html('left: '.$marginleft.';');
                        echo 'text-align: left';
                    }elseif( $leftprio === 'center' ){
                        echo 'text-align: center';
                    }else {
                        echo esc_html( 'right: '.$marginright.';' );
                        echo 'text-align: right';
                    }
                }
            echo'
            }
            .cpp_badge_wrapper .badge_post_main img {';
                if ( !$centered ) {
                    if ( $leftprio === 'left' ) {
                        echo esc_html($margin_right_auto);
                    }elseif( $leftprio === 'center' ){
                        echo esc_html($margin_right_auto);
                        echo esc_html($margin_left_auto);
                    }else {
                        echo esc_html($margin_left_auto);
                    }
                }
            echo'
            }
        </style>';
    }

    /**
     * Insert badges scripts for product loop and single product in DOM.
     */
    public function add_badges_scripts_for_product_loop_and_single() {
        $settings = Wpbm_Settings::get_settings();

        $tooltip_animation = '';

        if ($settings['cpp_badge_appearance_section']['cpp_show_tooltip'] == 1) {
            $tooltip_animation = $settings['cpp_badge_appearance_section']['cpp_tooltip_animation'];
        } elseif ($settings['spp_badge_appearance_section']['spp_show_tooltip'] == 1) {
            $tooltip_animation = $settings['spp_badge_appearance_section']['spp_tooltip_animation'];
        }

        echo '
        <script type="text/javascript" charset="utf-8">
            function initWPBMTooltip() {
                jQuery(".cpp_tooltip").tooltipster({
                animation: "' . $tooltip_animation . '"
                });
            }
            jQuery(document).ready(function() {
                initWPBMTooltip();
            });
            jQuery(document).ajaxStop(function() {
                initWPBMTooltip();
            });
        </script>
        ';
    }
    
}
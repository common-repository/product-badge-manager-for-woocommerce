<?php
defined( 'ABSPATH' ) || exit;

require_once WPBM_DIR . 'includes/class-wpbm-custom-post.php';
require_once WPBM_DIR . 'includes/class-wpbm-settings.php';
require_once WPBM_DIR . 'models/class-wpbm-badge.php';
require_once WPBM_DIR . 'models/class-wpbm-product.php';

/**
 * Handle plugin short-codes.
 *
 * @link       https://boomdevs.com
 * @package    Wpbm
 * @subpackage Wpbm/includes
 */

/**
 * Registers plugin badge short-codes.
 *
 * This class defines all code necessary to define badge shortcodes.
 *
 * @package    Wpbm
 * @subpackage Wpbm/includes
 * @author     BoomDevs <contact@boomdevs.com>
 */
class Wpbm_Shortcode {
    /**
     * Generates tooltip script for a shortcode.
     *
     * @param $id int Shortcode static ID.
     * @param $tooltip_animation string Tooltip animation.
     *
     * @return string Tooltip javascript script.
     */
    protected function get_tooltip_script($id, $tooltip_animation ) {
        return '
              <script type="text/javascript" charset="utf-8">
                jQuery(document).ready(function() {
                    jQuery(".yesshowtooltip'.$id.'").tooltipster({
                      animation: "'.$tooltip_animation.'"
                    });
                });
              </script>
            ';
    }

  /**
     * Create badge list view shortcode for product single page.
     *
     * @param array $atts Shortcode attributes.
     *
     * @return string badges DOM.
     */
    public function create_product_single_page_badge_shortcode( $atts ) {
        extract( shortcode_atts( array(
            'floating' => false,
            'margintop' => '0px',
            'marginbottom' => '0px',
            'tooltip' => true,
            'size' => 50,
            'tooltip_animation' => 'grow',
            'badge_description_page' => true,
            'archive_page' => false,
        ), $atts ) );

        // create unique id for each shortcode use
        static $id = 1;

        // global variables
        global $product;

        $product_id = get_the_ID();
        $product_model = new Wpbm_Product( $product_id );

        // product meta
        $author = $product_model->get_author_id();

        $published = $product_model->get_published_time();
        $units_sold = $product_model->get_sells_count();
        $rating = $product->get_average_rating();
        $stock = $product_model->get_current_stocks_count();
        $stock_status = $product_model->get_stock_status();

        // fetch taxonomies
        $product_categories = $product_model->get_categories_options();
        $show_badges_id = $product_model->get_selected_badges_ids();
        $hidden_badges_id = $product_model->get_excluded_badges_ids();

        $product_atts = $product_model->get_attributes_options( $product );
        $product_terms = $product_model->get_attributes_terms_options( $product );

        // remove margin if div is floating with position absolute
        if ( $floating ) {
            $margintop = $marginbottom = '0px';
        }

        // return shortcode with badge div
        $return_string = '<div class="badge_post_main" data-margintop="'.$margintop.'" data-marginbottom="'.$marginbottom.'">';

        if(class_exists('Woocommerce_Product_Badge_Manager_Pro')){
            // register tooltip if enabled
            $tooltip_class = '';
            if ( $tooltip == true  ) {
                if ( $archive_page == false ) {
                    $tooltip_class = 'yesshowtooltip'.$id;

                    $return_string .= $this->get_tooltip_script( $id, $tooltip_animation );
                } else {
                    $tooltip_class = 'cpp_tooltip';
                }
            }
        }else {
            // register tooltip if enabled
            $tooltip_class = '';
            if ( $tooltip == true  ) {
                $tooltip_class = 'cpp_tooltip';
            } 
        }

        // Loop through badge's
        $badges = Wpbm_Badge::get_all();
        foreach( $badges as $badge ) {
            // get badge meta
            $badge_id = $badge->ID;
            $badge_model = new Wpbm_Badge( $badge_id );
            // Skip below and continue loop if badge id is in hidden badge id list
            if ( in_array( $badge_id, $hidden_badges_id ) ) continue;

            // Fetch taxonomies
            $show_cats = $badge_model->get_categories_options();
            $show_author = $badge_model->get_authors_options();

            $allowed_attributes = $badge_model->get_attributes_options();
            $allowed_terms = $badge_model->get_attributes_terms_options();
            $badge_desc_link = $badge_model->get_permalink();

            if(class_exists('Woocommerce_Product_Badge_Manager_Pro')){
                $variation_checker = new Wpbm_Product_Variation_Checker( $product_id, $published, $units_sold, $rating, $stock, $stock_status, $badge_id );

                if ( $variation_checker->validate_variation() ||
                    in_array( $badge_id, $show_badges_id ) ||
                    array_intersect( $product_categories, $show_cats ) ||
                    array_intersect( $product_atts, $allowed_attributes ) ||
                    array_intersect( $product_terms, $allowed_terms ) ||
                    in_array( $author, $show_author ) ) {
                    // Show badge
                
                    if ( $badge_description_page == true ) {
                        $return_string .= $badge_model->get_dom_with_link( $tooltip_class, $badge, $badge_desc_link, $size );
                    } elseif ( $badge_description_page == false) {
                        $return_string .= $badge_model->get_dom( $tooltip_class, $badge );                    
                    }
                }
            }else {
                if ( in_array( $badge_id, $show_badges_id ) ||
                    array_intersect( $product_categories, $show_cats ) ||
                    in_array( $author, $show_author ) ) {
                    // Show badge
                    $return_string .= $badge_model->get_dom( $tooltip_class, $badge );
                }
            }
        }

        $return_string .= '</div>';

        // increment unique id
        $id++;

        // return everything
        return $return_string;
    }

    /**
     * Create extended shortcode for product single page using proper settings..
     *
     * @param array $atts Shortcode attributes.
     *
     * @return string badges DOM.
     */
    public function create_product_single_page_badge_extended_shortcode( $atts ) {
        extract( shortcode_atts( array(
            'floating' => false,
        ), $atts ) );

        $settings = Wpbm_Settings::get_settings();

        $marginTop = $settings['spp_badge_position_section']['spp_badge_top_margin'];
        $marginBottom = $settings['spp_badge_position_section']['spp_badge_bottom_margin'];
        $badge_size = $settings['spp_badge_appearance_section']['spp_badge_width'];
        $tooltip = $settings['spp_badge_appearance_section']['spp_show_tooltip'];
        $tooltip_animation = $settings['spp_badge_appearance_section']['spp_tooltip_animation'];
        $badge_description = $settings['spp_badge_appearance_section']['spp_badge_link'];

        // Execute Shortcode
        return do_shortcode('[woo_pro_badges floating = "'.$floating.'" margintop="'.$marginTop.'" marginbottom="'.$marginBottom.'" size="'.$badge_size.'" tooltip="'.$tooltip.'" tooltip_animation="'.$tooltip_animation.'" badge_description_page="'.$badge_description.'"]');
    }

    /**
     * Create extended short-code for product catalogue page using proper settings.
     *
     * @param $atts array Shortcode attributes.
     *
     * @return string badges DOM.
     */
    public function create_product_catalogue_page_badge_extended_shortcode( $atts ) {
        extract( shortcode_atts( array(
            'floating' => false,
        ), $atts ) );

        $settings = Wpbm_Settings::get_settings();

        $marginTop = $settings['cpp_badge_position_section']['cpp_badge_top_margin'];
        $marginBottom = $settings['cpp_badge_position_section']['cpp_badge_bottom_margin'];
        $badge_size = $settings['cpp_badge_appearance_section']['cpp_badge_width'];
        $tooltip = $settings['cpp_badge_appearance_section']['cpp_show_tooltip'];

        // Execute Shortcode
        return do_shortcode('[woo_pro_badges floating = "'.$floating.'" margintop="'.$marginTop.'" marginbottom="'.$marginBottom.'" size="'.$badge_size.'" tooltip="'.$tooltip.'" ]');
    }

    /**
     * Create badge short-code for author single page.
     *
     * @param $atts array Shortcode attributes.
     *
     * @return string badges DOM.
     */
    public function create_author_badges_shortcode( $atts ){
        extract( shortcode_atts( array(
            'margintop' => '10px',
            'size' => 50,
            'marginbottom' => '10px',
            'tooltip' => true,
            'tooltip_animation' => 'grow',
            'badge_description_page' => true,
        ), $atts ) );

        // create unique id
        static $id = 1;

        // get product author
        $author = get_the_author_meta( 'ID' );

        // return shortcode with badge div
        $return_string = '<div class="badge_post_main" data-margintop="'.$margintop.'" data-marginbottom="'.$marginbottom.'">';

        // register tooltip if enabled
        $tooltip_class = '';
        if ( $tooltip == true ) {
            $tooltip_class = 'yesshowtooltip'.$id;

            $return_string .= $this->get_tooltip_script( $id, $tooltip_animation );
        }

        // badge query
        $badges = Wpbm_Badge::get_all();

        foreach( $badges as $badge ) {
            // get meta
            $badge_id = $badge->ID;
            $badge_model = new Wpbm_Badge( $badge_id );
            $show_author = $badge_model->get_authors_options();
            $badge_desc_link = $badge_model->get_permalink();

            // check condition and show results
            if ( in_array( $author, $show_author ) ) {
                if ( $badge_description_page == true ) {
                    $return_string .= $badge_model->get_dom_with_link( $tooltip_class, $badge, $badge_desc_link, $size );
                } elseif ( $badge_description_page == false) {
                    $return_string .= $badge_model->get_dom( $tooltip_class, $badge, $size );
                }
            }
        }

        $return_string .= '</div>';

        // increment unique id
        $id++;

        // return everything
        return $return_string;
    }

    /**
     * Create badge list short-code for product filter widget.
     *
     * @param $atts array Shortcode attributes.
     *
     * @return string badges DOM.
     */
    public function create_badges_filter_shortcode($atts ){
        extract( shortcode_atts( array(
            'title' => '',
            'margintop' => '',
            'size' => 50,
            'marginbottom' => '',
            'tooltip' => true,
            'tooltip_animation' => 'grow'
        ), $atts ) );

        // create unique id
        static $id = 1;

        // return shortcode with badge div
        $return_string = '<div class="badge_post_main" data-margintop="'.$margintop.'" data-marginbottom="'.$marginbottom.'">';

        // register tooltip if enabled
        $tooltip_class = '';
        if ( $tooltip == true ) {
            $tooltip_class = 'yesshowtooltip'.$id;

            $return_string .= $this->get_tooltip_script( $id, $tooltip_animation );
        }

        if ( $title ) {
            $return_string .= '<h3 class="product_filter_title">'.$title.'</h3>';
        }

        // badge query
        $badges = Wpbm_Badge::get_all();

        foreach( $badges as $badge ) {
            // get meta
            $badge_id = $badge->ID;
            $badge_model = new Wpbm_Badge( $badge_id );
            $badge_desc_link = $badge_model->get_permalink();

            // show results
            $return_string .= $badge_model->get_dom_with_link( $tooltip_class, $badge, $badge_desc_link, $size );
        }

        $return_string .= '</div>';

        // increment unique id
        $id++;

        // return everything
        return $return_string;
    }

    /**
     * Create badge list extended short-code for product filter widget using proper settings.
     *
     * @return string badges DOM.
     */
    public function create_badges_filter_extended_shortcode() {
        $settings = Wpbm_Settings::get_settings();

        $title = $settings['pba_filters_section']['pba_filters_title'];
        $marginTop = $settings['pba_badge_appearance_section']['pba_badge_top_margin'];
        $marginBottom = $settings['pba_badge_appearance_section']['pba_badge_bottom_margin'];
        $badge_size = $settings['pba_badge_appearance_section']['pba_badge_width'];
        $tooltip = $settings['pba_badge_appearance_section']['pba_show_tooltip'];
        $tooltip_animation = $settings['pba_badge_appearance_section']['pba_tooltip_animation'];

        // execute shortcode
        return do_shortcode( "[woo_pro_badges_filter title='{$title}' margintop='{$marginTop}' marginbottom='{$marginBottom}' size='{$badge_size}' tooltip='{$tooltip}' tooltip_animation='{$tooltip_animation}']" );
    }

}
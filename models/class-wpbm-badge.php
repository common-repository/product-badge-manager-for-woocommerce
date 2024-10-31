<?php
require_once WPBM_DIR . 'includes/class-wpbm-custom-post.php';
require_once WPBM_DIR . 'includes/class-wpbm-settings.php';

class Wpbm_Badge {
    private $badge_id;

    public function __construct( $badge_id ) {
        $this->badge_id = $badge_id;
    }

    public static function get_all() {
        $query = new WP_Query( array(
            'post_type' => Wpbm_Custom_Post::$badge_post_type,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'showposts' => -1
        ) );
        return $query->posts;
    }

    public function get_thumbnail_url( $badge ) {
        $badge_thumb_id = get_post_thumbnail_id( $badge );
        return wp_get_attachment_image_src( $badge_thumb_id, 'full', true )[0];
    }

    public function get_categories_options() {
        if ( rwmb_meta( 'wpbm-woo_pro_badges_cat', null, $this->badge_id ) ) {
            $show_cat = rwmb_meta( 'wpbm-woo_pro_badges_cat', null, $this->badge_id );

            $show_cats = array();
            foreach ( $show_cat as $key => $value ) {
                $show_cats[$value->term_id] = $value->term_id;
            }

            return $show_cats;
        }

        return array( 'no_category_assigned' => 'no_category_assigned' );
    }

    public function get_attributes_options() {
        if ( rwmb_meta( 'wpbm-woo_pro_badges_att', null, $this->badge_id ) ) {
            $selected_pro_atts = rwmb_meta( 'wpbm-woo_pro_badges_att', null, $this->badge_id );

            $allowed_attributes = array();
            foreach ($selected_pro_atts as $key => $value) {
                $allowed_attributes[$value] = $value;
            }

            return $allowed_attributes;
        }

        return array( 'no_attribute_assigned' => 'no_attribute_assigned' );
    }

    public function get_attributes_terms_options() {
        if ( rwmb_meta( 'wpbm-woo_pro_badges_att_term', null, $this->badge_id ) ) {
            $selected_pro_terms = rwmb_meta( 'wpbm-woo_pro_badges_att_term', null, $this->badge_id );

            $allowed_terms = array();
            foreach ($selected_pro_terms as $key => $value) {
                $allowed_terms[$value] = $value;
            }

            return $allowed_terms;
        }

        return array( 'no_terms_assigned' => 'no_terms_assigned' );
    }

    public function get_authors_options() {
        if ( rwmb_meta( 'wpbm-woo_pro_published_author', null, $this->badge_id ) ) {
            return rwmb_meta( 'wpbm-woo_pro_published_author', null, $this->badge_id );
        }

        return array( 'no_author_assigned' => 'no_author_assigned' );
    }

    public function get_permalink() {
        if ( !empty( rwmb_meta( 'wpbm-custom_badge_link',null, $this->badge_id ) ) ) {
            return rwmb_meta( 'wpbm-custom_badge_link', null, $this->badge_id );
        }

        return get_permalink( $this->badge_id );
    }

    public function get_dom_with_link( $tooltip_class, $badge, $link, $size = '' ) {
        $settings = Wpbm_Settings::get_settings();
        $wpbm_badge_link_remove = isset($settings['wpbm_badge_link_remove']) ? $settings['wpbm_badge_link_remove'] : 0;

        if ($wpbm_badge_link_remove == 1) {
            return '<a class="single_badge"><img width="'.$size.'" class="badge_thumb '.$tooltip_class.'" title="'.get_the_title( $this->badge_id ).'" src="'.$this->get_thumbnail_url( $badge ).'" alt="'.get_the_title( $this->badge_id ).'" /></a>';
        }else {
            return '<a class="single_badge" href="'.$link.'"><img width="'.$size.'" class="badge_thumb '.$tooltip_class.'" title="'.get_the_title( $this->badge_id ).'" src="'.$this->get_thumbnail_url( $badge ).'" alt="'.get_the_title( $this->badge_id ).'" /></a>';
        }
    }

    public function get_dom( $tooltip_class, $badge, $size = '' ) {
        return '<img width="'.$size.'" class="single_badge badge_thumb '.$tooltip_class.'" title="'.get_the_title( $this->badge_id ).'" src="'.$this->get_thumbnail_url( $badge ).'" alt="'.get_the_title( $this->badge_id ).'" />';
    }
}
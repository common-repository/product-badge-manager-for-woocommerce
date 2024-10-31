<?php
class Wpbm_Product {
    private $product_id;

    public function __construct( $product_id ) {
        $this->product_id = $product_id;
    }

    public function get_categories_options() {
        return wp_get_post_terms( $this->product_id, 'product_cat', array( "fields" => "ids" ) );
    }

    public function get_attributes_options( $product ) {
        $attributes = $product->get_attributes();

        $product_atts = array();
        if ( $attributes ) {
            foreach ( $attributes as $att ) {
                // Add attribute
                $product_atts[] = $att->get_id();
            }
        }

        return $product_atts;
    }

    public function get_attributes_terms_options( $product ) {
        $attributes = $product->get_attributes();

        $product_terms = array();
        if ( $attributes ) {
            foreach ( $attributes as $att ) {
                // Prepare terms
                $terms = $att->get_terms();
                if ( $terms && is_array( $terms ) ) {
                    foreach( $terms as $term ) {
                        $product_terms[] = $term->term_id;
                    }
                }
            }
        }

        return $product_terms;
    }

    public function get_selected_badges_ids() {
        if ( rwmb_meta( 'wpbm-wpbm_choose_badge', null, $this->product_id ) ) {
            return rwmb_meta( 'wpbm-wpbm_choose_badge', null, $this->product_id );
        }

        return array('no_badges_to_show' => 'no_badges_to_show');
    }

    public function get_excluded_badges_ids() {
        if ( rwmb_meta( 'wpbm-wpbm_exclude_badge', null, $this->product_id ) ) {
            return rwmb_meta( 'wpbm-wpbm_exclude_badge', null, $this->product_id );
        }

        return array('no_badges_to_show' => 'no_badges_to_show');
    }

    public function get_published_time() {
        return get_the_time( 'U', $this->product_id );
    }

    public function get_sells_count() {
        return get_post_meta( $this->product_id, 'total_sales', true );
    }

    public function get_current_stocks_count() {
        return get_post_meta( $this->product_id, '_stock', true );
    }

    public function get_stock_status() {
        return get_post_meta( $this->product_id, '_stock_status', true );
    }

    public function get_author_id() {
        return get_the_author_meta( 'ID' );
    }
}
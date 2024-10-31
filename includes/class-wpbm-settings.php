<?php
defined( 'ABSPATH' ) || exit;

/**
 * Performs all kind of admin panel settings functions.
 *
 * Handles and registers plugin settings panel for admin dashboard.
 *
 * @link       https://boomdevs.com
 * @package    Wpbm
 * @subpackage Wpbm/includes
 */

/**
 * Performs all kind of admin panel settings functions.
 *
 * Handles and registers plugin settings panel for admin dashboard.
 *
 * @package    Wpbm
 * @subpackage Wpbm/includes
 * @author     BoomDevs <contact@boomdevs.com>
 */
class Wpbm_Settings {
    /**
     * The real name of this plugin.
     *
     * @access   protected
     * @var      string    $plugin_full_name    The full punctual name of this plugin.
     */
    protected $plugin_full_name;

    /**
     * The ID of this plugin.
     *
     * @var      string    $plugin_name    The ID of this plugin.
     */
    public static $plugin_name = WPBM_NAME;

    /**
     * Wpbm_Settings constructor.
     *
     * @param string $plugin_full_name The punctual name of the plugin.
     */
    public function __construct( $plugin_full_name ) {
        $this->plugin_full_name = $plugin_full_name;
    }

    /**
     * Get badge position options list.
     *
     * @return array List of badge positions.
     *
     * @access   protected
     */
    protected function get_badge_positions_list_free() {
        return array(
            '4' => __( 'Before product title', 'wpbm' ),
            '6' => __( 'After product title', 'wpbm' ),
            'pro_only' => __( 'Before product price ( Pro )', 'wpbm' ),
            'pro_only_1' => __( 'After product price ( Pro )', 'wpbm' ),
            'pro_only_2' => __( 'Before product excerpt ( Pro )', 'wpbm' ),
            'pro_only_3' => __( 'After product excerpt ( Pro )', 'wpbm' ),
            'pro_only_4' => __( 'Before product cart button ( Pro )', 'wpbm' ),
            'pro_only_5' => __( 'After product cart button ( Pro )', 'wpbm' ),
            'pro_only_6' => __( 'Before product sharing button ( Pro )', 'wpbm' ),
            'pro_only_7' => __( 'After product sharing button ( Pro )', 'wpbm' ),
            'pro_only_8' => __( 'Before product meta ( Pro )', 'wpbm' ),
            'pro_only_9' => __( 'After product meta ( Pro )', 'wpbm' ),
            'pro_only_10' => __( 'Before product tabs ( Pro )', 'wpbm' ),
            'pro_only_11' => __( 'Before product gallery thumbnail ( Pro )', 'wpbm' ),
            'pro_only_12' => __( 'After  product gallery thumbnail ( Pro )', 'wpbm' ),
        );
    }

    /**
     * Register codestar admin settings panel.
     */
    public function register_admin_settings_panel() {
        if ( class_exists( 'CSF' ) ) {
            // Set settings prefix
            $prefix = Wpbm_Settings::$plugin_name;
            $menu_icon = plugin_dir_url( __DIR__ ) .'admin/images/badge-menu-icon.png';
            // Create options
            CSF::createOptions( $prefix, array(
                // framework title
                'framework_title'   => sprintf( '%s <small>%s</small>', $this->plugin_full_name, __( 'by Boomdevs', 'wpbm' ) ),
                'framework_class'   => '',

                // menu settings
                'menu_title'    => sprintf( '%s %s', strtoupper(Wpbm_Settings::$plugin_name), __( 'Settings', 'wpbm' ) ),
                'menu_slug' => Wpbm_Settings::$plugin_name. '-settings',
                'menu_icon' => $menu_icon,
                'menu_capability'   => 'manage_options',

                // menu extras
                'show_bar_menu' => false,
                'show_sub_menu' => false,
                'show_in_network'   => false,
                'show_in_customizer'    => false,

                'show_search'   => true,
                'show_reset_all'    => true,
                'show_reset_section'    => true,
                'show_footer'   => true,
                'show_all_options'  => true,
                'show_form_warning' => true,
                'sticky_header' => true,
                'save_defaults' => true,
                'ajax_save' => true,

                // admin bar menu settings
                'admin_bar_menu_icon'   => '',
                'admin_bar_menu_priority'   => 80,

                // footer
                'footer_text'   => '',
                'footer_after'  => '',
                'footer_credit' => __( 'Made with love 💓 by BoomDevs', 'wpbm' ),

                // database model
                'database'  => '', // options, transient, theme_mod, network
                'transient_time'    => 0,

                // contextual help
                'contextual_help'   => array(),
                'contextual_help_sidebar'   => '',

                // typography options
                'enqueue_webfont'   => true,
                'async_webfont' => false,

                // others
                'output_css'    => true,

                // theme and wrapper classname
                'theme' => 'dark',
                'class' => '',

                // external default values
                'defaults'  => array(),
            ) );
            $pro_general_settings = apply_filters('wpbm_general_setting', []);

            if(!$pro_general_settings) {
                $pro_general_settings = array(
                    array(
                        'id' => 'custom_badge_permalink',
                        'type' => 'text',
                        'title' => __( 'Permalink base <strong>( Pro )</strong>', 'wpbm' ),
                        'desc' => __( 'Change badge archive page permalink base. Do not use space & do not touch this field if you have no idea.', 'wpbm' ),
                        'default' => 'woo-product-badges',
                        'class'      => 'pro_only',
                    ),
                    array(
                        'id'      => 'wpbm_badge_link_remove',
                        'type'    => 'switcher',
                        'title' => __( 'Removed Badge Link <strong>( Pro )</strong>', 'wpbm' ),
                        'text_on' => __( 'Yes', 'wpbm' ),
                        'text_off' => __( 'No', 'wpbm' ),
                        'default' => false,
                        'class'      => 'pro_only',
                    ),
                );
            }

            // General settings
            CSF::createSection( $prefix, array(
                'title' => __( 'General Settings', 'wpbm' ),
                'fields' => array(
                    ...$pro_general_settings
                ),
            ));

            // Single product badge position list
            $pro_single_badge_position = apply_filters('wpbm_single_product_settings', []);

            if(!$pro_single_badge_position) {
                $pro_single_badge_position = array(
                    array(
                        'id' => 'spp_badge_position',
                        'type' => 'select',
                        'class' => 'spp_bade_position',
                        'title' => __( 'Badge position', 'wpbm' ),
                        'options' => $this->get_badge_positions_list_free(),
                        'dependency' => array( 'spp_float_badge', '==', 'false' ),
                    ),
                    array(
                        'id' => 'spp_badge_alignment',
                        'type' => 'select',
                        'title' => __( 'Badge alignment', 'wpbm' ),
                        'options' => array(
                            'left' => __( 'Left', 'wpbm' ),
                            'center' => __( 'Center', 'wpbm' ),
                            'right' => __( 'Right', 'wpbm' ),
                        ),
                        'dependency' => array( 'spp_float_badge', '==', 'false' ),
                    ),
                );
            }

            // Product single settings
            CSF::createSection( $prefix, array(
                'title' => __( 'Product Single Page', 'wpbm' ),
                'fields' => array(
                    array(
                        'id' => 'spp_show_badge',
                        'type' => 'switcher',
                        'title' => __( 'Enable/Disable', 'wpbm' ),
                        'desc' => __( 'Disable this field if you are using custom shortcode to show badges or want to remove badges from product single page.', 'wpbm' ),
                        'default' => 1,
                        'text_on' => __( 'Enabled', 'wpbm' ),
                        'text_off' => __( 'Disabled', 'wpbm' ),
                    ),
                    array(
                        'id' => 'spp_badge_position_section',
                        'type' => 'fieldset',
                        'title' => __( 'Badge position', 'wpbm' ),
                        'dependency' => array( 'spp_show_badge', '==', 'true' ),
                        'fields' => array(
                            array(
                                'id' => 'spp_float_badge',
                                'type' => 'switcher',
                                'title' => __( 'Float top of product image', 'wpbm' ),
                            ),
                            array(
                                'id' => 'spp_horizontally_centered',
                                'type' => 'switcher',
                                'title' => __( 'Horizontally centered', 'wpbm' ),
                                'dependency' => array( 'spp_float_badge', '==', 'true' ),
                            ),
                            array(
                                'id' => 'spp_vertically_showing',
                                'type' => 'switcher',
                                'title' => __( 'Vertically Show', 'wpbm' ),
                                'dependency' => array( 'spp_float_badge', '==', 'true' ),
                            ),
                            array(
                                'id' => 'spp_topbottom_priority',
                                'type' => 'radio',
                                'title' => __( 'Vertical margin priority', 'wpbm' ),
                                'options' => array(
                                    'top' => __( 'Top', 'wpbm' ),
                                    'bottom' => __( 'Bottom', 'wpbm' ),
                                ),
                                'dependency' => array( 'spp_float_badge', '==', 'true' ),
                            ),
                            array(
                                'id' => 'spp_badge_top_margin',
                                'type' => 'text',
                                'title' => __( 'Margin top', 'wpbm' ),
                                'subtitle' => __( 'Use % or px', 'wpbm' ),
                            ),
                            array(
                                'id' => 'spp_badge_bottom_margin',
                                'type' => 'text',
                                'title' => __( 'Margin bottom', 'wpbm' ),
                                'subtitle' => __( 'Use % or px', 'wpbm' ),
                            ),
                            array(
                                'id' => 'spp_leftright_priority',
                                'type' => 'radio',
                                'title' => __( 'Horizontal margin priority', 'wpbm' ),
                                'options' => array(
                                    'left' => __( 'Left', 'wpbm' ),
                                    'center' => __( 'Center', 'wpbm' ),
                                    'right' => __( 'Right', 'wpbm' ),
                                ),
                                'dependency' => array( 'spp_float_badge', '==', 'true' ),
                            ),
                            array(
                                'id' => 'spp_badge_left_margin',
                                'type' => 'text',
                                'title' => __( 'Margin left', 'wpbm' ),
                                'subtitle' => __( 'Use % or px', 'wpbm' ),
                                'dependency' => array( 'spp_float_badge', '==', 'true' ),
                            ),
                            array(
                                'id' => 'spp_badge_right_margin',
                                'type' => 'text',
                                'title' => __( 'Margin right', 'wpbm' ),
                                'subtitle' => __( 'Use % or px', 'wpbm' ),
                                'dependency' => array( 'spp_float_badge', '==', 'true' ),
                            ),
                            array(
                                'id' => 'spp_remove_relative_wrapper',
                                'type' => 'switcher',
                                'title' => __( 'Remove wrapper style', 'wpbm' ),
                                'desc' => __( 'By default WPBM adds position relative style to parent div of the badge container. Try enabling this option if you have any issue seeing badges.', 'wpbm' ),
                                'dependency' => array( 'spp_float_badge', '==', 'true' ),
                            ),
                            ...$pro_single_badge_position,
                        ),
                        'default' => array(
                            'spp_float_badge' => 0,
                            'spp_horizontally_centered' => 1,
                            'spp_topbottom_priority' => 'bottom',
                            'spp_badge_top_margin' => '10px',
                            'spp_badge_bottom_margin' => '10px',
                            'spp_leftright_priority' => 'right',
                            'spp_badge_left_margin' => '0px',
                            'spp_badge_right_margin' => '0px',
                            'spp_remove_relative_wrapper' => 0,
                            'spp_badge_position' => '6',
                        ),
                    ),
                    array(
                        'id' => 'spp_badge_appearance_section',
                        'type' => 'fieldset',
                        'title' => __( 'Badge appearance', 'wpbm' ),
                        'fields' => array(
                            array(
                                'id' => 'spp_badge_width',
                                'type' => 'slider',
                                'title' => __( 'Badge width', 'wpbm' ),
                                'max' => '320',
                            ),
                            array(
                                'id' => 'spp_show_tooltip',
                                'type' => 'switcher',
                                'title' => __( 'Show tooltip', 'wpbm' ),
                            ),
                            array(
                                'id' => 'spp_tooltip_animation',
                                'type' => 'select',
                                'title' => __( 'Tooltip animation', 'wpbm' ),
                                'options' => array(
                                    'grow' => __( 'Grow up', 'wpbm' ),
                                    'fade' => __( 'Fade in', 'wpbm' ),
                                    'swing' => __( 'Swing up', 'wpbm' ),
                                    'slide' => __( 'Slide', 'wpbm' ),
                                ),
                                'dependency' => array( 'spp_show_tooltip', '==', 'true' ),
                            ),
                            array(
                                'id' => 'spp_badge_link',
                                'type' => 'switcher',
                                'title' => __( 'Enable badge description page / badge linking', 'wpbm' ),
                            ),
                        ),
                        'default' => array(
                            'spp_badge_width' => 50,
                            'spp_show_tooltip' => 1,
                            'spp_tooltip_animation' => 'grow',
                            'spp_badge_link' => 1,
                        ),
                    ),
                ),
            ) );

            // Product listing page settings
            CSF::createSection( $prefix, array(
                'title' => __( 'Product Listing Page', 'wpbm' ),
                'fields' => array(
                    array(
                        'id' => 'cpp_show_badge',
                        'type' => 'switcher',
                        'title' => __( 'Enable/Disable', 'wpbm' ),
                        'desc' => __( 'Disable this field if you are using custom shortcode to show badges or want to remove badges from product listing pages.', 'wpbm' ),
                        'default' => 1,
                        'text_on' => __( 'Enabled', 'wpbm' ),
                        'text_off' => __( 'Disabled', 'wpbm' ),
                    ),
                    array(
                        'id' => 'cpp_badge_position_section',
                        'type' => 'fieldset',
                        'title' => __( 'Badge position', 'wpbm' ),
                        'dependency' => array( 'cpp_show_badge', '==', 'true' ),
                        'fields' => array(
                            array(
                                'id' => 'cpp_float_badge',
                                'type' => 'switcher',
                                'title' => __( 'Float top of product image', 'wpbm' ),
                            ),
                            array(
                                'id' => 'cpp_horizontally_centered',
                                'type' => 'switcher',
                                'title' => __( 'Horizontally centered', 'wpbm' ),
                                'dependency' => array( 'cpp_float_badge', '==', 'true' ),
                            ),
                            array(
                                'id' => 'cpp_vertically_showing',
                                'type' => 'switcher',
                                'title' => __( 'Vertically Show', 'wpbm' ),
                                'dependency' => array( 'cpp_float_badge', '==', 'true' ),
                            ),
                            array(
                                'id' => 'cpp_topbottom_priority',
                                'type' => 'radio',
                                'title' => __( 'Vertical margin priority', 'wpbm' ),
                                'options' => array(
                                    'top' => __( 'Top', 'wpbm' ),
                                    'bottom' => __( 'Bottom', 'wpbm' ),
                                ),
                                'dependency' => array( 'cpp_float_badge', '==', 'true' ),
                            ),
                            array(
                                'id' => 'cpp_badge_top_margin',
                                'type' => 'text',
                                'title' => __( 'Margin top', 'wpbm' ),
                                'subtitle' => __( 'Use % or px', 'wpbm' ),
                            ),
                            array(
                                'id' => 'cpp_badge_bottom_margin',
                                'type' => 'text',
                                'title' => __( 'Margin bottom', 'wpbm' ),
                                'subtitle' => __( 'Use % or px', 'wpbm' ),
                            ),
                            array(
                                'id' => 'cpp_leftright_priority',
                                'type' => 'radio',
                                'title' => __( 'Horizontal margin priority', 'wpbm' ),
                                'options' => array(
                                    'left' => __( 'Left', 'wpbm' ),
                                    'center' => __( 'Center', 'wpbm' ),
                                    'right' => __( 'Right', 'wpbm' ),
                                ),
                                'dependency' => array( 'cpp_float_badge', '==', 'true' ),
                            ),
                            array(
                                'id' => 'cpp_badge_left_margin',
                                'type' => 'text',
                                'title' => __( 'Margin left', 'wpbm' ),
                                'subtitle' => __( 'Use % or px', 'wpbm' ),
                                'dependency' => array( 'cpp_float_badge', '==', 'true' ),
                            ),
                            array(
                                'id' => 'cpp_badge_right_margin',
                                'type' => 'text',
                                'title' => __( 'Margin right', 'wpbm' ),
                                'subtitle' => __( 'Use % or px', 'wpbm' ),
                                'dependency' => array( 'cpp_float_badge', '==', 'true' ),
                            ),
                            array(
                                'id' => 'cpp_remove_relative_wrapper',
                                'type' => 'switcher',
                                'title' => __( 'Remove wrapper style', 'wpbm' ),
                                'desc' => __( 'By default WPBM adds position relative style to parent div of the badge container. Try enabling this option if you have any issue seeing badges.', 'wpbm' ),
                                'dependency' => array( 'cpp_float_badge', '==', 'true' ),
                            ),
                            array(
                                'id' => 'cpp_badge_position',
                                'type' => 'select',
                                'title' => __( 'Badge position', 'wpbm' ),
                                'options' => array(
                                    '10' => __( 'Before product title', 'wpbm' ),
                                    '11' => __( 'After product title', 'wpbm' ),
                                ),
                                'dependency' => array( 'cpp_float_badge', '==', 'false' ),
                            ),
                        ),
                        'default' => array(
                            'cpp_float_badge' => 0,
                            'cpp_horizontally_centered' => 1,
                            'cpp_topbottom_priority' => 'bottom',
                            'cpp_badge_top_margin' => '10px',
                            'cpp_badge_bottom_margin' => '10px',
                            'cpp_leftright_priority' => 'right',
                            'cpp_badge_left_margin' => '0px',
                            'cpp_badge_right_margin' => '0px',
                            'cpp_remove_relative_wrapper' => 0,
                            'cpp_badge_position' => '10',
                        ),
                    ),
                    array(
                        'id' => 'cpp_badge_appearance_section',
                        'type' => 'fieldset',
                        'title' => __( 'Badge appearance', 'wpbm' ),
                        'fields' => array(
                            array(
                                'id' => 'cpp_badge_width',
                                'type' => 'slider',
                                'title' => __( 'Badge width', 'wpbm' ),
                                'max' => '320',
                            ),
                            array(
                                'id' => 'cpp_show_tooltip',
                                'type' => 'switcher',
                                'title' => __( 'Show tooltip', 'wpbm' ),
                            ),
                            array(
                                'id' => 'cpp_tooltip_animation',
                                'type' => 'select',
                                'title' => __( 'Tooltip animation', 'wpbm' ),
                                'options' => array(
                                    'grow' => __( 'Grow up', 'wpbm' ),
                                    'fade' => __( 'Fade in', 'wpbm' ),
                                    'swing' => __( 'Swing up', 'wpbm' ),
                                    'slide' => __( 'Slide', 'wpbm' ),
                                ),
                                'dependency' => array( 'cpp_show_tooltip', '==', 'true' ),
                            ),
                        ),
                        'default' => array(
                            'cpp_badge_width' => '50',
                            'cpp_show_tooltip' => 1,
                            'cpp_tooltip_animation' => 'grow',
                        ),
                    ),
                ),
            ) );

            // Product archives page settings

            $pro_archive_settings = apply_filters('wpbm_archive_product_settings', []);

            if(!$pro_archive_settings) {
                $pro_archive_settings = array(
                    array(
                        'id' => 'pba_show',
                        'type' => 'switcher',
                        'title' => __( 'Enable product archives <strong>( Pro )</strong>', 'wpbm' ),
                        'desc' => __('Enable to show relevant product list in the badge single page.', 'wpbm'),
                        'default' => 1,
                        'class'      => 'pro_only',
                    ),
                    array(
                        'id' => 'pba_archive_section',
                        'type' => 'fieldset',
                        'title' => __( 'Archive settings <strong>( Pro )</strong>', 'wpbm' ),
                        'dependency' => array( 'pba_show', '==', 'true' ),
                        'class'      => 'pro_only',
                        'fields' => array(
                            array(
                                'id' => 'pba_post_per_page',
                                'type' => 'text',
                                'title' => __( 'Products per page', 'wpbm' ),
                                'class'      => 'pro_only',
                            ),
                        ),
                        'default' => array(
                            'pba_post_per_page' => 12,
                        ),
                    ),
                    array(
                        'id' => 'pba_filters_section',
                        'type' => 'fieldset',
                        'title' => __( 'Archive page filters <strong>( Pro )</strong>', 'wpbm' ),
                        'dependency' => array( 'pba_show', '==', 'true' ),
                        'class'      => 'pro_only',
                        'fields' => array(
                            array(
                                'id' => 'pba_show_filters',
                                'type' => 'switcher',
                                'title' => __( 'Show products filter', 'wpbm' ),
                                'desc' => __('You can disable this option and use badge filter widget in your archive page widget area if available.', 'wpbm'),
                                'class'      => 'pro_only',
                                'default' => 1,
                            ),
                            array(
                                'id' => 'pba_filters_title',
                                'type' => 'text',
                                'title' => __( 'Products filter title', 'wpbm' ),
                                'dependency' => array( 'pba_show_filters', '==', 'true' ),
                                'class'      => 'pro_only',
                            ),
                        ),
                        'default' => array(
                            'pba_show_filters' => 0,
                            'pba_filters_title' => 'Filter Product Using Badge',
                        ),
                    ),
                    array(
                        'id' => 'pba_badge_appearance_section',
                        'type' => 'fieldset',
                        'title' => __( 'Filter badges settings <strong>( Pro )</strong>', 'wpbm' ),
                        'class'      => 'pro_only',
                        'fields' => array(
                            array(
                                'id' => 'pba_badge_top_margin',
                                'type' => 'text',
                                'title' => __( 'Margin top', 'wpbm' ),
                                'subtitle' => __( 'Use px or %', 'wpbm' ),
                                'class'      => 'pro_only',
                            ),
                            array(
                                'id' => 'pba_badge_bottom_margin',
                                'type' => 'text',
                                'title' => __( 'Margin bottom', 'wpbm' ),
                                'subtitle' => __( 'Use px or %', 'wpbm' ),
                                'class'      => 'pro_only',
                            ),
                            array(
                                'id' => 'pba_badge_width',
                                'type' => 'slider',
                                'title' => __( 'Badge width', 'wpbm' ),
                                'max' => '320',
                                'default' => 50,
                                'class'      => 'pro_only',
                            ),
                            array(
                                'id' => 'pba_show_tooltip',
                                'type' => 'switcher',
                                'title' => __( 'Show tooltip', 'wpbm' ),
                                'class'      => 'pro_only',
                                'default' => 1,
                            ),
                            array(
                                'id' => 'pba_tooltip_animation',
                                'type' => 'select',
                                'title' => __( 'Tooltip animation', 'wpbm' ),
                                'class'      => 'pro_only',
                                'options' => array(
                                    'grow' => __( 'Grow up', 'wpbm' ),
                                    'fade' => __( 'Fade in', 'wpbm' ),
                                    'swing' => __( 'Swing up', 'wpbm' ),
                                    'slide' => __( 'Slide', 'wpbm' ),
                                ),
                                'dependency' => array( 'pba_show_tooltip', '==', 'true' ),
                            ),
                        ),
                        'default' => array(
                            'pba_badge_top_margin' => '0px',
                            'pba_badge_bottom_margin' => '0px',
                            'pba_badge_width' => '50',
                            'pba_show_tooltip' => 1,
                            'pba_tooltip_animation' => 'grow',
                        ),
                    ),
                );
            }

            CSF::createSection( $prefix, array(
                'title' => __( 'Product Archives', 'wpbm' ),
                'fields' => array(
                    ...$pro_archive_settings
                )
            ));
        }
    }

    /**
     * Return plugin settings.
     *
     * @param string $key Key of the required settings.
     * @param string $default_value Default value of the required settings.
     *
     * @return string|array Settings value.
     */
    public static function get( $key = '', $default_value = null ) {
        $options = get_option( Wpbm_Settings::$plugin_name );

        return ( isset( $options[$key] ) ) ? $options[$key] : $default_value;
    }

    /**
     * Return plugin all settings.
     *
     * @return string|array Settings values.
     */
    public static function get_settings() {
        return get_option( Wpbm_Settings::$plugin_name );
    }
}

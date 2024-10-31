<?php

/**
 * Pixelerate_CDN
 *
 * @since 1.0.0
 */
class Pixelerate_CDN
{
    /**
     * pseudo-constructor
     *
     * @since  1.0.0
     */
    public static function instance() {
        new self();
    }

    /**
     * constructor
     *
     * @since  1.0.0
     */
    public function __construct() {
        /* CDN rewriter hook */
        add_action(
            'template_redirect',
            [
                __CLASS__,
                'handle_rewrite_hook',
            ]
        );

        /* Hooks */
        add_action(
            'admin_init',
            [
                __CLASS__,
                'register_textdomain',
            ]
        );
        add_action(
            'admin_init',
            [
                'Pixelerate_CDN_Settings',
                'register_settings',
            ]
        );
        add_action(
            'admin_menu',
            [
                'Pixelerate_CDN_Settings',
                'add_settings_page',
            ]
        );
        add_filter(
            'plugin_action_links_' .PIXELERATE_CDN_BASE,
            [
                __CLASS__,
                'add_action_link',
            ]
        );

        /* admin notices */
        add_action(
            'all_admin_notices',
            [
                __CLASS__,
                'pixelerate_cdn_requirements_check',
            ]
        );
    }

    /**
     * add action links
     *
     * @since  1.0.0
     *
     * @param   array  $data  already existing links
     * @return  array  $data  extended array with links
     */
    public static function add_action_link($data) {
        // check permission
        if ( ! current_user_can('manage_options') ) {
            return $data;
        }

        return array_merge(
            $data,
            [
                sprintf(
                    '<a href="%s">%s</a>',
                    add_query_arg(
                        [
                            'page' => 'pixelerate_cdn',
                        ],
                        admin_url('options-general.php')
                    ),
                    __("Settings")
                ),
            ]
        );
    }

    /**
     * run uninstall hook
     *
     * @since  1.0.0
     */
    public static function handle_uninstall_hook() {
        delete_option('pixelerate_cdn');
    }

    /**
     * run activation hook
     *
     * @since  1.0.0
     */
    public static function handle_activation_hook() {
        add_option(
            'pixelerate_cdn',
            [
                'url'            => get_option('home'),
                'pixelerate_endpoint_key' => '',
            ]
        );
    }

    /**
     * check plugin requirements
     *
     * @since  1.0.0
     */
    public static function pixelerate_cdn_requirements_check() {
        // WordPress version check
        if ( version_compare($GLOBALS['wp_version'], PIXELERATE_CDN_MIN_WP.'alpha', '<') ) {
            show_message(
                sprintf(
                    '<div class="error"><p>%s</p></div>',
                    sprintf(
                        __("Pixelerate CDN is optimized for WordPress %s. Please disable the plugin or upgrade your WordPress installation (recommended).", "pixelerate-cdn"),
                        PIXELERATE_CDN_MIN_WP
                    )
                )
            );
        }
    }

    /**
     * register textdomain
     *
     * @since   1.0.0
     */
    public static function register_textdomain() {
        load_plugin_textdomain(
            'pixelerate-cdn',
            false,
            'pixelerate-cdn/lang'
        );
    }

    /**
     * return plugin options
     *
     * @since  1.0.0
     *
     * @return  array  $diff  data pairs
     */
    public static function get_options() {
        return wp_parse_args(
            get_option('pixelerate_cdn'),
            [
                'url'             => get_option('home'),
                'pixelerate_endpoint_key'  => '',
            ]
        );
    }

    /**
     * run rewrite hook
     *
     * @since  1.0.0
     */
    public static function handle_rewrite_hook() {
        $options = self::get_options();

        // only run the rewriter if the cdn url has changed
        if (get_option('home') == $options['url']) {
            return;
        }

        // endpoint key is required
        if(strlen($options['pixelerate_endpoint_key']) < 1){
            return;
        }

        $rewriter = new Pixelerate_CDN_Rewriter(
            get_option('home'),
            $options['url'],
            $options['pixelerate_endpoint_key']
        );
        ob_start(array(&$rewriter, 'rewrite'));
    }
}
<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class LELA_Admin {


    protected $plugin_slug = 'livemesh_el_assistant';

    public function __construct() {

        $this->includes();
        $this->init_hooks();

    }

    public function includes() {

        // load class admin ajax function
        require_once LELA_PLUGIN_DIR . 'admin/admin-ajax.php';


    }

    public function init_hooks() {

        // Build admin menu/pages
        add_action('admin_menu', array($this, 'add_plugin_admin_menu'));

        // Load admin style sheet and JavaScript.
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        add_action('current_screen', array($this, 'remove_admin_notices'));

    }

    public function remove_admin_notices($screen) {

        // If this screen is AI Assistant for Elementor plugin options page, remove annoying admin notices
        if (strpos($screen->id, $this->plugin_slug) !== false && strpos($screen->id, $this->plugin_slug . '_license') === false) {
            add_action('admin_notices', array(&$this, 'remove_notices_start'));
            add_action('admin_notices', array(&$this, 'remove_notices_end'), 999);
        }
    }

    public function remove_notices_start() {

        // Turn on output buffering
        ob_start();

    }

    public function remove_notices_end() {

        // Get current buffer contents and delete current output buffer
        $content = ob_get_contents();
        ob_clean();

    }

    public function add_plugin_admin_menu() {

        // add plugin settings submenu page
        add_submenu_page(
            'options-general.php',
            esc_html__('AI Assistant for Elementor Settings', 'livemesh-el-assistant'),
            esc_html__('Elementor AI Assistant', 'livemesh-el-assistant'),
            'manage_options',
            $this->plugin_slug,
            array($this, 'display_settings_page')
        );

    }

    public function display_settings_page() {

        require_once('views/admin-header.php');
        require_once('views/admin-banner2.php');
        require_once('views/settings.php');
        require_once('views/admin-footer.php');

    }

    public function enqueue_admin_scripts() {

        // Use minified libraries if LELA_SCRIPT_DEBUG is turned off
        $suffix = (defined('LELA_SCRIPT_DEBUG') && LELA_SCRIPT_DEBUG) ? '' : '.min';

        // get current admin screen
        $screen = get_current_screen();

        // If screen is a part of AI Assistant for Elementor plugin options page
        if (strpos($screen->id, $this->plugin_slug) !== false) {

            wp_enqueue_script('jquery-ui-datepicker');

            wp_enqueue_script('wp-color-picker');
            wp_enqueue_style('wp-color-picker');

            wp_register_style('lela-admin-styles', LELA_PLUGIN_URL . 'admin/assets/css/lela-admin.css', array(), LELA_VERSION);
            wp_enqueue_style('lela-admin-styles');

            wp_register_script('lela-admin-scripts', LELA_PLUGIN_URL . 'admin/assets/js/lela-admin' . $suffix . '.js', array(), LELA_VERSION, true);
            wp_enqueue_script('lela-admin-scripts');

            wp_register_style('lela-admin-page-styles', LELA_PLUGIN_URL . 'admin/assets/css/lela-admin-page.css', array(), LELA_VERSION);
            wp_enqueue_style('lela-admin-page-styles');
        }

    }

}

new LELA_Admin;
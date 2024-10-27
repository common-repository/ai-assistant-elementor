<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class LELA_Admin_Ajax {

    // Instance of this class.
    protected $plugin_slug = 'livemesh_el_assistant';
    protected $ajax_data;
    protected $ajax_msg;


    public function __construct() {

        // retrieve all ajax string to localize
        $this->localize_strings();
        $this->init_hooks();

    }

    public function init_hooks() {

        // Register backend ajax action
        add_action('wp_ajax_lela_admin_ajax', array($this, 'lela_admin_ajax'));
        // Load admin ajax js script
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

    }

    public function ajax_response($success = true, $message = null, $content = null) {

        $response = array(
            'success' => $success,
            'message' => $message,
            'content' => $content
        );

        return $response;

    }

    public function lela_check_nonce() {

        // retrieve nonce
        $nonce = (isset($_POST['nonce'])) ? $_POST['nonce'] : $_GET['nonce'];

        // nonce action for the grid
        $action = 'lela_admin_nonce';

        // check ajax nounce
        if (!wp_verify_nonce($nonce, $action)) {
            // build response
            $response = $this->ajax_response(false, esc_html__('Sorry, an error occurred. Please refresh the page.', 'livemesh-el-assistant'));
            // die and send json error response
            wp_send_json($response);
        }

    }

    public function lela_admin_ajax() {

        // check the nonce
        $this->lela_check_nonce();

        // retrieve data
        $this->ajax_data = (isset($_POST)) ? $_POST : $_GET;

        // retrieve function
        $func = $this->ajax_data['func'];

        switch ($func) {
            case 'lela_save_settings':
                $response = $this->save_settings_callback();
                break;
            case 'lela_reset_settings':
                $response = $this->save_settings_callback();
                break;
            default:
                $response = ajax_response(false, esc_html__('Sorry, an unknown error occurred...', 'livemesh-el-assistant'), null);
                break;
        }

        // send json response and die
        wp_send_json($response);

    }

    public function save_settings_callback() {

        // retrieve data from jquery
        $setting_data = $this->ajax_data['setting_data'];

        lela_update_options($setting_data);

        $template = false;
        // get new restore global settings panel
        if ($this->ajax_data['reset']) {
            ob_start();
            require_once('views/settings.php');
            $template = ob_get_clean();
        }

        $response = $this->ajax_response(true, $this->ajax_data['reset'], $template);
        return $response;

    }


    public function localize_strings() {
        
        $this->ajax_msg = array(
            'box_icons' => array(
                'before' => '<i class="lela-info-box-icon dashicons dashicons-admin-generic"></i>',
                'success' => '<i class="lela-info-box-icon dashicons dashicons-yes"></i>',
                'error' => '<i class="lela-info-box-icon dashicons dashicons-no-alt"></i>'
            ),
            'box_messages' => array(

                'lela_save_settings' => array(
                    'before' => esc_html__('Saving plugin settings', 'livemesh-el-assistant'),
                    'success' => esc_html__('Plugin settings Saved', 'livemesh-el-assistant'),
                    'error' => esc_html__('Sorry, an error occurs while saving settings...', 'livemesh-el-assistant')
                ),
                'lela_reset_settings' => array(
                    'before' => esc_html__('Resetting plugin settings', 'livemesh-el-assistant'),
                    'success' => esc_html__('Plugin settings resetted', 'livemesh-el-assistant'),
                    'error' => esc_html__('Sorry, an error occurred while resetting settings', 'livemesh-el-assistant')
                ),
            )
        );

    }

    public function admin_nonce() {

        return array(
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('lela_admin_nonce')
        );

    }

    public function enqueue_admin_scripts() {

        $screen = get_current_screen();

        // enqueue only in grid panel
        if (strpos($screen->id, $this->plugin_slug) !== false) {
            // merge nonce to translatable strings
            $strings = array_merge($this->admin_nonce(), $this->ajax_msg);

            // Use minified libraries if LELA_SCRIPT_DEBUG is turned off
            $suffix = (defined('LELA_SCRIPT_DEBUG') && LELA_SCRIPT_DEBUG) ? '' : '.min';

            // register and localize script for ajax methods
            wp_register_script('lela-admin-ajax-scripts', LELA_PLUGIN_URL . 'admin/assets/js/lela-admin-ajax' . $suffix . '.js', array(), LELA_VERSION, true);
            wp_enqueue_script('lela-admin-ajax-scripts');

            wp_localize_script('lela-admin-ajax-scripts', 'lela_admin_global_var', $strings);

        }
    }

}

new LELA_Admin_Ajax;
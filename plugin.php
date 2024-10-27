<?php

namespace LivemeshAddons;

if (!defined('ABSPATH'))
    exit;


if (!class_exists('Livemesh_Elementor_Assistant')) :

    /**
     * Main Livemesh_Elementor_Assistant Class
     *
     */
    final class Livemesh_Elementor_Assistant {

        /** Singleton *************************************************************/

        private static $instance;

        /**
         * LELA Content Generator Object
         *
         * @var object|LELA_Content_Generator
         */
        public $content_generator;

        /**
         * LELA Headline Generator Object
         *
         * @var object|LELA_Headline_Generator
         */
        public $headline_generator;

        /**
         * Main Livemesh_Elementor_Assistant Instance
         *
         * Insures that only one instance of Livemesh_Elementor_Assistant exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         */
        public static function instance() {

            if (!isset(self::$instance) && !(self::$instance instanceof Livemesh_Elementor_Assistant)) {

                self::$instance = new Livemesh_Elementor_Assistant;

                self::$instance->setup_debug_constants();

                self::$instance->includes();

                self::$instance->content_generator = new LELA_Content_Generator();

                self::$instance->headline_generator = new LELA_Headline_Generator();


                self::$instance->hooks();

            }
            return self::$instance;
        }


        /**
         * Throw error on object clone
         *
         * The whole idea of the singleton design pattern is that there is a single
         * object therefore, we don't want the object to be cloned.
         */
        public function __clone() {
            // Cloning instances of the class is forbidden
            _doing_it_wrong(__FUNCTION__, esc_html__('Cheatin&#8217; huh?', 'livemesh-el-assistant'), '1.8');
        }

        /**
         * Disable unserializing of the class
         *
         */
        public function __wakeup() {
            // Unserializing instances of the class is forbidden
            _doing_it_wrong(__FUNCTION__, esc_html__('Cheatin&#8217; huh?', 'livemesh-el-assistant'), '1.8');
        }

        private function setup_debug_constants() {

            $enable_debug = false;

            $settings = get_option('lela_settings');

            if ($settings && isset($settings['lela_enable_debug']) && $settings['lela_enable_debug'] == 'true')
                $enable_debug = true;

            // Enable script debugging
            if (!defined('LELA_SCRIPT_DEBUG')) {
                define('LELA_SCRIPT_DEBUG', $enable_debug);
            }

            // Minified JS file name suffix
            if (!defined('LELA_JS_SUFFIX')) {
                if ($enable_debug)
                    define('LELA_JS_SUFFIX', '');
                else
                    define('LELA_JS_SUFFIX', '.min');
            }
        }

        /**
         * Include required files
         *
         */
        private function includes() {

            if (!function_exists('is_plugin_active')) {
                include_once(ABSPATH . 'wp-admin/includes/plugin.php');
            }

            require_once LELA_PLUGIN_DIR . 'includes/utils/class-openai-client.php';

            require_once LELA_PLUGIN_DIR . 'includes/core/class-content-generator.php';
            require_once LELA_PLUGIN_DIR . 'includes/core/class-headline-generator.php';

            require_once LELA_PLUGIN_DIR . 'includes/helper-functions.php';

            if (is_admin()) {
                require_once LELA_PLUGIN_DIR . 'admin/admin-init.php';
            }

        }

        /**
         * Load Plugin Text Domain
         *
         * Looks for the plugin translation files in certain directories and loads
         * them to allow the plugin to be localised
         */
        public function load_plugin_textdomain() {

            $lang_dir = apply_filters('livemesh_el_assistant_lang_dir', trailingslashit(LELA_PLUGIN_DIR . 'languages'));

            // Traditional WordPress plugin locale filter
            $locale = apply_filters('plugin_locale', get_locale(), 'livemesh-el-assistant');
            $mofile = sprintf('%1$s-%2$s.mo', 'livemesh-el-assistant', $locale);

            // Setup paths to current locale file
            $mofile_local = $lang_dir . $mofile;

            if (file_exists($mofile_local)) {
                // Look in the /wp-content/plugins/ai-assistant-elementor/languages/ folder
                load_textdomain('livemesh-el-assistant', $mofile_local);
            }
            else {
                // Load the default language files
                load_plugin_textdomain('livemesh-el-assistant', false, $lang_dir);
            }

            return false;
        }

        /**
         * Setup the default hooks and actions
         */
        private function hooks() {

            add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));

            add_action('plugins_loaded', array($this, 'enhancement_hooks'));

        }

        /**
         * @return void
         */
        function enhancement_hooks() {

            add_action('elementor/editor/before_enqueue_scripts', array($this, 'enqueue_editor_scripts'));

            add_action('elementor/widgets/register', array($this, 'register_widgets'));

            add_action('elementor/init', array($this, 'add_elementor_category'));
        }

        /* Adds the category only if not already added */
        public function add_elementor_category() {

            \Elementor\Plugin::instance()->elements_manager->add_category(
                'livemesh-addons',
                array(
                    'title' => esc_html__('Livemesh Addons', 'livemesh-el-assistant'),
                    'icon' => 'fa fa-plug',
                ));
        }

        /**
         * Load Frontend Scripts
         *
         */
        public function enqueue_editor_scripts() {

            wp_enqueue_script('jquery-ui-dialog');
            wp_enqueue_style('wp-jquery-ui-dialog');

            // Use minified libraries if LELA_SCRIPT_DEBUG is turned off
            $suffix = (defined('LELA_SCRIPT_DEBUG') && LELA_SCRIPT_DEBUG) ? '' : '.min';

            wp_enqueue_script('lela-editor-scripts', LELA_PLUGIN_URL . 'assets/js/lela-editor' . $suffix . '.js', array('jquery', 'wp-i18n', 'jquery-ui-dialog'), LELA_VERSION, true);

            $ajax_params = array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'assistant_nonce' => wp_create_nonce('lela-assistant')
            );
            wp_localize_script('lela-editor-scripts', 'lela_ajax_object', $ajax_params);

            wp_set_script_translations(
                'lela-editor-scripts',
                'livemesh-el-assistant',
                LELA_PLUGIN_DIR . 'languages'
            );

        }

        /**
         * Include required files
         *
         */
        public function register_widgets($widgets_manager) {

            require_once LELA_ADDONS_DIR . 'advanced-writer.php';

            $widgets_manager->register(new \LivemeshAddons\Widgets\LELA_Advanced_Writer_Widget());

        }

    }

    /**
     * The main function responsible for returning the one true Livemesh_Elementor_Assistant
     * Instance to functions everywhere.
     *
     * Use this function like you would a global variable, except without needing
     * to declare the global.
     *
     * Example: <?php $lela = LELA(); ?>
     */
    function LELA() {
        return Livemesh_Elementor_Assistant::instance();
    }

    // Get LELA Running
    LELA();

endif; // End if class_exists check



<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;


/**
 * Fetch the value of the option specified by the user in the Settings page under Livemesh Addons->Settings
 * @param $option_name The name of the option for which the value is desired
 * @param $default The default value if the option is not set
 * @return string The value of the option specified by the user
 */
function lela_get_option($option_name, $default = null) {

    $settings = get_option('lela_settings');

    if (!empty($settings) && isset($settings[$option_name]))
        $option_value = $settings[$option_name];
    else
        $option_value = $default;

    return apply_filters('lela_get_option', $option_value, $option_name, $default);
}

/**
 * Update the option in the DB for the option name specified. This is managed in
 * the Settings page under Livemesh Addons->Settings
 * @param $option_name The name of the option which needs to be updated
 * @param $option_value The new value for the option which needs updation
 * @return void
 */
function lela_update_option($option_name, $option_value) {

    $settings = get_option('lela_settings');

    if (empty($settings))
        $settings = array();

    $settings[$option_name] = $option_value;

    update_option('lela_settings', $settings);
}

/**
 * Update multiple options in one go for the Settings page under Livemesh Addons->Settings
 * @param array $setting_data A collection of settings key value pairs;
 */
function lela_update_options($setting_data) {

    $settings = get_option('lela_settings');

    if (empty($settings))
        $settings = array();

    foreach ($setting_data as $setting => $value) {
        // because of get_magic_quotes_gpc()
        $value = stripslashes($value);
        $settings[$setting] = $value;
    }

    update_option('lela_settings', $settings);
}

function lela_get_openai_models() {
    return [
        'gpt-4' => esc_html__('gpt-4', 'livemesh-el-assistant'),
        'gpt-4o' => esc_html__('gpt-4o', 'livemesh-el-assistant'),
        'gpt-4-turbo' => esc_html__('gpt-4-turbo', 'livemesh-el-assistant'),
        'gpt-3.5-turbo' => esc_html__('gpt-3.5-turbo', 'livemesh-el-assistant'),
        'gpt-3.5-turbo-instruct' => esc_html__('gpt-3.5-turbo-instruct', 'livemesh-el-assistant'),
    ];
}


function lela_get_supported_languages() {
    return [
        'English' => esc_html__('English', 'livemesh-el-assistant'),
        'Spanish' => esc_html__('Spanish', 'livemesh-el-assistant'),
        'German' => esc_html__('German', 'livemesh-el-assistant'),
        'French' => esc_html__('French', 'livemesh-el-assistant'),
        'Italian' => esc_html__('Italian', 'livemesh-el-assistant'),
        'Portuguese' => esc_html__('Portuguese', 'livemesh-el-assistant'),
        'Romanian' => esc_html__('Romanian', 'livemesh-el-assistant'),
        'Dutch' => esc_html__('Dutch', 'livemesh-el-assistant'),
        'Polish' => esc_html__('Polish', 'livemesh-el-assistant'),
        'Finnish' => esc_html__('Finnish', 'livemesh-el-assistant'),
        'Danish' => esc_html__('Danish', 'livemesh-el-assistant'),
        'Norwegian' => esc_html__('Norwegian', 'livemesh-el-assistant'),
        'Chinese' => esc_html__('Chinese', 'livemesh-el-assistant'),
        'Hindi' => esc_html__('Hindi', 'livemesh-el-assistant'),
        'Japanese' => esc_html__('Japanese', 'livemesh-el-assistant'),
        'Korean' => esc_html__('Korean', 'livemesh-el-assistant'),
        'Indonesian' => esc_html__('Indonesian', 'livemesh-el-assistant'),
        'Russian' => esc_html__('Russian', 'livemesh-el-assistant'),
        'Arabic' => esc_html__('Arabic', 'livemesh-el-assistant'),
        'Persian' => esc_html__('Persian', 'livemesh-el-assistant'),
        'Turkish' => esc_html__('Turkish', 'livemesh-el-assistant'),
        'Ukrainian' => esc_html__('Ukrainian', 'livemesh-el-assistant'),
        'Swedish' => esc_html__('Swedish', 'livemesh-el-assistant'),
        'Hungarian' => esc_html__('Hungarian', 'livemesh-el-assistant'),
        'Greek' => esc_html__('Greek', 'livemesh-el-assistant'),
        'Hebrew' => esc_html__('Hebrew', 'livemesh-el-assistant'),
        'Bulgarian' => esc_html__('Bulgarian', 'livemesh-el-assistant'),
        'Tamil' => esc_html__('Tamil', 'livemesh-el-assistant'),
        'Kannada' => esc_html__('Kannada', 'livemesh-el-assistant'),
        'Urdu' => esc_html__('Urdu', 'livemesh-el-assistant'),
        'Vietnamese' => esc_html__('Vietnamese', 'livemesh-el-assistant'),
        'Czech' => esc_html__('Czech', 'livemesh-el-assistant'),
        'Croatian' => esc_html__('Croatian', 'livemesh-el-assistant'),
    ];
}


/**
 * Get system or the server information like the site URL, plugins installed, WP version, PHP Info etc.
 * useful for debugging purposes. This is displayed in the Settings page under Livemesh Addons -> Settings.
 * @return string The information about the current WP setup
 */
function lela_get_sysinfo() {
    global $wpdb;

    // Get theme info
    $theme_data = wp_get_theme();
    $theme = $theme_data->Name . ' ' . $theme_data->Version;

    $return = '### <strong>Begin System Info</strong> ###' . "\n\n";

    // Start with the basics...
    $return .= '-- Site Info' . "\n\n";
    $return .= 'Site URL:                 ' . site_url() . "\n";
    $return .= 'Home URL:                 ' . home_url() . "\n";
    $return .= 'Multisite:                ' . (is_multisite() ? 'Yes' : 'No') . "\n";

    // Theme info
    $plugin = get_plugin_data(LELA_PLUGIN_FILE);


    // Plugin configuration
    $return .= "\n" . '-- Plugin Configuration' . "\n\n";
    $return .= 'Name:                     ' . $plugin['Name'] . "\n";
    $return .= 'Version:                  ' . $plugin['Version'] . "\n";

    // WordPress configuration
    $return .= "\n" . '-- WordPress Configuration' . "\n\n";
    $return .= 'Version:                  ' . get_bloginfo('version') . "\n";
    $return .= 'Language:                 ' . (defined('WPLANG') && WPLANG ? WPLANG : 'en_US') . "\n";
    $return .= 'Permalink Structure:      ' . (get_option('permalink_structure') ? get_option('permalink_structure') : 'Default') . "\n";
    $return .= 'Active Theme:             ' . $theme . "\n";
    $return .= 'Show On Front:            ' . get_option('show_on_front') . "\n";

    // Only show page specs if frontpage is set to 'page'
    if (get_option('show_on_front') == 'page') {
        $front_page_id = get_option('page_on_front');
        $blog_page_id = get_option('page_for_posts');

        $return .= 'Page On Front:            ' . ($front_page_id != 0 ? get_the_title($front_page_id) . ' (#' . $front_page_id . ')' : 'Unset') . "\n";
        $return .= 'Page For Posts:           ' . ($blog_page_id != 0 ? get_the_title($blog_page_id) . ' (#' . $blog_page_id . ')' : 'Unset') . "\n";
    }

    $return .= 'ABSPATH:                  ' . ABSPATH . "\n";


    $return .= 'WP_DEBUG:                 ' . (defined('WP_DEBUG') ? WP_DEBUG ? 'Enabled' : 'Disabled' : 'Not set') . "\n";
    $return .= 'Memory Limit:             ' . WP_MEMORY_LIMIT . "\n";
    $return .= 'Registered Post Stati:    ' . implode(', ', get_post_stati()) . "\n";

    // Get plugins that have an update
    $updates = get_plugin_updates();

    // WordPress active plugins
    $return .= "\n" . '-- WordPress Active Plugins' . "\n\n";

    $plugins = get_plugins();
    $active_plugins = get_option('active_plugins', array());

    foreach ($plugins as $plugin_path => $plugin) {
        if (!in_array($plugin_path, $active_plugins))
            continue;

        $update = (array_key_exists($plugin_path, $updates)) ? ' (needs update - ' . $updates[$plugin_path]->update->new_version . ')' : '';
        $return .= $plugin['Name'] . ': ' . $plugin['Version'] . $update . "\n";
    }

    // WordPress inactive plugins
    $return .= "\n" . '-- WordPress Inactive Plugins' . "\n\n";

    foreach ($plugins as $plugin_path => $plugin) {
        if (in_array($plugin_path, $active_plugins))
            continue;

        $update = (array_key_exists($plugin_path, $updates)) ? ' (needs update - ' . $updates[$plugin_path]->update->new_version . ')' : '';
        $return .= $plugin['Name'] . ': ' . $plugin['Version'] . $update . "\n";
    }

    if (is_multisite()) {
        // WordPress Multisite active plugins
        $return .= "\n" . '-- Network Active Plugins' . "\n\n";

        $plugins = wp_get_active_network_plugins();
        $active_plugins = get_site_option('active_sitewide_plugins', array());

        foreach ($plugins as $plugin_path) {
            $plugin_base = plugin_basename($plugin_path);

            if (!array_key_exists($plugin_base, $active_plugins))
                continue;

            $update = (array_key_exists($plugin_path, $updates)) ? ' (needs update - ' . $updates[$plugin_path]->update->new_version . ')' : '';
            $plugin = get_plugin_data($plugin_path);
            $return .= $plugin['Name'] . ': ' . $plugin['Version'] . $update . "\n";
        }
    }

    // Server configuration (really just versioning)
    $return .= "\n" . '-- Webserver Configuration' . "\n\n";
    $return .= 'PHP Version:              ' . PHP_VERSION . "\n";
    $return .= 'MySQL Version:            ' . $wpdb->db_version() . "\n";

    // PHP configs... now we're getting to the important stuff
    $return .= "\n" . '-- PHP Configuration' . "\n\n";
    $return .= 'Memory Limit:             ' . ini_get('memory_limit') . "\n";
    $return .= 'Upload Max Size:          ' . ini_get('upload_max_filesize') . "\n";
    $return .= 'Post Max Size:            ' . ini_get('post_max_size') . "\n";
    $return .= 'Upload Max Filesize:      ' . ini_get('upload_max_filesize') . "\n";
    $return .= 'Max Execution Time:               ' . ini_get('max_execution_time') . "\n";
    $return .= 'Max Input Vars:           ' . ini_get('max_input_vars') . "\n";
    $return .= 'Display Errors:           ' . (ini_get('display_errors') ? 'On (' . ini_get('display_errors') . ')' : 'N/A') . "\n";

    $return = apply_filters('edd_sysinfo_after_php_config', $return);

    // PHP extensions and such
    $return .= "\n" . '-- PHP Extensions' . "\n\n";
    $return .= 'cURL:                     ' . (function_exists('curl_init') ? 'Supported' : 'Not Supported') . "\n";
    $return .= 'fsockopen:                ' . (function_exists('fsockopen') ? 'Supported' : 'Not Supported') . "\n";
    $return .= 'SOAP Client:              ' . (class_exists('SoapClient') ? 'Installed' : 'Not Installed') . "\n";
    $return .= 'Suhosin:                  ' . (extension_loaded('suhosin') ? 'Installed' : 'Not Installed') . "\n";

    $return .= "\n" . '### End System Info ###';

    return $return;
}
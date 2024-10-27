<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<div id="lela-banner-wrap">

    <div id="lela-banner" class="lela-banner-sticky">
        <h2><span><?php echo esc_html__('Livemesh AI Assistant for Elementor', 'livemesh-el-assistant'); ?></span><?php echo esc_html__('Plugin Settings', 'livemesh-el-assistant') ?></h2>
        <div id="lela-buttons-wrap">
            <a class="lela-button" data-action="lela_save_settings" id="lela_settings_save"><i
                    class="dashicons dashicons-yes"></i><?php echo esc_html__('Save Settings', 'livemesh-el-assistant') ?></a>
            <a class="lela-button reset" data-action="lela_reset_settings" id="lela_settings_reset"><i
                    class="dashicons dashicons-update"></i><?php echo esc_html__('Reset', 'livemesh-el-assistant') ?></a>
        </div>
    </div>

</div>
<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$debug_mode = lela_get_option('lela_enable_debug', false);

/* Completions request to OpenAI public API */
$openai_api_secret_key = lela_get_option('lela_openai_api_secret_key', '');

?>

<div class="lela-settings">

    <div class="postbox">

        <!-------------------
        OPTIONS HOLDER START
        -------------------->
        <div class="lela-menu-options settings-options">

            <div class="lela-inner">

                <!-------------------  LI TABS -------------------->

                <ul class="lela-tabs-wrap">
                    <li class="lela-tab selected" data-target="social-api"><i
                                class="lela-icon dashicons dashicons-share"></i><?php echo esc_html__('External API', 'livemesh-el-assistant') ?>
                    </li>
                    <li class="lela-tab" data-target="debugging"><i
                                class="lela-icon dashicons dashicons-warning"></i><?php echo esc_html__('Debugging', 'livemesh-el-assistant') ?>
                    </li>
                </ul>

                    <!-------------------  SOCIAL API TAB -------------------->

                    <div class="lela-tab-content social-api lela-tab-show">

                        <!---- Twitter API -->
                        <div class="lela-box-side">
                            <h3><?php echo esc_html__('OpenAI API', 'livemesh-el-assistant') ?></h3>
                        </div>
                        <div class="lela-inner lela-box-inner">
                            <div class="lela-row lela-field">
                                <label class="lela-label"><?php echo esc_html__('OpenAI API Settings', 'livemesh-el-assistant') ?></label>
                                <p class="lela-desc"><?php echo esc_html__('Make sure you track your API usage at - ', 'livemesh-el-assistant'); ?>
                                    <a target="_blank"
                                       href="https://beta.openai.com/account/usage"><?php echo esc_html__('OpenAI Account page', 'livemesh-el-assistant'); ?></a>
                                </p>
                            </div>

                            <div class="lela-clearfix"></div>

                            <!---- OpenAI API Secret Key -->
                            <div class="lela-row lela-field lela-type-color">
                                <label class="lela-label"><?php echo esc_html__('OpenAI API Secret Key', 'livemesh-el-assistant') ?></label>
                                <p class="lela-desc"><?php echo esc_html__('Generate your secret key by visiting -.', 'livemesh-el-assistant') ?>
                                    <a href="https://beta.openai.com/account/api-keys" target="_blank"><?php echo esc_html__('OpenAI API keys page','livemesh-el-assistant'); ?></a></p>
                                <div class="lela-spacer" style="height: 5px"></div>
                                <input class="lela-text" style="width: 450px;" name="lela_openai_api_secret_key"
                                       type="password"
                                       value="<?php echo esc_attr($openai_api_secret_key); ?>"/>
                            </div>
                        
                        </div>
                    
                    </div>

                <!------------------- Debugging TAB -------------------->

                <div class="lela-tab-content debugging">

                    <!---- Enable script debugging -->
                    <div class="lela-box-side">
                        <h3><?php echo esc_html__('Debug Mode', 'livemesh-el-assistant') ?></h3>
                    </div>
                    <div class="lela-inner lela-box-inner">
                        <div class="lela-spacer" style="height: 15px"></div>
                        <label
                                class="lela-label lela-label-outside"><?php echo esc_html__('Enable Script Debug Mode', 'livemesh-el-assistant') ?></label>
                        <div class="lela-row lela-type-checkbox lela-field">
                            <p class="lela-desc"><?php echo esc_html__('Use unminified Javascript files instead of minified ones to help developers debug an issue', 'livemesh-el-assistant') ?></p>
                            <div class="lela-toggle">
                                <input type="checkbox" class="lela-checkbox" name="lela_enable_debug"
                                       id="lela_enable_debug"
                                       data-default=""
                                       value="<?php echo esc_attr($debug_mode) ?>" <?php echo checked(!empty($debug_mode), 1, false) ?>>
                                <label for="lela_enable_debug"></label>
                            </div>
                        </div>
                    </div>

                    <div class="lela-clearfix"></div>

                    <!---- System Info -->
                    <div class="lela-box-side">
                        <h3><?php echo esc_html__('System Info', 'livemesh-el-assistant') ?></h3>
                    </div>
                    <div class="lela-inner lela-box-inner">

                        <div class="lela-row lela-field">
                            <label
                                    class="lela-label"><?php echo esc_html__('System Information', 'livemesh-el-assistant') ?></label>
                            <p class="lela-desc"><?php echo esc_html__('Server setup information useful for debugging purposes.', 'livemesh-el-assistant'); ?></p>

                            <div class="lela-spacer" style="height: 15px"></div>

                            <p class="debug-info"><?php echo nl2br(lela_get_sysinfo()); ?></p>
                        </div>

                    </div>

                    <div class="lela-clearfix"></div>

                </div>

                <!-------------------  OPTIONS HOLDER END  -------------------->
            </div>

        </div>

        <!------------------- BUILD PANEL SETTINGS -------------------->

    </div>

</div>

<?php

/*
Widget Name: Advanced Writer
Description: Generates content using advanced AI tools
Author: LiveMesh
Author URI: https://www.livemeshwp.com
*/

namespace LivemeshAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Class for Advanced Writer widget that generates text content for the web page
 */
class LELA_Advanced_Writer_Widget extends Widget_Base {

    /**
     * Get the name for the widget
     * @return string
     */
    public function get_name() {
        return 'lela-advanced-writer';
    }

    /**
     * Get the widget title
     * @return string|void
     */
    public function get_title() {
        return esc_html__('AI Advanced Writer', 'livemesh-el-assistant');
    }

    /**
     * Get the widget icon
     * @return string
     */
    public function get_icon() {
        return 'eicon-text-area';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * @return string[]
     */
    public function get_categories() {
        return array('livemesh-addons');
    }

    /**
     * Get the widget documentation URL
     * @return string
     */
    public function get_custom_help_url() {
        return 'https://livemeshelementor.com/docs/livemesh-addons/core-addons/ai-advanced-writer/';
    }

    /**
     * Obtain the scripts required for the widget to function
     * @return string[]
     */
    public function get_script_depends() {
        return [];
    }

    /**
     * Register the controls for the widget
     * Adds fields that help configure and customize the widget
     * @return void
     */
    protected function register_controls() {

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Advanced AI Content', 'livemesh-el-assistant'),
            ]
        );

        $this->add_control(
            'content',
            [
                'type' => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'separator' => 'before',
                'default' => esc_html__('AI generated content reflected here', 'livemesh-el-assistant'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'prompt',
            [
                'type' => Controls_Manager::TEXTAREA,
                'label' => esc_html__('Prompt', 'livemesh-el-assistant'),
                'label_block' => true,
                'placeholder' => '',
                'rows' => 10,
                'description' => wp_kses_post(__('Provide subject and detailed instructions for the content you want to generate. You can also paste here existing content and request AI to fix style/grammar or rewrite that content for you. <br><strong><div style="font-size: 14px; font-style: normal; margin-top: 10px; color: red;" >This widget is deprecated in favor of native Text Editor widget of Elementor which is now AI enabled with better features. <br></br>Hence, the Generate feature of AI Advanced Writer widget is now disabled. <u>This widget will be removed in future releases. To preserve existing content, pls ensure all data is moved to Text Editor widget.</u></div></strong>', 'livemesh-el-assistant')),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'generate',
            [
                'type' => Controls_Manager::BUTTON,
                'label' => '',
                'separator' => 'before',
                'show_label' => false,
                'text' => esc_html__('Generate', 'livemesh-el-assistant'),
                'button_type' => 'default',

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_content_params',
            [
                'label' => esc_html__('Content Parameters', 'livemesh-el-assistant'),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $this->add_control(
            'keywords',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Keywords', 'livemesh-el-assistant'),
                'label_block' => true,
                'description' => esc_html__('Provide keywords separated by commas for SEO', 'livemesh-el-assistant'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'language',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Choose Language', 'livemesh-el-assistant'),
                'description' => esc_html__('The text will be generated in the selected language.', 'livemesh-el-assistant'),
                'options' => lela_get_supported_languages(),
                'default' => 'English',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_openai_api_settings',
            [
                'label' => esc_html__('OpenAI API Settings', 'livemesh-el-assistant'),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $this->add_control(
            'gpt3_model',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Choose a GPT-3 Model to use', 'livemesh-el-assistant'),
                'description' => wp_kses_post(__('Know more about these models and their capabilities <a target="_blank" href="https://platform.openai.com/docs/models/overview">here</a>', 'livemesh-el-assistant')),
                'options' => array(
                    'text-davinci-003' => esc_html__('text-davinci-003', 'livemesh-el-assistant'),
                    'text-curie-001' => esc_html__('text-curie-001', 'livemesh-el-assistant'),
                    'text-babbage-001' => esc_html__('text-babbage-001', 'livemesh-el-assistant'),
                    'text-ada-001' => esc_html__('text-ada-001', 'livemesh-el-assistant'),
                ),
                'default' => 'text-davinci-003',
            ]
        );

        $this->add_control(
            'max_tokens',
            [
                'label' => esc_html__('Maximum Tokens', 'livemesh-el-assistant'),
                'description' => esc_html__('The maximum number of tokens to use for generation. Higher values means more content will be generated.', 'livemesh-el-assistant'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1000,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 2048,
                    ],
                ],
            ]
        );

        $this->add_control(
            'temperature',
            [
                'label' => esc_html__('Temperature', 'livemesh-el-assistant'),
                'description' => esc_html__('The sampling temperature to use. Higher values means the model will take more risks. Try 0.9 for more creative applications, and 0 for ones with a well-defined answer.', 'livemesh-el-assistant'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.6,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'step' => 0.1,
                        'max' => 1,
                    ],
                ],
            ]
        );

        $this->add_control(
            'presence_penalty',
            [
                'label' => esc_html__('Presence Penalty', 'livemesh-el-assistant'),
                'description' => esc_html__('Number between -2.0 and 2.0. Default is 0. Positive values penalize new tokens based on whether they appear in the text so far, increasing the model\'s likelihood to talk about new topics.', 'livemesh-el-assistant'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'min' => -2.0,
                        'step' => 0.1,
                        'max' => 2.0,
                    ],
                ],
            ]
        );

        $this->add_control(
            'frequency_penalty',
            [
                'label' => esc_html__('Frequency Penalty', 'livemesh-el-assistant'),
                'description' => esc_html__('Number between -2.0 and 2.0. Default is 0. Positive values penalize new tokens based on their existing frequency in the text so far, decreasing the model\'s likelihood to repeat the same line verbatim.', 'livemesh-el-assistant'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'min' => -2.0,
                        'step' => 0.1,
                        'max' => 2.0,
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_styling',
            [
                'label' => esc_html__('Text', 'livemesh-el-assistant'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Text Color', 'livemesh-el-assistant'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lela-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'label' => esc_html__('Typography', 'livemesh-el-assistant'),
                'selector' => '{{WRAPPER}} .lela-content',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render HTML widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @return void
     */
    protected function render() {

        $settings = $this->get_settings_for_display();

        $settings = apply_filters('lela_advanced_content_' . $this->get_id() . '_settings', $settings);
        ?>
        <div class="lela-content">

            <?php echo wp_kses_post($settings['content']); ?>

        </div><!-- .lela-content -->

        <?php
    }

    /**
     * Render the widget output in the editor.
     * @return void
     */
    protected function content_template() {
    }

}
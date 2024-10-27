<?php

namespace LivemeshAddons;

// Exit if accessed directly
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;

if (!defined('ABSPATH'))
    exit;


if (!class_exists('LELA_Headline_Generator')) :

    /**
     * Main LELA_Headline_Generator Class
     *
     */
    class LELA_Headline_Generator {

        /**
         * LELA OpenAI Client Object
         *
         * @var object|LELA_OpenAI_Client
         */
        public $openai_client;

        /**
         * Get things going
         *
         * @since 1.4.4
         */
        public function __construct() {

            add_action('elementor/element/heading/section_title/before_section_end', array($this, 'register_headline_generation_controls'), 20, 2);

            add_action('elementor/element/heading/section_title/after_section_end', array($this, 'register_openai_settings_controls'), 20, 2);

            $this->openai_client = new LELA_OpenAI_Client();

            add_action('wp_ajax_nopriv_lela_generate_headline', array($this, 'generate_headline'));

            add_action('wp_ajax_lela_generate_headline', array($this, 'generate_headline'));

        }

        /**
         * Register Headline Generation Controls. Make sure you prefix everything to avoid conflict with Elementor widget
         *
         * @param Controls_Stack $element Elementor element.
         * @param string $section_id Section ID.
         */
        public function register_headline_generation_controls(Controls_Stack $element, $section_id) {

            $element->add_control(
                'lela_headline_generation',
                [
                    'label' => esc_html__('AI Headline Generation', 'livemesh-el-assistant'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $element->add_control(
                'lela_prompt',
                [
                    'type' => Controls_Manager::TEXTAREA,
                    'label' => esc_html__('Prompt for Headline Generation', 'livemesh-el-assistant'),
                    'label_block' => true,
                    'rows' => 2,
                    'description' => wp_kses_post(__('Provide subject and instructions for the one or more headlines that you want to generate. You can also paste here existing headline and request AI to rewrite that or suggest variations of it for you. A few example headline styles <a target="_blank" href="https://www.indeed.com/career-advice/career-development/types-of-headlines">here</a>.', 'livemesh-el-assistant')),
                    'dynamic' => [
                        'active' => true,
                    ],
                ]
            );

            $element->add_control(
                'lela_keywords',
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

            $element->add_control(
                'lela_language',
                [
                    'type' => Controls_Manager::SELECT,
                    'label' => esc_html__('Choose Language', 'livemesh-el-assistant'),
                    'description' => esc_html__('The headline will be generated in the selected language.', 'livemesh-el-assistant'),
                    'options' => lela_get_supported_languages(),
                    'default' => 'English',
                ]
            );

            $element->add_control(
                'lela_generate',
                [
                    'type' => Controls_Manager::BUTTON,
                    'label' => '',
                    'separator' => 'before',
                    'show_label' => false,
                    'text' => esc_html__('Generate', 'livemesh-el-assistant'),
                    'button_type' => 'default',
                    'event' => 'lela:headline:generate'

                ]
            );

        }

        /**
         * Register OpenAI configuration controls. Make sure you prefix everything to avoid conflict with Elementor widget
         *
         * @param Controls_Stack $element Elementor element.
         * @param string $section_id Section ID.
         */
        public function register_openai_settings_controls(Controls_Stack $element, $section_id) {

            $element->start_controls_section(
                'lela_section_openai_api_settings',
                [
                    'label' => esc_html__('OpenAI API Settings', 'livemesh-el-assistant'),
                    'tab' => Controls_Manager::TAB_SETTINGS,
                ]
            );

            $element->add_control(
                'lela_gpt3_model',
                [
                    'type' => Controls_Manager::SELECT,
                    'label' => esc_html__('Choose a GPT Model to use', 'livemesh-el-assistant'),
                    'description' => wp_kses_post(__('Know more about these models and their capabilities <a target="_blank" href="https://platform.openai.com/docs/models/overview">here</a>', 'livemesh-el-assistant')),
                    'options' => lela_get_openai_models(),
                    'default' => 'gpt-3.5-turbo',
                ]
            );

            $element->add_control(
                'lela_max_tokens',
                [
                    'label' => esc_html__('Maximum Tokens', 'livemesh-el-assistant'),
                    'description' => esc_html__('The maximum number of tokens to use for generation. Higher values means more content will be generated.', 'livemesh-el-assistant'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 400,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 1,
                            'max' => 2048,
                        ],
                    ],
                ]
            );

            $element->add_control(
                'lela_temperature',
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

            $element->add_control(
                'lela_presence_penalty',
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

            $element->add_control(
                'lela_frequency_penalty',
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

            $element->end_controls_section();

        }

        public function generate_headline() {

            check_ajax_referer('lela-assistant', '_ajax_nonce-lela-assistant');

            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('Error! Your account does not have user permission to generate headline!', 'livemesh-el-assistant'));
            }

            $model_params = $headline_params = array();

            if (isset($_POST['modelParams'])) {
                $model_params = array_map('sanitize_text_field', $_POST['modelParams']);
            }
            if (isset($_POST['headlineParams'])) {
                $headline_params = array_map('sanitize_text_field', $_POST['headlineParams']);
            }

            $output = '';

            try {

                if (empty($headline_params['prompt'])) {
                    throw new \Exception(esc_html__('You have not provided the topic for the headline that you want to generate!', 'livemesh-el-assistant'));
                }

                $prompt = 'Write me one or more headlines for the topic and instructions that follow: ' . $headline_params['prompt'] . '\n';;
                if ($headline_params['keywords'] != '') {
                    $prompt .= 'Place these keywords: ' . $headline_params['keywords'] . '\n';
                }
                $prompt .= 'Generate in this language: ' . $headline_params['language'] . '\n';

                $call_params = array(
                    'model' => $model_params['gpt3Model'],
                    'max_tokens' => intval($model_params['maxTokens']),
                    'temperature' => floatval($model_params['temperature']),
                    'top_p' => 1,
                    'n' => 1,
                    'stream' => false,
                    'frequency_penalty' => floatval($model_params['frequencyPenalty']),
                    'presence_penalty' => floatval($model_params['presencePenalty']),
                );

                $chat_mode = true;
                if($call_params['model'] == 'gpt-3.5-turbo-instruct') {
                    $chat_mode = false;
                }

                if ($chat_mode) {
                    $url = 'https://api.openai.com/v1/chat/completions';
                    $call_params['messages'] = array(
                        array('role' => 'user', 'content' => $prompt)
                    );
                }
                else {
                    $url = 'https://api.openai.com/v1/completions';
                    $call_params['prompt'] = $prompt;
                    $call_params['best_of'] = 1;
                }

                $response = $this->openai_client->dispatch($url, $call_params);

                $choices = $response['choices'];

                if ( count( $choices ) == 0 ) {
                    throw new \Exception(esc_html__('No response was generated. Please try again using different prompt!', 'livemesh-el-assistant'));
                }

                if ($chat_mode) {
                    $output = $choices[0]['message']['content'];
                }
                else {
                    $output =$choices[0]['text'];
                }

                wp_send_json_success(trim($output));

            } catch (\Throwable $throwable) {
                wp_send_json_error(esc_html__('Error! ', 'livemesh-el-assistant') . $throwable->getMessage());
            }

        }

    }

endif; // End if class_exists check



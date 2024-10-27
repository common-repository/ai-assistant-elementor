<?php

namespace LivemeshAddons;

// Exit if accessed directly

if (!defined('ABSPATH'))
    exit;


if (!class_exists('LELA_OpenAI_Client')) :

    /**
     * Main LELA_OpenAI_Client Class
     *
     */
    class LELA_OpenAI_Client {

        /**
         * @throws \Exception
         */
        public function dispatch($url, $params) {

            $open_ai_key = lela_get_option('lela_openai_api_secret_key');
            if ($open_ai_key == '') {
                throw new \Exception(esc_html__('The OpenAI API secret key is not specified in the plugin settings. Go to Settings -> Elementor AI Assistant to provide the same.', 'livemesh-el-assistant'));
            }
            $headers = array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $open_ai_key
            );
            $options = array(
                'headers' => $headers,
                'method' => 'POST',
                'timeout' => 120,
                'redirection' => 5,
                'body' => json_encode($params),
                'sslverify' => false
            );
            $response = wp_remote_post($url, $options);

            if (is_wp_error($response)) {
                throw new \Exception($response->get_error_message());
            }

            $response_decoded = json_decode(wp_remote_retrieve_body($response), true);

            if (isset($response_decoded['error'])) {
                $message = $response_decoded['error']['message'];
                // If the message contains "Incorrect API key provided: THE_KEY.", replace the key by "----".
                if (preg_match('/API key provided(: .*)\./', $message, $matches)) {
                    $message = str_replace($matches[1], '', $message);
                }
                throw new \Exception($message);
            }

            return $response_decoded;
        }

    }

endif; // End if class_exists check



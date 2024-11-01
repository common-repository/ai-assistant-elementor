if (typeof ( jQuery ) != 'undefined') {

    const { __ } = wp.i18n;

    ( function ( $ ) {
        'use strict';

        var LELA_Content_Generator;
        var LELA_Headline_Generator;

        LELA_Content_Generator = function ( controlView ) {

            this.init( controlView );

        };

        LELA_Content_Generator.prototype = {

            controls: '',
            editor: '',
            settings: '',

            init: function ( controlView ) {

                this.controls = $( controlView ).get( 0 ).$el.parentsUntil( '.elementor-controls-stack' );

                this.editor = elementor.getPanelView().currentPageView.getControlViewByName( 'editor' )

                this.settings = controlView.container.settings.attributes;

            },
            handleGenerateEvent: function () {

                var self = this;

                self.editor.trigger( 'change' );

                const promptControl = self.controls.find( '.elementor-control-type-textarea textarea[data-setting="lela_prompt"]' );
                const siblingDiv = promptControl.next('.elementor-dynamic-cover'); // check if the dynamic field is set

                promptControl.css( { borderColor: 'inherit' } );

                if (siblingDiv.length === 0 && promptControl.val().trim().length == 0) {
                    promptControl.css( { borderColor: '#93003c' } );
                    alert( __( 'Error! The prompt is blank. No instructions provided for content generation', 'livemesh-el-assistant' ) );
                    return;
                }

                self.postGenerateRequest();

            },
            prepareForGenerate: function () {

                var generateButton = this.controls.find( '.elementor-button-default[data-event="lela:content:generate"]' );
                generateButton.prop( "disabled", true );
                generateButton.before( '<span class="elementor-control-spinner" style="display: inline;"><i class="eicon-spinner eicon-animation-spin"  style="font-size: 16px; color: #930b3c;"></i>&nbsp;&nbsp;</span>' );

            },
            doneWithGenerate: function () {

                var generateButton = this.controls.find( '.elementor-button-default[data-event="lela:content:generate"]' );
                generateButton.siblings( '.elementor-control-spinner' ).remove();
                generateButton.prop( "disabled", false );

            },
            postGenerateRequest: function () {

                var self = this;

                var modelParams = {
                    'gpt3Model': self.settings['lela_gpt3_model'],
                    'maxTokens': self.settings['lela_max_tokens'].size,
                    'temperature': self.settings['lela_temperature'].size,
                    'presencePenalty': self.settings['lela_presence_penalty'].size,
                    'frequencyPenalty': self.settings['lela_frequency_penalty'].size,
                };

                var contentParams = {
                    'prompt': self.settings['lela_prompt'],
                    'keywords': self.settings['lela_keywords'],
                    'language': self.settings['lela_language'],
                };


                var requestData = {
                    'action': 'lela_generate_content',
                    'contentParams': contentParams,
                    'modelParams': modelParams,
                    '_ajax_nonce-lela-assistant': lela_ajax_object.assistant_nonce
                };

                $.ajax( {
                    url: lela_ajax_object.ajax_url,
                    type: 'POST',
                    data: requestData,
                    beforeSend: function () {
                        self.prepareForGenerate();
                    },
                    success: function ( response ) {
                        self.handleGenerateResponse( response );
                    },
                    complete: function () {
                        self.doneWithGenerate();
                    }
                } )

            },
            handleGenerateResponse: function ( response ) {

                var self = this;

                if (!response.success) {
                    alert( response.data );
                } else {
                    var activeEditor = self.editor.editor;
                    var content = response.data.trim().replace( /\r?\n/g, '<br />' );
                    activeEditor.setContent( content, { format: 'html' } );
                    activeEditor.fire( 'change' );
                }

            },

        };

        LELA_Headline_Generator = function ( controlView ) {

            this.init( controlView );

        };

        LELA_Headline_Generator.prototype = {

            controls: '',
            editor: '',
            settings: '',

            init: function ( controlView ) {

                this.controls = $( controlView ).get( 0 ).$el.parentsUntil( '.elementor-controls-stack' );

                this.editor = elementor.getPanelView().currentPageView.getControlViewByName( 'title' )

                this.settings = controlView.container.settings.attributes;

            },
            handleGenerateEvent: function () {

                var self = this;

                self.editor.trigger( 'change' );

                const promptControl = self.controls.find( '.elementor-control-type-textarea textarea[data-setting="lela_prompt"]' );

                promptControl.css( { borderColor: 'inherit' } );

                if (promptControl.val().trim().length == 0) {
                    promptControl.css( { borderColor: '#93003c' } );
                    alert( __( 'Error! The prompt is blank. No instructions provided for headline generation', 'livemesh-el-assistant' ) );
                    return;
                }

                self.postGenerateRequest();

            },
            prepareForGenerate: function () {

                var generateButton = this.controls.find( '.elementor-button-default[data-event="lela:headline:generate"]' );
                generateButton.prop( "disabled", true );
                generateButton.before( '<span class="elementor-control-spinner" style="display: inline;"><i class="eicon-spinner eicon-animation-spin"  style="font-size: 16px; color: #930b3c;"></i>&nbsp;&nbsp;</span>' );

            },
            doneWithGenerate: function () {

                var generateButton = this.controls.find( '.elementor-button-default[data-event="lela:headline:generate"]' );
                generateButton.siblings( '.elementor-control-spinner' ).remove();
                generateButton.prop( "disabled", false );

            },
            postGenerateRequest: function () {

                var self = this;

                var modelParams = {
                    'gpt3Model': self.settings['lela_gpt3_model'],
                    'maxTokens': self.settings['lela_max_tokens'].size,
                    'temperature': self.settings['lela_temperature'].size,
                    'presencePenalty': self.settings['lela_presence_penalty'].size,
                    'frequencyPenalty': self.settings['lela_frequency_penalty'].size,
                };

                var headlineParams = {
                    'prompt': self.settings['lela_prompt'],
                    'keywords': self.settings['lela_keywords'],
                    'language': self.settings['lela_language'],
                };

                var requestData = {
                    'action': 'lela_generate_headline',
                    'headlineParams': headlineParams,
                    'modelParams': modelParams,
                    '_ajax_nonce-lela-assistant': lela_ajax_object.assistant_nonce
                };

                $.ajax( {
                    url: lela_ajax_object.ajax_url,
                    type: 'POST',
                    data: requestData,
                    beforeSend: function () {
                        self.prepareForGenerate();
                    },
                    success: function ( response ) {
                        self.handleGenerateResponse( response );
                    },
                    complete: function () {
                        self.doneWithGenerate();
                    }
                } )

            },
            handleGenerateResponse: function ( response ) {

                var self = this;

                if (!response.success) {
                    alert( response.data );
                } else {
                    self.editor.setValue( response.data );
                    self.editor.applySavedValue();
                }

            },

        };

        $( window ).on( "elementor/init", function () {

            elementor.channels.editor.on( 'lela:content:generate', function ( controlView ) {
                var handler = new LELA_Content_Generator( controlView );
                handler.handleGenerateEvent();
            } );

            elementor.channels.editor.on( 'lela:headline:generate', function ( controlView ) {
                var handler = new LELA_Headline_Generator( controlView );
                handler.handleGenerateEvent();
            } );

        } );

    } )( jQuery );
}


/*global jQuery:false*/

jQuery(document).ready(function () {
    'use strict';

    LELA_JS.init();
    // Run tab open/close event
    LELA_Tab.event();
});

// Init all fields functions (invoked from ajax)
var LELA_JS = {
    init: function () {
        // Run tab open/close
        LELA_Tab.init();
        // Load colorpicker if field exists
        LELA_ColorPicker.init();
    }
};


var LELA_ColorPicker = {
    init: function () {
        var $colorPicker = jQuery('.lela-colorpicker');
        if ($colorPicker.length > 0) {

            $colorPicker.wpColorPicker();

        }
    }
};

var LELA_Tab = {
    init: function () {
        // display the tab chosen for initial display in content
        jQuery('.lela-tab.selected').each(function () {
            LELA_Tab.check(jQuery(this));
        });
    },
    event: function () {
        jQuery(document).on('click', '.lela-tab', function () {
            LELA_Tab.check(jQuery(this));
        });
    },
    check: function (elem) {
        var chosen_tab_name = elem.data('target');
        elem.siblings().removeClass('selected');
        elem.addClass('selected');
        elem.closest('.lela-inner').find('.lela-tab-content').removeClass('lela-tab-show').hide();
        elem.closest('.lela-inner').find('.lela-tab-content.' + chosen_tab_name + '').addClass('lela-tab-show').show();
    }
};
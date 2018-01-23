// Setup the namespace
var SLP_Admin_Settings_Help = SLP_Admin_Settings_Help || {};

/**
 * Help UX
 */
var SLP_Admin_Help_UX = function () {

    /**
     * Initialize the UX
     */
    this.initialize = function () {
        jQuery('div.input-group').mouseenter( function() {
            var title = jQuery(this).find("LABEL").html();
            if ( typeof title === 'undefined' ) { return; }
            var details = jQuery(this).find(".input-description").html();
            if ( typeof details === 'undefined' ) { return; }
            jQuery('.settings-description').toggleClass('is-visible').html( '<h3>' + title + '</h3>' + details );
        });

        jQuery('.dashboard-main').mouseleave( SLP_Admin_Settings_Help.UX.clear_more_info );
        jQuery('.subtab_navbar_wrapper').mouseenter( SLP_Admin_Settings_Help.UX.clear_more_info );

        jQuery('H3.aside-heading').click( SLP_Admin_Settings_Help.UX.accordian );
    }

    this.accordian = function() {
        var is_now = jQuery( '.dashboard-aside-secondary' ).css( 'flex-basis' );
        if ( is_now === '350px' ) {
            jQuery('.dashboard-aside-secondary').css('flex-basis', '10px');
        } else {
            jQuery('.dashboard-aside-secondary').css('flex-basis', '350px');
        }
    }

    /**
     * Clear the more info box.
     */
    this.clear_more_info = function() {
        jQuery('.settings-description').toggleClass('is-visible').html('');
    }
};

// Experience Tab Only
if ( adminpage === 'store-locator-plus_page_slp_experience' ) {
    var SLP_Admin_Plugin_Style = function () {
        var starting_theme;

        /**
         * Things we do to start.
         */
        this.initialize = function() {
            jQuery( 'select#options_nojs\\[theme\\]' ).on('change keyup', SLP_Admin_Settings_Help.PluginStyle.show_details );
            SLP_Admin_Settings_Help.PluginStyle.starting_theme = jQuery( 'select#options_nojs\\[theme\\] option:selected' ).val();
            jQuery( '#wpcsl-option-view_sidemenu' ).on('click', SLP_Admin_Settings_Help.PluginStyle.show_details );
        }

        /**
         * Show the theme details panel and hide the prior active selection.
         *
         * @returns {undefined}
         */
        this.show_details = function () {
            var selected_theme = jQuery('select#options_nojs\\[theme\\] option:selected').val();
            var selected_theme_details = '#' + selected_theme + '_details';

            var content = '<h3>' + jQuery('select#options_nojs\\[theme\\] option:selected').text() + '</h3>' +jQuery( selected_theme_details ).html();
            jQuery('.settings-description').toggleClass('is-visible').html( content );

            // Auto apply plugin theme layouts
            if ( selected_theme !== SLP_Admin_Settings_Help.PluginStyle.starting_theme ) {
                SLP_Admin_Settings_Help.PluginStyle.starting_theme = selected_theme;
                SLP_Admin_Settings_Help.PluginStyle.set_theme_options( selected_theme_details );
            }
        }

        /**
         * Set theme options on plugin style change.
         * @returns {boolean}
         */
        this.set_theme_options = function ( selected_theme_details ) {
            jQuery( selected_theme_details + ' > .theme_option_value').each(
                function(index) {
                    var field_name = jQuery(this).attr('settings_field');
                    jQuery('[name="' + field_name + '"]').val(jQuery(this).text());
                }
            );
            return false;
        }

    };
}

/**
 * Locations Tab Admin JS
 */
jQuery(document).ready(
    function() {
        SLP_Admin_Settings_Help.UX = new SLP_Admin_Help_UX();
        SLP_Admin_Settings_Help.UX.initialize();

        // Experience Tab Only
        if ( adminpage === 'store-locator-plus_page_slp_experience' ) {
            SLP_Admin_Settings_Help.PluginStyle = new SLP_Admin_Plugin_Style();
            SLP_Admin_Settings_Help.PluginStyle.initialize();
        }
    }
);

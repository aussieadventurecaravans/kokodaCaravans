/*****************************************************************
 * file: userinterface.js
 *
 * Enhanced Search User Interface Modifications
 * for Store Locator Plus
 *
 *****************************************************************/
/**
 * A base class that helps add-on packs separate ui functionality.
 *
 * Add on packs should include and extend this class.
 *
 * This allows the main plugin to only include this file when rendering the front end.
 * Reduces the front-end footprint.
 *
 * @package StoreLocatorPlus\EnhancedSearch\UserInterfaceJS
 * @author Lance Cleveland <lance@charlestonsw.com>
 * @copyright 2015 Charleston Software Associates, LLC
 *
 * @type {SLPES|*|{}}
 */

// Setup the Enhanced Search Namespace
var SLPES = SLPES || {};

SLPES.address_quicksearch = {

    /**
     * Attach the address input handler if needed.
     */
    attach_ui_handler: function() {

        // Check that the quicksearch is enabled.
        //
        if (
            ( typeof slplus.options['address_autocomplete'] != 'undefined' ) &&
            ( slplus.options['address_autocomplete'] === 'zipcode' )
        ) {
            jQuery( '#addressInput').autocomplete(
                {
                    source: SLPES.address_quicksearch.query_locations,
                    minLength: slplus.options.address_autocomplete_min
                }
            );
        }
    },

    /**
     * Query locations for autocomplete on zip.
     *
     * @param request
     * @param callback
     */
    query_locations: function( request , callback ) {
        post_vars = { action: 'slp_list_location_zips' , address: request.term };
        jQuery.post(
            slplus.ajaxurl,
            post_vars ,
            function (results) { callback( JSON.parse(results) ); }
        );
    }
};

/**
 * The address and city/state/country selector manager for 'either_or' mode.
 *
 */
SLPES.selector_handler = {

    address_value: '',
    current_value: {
        'addressInputState' : ''
    },

    /**
     * Attach the city/state/zip and address interaction handler if needed.
     */
    attach_ui_handler: function() {

            // City/State/Zip Selector Disables Address
            if  (
                ( typeof slplus.options['selector_behavior'] != 'undefined' ) &&
                ( slplus.options['selector_behavior'] === 'either_or' )
            ) {
                jQuery( '#addressInput').click( SLPES.selector_handler.activate );
                jQuery( '#addressInputCity').click( SLPES.selector_handler.activate );
                jQuery( '#addressInputState').click( SLPES.selector_handler.activate );
                jQuery( '#addressInputCountry').click( SLPES.selector_handler.activate );
            }
    } ,

    /**
     * Activate the given input element.  Store selections if necessary.
     */
    activate: function( ) {
        element_name = jQuery( this ).attr('name');

        switch ( element_name ) {
            case 'addressInputCity'     :
            case 'addressInputState'    :
            case 'addressInputCountry'  :
                SLPES.selector_handler.save_address();
                SLPES.selector_handler.restore_dropdown_selection( element_name );
                break;

            case 'addressInput' :
                SLPES.selector_handler.save_dropdown_selection('addressInputCity'   );
                SLPES.selector_handler.save_dropdown_selection('addressInputState'  );
                SLPES.selector_handler.save_dropdown_selection('addressInputCountry');
                SLPES.selector_handler.restore_address();
                break;
        }

    },

    restore_address: function() {
        current_address_value = jQuery( '#addressInput' ).val();

        // Save the address value if it is not empty
        //
        if ( ! current_address_value ) {
            jQuery('#addressInput').val( this.address_value );
            this.address_value = '';
        }
    },


    /**
     * Restore dropdown selection.
     */
    restore_dropdown_selection: function( element_name ) {
        element_id    = '#' + element_name;
        current_value = jQuery( element_id ).find( 'option:selected').val();

        // If the dropdown is empty AND the previously saved value is NOT empty...
        //
        if ( ( ! current_value ) && ( this.current_value[element_name] ) ) {
            option_by_val = element_id + ' option[value="' + SLPES.selector_handler.current_value[element_name] + '"]';
            
            jQuery( element_id ).val( SLPES.selector_handler.current_value[element_name] );
            jQuery( element_id ).blur();
            jQuery( element_id ).focus();
            jQuery( option_by_val ).prop( 'selected' , true );

            jQuery( element_id ).show();

            this.current_value[element_name] = '';
        }
    },

    /**
     * Save address.
     */
    save_address: function() {
        current_address_value = jQuery( '#addressInput' ).val();

        // Save the address value if it is not empty
        //
        if ( current_address_value ) {
            SLPES.selector_handler.address_value = current_address_value;
            jQuery('#addressInput').val('');
        }
    } ,

    /**
     * Save dropdown selection.
     */
    save_dropdown_selection: function( element_name ) {
        element_id    = '#' + element_name;
        current_value = jQuery( element_id ).find( 'option:selected').val();

        if ( current_value ) {
            SLPES.selector_handler.current_value[element_name] = current_value;
            jQuery( element_id ).val( '' );
        }
    }
};


// Document Ready
jQuery( document ).ready(
    function () {
        SLPES.selector_handler.attach_ui_handler();
        SLPES.address_quicksearch.attach_ui_handler();
    }
);





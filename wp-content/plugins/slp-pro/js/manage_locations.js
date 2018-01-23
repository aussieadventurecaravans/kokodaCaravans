/*
 * 
 * manage_locations.js
 *
 * Pro Pack Manage Locations page scripts.
 *
 * @requires jQuery v1.2.3
 * 
 * GPL 2 licensed.
 * http://www.gnu.org/licenses/gpl.html
 * 
 */


var SLP_PRO_LOCATION_MANAGER = SLP_PRO_LOCATION_MANAGER || {

        /**
         * Info Box Object
         */
        infobox: function() {
            var current_message = '';

            /**
             * Update the info box.
             */
            this.update = function( json_data ) {

                // Show alerts returned in json data.
                //
                if ( json_data.alert ) {
                    alert( json_data.alert);
                    return;
                }

                // No message? Leave.
                //
                if ( ! json_data.message ) {
                    return;
                } else {
                    this.current_message = json_data.message;
                }
                jQuery('#slp-pro_messages').append( this.create_string_message_div() );
                jQuery('#slp-pro_message_board').show();
            } ,

            /**
             * Create the message string div.
             */
            this.create_string_message_div = function() {
                return (
                    '<div class="slp-pro_message">' +
                    this.current_message +
                    '</div>'
                );
            }

        }


}

// Document Is Ready...
//
jQuery(document).ready(
    function($) {


        // Process incoming requeset actions
        //
        switch ( request_vars.action ) {

            // Export immediate mode, load CSV into iFrame.
            //
            case 'export':
                jQuery('#pro_csv_download').attr( 'src' ,
                    ajaxurl + '?' +
                    jQuery.param(
                        {
                            action: 'slp_download_locations_csv',
                            filename: 'locations' ,
                            formdata: jQuery('#locationForm').serialize()
                        }
                    )
                );
                break;

            // Export, locally hosted.
            //
            case 'export_local':
                var infobox = new  SLP_PRO_LOCATION_MANAGER.infobox();
                infobox.update( { 'message': request_vars.download_file_message } );
                break;
        }
    }
);
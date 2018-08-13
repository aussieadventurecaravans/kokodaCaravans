<?php
/**
 * Edit Quote form template at admin
 *
 */

if ( ! class_exists( 'Quote' ) ) {
    // Includes
    include(KOKODA_CUSTOM_ORDER_PLUGIN_URL .'includes/models/quote.php');
}


// don't load directly
if ( !defined('ABSPATH') )
    die('-1');
?>
<?php
$_QUOTE_ID = $_REQUEST['quote_id'];
if(isset($_REQUEST['quote_id']) && $_QUOTE_ID != '')
{
    $quote = Quote::get_instance($_QUOTE_ID);
}

if(!isset($quote->quote_id))
{
    wp_redirect('/wp-admin/admin.php?page=quotes');
}

$ajax_edit_url =  plugins_url('/kokoda-custom-order/includes/admin/quote_edit.php');
?>

    <form method="post" id="quote_edit_form">
        <?php wp_nonce_field('update-quote' . $quote->quote_id) ?>
        <input type="hidden" name="ajax_edit_url" value="<?php echo $ajax_edit_url; ?>" />
        <div class="wrap header">
            <h1><?php _e( 'Edit Quote #' ); ?> <?php echo $quote->quote_id; ?> </h1>
            <div class="notice-wrapper"></div>
            <div id="poststuff">
                <input type="hidden" name="action" value="editquote" />
                <input type="hidden" name="quote_id" value="<?php echo esc_attr( $quote->quote_id ); ?>" />


                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content" class="edit-form-section edit-comment-section">
                        <div id="namediv" class="stuffbox">
                            <div class="inside">
                                <fieldset>
                                    <h2 class="hndle ui-sortable-handle"><?php _e( 'Customer Info' ) ?></h2>
                                    <table class="form-table editcomment">
                                        <tbody>
                                        <tr>
                                            <td class="first"><label for="customer_name"><?php _e( 'Name:' ); ?></label></td>
                                            <td><input type="text" name="customer_name" size="30" value="<?php echo esc_attr( $quote->customer_name ); ?>" id="customer_name" /></td>
                                        </tr>
                                        <tr>
                                            <td class="first"><label for="customer_address"><?php _e( 'Address:' ); ?></label></td>
                                            <td><input type="text" name="customer_address" size="30" value="<?php echo esc_attr( $quote->customer_address ); ?>" id="customer_address" /></td>
                                        </tr>
                                        <tr>
                                            <td class="first"><label for="customer_postcode"><?php _e( 'Postcode:' ); ?></label></td>
                                            <td><input type="text" name="customer_postcode" size="30" value="<?php echo esc_attr( $quote->customer_postcode ); ?>" id="customer_postcode" /></td>
                                        </tr>
                                        <tr>
                                            <td class="first"><label for="customer_state"><?php _e( 'State:' ); ?></label></td>
                                            <td><input type="text" name="customer_state" size="30" value="<?php echo esc_attr( $quote->customer_state ); ?>" id="customer_state" /></td>
                                        </tr>
                                        <tr>
                                            <td class="first"><label for="customer_phone"><?php _e( 'Phone:' ); ?></label></td>
                                            <td><input type="text" name="customer_phone" size="30" value="<?php echo esc_attr( $quote->customer_phone ); ?>" id="customer_phone" /></td>
                                        </tr>
                                        <tr>
                                            <td class="first"><label for="customer_email"><?php _e( 'Email:' ); ?></label></td>
                                            <td>
                                                <input type="text" name="customer_email" size="30" value="<?php echo $quote->customer_email; ?>" id="email" />
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <br />
                                </fieldset>
                                <fieldset>
                                    <h2 class="hndle ui-sortable-handle"><?php _e( 'Quote Details' ) ?></h2>
                                    <table class="form-table editquote">
                                        <tbody>
                                        <tr>
                                            <td class="first"><label for="product_name"><?php _e( 'Caravan:' ); ?></label></td>
                                            <td><input type="text" name="product_name" size="30" value="<?php echo esc_attr( $quote->product_name ); ?>" id="product_name" /></td>
                                        </tr>
                                        <tr>
                                            <td class="first"><label for="product_cost"><?php _e( 'Caravan Cost:' ); ?></label></td>
                                            <td><input type="number" name="product_cost" size="30" value="<?php echo esc_attr( $quote->product_cost ); ?>" id="product_cost" /></td>
                                        </tr>
                                        <tr>
                                            <td class="first"><label for="add_on_cost"><?php _e( 'Add on Cost:' ); ?></label></td>
                                            <td><input type="text" name="add_on_cost" size="30" value="<?php echo esc_attr( $quote->add_on_cost ); ?>" id="add_on_cost" /></td>
                                        </tr>
                                        <tr>
                                            <td class="first"><label for="total_cost"><?php _e( 'Total Cost:' ); ?></label></td>
                                            <td><input type="text" name="total_cost" size="30" value="<?php echo esc_attr( $quote->total_cost ); ?>" id="total_cost" /></td>
                                        </tr>
                                        <tr>
                                            <td class="first"><label for="payment_method"><?php _e( 'Payment Method:' ); ?></label></td>
                                            <td><input type="text" name="payment_method" size="30" value="<?php echo esc_attr( $quote->payment_method ); ?>" id="payment_method" /></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <br />
                                </fieldset>
                            </div>
                        </div>
                    </div><!-- /post-body-content -->

                    <div id="postbox-container-1" class="postbox-container">
                        <div id="submitdiv" class="stuffbox" >
                            <h2><?php _e( 'Status' ) ?></h2>
                            <div class="inside">
                                <div class="submitbox" id="submitcomment">
                                    <div id="minor-publishing">

                                        <div id="misc-publishing-actions">
                                            <div class="misc-pub-section curtime misc-pub-curtime">
                                                <?php
                                                /* translators: Publish box date format, see https://secure.php.net/date */
                                                $datef = __( 'M j, Y @ H:i' );
                                                ?>
                                                <span id="timestamp"><?php
                                                    printf(
                                                    /* translators: %s: comment date */
                                                        __( 'Submitted on: %s' ),
                                                        '<b>' . date_i18n( $datef, strtotime( $quote->date_created ) ) . '</b>'
                                                    );
                                                    ?>
                                                </span>
                                            </div>
                                            <div class="misc-pub-section curtime misc-pub-curtime">
                                                <?php
                                                /* translators: Publish box date format, see https://secure.php.net/date */
                                                $datef = __( 'M j, Y @ H:i' );
                                                ?>
                                                <span id="timestamp"><?php
                                                    printf(
                                                    /* translators: %s: comment date */
                                                        __( 'Modified on: %s' ),
                                                        '<b>' . date_i18n( $datef, strtotime( $quote->date_modified ) ) . '</b>'
                                                    );
                                                    ?>
                                                </span>
                                            </div>
                                        </div> <!-- misc actions -->
                                        <div class="clear"></div>
                                    </div>

                                    <div id="major-publishing-actions">
                                        <div id="delete-action">
                                            <input type="button" value="Delete" class="quote_edit_form_delete_ajax button-secondary">
                                        </div>
                                        <div id="publishing-action">
                                            <input type="button" value="Update" class="quote_edit_form_submit_ajax button-primary">
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /submitdiv -->
                    </div>

                    <div id="postbox-container-2" class="postbox-container">
                        <?php
                        /**
                         * Fires when comment-specific meta boxes are added.
                         *
                         * @since 3.0.0
                         *
                         * @param Quote $quote Quote object.
                         */
                        do_action( 'add_meta_boxes_quote', $quote );

                        do_meta_boxes(null, 'normal', $quote);

                        $referer = wp_get_referer();
                        ?>
                    </div>

                    <input type="hidden" name="c" value="<?php echo esc_attr($quote->quote_id) ?>" />
                    <input name="referredby" type="hidden" id="referredby" value="<?php echo $referer ? esc_url( $referer ) : ''; ?>" />
                    <?php wp_original_referer_field(true, 'previous'); ?>
                    <input type="hidden" name="noredir" value="1" />
                </div><!-- /post-body -->
            </div>


        </div>
    </form>

<script type="text/javascript">


    jQuery(document).ready(function($)
    {

        $('.quote_edit_form_submit_ajax').click(submitUpdate);
        $('.quote_edit_form_delete_ajax').click(submitDelete);
        $('button.notice-dismiss').live('click' , dismissNotice);

        function submitUpdate()
        {
            var ajax_url = "<?php echo $ajax_edit_url; ?>";
            jQuery.ajax({
                type:'POST',
                url:ajax_url,
                data: $('form#quote_edit_form').serialize(),
                success:function(msg)
                {
                    if (msg.valid == true)
                    {
                        jQuery('form#quote_edit_form .header .notice-wrapper').html('<div class="notice notice-success is-dismissible"><p>'+ msg.message + '</p>' +
                            '<button type="button" class="notice-dismiss">' +
                            '<span class="screen-reader-text">Dismiss this notice.</span>' +
                            '</button>' +
                            '</div>');
                    }
                },
                error:function(html)
                {
                    if (msg.valid == true)
                    {
                        jQuery('form#quote_edit_form .header .notice-wrapper').html('<div class="error is-dismissible"><p>'+ msg.message + '</p>' +
                        '<button type="button" class="notice-dismiss">' +
                        '<span class="screen-reader-text">Dismiss this notice.</span>' +
                        '</button>' +
                        '</div>');
                    }
                }
             }).done(function (res) {

            });
        }
        function submitDelete()
        {
            var ajax_url = "<?php echo $ajax_edit_url; ?>";
            if(confirm('Are you sure you want to delete this Quote'))
            {
                jQuery.ajax({
                    type: 'POST',
                    url: ajax_url,
                    data: {
                        action: 'deletequote',
                        quote_id: $('input[name=quote_id]').val(),
                        _wpnonce: $('input[name=_wpnonce]').val()
                    },
                    success: function (msg)
                    {
                        if (msg.valid == true)
                        {
                            $(location).attr('href', $('input[name=referredby]').val());
                        }
                    },
                    error: function (msg)
                    {
                        jQuery('form#quote_edit_form .header .notice-wrapper').html('<div class="error is-dismissible"><p>'+ msg.message + '</p>' +
                            '<button type="button" class="notice-dismiss">' +
                            '<span class="screen-reader-text">Dismiss this notice.</span>' +
                            '</button>' +
                            '</div>');
                    }
                }).done(function (res) {

                });
            }
        }

        function dismissNotice()
        {
            jQuery('form#quote_edit_form .header .notice-wrapper').html('');
        }

    });


</script>

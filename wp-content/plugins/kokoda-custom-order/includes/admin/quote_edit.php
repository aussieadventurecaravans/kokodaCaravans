<?php
require_once('../../../../../wp-load.php');
require_once('../../../../../wp-config.php');
require_once('../../../../../wp-includes/load.php');

if ( ! class_exists( 'Quote' ) ) {
    require_once(KOKODA_CUSTOM_ORDER_PLUGIN_URL.'includes/models/quote.php');
}


if(isset($_POST['action']) && $_POST['action'] != '' && isset($_POST['quote_id']) && $_POST['quote_id'] != '' )
{
    if (!isset($_POST['_wpnonce']) && !wp_verify_nonce( $_POST['_wpnonce'], 'update-quote'.$_POST['quote_id']  ) )
    {
        $return = array(
            'valid'       => false,
            'message'    => 'Please refresh the page before update or delete'
        );
        wp_send_json($return);
        exit;
    }

    $action = $_POST['action'];

    switch($action)
    {
        case 'editquote':
            $data = $_POST;
            $respond = Quote::update_quote($_POST['quote_id'],$data);
            if(!$respond)
            {
                $return = array(
                    'valid'       => false,
                     'message'  => 'Quote is failed to update',
                );
            }
            else
            {
                $return = array(
                    'message'  => 'Quote is updated',
                    'valid'       => true
                );
            }
            wp_send_json($return);
            break;
        case 'deletequote':
            $respond = Quote::delete_quote($_POST['quote_id']);
            if(!$respond)
            {
                $return = array(
                    'valid'       => false,
                    'message'  => 'Fail to remove Quote',
                );
            }
            else
            {
                $return = array(
                    'message'  => 'Quote is removed',
                    'valid'       => true
                );
            }
            wp_send_json($return);
            break;
        default:
            echo "no action or quote ID specified";
            exit;
    }

}
else
{
    $return = array(
        'valid'       => false,
        'message'    => 'No clear action specified or quote id'
    );
    wp_send_json($return);
}


exit;



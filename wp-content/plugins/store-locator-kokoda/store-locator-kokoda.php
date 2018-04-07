<?php
/**
 * Plugin Name: Store Locator Customize for Kokoda
 * Plugin URI: http://www.wordpress.org/plugins
 * Description: extend and customize the the store locator plus pluggin for kokoda theme
 * Version: 1.0
 * Author: Son Nguyen
 */
class StoreLocatorKokoda
{
    /**
 * init
 */
    function __construct()
    {

    }

}


function search_dealer_by_postcode()
{
    if ( isset( $_POST["dealer_link"] ) )
    {
        $url =   $_POST["dealer_link"];

        wp_send_json_success(array(
            'url' =>  $url.'?address='.$_POST['address']
        ));
    }

}

add_action('wp_ajax_search_dealer','search_dealer_by_postcode');
//add_action('wp_ajax_nopriv_csl_search_dealer', 'search_dealer_by_postcode');
add_filter('slp_search_default_address','add_default_search_address',99);

function add_default_search_address()
{
    if ( isset( $_GET["address"] ) )
    {
        return $_GET['address'];
    }
    return '';
}

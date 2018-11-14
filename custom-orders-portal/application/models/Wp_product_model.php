<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wp_product_model extends CI_Model
{



    public function __construct()
    {
        parent::__construct();
    }

    public function get_product($product_id)
    {
       return false;
    }

    /*
     * Get Product from Wordpress Website
     * with the custom order flag enable
     */

    public function get_products()
    {


        $terms = get_terms('product-cat','orderby=name' );
        //get the ID of category with name "Caravan Archive"
        $caravan_archive_category_id = 0;
        foreach ( $terms as $term ){
            if(in_array( $term->name ,array('Caravan Archive')))
            {
                $caravan_archive_category_id = $term->term_id;
                break;
            }
        }

        //query find the caravans belong to cateogory specified by page
        // and these caravans also don't belong to archive category.
        $args = array(
            'post_type' => 'product',
            'tax_query' => array(
                array(
                    'taxonomy' => 'product-cat',
                    'field'    => 'term_id',
                    'terms'    => array($caravan_archive_category_id),
                    'operator' => 'NOT IN'
                )
            ),
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'nopaging' => true,
            'meta_query'	=> array(
                array(
                    'key'	 	=> 'custom_order_active',
                    'compare' 	=> '=',
                    'value'	  	=> 1,
                ),
            ),
        );


        $models  =  get_posts($args);
        if(count($models) > 0)
        {

            return $models;
        }

        return false;
    }
}
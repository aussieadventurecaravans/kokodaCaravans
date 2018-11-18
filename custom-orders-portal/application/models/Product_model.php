<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model
{
    const PRODUCT_TABLE = "products";


    public function __construct()
    {
        parent::__construct();
    }

    public function get_product($product_id)
    {
        $custom_order_db = $this->db;

        $query = $custom_order_db->get_where(self::PRODUCT_TABLE, array('product_id'=>$product_id));

        $custom_order_db->close();


        if($query->num_rows() > 0)
        {
            return $query->row_array();
        }
        else
        {

            return false;
        }
    }

    public function get_products()
    {
        //load products table
        $custom_order_db = $this->db;
        $custom_order_db->select('*');
        $custom_order_db->from(self::PRODUCT_TABLE);
        $custom_order_db->order_by('product_id', 'ASC');

        $query =  $custom_order_db->get();
        $custom_order_db->close();

        if($query->num_rows() > 0)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }


    public function get_customOptions($product_id)
    {
        $custom_order_db = $this->db;

        $custom_order_db->select('custom_options');

        $query = $custom_order_db->get_where(self::PRODUCT_TABLE, array('product_id'=>$product_id));

        $custom_order_db->close();


        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                return unserialize($row['custom_options']);
            }
        }
        else
        {

            return false;
        }
    }
}
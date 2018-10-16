<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }


    public function get_orders()
    {

        $custom_order_db = $this->db;
        $custom_order_db->select('*');
        $custom_order_db->order_by('order_id', 'DESC');
        $custom_order_db->from('custom_orders');

        $query = $custom_order_db->get();

        $custom_order_db->close();

        return $query->result_array();

    }

    public function get_order($id)
    {

        $custom_order_db = $this->db;

        $query = $custom_order_db->get_where('custom_orders', array('order_id'=>$id));

        $custom_order_db->close();

        return $query->row_array();
    }
}
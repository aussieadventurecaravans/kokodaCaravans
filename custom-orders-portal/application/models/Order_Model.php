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
        $user_email = $this->session->userdata('user_email');
        $user_role = $this->session->userdata('user_role');


        $custom_order_db = $this->db;
        $custom_order_db->select('*');
        $custom_order_db->order_by('order_id', 'DESC');
        $custom_order_db->from('custom_orders');

        if($user_role != 'admin')
        {
            $custom_order_db->where('dealer_email',$user_email);
        }

        $query = $custom_order_db->get();

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

    public function get_order($id)
    {

        $user_email = $this->session->userdata('user_email');
        $user_role = $this->session->userdata('user_role');


        $custom_order_db = $this->db;

        if($user_role != 'admin')
        {
            $query = $custom_order_db->get_where('custom_orders', array('order_id' => $id));
        }
        else
        {
            $query = $custom_order_db->get_where('custom_orders', array('order_id' => $id , 'dealer_email' => $user_email));
        }
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
}
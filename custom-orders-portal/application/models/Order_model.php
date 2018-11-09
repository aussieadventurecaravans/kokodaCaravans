<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model
{

    const ORDER_TABLE = "custom_orders";

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
        $custom_order_db->from(self::ORDER_TABLE);

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

        if($user_role == 'admin')
        {
            $query = $custom_order_db->get_where(self::ORDER_TABLE, array('order_id' => $id));
        }
        else
        {
            $query = $custom_order_db->get_where(self::ORDER_TABLE, array('order_id' => $id , 'dealer_email' => $user_email));
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


    public function delete_order($id)
    {
        $user_email = $this->session->userdata('user_email');
        $user_role = $this->session->userdata('user_role');

        $custom_order_db = $this->db;

        if($user_role == 'admin')
        {
            $query = $custom_order_db->delete(self::ORDER_TABLE, array('order_id'=>$id));
        }
        else
        {
            $query = $custom_order_db->delete(self::ORDER_TABLE, array('order_id'=>$id,'dealer_email' => $user_email));
        }

        $custom_order_db->close();

        return $query;
    }


    public function update_order($data,$order_id)
    {
        if(!is_array($data) || !isset($order_id))
        {
            return false;
        }
        $custom_order_db = $this->db;

        $custom_order_db->trans_start();

        $custom_order_db->update(self::ORDER_TABLE, $data,  array('order_id' => $order_id));

        $custom_order_db->trans_complete();

        $result =  $custom_order_db->trans_status();

        $custom_order_db->close();

        return $result;
    }
}
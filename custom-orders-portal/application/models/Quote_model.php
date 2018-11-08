<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quote_model extends CI_Model
{

    const QUOTE_TABLE = "quotes";


    public function __construct()
    {
        parent::__construct();
    }


    public function get_quotes()
    {
        $user_email = $this->session->userdata('user_email');
        $user_role = $this->session->userdata('user_role');

        //Load  database to load quote table
        $custom_order_db = $this->db;

        $custom_order_db->select('*');
        $custom_order_db->order_by('quote_id', 'DESC');
        if($user_role == 'admin')
        {
            $query = $custom_order_db->get_where(self::QUOTE_TABLE);
        }
        else
        {
            $query = $custom_order_db->get_where(self::QUOTE_TABLE, array('dealer_email' => $user_email));
        }

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

    public function get_quote($id)
    {

        $user_email = $this->session->userdata('user_email');
        $user_role = $this->session->userdata('user_role');

        //Load  database to load quote table
        $custom_order_db = $this->db;

        if($user_role == 'admin')
        {
            $query = $custom_order_db->get_where(self::QUOTE_TABLE, array('quote_id'=>$id));
        }
        else
        {
            $query = $custom_order_db->get_where(self::QUOTE_TABLE, array('quote_id'=>$id,'dealer_email' => $user_email));
        }


        if($query->num_rows() > 0)
        {
            return $query->row_array();
        }
        else
        {

            return false;
        }
    }


    public function delete_quote($id)
    {
        $user_email = $this->session->userdata('user_email');
        $user_role = $this->session->userdata('user_role');

        //Load  database to load quote table
        $custom_order_db = $this->db;

        if($user_role == 'admin')
        {
            $query = $custom_order_db->delete(self::QUOTE_TABLE, array('quote_id'=>$id));
        }
        else
        {
            $query = $custom_order_db->delete(self::QUOTE_TABLE, array('quote_id'=>$id,'dealer_email' => $user_email));
        }

        $custom_order_db->close();

        return $query;
    }

    public function update_quote($id,$data)
    {
        //Load  database to load quote table
        $custom_order_db = $this->db;

        $custom_order_db->update(self::QUOTE_TABLE, $data,  array('quote_id' => $id));

        $result =  $custom_order_db->affected_rows();

        $custom_order_db->close();

        return $result;
    }


    public function place_order($id)
    {

        $data = array('status' => 'In Order');

        //Load  database to load quote table
        $custom_order_db = $this->db;

        $custom_order_db->trans_start();

        $custom_order_db->update(self::QUOTE_TABLE, $data,  array('quote_id' => $id));

        $custom_order_db->trans_complete();

        $result = $custom_order_db->trans_status();

        if($result === true)
        {
            //copy the quote detail and add it to the custom_order table
            $quote = $this->get_quote($id);


            $result = $custom_order_db->insert('custom_orders',$quote,true);

        }

        $custom_order_db->close();

        return $result;
    }



}

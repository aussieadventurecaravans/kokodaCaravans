<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quote_Model extends CI_Model
{

    const QUOTE_TABLE = "wp_custom_order_quote";


    public function __construct()
    {
        parent::__construct();
    }


    public function get_quotes()
    {
        $user_email = $this->session->userdata('user_email');
        $user_role = $this->session->userdata('user_role');

        //Load Wordpress database to load wp_custom_order_quote table
        $wordpress_db = $this->load->database('wordpress', TRUE);

        $wordpress_db->select('*');
        $wordpress_db->order_by('quote_id', 'DESC');
        if($user_role == 'admin')
        {
            $query = $wordpress_db->get_where(self::QUOTE_TABLE);
        }
        else
        {
            $query = $wordpress_db->get_where(self::QUOTE_TABLE, array('dealer_email' => $user_email));
        }

        $wordpress_db->close();

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

        $wordpress_db = $this->load->database('wordpress', TRUE);

        if($user_role == 'admin')
        {
            $query = $wordpress_db->get_where(self::QUOTE_TABLE, array('quote_id'=>$id));
        }
        else
        {
            $query = $wordpress_db->get_where(self::QUOTE_TABLE, array('quote_id'=>$id,'dealer_email' => $user_email));
        }

        $wordpress_db->close();


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

        $wordpress_db = $this->load->database('wordpress', TRUE);

        if($user_role == 'admin')
        {
            $query = $wordpress_db->delete(self::QUOTE_TABLE, array('quote_id'=>$id));
        }
        else
        {
            $query = $wordpress_db->delete(self::QUOTE_TABLE, array('quote_id'=>$id,'dealer_email' => $user_email));
        }

        $wordpress_db->close();

        return $query;
    }

    public function update_quote($id,$data)
    {
        $wordpress_db = $this->load->database('wordpress', TRUE);

        $wordpress_db->update(self::QUOTE_TABLE, $data,  array('quote_id' => $id));

        $result =  $wordpress_db->affected_rows();

        $wordpress_db->close();

        return $result;

    }
}

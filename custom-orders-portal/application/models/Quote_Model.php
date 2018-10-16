<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quote_Model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function get_quotes()
    {
        //Load Wordpress database to load wp_custom_order_quote table
        $wordpress_db = $this->load->database('wordpress', TRUE);
        $wordpress_db->select('*');
        $wordpress_db->order_by('quote_id', 'DESC');
        $wordpress_db->from('wp_custom_order_quote');

        $query = $wordpress_db->get();

        $wordpress_db->close();

        return $query->result_array();

    }

    public function view_quote($id)
    {
        //Load Wordpress database to load wp_custom_order_quote table
        $wordpress_db = $this->load->database('wordpress', TRUE);

        $query = $wordpress_db->get_where('wp_custom_order_quote', array('quote_id'=>$id));

        $wordpress_db->close();

        return $query->row_array();
    }


}

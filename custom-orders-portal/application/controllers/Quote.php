<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quote extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');

        $this->load->model("quote_model");
    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');

        if(!$user_id)
        {
            redirect( base_url('user/login'), 'refresh');
        }


        $data = array();
        $quote_id = $this->input->get('quote_id');

        $data['content'] = 'Pages/quote_detail';
        $data['quote'] = $this->quote_model->get_quote($quote_id);

        $this->load->view('Layouts/master', $data);

    }
}
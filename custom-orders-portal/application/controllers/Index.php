<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');

        $this->load->model("quoterequest_model");
        $this->load->model("quote_model");
        $this->load->model("order_model");
    }

    public function index()
    {

        $user_id = $this->session->userdata('user_id');

        if(!$user_id)
        {
            $this->session->set_userdata('referer_url',  base_url()  );
            redirect( base_url('user/login'), 'refresh');
        }

        $data = array();
        $data['title'] = 'Custom Orders Management ';
        $data['message'] = 'Kokoda Custom Orders';
        $data['content'] = 'Pages/index';
        $data['quotes'] =  $this->quoterequest_model->get_quotes();
        $this->load->view('Layouts/master', $data);

    }
    public function quote_requests()
    {

        $user_id = $this->session->userdata('user_id');

        if(!$user_id)
        {
            $this->session->set_userdata('referer_url',  base_url('quote_request'));
            redirect( base_url('user/login'), 'refresh');
        }

        $data = array();
        $data['title'] = 'Custom Orders Management ';
        $data['content'] = 'Pages/quote_requests';
        $data['quotes'] = $this->quoterequest_model->get_quotes();
        $this->load->view('Layouts/master', $data);

    }

    public function quotes()
    {

        $user_id = $this->session->userdata('user_id');

        if(!$user_id)
        {
            $this->session->set_userdata('referer_url',  base_url('quotes'));
            redirect( base_url('user/login'), 'refresh');
        }

        $data = array();
        $data['title'] = 'Custom Orders Management ';
        $data['content'] = 'Pages/quotes';
        $data['quotes'] = $this->quote_model->get_quotes();
        $this->load->view('Layouts/master', $data);

    }

    public function orders()
    {

        $user_id = $this->session->userdata('user_id');

        if(!$user_id)
        {
            $this->session->set_userdata('referer_url',  base_url('orders'));
            redirect( base_url('user/login'), 'refresh');
        }

        $data = array();
        $data['title'] = 'Custom Orders Management ';
        $data['message'] = 'Kokoda Custom Orders';
        $data['content'] = 'Pages/orders';
        $data['orders'] = $this->order_model->get_orders();
        $this->load->view('Layouts/master', $data);

    }
}

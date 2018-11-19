<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');

        $this->load->model("order_model");
    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $order_id = $this->input->get('order_id');
        if(!$user_id)
        {
            $this->session->set_userdata('referer_url',  base_url('order') . '?order_id=' . $order_id );
            redirect( base_url('user/login'), 'refresh');
        }

        $data = array();

        $order = $this->order_model->get_order($order_id);
        if($order)
        {
            $data['order'] = $order;
        }
        else
        {
            redirect( base_url(''), 'refresh');
        }

        $data['content'] = 'Pages/order_detail';

        $this->load->view('Layouts/master', $data);

    }


    public function edit()
    {
        $user_id = $this->session->userdata('user_id');

        $order_id = $this->input->get('order_id');

        if(!$user_id)
        {
            $this->session->set_userdata('referer_url',  base_url('order/edit') . '?order_id=' . $order_id );
            redirect( base_url('user/login'), 'refresh');
        }


        $data = array();


        $data['content'] = 'Pages/order_edit';

        $order = $this->order_model->get_order($order_id);
        if($order)
        {
            $data['order'] = $order;
        }
        else
        {
            redirect( base_url(''), 'refresh');
        }

        $this->load->view('Layouts/master', $data);

    }

    public function update()
    {
        $order_id = $this->input->post('order_id');


        $data=array(
            'status'=>$this->input->post('status'),
            'customer_first_name'=>$this->input->post('customer_first_name'),
            'customer_last_name'=>$this->input->post('customer_last_name'),
            'customer_city'=>$this->input->post('customer_city'),
            'customer_address'=>$this->input->post('customer_address'),
            'customer_postcode'=>$this->input->post('customer_postcode'),
            'customer_state'=>strtolower($this->input->post('customer_state')),
            'customer_email'=>$this->input->post('customer_email'),
            'customer_phone'=>$this->input->post('customer_phone'),
            'product_name'=>$this->input->post('product_name')
        );

        if($this->input->post('updateOrder'))
        {
            $result = $this->order_model->update_order($data,$order_id);
            if(!$result)
            {
                $this->session->set_flashdata('error_msg', 'Update failed, please try again or contact to our Admin at Kokoda Caravans.' );
            }
            else
            {
                $this->session->set_flashdata('success_msg', 'Order update successfully !!!' );

            }
        }


        redirect( base_url('order/edit') .'?order_id='. $order_id, 'refresh');

    }
}
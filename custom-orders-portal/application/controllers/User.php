<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct(){

        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('user_model','user_model');


    }

    public function index()
    {
        $this->load->view("User/register");
    }

    public function register_user(){

        $user=array(
            'user_name'=>$this->input->post('user_name'),
            'user_email'=>$this->input->post('user_email'),
            'user_password'=>md5($this->input->post('user_password')),
            'user_age'=>$this->input->post('user_age'),
            'user_mobile'=>$this->input->post('user_mobile')
        );


        $email_check=$this->user_model->email_check($user['user_email']);

        if($email_check)
        {
            $this->user_model->register_user($user);
            $this->session->set_flashdata('success_msg', 'Registered successfully.Now login to your account.');
            redirect(base_url('user/login'),'refresh');

        }
        else
        {

            $this->session->set_flashdata('error_msg', 'Error occured,Try again.');
            redirect(base_url('user/register_user'),'refresh');

        }

    }

    public function login()
    {
        $user_id = $this->session->userdata('user_id');

        if($user_id)
        {
            redirect( base_url(), 'refresh');
        }

        $this->load->view("User/login");

    }

    public function login_user()
    {

        $user_login=array(
            'user_email'=>$this->input->post('user_email'),
            'user_password'=>md5($this->input->post('user_password'))
        );

        $login_result = $this->user_model->login_user($user_login);
        if($login_result)
        {
            $this->session->set_userdata('user_id',$user_profile[0]['user_id']);
            $this->session->set_userdata('user_email',$user_profile[0]['user_email']);
            $this->session->set_userdata('user_name',$user_profile[0]['user_name']);
            $this->session->set_userdata('user_role',$user_profile[0]['user_role']);


            $referer_url = $this->session->userdata('referer_url');
            if(!$referer_url)
            {

                redirect(base_url(), 'refresh');

            }
            else
            {
                $this->session->unset_userdata('referer_url');
                redirect($referer_url, 'refresh');
            }

            //$data['user_profile']  = $user_profile[0];
            //$this->load->view('User/user_profile',$data);
        }
        else
        {
            $this->session->set_flashdata('error_msg', 'Email or password is incorrect, please try again.' );
            redirect(base_url('user/login'), 'refresh');
        }
    }

    public function user_profile()
    {
        $user_id= $this->session->userdata('user_id');

        if(!$user_id)
        {
            redirect( base_url('user/login'), 'refresh');
        }
        $data['content'] = 'Pages/user_profile';
        $this->load->view('Layouts/master', $data);


    }
    public function user_logout()
    {
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('user_email');
        $this->session->unset_userdata('user_name');
        $this->session->unset_userdata('user_role');


        redirect(base_url('user/login'), 'refresh');
    }

}

?>
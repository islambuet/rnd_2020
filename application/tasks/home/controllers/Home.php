<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Root_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->lang->load('home/login');
    }
    public function index()
    {
        $this->login();
    }
    public function login()
    {
        $user=User_helper::get_user();
        if($user)
        {
            $this->logged_page();
        }
        else
        {
            $this->session->set_userdata("user_id", 2);
            $this->logged_page(true,array('system_message'=>$this->lang->line('MSG_LOGIN_SUCCESS')));

        }
        //$this->logged_page();
        //$ajax['status']=false;
        //$ajax['system_message']="UserName and Password wrong\nTry again.";
        //$ajax['system_message_type']="info";
        //$ajax['system_message_duration']=10000;
        //$this->json_return($ajax);

    }
    public function logout()
    {

        $this->session->set_userdata('user_id','');
        $this->login_page(array('system_message'=>$this->lang->line('MSG_LOGOUT_SUCCESS')));
    }
}

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* load the MX_Router class */
//require APPPATH . "third_party/MX/Controller.php";

class Root_Controller extends CI_Controller
{
    public $EXTERNAL_CONTROLLERS = array('home');
    public $OFFLINE_CONTROLLERS = array('home','sys_site_offline');
	function __construct()
	{
		parent::__construct();
        if ($this->input->is_ajax_request())
        {
            $user=User_helper::get_user();
            if(!$user)
            {
                if(!in_array(strtolower($this->router->class),$this->EXTERNAL_CONTROLLERS))
                {
                    $this->login_page(array('system_message'=>$this->lang->line("MSG_SESSION_TIME_OUT")));
                }
            }
            else
            {

            }

        }
        else
        {
            echo $this->load->view('theme','',true);
            die();
        }
	}
    public function json_return($array)
    {
        header('Content-type: application/json');
        echo json_encode($array);
        exit();
    }
    public function set_message($message,&$data)
    {
        if(isset($message['system_message']))
        {
            $data['system_message']=$message['system_message'];
            if(isset($message['system_message_type']))
            {
                $data['system_message_type']=$message['system_message_type'];
            }
            if(isset($message['system_message_duration']))
            {
                $data['system_message_duration']=$message['system_message_duration'];
            }
        }
    }
    public function login_page($message=array())
    {
        $ajax['status']=true;
        $ajax['system_content'][]=array("id"=>"#system_main_container","html"=>$this->load->view("home/login",'',true));
        $this->set_message($message,$ajax);
        $ajax['system_page_url']=site_url();
        $this->json_return($ajax);
    }
    public function logged_page($dashboard=true,$message=array())
    {
        $ajax['status']=true;
        $ajax['system_content'][]=array("id"=>"#system_main_container","html"=>$this->load->view("home/logged",'',true));
        if($dashboard)
        {
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("home/dashboard",'',true));
        }
        $this->set_message($message,$ajax);
        $ajax['system_page_url']=site_url();
        $this->json_return($ajax);
    }

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */

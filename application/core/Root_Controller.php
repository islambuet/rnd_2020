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
                if($this->is_site_offline())
                {
                    if(!(in_array($user->user_group,array(1))))
                    {
                        if(!((strtolower($this->router->class)=='home')&&(strtolower($this->router->method)=='logout')))//not logout-- logout allowed
                        {
                            $this->offline_page();
                        }
                    }
                }
                if($user->username_password_same)
                {
                    if(
                        !(
                            ((strtolower($this->router->class)=='home')&&(strtolower($this->router->method)=='logout'))//logout allowed
                            ||((strtolower($this->router->class)=='user')&&(strtolower($this->router->method)=='save_edit_password'))//save password allowd
                        )
                    )
                    {
                        //load the user/edit_password task
                        $this->lang->load('user/user');
                        $this->controller_url='user';
                        $ajax['status']=true;
                        $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view($this->controller_url."/edit_password",'',true));
                        $ajax['system_page_url']=site_url($this->controller_url.'/edit_password');
                        $this->json_return($ajax);//go
                    }
                }

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
    public function is_site_offline()
    {
        $info=Query_helper::get_info(TABLE_SYSTEM_CONFIGURATION,array('config_value'),array('purpose ="' .SYSTEM_CONFIGURATION_RND_SITE_OFFLINE.'"','status ="'.SYSTEM_STATUS_ACTIVE.'"'),1);
        if($info)
        {
            if($info['config_value']==1)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
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
    public function dashboard_page($message=array())
    {
        $user=User_helper::get_user();

        $ajax['status']=true;
        $ajax['system_content'][]=array("id"=>"#system_main_container","html"=>$this->load->view("home/logged",'',true));

        $site_offline=$this->is_site_offline();

        if($site_offline || $user->username_password_same)
        {
            if($site_offline)
            {
                $ajax['system_page_url']=site_url();
                $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("home/offline",'',true));

            }
            else if($user->username_password_same)
            {
                $this->lang->load('user/user');
                $this->controller_url='user';
                $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view($this->controller_url."/edit_password",'',true));
                $ajax['system_page_url']=site_url($this->controller_url.'/edit_password');
            }

        }
        else
        {
            $ajax['system_page_url']=site_url();
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("home/dashboard",'',true));
        }
        $this->set_message($message,$ajax);
        $this->json_return($ajax);
    }

    public function offline_page($message=array())
    {
        $ajax['status']=true;
        $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("home/offline",'',true));
        $this->set_message($message,$ajax);
        $ajax['system_page_url']=site_url();
        $this->json_return($ajax);
    }

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Root_controller
{
    public $message;
    public $controller_url;
    public function __construct()
    {
        parent::__construct();
        $this->message = array();
        $this->controller_url = strtolower(get_class($this));
        $this->lang->load('user');

    }
    public function profile_picture()//edit
    {
        $user=User_helper::get_user();
        $user_id=$user->user_id;

        $data['user_info']=Query_helper::get_info(TABLE_RND_SETUP_USER_INFO,array('image_location','name'),array('user_id ='.$user_id,'revision =1'),1);
        $ajax['status']=true;
        $this->set_message($this->message,$ajax);
        $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view($this->controller_url."/profile_picture",$data,true));
        $ajax['system_page_url']=site_url($this->controller_url.'/profile_picture');
        $this->json_return($ajax);

    }
    public function edit_password()//edit
    {
        $user=User_helper::get_user();
        $user_id=$user->user_id;

        $data['user_info']=Query_helper::get_info(TABLE_RND_SETUP_USER_INFO,array('image_location','name'),array('user_id ='.$user_id,'revision =1'),1);
        $ajax['status']=true;
        $this->set_message($this->message,$ajax);
        $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view($this->controller_url."/edit_password",$data,true));
        $ajax['system_page_url']=site_url($this->controller_url.'/edit_password');
        $this->json_return($ajax);

    }
    public function save_edit_password()
    {
        $user = User_helper::get_user();
        if(!$this->check_validation_edit_password())
        {
            $ajax['status']=false;
            $this->message['system_message_type']='error';
            $this->message['system_message_duration']='30000';
            $this->set_message($this->message,$ajax);
            $this->json_return($ajax);
        }
        else
        {
            $this->db->trans_start();  //DB Transaction Handle START
            $data['password']=md5($this->input->post('new_password'));
            $data['user_updated'] = $user->user_id;
            $data['date_updated'] = time();
            Query_helper::update(TABLE_RND_SETUP_USER,$data,array("id = ".$user->user_id));

            $this->db->trans_complete();   //DB Transaction Handle END
            if ($this->db->trans_status() === TRUE)
            {
                $this->message['system_message']=$this->lang->line("MSG_SUCCESS_SAVED_PASSWORD");
                $this->edit_password();
            }
            else
            {
                $ajax['status']=false;
                $this->message['system_message']=$this->lang->line("MSG_FAIL_SAVED_PASSWORD");
                $this->message['system_message_type']='error';
                $this->message['system_message_duration']='30000';
                $this->set_message($this->message,$ajax);
                $this->json_return($ajax);
            }
        }
    }
    private function check_validation_edit_password()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('password',$this->lang->line('LABEL_CURRENT_PASSWORD'),'required');
        $this->form_validation->set_rules('new_password',$this->lang->line('LABEL_NEW_PASSWORD'),'required');
        if($this->form_validation->run() == FALSE)
        {
            $this->message['system_message']=validation_errors();
            return false;
        }

        $user = User_helper::get_user();
        $info=Query_helper::get_info(TABLE_RND_SETUP_USER,array('id'),array('id ='.$user->user_id,'password ="'.md5($this->input->post('password')).'"'),1);

        if(!$info)
        {
            $this->message['system_message']="Old Password did not Match";
            return false;
        }

        return true;
    }
}

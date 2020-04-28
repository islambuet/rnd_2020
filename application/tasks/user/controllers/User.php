<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Root_controller
{
    public $message;
    public $controller_name;
    public function __construct()
    {
        parent::__construct();
        $this->message = array();
        $this->controller_name = strtolower(get_class($this));
        $this->lang->load($this->controller_name.'/'.$this->controller_name);
        $this->lang->load('upload');

    }
    public function profile_picture()//edit
    {
        $user=User_helper::get_user();
        $data['user_info']=(array)$user;

        $ajax['status']=true;
        $this->set_message($this->message,$ajax);
        $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view($this->controller_name."/profile_picture",$data,true));
        $ajax['system_page_url']=site_url($this->controller_name.'/profile_picture');
        $this->json_return($ajax);

    }
    public function save_profile_picture()
    {
        $user = User_helper::get_user();
        $user_id=$user->id;
        $uploaded_image = Upload_helper::upload_file("images/profiles/".$user_id);

        if(array_key_exists('image_profile',$uploaded_image))
        {
            if($uploaded_image['image_profile']['status'])
            {
                $data_current['image_name']=$user->image_name;
                $data_current['image_location']=$user->image_location;


                $data_new['image_name']=$uploaded_image['image_profile']['info']['file_name'];
                $data_new['image_location']="images/profiles/".$user_id.'/'.$uploaded_image['image_profile']['info']['file_name'];
                Query_helper::update(TABLE_RND_SETUP_USER,$data_new,array("id = ".$user->id),false);
                System_helper::history_user($user->id,$data_current,$data_new);

                $this->db->trans_complete();   //DB Transaction Handle END
                if ($this->db->trans_status() === TRUE)
                {
                    $user->image_location=$data_new['image_location'];
                    $this->dashboard_page(array('system_message'=>$this->lang->line("MSG_SAVE_DONE_PROFILE_PICTURE")));
                }
                else
                {
                    $ajax['status']=false;
                    $this->set_message(array('system_message'=>$this->lang->line("MSG_SAVE_FAIL_PROFILE_PICTURE"),'system_message_type'=>'error'),$ajax);
                    $this->json_return($ajax);
                }
            }
            else
            {
                $ajax['status']=false;
                $this->set_message(array('system_message'=>strip_tags($uploaded_image['image_profile']['message']),'system_message_type'=>'error','system_message_duration'=>20000),$ajax);
                $this->json_return($ajax);
            }
        }
        else
        {
            $ajax['status']=false;
            $this->set_message(array('system_message'=>$this->lang->line("MSG_NO_FILE_UPLOADED"),'system_message_type'=>'error'),$ajax);
            $this->json_return($ajax);
        }


    }
    public function edit_password()//edit
    {
        $ajax['status']=true;
        $this->set_message($this->message,$ajax);
        $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view($this->controller_name."/edit_password",'',true));
        $ajax['system_page_url']=site_url($this->controller_name.'/edit_password');
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

            $data_current['password']=$user->password;
            $data_new['password']=md5($this->input->post('new_password'));

            Query_helper::update(TABLE_RND_SETUP_USER,$data_new,array("id = ".$user->id),false);
            System_helper::history_user($user->id,$data_current,$data_new);

            Query_helper::update(TABLE_RND_SETUP_USER,$data_new,array("id = ".$user->id),false);

            $this->db->trans_complete();   //DB Transaction Handle END
            if ($this->db->trans_status() === TRUE)
            {
                $user->username_password_same=false;
                $this->dashboard_page(array('system_message'=>$this->lang->line("MSG_SAVE_DONE_PASSWORD")));
            }
            else
            {
                $this->action_error($this->lang->line("MSG_SAVE_FAIL_PASSWORD"));
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
        if($user->password!= md5($this->input->post('password')))
        {
            $this->message['system_message']=$this->lang->line('MSG_OLD_PASSWORD_WRONG');
            return false;
        }
        if($this->input->post('password')==$this->input->post('new_password'))
        {
            $this->message['system_message']=$this->lang->line('MSG_OLD_NEW_PASSWORD_SAME');
            return false;
        }
        if($user->user_name==$this->input->post('new_password'))
        {
            $this->message['system_message']=$this->lang->line('MSG_USERNAME_PASSWORD_SAME');
            return false;
        }

        return true;
    }
}

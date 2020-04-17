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
        $this->lang->load('user/user');
        $this->lang->load('upload');

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
    public function save_profile_picture()
    {
        $user = User_helper::get_user();
        $user_id=$user->user_id;
        $time=time();
        $uploaded_image = Upload_helper::upload_file("images/profiles/".$user_id);

        if(array_key_exists('image_profile',$uploaded_image))
        {
            if($uploaded_image['image_profile']['status'])
            {

                $data_user_info=Query_helper::get_info(TABLE_RND_SETUP_USER_INFO,array('*'),array('user_id ='.$user_id,'revision =1'),1);
                unset($data_user_info['id']);
                $data_user_info['user_created'] = $user->user_id;
                $data_user_info['date_created'] = $time;
                $data_user_info['revision'] = 1;
                $data_user_info['image_name']=$uploaded_image['image_profile']['info']['file_name'];
                $data_user_info['image_location']="images/profiles/".$user_id.'/'.$uploaded_image['image_profile']['info']['file_name'];

                $revision_history_data=array();
                $revision_history_data['date_updated']=$time;
                $revision_history_data['user_updated']=$user->user_id;
                Query_helper::update(TABLE_RND_SETUP_USER_INFO,$revision_history_data,array('revision=1','user_id='.$user_id));

                $this->db->trans_start();  //DB Transaction Handle START
                $this->db->where('user_id',$user_id);
                $this->db->set('revision', 'revision+1', FALSE);
                $this->db->update(TABLE_RND_SETUP_USER_INFO);


                Query_helper::add(TABLE_RND_SETUP_USER_INFO,$data_user_info);
                $this->db->trans_complete();   //DB Transaction Handle END
                if ($this->db->trans_status() === TRUE)
                {
                    $this->message['system_message']=$this->lang->line("MSG_SAVE_DONE_PROFILE_PICTURE");
                    $this->profile_picture();
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
                $this->message['system_message']=$this->lang->line("MSG_SAVE_DONE_PASSWORD");
                $this->edit_password();
            }
            else
            {
                $ajax['status']=false;
                $this->set_message(array('system_message'=>$this->lang->line("MSG_SAVE_FAIL_PASSWORD"),'system_message_type'=>'error'),$ajax);
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
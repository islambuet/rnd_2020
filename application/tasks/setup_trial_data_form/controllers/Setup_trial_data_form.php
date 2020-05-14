<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup_trial_data_form extends Root_Controller
{
    public $controller_name;
    public $permissions;
    public $message;
    public function __construct()
    {
        parent::__construct();
        $this->controller_name = strtolower(get_class($this));
        $this->permissions = User_helper::get_permission(get_class($this));
        $this->message = array();

        $this->load->config('system_input_type');
        $this->lang->load('setup_crop/setup_crop');
        $this->lang->load($this->controller_name.'/'.$this->controller_name);
    }
    public function index()
    {
        $this->system_list();
    }
    /*get_jqx_items
     *
     * preference =default preference
     * jqx_column = as column
     * data_attributes = other optional jqx data attributes
     *      format
     * column_attributes = other optional jqx column attributes
     *      width
     *      filtertype
     *      cellsAlign
     *      cellsrenderer
     *      cellsformat
    */

    public function get_jqx_items($method)
    {
        $data=array();
        if($method=='system_list')
        {
            $data['id']= array('text'=>$this->lang->line('LABEL_ID'),'type'=>'number','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"50"','cellsAlign'=>'"right"'));
            $data['name']= array('text'=>$this->lang->line('LABEL_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"200"'));
            $data['remarks']= array('text'=>$this->lang->line('LABEL_REMARKS'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"350"'));
            $data['ordering']= array('text'=>$this->lang->line('LABEL_ORDERING'),'type'=>'number','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"70"','filtertype'=>'"number"','cellsAlign'=>'"right"'));
            $data['status']= array('text'=>$this->lang->line('LABEL_STATUS'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"70"','filtertype'=>'"list"'));
        }
        else if($method=='system_list_input')
        {
            $data['id']= array('text'=>$this->lang->line('LABEL_ID'),'type'=>'number','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"50"','cellsAlign'=>'"right"'));
            $data['name']= array('text'=>$this->lang->line('LABEL_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"200"'));
            $data['type']= array('text'=>$this->lang->line('LABEL_INPUT_TYPE'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"120"','filtertype'=>'"list"'));
            $data['options']= array('text'=>$this->lang->line('LABEL_OPTIONS'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"300"'));
            $data['default']= array('text'=>$this->lang->line('LABEL_DEFAULT'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"100"'));
            $data['mandatory']= array('text'=>$this->lang->line('LABEL_MANDATORY'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"70"','filtertype'=>'"list"'));
            $data['class']= array('text'=>$this->lang->line('LABEL_CLASS'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"150"'));
            $data['average_group_name']= array('text'=>$this->lang->line('LABEL_AVERAGE_GROUP_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"250"','filtertype'=>'"list"'));
            $data['summary_report_column']= array('text'=>$this->lang->line('LABEL_SUMMARY_REPORT_COLUMN'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"50"','filtertype'=>'"list"'));
            $data['ordering']= array('text'=>$this->lang->line('LABEL_ORDERING'),'type'=>'number','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"70"','filtertype'=>'"number"','cellsAlign'=>'"right"'));
            $data['status']= array('text'=>$this->lang->line('LABEL_STATUS'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"70"','filtertype'=>'"list"'));
        }
        return $data;
    }
    public function system_preference($method='system_list')
    {
        //if necessary can rewrite it
        System_helper::system_preference($method);
    }
    public function system_save_preference()
    {
        //if necessary can rewrite it
        System_helper::save_preference();
    }
    public function system_list()
    {
        if(isset($this->permissions['action0']) && ($this->permissions['action0']==1))
        {
            $user = User_helper::get_user();
            $method='system_list';
            $data['system_jqx_items']= System_helper::get_preference($user->id, $this->controller_name, $method, $this->get_jqx_items($method));
            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#system_content','html'=>$this->load->view($this->controller_name.'/list',$data,true));
            $this->set_message($this->message,$ajax);
            $ajax['system_page_url']=site_url($this->controller_name.'/system_list');
            $this->json_return($ajax);
        }
        else
        {
            $this->access_denied();
        }
    }
    public function system_get_items_list()
    {
        $items=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_DATA_FORM,array('id','name','remarks','status','ordering'),array('status !="'.SYSTEM_STATUS_DELETE.'"'),0,0,array('ordering ASC','id ASC'));
        $this->json_return($items);
    }
    public function system_add()
    {
        if(isset($this->permissions['action1']) && ($this->permissions['action1']==1))
        {
            $data['item']=array();
            $table_fields = $this->db->field_data(TABLE_RND_SETUP_TRIAL_DATA_FORM);

            foreach ($table_fields as $field)
            {
                $data['item'][$field->name]=$field->default;
            }
            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#system_content','html'=>$this->load->view($this->controller_name.'/add_edit',$data,true));
            $this->set_message($this->message,$ajax);
            $ajax['system_page_url']=site_url($this->controller_name.'/system_add');
            $this->json_return($ajax);
        }
        else
        {
            $this->access_denied();
        }
    }
    public function system_edit($id=0)
    {
        if(isset($this->permissions['action2']) && ($this->permissions['action2']==1))
        {
            if($id>0)
            {
                $item_id=$id;
            }
            else
            {
                $item_id=$this->input->post('id');
            }
            $data['item']=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_DATA_FORM,'*',array('id ='.$item_id),1);
            if(!$data['item'])
            {
                $this->action_error($this->lang->line("MSG_INVALID_ITEM"));
            }
            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#system_content','html'=>$this->load->view($this->controller_name.'/add_edit',$data,true));
            $this->set_message($this->message,$ajax);
            $ajax['system_page_url']=site_url($this->controller_name.'/system_edit/'.$item_id);
            $this->json_return($ajax);
        }
        else
        {
            $this->access_denied();
        }
    }
    public function system_save_add_edit()
    {
        $user=User_helper::get_user();
        $time = time();
        $id=$this->input->post('id');
        $system_user_token = $this->input->post("system_user_token");
        $item = $this->input->post('item');

        if($id>0)
        {
            if(!(isset($this->permissions['action2']) && ($this->permissions['action2']==1)))
            {
                $this->access_denied();
            }
        }
        else
        {
            if(!(isset($this->permissions['action1']) && ($this->permissions['action1']==1)))
            {
                $this->access_denied();
            }
        }
        if(!$this->check_validation_add_edit())
        {
            $this->validation_error($this->message['system_message']);
        }
        $system_user_token_info = Token_helper::get_token($system_user_token);
        if($system_user_token_info['status'])
        {
            $this->message['system_message']=$this->lang->line('MSG_SAVE_ALREADY');
            $this->system_list();
        }

        {
            $data=$item;
            $this->db->trans_start(); //DB Transaction Handle START
            if($id>0)
            {
                $data['user_updated']=$user->id;
                $data['date_updated']=$time;
                Query_helper::update(TABLE_RND_SETUP_TRIAL_DATA_FORM,$data,array('id='.$id));
            }
            else
            {
                $data['user_created']=$user->id;
                $data['date_created']=$time;
                Query_helper::add(TABLE_RND_SETUP_TRIAL_DATA_FORM,$data);
            }
            Token_helper::update_token($system_user_token_info['id'], $system_user_token);

            $this->db->trans_complete(); //DB Transaction Handle END
            if ($this->db->trans_status()===true)
            {
                $save_and_new=$this->input->post('system_save_new_status');
                $this->message['system_message']=$this->lang->line('MSG_SAVE_DONE');
                if($save_and_new==1)
                {
                    $this->system_add();
                }
                else
                {
                    $this->system_list();
                }
            }
            else
            {
                $this->action_error($this->lang->line("MSG_SAVE_FAIL"));
            }
        }
    }
    private function check_validation_add_edit()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('item[name]',$this->lang->line('LABEL_NAME'),'required');
        $this->form_validation->set_rules('item[status]',$this->lang->line('LABEL_STATUS'),'required');
        if($this->form_validation->run()==false)
        {
            $this->message['system_message']=validation_errors();
            return false;
        }
        return true;
    }
    public function system_list_input($form_id=0,$crop_id=0)
    {
        if(isset($this->permissions['action0']) && ($this->permissions['action0']==1))
        {
            $user = User_helper::get_user();
            $method='system_list_input';
            if($form_id>0)
            {
                $item_id=$form_id;
            }
            else
            {
                $item_id=$this->input->post('id');
            }
            if(!($crop_id>0))
            {
                $crop_id=$this->input->post('crop_id');
            }
            if(!$crop_id)
            {
                $crop_id=1;
            }
            $data['item']=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_DATA_FORM,'*',array('id ='.$item_id),1);
            if(!$data['item'])
            {
                $this->action_error($this->lang->line("MSG_INVALID_ITEM"));
            }

            $data['crop_id']=$crop_id;
            $data['crops']=Query_helper::get_info(TABLE_RND_SETUP_CROP,array('id value','name text'),array('status !="'.SYSTEM_STATUS_DELETE.'"'));
            $data['system_jqx_items']= System_helper::get_preference($user->id, $this->controller_name, $method, $this->get_jqx_items($method));
            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#system_content','html'=>$this->load->view($this->controller_name.'/list_inputs',$data,true));
            $this->set_message($this->message,$ajax);
            $ajax['system_page_url']=site_url($this->controller_name.'/system_list_input/'.$item_id.'/'.$crop_id);
            $this->json_return($ajax);
        }
        else
        {
            $this->access_denied();
        }
    }
    public function system_get_items_list_input($form_id,$crop_id)
    {
        $items=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_DATA_FORM_INPUT,'',array('status !="'.SYSTEM_STATUS_DELETE.'"','form_id ='.$form_id,'crop_id ='.$crop_id),0,0,array('ordering ASC','id ASC'));
        $this->json_return($items);
    }
    public function system_add_input($form_id,$crop_id)
    {
        if(isset($this->permissions['action1']) && ($this->permissions['action1']==1))
        {
            $data['item']=array();
            $table_fields = $this->db->field_data(TABLE_RND_SETUP_TRIAL_DATA_FORM_INPUT);

            foreach ($table_fields as $field)
            {
                $data['item'][$field->name]=$field->default;
            }
            $data['form']=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_DATA_FORM,'*',array('id ='.$form_id),1);
            $data['crop']=Query_helper::get_info(TABLE_RND_SETUP_CROP,'*',array('id ='.$crop_id),1);
            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#system_content','html'=>$this->load->view($this->controller_name.'/add_edit_input',$data,true));
            $this->set_message($this->message,$ajax);
            $ajax['system_page_url']=site_url($this->controller_name.'/system_add_input/'.$form_id.'/'.$crop_id);
            $this->json_return($ajax);
        }
        else
        {
            $this->access_denied();
        }
    }
    public function system_edit_input($form_id,$crop_id,$id=0)
    {
        if(isset($this->permissions['action2']) && ($this->permissions['action2']==1))
        {
            if($id>0)
            {
                $item_id=$id;
            }
            else
            {
                $item_id=$this->input->post('id');
            }
            $data['item']=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_DATA_FORM_INPUT,'*',array('id ='.$item_id,'form_id ='.$form_id,'crop_id ='.$crop_id),1);
            if(!$data['item'])
            {
                $this->action_error($this->lang->line("MSG_INVALID_ITEM_INPUT"));
            }
            $data['item']['options']=str_replace(",",PHP_EOL,$data['item']['options']);
            $data['form']=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_DATA_FORM,'*',array('id ='.$form_id),1);
            $data['crop']=Query_helper::get_info(TABLE_RND_SETUP_CROP,'*',array('id ='.$crop_id),1);
            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#system_content','html'=>$this->load->view($this->controller_name.'/add_edit_input',$data,true));
            $this->set_message($this->message,$ajax);
            $ajax['system_page_url']=site_url($this->controller_name.'/system_edit_input/'.$form_id.'/'.$crop_id.'/'.$item_id);
            $this->json_return($ajax);
        }
        else
        {
            $this->access_denied();
        }
    }
    public function system_duplicate_input($form_id,$crop_id,$id=0)
    {
        if(isset($this->permissions['action2']) && ($this->permissions['action2']==1))
        {
            if($id>0)
            {
                $item_id=$id;
            }
            else
            {
                $item_id=$this->input->post('id');
            }
            $data['item']=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_DATA_FORM_INPUT,'*',array('id ='.$item_id,'form_id ='.$form_id,'crop_id ='.$crop_id),1);
            if(!$data['item'])
            {
                $this->action_error($this->lang->line("MSG_INVALID_ITEM_INPUT"));
            }
            $data['item']['id']=0;
            $data['item']['name']='';
            $data['item']['options']=str_replace(",",PHP_EOL,$data['item']['options']);


            $data['form']=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_DATA_FORM,'*',array('id ='.$form_id),1);
            $data['crop']=Query_helper::get_info(TABLE_RND_SETUP_CROP,'*',array('id ='.$crop_id),1);
            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#system_content','html'=>$this->load->view($this->controller_name.'/add_edit_input',$data,true));
            $this->set_message($this->message,$ajax);
            $ajax['system_page_url']=site_url($this->controller_name.'/system_add_input/'.$form_id.'/'.$crop_id);
            $this->json_return($ajax);
        }
        else
        {
            $this->access_denied();
        }
    }
    public function system_save_add_edit_input()
    {
        $user=User_helper::get_user();
        $time = time();
        $id=$this->input->post('id');
        $crop_id=$this->input->post('crop_id');
        $form_id=$this->input->post('form_id');
        $system_user_token = $this->input->post("system_user_token");
        $item = $this->input->post('item');
        $item['options']=str_replace(PHP_EOL,",",trim($item['options']));


        if($id>0)
        {
            if(!(isset($this->permissions['action2']) && ($this->permissions['action2']==1)))
            {
                $this->access_denied();
            }
            $result=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_DATA_FORM_INPUT,'*',array('id ='.$id,'form_id ='.$form_id,'crop_id ='.$crop_id),1);
            if(!$result)
            {
                $this->action_error($this->lang->line("MSG_INVALID_ITEM_INPUT"));
            }

        }
        else
        {
            if(!(isset($this->permissions['action1']) && ($this->permissions['action1']==1)))
            {
                $this->access_denied();
            }
        }
        if(!$this->check_validation_add_edit_input())
        {
            $this->validation_error($this->message['system_message']);
        }
        $system_user_token_info = Token_helper::get_token($system_user_token);
        if($system_user_token_info['status'])
        {
            $this->message['system_message']=$this->lang->line('MSG_SAVE_ALREADY');
            $this->system_list();
        }

        {
            $data=$item;
            $this->db->trans_start(); //DB Transaction Handle START
            if($id>0)
            {
                $data['user_updated']=$user->id;
                $data['date_updated']=$time;
                Query_helper::update(TABLE_RND_SETUP_TRIAL_DATA_FORM_INPUT,$data,array('id='.$id));
            }
            else
            {
                $data['crop_id']=$crop_id;
                $data['form_id']=$form_id;
                $data['user_created']=$user->id;
                $data['date_created']=$time;
                Query_helper::add(TABLE_RND_SETUP_TRIAL_DATA_FORM_INPUT,$data);
            }
            Token_helper::update_token($system_user_token_info['id'], $system_user_token);

            $this->db->trans_complete(); //DB Transaction Handle END
            if ($this->db->trans_status()===true)
            {
                $save_and_new=$this->input->post('system_save_new_status');
                $this->message['system_message']=$this->lang->line('MSG_SAVE_DONE');
                if($save_and_new==1)
                {
                    $this->system_add_input($form_id,$crop_id);
                }
                else
                {
                    $this->system_list_input($form_id,$crop_id);
                }
            }
            else
            {
                $this->action_error($this->lang->line("MSG_SAVE_FAIL"));
            }
        }
    }
    private function check_validation_add_edit_input()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('item[name]',$this->lang->line('LABEL_NAME'),'required');
        $this->form_validation->set_rules('item[type]',$this->lang->line('LABEL_INPUT_TYPE'),'required');
        $this->form_validation->set_rules('item[status]',$this->lang->line('LABEL_STATUS'),'required');
        if($this->form_validation->run()==false)
        {
            $this->message['system_message']=validation_errors();
            return false;
        }
        return true;
    }

}

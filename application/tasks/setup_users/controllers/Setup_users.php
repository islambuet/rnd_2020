<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup_users extends Root_Controller
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
            $data['employee_id']= array('text'=>$this->lang->line('LABEL_EMPLOYEE_ID'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"80"'));
            $data['user_name']= array('text'=>$this->lang->line('LABEL_USER_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"100"'));
            $data['name']= array('text'=>$this->lang->line('LABEL_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"200"'));
            $data['user_group']= array('text'=>$this->lang->line('LABEL_USER_GROUP'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"100"','filtertype'=>'"list"'));
            $data['designation_name']= array('text'=>$this->lang->line('LABEL_DESIGNATION_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"100"','filtertype'=>'"list"'));
            $data['mobile_no']= array('text'=>$this->lang->line('LABEL_MOBILE_NO'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"110"'));
            $data['email']= array('text'=>$this->lang->line('LABEL_EMAIL'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"200"'));
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
            $data['system_jqx_items']= System_helper::get_preference($user->user_id, $this->controller_name, $method, $this->get_jqx_items($method));
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
        $user = User_helper::get_user();

        $this->db->from(TABLE_RND_SETUP_USER.' user');
        $this->db->select('user.id,user.employee_id,user.user_name,user.status');

        $this->db->join(TABLE_RND_SETUP_USER_INFO.' user_info','user.id = user_info.user_id','INNER');
        $this->db->select('user_info.name,user_info.mobile_no,user_info.email,user_info.ordering');

        $this->db->join(TABLE_SYSTEM_USER_GROUP.' ug','ug.id = user_info.user_group','LEFT');
        $this->db->select('ug.name user_group');

        $this->db->join(TABLE_RND_SETUP_DESIGNATION.' designation','designation.id = user_info.designation','LEFT');
        $this->db->select('designation.name designation_name');


        $this->db->where('user_info.revision',1);
        $this->db->order_by('user_info.ordering','ASC');
        if($user->user_group!=1)
        {
            $this->db->where('user_info.user_group !=',1);
        }
        $this->db->where('user.status !=',SYSTEM_STATUS_DELETE);

        $items=$this->db->get()->result_array();
        $this->json_return($items);
    }
    public function system_edit($id=0)
    {
        if(isset($this->permissions['action2']) && ($this->permissions['action2']==1))
        {
            $user=User_helper::get_user();
            if(($this->input->post('id')))
            {
                $item_id=$this->input->post('id');
            }
            else
            {
                $item_id=$id;
            }
            $data['item']=Query_helper::get_info(TABLE_RND_SETUP_USER,array('id','employee_id','user_name','status'),array('id ='.$item_id),1);
            if(!$data['item'])
            {
                $this->action_error($this->lang->line("MSG_INVALID_ITEM"));
            }
            $data['user_info']=Query_helper::get_info(TABLE_RND_SETUP_USER_INFO,'*',array('user_id ='.$item_id,'revision =1'),1);
            if($user->user_group==1)
            {
                $data['user_groups']=Query_helper::get_info(TABLE_SYSTEM_USER_GROUP,array('id value','name text'),array('status ="'.SYSTEM_STATUS_ACTIVE.'"'));
            }
            else
            {
                $data['user_groups']=Query_helper::get_info(TABLE_SYSTEM_USER_GROUP,array('id value','name text'),array('status ="'.SYSTEM_STATUS_ACTIVE.'"','id !=1'));
            }
            $data['designations']=Query_helper::get_info(TABLE_RND_SETUP_DESIGNATION,array('id value','name text'),array('status ="'.SYSTEM_STATUS_ACTIVE.'"'));
            $data['user_types']=Query_helper::get_info(TABLE_RND_SETUP_USER_TYPE,array('id value','name text'),array('status ="'.SYSTEM_STATUS_ACTIVE.'"'));

            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#system_content','html'=>$this->load->view($this->controller_name.'/edit',$data,true));
            $this->set_message($this->message,$ajax);
            $ajax['system_page_url']=site_url($this->controller_name.'/system_edit/'.$item_id);
            $this->json_return($ajax);
        }
        else
        {
            $this->access_denied();
        }
    }
    public function system_save_add_edit()//only edit--add removed
    {
        $user=User_helper::get_user();
        $time = time();
        $id=$this->input->post('id');

        $system_user_token = $this->input->post("system_user_token");
        $data_user_info=$this->input->post('user_info');

        if(!(isset($this->permissions['action2']) && ($this->permissions['action2']==1)))
        {
            $this->access_denied();
        }

        if(!$this->check_validation_add_edit())
        {
            $this->validation_error($this->message['system_message']);
        }
        $item=Query_helper::get_info(TABLE_RND_SETUP_USER,array('id','employee_id','user_name','status'),array('id ='.$id),1);
        if(!$item)
        {
            $this->action_error($this->lang->line("MSG_INVALID_ITEM"));
        }

        $system_user_token_info = Token_helper::get_token($system_user_token);
        if($system_user_token_info['status'])
        {
            $this->message['system_message']=$this->lang->line('MSG_SAVE_ALREADY');
            $this->system_list();
        }

        {
            if(isset($data_user_info['date_birth']))
            {
                $data_user_info['date_birth']=System_helper::get_time($data_user_info['date_birth']);
                if($data_user_info['date_birth']===0)
                {
                    unset($data_user_info['date_birth']);
                }
            }
            if(isset($data_user_info['date_join']))
            {
                $data_user_info['date_join']=System_helper::get_time($data_user_info['date_join']);
                if($data_user_info['date_join']===0)
                {
                    unset($data_user_info['date_join']);
                }
            }
            $this->db->trans_start(); //DB Transaction Handle START
            $revision_history_data=array();
            $revision_history_data['date_updated']=$time;
            $revision_history_data['user_updated']=$user->user_id;
            Query_helper::update(TABLE_RND_SETUP_USER_INFO,$revision_history_data,array('revision=1','user_id='.$id), false);

            $revision_change_data=array();
            $this->db->set('revision', 'revision+1', FALSE);
            Query_helper::update(TABLE_RND_SETUP_USER_INFO,$revision_change_data,array('user_id='.$id), false);

            $data_user_info['revision'] = 1;
            $data_user_info['user_id']=$id;
            $data_user_info['user_created'] = $user->user_id;
            $data_user_info['date_created'] = $time;
            Query_helper::add(TABLE_RND_SETUP_USER_INFO,$data_user_info,false);


            Token_helper::update_token($system_user_token_info['id'], $system_user_token);

            $this->db->trans_complete(); //DB Transaction Handle END
            if ($this->db->trans_status()===true)
            {
                $this->message['system_message']=$this->lang->line('MSG_SAVE_DONE');
                $this->system_list();
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
        $this->form_validation->set_rules('user_info[name]',$this->lang->line('LABEL_NAME'),'required');
        $this->form_validation->set_rules('user_info[email]',$this->lang->line('LABEL_EMAIL'),'required');
        if($this->form_validation->run()==false)
        {
            $this->message['system_message']=validation_errors();
            return false;
        }
        return true;
    }
}

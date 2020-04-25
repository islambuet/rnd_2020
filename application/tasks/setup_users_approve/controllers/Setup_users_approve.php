<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup_users_approve extends Root_Controller
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
        $this->lang->load('setup_users/setup_users');
        $this->lang->load('setup_users_request/setup_users_request');
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
            $data['id']= array('text'=>$this->lang->line('LABEL_ID'),'type'=>'int','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"50"','cellsAlign'=>'"right"'));
            $data['employee_id']= array('text'=>$this->lang->line('LABEL_EMPLOYEE_ID'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"80"'));
            $data['user_name']= array('text'=>$this->lang->line('LABEL_USER_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"100"'));
            $data['name']= array('text'=>$this->lang->line('LABEL_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"200"'));
            $data['email']= array('text'=>$this->lang->line('LABEL_EMAIL'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"200"'));
            $data['mobile_no']= array('text'=>$this->lang->line('LABEL_MOBILE_NO'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"110"'));
            $data['mobile_no_personal']= array('text'=>$this->lang->line('LABEL_MOBILE_NO_PERSONAL'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"110"'));
            $data['designation_name']= array('text'=>$this->lang->line('LABEL_DESIGNATION_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"100"','filtertype'=>'"list"'));
            $data['user_type_name']= array('text'=>$this->lang->line('LABEL_USER_TYPE'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"100"','filtertype'=>'"list"'));

        }
        else if($method=='system_list_all')
        {
            $data['id']= array('text'=>$this->lang->line('LABEL_ID'),'type'=>'int','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"50"','cellsAlign'=>'"right"'));
            $data['employee_id']= array('text'=>$this->lang->line('LABEL_EMPLOYEE_ID'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"80"'));
            $data['user_name']= array('text'=>$this->lang->line('LABEL_USER_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"100"'));
            $data['name']= array('text'=>$this->lang->line('LABEL_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"200"'));
            $data['email']= array('text'=>$this->lang->line('LABEL_EMAIL'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"200"'));
            $data['mobile_no']= array('text'=>$this->lang->line('LABEL_MOBILE_NO'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"110"'));
            $data['mobile_no_personal']= array('text'=>$this->lang->line('LABEL_MOBILE_NO_PERSONAL'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"110"'));
            $data['designation_name']= array('text'=>$this->lang->line('LABEL_DESIGNATION_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"100"','filtertype'=>'"list"'));
            $data['user_type_name']= array('text'=>$this->lang->line('LABEL_USER_TYPE'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"100"','filtertype'=>'"list"'));
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
        $this->db->from(TABLE_RND_SETUP_USER_REQUEST.' user');
        $this->db->select('user.*');
        $this->db->join(TABLE_RND_SETUP_DESIGNATION.' designation','designation.id = user.designation','LEFT');
        $this->db->select('designation.name designation_name');
        $this->db->join(TABLE_RND_SETUP_USER_TYPE.' ut','ut.id = user.user_type_id','LEFT');
        $this->db->select('ut.name user_type_name');
        $this->db->where('user.status',SYSTEM_STATUS_PENDING);
        $items=$this->db->get()->result_array();
        $this->json_return($items);
    }
    public function system_list_all()
    {
        if(isset($this->permissions['action0']) && ($this->permissions['action0']==1))
        {
            $user = User_helper::get_user();
            $method='system_list_all';
            $data['system_jqx_items']= System_helper::get_preference($user->user_id, $this->controller_name, $method, $this->get_jqx_items($method));
            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#system_content','html'=>$this->load->view($this->controller_name.'/list_all',$data,true));
            $this->set_message($this->message,$ajax);
            $ajax['system_page_url']=site_url($this->controller_name.'/system_list_all');
            $this->json_return($ajax);
        }
        else
        {
            $this->access_denied();
        }
    }
    public function system_get_items_list_all()
    {
        $this->db->from(TABLE_RND_SETUP_USER_REQUEST.' user');
        $this->db->select('user.*');
        $this->db->join(TABLE_RND_SETUP_DESIGNATION.' designation','designation.id = user.designation','LEFT');
        $this->db->select('designation.name designation_name');
        $this->db->join(TABLE_RND_SETUP_USER_TYPE.' ut','ut.id = user.user_type_id','LEFT');
        $this->db->select('ut.name user_type_name');

        $this->db->where('user.status !=',SYSTEM_STATUS_PENDING);
        $this->db->where('user.status !=',SYSTEM_STATUS_DELETE);
        $items=$this->db->get()->result_array();
        $this->json_return($items);
    }
    public function system_edit($id=0)
    {
        $user=User_helper::get_user();
        if(isset($this->permissions['action2']) && ($this->permissions['action2']==1))
        {
            if(($this->input->post('id')))
            {
                $item_id=$this->input->post('id');
            }
            else
            {
                $item_id=$id;
            }
            $data['item']=Query_helper::get_info(TABLE_RND_SETUP_USER_REQUEST,'*',array('id ='.$item_id),1);
            if(!$data['item'])
            {
                $this->action_error($this->lang->line("MSG_INVALID_ITEM"));
            }
            if($data['item']['status']!=SYSTEM_STATUS_PENDING)
            {
                $this->message['system_message']=$this->lang->line('MSG_FORWARDED_ALREADY');
                $this->system_list();
            }
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
    public function system_save_add_edit()//only edit--add removed
    {
        $user=User_helper::get_user();
        $time = time();
        $item_id=$this->input->post('id');

        $system_user_token = $this->input->post("system_user_token");
        $status=$this->input->post('status');
        $item=$this->input->post('item');
        $data_user_info=$this->input->post('user_info');



        if($item_id>0)
        {
            if(!(isset($this->permissions['action2'])&&($this->permissions['action2']==1)))
            {
                $this->access_denied();
            }
            $item_old=Query_helper::get_info(TABLE_RND_SETUP_USER_REQUEST,'*',array('id ='.$item_id),1);
            if(!$item_old)
            {
                $this->action_error($this->lang->line("MSG_INVALID_ITEM"));
            }
            if($item_old['status']!=SYSTEM_STATUS_PENDING)
            {
                $this->message['system_message']=$this->lang->line('MSG_FORWARDED_ALREADY');
                $this->system_list();
            }
        }
        else
        {
            $this->action_error($this->lang->line("MSG_INVALID_ITEM"));
        }
        if(!$this->check_validation_add_edit())
        {
            $this->validation_error($this->message['system_message']);
        }
        if($status==SYSTEM_STATUS_APPROVED)
        {
            $item['user_name']=trim($item['user_name']);
            $item['employee_id']=trim($item['employee_id']);
            $duplicate_username_check=Query_helper::get_info(TABLE_RND_SETUP_USER,array('user_name'),array('user_name ="'.$item['user_name'].'"'),1);
            if($duplicate_username_check)
            {
                $this->action_error($this->lang->line("MSG_USER_NAME_EXISTS"));
            }
            if($item['employee_id'])
            {
                $duplicate_employee_id_check=Query_helper::get_info(TABLE_RND_SETUP_USER,array('employee_id'),array('employee_id ="'.$item['employee_id'].'"'),1);
                if($duplicate_employee_id_check)
                {
                    $this->action_error($this->lang->line("MSG_EMPLOYEE_ID_EXISTS"));
                }
            }
        }

        $system_user_token_info = Token_helper::get_token($system_user_token);
        if($system_user_token_info['status'])
        {
            $this->message['system_message']=$this->lang->line('MSG_SAVE_ALREADY');
            $this->system_list();
        }
        $this->db->trans_start();  //DB Transaction Handle START
        if($status==SYSTEM_STATUS_REJECTED)
        {
            $data=array();
            $data['remarks_reject']=$this->input->post('remarks_reject');
            $data['user_approved'] = $user->user_id;
            $data['date_approved'] = $time;
            $data['status'] = $status;
            Query_helper::update(TABLE_RND_SETUP_USER_REQUEST,$data,array("id = ".$item_id));
        }
        else
        {
            $item['password']=md5($item['user_name']);
            $item['status']=SYSTEM_STATUS_ACTIVE;
            $item['user_created'] = $user->user_id;
            $item['date_created'] = $time;
            $user_id_new=Query_helper::add(TABLE_RND_SETUP_USER,$item);
            if($user_id_new===false)
            {
                $this->db->trans_complete();
                $this->action_error($this->lang->line("MSG_SAVED_FAIL"));
            }
            else
            {
                $data_user_info['user_id']=$user_id_new;
                $data_user_info['user_created'] = $user->user_id;
                $data_user_info['date_created'] = $time;
                $data_user_info['revision'] = 1;
                Query_helper::add(TABLE_RND_SETUP_USER_INFO,$data_user_info,false);
                $data=array();
                $data['user_approved'] = $user->user_id;
                $data['date_approved'] = $time;
                $data['status'] = $status;
                $data['user_id'] = $user_id_new;
                Query_helper::update(TABLE_RND_SETUP_USER_REQUEST,$data,array("id = ".$item_id));
            }
        }
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
    private function check_validation_add_edit()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('status',$this->lang->line('LABEL_APPROVE_REJECT'),'required');
        $status=$this->input->post('status');
        if($status==SYSTEM_STATUS_APPROVED)
        {
            $this->form_validation->set_rules('item[user_name]',$this->lang->line('LABEL_USER_NAME'),'required');
            $this->form_validation->set_rules('user_info[name]',$this->lang->line('LABEL_NAME'),'required');
        }
        elseif($status==SYSTEM_STATUS_REJECTED)
        {
            $this->form_validation->set_rules('remarks_reject',$this->lang->line('LABEL_REMARKS_REJECT'),'required');
        }

        if($this->form_validation->run()==false)
        {
            $this->message['system_message']=validation_errors();
            return false;
        }
        $item=$this->input->post('item');
        if(!preg_match('/^[a-z0-9][a-z0-9_]*[a-z0-9]$/',$item['user_name']))
        {
            $this->message['system_message']=$this->lang->line('MSG_USER_NAME_RULE_ERROR');
            return false;
        }
        return true;
    }
}

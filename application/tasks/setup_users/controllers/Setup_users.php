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
            $data['id']= array('text'=>$this->lang->line('LABEL_ID'),'type'=>'int','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"50"','cellsAlign'=>'"right"'));
            $data['employee_id']= array('text'=>$this->lang->line('LABEL_EMPLOYEE_ID'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"80"'));
            $data['user_name']= array('text'=>$this->lang->line('LABEL_USER_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"100"'));
            $data['name']= array('text'=>$this->lang->line('LABEL_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"200"'));
            $data['user_group']= array('text'=>$this->lang->line('LABEL_USER_GROUP'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"100"','filtertype'=>'"list"'));
            $data['designation_name']= array('text'=>$this->lang->line('LABEL_DESIGNATION_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"100"','filtertype'=>'"list"'));
            $data['mobile_no']= array('text'=>$this->lang->line('LABEL_MOBILE_NO'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"110"'));
            $data['email']= array('text'=>$this->lang->line('LABEL_EMAIL'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"200"'));
            $data['ordering']= array('text'=>$this->lang->line('LABEL_ORDERING'),'type'=>'int','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"70"','filtertype'=>'"number"','cellsAlign'=>'"right"'));
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
        $user = User_helper::get_user();

        $this->db->from(TABLE_RND_SETUP_USER.' user');
        $this->db->select('user.*');


        $this->db->join(TABLE_SYSTEM_USER_GROUP.' ug','ug.id = user.user_group','LEFT');
        $this->db->select('ug.name user_group');

        $this->db->join(TABLE_RND_SETUP_DESIGNATION.' designation','designation.id = user.designation','LEFT');
        $this->db->select('designation.name designation_name');

        $this->db->order_by('user.ordering','ASC');
        if($user->user_group!=1)
        {
            $this->db->where('user.user_group !=',1);
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
            $data['item']=Query_helper::get_info(TABLE_RND_SETUP_USER,'*',array('id ='.$item_id),1);
            if(!$data['item'])
            {
                $this->action_error($this->lang->line("MSG_INVALID_ITEM"));
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
        $item=$this->input->post('item');
        $system_user_token = $this->input->post("system_user_token");
        $item_current=Query_helper::get_info(TABLE_RND_SETUP_USER,'*',array('id ='.$id),1);
        if(!$item_current)
        {
            $this->action_error($this->lang->line("MSG_INVALID_ITEM"));
        }

        if(!(isset($this->permissions['action2']) && ($this->permissions['action2']==1)))
        {
            $this->access_denied();
        }
        //validation start
        $changed_keys=array();
        $item['employee_id']=trim($item['employee_id']);
        if($item['employee_id']!=$item_current['employee_id'])
        {
            $duplicate_employee_id_check=Query_helper::get_info(TABLE_RND_SETUP_USER,array('employee_id'),array('employee_id ="'.$item['employee_id'].'"','id !='.$item_current['id'],'employee_id IS NOT NULL'),1);
            if($duplicate_employee_id_check)
            {
                $this->action_error($this->lang->line("MSG_EMPLOYEE_ID_EXISTS"));
            }
        }
        $item['user_name']=trim($item['user_name']);
        if($item['user_name']!=$item_current['user_name'])
        {
            $duplicate_username_check=Query_helper::get_info(TABLE_RND_SETUP_USER,array('user_name'),array('user_name ="'.$item['user_name'].'"','id !='.$item_current['id']),1);
            if($duplicate_username_check)
            {
                $this->action_error($this->lang->line("MSG_USER_NAME_EXISTS"));
            }
        }
        if($item['password'])
        {
            $item['password']=md5($item['password']);
        }
        else
        {
            $item['password']=$item_current['password'];
        }
        if($item['status']!=$item_current['status'])
        {
            if(!$this->input->post('remarks_status_change'))
            {
                $this->action_error($this->lang->line("LABEL_REMARKS_STATUS_CHANGE").' Required');
            }
        }
        if($item['time_mobile_authentication_off_end']>0)
        {
            $item['time_mobile_authentication_off_end']=System_helper::get_time(System_helper::display_date($time))+$item['time_mobile_authentication_off_end']*3600*24;
        }
        else
        {
            $item['time_mobile_authentication_off_end']=$item_current['time_mobile_authentication_off_end'];
        }
        $item['date_birth']=System_helper::get_time($item['date_birth']);
        $item['date_join']=System_helper::get_time($item['date_join']);
        $data_current=array();
        $data_new=array();
        foreach($item as $key=>$value)
        {
            if($item[$key]!=$item_current[$key])
            {
                $data_new[$key]=$item[$key];
                $data_current[$key]=$item_current[$key];
            }
        }

        $system_user_token_info = Token_helper::get_token($system_user_token);
        if($system_user_token_info['status'])
        {
            $this->message['system_message']=$this->lang->line('MSG_SAVE_ALREADY');
            $this->system_list();
        }
        if(!$data_new)
        {
            $this->action_error($this->lang->line("MSG_NO_CHANGE"));
        }



        $this->db->trans_start(); //DB Transaction Handle START

        Query_helper::update(TABLE_RND_SETUP_USER,$data_new,array("id = ".$id),false);
        System_helper::history_user($id,$data_current,$data_new,isset($data_new['status'])?array('remarks_status_change'=>$this->input->post('remarks_status_change')):array());


        Token_helper::update_token($system_user_token_info['id'], $system_user_token);

        $this->db->trans_complete(); //DB Transaction Handle END
        if ($this->db->trans_status()===true)
        {
            $this->message['system_message']=$this->lang->line('MSG_SAVE_DONE');
            $this->system_edit($id);
        }
        else
        {
            $this->action_error($this->lang->line("MSG_SAVE_FAIL"));
        }

    }


}

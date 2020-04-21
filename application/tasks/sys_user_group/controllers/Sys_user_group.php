<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sys_user_group extends Root_Controller
{
    public $controller_url;
    public $permissions;
    public $message;
    public function __construct()
    {
        parent::__construct();
        $this->controller_url = strtolower(get_class($this));
        $this->permissions = User_helper::get_permission(get_class($this));
        $this->message = array();

        $user = User_helper::get_user();
        if($user->user_group==1)
        {
            $this->permissions['action0']=1;
            $this->permissions['action2']=1;
        }

        $this->lang->load($this->controller_url.'/sys_user_group');
    }
    public function index()
    {
        $this->system_list();
    }

    /*public function index($action='list',$id=0)
    {
        if($action=='list')
        {
            $this->system_list();
        }
        elseif($action=='get_items')
        {
            $this->system_get_items();
        }
        elseif($action=='add')
        {
            $this->system_add();
        }
        elseif($action=='edit')
        {
            $this->system_edit($id);
        }
        elseif($action=='assign_group_role')
        {
            $this->system_assign_group_role($id);
        }
        elseif($action=='save')
        {
            $this->system_save();
        }
        elseif($action=='save_assign_group_role')
        {
            $this->system_save_assign_group_role();
        }
        else
        {
            $this->system_list();
        }
    }*/
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
            $data['total_task']= array('text'=>$this->lang->line('LABEL_TOTAL_TASK'),'type'=>'number','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"70"','filtertype'=>'"number"','cellsAlign'=>'"right"'));
            $data['ordering']= array('text'=>$this->lang->line('LABEL_ORDERING'),'type'=>'number','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"70"','filtertype'=>'"number"','cellsAlign'=>'"right"'));
            $data['status']= array('text'=>$this->lang->line('LABEL_STATUS'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"70"'));
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
            $data['system_jqx_items']= System_helper::get_preference($user->user_id, $this->controller_url, $method, $this->get_jqx_items($method));
            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#system_content','html'=>$this->load->view($this->controller_url.'/list',$data,true));
            $this->set_message($this->message,$ajax);
            $ajax['system_page_url']=site_url($this->controller_url.'/system_list');
            $this->json_return($ajax);
        }
        else
        {
            $this->access_denied();
        }
    }
    public function system_get_items_list()
    {
        $user=User_helper::get_user();
        if($user->user_group==1)
        {
            $user_groups=Query_helper::get_info(TABLE_SYSTEM_USER_GROUP,array('id','name','status','ordering'),array('status!="'.SYSTEM_STATUS_DELETE.'"'),0,0,array('ordering ASC'));
        }
        else
        {
            $user_groups=Query_helper::get_info(TABLE_SYSTEM_USER_GROUP,array('id','name','status','ordering'),array('id!=1','status!="'.SYSTEM_STATUS_DELETE.'"'),0,0,array('ordering ASC'));
        }

        $this->db->from(TABLE_SYSTEM_USER_GROUP_ROLE);
        $this->db->select('COUNT(id) total_task',false);
        $this->db->select('user_group_id');
        $this->db->where('revision',1);
        $this->db->where('action0',1);
        $this->db->group_by('user_group_id');
        $results=$this->db->get()->result_array();

        $total_roles=array();
        foreach($results as $result)
        {
            $total_roles[$result['user_group_id']]['total_task']=$result['total_task'];
        }
        foreach($user_groups as &$groups)
        {
            if(isset($total_roles[$groups['id']]['total_task']))
            {
                $groups['total_task']=$total_roles[$groups['id']]['total_task'];
            }
            else
            {
                $groups['total_task']=0;
            }
        }
        $this->json_return($user_groups);
    }
    public function system_add()
    {
        if(isset($this->permissions['action1']) && ($this->permissions['action1']==1))
        {
            $data['item']=array
            (
                'id'=>0,
                'name'=>'',
                'ordering'=>99,
                'status'=>SYSTEM_STATUS_ACTIVE
            );
            $ajax['system_page_url']=site_url($this->controller_url.'/system_add');
            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#system_content','html'=>$this->load->view($this->controller_url.'/add_edit',$data,true));
            $this->set_message($this->message,$ajax);
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
            if(($this->input->post('id')))
            {
                $item_id=$this->input->post('id');
            }
            else
            {
                $item_id=$id;
            }
            $data['item']=Query_helper::get_info(TABLE_SYSTEM_USER_GROUP,'*',array('id ='.$item_id),1);
            if(!$data['item'])
            {
                $this->action_error($this->lang->line("MSG_INVALID_USER_GROUP"));
            }
            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#system_content','html'=>$this->load->view($this->controller_url.'/add_edit',$data,true));
            $this->set_message($this->message,$ajax);
            $ajax['system_page_url']=site_url($this->controller_url.'/system_edit/'.$item_id);
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
            $ajax['status']=false;
            $this->message['system_message_type']='error';
            $this->message['system_message_duration']='30000';
            $this->set_message($this->message,$ajax);
            $this->json_return($ajax);
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
                $data['user_updated']=$user->user_id;
                $data['date_updated']=$time;
                Query_helper::update(TABLE_SYSTEM_USER_GROUP,$data,array('id='.$id));
            }
            else
            {
                $data['user_created']=$user->user_id;
                $data['date_created']=time();
                Query_helper::add(TABLE_SYSTEM_USER_GROUP,$data);
            }
            Token_helper::update_token($system_user_token_info['id'], $system_user_token);

            $this->db->trans_complete(); //DB Transaction Handle END
            if ($this->db->trans_status()===true)
            {
                $save_and_new=$this->input->post('system_save_new_status');
                $this->message['system_message']=$this->lang->line('MSG_SAVE_DONE_USER_GROUP');
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
                $this->action_error($this->lang->line("MSG_SAVE_FAIL_USER_GROUP"));
            }
        }
    }
    private function check_validation_add_edit()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('item[name]',$this->lang->line('LABEL_USER_GROUP_NAME'),'required');
        if($this->form_validation->run()==false)
        {
            $this->message['system_message']=validation_errors();
            return false;
        }
        return true;
    }



    private function system_assign_group_role($id)
    {
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
            $data['modules_tasks']=Task_helper::get_modules_tasks_table_tree();
            $data['role_status']=$this->get_role_status($item_id);
            $data['title']="Edit User Role";
            $data['item_id']=$item_id;
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view($this->controller_url.'/assign_group_role',$data,true));
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $ajax['system_page_url']=site_url($this->controller_url.'/index/assign_group_role/'.$item_id);
            $this->json_return($ajax);
        }
        else
        {
            $ajax['status']=false;
            $ajax['system_message']=$this->lang->line('YOU_DONT_HAVE_ACCESS');
            $this->json_return($ajax);
        }
    }
    private function get_role_status($user_group_id)
    {
        $this->db->from($this->config->item('table_system_user_group_role'));
        $this->db->select('*');
        $this->db->where('user_group_id',$user_group_id);
        $this->db->where('revision',1);
        $results=$this->db->get()->result_array();

        $roles=array();
        for($i=0;$i<$this->config->item('system_max_actions');$i++)
        {
            $roles['action'.$i]=array();
        }
        foreach($results as $result)
        {
            for($i=0;$i<$this->config->item('system_max_actions');$i++)
            {
                if($result['action'.$i])
                {
                    $roles['action'.$i][]=$result['task_id'];
                }
            }
        }
        return $roles;
    }


    private function system_save_assign_group_role()
    {
        $item_id=$this->input->post('id');
        $user=User_helper::get_user();
        if(!(isset($this->permissions['action2']) && ($this->permissions['action2']==1)))
        {
            $ajax['status']=false;
            $ajax['system_message']=$this->lang->line('YOU_DONT_HAVE_ACCESS');
            $this->json_return($ajax);
        }
        $tasks=$this->input->post('tasks');
        $time=time();

        $this->db->trans_start(); //DB Transaction Handle START

        $revision_history_data=array();
        $revision_history_data['date_updated']=$time;
        $revision_history_data['user_updated']=$user->user_id;
        Query_helper::update($this->config->item('table_system_user_group_role'),$revision_history_data,array('revision=1','user_group_id='.$item_id));

        $this->db->where('user_group_id',$item_id);
        $this->db->set('revision','revision+1',false);
        $this->db->update($this->config->item('table_system_user_group_role'));
        if(is_array($tasks))
        {
            foreach($tasks as $task_id=>$task)
            {
                $data=array();
                for($i=0;$i<$this->config->item('system_max_actions');$i++)
                {
                    if(isset($task['action'.$i]) && ($task['action'.$i]==1))
                    {
                        $data['action'.$i]=1;
                    }
                    else
                    {
                        $data['action'.$i]=0;
                    }
                }
                for($i=0;$i<$this->config->item('system_max_actions');$i++)
                {
                    if($data['action'.$i])
                    {
                        $data['action0']=1;
                        break;
                    }
                }
                $data['task_id']=$task_id;
                $data['user_group_id']=$item_id;
                $data['user_created']=$user->user_id;
                $data['date_created']=$time;
                Query_helper::add($this->config->item('table_system_user_group_role'),$data);
            }
        }
        $this->db->trans_complete(); //DB Transaction Handle END

        if ($this->db->trans_status()===true)
        {
            $this->message=$this->lang->line('MSG_ROLE_ASSIGN_SUCCESS');
            $this->system_list();
        }
        else
        {
            $ajax['status']=false;
            $ajax['system_message']=$this->lang->line('MSG_ROLE_ASSIGN_FAIL');
            $this->json_return($ajax);
        }
    }
}

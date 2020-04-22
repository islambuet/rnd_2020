<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_designation extends Root_Controller
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
    public function system_list()
    {
        if(isset($this->permissions['action0'])&&($this->permissions['action0']==1))
        {
            $data['items']=$this->get_designation_table_tree();
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
    private function get_designation_table_tree()
    {
        $CI=& get_instance();
        $CI->db->from(TABLE_RND_SETUP_DESIGNATION);
        $CI->db->order_by('ordering');
        $results=$CI->db->get()->result_array();
        $children=array();
        foreach($results as $result)
        {
            $children[$result['parent']]['ids'][$result['id']]=$result['id'];
            $children[$result['parent']]['designations'][$result['id']]=$result;
        }
        $level0=$children[0]['designations'];
        $tree=array();
        $max_level=1;
        foreach ($level0 as $designation)
        {
            $this->get_sub_designation_tree($designation,'',1,$max_level,$tree,$children);
        }
        return array('max_level'=>$max_level,'tree'=>$tree);
    }

    private function get_sub_designation_tree($designation,$prefix,$level,&$max_level,&$tree,$children)
    {
        if($level>$max_level)
        {
            $max_level=$level;
        }
        $tree[]=array('prefix'=>$prefix,'level'=>$level,'designation'=>$designation);
        $subs=array();
        if(isset($children[$designation['id']]))
        {
            $subs=$children[$designation['id']]['designations'];
        }
        if(sizeof($subs)>0)
        {
            foreach($subs as $sub)
            {
                $this->get_sub_designation_tree($sub,$prefix.'- ',$level+1,$max_level,$tree,$children);
            }
        }
    }
    public function system_add()
    {
        if(isset($this->permissions['action1'])&&($this->permissions['action1']==1))
        {
            $data["item"] = Array(
                'id' => 0,
                'name' => '',
                'parent' => 0,
                'ordering' => 99,
                'status' => SYSTEM_STATUS_ACTIVE
            );
            $data['designations']=$this->get_designation_table_tree();
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
    public function system_edit($id)
    {
        if(isset($this->permissions['action2'])&&($this->permissions['action2']==1))
        {
            $data['item']=Query_helper::get_info(TABLE_RND_SETUP_DESIGNATION,'*',array('id ='.$id),1);
            $data['designations']=$this->get_designation_table_tree();
            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#system_content','html'=>$this->load->view($this->controller_name.'/add_edit',$data,true));
            $this->set_message($this->message,$ajax);
            $ajax['system_page_url']=site_url($this->controller_name."/system_edit/".$id);
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
        $system_user_token = $this->input->post("system_user_token");
        $id = $this->input->post("id");
        $data=$this->input->post('item');
        if($id>0)
        {
            if(!(isset($this->permissions['action2'])&&($this->permissions['action2']==1)))
            {
                $this->access_denied();
            }
        }
        else
        {
            if(!(isset($this->permissions['action1'])&&($this->permissions['action1']==1)))
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
        $this->db->trans_start();  //DB Transaction Handle START
        if($id>0)
        {
            $data['user_updated'] = $user->user_id;
            $data['date_updated'] = $time;
            Query_helper::update(TABLE_RND_SETUP_DESIGNATION,$data,array("id = ".$id));
        }
        else
        {
            $data['user_created'] = $user->user_id;
            $data['date_created'] = time();
            Query_helper::add(TABLE_RND_SETUP_DESIGNATION,$data);
        }
        Token_helper::update_token($system_user_token_info['id'], $system_user_token);
        $this->db->trans_complete();   //DB Transaction Handle END
        if ($this->db->trans_status() === TRUE)
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
}

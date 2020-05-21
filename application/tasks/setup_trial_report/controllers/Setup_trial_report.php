<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup_trial_report extends Root_Controller
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
        $this->load->config('system_trial');
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
            $data['crop_id']= array('text'=>$this->lang->line('LABEL_ID'),'type'=>'number','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"50"','cellsAlign'=>'"right"'));
            $data['crop_name']= array('text'=>$this->lang->line('LABEL_CROP_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"200"'));
            $data['input_ids']= array('text'=>$this->lang->line('LABEL_HEADERS'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"200"'));
            for($i=1;$i<=SYSTEM_TRIAL_REPORT_MAX_CALCULATION;$i++)
            {
                $data['calc_name_'.$i]= array('text'=>$this->lang->line('LABEL_CALC_NAME_'.$i),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"200"'));
                $data['calc_value_'.$i]= array('text'=>$this->lang->line('LABEL_CALC_VALUE_'.$i),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"200"'));
            }
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
        $items=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_REPORT,'*',array('status !="'.SYSTEM_STATUS_DELETE.'"'),0,0,array('ordering ASC','id ASC'));
        $this->json_return($items);
    }
    public function system_add()
    {
        if(isset($this->permissions['action1']) && ($this->permissions['action1']==1))
        {
            $data['item']=array();
            $table_fields = $this->db->field_data(TABLE_RND_SETUP_TRIAL_REPORT);

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
            $data['item']=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_REPORT,'*',array('id ='.$item_id),1);
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
                Query_helper::update(TABLE_RND_SETUP_TRIAL_REPORT,$data,array('id='.$id));
            }
            else
            {
                $data['user_created']=$user->id;
                $data['date_created']=$time;
                Query_helper::add(TABLE_RND_SETUP_TRIAL_REPORT,$data);
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
    public function system_list_input($report_id=0)
    {
        if(isset($this->permissions['action0']) && ($this->permissions['action0']==1))
        {
            $user = User_helper::get_user();
            $method='system_list_input';
            if($report_id>0)
            {
                $item_id=$report_id;
            }
            else
            {
                $item_id=$this->input->post('id');
            }
            if(!$item_id)
            {
                $this->system_list();
            }


            $data['item']=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_REPORT,'*',array('id ='.$item_id),1);
            if(!$data['item'])
            {
                $this->action_error($this->lang->line("MSG_INVALID_ITEM"));
            }
            $data['system_jqx_items']= System_helper::get_preference($user->id, $this->controller_name, $method, $this->get_jqx_items($method));
            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#system_content','html'=>$this->load->view($this->controller_name.'/list_inputs',$data,true));
            $this->set_message($this->message,$ajax);
            $ajax['system_page_url']=site_url($this->controller_name.'/system_list_input/'.$item_id);
            $this->json_return($ajax);
        }
        else
        {
            $this->access_denied();
        }
    }
    public function system_get_items_list_input($report_id)
    {
        $results=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_REPORT_INPUT_FIELDS,'*',array('report_id ='.$report_id));
        $report_headers=array();
        foreach($results as $result)
        {
            $report_headers[$result['crop_id']]=$result;
        }
        $this->db->from(TABLE_RND_SETUP_CROP.' crop');
        $this->db->select('crop.name crop_name,crop.id crop_id');
        $items=$this->db->get()->result_array();
        foreach($items as &$item)
        {
            if(isset($report_headers[$item['crop_id']]))
            {
                $item['input_ids']=$report_headers[$item['crop_id']]['input_ids'];
                for($i=1;$i<=SYSTEM_TRIAL_REPORT_MAX_CALCULATION;$i++)
                {
                    $item['calc_name_'.$i]= $report_headers[$item['crop_id']]['calc_name_'.$i];
                    $item['calc_value_'.$i]= $report_headers[$item['crop_id']]['calc_value_'.$i];
                }
            }

        }
        $this->json_return($items);
    }

    public function system_edit_input($report_id,$crop_id=0)
    {
        if(isset($this->permissions['action2']) && ($this->permissions['action2']==1))
        {
            if(!($crop_id>0))
            {
                $crop_id=$this->input->post('crop_id');

            }
            $this->db->from(TABLE_RND_SETUP_TRIAL_DATA_INPUT_FIELDS.' input');
            $this->db->select('input.name input_name,input.id input_id');
            $this->db->join(TABLE_RND_SETUP_TRIAL_DATA.' trial','trial.id = input.trial_id','INNER');
            $this->db->select('trial.id trial_id,trial.name trial_name');
            $this->db->order_by('trial.ordering ASC');
            $this->db->order_by('trial.id ASC');
            $this->db->order_by('input.ordering ASC');
            $this->db->order_by('input.id ASC');
            $results=$this->db->get()->result_array();
            $data['trail_inputs']=array();
            foreach($results as $result)
            {

                $data['trail_inputs'][$result['trial_id']]['trial_name']=$result['trial_name'];
                $data['trail_inputs'][$result['trial_id']]['trial_id']=$result['trial_id'];
                $data['trail_inputs'][$result['trial_id']]['inputs'][]=$result;
            }
            $data['item']=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_REPORT_INPUT_FIELDS,'*',array('report_id ='.$report_id,'crop_id ='.$crop_id),1);
            /*if(!$data['selected_inputs'])
            {

            }*/
            $data['report']=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_REPORT,'*',array('id ='.$report_id),1);
            $data['crop']=Query_helper::get_info(TABLE_RND_SETUP_CROP,'*',array('id ='.$crop_id),1);
            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#system_content','html'=>$this->load->view($this->controller_name.'/add_edit_input',$data,true));
            $this->set_message($this->message,$ajax);
            $ajax['system_page_url']=site_url($this->controller_name.'/system_edit_input/'.$report_id.'/'.$crop_id);
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

        $crop_id=$this->input->post('crop_id');
        $report_id=$this->input->post('report_id');
        $system_user_token = $this->input->post("system_user_token");
        $input_ids = $this->input->post("input_ids");
        $input_ids=is_array($input_ids)?implode(',',$input_ids):'';
        $item = $this->input->post('item');
        $item['input_ids']=','.$input_ids.',';
        $item_current=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_REPORT_INPUT_FIELDS,'*',array('report_id ='.$report_id,'crop_id ='.$crop_id),1);

        if(!(isset($this->permissions['action2']) && ($this->permissions['action2']==1)))
        {
            $this->access_denied();
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
            if($item_current)
            {
                $data['user_updated']=$user->id;
                $data['date_updated']=$time;
                Query_helper::update(TABLE_RND_SETUP_TRIAL_REPORT_INPUT_FIELDS,$data,array('id='.$item_current['id']));
            }
            else
            {
                $data['crop_id']=$crop_id;
                $data['report_id']=$report_id;
                $data['user_created']=$user->id;
                $data['date_created']=$time;
                Query_helper::add(TABLE_RND_SETUP_TRIAL_REPORT_INPUT_FIELDS,$data);
            }
            Token_helper::update_token($system_user_token_info['id'], $system_user_token);

            $this->db->trans_complete(); //DB Transaction Handle END
            if ($this->db->trans_status()===true)
            {
                $save_and_new=$this->input->post('system_save_new_status');
                $this->message['system_message']=$this->lang->line('MSG_SAVE_DONE');
                $this->system_list_input($report_id);
            }
            else
            {
                $this->action_error($this->lang->line("MSG_SAVE_FAIL"));
            }
        }
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup_variety extends Root_Controller
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
        $this->lang->load('setup_crop/setup_crop');
        $this->lang->load('setup_type/setup_type');
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
            $data['crop_name']= array('text'=>$this->lang->line('LABEL_CROP_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"150"','filtertype'=>'"list"'));
            $data['type_name']= array('text'=>$this->lang->line('LABEL_TYPE_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"150"'));
            $data['name']= array('text'=>$this->lang->line('LABEL_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"150"'));
            $data['whose']= array('text'=>$this->lang->line('LABEL_WHOSE'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"200"','filtertype'=>'"list"'));
            $data['principal_name']= array('text'=>$this->lang->line('LABEL_PRINCIPAL_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"200"','filtertype'=>'"list"'));
            $data['competitor_name']= array('text'=>$this->lang->line('LABEL_COMPETITOR_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"200"','filtertype'=>'"list"'));

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
        $this->db->from(TABLE_RND_SETUP_VARIETY.' variety');
        $this->db->select('variety.*');
        $this->db->join(TABLE_RND_SETUP_TYPE.' type','type.id = variety.type_id','INNER');
        $this->db->select('type.name type_name');
        $this->db->join(TABLE_RND_SETUP_CROP.' crop','crop.id = type.crop_id','INNER');
        $this->db->select('crop.name crop_name');

        $this->db->join(TABLE_RND_SETUP_PRINCIPAL.' principal','principal.id = variety.principal_id','LEFT');
        $this->db->select('principal.name principal_name');
        $this->db->join(TABLE_RND_SETUP_COMPETITOR.' competitor','competitor.id = variety.competitor_id','LEFT');
        $this->db->select('competitor.name competitor_name');

        $this->db->where('variety.status !=',SYSTEM_STATUS_DELETE);
        $this->db->order_by('crop.ordering','ASC');
        $this->db->order_by('crop.id','ASC');
        $this->db->order_by('type.ordering','DESC');
        $this->db->order_by('type.id','ASC');
        $this->db->order_by('variety.ordering','ASC');
        $this->db->order_by('variety.id','ASC');
        $items=$this->db->get()->result_array();

        $this->json_return($items);
    }
    public function system_add()
    {
        if(isset($this->permissions['action1']) && ($this->permissions['action1']==1))
        {
            $data['item']=array();
            $table_fields = $this->db->field_data(TABLE_RND_SETUP_VARIETY);

            foreach ($table_fields as $field)
            {
                $data['item'][$field->name]=$field->default;
            }
            $data['item']['crop_id']=0;
            $data['crops']=Query_helper::get_info(TABLE_RND_SETUP_CROP,array('id value','name text'),array('status !="'.SYSTEM_STATUS_DELETE.'"'));
            $data['types']=array();
            $data['competitors']=Query_helper::get_info(TABLE_RND_SETUP_COMPETITOR,array('id value','name text'),array('status !="'.SYSTEM_STATUS_DELETE.'"'));
            $data['principals']=Query_helper::get_info(TABLE_RND_SETUP_PRINCIPAL,array('id value','name text'),array('status !="'.SYSTEM_STATUS_DELETE.'"'));
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
            if(($this->input->post('id')))
            {
                $item_id=$this->input->post('id');
            }
            else
            {
                $item_id=$id;
            }
            $this->db->from(TABLE_RND_SETUP_VARIETY.' variety');
            $this->db->select('variety.*');
            $this->db->join(TABLE_RND_SETUP_TYPE.' type','type.id = variety.type_id','INNER');
            $this->db->select('type.name type_name');
            $this->db->select('type.crop_id crop_id');
            $this->db->where('variety.id',$item_id);
            $data['item']=$this->db->get()->row_array();

            //$data['item']=Query_helper::get_info(TABLE_RND_SETUP_VARIETY,'*',array('id ='.$item_id),1);
            if(!$data['item'])
            {
                $this->action_error($this->lang->line("MSG_INVALID_ITEM"));
            }
            $data['crops']=Query_helper::get_info(TABLE_RND_SETUP_CROP,array('id value','name text'),array('status !="'.SYSTEM_STATUS_DELETE.'"'));
            $data['types']=Query_helper::get_info(TABLE_RND_SETUP_TYPE,array('id value','name text'),array('status !="'.SYSTEM_STATUS_DELETE.'"','crop_id ='.$data['item']['crop_id']));
            $data['competitors']=Query_helper::get_info(TABLE_RND_SETUP_COMPETITOR,array('id value','name text'),array('status !="'.SYSTEM_STATUS_DELETE.'"'));
            $data['principals']=Query_helper::get_info(TABLE_RND_SETUP_PRINCIPAL,array('id value','name text'),array('status !="'.SYSTEM_STATUS_DELETE.'"'));
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
        $item['name']=trim($item['name']);
        $duplicate_name_check=Query_helper::get_info(TABLE_RND_SETUP_VARIETY,array('name'),array('name ="'.$item['name'].'"','id !='.$id),1);
        if($duplicate_name_check)
        {
            $this->action_error($this->lang->line("MSG_SAME_VARIETY_NAME"));
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
                Query_helper::update(TABLE_RND_SETUP_VARIETY,$data,array('id='.$id));
            }
            else
            {
                $data['user_created']=$user->id;
                $data['date_created']=$time;
                Query_helper::add(TABLE_RND_SETUP_VARIETY,$data);
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
        $this->form_validation->set_rules('item[type_id]',$this->lang->line('LABEL_TYPE_NAME'),'required');
        $this->form_validation->set_rules('item[name]',$this->lang->line('LABEL_NAME'),'required');
        $this->form_validation->set_rules('item[status]',$this->lang->line('LABEL_STATUS'),'required');
        $item=$this->input->post('item');
        if($item['whose']=='Competitor')
        {
            $this->form_validation->set_rules('item[competitor_id]',$this->lang->line('LABEL_COMPETITOR_NAME'),'required');
        }
        elseif($item['whose']=='Principal')
        {
            $this->form_validation->set_rules('item[principal_id]',$this->lang->line('LABEL_PRINCIPAL_NAME'),'required');
        }

        if($this->form_validation->run()==false)
        {
            $this->message['system_message']=validation_errors();
            return false;
        }
        return true;
    }
}

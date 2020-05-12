<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vc_variety_delivery extends Root_Controller
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
        $this->lang->load('setup_season/setup_season');
        $this->lang->load('setup_crop/setup_crop');
        $this->lang->load('setup_type/setup_type');
        $this->lang->load('setup_variety/setup_variety');
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
            $data['season_name']= array('text'=>$this->lang->line('LABEL_SEASON_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"150"','filtertype'=>'"list"'));
            $data['num_variety_selected']= array('text'=>$this->lang->line('LABEL_NUM_VARIETY_SELECTED'),'type'=>'number','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"100"','filtertype'=>'"number"','cellsAlign'=>'"right"'));
            $data['num_variety_delivered']= array('text'=>$this->lang->line('LABEL_NUM_VARIETY_DELIVERED'),'type'=>'number','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"100"','filtertype'=>'"number"','cellsAlign'=>'"right"'));
            $data['num_variety_not_delivered']= array('text'=>$this->lang->line('LABEL_NUM_VARIETY_NOT_DELIVERED'),'type'=>'number','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"100"','filtertype'=>'"number"','cellsAlign'=>'"right"'));

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
    public function system_list($year=0)
    {
        if(isset($this->permissions['action0']) && ($this->permissions['action0']==1))
        {
            if(($this->input->post('year')))
            {
                $year=$this->input->post('year');
            }
            else if($year==0)
            {
                $year=date("Y");
            }
            $data['year']=$year;
            $user = User_helper::get_user();
            $method='system_list';
            $data['system_jqx_items']= System_helper::get_preference($user->id, $this->controller_name, $method, $this->get_jqx_items($method));
            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#system_content','html'=>$this->load->view($this->controller_name.'/list',$data,true));
            $this->set_message($this->message,$ajax);
            $ajax['system_page_url']=site_url($this->controller_name.'/system_list/'.$year);
            $this->json_return($ajax);
        }
        else
        {
            $this->access_denied();
        }
    }
    public function system_get_items_list($year)
    {
        $this->db->from(TABLE_RND_VC_VARIETY_SELECTION.' vc');
        $this->db->select('vc.season_id');
        $this->db->select('SUM(CASE WHEN vc.status_selection="'.SYSTEM_STATUS_YES.'" then 1 ELSE 0 END) num_variety_selected',false);
        $this->db->select('SUM(CASE WHEN vc.status_selection="'.SYSTEM_STATUS_YES.'" AND vc.status_delivery="'.SYSTEM_STATUS_YES.'" then 1 ELSE 0 END) num_variety_delivered',false);
        $this->db->where('vc.year',$year);
        $this->db->group_by('vc.season_id');
        $results=$this->db->get()->result_array();
        $seasons_variety=array();
        foreach($results as $result)
        {
            $seasons_variety[$result['season_id']]=$result;
        }


        $items=Query_helper::get_info(TABLE_RND_SETUP_SEASON,array('id','name season_name'),array('status !="'.SYSTEM_STATUS_DELETE.'"'),0,0,array('ordering ASC','id ASC'));
        foreach($items as &$item)
        {
            if(isset($seasons_variety[$item['id']]))
            {
                $item['num_variety_selected']=$seasons_variety[$item['id']]['num_variety_selected'];
                $item['num_variety_delivered']=$seasons_variety[$item['id']]['num_variety_delivered'];
            }
            else
            {
                $item['num_variety_selected']=0;
                $item['num_variety_delivered']=0;
            }
            $item['num_variety_not_delivered']=$item['num_variety_selected']-$item['num_variety_delivered'];

        }

        $this->json_return($items);
    }
    public function system_edit($year,$is_delivered,$id=0)
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
            //item id=season id
            $this->db->from(TABLE_RND_VC_VARIETY_SELECTION.' vc');
            $this->db->select('vc.variety_index,vc.year');
            $this->db->where('vc.status_selection',SYSTEM_STATUS_YES);
            $this->db->where('vc.year',$year);
            $this->db->where('vc.season_id',$item_id);
            if($is_delivered)
            {
                $this->db->where('vc.status_delivery',SYSTEM_STATUS_YES);
            }
            else
            {
                $this->db->where('vc.status_delivery',SYSTEM_STATUS_NO);
            }
            $this->db->join(TABLE_RND_SETUP_VARIETY.' variety','variety.id = vc.variety_id','INNER');
            $this->db->select('variety.name variety_name,variety.id variety_id');
            $this->db->join(TABLE_RND_SETUP_TYPE.' type','type.id = variety.type_id','INNER');
            $this->db->select('type.name type_name,type.code type_code');
            $this->db->join(TABLE_RND_SETUP_CROP.' crop','crop.id = type.crop_id','INNER');
            $this->db->select('crop.name crop_name,crop.code crop_code,crop.id crop_id');

            $this->db->order_by('crop.ordering','ASC');
            $this->db->order_by('crop.id','ASC');
            $this->db->order_by('type.ordering','DESC');
            $this->db->order_by('type.id','ASC');
            $this->db->order_by('variety.ordering','ASC');
            $this->db->order_by('variety.id','ASC');

            $results=$this->db->get()->result_array();

            $data['crop_varieties']=array();
            foreach($results as $result)
            {
                $result['rnd_code']=System_helper::get_variety_rnd_code($result);
                $data['crop_varieties'][$result['crop_id']]['crop_name']=$result['crop_name'];
                $data['crop_varieties'][$result['crop_id']]['crop_id']=$result['crop_id'];
                $data['crop_varieties'][$result['crop_id']]['varieties'][]=$result;
            }
            $data['year']=$year;
            $data['is_delivered']=$is_delivered;
            $data['season']=Query_helper::get_info(TABLE_RND_SETUP_SEASON,array('id value','name text'),array('id ="'.$item_id.'"'),1);

            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#system_content','html'=>$this->load->view($this->controller_name.'/edit',$data,true));
            $this->set_message($this->message,$ajax);
            $ajax['system_page_url']=site_url($this->controller_name.'/system_edit/'.$year.'/'.$is_delivered.'/'.$item_id);
            $this->json_return($ajax);
        }
        else
        {
            $this->access_denied();
        }
    }
    public function system_save_edit()
    {
        $user=User_helper::get_user();
        $time = time();
        $season_id=$this->input->post('id');
        $year=$this->input->post('year');
        $is_delivered=$this->input->post('is_delivered');
        $date_delivery=$this->input->post('date_delivery');
        $varieties=$this->input->post('varieties');
        $system_user_token = $this->input->post("system_user_token");
        if(!(isset($this->permissions['action2']) && ($this->permissions['action2']==1)))
        {
            $this->access_denied();
        }
        if(!$is_delivered)
        {
            if(!$date_delivery)
            {
                $this->action_error($this->lang->line('MSG_DELIVERY_DATE_REQUIRE'));
            }
        }
        $system_user_token_info = Token_helper::get_token($system_user_token);
        if($system_user_token_info['status'])
        {
            $this->message['system_message']=$this->lang->line('MSG_SAVE_ALREADY');
            $this->system_list($year);
        }
        $selected_varieties=array();
        $results=Query_helper::get_info(TABLE_RND_VC_VARIETY_SELECTION,'*',array('year ='.$year,'season_id ='.$season_id));
        foreach($results as $result)
        {

            $selected_varieties[$result['variety_id']]=$result;
        }

        $this->db->trans_start(); //DB Transaction Handle START

        foreach($varieties as $variety_id)
        {
            if(isset($selected_varieties[$variety_id]))
            {
                if($is_delivered)//return
                {
                    $data=array();
                    $data['status_delivery']=SYSTEM_STATUS_NO;
                    Query_helper::update(TABLE_RND_VC_VARIETY_SELECTION,$data,array('id ='.$selected_varieties[$variety_id]['id']),false);
                    System_helper::history_save(TABLE_RND_VC_VARIETY_SELECTION_HISTORY,$selected_varieties[$variety_id]['id'],array('status_delivery'=>$selected_varieties[$variety_id]['status_delivery']),array('status_delivery'=>SYSTEM_STATUS_NO));

                }
                else//send
                {
                    $data=array();
                    $data['status_delivery']=SYSTEM_STATUS_YES;
                    $data['date_delivered']=$time;
                    $data['user_delivered']=$user->id;
                    Query_helper::update(TABLE_RND_VC_VARIETY_SELECTION,$data,array('id ='.$selected_varieties[$variety_id]['id']));

                }
            }
        }
        Token_helper::update_token($system_user_token_info['id'], $system_user_token);

        $this->db->trans_complete(); //DB Transaction Handle END
        if ($this->db->trans_status()===true)
        {
            //$save_and_new=$this->input->post('system_save_new_status');
            $this->message['system_message']=$this->lang->line('MSG_SAVE_DONE');
            $this->system_list($year);
        }
        else
        {
            $this->action_error($this->lang->line("MSG_SAVE_FAIL"));
        }
    }
}

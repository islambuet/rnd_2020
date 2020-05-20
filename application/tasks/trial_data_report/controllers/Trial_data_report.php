<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trial_data_report extends Root_Controller
{
    public $controller_name;
    public $message;
    public function __construct()
    {
        parent::__construct();
        $this->controller_name = strtolower(get_class($this));
        $this->message = array();
        $this->load->helper('season');
        $this->load->config('system_input_type');
        $this->lang->load('upload');
        $this->lang->load('setup_season/setup_season');
        $this->lang->load('setup_crop/setup_crop');
        $this->lang->load('setup_type/setup_type');
        $this->lang->load('setup_variety/setup_variety');
        $this->lang->load('trial_data_report/trial_data_report');
        $this->lang->load($this->controller_name.'/'.$this->controller_name);
    }
    public function index()
    {
        $this->system_search();
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

    public function get_jqx_items($method,$crop_id,$trail_id=0)
    {
        $data=array();
        if($method=='system_list')
        {
            $data['variety_id']= array('text'=>$this->lang->line('LABEL_VARIETY_ID'),'type'=>'number','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"50"','cellsAlign'=>'"right"'));
            $data['variety_name']= array('text'=>$this->lang->line('LABEL_VARIETY_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"150"'));
            $data['rnd_code']= array('text'=>$this->lang->line('LABEL_RND_CODE'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"150"'));
            if($trail_id==0)
            {
                $this->db->from(TABLE_RND_SETUP_TRIAL_DATA_INPUT_FIELDS.' input');
                $this->db->select('input.name input_name,input.id input_id');
                $this->db->join(TABLE_RND_SETUP_TRIAL_DATA.' trial','trial.id = input.trial_id','INNER');
                $this->db->select('trial.name trial_name');
                $this->db->where('input.crop_id',$crop_id);
                $this->db->where('input.summary_report_column',SYSTEM_STATUS_YES);
                $this->db->order_by('trial.ordering','ASC');
                $this->db->order_by('input.id','ASC');
                $results=$this->db->get()->result_array();
                foreach($results as $result)
                {
                    $data['input_'.$result['input_id']]= array('text'=>$result['trial_name'].' - '.$result['input_name'],'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"150"','renderer'=>'header_render'));
                }
            }
            else
            {
                $this->db->from(TABLE_RND_SETUP_TRIAL_DATA_INPUT_FIELDS.' input');
                $this->db->select('input.name input_name,input.id input_id');
                $this->db->where('input.crop_id',$crop_id);
                $this->db->where('input.trial_id',$trail_id);
                $this->db->order_by('input.id','ASC');
                $results=$this->db->get()->result_array();
                foreach($results as $result)
                {
                    $data['input_'.$result['input_id']]= array('text'=>$result['input_name'],'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"150"','renderer'=>'header_render'));
                }

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
    public function system_search()
    {
        $user=User_helper::get_user();
        if(strlen($user->trail_data_report)>2)
        {
            $data['year']=date("Y");
            $data['season_id']=Season_helper::get_current_season()['id'];
            $data['seasons']=Season_helper::get_all_seasons();
            $data['trials']=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_DATA,array('id value','name text'),array('status !="'.SYSTEM_STATUS_DELETE.'"'),0,0,array('ordering ASC','id ASC'));
            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#system_content','html'=>$this->load->view($this->controller_name.'/search',$data,true));
            $this->set_message($this->message,$ajax);
            $ajax['system_page_url']=site_url($this->controller_name.'/system_search');
            $this->json_return($ajax);
        }
        else
        {
            $this->access_denied();
        }
    }
    public function system_report()
    {
        $method='system_list';
        $user=User_helper::get_user();
        $search_items = $this->input->post('search_items');
        if(strlen($user->trail_data_report)>2)
        {
            if(!$this->check_validation_report())
            {
                $this->validation_error($this->message['system_message']);
            }
            $data['search_items']=$search_items;
            $data['system_jqx_items']= System_helper::get_preference($user->id, $this->controller_name, $method, $this->get_jqx_items($method,$search_items['crop_id'],$search_items['trial_id']));
            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#report_container','html'=>$this->load->view($this->controller_name.'/list',$data,true));
            $this->set_message($this->message,$ajax);
            $this->json_return($ajax);
        }

    }
    private function check_validation_report()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('search_items[year]',$this->lang->line('LABEL_YEAR'),'required');
        $this->form_validation->set_rules('search_items[season_id]',$this->lang->line('LABEL_SEASON_NAME'),'required');
        $this->form_validation->set_rules('search_items[crop_id]',$this->lang->line('LABEL_CROP_NAME'),'required');
        if($this->form_validation->run()==false)
        {
            $this->message['system_message']=validation_errors();
            return false;
        }
        return true;
    }
    public function system_get_items_list()
    {
        //$trial_id=5;
        $year=$this->input->post('year');
        $season_id=$this->input->post('season_id');

        $user=User_helper::get_user();

        /*$this->db->from(TABLE_RND_TRIAL_DATA.' data');
        $this->db->select('data.id,data.variety_id');
        $this->db->where('data.year',$year);
        $this->db->where('data.season_id',$season_id);
        $this->db->where('data.trial_id',$trial_id);
        $results=$this->db->get()->result_array();
        $data_trail=array();
        foreach($results as $result)
        {
            $data_trail[$result['variety_id']]=$result['id'];
        }*/



        $this->db->from(TABLE_RND_VC_VARIETY_SELECTION.' vc');
        $this->db->select('vc.variety_index,vc.year');
        $this->db->join(TABLE_RND_SETUP_VARIETY.' variety','variety.id = vc.variety_id','INNER');
        $this->db->select('variety.name variety_name,variety.id variety_id');
        $this->db->join(TABLE_RND_SETUP_TYPE.' type','type.id = variety.type_id','INNER');
        $this->db->select('type.name type_name,type.code type_code');
        $this->db->join(TABLE_RND_SETUP_CROP.' crop','crop.id = type.crop_id','INNER');
        $this->db->select('crop.name crop_name,crop.code crop_code');

        $this->db->where('vc.year',$year);
        $this->db->where('vc.season_id',$season_id);
        $this->db->where('vc.status_selection',SYSTEM_STATUS_YES);
        $this->db->where('vc.status_delivery',SYSTEM_STATUS_YES);
        $this->db->where('vc.status_sowing',SYSTEM_STATUS_YES);
        if(strlen($user->crop_ids)>2)
        {
            $this->db->where_in('crop.id',explode(',',trim($user->crop_ids, ",")));
        }
        $this->db->order_by('crop.ordering','ASC');
        $this->db->order_by('crop.id','ASC');
        $this->db->order_by('type.ordering','DESC');
        $this->db->order_by('type.id','ASC');
        $this->db->order_by('variety.ordering','ASC');
        $this->db->order_by('variety.id','ASC');

        $items=$this->db->get()->result_array();
        foreach($items as &$item)
        {
            $item['rnd_code']=System_helper::get_variety_rnd_code($item);
        }

        $this->json_return($items);
    }
}
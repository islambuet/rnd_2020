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
        $this->load->config('system_trial');
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
    public function system_search()
    {
        $user=User_helper::get_user();
        if(strlen($user->trial_report)>2)
        {
            $data['year']=date("Y");
            $data['season_id']=Season_helper::get_current_season()['id'];
            $data['seasons']=Season_helper::get_all_seasons();



            $this->db->from(TABLE_RND_SETUP_CROP.' crop');
            $this->db->select('crop.id value,crop.name text');
            if(strlen($user->crop_ids)>2)
            {
                $this->db->where_in('crop.id',explode(',',trim($user->crop_ids, ",")));
            }
            $this->db->order_by('crop.ordering ASC');
            $this->db->order_by('crop.id ASC');
            $data['crops']=$this->db->get()->result_array();

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
    public function system_search_variety()
    {
        $user=User_helper::get_user();
        $data= $this->input->post('search_items');

        $this->db->from(TABLE_RND_VC_VARIETY_SELECTION.' vc');
        $this->db->select('vc.variety_index,vc.year');
        $this->db->join(TABLE_RND_SETUP_VARIETY.' variety','variety.id = vc.variety_id','INNER');
        $this->db->select('variety.name variety_name,variety.id variety_id');
        $this->db->join(TABLE_RND_SETUP_TYPE.' type','type.id = variety.type_id','INNER');
        $this->db->select('type.name type_name,type.code type_code');
        $this->db->join(TABLE_RND_SETUP_CROP.' crop','crop.id = type.crop_id','INNER');
        $this->db->select('crop.name crop_name,crop.code crop_code');

        $this->db->where('vc.year',$data['year']);
        $this->db->where('vc.season_id',$data['season_id']);
        $this->db->where('crop.id',$data['crop_id']);
        if($data['type_id']>0)
        {
            $this->db->where('type.id',$data['type_id']);
        }
        $this->db->where('vc.status_selection',SYSTEM_STATUS_YES);
        $this->db->where('vc.status_delivery',SYSTEM_STATUS_YES);
        $this->db->where('vc.status_sowing',SYSTEM_STATUS_YES);

        $this->db->order_by('crop.ordering','ASC');
        $this->db->order_by('crop.id','ASC');
        $this->db->order_by('type.ordering','DESC');
        $this->db->order_by('type.id','ASC');
        $this->db->order_by('variety.ordering','ASC');
        $this->db->order_by('variety.id','ASC');

        $data['varieties']=$this->db->get()->result_array();
        if(!(sizeof($data['varieties'])>0))
        {
            $this->action_error($this->lang->line("MSG_VARIETY_NOT_FOUND"));
        }


        $this->db->from(TABLE_RND_SETUP_TRIAL_REPORT.' report');
        $this->db->select('report.id value,report.name text');
        $this->db->where('status',SYSTEM_STATUS_ACTIVE);
        $this->db->where_in('report.id',explode(',',trim($user->trial_report, ",")));
        $this->db->order_by('report.ordering ASC');
        $this->db->order_by('report.id ASC');
        $data['reports']=$this->db->get()->result_array();



        $ajax['status']=true;
        $ajax['system_content'][]=array('id'=>'#variety_container','html'=>$this->load->view($this->controller_name.'/search_variety',$data,true));
        $this->set_message($this->message,$ajax);

        $this->json_return($ajax);


    }
    public function system_report()
    {
        $method='system_list';
        $user=User_helper::get_user();
        $search_items = $this->input->post('search_items');
        if(!(isset($search_items['variety_ids'])))
        {
            $this->action_error($this->lang->line("MSG_NO_VARIETY_SELECTED"));
        }

        if(strpos($user->trial_report, ','.$search_items['report_id'].',') !== FALSE)
        {
            $data['search_items']=$search_items;
            $data['system_jqx_items']= array();
            $data['system_jqx_items']['variety_id']= array('text'=>$this->lang->line('LABEL_VARIETY_ID'),'type'=>'number','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"50"','cellsAlign'=>'"right"'));
            //$data['system_jqx_items']['variety_name']= array('text'=>$this->lang->line('LABEL_VARIETY_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"150"'));
            $data['system_jqx_items']['rnd_code']= array('text'=>$this->lang->line('LABEL_RND_CODE'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"150"'));

            $this->db->from(TABLE_RND_SETUP_TRIAL_REPORT_INPUT_FIELDS.' input');
            $this->db->select('input.*');
            $this->db->join(TABLE_RND_SETUP_TRIAL_REPORT.' report','report.id = input.report_id','INNER');
            $this->db->select('report.name report_name');
            $this->db->where('input.crop_id',$search_items['crop_id']);
            $this->db->where('report.id',$search_items['report_id']);
            $data['report']=$this->db->get()->row_array();
            if(!$data['report'])
            {
                $this->action_error($this->lang->line("MSG_INVALID_REPORT"));
            }
            $report_input_ids=explode(',',trim($data['report']['input_ids'],','));


            $this->db->from(TABLE_RND_SETUP_TRIAL_DATA_INPUT_FIELDS.' input');
            $this->db->select('input.name input_name,input.id input_id,input.type input_type');
            $this->db->join(TABLE_RND_SETUP_TRIAL_DATA.' trial','trial.id = input.trial_id','INNER');
            $this->db->select('trial.name trial_name');
            $this->db->where_in('input.id',$report_input_ids);

            $this->db->order_by('trial.ordering ASC');
            $this->db->order_by('trial.id ASC');
            $this->db->order_by('input.ordering ASC');
            $this->db->order_by('input.id ASC');
            $results=$this->db->get()->result_array();
            foreach($results as $result)
            {
                $data['system_jqx_items']['input_'.$result['input_id']]= array('text'=>$result['trial_name'].' - '.$result['input_name'],'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"150"','renderer'=>'header_render'));
            }
            for($i=1;$i<=SYSTEM_TRIAL_REPORT_MAX_CALCULATION;$i++)
            {
                if(strlen($data['report']['calc_name_'.$i])>0)
                {
                    $data['system_jqx_items']['calc_'.$i]= array('text'=>$data['report']['calc_name_'.$i],'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"150"','renderer'=>'header_render'));
                }
            }

            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#report_container','html'=>$this->load->view($this->controller_name.'/list',$data,true));
            $this->set_message($this->message,$ajax);
            $this->json_return($ajax);
        }
        else
        {
            $this->access_denied();
        }

    }
    public function system_get_items_list()
    {
        //$trial_id=5;
        $year=$this->input->post('year');
        $season_id=$this->input->post('season_id');
        $crop_id=$this->input->post('crop_id');
        $report_id=$this->input->post('report_id');
        $variety_ids=$this->input->post('variety_ids');
        $report_info=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_REPORT_INPUT_FIELDS,'*',array('report_id ='.$report_id,'crop_id ='.$crop_id),1);
        if(!$report_info)
        {
            $this->json_return(array());
        }
        $report_input_ids=array();
        $report_input_ids[0]=0;
        //from setup
        if(strlen($report_info['input_ids'])>2)
        {
            $input_ids=explode(',',trim($report_info['input_ids'],','));
            foreach($input_ids as $input_id)
            {
                $report_input_ids[$input_id]=$input_id;
            }
        }
        //from calculation
        for($i=1;$i<=SYSTEM_TRIAL_REPORT_MAX_CALCULATION;$i++)
        {
            if(strlen($report_info['calc_name_'.$i])>0)
            {
                preg_match_all('!\[(\d+)\]!', $report_info['calc_value_'.$i], $matches);
                foreach($matches[1] as $match)
                {
                    $report_input_ids[$match]=$match;
                }
            }
        }
        $this->db->from(TABLE_RND_SETUP_TRIAL_DATA_INPUT_FIELDS.' input');
        $this->db->select('input.*');
        $this->db->where_in('input.id',$report_input_ids);
        $results=$this->db->get()->result_array();
        $report_inputs=array();
        foreach($results as $result)
        {
            $report_inputs[$result['id']]=$result;
        }


        //varieties

        $this->db->from(TABLE_RND_VC_VARIETY_SELECTION.' vc');
        $this->db->select('vc.variety_index,vc.year,vc.status_replica');
        $this->db->join(TABLE_RND_SETUP_VARIETY.' variety','variety.id = vc.variety_id','INNER');
        $this->db->select('variety.name variety_name,variety.id variety_id');
        $this->db->join(TABLE_RND_SETUP_TYPE.' type','type.id = variety.type_id','INNER');
        $this->db->select('type.name type_name,type.code type_code');
        $this->db->join(TABLE_RND_SETUP_CROP.' crop','crop.id = type.crop_id','INNER');
        $this->db->select('crop.name crop_name,crop.code crop_code');

        $this->db->where('vc.year',$year);
        $this->db->where('vc.season_id',$season_id);
        $this->db->where_in('vc.variety_id',$variety_ids);
        $this->db->where('vc.status_selection',SYSTEM_STATUS_YES);
        $this->db->where('vc.status_delivery',SYSTEM_STATUS_YES);
        $this->db->where('vc.status_sowing',SYSTEM_STATUS_YES);

        $this->db->order_by('crop.ordering','ASC');
        $this->db->order_by('crop.id','ASC');
        $this->db->order_by('type.ordering','DESC');
        $this->db->order_by('type.id','ASC');
        $this->db->order_by('variety.ordering','ASC');
        $this->db->order_by('variety.id','ASC');
        $results=$this->db->get()->result_array();
        //initialize
        $varieties=array();
        foreach($results as $result)
        {
            $varieties[$result['variety_id']]=$result;
        }



        $items=array();

        foreach($varieties as $variety)
        {
            $item=array();
            $item['variety_id']=$variety['variety_id'];
            $item['rnd_code']=System_helper::get_variety_rnd_code($variety);
            $items[]=$item;
            if($variety['status_replica']==SYSTEM_STATUS_YES)
            {
                $item['variety_id']=$variety['variety_id'];
                $item['rnd_code']=System_helper::get_variety_rnd_code($variety,true);
                $items[]=$item;
            }
        }
        $this->json_return($items);
    }
}

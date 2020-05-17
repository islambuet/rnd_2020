<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vc_variety_selection extends Root_Controller
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
            $data['crop_name']= array('text'=>$this->lang->line('LABEL_CROP_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"150"','filtertype'=>'"list"'));
            $data['type_name']= array('text'=>$this->lang->line('LABEL_TYPE_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"150"','filtertype'=>'"list"','filteritems'=>'filter_items_type_name'));
            $data['variety_name']= array('text'=>$this->lang->line('LABEL_VARIETY_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"150"'));
            $data['rnd_code']= array('text'=>$this->lang->line('LABEL_RND_CODE'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"150"'));
            $data['number_of_seasons']= array('text'=>$this->lang->line('LABEL_NUMBER_OF_SEASONS'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"150"'));
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
            if(!($year>0))
            {
                $year=$this->input->post('year');
            }
            if(!$year)
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
        $this->db->select('vc.variety_index,vc.year');
        $this->db->select('SUM(CASE WHEN vc.status_selection="'.SYSTEM_STATUS_YES.'" then 1 ELSE 0 END) number_of_seasons',false);

        $this->db->join(TABLE_RND_SETUP_VARIETY.' variety','variety.id = vc.variety_id','INNER');
        $this->db->select('variety.name variety_name,variety.id id');
        $this->db->join(TABLE_RND_SETUP_TYPE.' type','type.id = variety.type_id','INNER');
        $this->db->select('type.name type_name,type.code type_code');
        $this->db->join(TABLE_RND_SETUP_CROP.' crop','crop.id = type.crop_id','INNER');
        $this->db->select('crop.name crop_name,crop.code crop_code');

        $this->db->where('vc.year',$year);
        $this->db->group_by('variety.id');
        $this->db->order_by('vc.id','DESC');

        $items=$this->db->get()->result_array();
        foreach($items as &$item)
        {
            $item['rnd_code']=System_helper::get_variety_rnd_code($item);
        }

        $this->json_return($items);
    }
    public function system_search_add($year)
    {
        if(isset($this->permissions['action1']) && ($this->permissions['action1']==1))
        {
            $data['item']=array();
            $data['year']=$year;
            $data['crops']=Query_helper::get_info(TABLE_RND_SETUP_CROP,array('id value','name text'),array('status !="'.SYSTEM_STATUS_DELETE.'"'));
            $data['types']=array();
            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#system_content','html'=>$this->load->view($this->controller_name.'/search_add',$data,true));
            $this->set_message($this->message,$ajax);
            $ajax['system_page_url']=site_url($this->controller_name.'/system_search_add/'.$year);
            $this->json_return($ajax);
        }
        else
        {
            $this->access_denied();
        }
    }
    public function system_get_drop_down_new_variety_list()
    {
        $type_id = $this->input->post('type_id');
        $year = $this->input->post('year');

        $this->db->from(TABLE_RND_VC_VARIETY_SELECTION.' vc');
        $this->db->select('DISTINCT(vc.variety_id) variety_id');
        $this->db->where('vc.year',$year);
        $results=$this->db->get()->result_array();
        $selected_variety_ids=array();
        $selected_variety_ids[0]=0;
        foreach($results as $result)
        {
            $selected_variety_ids[$result['variety_id']]=$result['variety_id'];
        }
        $this->db->from(TABLE_RND_SETUP_VARIETY.' variety');
        $this->db->select('variety.id value,variety.name text');
        $this->db->where('variety.status',SYSTEM_STATUS_ACTIVE);
        $this->db->where('variety.type_id',$type_id);
        $this->db->where_not_in('variety.id',$selected_variety_ids);
        $this->db->order_by('variety.ordering','ASC');
        $this->db->order_by('variety.id','ASC');
        $data['items']=$this->db->get()->result_array();
        $ajax['status']=true;
        $ajax['system_content'][]=array("id"=>'#variety_id',"html"=>$this->load->view("drop_down_with_select",$data,true));
        $this->json_return($ajax);
    }
    public function system_add()
    {
        if(isset($this->permissions['action1']) && ($this->permissions['action1']==1))
        {
            $year=$this->input->post('year');
            $item_id=$this->input->post('variety_id');
            $results=Query_helper::get_info(TABLE_RND_VC_VARIETY_SELECTION,'*',array('year ='.$year,'variety_id ='.$item_id));
            if($results)
            {
                $this->action_error($this->lang->line("MSG_VARIETY_ADDED_ALREADY"));
            }

            $this->db->from(TABLE_RND_SETUP_VARIETY.' variety');
            $this->db->select('variety.*');
            $this->db->join(TABLE_RND_SETUP_TYPE.' type','type.id = variety.type_id','INNER');
            $this->db->select('type.name type_name,type.expected_seed_per_gram');
            $this->db->join(TABLE_RND_SETUP_CROP.' crop','crop.id = type.crop_id','INNER');
            $this->db->select('crop.name crop_name,crop.status_replica,crop.length,crop.width,crop.plants_initial,crop.optimum_transplanting_days');

            $this->db->join(TABLE_RND_SETUP_PRINCIPAL.' principal','principal.id = variety.principal_id','LEFT');
            $this->db->select('principal.name principal_name');
            $this->db->join(TABLE_RND_SETUP_COMPETITOR.' competitor','competitor.id = variety.competitor_id','LEFT');
            $this->db->select('competitor.name competitor_name');

            $this->db->where('variety.status !=',SYSTEM_STATUS_DELETE);
            $this->db->where('variety.id',$item_id);
            $data['item']=$this->db->get()->row_array();
            if(!$data['item'])
            {
                $this->action_error($this->lang->line("MSG_INVALID_ITEM"));
            }
            $data['selected_seasons']=array();
            $data['year']=$year;
            $data['seasons']=Query_helper::get_info(TABLE_RND_SETUP_SEASON,array('id value','name text'),array('status !="'.SYSTEM_STATUS_DELETE.'"'));

            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#report_container','html'=>$this->load->view($this->controller_name.'/add_edit',$data,true));
            $this->set_message($this->message,$ajax);
            $this->json_return($ajax);
        }
        else
        {
            $this->access_denied();
        }
    }
    public function system_edit($year,$id=0)
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
            $this->db->from(TABLE_RND_SETUP_VARIETY.' variety');
            $this->db->select('variety.*');
            $this->db->join(TABLE_RND_SETUP_TYPE.' type','type.id = variety.type_id','INNER');
            $this->db->select('type.name type_name,type.expected_seed_per_gram');
            $this->db->join(TABLE_RND_SETUP_CROP.' crop','crop.id = type.crop_id','INNER');
            $this->db->select('crop.name crop_name,crop.status_replica,crop.length,crop.width,crop.plants_initial,crop.optimum_transplanting_days');

            $this->db->join(TABLE_RND_SETUP_PRINCIPAL.' principal','principal.id = variety.principal_id','LEFT');
            $this->db->select('principal.name principal_name');
            $this->db->join(TABLE_RND_SETUP_COMPETITOR.' competitor','competitor.id = variety.competitor_id','LEFT');
            $this->db->select('competitor.name competitor_name');

            $this->db->where('variety.status !=',SYSTEM_STATUS_DELETE);
            $this->db->where('variety.id',$item_id);
            $data['item']=$this->db->get()->row_array();
            if(!$data['item'])
            {
                $this->action_error($this->lang->line("MSG_INVALID_ITEM"));
            }
            $data['selected_seasons']=array();
            $results=Query_helper::get_info(TABLE_RND_VC_VARIETY_SELECTION,'*',array('year ='.$year,'variety_id ='.$item_id));
            foreach($results as $result)
            {
                $data['selected_seasons'][$result['season_id']]=$result;
            }
            $data['year']=$year;
            $data['seasons']=Query_helper::get_info(TABLE_RND_SETUP_SEASON,array('id value','name text'),array('status !="'.SYSTEM_STATUS_DELETE.'"'));

            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#system_content','html'=>$this->load->view($this->controller_name.'/add_edit',$data,true));
            $this->set_message($this->message,$ajax);
            $ajax['system_page_url']=site_url($this->controller_name.'/system_edit/'.$year.'/'.$item_id);
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
        $variety_id=$this->input->post('id');
        $year=$this->input->post('year');
        $system_user_token = $this->input->post("system_user_token");

        $selected_seasons=$this->input->post('selected_seasons');

        $variety_index=0;
        $selected_seasons_old=array();
        $results=Query_helper::get_info(TABLE_RND_VC_VARIETY_SELECTION,'*',array('year ='.$year,'variety_id ='.$variety_id));
        foreach($results as $result)
        {
            $variety_index=$result['variety_index'];
            $selected_seasons_old[$result['season_id']]=$result;
        }
        $this->db->from(TABLE_RND_SETUP_VARIETY.' variety');
        $this->db->select('variety.*');
        $this->db->join(TABLE_RND_SETUP_TYPE.' type','type.id = variety.type_id','INNER');
        $this->db->select('type.crop_id');
        $this->db->where('variety.id',$variety_id);
        $item=$this->db->get()->row_array();
        if(!$item)
        {
            $this->action_error($this->lang->line("MSG_INVALID_ITEM"));
        }

        if(sizeof($selected_seasons_old)>0)
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
            $this->db->from(TABLE_RND_VC_VARIETY_SELECTION.' vc');
            $this->db->select('COUNT(DISTINCT(vc.variety_id)) total_variety');
            $this->db->join(TABLE_RND_SETUP_VARIETY.' variety','variety.id = vc.variety_id','INNER');
            $this->db->join(TABLE_RND_SETUP_TYPE.' type','type.id = variety.type_id','INNER');
            $this->db->where('type.crop_id',$item['crop_id']);
            $this->db->where('vc.year',$year);
            $result=$this->db->get()->row_array();
            $variety_index=$result['total_variety']+1;

        }
        $system_user_token_info = Token_helper::get_token($system_user_token);
        if($system_user_token_info['status'])
        {
            $this->message['system_message']=$this->lang->line('MSG_SAVE_ALREADY');
            $this->system_list($year);
        }
        $this->db->trans_start(); //DB Transaction Handle START

        foreach($selected_seasons as $selected_season)
        {
            if(isset($selected_season['season_id']))
            {
                if(isset($selected_seasons_old[$selected_season['season_id']]))
                {
                    $data_new=array();
                    $data_old=array();
                    $selected_season['status_selection']=SYSTEM_STATUS_YES;
                    foreach($selected_season as $key=>$value)
                    {
                        if($value!= $selected_seasons_old[$selected_season['season_id']][$key])
                        {
                            $data_new[$key]=$value;
                            $data_old[$key]=$selected_seasons_old[$selected_season['season_id']][$key];
                        }
                    }
                    if($data_new)
                    {
                        $selected_season['date_selection_updated']=$time;
                        $selected_season['user_selection_updated']=$user->id;
                        Query_helper::update(TABLE_RND_VC_VARIETY_SELECTION,$selected_season,array('id ='.$selected_seasons_old[$selected_season['season_id']]['id']));
                        System_helper::history_save(TABLE_RND_VC_VARIETY_SELECTION_HISTORY,$selected_seasons_old[$selected_season['season_id']]['id'],$data_old,$data_new);
                    }
                    unset($selected_seasons_old[$selected_season['season_id']]);

                }
                else
                {
                    $selected_season['variety_index']=$variety_index;
                    $selected_season['year']=$year;
                    $selected_season['variety_id']=$variety_id;
                    $selected_season['status_selection']=SYSTEM_STATUS_YES;
                    $selected_season['date_selection_created']=$time;
                    $selected_season['user_selection_created']=$user->id;
                    Query_helper::add(TABLE_RND_VC_VARIETY_SELECTION,$selected_season,false);

                }
            }
        }
        foreach($selected_seasons_old as $season_old)
        {
            if($season_old['status_selection']==SYSTEM_STATUS_YES)
            {
                //TODO maybe reset delivery and sowing status no
                $data=array();
                $data['status_selection']=SYSTEM_STATUS_NO;
                $data['date_selection_updated']=$time;
                $data['user_selection_updated']=$user->id;

                Query_helper::update(TABLE_RND_VC_VARIETY_SELECTION,$data,array('id ='.$season_old['id']));
                System_helper::history_save(TABLE_RND_VC_VARIETY_SELECTION_HISTORY,$season_old['id'],array('status_selection'=>SYSTEM_STATUS_YES),array('status_selection'=>SYSTEM_STATUS_NO));
            }


        }
        Token_helper::update_token($system_user_token_info['id'], $system_user_token);

        $this->db->trans_complete(); //DB Transaction Handle END
        if ($this->db->trans_status()===true)
        {
            $save_and_new=$this->input->post('system_save_new_status');
            $this->message['system_message']=$this->lang->line('MSG_SAVE_DONE');
            if($save_and_new==1)
            {
                $this->system_search_add($year);
            }
            else
            {
                $this->system_list($year);
            }
        }
        else
        {
            $this->action_error($this->lang->line("MSG_SAVE_FAIL"));
        }
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trial_data_entry extends Root_Controller
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
        $this->lang->load($this->controller_name.'/'.$this->controller_name);
    }
    public function index($trial_id)
    {
        $this->system_list($trial_id);
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
        $user=User_helper::get_user();
        $data=array();
        if($method=='system_list')
        {
            $data['variety_id']= array('text'=>$this->lang->line('LABEL_VARIETY_ID'),'type'=>'number','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"50"','cellsAlign'=>'"right"'));
            $data['crop_name']= array('text'=>$this->lang->line('LABEL_CROP_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"150"','filtertype'=>'"list"'));
            $data['type_name']= array('text'=>$this->lang->line('LABEL_TYPE_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"150"','filtertype'=>'"list"','filteritems'=>'filter_items_type_name'));
            $data['variety_name']= array('text'=>$this->lang->line('LABEL_VARIETY_NAME'),'type'=>'string','preference'=>1,'jqx_column'=>($user->show_variety==SYSTEM_STATUS_YES?true:false),'column_attributes'=>array('width'=>'"150"'));
            $data['rnd_code']= array('text'=>$this->lang->line('LABEL_RND_CODE'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"150"'));
            $data['status_data_entered']= array('text'=>$this->lang->line('LABEL_STATUS_DATA_ENTERED'),'type'=>'string','preference'=>1,'jqx_column'=>true,'column_attributes'=>array('width'=>'"70"','filtertype'=>'"list"'));
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
    public function system_list($trial_id,$year=0,$season_id=0)
    {
        $user=User_helper::get_user();
        if(strpos($user->trial_data, ','.$trial_id.',') !== FALSE)
        {
            if(!($year>0))
            {
                $year=$this->input->post('year');
            }
            if(!$year)
            {
                $year=date("Y");
            }

            if(!($season_id>0))
            {
                $season_id=$this->input->post('season_id');
            }
            if(!$season_id)
            {
                $season_id=Season_helper::get_current_season()['id'];
            }
            $data['year']=$year;
            $data['season_id']=$season_id;
            $data['trial_id']=$trial_id;
            $data['seasons']=Season_helper::get_all_seasons();
            $data['trial']=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_DATA,'*',array('id ='.$trial_id),1,0);

            $user = User_helper::get_user();
            $method='system_list';
            $data['system_jqx_items']= System_helper::get_preference($user->id, $this->controller_name, $method, $this->get_jqx_items($method));
            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#system_content','html'=>$this->load->view($this->controller_name.'/list',$data,true));
            $this->set_message($this->message,$ajax);
            $ajax['system_page_url']=site_url($this->controller_name.'/system_list/'.$trial_id.'/'.$year.'/'.$season_id);
            $this->json_return($ajax);
        }
        else
        {
            $this->access_denied();
        }
    }
    public function system_get_items_list($trial_id,$year,$season_id)
    {
        $user=User_helper::get_user();
        $this->db->from(TABLE_RND_TRIAL_DATA.' data');
        $this->db->select('data.id,data.variety_id');
        $this->db->where('data.year',$year);
        $this->db->where('data.season_id',$season_id);
        $this->db->where('data.trial_id',$trial_id);
        $results=$this->db->get()->result_array();
        $data_trial=array();
        foreach($results as $result)
        {
            $data_trial[$result['variety_id']]=$result['id'];
        }



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
            if(isset($data_trial[$item['variety_id']]))
            {
                $item['status_data_entered']=SYSTEM_STATUS_YES;
            }
            else
            {
                $item['status_data_entered']=SYSTEM_STATUS_NO;
            }
        }

        $this->json_return($items);
    }

    public function system_edit($trial_id,$year,$season_id,$variety_id=0)
    {
        $user=User_helper::get_user();
        if(strpos($user->trial_data, ','.$trial_id.',') !== FALSE)
        {
            if(!($variety_id>0))
            {
                $variety_id=$this->input->post('variety_id');
            }
            $this->db->from(TABLE_RND_VC_VARIETY_SELECTION.' vc');
            $this->db->select('vc.variety_index,vc.year,vc.status_replica');
            $this->db->join(TABLE_RND_SETUP_VARIETY.' variety','variety.id = vc.variety_id','INNER');
            $this->db->select('variety.name variety_name,variety.id variety_id');
            $this->db->join(TABLE_RND_SETUP_TYPE.' type','type.id = variety.type_id','INNER');
            $this->db->select('type.name type_name,type.code type_code');
            $this->db->join(TABLE_RND_SETUP_CROP.' crop','crop.id = type.crop_id','INNER');
            $this->db->select('crop.name crop_name,crop.code crop_code,crop.id crop_id');

            $this->db->where('vc.year',$year);
            $this->db->where('vc.season_id',$season_id);
            $this->db->where('variety.id',$variety_id);
            $this->db->where('vc.status_selection',SYSTEM_STATUS_YES);
            $this->db->where('vc.status_delivery',SYSTEM_STATUS_YES);
            $this->db->where('vc.status_sowing',SYSTEM_STATUS_YES);
            if(strlen($user->crop_ids)>2)
            {
                $this->db->where_in('crop.id',explode(',',trim($user->crop_ids, ",")));
            }
            $data['item']=$this->db->get()->row_array();
            if(!$data['item'])
            {
                $this->action_error($this->lang->line("MSG_INVALID_ITEM"));
            }
            $data['year']=$year;
            $data['trial_id']=$trial_id;
            $data['season_id']=$season_id;
            $data['trial']=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_DATA,'*',array('id ='.$trial_id),1,0);
            $data['season']=Query_helper::get_info(TABLE_RND_SETUP_SEASON,'*',array('id ='.$trial_id),1,0);
            $data['trial_input_fields']=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_DATA_INPUT_FIELDS,'*',array('trial_id ='.$trial_id,'crop_id ='.$data['item']['crop_id'],'status ="'.SYSTEM_STATUS_ACTIVE.'"'),0,0,array('ordering ASC','id ASC'));
            $data['trial_data']=Query_helper::get_info(TABLE_RND_TRIAL_DATA,'*',array('year ='.$year,'season_id ='.$season_id,'trial_id ='.$trial_id,'variety_id ='.$variety_id),1,0);
            if($data['trial_data'])
            {
                $data['trial_data']['normal']=json_decode($data['trial_data']['trial_normal'],true);
                $data['trial_data']['replica']=json_decode($data['trial_data']['trial_replica'],true);
            }
            $ajax['status']=true;
            $ajax['system_content'][]=array('id'=>'#system_content','html'=>$this->load->view($this->controller_name.'/add_edit',$data,true));
            $this->set_message($this->message,$ajax);
            $ajax['system_page_url']=site_url($this->controller_name.'/system_edit/'.$trial_id.'/'.$year.'/'.$season_id.'/'.$variety_id);
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

        $trial_id=$this->input->post('trial_id');
        $year=$this->input->post('year');
        $season_id=$this->input->post('season_id');
        $variety_id=$this->input->post('variety_id');
        $trial_data=$this->input->post('trial_data');
        $system_user_token = $this->input->post("system_user_token");
        $data_normal=array();
        $data_replica=array();

        $this->db->from(TABLE_RND_VC_VARIETY_SELECTION.' vc');
        $this->db->select('vc.variety_index,vc.year,vc.status_replica');
        $this->db->join(TABLE_RND_SETUP_VARIETY.' variety','variety.id = vc.variety_id','INNER');
        $this->db->select('variety.name variety_name,variety.id variety_id');
        $this->db->join(TABLE_RND_SETUP_TYPE.' type','type.id = variety.type_id','INNER');
        $this->db->select('type.name type_name,type.code type_code');
        $this->db->join(TABLE_RND_SETUP_CROP.' crop','crop.id = type.crop_id','INNER');
        $this->db->select('crop.name crop_name,crop.code crop_code,crop.id crop_id');

        $this->db->where('vc.year',$year);
        $this->db->where('vc.season_id',$season_id);
        $this->db->where('variety.id',$variety_id);
        $this->db->where('vc.status_selection',SYSTEM_STATUS_YES);
        $this->db->where('vc.status_delivery',SYSTEM_STATUS_YES);
        $this->db->where('vc.status_sowing',SYSTEM_STATUS_YES);
        if(strlen($user->crop_ids)>2)
        {
            $this->db->where_in('crop.id',explode(',',trim($user->crop_ids, ",")));
        }
        $item=$this->db->get()->row_array();
        if(!$item)
        {
            $this->action_error($this->lang->line("MSG_INVALID_ITEM"));
        }
        $current_normal=array();
        $current_replica=array();
        $trial_data_current=Query_helper::get_info(TABLE_RND_TRIAL_DATA,'*',array('year ='.$year,'season_id ='.$season_id,'trial_id ='.$trial_id,'variety_id ='.$variety_id),1,0);
        if($trial_data_current)
        {
            $current_normal=json_decode($trial_data_current['trial_normal'],true);
            $current_replica=json_decode($trial_data_current['trial_replica'],true);
        }



        $uploaded_images = Upload_helper::upload_file("images/trial_data/".$year.'/'.$season_id.'/'.$trial_id);
        $trial_input_fields=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_DATA_INPUT_FIELDS,'*',array('trial_id ='.$trial_id,'crop_id ='.$item['crop_id']),0,0,array('ordering ASC','id ASC'));

        $data['trial_id']=$trial_id;
        $data['year']=$year;
        $data['season_id']=$season_id;
        $data['variety_id']=$variety_id;

        foreach($trial_input_fields as $input_field)
        {
            if($input_field['type']==SYSTEM_INPUT_TYPE_IMAGE)
            {
                if($trial_data_current)//load first item
                {
                    if(isset($current_normal[$input_field['id']]))
                    {
                        $data_normal[$input_field['id']]=$current_normal[$input_field['id']];
                    }
                    if(isset($current_replica[$input_field['id']]))
                    {
                        $data_replica[$input_field['id']]=$current_replica[$input_field['id']];
                    }

                }
                $normal_uploaded=false;
                $replica_uploaded=false;
                if(array_key_exists('trial_data_image_normal_'.$input_field['id'],$uploaded_images))
                {
                    if($uploaded_images['trial_data_image_normal_'.$input_field['id']]['status'])
                    {
                        $normal_uploaded=true;
                        $data_normal[$input_field['id']]="images/trial_data/".$year.'/'.$season_id.'/'.$trial_id.'/'.$uploaded_images['trial_data_image_normal_'.$input_field['id']]['info']['file_name'];
                    }
                    else
                    {
                        $this->action_error(strip_tags($uploaded_images['trial_data_image_normal_'.$input_field['id']]['message']));
                    }
                }
                if($item['status_replica']==SYSTEM_STATUS_YES)
                {
                    if(array_key_exists('trial_data_image_replica_'.$input_field['id'],$uploaded_images))
                    {
                        if($uploaded_images['trial_data_image_replica_'.$input_field['id']]['status'])
                        {
                            $replica_uploaded=true;
                            $data_replica[$input_field['id']]="images/trial_data/".$year.'/'.$season_id.'/'.$trial_id.'/'.$uploaded_images['trial_data_image_replica_'.$input_field['id']]['info']['file_name'];
                        }
                        else
                        {
                            $this->action_error(strip_tags($uploaded_images['trial_data_image_replica_'.$input_field['id']]['message']));
                        }
                    }
                }
                if($input_field['mandatory']==SYSTEM_STATUS_YES)
                {
                    if(!$trial_data_current)
                    {
                        if(!$normal_uploaded)
                        {
                            $this->action_error(sprintf($this->lang->line("MSG_NORMAL_INPUT_REQUIRED"),$input_field['name']));
                        }
                        if($item['status_replica']==SYSTEM_STATUS_YES)
                        {
                            if(!$replica_uploaded)
                            {
                                $this->action_error(sprintf($this->lang->line("MSG_REPLICA_INPUT_REQUIRED"),$input_field['name']));
                            }
                        }
                    }
                }
            }
            else
            {
                if($input_field['mandatory']==SYSTEM_STATUS_YES)
                {
                    if($input_field['type']!=SYSTEM_INPUT_TYPE_IMAGE)
                    {
                        if(!$trial_data['normal'][$input_field['id']])
                        {
                            $this->action_error(sprintf($this->lang->line("MSG_NORMAL_INPUT_REQUIRED"),$input_field['name']));
                        }
                        if($item['status_replica']==SYSTEM_STATUS_YES)
                        {
                            if(!$trial_data['replica'][$input_field['id']])
                            {
                                $this->action_error(sprintf($this->lang->line("MSG_REPLICA_INPUT_REQUIRED"),$input_field['name']));
                            }
                        }
                    }

                }
                $data_normal[$input_field['id']]=$trial_data['normal'][$input_field['id']];
                if($item['status_replica']==SYSTEM_STATUS_YES)
                {
                    $data_replica[$input_field['id']]=$trial_data['replica'][$input_field['id']];
                }
            }
        }
        $data['trial_normal']=json_encode($data_normal,JSON_FORCE_OBJECT);
        $data['trial_replica']=json_encode($data_replica,JSON_FORCE_OBJECT);


        $system_user_token_info = Token_helper::get_token($system_user_token);
        if($system_user_token_info['status'])
        {
            $this->message['system_message']=$this->lang->line('MSG_SAVE_ALREADY');
            $this->system_list($trial_id,$year,$season_id);
        }
        $this->db->trans_start(); //DB Transaction Handle START
        if($trial_data_current)
        {
            $data['date_updated']=$time;
            $data['user_updated']=$user->id;
            Query_helper::update(TABLE_RND_TRIAL_DATA,$data,array('id ='.$trial_data_current['id']),false);

            $data_current=array();
            $data_new=array();
            $data_new['trial_normal']=$data['trial_normal'];
            $data_current['trial_normal']=$trial_data_current['trial_normal'];
            if($item['status_replica']==SYSTEM_STATUS_YES)
            {
                $data_new['trial_replica']=$data['trial_replica'];
                $data_current['trial_replica']=$trial_data_current['trial_replica'];
            }

            System_helper::history_save(TABLE_RND_TRIAL_DATA_HISTORY,$trial_data_current['id'],$data_current,$data_new);
        }
        else
        {
            $data['date_created']=$time;
            $data['user_created']=$user->id;
            Query_helper::add(TABLE_RND_TRIAL_DATA,$data,false);
        }



        Token_helper::update_token($system_user_token_info['id'], $system_user_token);

        $this->db->trans_complete(); //DB Transaction Handle END
        if ($this->db->trans_status()===true)
        {
            $save_and_new=$this->input->post('system_save_new_status');
            $this->message['system_message']=$this->lang->line('MSG_SAVE_DONE');
            $this->system_list($trial_id,$year,$season_id);

        }
        else
        {
            $this->action_error($this->lang->line("MSG_SAVE_FAIL"));
        }
    }
}

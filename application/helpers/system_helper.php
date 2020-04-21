<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class System_helper
{
    public static function display_date($time)
    {
        if(is_numeric($time))
        {
            return date('d-M-Y',$time);
        }
        else
        {
            return '';
        }
    }
    public static function display_date_time($time)
    {
        if(is_numeric($time))
        {
            return date('d-M-Y h:i:s A',$time);
        }
        else
        {
            return '';
        }
    }
    public static function get_time($str)
    {
        $time=strtotime($str);
        if($time===false)
        {
            return 0;
        }
        else
        {
            return $time;
        }
    }
    //getting preference
    public static function get_preference($user_id,$controller,$method,$headers)
    {
        $result=Query_helper::get_info(TABLE_SYSTEM_USER_PREFERENCE,'*',array('user_id ='.$user_id,'controller ="' .$controller.'"','method ="'.$method.'"'),1);
        $data=$headers;
        if($result)
        {
            if($result['preferences']!=null)
            {
                $preferences=json_decode($result['preferences'],true);
                foreach($data as $key=>$value)
                {
                    if(isset($preferences[$key]))
                    {
                        //$data[$key]=$value;
                        $data[$key]['preference']=$preferences[$key];//should be value of set
                    }
                    else
                    {
                        $data[$key]['preference']=0;
                    }
                }
            }
        }
        return $data;
    }
    //loading preference task
    public static function system_preference($method)
    {
        $CI =& get_instance();
        $user = User_helper::get_user();
        if(isset($CI->permissions['action6']) && ($CI->permissions['action6']==1))
        {
            $data['system_jqx_items']= System_helper::get_preference($user->user_id, $CI->controller_url, $method, $CI->get_jqx_items($method));
            $data['return_method']=$method;
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$CI->load->view("preference_add_edit",$data,true));
            $ajax['system_page_url']=site_url($CI->controller_url.'/system_preference/'.$method);
            $CI->json_return($ajax);
        }
        else
        {
            $CI->access_denied();
        }

    }
    //saving preference
    public static function save_preference()
    {
        $CI =& get_instance();

        $return_method=$CI->input->post('return_method');
        $user = User_helper::get_user();
        if(!(isset($CI->permissions['action6']) && ($CI->permissions['action6']==1)))
        {
            $CI->access_denied();
            die();
        }
        else
        {
            $preference_items=$CI->input->post('preference_items');
            if(!$preference_items)
            {
                $ajax['status']=false;
                $CI->set_message(array('system_message'=>$CI->lang->line("ALERT_SELECT_ONE_ITEM"),'system_message_type'=>'error'),$ajax);
                $CI->json_return($ajax);
                die();
            }

            $time=time();
            $CI->db->trans_start();  //DB Transaction Handle START
            $result=Query_helper::get_info(TABLE_SYSTEM_USER_PREFERENCE,'*',array('user_id ='.$user->user_id,'controller ="' .$CI->controller_url.'"','method ="'.$return_method.'"'),1);
            if($result)
            {
                $data['user_updated']=$user->user_id;
                $data['date_updated']=$time;
                $data['preferences']=json_encode($preference_items);
                Query_helper::update(TABLE_SYSTEM_USER_PREFERENCE,$data,array('id='.$result['id']),false);
            }
            else
            {
                $data['user_id']=$user->user_id;
                $data['controller']=$CI->controller_url;
                $data['method']="$return_method";
                $data['user_created']=$user->user_id;
                $data['date_created']=$time;
                $data['preferences']=json_encode($preference_items);
                Query_helper::add(TABLE_SYSTEM_USER_PREFERENCE,$data,false);
            }

            $CI->db->trans_complete();   //DB Transaction Handle END
            $ajax['status']=true;
            if ($CI->db->trans_status() === TRUE)
            {
                $CI->message['system_message']=$CI->lang->line("MSG_SAVE_DONE");
                if(method_exists($CI,$return_method))
                {
                    $CI->$return_method();
                }
                else
                {
                    $CI->index();
                }

            }
            else
            {
                $ajax['status']=false;
                $CI->set_message(array('system_message'=>$CI->lang->line("MSG_SAVE_FAIL"),'system_message_type'=>'error'),$ajax);
                $CI->json_return($ajax);
            }
        }
    }

    /*public static function invalid_try($action='',$action_id='',$other_info='')
    {
        $CI =& get_instance();
        $user = User_helper::get_user();
        $time=time();
        $data=array();
        $data['user_id']=$user->user_id;
        $data['controller']=$CI->router->class;
        $data['action']=$action;
        $data['action_id']=$action_id;
        $data['other_info']=$other_info;
        $data['date_created']=$time;
        $data['date_created_string']=System_helper::display_date_time($time);
        $CI->db->insert($CI->config->item('table_system_history_hack'), $data);
    }


    public static function get_users_info($user_ids)
    {
        //can be upgrade select field from user_info
        //but no more join query
        $CI =& get_instance();
        $CI->db->from($CI->config->item('table_login_setup_user').' user');
        $CI->db->select('user.id,user.employee_id,user.user_name,user.status');
        $CI->db->join($CI->config->item('table_login_setup_user_info').' user_info','user.id = user_info.user_id','INNER');
        $CI->db->select('user_info.name,user_info.ordering,user_info.blood_group,user_info.mobile_no');
        $CI->db->where('user_info.revision',1);
        if(sizeof($user_ids)>0)
        {
            $CI->db->where_in('user.id',$user_ids);
        }
        $results=$CI->db->get()->result_array();
        $users=array();
        foreach($results as $result)
        {
            $users[$result['id']]=$result;
        }
        return $users;
    }    */
}

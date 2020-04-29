<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    public function index()
    {
//        $this->load->helper('mobile_sms');
//        echo '<pre>';
//        print_r(Mobile_sms_helper::send_sms(Mobile_sms_helper::$API_SENDER_ID_MALIK_SEEDS,'01713090962','Your mobile verification code for login: 285683'));
//        echo '</pre>';
//        $this->session->set_userdata("user_id", 2);

        $this->load->helper('module_task');
        $modules_tasks=Module_task_helper::get_modules_tasks_table_tree();
        echo '<pre>';
        print_r($modules_tasks);
        echo '</pre>';
    }
}

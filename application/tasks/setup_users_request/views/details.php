<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI=& get_instance();
$user=User_helper::get_user();
$action_buttons=array();
$action_buttons[]=array(
    'label'=>$CI->lang->line("BUTTON_LIST_PENDING"),
    'class'=>'system_ajax',
    'href'=>site_url($CI->controller_name)
);
$action_buttons[]=array(
    'label'=>$CI->lang->line("BUTTON_LIST_ALL"),
    'class'=>'system_ajax',
    'href'=>site_url($CI->controller_name.'/system_list_all')
);
$action_buttons[]=array(
    'label'=>$CI->lang->line("BUTTON_REFRESH"),
    'class'=>'system_ajax',
    'href'=>site_url($CI->controller_name.'/system_details/'.$item['id'])
);

$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));
?>
<div class="card mt-2">
    <div class="card-header font-weight-bold">
        <?php
        echo sprintf($CI->lang->line('LABEL_TITLE_DETAILS'),$item['name']);
        ?>
    </div>
    <div class="card-body">
        <?php
        echo $CI->load->view("info",array('accordion'=>array('header'=>$CI->lang->line('LABEL_TITLE_INFO_MODERATION'),'div_id'=>'accordion_info','show'=>true,'data'=>$moderation_info)),true);
        ?>
    </div>
</div>

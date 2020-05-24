<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI=& get_instance();
$user=User_helper::get_user();
$action_buttons=array();
$action_buttons[]=array(
    'label'=>$CI->lang->line("BUTTON_CANCEL"),
    'class'=>'system_ajax',
    'href'=>site_url($CI->controller_name)
);
$action_buttons[]=array(
    'type'=>'button',
    'label'=>$CI->lang->line("BUTTON_SAVE"),
    'class'=>'button_action_save',
    'data-target-element'=>'#save_form'
);
$action_buttons[]=array(
    'type'=>'button',
    'label'=>$CI->lang->line("BUTTON_CLEAR"),
    'class'=>'button_action_clear',
    'data-target-element'=>'#save_form'
);
$action_buttons[]=array(
    'label'=>$CI->lang->line("BUTTON_REFRESH"),
    'class'=>'system_ajax',
    'href'=>site_url($CI->controller_name.'/system_trial_data/'.$item['id'])
);
$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));

?>
<div class="card mt-2">
    <div class="card-header font-weight-bold">

        <?php echo sprintf($CI->lang->line('LABEL_TITLE_USER_GROUP_TRIAL_DATA'),$item['name']);?>
    </div>
    <div class="card-body">
        <form id="save_form" class="system_ajax" action="<?php echo site_url($CI->controller_name.'/system_save_trial_data');?>" method="post">
            <input type="hidden" id="id" name="id" value="<?php echo $item['id']; ?>" />
            <input type="hidden" id="system_save_new_status" name="system_save_new_status" value="0" />
            <input type="hidden" id="system_user_token" name="system_user_token" value="<?php echo time().'_'.$user->id; ?>" />

            <div class="card">
                <div class="card-header">
                    <a class="btn-link" data-toggle="collapse" href="#LABEL_TITLE_TRIAL_DATA">
                        <?php echo $CI->lang->line('LABEL_TITLE_TRIAL_DATA');?>
                    </a>
                </div>
                <div id="LABEL_TITLE_TRIAL_DATA" class="collapse">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 text-center pt-2 pb-2" style="border: 1px solid rgba(0,0,0,.125);">
                                <label><input type="checkbox" data-type='trial_data' class="select_all"></label>
                            </div>
                            <div class="col-6 pt-2 pb-2" style="border: 1px solid rgba(0,0,0,.125);">
                                <?php echo $CI->lang->line("LABEL_TITLE_TRIAL_DATA"); ?>
                            </div>
                        </div>
                        <?php

                        foreach($trial_data as $trial)
                        {
                            ?>
                            <div class="row" >
                                <div class="col-6 text-center pt-2 pb-2" style="border: 1px solid rgba(0,0,0,.125);">
                                    <input type="checkbox" class="trial_data"  <?php if(strpos($item['trial_data'], ','.$trial['id'].',') !== FALSE){echo 'checked';}?> value="<?php echo $trial['id']; ?>" name='trial_data[]'>
                                </div>
                                <div class="col-6 pt-2 pb-2" style="border: 1px solid rgba(0,0,0,.125);">
                                    <label><?php echo $trial['name'];?></label>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <a class="btn-link" data-toggle="collapse" href="#LABEL_TITLE_TRIAL_REPORT">
                        <?php echo $CI->lang->line('LABEL_TITLE_TRIAL_REPORT');?>
                    </a>
                </div>
                <div id="LABEL_TITLE_TRIAL_REPORT" class="collapse">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 text-center pt-2 pb-2" style="border: 1px solid rgba(0,0,0,.125);">
                                <label><input type="checkbox" data-type='trial_report' class="select_all"></label>
                            </div>
                            <div class="col-6 pt-2 pb-2" style="border: 1px solid rgba(0,0,0,.125);">
                                <?php echo $CI->lang->line("LABEL_TITLE_TRIAL_REPORT"); ?>
                            </div>
                        </div>
                        <?php

                        foreach($trial_report as $report)
                        {
                            ?>
                            <div class="row" >
                                <div class="col-6 text-center pt-2 pb-2" style="border: 1px solid rgba(0,0,0,.125);">
                                    <input type="checkbox" class="trial_report"  <?php if(strpos($item['trial_report'], ','.$report['id'].',') !== FALSE){echo 'checked';}?> value="<?php echo $report['id']; ?>" name='trial_report[]'>
                                </div>
                                <div class="col-6 pt-2 pb-2" style="border: 1px solid rgba(0,0,0,.125);">
                                    <label><?php echo $report['name'];?></label>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI=& get_instance();
$user=User_helper::get_user();
$action_buttons=array();
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
$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));

?>
<div class="card mt-2">
    <div class="card-header font-weight-bold">
        <?php echo $CI->lang->line('LABEL_TITLE_CHANGE_PASSWORD'); ?>
    </div>
    <div class="card-body">
        <form id="save_form" class="system_ajax" action="<?php echo site_url($CI->controller_url.'/user/save_edit_password');?>" method="post">
            <input type="hidden" class="system_save_new_status" name="system_save_new_status" value="0" />
            <?php
            if($user->username_password_same)
            {
                ?>
                <h4 class="text-danger text-center mb-2"><?php echo $CI->lang->line('LABEL_USERNAME_PASSWORD_SAME'); ?></h4>
                <?php
            }
            ?>
            <div class="row mb-2">
                <div class="col-4">
                    <label class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_CURRENT_PASSWORD');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <div class="input-group">
                        <input class="form-control" type="password" name="password" value="">
                        <div class="input-group-append eye_password" style="cursor: pointer;">
                            <span class="input-group-text"><i class="fe-eye"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-4">
                    <label class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_NEW_PASSWORD');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <div class="input-group">
                        <input class="form-control" type="password" name="new_password" value="">
                        <div class="input-group-append eye_password" style="cursor: pointer;">
                            <span class="input-group-text"><i class="fe-eye"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
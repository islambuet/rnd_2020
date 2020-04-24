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
    'href'=>site_url($CI->controller_name.'/system_edit_password/'.$user_info['user_id'])
);
$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));
?>

<div class="card mt-2">
    <div class="card-header font-weight-bold">
        <?php
        echo sprintf($CI->lang->line('LABEL_TITLE_CHANGE_PASSWORD'),$user_info['name']);
        ?>
    </div>
    <div class="card-body">
        <form id="save_form" class="system_ajax" action="<?php echo site_url($CI->controller_name.'/system_save_edit_password');?>" method="post">
            <input type="hidden" id="system_user_token" name="system_user_token" value="<?php echo time().'_'.$user->user_id; ?>" />
            <input type="hidden" class="system_save_new_status" name="system_save_new_status" value="0" />
            <input type="hidden" id="id" name="id" value="<?php echo $user_info['user_id']; ?>" />


            <div class="row mb-2">
                <div class="col-4">
                    <label for="new_password" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_NEW_PASSWORD');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <div class="input-group">
                        <input class="form-control" type="text" name="new_password" id="new_password" value="">
                        <div class="input-group-append eye_password" style="cursor: pointer;">
                            <span class="input-group-text"><i class="fe-eye-off"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function()
    {
        system_pre_tasks({controller:'<?php echo $CI->router->class; ?>'});
    });
</script>

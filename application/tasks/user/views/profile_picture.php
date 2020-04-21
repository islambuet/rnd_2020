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
$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));

?>
<div class="card mt-2">
    <div class="card-header font-weight-bold">
        <?php echo $CI->lang->line('LABEL_TITLE_CHANGE_PROFILE_PICTURE'); ?>
    </div>
    <div class="card-body">
        <form id="save_form" class="system_ajax" action="<?php echo site_url($CI->controller_name.'/save_profile_picture');?>" method="post">
            <input type="hidden" class="system_save_new_status" name="system_save_new_status" value="0" />
            <div class="row mb-2">
                <div class="col-4">
                    <label class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_PROFILE_PICTURE');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" data-preview-container="#image_profile" name="image_profile">
                        <label class="custom-file-label" data-browse="<?php echo $CI->lang->line('LABEL_BUTTON_UPLOAD');?>"><?php echo $CI->lang->line('LABEL_CHOOSE_PICTURE');?></label>
                    </div>
                </div>
                <div class="col-lg-4 col-8 float-right" id="image_profile">
                    <img style="max-width: 250px;" src="<?php echo Upload_helper::$IMAGE_BASE_URL.$user_info['image_location']; ?>" alt="<?php echo $user_info['name']; ?>">
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

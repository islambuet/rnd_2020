<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI=& get_instance();
$user=User_helper::get_user();
$action_buttons=array();
$action_buttons[]=array(
    'label'=>$CI->lang->line("BUTTON_CANCEL"),
    'class'=>'system_ajax',
    'href'=>site_url($CI->controller_name.'/system_list_input/'.$form['id'].'/'.$crop['id'])
);
$action_buttons[]=array(
    'type'=>'button',
    'label'=>$CI->lang->line("BUTTON_SAVE"),
    'class'=>'button_action_save',
    'data-target-element'=>'#save_form'
);
$action_buttons[]=array(
    'type'=>'button',
    'label'=>$CI->lang->line("BUTTON_SAVE_NEW"),
    'class'=>'button_action_save_new',
    'data-target-element'=>'#save_form'
);
$action_buttons[]=array(
    'type'=>'button',
    'label'=>$CI->lang->line("BUTTON_CLEAR"),
    'class'=>'button_action_clear',
    'data-target-element'=>'#save_form'
);
if($item['id']>0)
{
    $action_buttons[]=array(
        'label'=>$CI->lang->line("BUTTON_REFRESH"),
        'class'=>'system_ajax',
        'href'=>site_url($CI->controller_name.'/system_edit_input/'.$form['id'].'/'.$crop['id'].'/'.$item['id'])
    );
}
else
{
    $action_buttons[]=array(
        'label'=>$CI->lang->line("BUTTON_REFRESH"),
        'class'=>'system_ajax',
        'href'=>site_url($CI->controller_name.'/system_add_input/'.$form['id'].'/'.$crop['id'])
    );
}
$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));
?>
<div class="card mt-2">
    <div class="card-header font-weight-bold">
        <?php
        if($item['id']>0)
        {
            echo sprintf($CI->lang->line('LABEL_TITLE_EDIT_INPUT'),$item['name']);
        }
        else
        {
            echo $CI->lang->line('LABEL_TITLE_NEW_INPUT');
        }

        ?>
    </div>
    <div class="card-body">
        <form id="save_form" class="system_ajax" action="<?php echo site_url($CI->controller_name.'/system_save_add_edit_input');?>" method="post">
            <input type="hidden" class="system_save_new_status" name="system_save_new_status" value="0" />
            <input type="hidden" id="id" name="id" value="<?php echo $item['id']; ?>" />
            <input type="hidden" name="form_id" value="<?php echo $form['id']; ?>" />
            <input type="hidden" name="crop_id" value="<?php echo $crop['id']; ?>" />
            <input type="hidden" id="system_user_token" name="system_user_token" value="<?php echo time().'_'.$user->id; ?>" />
            <div class="row mb-2">
                <div class="col-4">
                    <label for="width" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_CROP_NAME');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <?php echo $crop['name'];?>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="width" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_FORM_NAME');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <?php echo $form['name'];?>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="name" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_NAME');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <input type="text" name="item[name]" id="name" class="form-control" value="<?php echo $item['name'];?>"/>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="type" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_INPUT_TYPE');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <select id="type" name="item[type]" class="form-control">
                        <option value=""><?php echo $this->lang->line('LABEL_SELECT');?></option>
                        <option value="<?php echo SYSTEM_INPUT_TYPE_TEXT; ?>" <?php if ($item['type'] == SYSTEM_INPUT_TYPE_TEXT) { echo "selected='selected'"; } ?> ><?php echo SYSTEM_INPUT_TYPE_TEXT; ?></option>
                        <option value="<?php echo SYSTEM_INPUT_TYPE_TEXTAREA; ?>" <?php if ($item['type'] == SYSTEM_INPUT_TYPE_TEXTAREA) { echo "selected='selected'"; } ?> ><?php echo SYSTEM_INPUT_TYPE_TEXTAREA; ?></option>
                        <option value="<?php echo SYSTEM_INPUT_TYPE_DATE; ?>" <?php if ($item['type'] == SYSTEM_INPUT_TYPE_DATE) { echo "selected='selected'"; } ?> ><?php echo SYSTEM_INPUT_TYPE_DATE; ?></option>
                        <option value="<?php echo SYSTEM_INPUT_TYPE_DROPDOWN; ?>" <?php if ($item['type'] == SYSTEM_INPUT_TYPE_DROPDOWN) { echo "selected='selected'"; } ?> ><?php echo SYSTEM_INPUT_TYPE_DROPDOWN; ?></option>
                        <option value="<?php echo SYSTEM_INPUT_TYPE_IMAGE; ?>" <?php if ($item['type'] == SYSTEM_INPUT_TYPE_IMAGE) { echo "selected='selected'"; } ?> ><?php echo SYSTEM_INPUT_TYPE_IMAGE; ?></option>
                    </select>
                </div>
            </div>
            <div class="row mb-2 <?php if($item['type']!=SYSTEM_INPUT_TYPE_DROPDOWN){echo 'd-none';} ?>" id="options_container">
                <div class="col-4">
                    <label for="options" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_OPTIONS');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <textarea name="item[options]" id="options" class="form-control" rows="5"><?php echo $item['options'];?></textarea>
                    <small class="form-text text-muted"><?php echo $CI->lang->line('LABEL_OPTIONS_RULE');?></small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="default" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_DEFAULT');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <input type="text" class="form-control" name="item[default]" id="default" value="<?php echo $item['default']; ?>" >
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="mandatory" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_MANDATORY');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <select id="mandatory" name="item[mandatory]" class="form-control">
                        <option value="<?php echo SYSTEM_STATUS_YES; ?>" <?php if ($item['mandatory'] == SYSTEM_STATUS_YES) { echo "selected='selected'"; } ?> ><?php echo SYSTEM_STATUS_YES; ?></option>
                        <option value="<?php echo SYSTEM_STATUS_NO; ?>" <?php if ($item['mandatory'] == SYSTEM_STATUS_NO) { echo "selected='selected'"; } ?> ><?php echo SYSTEM_STATUS_NO; ?></option>
                    </select>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="class" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_CLASS');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <input type="text" name="item[class]" id="class" class="form-control" value="<?php echo $item['class']; ?>">
                    <small class="form-text text-muted"><?php echo $CI->lang->line('LABEL_CLASS_RULE');?></small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="average_group_name" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_AVERAGE_GROUP_NAME');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <input type="text" name="item[average_group_name]" id="average_group_name" class="form-control" value="<?php echo $item['average_group_name']; ?>">
                    <small class="form-text text-muted"><?php echo $CI->lang->line('LABEL_AVERAGE_GROUP_NAME_RULE');?></small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="summary_report_column" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_SUMMARY_REPORT_COLUMN');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <select id="summary_report_column" name="item[summary_report_column]" class="form-control">
                        <option value="<?php echo SYSTEM_STATUS_YES; ?>" <?php if ($item['summary_report_column'] == SYSTEM_STATUS_YES) { echo "selected='selected'"; } ?> ><?php echo SYSTEM_STATUS_YES; ?></option>
                        <option value="<?php echo SYSTEM_STATUS_NO; ?>" <?php if ($item['summary_report_column'] == SYSTEM_STATUS_NO) { echo "selected='selected'"; } ?> ><?php echo SYSTEM_STATUS_NO; ?></option>
                    </select>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="ordering" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_ORDERING');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <input type="text" class="form-control" name="item[ordering]" id="ordering" value="<?php echo $item['ordering']; ?>" >
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="status" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_STATUS');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <select id="status" name="item[status]" class="form-control">
                        <option value="<?php echo SYSTEM_STATUS_ACTIVE; ?>" <?php if ($item['status'] == SYSTEM_STATUS_ACTIVE) { echo "selected='selected'"; } ?> ><?php echo SYSTEM_STATUS_ACTIVE; ?></option>
                        <option value="<?php echo SYSTEM_STATUS_INACTIVE; ?>" <?php if ($item['status'] == SYSTEM_STATUS_INACTIVE) { echo "selected='selected'"; } ?> ><?php echo SYSTEM_STATUS_INACTIVE; ?></option>
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">

    jQuery(document).ready(function()
    {
        system_pre_tasks({controller:'<?php echo $CI->router->class; ?>'});
        $(document).off('change','#type');
        $(document).on("change","#type",function()
        {
            var type=$(this).val();

            if(type=='<?php echo SYSTEM_INPUT_TYPE_IMAGE; ?>')
            {
                $("#default").val('images/no_image.jpg');
            }
            else
            {
                $("#default").val('');
            }
            if(type=='<?php echo SYSTEM_INPUT_TYPE_DROPDOWN; ?>')
            {
                $("#options_container").removeClass('d-none');

            }
            else
            {
                $("#options").val('');
                $("#options_container").addClass('d-none');
            }

        });
    });
</script>
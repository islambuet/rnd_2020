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
        'href'=>site_url($CI->controller_name.'/system_edit/'.$item['id'])
    );
}
else
{
    $action_buttons[]=array(
        'label'=>$CI->lang->line("BUTTON_REFRESH"),
        'class'=>'system_ajax',
        'href'=>site_url($CI->controller_name.'/system_add')
    );
}

$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));
?>
<div class="card mt-2">
    <div class="card-header font-weight-bold">
        <?php
        if($item['id']>0)
        {
            echo sprintf($CI->lang->line('LABEL_TITLE_EDIT'),$item['purpose']);
        }
        else
        {
            echo $CI->lang->line('LABEL_TITLE_NEW');
        }

        ?>
    </div>
    <div class="card-body">
        <form id="save_form" class="system_ajax" action="<?php echo site_url($CI->controller_name.'/system_save_add_edit');?>" method="post">
            <input type="hidden" class="system_save_new_status" name="system_save_new_status" value="0" />
            <input type="hidden" id="id" name="id" value="<?php echo $item['id']; ?>" />
            <input type="hidden" id="system_user_token" name="system_user_token" value="<?php echo time().'_'.$user->id; ?>" />

            <div class="row mb-2">
                <div class="col-4">
                    <label for="purpose" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_PURPOSE');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <input type="text" id="purpose" name="item[purpose]" class="form-control" value="<?php echo $item['purpose']?>"/>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="description" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_DESCRIPTION');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <textarea name="item[description]" id="description" class="form-control" ><?php echo $item['description'];?></textarea>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="config_value" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_CONFIG_VALUE');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <textarea name="item[config_value]" id="config_value" class="form-control" ><?php echo $item['config_value'];?></textarea>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="status" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_STATUS');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <select id="status" name="item[status]" class="form-control">
                        <!--<option value=""></option>-->
                        <option value="<?php echo SYSTEM_STATUS_ACTIVE; ?>" <?php if ($item['status'] == SYSTEM_STATUS_ACTIVE) { echo "selected='selected'"; } ?> ><?php echo SYSTEM_STATUS_ACTIVE; ?></option>
                        <option value="<?php echo SYSTEM_STATUS_INACTIVE; ?>" <?php if ($item['status'] == SYSTEM_STATUS_INACTIVE) { echo "selected='selected'"; } ?> ><?php echo SYSTEM_STATUS_INACTIVE; ?></option>
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

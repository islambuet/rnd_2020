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
            echo sprintf($CI->lang->line('LABEL_TITLE_EDIT'),$item['name']);
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
                    <label for="employee_id" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_EMPLOYEE_ID');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <input type="text" name="item[employee_id]" id="employee_id" class="form-control" value="<?php echo $item['employee_id']; ?>">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="user_name" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_USER_NAME');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <input type="text" name="item[user_name]" id="user_name" class="form-control" value="<?php echo $item['user_name']; ?>">
                    <small class="form-text text-muted"><?php echo $CI->lang->line('LABEL_USER_NAME_RULE');?><</small>

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
                    <label for="email" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_EMAIL');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <input type="text" name="item[email]" id="email" class="form-control" value="<?php echo $item['email'];?>"/>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="email" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_MOBILE_NO');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <input type="text" name="item[mobile_no]" id="mobile_no" class="form-control" value="<?php echo $item['mobile_no'];?>"/>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="email" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_MOBILE_NO_PERSONAL');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <input type="text" name="item[mobile_no_personal]" id="mobile_no_personal" class="form-control" value="<?php echo $item['mobile_no_personal'];?>"/>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-4">
                    <label for="designation" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_DESIGNATION_NAME');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <select id="designation" name="item[designation]" class="form-control">
                        <option value=""><?php echo $CI->lang->line('LABEL_SELECT'); ?></option>
                        <?php
                        foreach($designations as $designation)
                        {
                            ?>
                            <option value="<?php echo $designation['value']; ?>" <?php if($designation['value']==$item['designation']){echo 'selected';} ?>><?php echo $designation['text']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="user_type_id" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_USER_TYPE');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <select id="user_type_id" name="item[user_type_id]" class="form-control">
                        <option value=""><?php echo $CI->lang->line('LABEL_SELECT'); ?></option>
                        <?php
                        foreach($user_types as $user_type)
                        {
                            ?>
                            <option value="<?php echo $user_type['value']; ?>" <?php if($user_type['value']==$item['user_type_id']){echo 'selected';} ?>><?php echo $user_type['text']; ?></option>
                        <?php
                        }
                        ?>
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
        $(document).off('input','#employee_id');
        $(document).on("input","#employee_id",function()
        {
            $('#user_name').val($(this).val());
        });
    });
</script>
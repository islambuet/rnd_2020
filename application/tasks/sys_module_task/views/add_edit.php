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


$controller_folder=dir(APPPATH.'tasks');
$controller_list=array();
while(($controller=$controller_folder->read())!==false)
{
    if($controller=='.' || $controller=='..')
    {
        continue;
    }
    $controller_list[]=ucfirst(pathinfo($controller,PATHINFO_FILENAME));
}
$controller_folder->close();
?>
<div class="card mt-2">
    <div class="card-header font-weight-bold">
        <?php
        if($item['id']>0)
        {
            echo sprintf($CI->lang->line('LABEL_TITLE_MODULE_TASK_EDIT'),$item['name']);
        }
        else
        {
            echo $CI->lang->line('LABEL_TITLE_MODULE_TASK_NEW');
        }

        ?>
    </div>
    <div class="card-body">
        <form id="save_form" class="system_ajax" action="<?php echo site_url($CI->controller_name.'/system_save_add_edit');?>" method="post">
            <input type="hidden" class="system_save_new_status" name="system_save_new_status" value="0" />
            <input type="hidden" id="id" name="id" value="<?php echo $item['id']; ?>" />
            <input type="hidden" id="system_user_token" name="system_user_token" value="<?php echo time().'_'.$user->user_id; ?>" />

            <div class="row mb-2">
                <div class="col-4">
                    <label for="name" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_MODULE_TASK_NAME');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <input type="text" name="item[name]" id="name" class="form-control" value="<?php echo $item['name']; ?>"/>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="name" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_MODULE_TYPE');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <select id="type" name="item[type]" class="form-control">
                        <option value="MODULE"
                            <?php
                            if($item['type']=='MODULE')
                            {
                                echo ' selected';
                            }
                            ?> >Module
                        </option>
                        <option value="TASK"
                            <?php
                            if($item['type']=='TASK')
                            {
                                echo ' selected';
                            }
                            ?> >Task</option>
                    </select>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="name" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_PARENT');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <select id="parent" name="item[parent]" class="form-control" tabindex="-1">
                        <option value="0"><?php echo $CI->lang->line('LABEL_SELECT'); ?></option>
                        <?php
                        foreach($modules_tasks['tree'] as $module)
                        {
                            if($module['module_task']['type']=='MODULE')
                            {
                            ?>
                            <option value='<?php echo $module['module_task']['id']; ?>' <?php if($module['module_task']['id']==$item['parent']){ echo ' selected';} ?>><?php echo $module['prefix'].$module['module_task']['name']; ?></option>
                            <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="name" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_CONTROLLER_NAME');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <input type="text" name="item[controller]" id="controller" class="form-control" value="<?php echo $item['controller'] ?>" >
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
                    <label for="ordering" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_STATUS');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <select id="status" name="item[status]" class="form-control" tabindex="-1">
                        <option value="<?php echo SYSTEM_STATUS_ACTIVE; ?>"
                            <?php
                            if($item['status']==SYSTEM_STATUS_ACTIVE)
                            {
                                echo ' selected';
                            }
                            ?> ><?php echo SYSTEM_STATUS_ACTIVE; ?>
                        </option>
                        <option value="<?php echo SYSTEM_STATUS_INACTIVE; ?>"
                            <?php
                            if($item['status']==SYSTEM_STATUS_INACTIVE)
                            {
                                echo ' selected';
                            }
                            ?> ><?php echo SYSTEM_STATUS_INACTIVE; ?>
                        </option>
                        <option value="<?php echo SYSTEM_STATUS_DELETE; ?>"
                            <?php
                            if($item['status']==SYSTEM_STATUS_DELETE)
                            {
                                echo ' selected';
                            }
                            ?> ><?php echo SYSTEM_STATUS_DELETE; ?>
                        </option>
                    </select>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="ordering" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_STATUS_NOTIFICATION');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <select id="status_notification" name="item[status_notification]" class="form-control">
                        <option value="<?php echo SYSTEM_STATUS_NO; ?>"
                            <?php
                            if($item['status_notification']==SYSTEM_STATUS_NO)
                            {
                                echo ' selected';
                            }
                            ?> ><?php echo SYSTEM_STATUS_NO; ?>
                        </option>
                        <option value="<?php echo SYSTEM_STATUS_YES; ?>"
                            <?php
                            if($item['status_notification']==SYSTEM_STATUS_YES)
                            {
                                echo ' selected';
                            }
                            ?> ><?php echo SYSTEM_STATUS_YES; ?>
                        </option>
                    </select>
                </div>
            </div>

        </form>
    </div>
</div>
<script>
    jQuery(document).ready(function()
    {
        system_pre_tasks({controller:'<?php echo $CI->router->class; ?>'});
        var controller_list=<?php echo json_encode($controller_list); ?>;
        $("#controller").jqxInput({minLength: 1,  source: controller_list });
        //$("#controller").autocomplete({ source: controller_list });

        //$("#item_date").jqxDateTimeInput({formatString:'dd-MMM-yyyy',value: new Date(<?php echo time(); ?> * 1000), animationType: 'slide',readonly: true,width:'100%'});



    });
</script>

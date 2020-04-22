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
    'href'=>site_url($CI->controller_name.'/system_role/'.$item_id)
);
$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));
echo '<pre>';
//print_r($modules_tasks);
echo '</pre>';
?>
<div class="card mt-2">
    <div class="card-header font-weight-bold">

        <?php echo sprintf($CI->lang->line('LABEL_TITLE_USER_GROUP_ROLE'),$item_id);?>
    </div>
    <div class="card-body">
        <form id="save_form" class="system_ajax" action="<?php echo site_url($CI->controller_name.'/system_save_role');?>" method="post">
            <input type="hidden" id="id" name="id" value="<?php echo $item_id; ?>" />
            <input type="hidden" id="system_save_new_status" name="system_save_new_status" value="0" />
            <input type="hidden" id="system_user_token" name="system_user_token" value="<?php echo time().'_'.$user->user_id; ?>" />

            <div class="overflow-auto" style="height: 500px">
                <table class="table table-hover table-bordered">
                    <thead class="text-center thead-light">
                    <tr>
                        <th colspan="<?php echo $modules_tasks['max_level'];?>"><?php echo $CI->lang->line("LABEL_MODULE_TASK_NAME");?></th>
                        <?php
                        for($i=0;$i<Module_task_helper::$MAX_MODULE_ACTIONS;$i++)
                        {
                            ?>
                            <th>
                                <label><input type="checkbox" data-type='header_action_<?php echo $i;?>' class="header_action"> <?php echo $CI->lang->line('LABEL_ACTION'.$i); ?></label>
                            </th>
                        <?php
                        }
                        ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                        foreach($modules_tasks['tree'] as $module_task)
                        {
                            ?>
                            <tr>
                                <?php
                                for($i=1;$i<=$modules_tasks['max_level'];$i++)
                                {
                                    ?>
                                    <td>
                                        <?php
                                        if($i==$module_task['level'])
                                        {
                                            //echo $module_task['prefix'];
                                            ?>
                                            <label>
                                            <?php
                                                if($module_task['module_task']['type']=='TASK')
                                                {
                                                    ?>
                                                    <input type="checkbox" data-id='<?php echo $module_task['module_task']['id'];?>' class="task_action">
                                                <?php
                                                }
                                                echo $module_task['module_task']['name'];
                                            ?>
                                            </label>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <?php

                                }
                                ?>

                                <?php
                                for($i=0;$i<Module_task_helper::$MAX_MODULE_ACTIONS;$i++)
                                {
                                    ?>
                                    <td>
                                        <?php
                                        if($module_task['module_task']['type']=='TASK')
                                        {
                                            ?>
                                            <label><input type="checkbox" title="<?php echo $CI->lang->line('LABEL_ACTION'.$i); ?>" class="header_action_<?php echo $i;?> task_action_<?php echo $module_task['module_task']['id'];?>"  <?php if(in_array($module_task['module_task']['id'],$role_status['action'.$i])){echo 'checked';}?> value="1" name='tasks[<?php echo $module_task['module_task']['id'];?>][action<?php echo $i; ?>]'> <?php echo $CI->lang->line('LABEL_SHORT_ACTION'.$i); ?></label>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                <?php
                                }
                                ?>
                            </tr>
                        <?php
                        }
                    ?>
                    </tbody>
                </table>
            </div>

        </form>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function()
    {
        system_pre_tasks({controller:'<?php echo $CI->router->class; ?>'});

        $(document).off("click", ".header_action");
        $(document).on("click",'.header_action',function()
        {
            if($(this).is(':checked'))
            {
                $('.'+$(this).attr('data-type')).prop('checked', true);
            }
            else
            {
                $('.'+$(this).attr('data-type')).prop('checked', false);
            }
        });
        $(document).off("click", ".task_action");
        $(document).on("click",'.task_action',function()
        {
            if($(this).is(':checked'))
            {
                //$('.task_action_'+$(this).attr('data-id')+':not(.header_action_3,.header_action_4)').prop('checked', true);
                $('.task_action_'+$(this).attr('data-id')+':not(.header_action_3)').prop('checked', true);
            }
            else
            {
                $('.task_action_'+$(this).attr('data-id')).prop('checked', false);
            }
        });
    });
</script>

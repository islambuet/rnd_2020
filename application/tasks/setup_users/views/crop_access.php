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
    'href'=>site_url($CI->controller_name.'/system_edit_crop_access/'.$item['id'])
);

$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));
?>
<div class="card mt-2">
    <div class="card-header font-weight-bold">
        <?php
        echo sprintf($CI->lang->line('LABEL_TITLE_CROP_ACCESS'),$item['name']);

        ?>
    </div>
    <div class="card-body">
        <form id="save_form" class="system_ajax" action="<?php echo site_url($CI->controller_name.'/system_save_crop_access');?>" method="post">
            <input type="hidden" class="system_save_new_status" name="system_save_new_status" value="0" />
            <input type="hidden" id="id" name="id" value="<?php echo $item['id']; ?>" />
            <input type="hidden" id="system_user_token" name="system_user_token" value="<?php echo time().'_'.$user->id; ?>" />

            <div class="overflow-auto" style="height: 500px">
                <table class="table table-hover table-bordered">
                    <thead class="text-center thead-light">
                    <tr>
                        <th><?php echo $CI->lang->line("LABEL_CROP_NAME");?></th>
                        <th><label><input type="checkbox" data-type='header_action_edit' class="header_action"> <?php echo $CI->lang->line('LABEL_ACCESS'); ?></label></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                        foreach($crops as $crop)
                        {
                            ?>
                            <tr>
                                <td><label><input type="checkbox" data-id='<?php echo $crop['value'];?>' class="task_action"><?php echo $crop['text'];?></label></td>
                                <td class="text-center"><label><input type="checkbox" title="Edit" class="header_action_edit <?php echo 'parent_'.$crop['value'];?>"  <?php if(strpos($item['crop_ids'], ','.$crop['value'].',') !== FALSE){echo 'checked';}?> value="<?php echo $crop['value']; ?>" name='crop_ids[<?php echo $crop['value']; ?>]'></label></td>
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
                $('.parent_'+$(this).attr('data-id')+':not(.header_action_3)').prop('checked', true);
            }
            else
            {
                $('.parent_'+$(this).attr('data-id')).prop('checked', false);
            }
        });
    });
</script>

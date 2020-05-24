<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI=& get_instance();
$user=User_helper::get_user();
$action_buttons=array();
$action_buttons[]=array(
    'label'=>$CI->lang->line("BUTTON_CANCEL"),
    'class'=>'system_ajax',
    'href'=>site_url($CI->controller_name.'/system_list_input/'.$report['id'])
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
    'href'=>site_url($CI->controller_name.'/system_edit_input/'.$report['id'].'/'.$crop['id'])
);
$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));
?>
<div class="card mt-2">
    <div class="card-header font-weight-bold">
        <?php
        echo sprintf($CI->lang->line('LABEL_TITLE_EDIT_INPUT'),$report['name']);

        ?>
    </div>
    <div class="card-body">
        <form id="save_form" class="system_ajax" action="<?php echo site_url($CI->controller_name.'/system_save_add_edit_input');?>" method="post">
            <input type="hidden" class="system_save_new_status" name="system_save_new_status" value="0" />
            <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>" />
            <input type="hidden" name="crop_id" value="<?php echo $crop['id']; ?>" />
            <input type="hidden" id="system_user_token" name="system_user_token" value="<?php echo time().'_'.$user->id; ?>" />
            <div class="row mb-2">
                <div class="col-4">
                    <label for="width" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_REPORT_NAME');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <?php echo $report['name'];?>
                </div>
            </div>
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
                    <label for="rowsheight" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_ROWS_HEIGHT');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <input type="text" name="item[rowsheight]" id="rowsheight" class="form-control" value="<?php echo $item['rowsheight'];?>"/>
                    <small class="form-text text-muted"><?php echo $CI->lang->line('LABEL_ROWS_HEIGHT_RULE');?></small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="columnsheight" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_COLUMNS_HEIGHT');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <input type="text" name="item[columnsheight]" id="columnsheight" class="form-control" value="<?php echo $item['columnsheight'];?>"/>
                    <small class="form-text text-muted"><?php echo $CI->lang->line('LABEL_COLUMNS_HEIGHT_RULE');?></small>
                </div>
            </div>
            <?php
            foreach($trail_inputs as $trial_id=>$trial)
            {
            ?>
            <div class="card">
                <div class="card-header">
                    <a class="btn-link" data-toggle="collapse" href="#trial_<?php echo $trial['trial_id']; ?>">
                        <?php echo $trial['trial_name'];?>
                    </a>
                </div>
                <div id="trial_<?php echo $trial['trial_id']; ?>" class="collapse">
                    <div class="card-body">
                        <div class="overflow-auto">
                            <table class="table table-hover table-bordered">
                                <thead class="text-center thead-light">
                                <tr>
                                    <th><?php echo $CI->lang->line("LABEL_ID"); ?></th>
                                    <th class="text-center"><input type="checkbox" data-trial-id='<?php echo $trial['trial_id']; ?>' class="select_all"></th>
                                    <th><?php echo $CI->lang->line("LABEL_HEADER_NAME"); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach($trial['inputs'] as $input)
                                {
                                    ?>
                                    <tr>
                                        <td><?php echo $input['input_id']; ?></td>
                                        <td class="text-center"><input type="checkbox" class='select_<?php echo $trial['trial_id']; ?>' name="input_ids[]" <?php if(strpos($item['input_ids'], ','.$input['input_id'].',') !== FALSE){echo 'checked';}?>  value="<?php echo $input['input_id']; ?>"></td>
                                        <td><?php echo $input['input_name']; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php
                }
                for($i=1;$i<=SYSTEM_TRIAL_REPORT_MAX_CALCULATION;$i++)
                {
                    ?>
                    <div class="row mb-2">
                        <div class="col-4">
                            <label class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_CALC_NAME_'.$i);?></label>
                        </div>
                        <div class="col-lg-4 col-8">
                            <input type="text" name="item[calc_name_<?php echo $i; ?>]" id="height" class="form-control" value="<?php echo $item['calc_name_'.$i];?>"/>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4">
                            <label class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_CALC_VALUE_'.$i);?></label>
                        </div>
                        <div class="col-lg-4 col-8">
                            <input type="text" name="item[calc_value_<?php echo $i; ?>]" id="height" class="form-control" value="<?php echo $item['calc_value_'.$i];?>"/>
                            <small class="form-text text-muted"><?php echo $CI->lang->line('LABEL_CALC_RULE');?></small>
                        </div>
                    </div>
                    <?php
                }
            ?>

        </form>
    </div>
</div>

<script type="text/javascript">

    jQuery(document).ready(function()
    {
        system_pre_tasks({controller:'<?php echo $CI->router->class; ?>'});
        $(document).off("click", ".select_all");
        $(document).on("click",'.select_all',function()
        {
            if($(this).is(':checked'))
            {
                $('.select_'+$(this).attr('data-trial-id')).prop('checked', true);
            }
            else
            {
                $('.select_'+$(this).attr('data-trial-id')).prop('checked', false);
            }
        });
    });
</script>
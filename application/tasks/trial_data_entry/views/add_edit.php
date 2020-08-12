<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI=& get_instance();
$user=User_helper::get_user();
$action_buttons=array();

$action_buttons[]=array(
    'label'=>$CI->lang->line("BUTTON_CANCEL"),
    'class'=>'system_ajax',
    'href'=>site_url($CI->controller_name.'/system_list/'.$trial_id.'/'.$year.'/'.$season_id)
);
$action_buttons[]=array(
    'label'=>$CI->lang->line("BUTTON_REFRESH"),
    'class'=>'system_ajax',
    'href'=>site_url($CI->controller_name.'/system_edit/'.$trial_id.'/'.$year.'/'.$season_id.'/'.$item['variety_id'])
);

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
        <?php
            echo $CI->lang->line('LABEL_TITLE_EDIT');
        ?>
    </div>
    <div class="card-body">
        <form id="save_form" class="system_ajax" action="<?php echo site_url($CI->controller_name.'/system_save_add_edit');?>" method="post">
            <input type="hidden" class="system_save_new_status" name="system_save_new_status" value="0" />
            <input type="hidden" id="variety_id" name="variety_id" value="<?php echo $item['variety_id']; ?>" />
            <input type="hidden" name="trial_id" value="<?php echo $trial_id; ?>" />
            <input type="hidden" name="year" value="<?php echo $year; ?>" />
            <input type="hidden" name="season_id" value="<?php echo $season_id; ?>" />
            <input type="hidden" id="system_user_token" name="system_user_token" value="<?php echo time().'_'.$user->id; ?>" />
            <div class="row mb-2">
                <div class="col-4">
                    <label for="width" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_TRIAL_NAME');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <?php echo $trial['name'];?>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="width" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_YEAR');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <?php echo $year;?>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="width" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_CROP_NAME');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <?php echo $item['crop_name'];?>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="width" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_TYPE_NAME');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <?php echo $item['type_name'];?>
                </div>
            </div>
            <?php
            if($user->show_variety==SYSTEM_STATUS_YES)
            {
            ?>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="width" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_VARIETY_NAME');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <?php echo $item['variety_name'];?>
                </div>
            </div>
            <?php
            }
            ?>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="width" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_RND_CODE');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <?php echo System_helper::get_variety_rnd_code($item);?>
                </div>
            </div>
            <?php
            $column_class='col-lg-4 col-8';
            if($item['status_replica']==SYSTEM_STATUS_YES)
            {
                $column_class='col-4';
                ?>
                <div class="row mb-2">
                    <div class="col-4">
                    </div>
                    <div class="<?php echo $column_class;?> text-center btn btn-primary">
                        <?php echo $CI->lang->line('Normal');?>
                    </div>
                    <div class="<?php echo $column_class;?> text-center btn btn-danger">
                        <?php echo $CI->lang->line('LABEL_STATUS_REPLICA');?>
                    </div>
                </div>
                <?php
            }
            foreach($trial_input_fields as $input_field)
            {
                $default_normal=array();
                $default_normal['name']='trial_data[normal]['.$input_field['id'].']';
                if($input_field['type']==SYSTEM_INPUT_TYPE_IMAGE)
                {
                    $default_normal['name']='trial_data_image_normal_'.$input_field['id'];
                }
                $default_normal['value']=isset($trial_data['normal'][$input_field['id']])?$trial_data['normal'][$input_field['id']]:$input_field['default'];
                ?>
                <div class="row mb-2">
                    <div class="col-4">
                        <label class="font-weight-bold float-right"><?php echo $input_field['name'];?><?php if($input_field['mandatory']==SYSTEM_STATUS_YES){?><span class="text-danger">*</span><?php }?></label>
                    </div>
                    <div class="<?php echo $column_class;?>">
                        <?php $CI->load->view('input_field',array('input_field'=>$input_field,'default_data'=>$default_normal));?>
                    </div>
                    <?php
                    if($item['status_replica']==SYSTEM_STATUS_YES)
                    {
                        $default_replica=array();
                        $default_replica['name']='trial_data[replica]['.$input_field['id'].']';
                        if($input_field['type']==SYSTEM_INPUT_TYPE_IMAGE)
                        {
                            $default_replica['name']='trial_data_image_replica_'.$input_field['id'];
                        }
                        $default_replica['value']=isset($trial_data['replica'][$input_field['id']])?$trial_data['replica'][$input_field['id']]:$input_field['default'];
                        ?>
                        <div class="<?php echo $column_class;?>">
                            <?php $CI->load->view('input_field',array('input_field'=>$input_field,'default_data'=>$default_replica));?>
                        </div>
                        <?php
                    }
                    ?>

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
        $('.datepicker').datepicker({dateFormat : SYSTEM_DATE_FORMAT,changeMonth: true,changeYear: true,yearRange: "2020:c+1",showButtonPanel: true});
    });
</script>
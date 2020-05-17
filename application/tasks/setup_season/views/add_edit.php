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
                    <label for="name" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_NAME');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <input type="text" name="item[name]" id="name" class="form-control" value="<?php echo $item['name'];?>"/>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="season_start_date" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_SEASON_START_DATE');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <div class="input-group">
                        <input type="text" name="item[season_start_date]" id="season_start_date" class="form-control datepicker" value="<?php echo $item['season_start_date'];?>" readonly/>
                        <div class="input-group-append datepicker_handeler" style="cursor: pointer;">
                            <span class="input-group-text"><i class="fe-calendar"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="expected_sowing_start" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_EXPECTED_SOWING_START');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <div class="input-group">
                        <input type="text" name="item[expected_sowing_start]" id="expected_sowing_start" class="form-control datepicker" value="<?php echo $item['expected_sowing_start'];?>" readonly/>
                        <div class="input-group-append datepicker_handeler" style="cursor: pointer;">
                            <span class="input-group-text"><i class="fe-calendar"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="expected_sowing_end" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_EXPECTED_SOWING_END');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <div class="input-group">
                        <input type="text" name="item[expected_sowing_end]" id="expected_sowing_end" class="form-control datepicker" value="<?php echo $item['expected_sowing_end'];?>" readonly/>
                        <div class="input-group-append datepicker_handeler" style="cursor: pointer;">
                            <span class="input-group-text"><i class="fe-calendar"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="estimated_delivery_date" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_ESTIMATED_DELIVERY_DATE');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <div class="input-group">
                        <input type="text" name="item[estimated_delivery_date]" id="estimated_delivery_date" class="form-control datepicker" value="<?php echo $item['estimated_delivery_date'];?>" readonly/>
                        <div class="input-group-append datepicker_handeler" style="cursor: pointer;">
                            <span class="input-group-text"><i class="fe-calendar"></i></span>
                        </div>
                    </div>
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
        $('.datepicker').datepicker({dateFormat : 'dd-M',changeMonth: true,changeYear: true,yearRange: "-100:+0",showButtonPanel: true});
    });
</script>
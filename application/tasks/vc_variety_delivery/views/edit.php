<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI=& get_instance();
$user=User_helper::get_user();
$action_buttons=array();
$action_buttons[]=array(
    'label'=>$CI->lang->line("BUTTON_CANCEL"),
    'class'=>'system_ajax',
    'href'=>site_url($CI->controller_name.'/system_list/'.$year)
);
$action_buttons[]=array(
    'type'=>'button',
    'label'=>$is_delivered?$CI->lang->line("BUTTON_SAVE_DELIVERED"):$CI->lang->line("BUTTON_SAVE_NOT_DELIVERED"),
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
    'href'=>site_url($CI->controller_name.'/system_edit/'.$year.'/'.$is_delivered.'/'.$season['value'])
);

$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));
?>
<div class="card mt-2">
    <div class="card-header font-weight-bold">
        <?php
        echo $is_delivered?$CI->lang->line('LABEL_TITLE_LIST_DELIVERED'):$CI->lang->line('LABEL_TITLE_LIST_NOT_DELIVERED');

        ?>
    </div>
    <div class="card-body">
        <form id="save_form" class="system_ajax" action="<?php echo site_url($CI->controller_name.'/system_save_edit');?>" method="post">
            <input type="hidden" class="system_save_new_status" name="system_save_new_status" value="0" />
            <input type="hidden" id="id" name="id" value="<?php echo $season['value']; ?>" />
            <input type="hidden" name="year" value="<?php echo $year; ?>" />
            <input type="hidden" name="is_delivered" value="<?php echo $is_delivered; ?>" />
            <input type="hidden" id="system_user_token" name="system_user_token" value="<?php echo time().'_'.$user->id; ?>" />
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
                    <label for="width" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_SEASON_NAME');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <?php echo $season['text'];?>
                </div>
            </div>

            <div id="accordion">
            <?php
            if(!$is_delivered)
            {
                ?>
                <div class="row mb-2">
                    <div class="col-4">
                        <label for="date_delivery" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_DATE_DELIVERY');?></label>
                    </div>
                    <div class="col-lg-4 col-8">
                        <div class="input-group">
                            <input type="text" name="date_delivery" id="date_delivery" class="form-control datepicker" value="" readonly/>
                            <div class="input-group-append datepicker_handeler" style="cursor: pointer;">
                                <span class="input-group-text"><i class="fe-calendar"></i></span>
                            </div>
                        </div>


                    </div>
                </div>
                <?php
            }
            foreach($crop_varieties as $crop_id=>$crop_info)
            {
               ?>
                <div class="card">
                    <div class="card-header">
                        <a class="btn-link" data-toggle="collapse" href="#crop_<?php echo $crop_info['crop_id']; ?>">
                            <?php echo $crop_info['crop_name'];?>
                        </a>
                    </div>
                    <div id="crop_<?php echo $crop_info['crop_id']; ?>" class="collapse">
                        <div class="card-body">
                            <div class="overflow-auto">
                                <table class="table table-hover table-bordered">
                                    <thead class="text-center thead-light">
                                    <tr>
                                        <th class="text-center"><input type="checkbox" data-crop-id='<?php echo $crop_info['crop_id']; ?>' class="select_all"></th>
                                        <th><?php echo $CI->lang->line("LABEL_TYPE_NAME"); ?></th>
                                        <th><?php echo $CI->lang->line("LABEL_VARIETY_NAME"); ?></th>
                                        <th><?php echo $CI->lang->line("LABEL_RND_CODE"); ?></th>
                                        <?php
                                        if($is_delivered)
                                        {
                                            ?>
                                            <th><?php echo $CI->lang->line("LABEL_DATE_DELIVERY"); ?></th>
                                            <?php
                                        }
                                        ?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach($crop_info['varieties'] as $variety)
                                    {
                                        ?>
                                        <tr>
                                            <td class="text-center"><input type="checkbox" class='select_<?php echo $crop_info['crop_id']; ?>' name="varieties[]" value="<?php echo $variety['variety_id']; ?>"></td>
                                            <td><?php echo $variety['type_name']; ?></td>
                                            <td><?php echo $variety['variety_name']; ?></td>
                                            <td><?php echo $variety['rnd_code']; ?></td>
                                            <?php
                                            if($is_delivered)
                                            {
                                                ?>
                                                <td><?php echo System_helper::display_date($variety['date_delivery']); ?></td>
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
                    </div>
                </div>
                <?php
            }
            ?>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">

    jQuery(document).ready(function()
    {
        system_pre_tasks({controller:'<?php echo $CI->router->class; ?>'});
        $('.datepicker').datepicker({dateFormat : SYSTEM_DATE_FORMAT,changeMonth: true,changeYear: true,yearRange: "2020:c+1",showButtonPanel: true});
        $(document).off("click", ".select_all");
        $(document).on("click",'.select_all',function()
        {
            if($(this).is(':checked'))
            {
                $('.select_'+$(this).attr('data-crop-id')).prop('checked', true);
            }
            else
            {
                $('.select_'+$(this).attr('data-crop-id')).prop('checked', false);
            }
        });

    });
</script>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI=& get_instance();
$user=User_helper::get_user();
$action_buttons=array();
if($selected_seasons)
{
    $action_buttons[]=array(
        'label'=>$CI->lang->line("BUTTON_CANCEL"),
        'class'=>'system_ajax',
        'href'=>site_url($CI->controller_name.'/system_list/'.$year)
    );
    $action_buttons[]=array(
        'label'=>$CI->lang->line("BUTTON_REFRESH"),
        'class'=>'system_ajax',
        'href'=>site_url($CI->controller_name.'/system_edit/'.$year.'/'.$item['id'])
    );
}
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
$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));

?>
<div class="card mt-2">
    <div class="card-header font-weight-bold">
        <?php
        if($selected_seasons)
        {
            echo $CI->lang->line('LABEL_TITLE_EDIT');
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
            <input type="hidden" name="year" value="<?php echo $year; ?>" />
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
            <div class="row mb-2">
                <div class="col-4">
                    <label for="width" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_VARIETY_NAME');?></label>
                </div>
                <div class="col-lg-4 col-8">
                    <?php echo $item['name'];?>
                </div>
            </div>
            <?php
            if($item['whose']=='Principal')
            {
                ?>
                <div class="row mb-2">
                    <div class="col-4">
                        <label for="width" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_PRINCIPAL_NAME');?></label>
                    </div>
                    <div class="col-lg-4 col-8">
                        <?php echo $item['principal_name'];?>
                    </div>
                </div>
                <?php
            }
            ?>
            <?php
            if($item['whose']=='Competitor')
            {
                ?>
                <div class="row mb-2">
                    <div class="col-4">
                        <label for="width" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_COMPETITOR_NAME');?></label>
                    </div>
                    <div class="col-lg-4 col-8">
                        <?php echo $item['competitor_name'];?>
                    </div>
                </div>
            <?php
            }
            ?>
            <div class="overflow-auto">
                <table class="table table-hover table-bordered">
                    <thead class="text-center thead-light">
                    <tr>
                        <th style="min-width: 100px;"><?php echo $CI->lang->line("LABEL_SEASON"); ?></th>
                        <th style="min-width: 100px;"><?php echo $CI->lang->line("LABEL_STATUS_REPLICA"); ?></th>
                        <th style="min-width: 100px;"><?php echo $CI->lang->line("LABEL_LENGTH"); ?></th>
                        <th style="min-width: 100px;"><?php echo $CI->lang->line("LABEL_WIDTH"); ?></th>
                        <th style="min-width: 100px;"><?php echo $CI->lang->line("LABEL_PLANTS_INITIAL"); ?></th>
                        <th style="min-width: 100px;"><?php echo $CI->lang->line("LABEL_OPTIMUM_TRANSPLANTING_DAYS"); ?></th>
                        <th style="min-width: 100px;"><?php echo $CI->lang->line("LABEL_EXPECTED_SEED_PER_GRAM"); ?></th>
                        <th style="min-width: 100px;"><?php echo $CI->lang->line("LABEL_NUMBER_SEED_PER_GRAM"); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($seasons as $season)
                    {
                        $is_edit=false;
                        if(isset($selected_seasons[$season['value']])&&$selected_seasons[$season['value']]['status_selection']==SYSTEM_STATUS_YES)
                        {
                            $is_edit=true;
                        }
                        ?>
                        <tr>
                            <td>
                                <label><input type="checkbox" value="<?php echo $season['value'];?>" name="selected_seasons[<?php echo $season['value'];?>][season_id]" <?php if($is_edit){echo 'checked';}?>> <?php echo $season['text']; ?></label>
                            </td>

                            <td>
                                <select id="status" name="selected_seasons[<?php echo $season['value'];?>][status_replica]" class="form-control">
                                    <option value="<?php echo SYSTEM_STATUS_YES; ?>" <?php if (($is_edit?$selected_seasons[$season['value']]['status_replica']:$item['status_replica']) == SYSTEM_STATUS_YES) { echo "selected='selected'"; } ?> ><?php echo SYSTEM_STATUS_YES; ?></option>
                                    <option value="<?php echo SYSTEM_STATUS_NO; ?>" <?php if (($is_edit?$selected_seasons[$season['value']]['status_replica']:$item['status_replica']) == SYSTEM_STATUS_NO) { echo "selected='selected'"; } ?> ><?php echo SYSTEM_STATUS_NO; ?></option>
                                </select>
                            </td>
                            <td><input type="text" class="form-control float_positive" name="selected_seasons[<?php echo $season['value'];?>][length]" value="<?php echo $is_edit?$selected_seasons[$season['value']]['length']:$item['length']; ?>" ></td>
                            <td><input type="text" class="form-control float_positive" name="selected_seasons[<?php echo $season['value'];?>][width]" value="<?php echo $is_edit?$selected_seasons[$season['value']]['width']:$item['width']; ?>" ></td>
                            <td><input type="text" class="form-control integer_positive" name="selected_seasons[<?php echo $season['value'];?>][plants_initial]" value="<?php echo $is_edit?$selected_seasons[$season['value']]['plants_initial']:$item['plants_initial']; ?>" ></td>
                            <td><input type="text" class="form-control integer_positive" name="selected_seasons[<?php echo $season['value'];?>][optimum_transplanting_days]" value="<?php echo $is_edit?$selected_seasons[$season['value']]['optimum_transplanting_days']:$item['optimum_transplanting_days']; ?>" ></td>
                            <td><input type="text" class="form-control" name="selected_seasons[<?php echo $season['value'];?>][expected_seed_per_gram]" value="<?php echo $is_edit?$selected_seasons[$season['value']]['expected_seed_per_gram']:$item['expected_seed_per_gram']; ?>" ></td>
                            <td><input type="text" class="form-control" name="selected_seasons[<?php echo $season['value'];?>][number_seed_per_gram]" value="<?php echo $is_edit?$selected_seasons[$season['value']]['number_seed_per_gram']:$item['number_seed_per_gram']; ?>" ></td>
                        </tr>
                        <tr>
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

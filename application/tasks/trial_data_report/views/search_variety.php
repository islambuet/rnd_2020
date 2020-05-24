<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI=& get_instance();
$user=User_helper::get_user();
?>

    <form id="report_form" class="system_ajax" action="<?php echo site_url($CI->controller_name.'/system_report');?>" method="post">
        <input type="hidden" class="system_save_new_status" name="system_save_new_status" value="0" />
        <input type="hidden" id="system_user_token" name="system_user_token" value="<?php echo time().'_'.$user->id; ?>" />
        <input type="hidden" name="search_items[year]" value="<?php echo $year; ?>" />
        <input type="hidden" name="search_items[season_id]" value="<?php echo $season_id; ?>" />
        <input type="hidden" name="search_items[crop_id]" value="<?php echo $crop_id; ?>" />
        <div class="card">
            <div class="card-header">
                <a class="btn-link" data-toggle="collapse" href="#LABEL_TITLE_SELECT_VARIETY">
                    <?php
                    echo $CI->lang->line('LABEL_TITLE_SELECT_VARIETY');
                    ?>
                </a>
            </div>
            <div id="LABEL_TITLE_SELECT_VARIETY" class="collapse show">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 text-center pt-2 pb-2 text-danger" style="border: 1px solid rgba(0,0,0,.125);">
                            <label><input type="checkbox" data-type='select_variety_id' class="select_all"><?php echo $CI->lang->line('LABEL_SELECT_ALL'); ?></label>
                        </div>
                        <?php
                        foreach($varieties as $variety)
                        {

                                ?>
                                <div class="col-6 col-sm-4 pt-2 pb-2" style="border: 1px solid rgba(0,0,0,.125);">
                                    <input id="<?php echo 'variety_'.$variety['variety_id'];?>" name="search_items[variety_ids][]" type="checkbox" value="<?php echo $variety['variety_id'];?>" class="select_variety_id">
                                    <label for="<?php echo 'variety_'.$variety['variety_id'];?>"><?php echo System_helper::get_variety_rnd_code($variety);?></label>
                                </div>
                            <?php
                        }
                        ?>
                    </div>

                    <div class="row mb-2">
                        <div class="col-4">
                            <label for="report_id" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_REPORT_NAME');?><span class="text-danger">*</span></label>
                        </div>
                        <div class="col-lg-4 col-8">
                            <select id="report_id" name="search_items[report_id]" class="form-control">
                                <?php
                                foreach($reports as $report)
                                {
                                    ?>
                                    <option value='<?php echo $report['value']; ?>'><?php echo $report['text']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4">
                        </div>
                        <div class="col-lg-4 col-8">
                            <button class="btn btn-md btn-block btn-primary" type="submit"><?php echo $CI->lang->line('LABEL_VIEW_REPORT'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI=& get_instance();
$user=User_helper::get_user();
?>
<div class="card mt-2">

    <div class="card-body">
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
        <div id="variety_container">
        </div>
        <div id="report_container">
        </div>
    </div>

</div>
<script type="text/javascript">

    jQuery(document).ready(function()
    {
        $(document).off('change','#year');
        $(document).on("change","#year",function()
        {
            $("#variety_container").html('');
            $("#report_container").html('');
        });
        $(document).off('change','#season_id');

        $(document).on("change","#season_id",function()
        {
            $("#variety_container").html('');
            $("#report_container").html('');
        });
        $(document).off('change', '#crop_id');

        $(document).on("change","#crop_id",function()
        {
            $("#variety_container").html('');
            $("#report_container").html('');
        });
    });
</script>
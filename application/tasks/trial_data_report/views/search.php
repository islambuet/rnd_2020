<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI=& get_instance();
$user=User_helper::get_user();
?>
<div class="card mt-2">

    <div class="card-body">
        <form id="report_form" class="system_ajax" action="<?php echo site_url($CI->controller_name.'/system_search_variety');?>" method="post">
            <input type="hidden" class="system_save_new_status" name="system_save_new_status" value="0" />
            <input type="hidden" id="system_user_token" name="system_user_token" value="<?php echo time().'_'.$user->id; ?>" />
            <div class="card">
                <div class="card-header">
                    <a class="btn-link" data-toggle="collapse" href="#LABEL_TITLE_SEARCH">
                        <?php
                        echo $CI->lang->line('LABEL_TITLE_SEARCH');
                        ?>
                    </a>
                </div>
                <div id="LABEL_TITLE_SEARCH" class="collapse show">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-4">
                                <label for="year" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_YEAR');?><span class="text-danger">*</span></label>
                            </div>
                            <div class="col-lg-4 col-8">
                                <select id="year" name="search_items[year]" class="form-control">
                                    <?php
                                    $cur_year=date("Y");
                                    for($i=2020;$i<=($cur_year+1);$i++)
                                    {
                                        ?>
                                        <option value='<?php echo $i; ?>' <?php if($i==$year){ echo ' selected';} ?>><?php echo $i; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4">
                                <label for="season_id" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_SEASON_NAME');?><span class="text-danger">*</span></label>
                            </div>
                            <div class="col-lg-4 col-8">
                                <select id="season_id" name="search_items[season_id]" class="form-control">
                                    <?php
                                    foreach($seasons as $season)
                                    {
                                        ?>
                                        <option value='<?php echo $season['id']; ?>' <?php if($season['id']==$season_id){ echo ' selected';} ?>><?php echo $season['name']; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4">
                                <label for="crop_id" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_CROP_NAME');?><span class="text-danger">*</span></label>
                            </div>
                            <div class="col-lg-4 col-8">
                                <select id="crop_id" name="search_items[crop_id]" class="form-control">
                                    <option value=""><?php echo $this->lang->line('LABEL_SELECT');?></option>
                                    <?php
                                    foreach($crops as $crop)
                                    {
                                        ?>
                                        <option value='<?php echo $crop['value']; ?>' ><?php echo $crop['text']; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2 d-none" id="type_id_container">
                            <div class="col-4">
                                <label for="type_id" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_TYPE_NAME');?></label>
                            </div>
                            <div class="col-lg-4 col-8">
                                <select id="type_id" name="search_items[type_id]" class="form-control">
                                    <option value=""><?php echo $this->lang->line('LABEL_SELECT');?></option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2 d-none" id="search_variety_container">
                            <div class="col-4">
                            </div>
                            <div class="col-lg-4 col-8">
                                <button class="btn btn-md btn-block btn-primary" type="submit"><?php echo $CI->lang->line('LABEL_SEARCH_VARIETY'); ?></button>
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
            $("#type_id").val("");
            var crop_id=$('#crop_id').val();
            if(crop_id>0)
            {
                $("#search_variety_container").removeClass('d-none');
                $("#type_id_container").removeClass('d-none');
                $('#type_id').html(get_dropdown_with_select(SYSTEM_TYPES[crop_id]));
            }
            else
            {
                $("#type_id_container").addClass('d-none');
                $("#search_variety_container").addClass('d-none');

            }
        });
        $(document).off('change', '#type_id');
    });
</script>
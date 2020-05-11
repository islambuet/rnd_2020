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
    'label'=>$CI->lang->line("BUTTON_REFRESH"),
    'class'=>'system_ajax',
    'href'=>site_url($CI->controller_name.'/system_search_add/'.$year)
);


$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));
?>
<div class="card mt-2">
    <div class="card-header font-weight-bold">
        <?php
        echo sprintf($CI->lang->line('LABEL_TITLE_SEARCH_NEW'),$year);
        ?>
    </div>
    <div class="card-body">

        <div class="row mb-2">
            <div class="col-4">
                <label for="crop_id" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_CROP_NAME');?><span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-4 col-8">
                <select id="crop_id" class="form-control">
                    <option value=""><?php echo $this->lang->line('LABEL_SELECT');?></option>
                    <?php
                    foreach($crops as $crop)
                    {
                        ?>
                        <option value='<?php echo $crop['value']; ?>'><?php echo $crop['text']; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row mb-2 d-none" id="type_id_container">
            <div class="col-4">
                <label for="type_id" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_TYPE_NAME');?><span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-4 col-8">
                <select id="type_id" class="form-control">
                    <option value=""><?php echo $this->lang->line('LABEL_SELECT');?></option>
                </select>
            </div>
        </div>
        <div class="row mb-2 d-none" id="variety_id_container">
            <div class="col-4">
                <label for="variety_id" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_VARIETY_NAME');?><span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-4 col-8">
                <select id="variety_id" class="form-control">
                    <option value=""><?php echo $this->lang->line('LABEL_SELECT');?></option>
                </select>
            </div>
        </div>
        <div id="report_container" class="d-none">
        </div>
    </div>
</div>
<script type="text/javascript">

    jQuery(document).ready(function()
    {
        system_pre_tasks({controller:'<?php echo $CI->router->class; ?>'});
        $(document).off('change','#crop_id');
        $(document).on("change","#crop_id",function()
        {
            $("#type_id_container").addClass('d-none');
            $("#variety_id_container").addClass('d-none');
            $("#report_container").addClass('d-none');
            $("#type_id").val("");
            $("#variety_id").val("");
            var crop_id=$('#crop_id').val();
            if(crop_id>0)
            {
                $("#type_id_container").removeClass('d-none');
                $("#variety_id_container").addClass('d-none');
                $('#type_id').html(get_dropdown_with_select(SYSTEM_TYPES[crop_id]));
            }
        });
        $(document).off('change','#type_id');
        $(document).on("change","#type_id",function()
        {
            $("#variety_id_container").addClass('d-none');
            $("#report_container").addClass('d-none');
            $("#variety_id").val("");
            var year='<?php echo $year; ?>';
            var type_id=$('#type_id').val();
            if(type_id>0)
            {
                $("#variety_id_container").removeClass('d-none');
                $.ajax({
                    url: '<?php echo site_url($CI->controller_name.'/system_get_drop_down_new_variety_list'); ?>',
                    type: 'POST',
                    datatype: "JSON",
                    data:{year:year,type_id:type_id},
                    success: function (data, status)
                    {

                    },
                    error: function (xhr, desc, err)
                    {
                        console.log("error");

                    }
                });
                //$('#type_id').html(get_dropdown_with_select(SYSTEM_TYPES[crop_id]));
            }
        });
        $(document).off('change','#variety_id');
        $(document).on("change","#variety_id",function()
        {
            var year='<?php echo $year; ?>';
            var variety_id=$('#variety_id').val();
            $("#report_container").addClass('d-none');
            if(variety_id>0)
            {
                $("#report_container").removeClass('d-none');
                $.ajax({
                    url: '<?php echo site_url($CI->controller_name.'/system_add'); ?>',
                    type: 'POST',
                    datatype: "JSON",
                    data:{year:year,variety_id:variety_id},
                    success: function (data, status)
                    {

                    },
                    error: function (xhr, desc, err)
                    {
                        console.log("error");

                    }
                });
            }
        });

    });
</script>

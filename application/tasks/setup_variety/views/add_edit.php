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
                    <label for="crop_id" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_CROP_NAME');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <select id="crop_id" class="form-control">
                        <option value=""><?php echo $this->lang->line('LABEL_SELECT');?></option>
                        <?php
                        foreach($crops as $crop)
                        {
                            ?>
                            <option value='<?php echo $crop['value']; ?>' <?php if($crop['value']==$item['crop_id']){ echo ' selected';} ?>><?php echo $crop['text']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row mb-2 <?php if(!($item['type_id']>0)){echo 'd-none';} ?>" id="type_id_container">
                <div class="col-4">
                    <label for="type_id" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_TYPE_NAME');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <select id="type_id" name="item[type_id]" class="form-control">
                        <option value=""><?php echo $this->lang->line('LABEL_SELECT');?></option>
                        <?php
                        foreach($types as $type)
                        {
                            ?>
                            <option value='<?php echo $type['value']; ?>' <?php if($type['value']==$item['type_id']){ echo ' selected';} ?>><?php echo $type['text']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
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
                    <label for="whose" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_WHOSE');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <select id="whose" name="item[whose]" class="form-control">
                        <option value="ARM" <?php if ($item['whose'] == "ARM") { echo "selected='selected'"; } ?> >ARM</option>
                        <option value="Principal" <?php if ($item['whose'] == "Principal") { echo "selected='selected'"; } ?> >Principal</option>
                        <option value="Competitor" <?php if ($item['whose'] == "Competitor") { echo "selected='selected'"; } ?> >Competitor</option>
                    </select>
                </div>
            </div>

            <div class="row mb-2 <?php if($item['whose']!='Principal'){echo 'd-none';} ?>" id="principal_id_container">
                <div class="col-4">
                    <label for="principal_id" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_PRINCIPAL_NAME');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <select id="principal_id" name="item[principal_id]" class="form-control">
                        <option value=""><?php echo $this->lang->line('LABEL_SELECT');?></option>
                        <?php
                        foreach($principals as $principal)
                        {?>
                            <option value="<?php echo $principal['value']?>" <?php if($principal['value']==$item['principal_id']){ echo "selected";}?>><?php echo $principal['text'];?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row mb-2 <?php if($item['whose']!='Competitor'){echo 'd-none';} ?>" id="competitor_id_container">
                <div class="col-4">
                    <label for="competitor_id" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_COMPETITOR_NAME');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <select id="competitor_id" name="item[competitor_id]" class="form-control">
                        <option value=""><?php echo $this->lang->line('LABEL_SELECT');?></option>
                        <?php
                        foreach($competitors as $competitor)
                        {?>
                            <option value="<?php echo $competitor['value']?>" <?php if($competitor['value']==$item['competitor_id']){ echo "selected";}?>><?php echo $competitor['text'];?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="ordering" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_ORDERING');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <input type="text" class="form-control integer_all" name="item[ordering]" id="ordering" value="<?php echo $item['ordering']; ?>" >
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="status" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_STATUS');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <select id="status" name="item[status]" class="form-control">
                        <!--<option value=""></option>-->
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
        system_pre_tasks({controller:'<?php echo $CI->router->class; ?>'});
        $(document).off('change','#whose');
        $(document).on("change","#whose",function()
        {
            var whose=$(this).val();
            $("#competitor_id").val('');
            $("#principal_id").val('');
            if(whose=='Competitor')
            {
                $("#competitor_id_container").removeClass('d-none');
                $("#principal_id_container").addClass('d-none');
            }
            else if(whose=='Principal')
            {
                $("#competitor_id_container").addClass('d-none');
                $("#principal_id_container").removeClass('d-none');
            }
            else
            {
                $("#competitor_id_container").addClass('d-none');
                $("#principal_id_container").addClass('d-none');
            }

        });
        $(document).off('change','#crop_id');
        $(document).on("change","#crop_id",function()
        {
            $("#type_id").val("");
            var crop_id=$('#crop_id').val();
            if(crop_id>0)
            {
                $("#type_id_container").removeClass('d-none');
                $('#type_id').html(get_dropdown_with_select(SYSTEM_TYPES[crop_id]));
            }
            else
            {
                $("#type_id_container").addClass('d-none');

            }
        });
    });
</script>

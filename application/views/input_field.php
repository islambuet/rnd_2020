<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//input_field ==info about the input
//
$CI=& get_instance();

if($input_field['type']==SYSTEM_INPUT_TYPE_TEXT)
{
    ?>
    <input type="text" name="<?php echo $default_data['name']; ?>" class="form-control <?php echo $input_field['class']; ?>" value="<?php echo $default_data['value']; ?>"/>
<?php
}
else if($input_field['type']==SYSTEM_INPUT_TYPE_TEXTAREA)
{
    ?>
    <textarea name="<?php echo $default_data['name']; ?>" class="form-control <?php echo $input_field['class']; ?>" ><?php echo $default_data['value']; ?></textarea>
<?php
}

else if($input_field['type']==SYSTEM_INPUT_TYPE_DATE)
{
    ?>
            <div class="input-group">
                <input type="text" name="<?php echo $default_data['name']; ?>" class="form-control datepicker <?php echo $input_field['class']; ?>" value="<?php echo $default_data['value']; ?>" readonly/>
                <div class="input-group-append datepicker_handeler" style="cursor: pointer;">
                    <span class="input-group-text"><i class="fe-calendar"></i></span>
                </div>
            </div>
    <?php
}

else if($input_field['type']==SYSTEM_INPUT_TYPE_DROPDOWN)
{
    ?>
    <select name="<?php echo $default_data['name']; ?>" class="form-control <?php echo $input_field['class']; ?>">
        <option value=""><?php echo $this->lang->line('LABEL_SELECT');?></option>
        <?php
        foreach(explode(',',trim($input_field['options'],',')) as $option)
        {
            ?>
            <option value='<?php echo trim($option); ?>' <?php if(trim($option)==$default_data['value']){ echo ' selected';} ?>><?php echo trim($option); ?></option>
        <?php
        }
        ?>
    </select>
<?php
}
else if($input_field['type']==SYSTEM_INPUT_TYPE_IMAGE)
{
    ?>
    <input type="file" class="custom-file-input" data-preview-container="#container_<?php echo $default_data['name']; ?>" name="<?php echo $default_data['name']; ?>">
    <label class="custom-file-label" data-browse="<?php echo $CI->lang->line('LABEL_BUTTON_UPLOAD');?>"><?php echo $CI->lang->line('LABEL_CHOOSE_PICTURE');?></label>
    <div id="container_<?php echo $default_data['name']; ?>">
        <img style="max-width: 250px;" src="<?php echo Upload_helper::$IMAGE_BASE_URL.$default_data['value']; ?>" alt="Image not found">
    </div>
<?php
}

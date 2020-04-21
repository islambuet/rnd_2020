<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI=& get_instance();

$action_buttons=array();
$action_buttons[]=array(
    'label'=>$CI->lang->line("BUTTON_CANCEL"),
    'class'=>'system_ajax',
    'href'=>site_url($CI->controller_url.'/'.$return_method)
);
$action_buttons[]=array(
    'type'=>'button',
    'label'=>$CI->lang->line("BUTTON_SAVE"),
    'class'=>'button_action_save',
    'data-target-element'=>'#save_form'
);
$action_buttons[]=array(
    'label'=>$CI->lang->line("BUTTON_REFRESH"),
    'class'=>'system_ajax',
    'href'=>site_url($CI->controller_url.'/system_preference/'.$return_method)
);
$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));
?>
<div class="card mt-2">
    <div class="card-header font-weight-bold">
        <?php echo $CI->lang->line('LABEL_TITLE_PREFERENCE_ADD_EDIT'); ?>
    </div>
    <div class="card-body">
        <form id="save_form" class="system_ajax" action="<?php echo site_url($CI->controller_url.'/system_save_preference');?>" method="post">
            <input type="hidden" id="method_name" name="return_method" value="<?php echo $return_method; ?>" />
            <div class="row">
                <div class="col-12 text-center text-danger mb-2">
                    <input type="checkbox" id="selectAllCheckbox" checked>
                    <label for="selectAllCheckbox">Select All</label>
                </div>
                <?php
                foreach($system_jqx_items as $key=>$jqx_item)
                {
                    if($jqx_item['jqx_column'])
                    {
                        ?>
                        <div class="col-6 col-sm-4 p-2">
                            <input id="<?php echo 'jqx_column_handler_'.$key;?>" name="preference_items[<?php echo $key;?>]" type="checkbox" value="1" <?php if($jqx_item['preference']){echo 'checked';}?>>
                            <label for="<?php echo 'jqx_column_handler_'.$key;?>"><?php echo $jqx_item['text']; ?></label>
                        </div>
                    <?php
                    }
                }
                ?>
            </div>


        </form>
    </div>
</div>



<script type="text/javascript">
    jQuery(document).ready(function()
    {
        $(document).off("click",'#selectAllCheckbox');
        $(document).on("click",'#selectAllCheckbox',function()
        {
            if($(this).is(':checked'))
            {
                $('#save_form input:checkbox').prop('checked', true);
            }
            else
            {
                $('#save_form input:checkbox').prop('checked', false);
            }
        });
    });

</script>

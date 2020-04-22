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
$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));


$controller_folder=dir(APPPATH.'controllers');
$array=array();
while(($controller=$controller_folder->read())!==false)
{
    if($controller=='.' || $controller=='..')
    {
        continue;
    }
    $array[]=pathinfo($controller,PATHINFO_FILENAME);
}
$controller_folder->close();
?>
<div class="card mt-2">
    <div class="card-header font-weight-bold">
        <?php
        if($item['id']>0)
        {
            echo sprintf($CI->lang->line('LABEL_TITLE_MODULE_TASK_EDIT'),$item['name']);
        }
        else
        {
            echo $CI->lang->line('LABEL_TITLE_MODULE_TASK_NEW');
        }

        ?>
    </div>
    <div class="card-body">
        <form id="save_form" class="system_ajax" action="<?php echo site_url($CI->controller_name.'/system_save_add_edit');?>" method="post">
            <input type="hidden" class="system_save_new_status" name="system_save_new_status" value="0" />
            <input type="hidden" id="id" name="id" value="<?php echo $item['id']; ?>" />
            <input type="hidden" id="system_user_token" name="system_user_token" value="<?php echo time().'_'.$user->user_id; ?>" />

            <div class="row mb-2">
                <div class="col-4">
                    <label for="name" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_MODULE_TASK_NAME');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <input type="text" name="item[name]" id="name" class="form-control" value="<?php echo $item['name']; ?>"/>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="name" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_CONTROLLER_NAME');?><span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-4 col-8">
                    <input type="text" name="item[controller]" id="controller" class="form-control" value="<?php echo $item['controller'] ?>" >
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
        </form>
    </div>
</div>
<script>
    jQuery(document).ready(function()
    {
        system_pre_tasks({controller:'<?php echo $CI->router->class; ?>'});

        var availableTags=<?php echo json_encode($array); ?>;
        $('#controller').autocomplete({source:availableTags});
    });
</script>

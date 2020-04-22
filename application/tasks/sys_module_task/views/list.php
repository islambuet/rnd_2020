<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI=& get_instance();
$action_buttons=array();
if(isset($CI->permissions['action1']) && ($CI->permissions['action1']==1))
{
    $action_buttons[]=array(
        'label'=>$CI->lang->line("BUTTON_NEW"),
        'class'=>'system_ajax',
        'href'=>site_url($CI->controller_name.'/system_add')
    );
}
$action_buttons[]=array(
    'label'=>$CI->lang->line("BUTTON_REFRESH"),
    'class'=>'system_ajax',
    'href'=>site_url($CI->controller_name)

);
$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));
?>
<div class="card mt-2">
    <div class="card-header font-weight-bold">
        <?php echo $CI->lang->line('LABEL_TITLE_MODULE_TASK_LIST'); ?>
    </div>
    <div class="card-body">
        <div class="overflow-auto" style="height: 500px">
            <table class="table table-hover table-bordered">
                <thead class="text-center thead-light">
                    <tr>
                        <th><?php echo $CI->lang->line("ID"); ?></th>
                        <th colspan="<?php echo $modules_tasks['max_level'];?>"><?php echo $CI->lang->line("LABEL_MODULE_TASK_NAME");?></th>
                        <th><?php echo $CI->lang->line("LABEL_MODULE_TYPE"); ?></th>
                        <th><?php echo $CI->lang->line("LABEL_CONTROLLER_NAME"); ?></th>
                        <th><?php echo $CI->lang->line("LABEL_ORDERING"); ?></th>

                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach($modules_tasks['tree'] as $item)
                    {
                        ?>
                        <tr>
                            <td><?php echo $item['module_task']['id']; ?></td>
                            <?php
                            for($i=1;$i<=$modules_tasks['max_level'];$i++)
                            {
                                ?>
                                <td class="text-primary">
                                    <?php
                                    if($i==$item['level'])
                                    {
                                        //echo $module_task['prefix'];
                                        ?>
                                        <a href="<?php echo site_url($CI->controller_name.'/system_edit/'.$item['module_task']['id']); ?>"><?php echo $item['module_task']['name']; ?></a>
                                    <?php
                                    }
                                    ?>
                                </td>
                            <?php
                            }
                            ?>
                            <td><?php if($item['module_task']['type']=='TASK'){echo $CI->lang->line('TASK');}else{echo $CI->lang->line('MODULE');} ?></td>
                            <td><?php echo $item['module_task']['ordering']; ?></td>
                            <td><?php echo $item['module_task']['controller']; ?></td>
                        </tr>
                    <?php
                    }
                ?>
                </tbody>

            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function()
    {
        system_pre_tasks({controller:'<?php echo $CI->router->class; ?>'});
    });
</script>

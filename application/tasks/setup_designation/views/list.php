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
        <?php echo $CI->lang->line('LABEL_TITLE_LIST'); ?>
    </div>
    <div class="card-body">
        <div class="overflow-auto" style="height: 500px">
            <table class="table table-hover table-bordered">
                <thead class="text-center thead-light">
                    <tr>
                        <th><?php echo $CI->lang->line("ID"); ?></th>
                        <th colspan="<?php echo $items['max_level'];?>"><?php echo $CI->lang->line("LABEL_NAME");?></th>
                        <th><?php echo $CI->lang->line("LABEL_ORDERING"); ?></th>

                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach($items['tree'] as $item)
                    {
                        ?>
                        <tr>
                            <td><?php echo $item['designation']['id']; ?></td>
                            <?php
                            for($i=1;$i<=$items['max_level'];$i++)
                            {
                                ?>
                                <td class="text-primary">
                                    <?php
                                    if($i==$item['level'])
                                    {
                                        //echo $module_task['prefix'];
                                        ?>
                                        <a class="system_ajax" href="<?php echo site_url($CI->controller_name.'/system_edit/'.$item['designation']['id']); ?>"><?php echo $item['designation']['name']; ?></a>
                                    <?php
                                    }
                                    ?>
                                </td>
                            <?php
                            }
                            ?>
                            <td><?php echo $item['designation']['ordering']; ?></td>

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

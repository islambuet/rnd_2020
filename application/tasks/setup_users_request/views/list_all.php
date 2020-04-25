<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI=& get_instance();
$user=User_helper::get_user();
$action_buttons=array();
$jqx_container='#system_jqx_container';
$action_buttons[]=array(
    'label'=>$CI->lang->line("BUTTON_LIST_PENDING"),
    'class'=>'system_ajax',
    'href'=>site_url($CI->controller_name.'/system_list')

);
if(isset($CI->permissions['action1']) && ($CI->permissions['action1']==1))
{
    $action_buttons[]=array(
        'label'=>$CI->lang->line("BUTTON_NEW"),
        'class'=>'system_ajax',
        'href'=>site_url($CI->controller_name.'/system_add')
    );
}
if(isset($CI->permissions['action4']) && ($CI->permissions['action4']==1))
{
    $action_buttons[]=array(
        'type'=>'button',
        'label'=>$CI->lang->line("BUTTON_DOWNLOAD_PRINT"),
        'class'=>'button_jqx_action_download',
        'data-title'=>$CI->lang->line('LABEL_TITLE_LIST'),
        'data-target-element'=>$jqx_container,
        'data-print'=>true
    );
}
if(isset($CI->permissions['action5']) && ($CI->permissions['action5']==1))
{
    $action_buttons[]=array(
        'type'=>'button',
        'label'=>$CI->lang->line("BUTTON_DOWNLOAD_NOPRINT"),
        'class'=>'button_jqx_action_download',
        'data-title'=>$CI->lang->line('LABEL_TITLE_LIST'),
        'data-target-element'=>$jqx_container
    );
}
if (isset($CI->permissions['action6']) && ($CI->permissions['action6'] == 1)) {
    $action_buttons[] = array
    (
        'label'=>$CI->lang->line("BUTTON_PREFERENCE"),
        'class'=>'system_ajax',
        'href' => site_url($CI->controller_name . '/system_preference/system_list_all')
    );
}
$action_buttons[]=array(
    'label'=>$CI->lang->line("BUTTON_REFRESH"),
    'class'=>'system_ajax',
    'href'=>site_url($CI->controller_name.'/system_list_all')

);

$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));

?>
<div class="card mt-2">
    <div class="card-header font-weight-bold">
        <?php echo $CI->lang->line('LABEL_TITLE_LIST_ALL'); ?>
    </div>
    <div class="card-body">
        <?php
        if(isset($CI->permissions['action6']) && ($CI->permissions['action6']==1))
        {
            $CI->load->view('jqx_column_handler',array('system_jqx_items'=>$system_jqx_items,'jqx_container'=>$jqx_container));
        }
        ?>
        <div id="<?php echo substr($jqx_container,1);?>">

        </div>
    </div>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
    $(document).ready(function ()
    {
        system_pre_tasks({controller:'<?php echo $CI->router->class; ?>'});

        var url = "<?php echo site_url($CI->controller_name.'/system_get_items_list_all'); ?>";
        // prepare the data
        var source =
        {
            dataType: "json",
            dataFields: [
                <?php
                foreach($system_jqx_items as $key=>$jqx_item)
                {
                ?>
                {
                    name: '<?php echo $key ?>',
                    type: '<?php echo $jqx_item['type']; ?>'
                    <?php
                    if(isset($jqx_item['data_attributes']))
                    {
                        foreach($jqx_item['data_attributes'] as $attr_key=>$attr_value)
                        {
                            echo ','.$attr_key.':'.$attr_value;
                        }
                    }
                    ?>
                },
                <?php
                }
                ?>
            ],
            url: url,
            type: 'POST'
        };
        var dataAdapter = new $.jqx.dataAdapter(source);
        // create jqxgrid.
        $("<?php echo $jqx_container; ?>").jqxGrid(
        {
            width: '100%',
            height:'350px',
            source: dataAdapter,
            columnsresize: true,
            columnsreorder: true,
            altrows: true,
            enablebrowserselection: true,

            pageable: true,
            sortable: true,
            filterable: true,
            showfilterrow: true,

            pagesize:50,
            pagesizeoptions: ['20', '50', '100', '200','300','500'],
            selectionmode: 'singlerow',

            columns: [
                <?php
                foreach($system_jqx_items as $key=>$jqx_item)
                {
                    if($jqx_item['jqx_column'])
                    {
                        ?>
                        {
                            text: '<?php echo $system_jqx_items[$key]['text']; ?>',
                            dataField: '<?php echo $key; ?>',
                            <?php
                            if(isset($jqx_item['column_attributes']))
                            {
                                foreach($jqx_item['column_attributes'] as $attr_key=>$attr_value)
                                {
                                    echo $attr_key.':'.$attr_value.',';
                                }
                            }
                            ?>
                            hidden: <?php echo $system_jqx_items[$key]['preference']?0:1;?>
                        },
                        <?php
                    }
                }
                ?>
            ]
        });
    });
</script>

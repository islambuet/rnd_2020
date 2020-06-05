<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI=& get_instance();
$user=User_helper::get_user();
$action_buttons=array();
$jqx_container='#system_jqx_container';
$action_buttons[]=array(
    'type'=>'button',
    'label'=>$CI->lang->line("BUTTON_DOWNLOAD_PRINT"),
    'class'=>'button_jqx_action_download',
    'data-title'=>$CI->lang->line('LABEL_TITLE_LIST'),
    'data-target-element'=>$jqx_container,
    'data-print'=>true
);
$action_buttons[]=array(
    'type'=>'button',
    'label'=>$CI->lang->line("BUTTON_DOWNLOAD_NOPRINT"),
    'class'=>'button_jqx_action_download',
    'data-title'=>$CI->lang->line('LABEL_TITLE_LIST'),
    'data-target-element'=>$jqx_container
);
$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));
?>
<div class="card mt-2">
    <div class="card-body">

        <div id="<?php echo substr($jqx_container,1);?>">

        </div>
    </div>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
    $(document).ready(function ()
    {
        system_pre_tasks({controller:'<?php echo $CI->router->class; ?>'});

        var url = "<?php echo site_url($CI->controller_name.'/system_get_items_list'); ?>";
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
            type: 'POST',
            data:JSON.parse('<?php echo json_encode($search_items);?>')
        };
        var dataAdapter = new $.jqx.dataAdapter(source);
        var cellsrenderer = function(row, column, value, defaultHtml, columnSettings, record)
        {
            var element = $(defaultHtml);
            element.css({'margin': '0px','width': '100%', 'height': '100%',padding:'5px'});
            //element.css({'margin': '0px',padding:'5px'});

            return element[0].outerHTML;

        };
        var filter_items_type_name=[];
        // create jqxgrid.
        $("<?php echo $jqx_container; ?>").jqxGrid(
        {
            width: '100%',
            height:'<?php echo $report['jqx_height'] ?>px',
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
            rowsheight: <?php echo $report['jqx_rowsheight'] ?>,
            columnsheight: <?php echo $report['jqx_columnsheight'] ?>,

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

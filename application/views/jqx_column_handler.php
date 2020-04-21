<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$CI=& get_instance();
?>
<div id="system_jqx_column_handler_parent_container" class="mb-3">
    <div class="card">
        <div class="card-header pb-0 pt-0" id="system_jqx_column_handler_container_label">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#system_jqx_column_handler_container" aria-expanded="false" aria-controls="system_jqx_column_handler_container">
                    <?php echo $CI->lang->line('LABEL_TITLE_JQX_COLUMN_HANDLER'); ?>
                </button>
            </h5>
        </div>
        <div id="system_jqx_column_handler_container" class="collapse" aria-labelledby="system_jqx_column_handler_container_label" data-parent="#system_jqx_column_handler_parent_container">
            <div class="card-body row">

                    <?php
                    foreach($system_jqx_items as $key=>$jqx_item)
                    {
                        if($jqx_item['jqx_column'])
                        {
                            ?>
                            <div class="col-6 col-sm-4 p-2">
                                <div class="form-check form-check-inline">
                                    <input id="<?php echo 'jqx_column_handler_'.$key;?>" class="form-check-input system_jqx_column_handler" data-target-element="<?php echo $jqx_container;?>" type="checkbox" value="<?php echo $key;?>" <?php if($jqx_item['preference']){echo 'checked';}?>>
                                    <label class="form-check-label" for="<?php echo 'jqx_column_handler_'.$key;?>"><?php echo $jqx_item['text']; ?></label>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>

            </div>
        </div>
    </div>
</div>
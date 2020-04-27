 <?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI=& get_instance();
$accordion_header=isset($accordion['header'])?$accordion['header']:'+ Basic Information';
$accordion_id=isset($accordion['div_id'])?$accordion['div_id']:'accordion_basic';
$accordion_show=(isset($accordion['show']) &&$accordion['show'])?'show':'';
$accordion_data=isset($accordion['data'])?$accordion['data']:array();
?>
 <div class="card mb-2">
     <div class="card-header">
         <a class="btn-link" data-toggle="collapse" href="#<?php echo $accordion_id;?>">
             <?php echo $accordion_header; ?>
         </a>
     </div>
     <div id="<?php echo $accordion_id;?>" class="card-body collapse row p-0 m-0 <?php echo $accordion_show;?>">
         <?php
            foreach($accordion_data as $info)
            {
            ?>
                <div class="<?php echo $info['class'];?>" style="border: 1px solid rgba(0,0,0,.125);">
                    <?php echo $info['text'];?>
                </div>
            <?php
            }
         ?>
     </div>
 </div>

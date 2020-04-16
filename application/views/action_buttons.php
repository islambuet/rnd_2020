<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
//type ==link|button
//label == Button text
//rest as attribute
    $CI = & get_instance();
?>
<div class="card d-print-none">
    <div class="card-body">
        <?php
        foreach($action_buttons as $button)
        {
            $type='link';
            $label='LABEL';
            $classes='btn btn-dark mb-1';
            $attributes='';
            foreach($button as $key=>$value)
            {
                if($key=='type')
                {
                    $type=$value;
                }
                elseif($key=='label')
                {
                    $label=$value;
                }
                elseif($key=='class')
                {
                    $classes.=' '.$value;
                }
                else
                {
                    $attributes.=' '.$key.'="'.$value.'"';
                }
            }

            $attributes='class="'.$classes.'"'.$attributes;
            ?>

                <?php
                if($type=='link')
                {
                    ?>
                    <a <?php echo $attributes; ?>><?php echo $label; ?></a>
                    <?php
                }
                elseif($type=='button')
                {
                    ?>
                    <button <?php echo $attributes; ?>><?php echo $label; ?></button>
                    <?php
                }
                ?>


            <?php

        }
        ?>
    </div>
</div>


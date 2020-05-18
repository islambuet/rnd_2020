<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
$CI = & get_instance();
$user=User_helper::get_user();
?>
<nav id="system_left_sidebar" class="sidebar d-print-none">
    <ul class="list-unstyled">
        <li><a class="system_ajax" href="<?php echo site_url(); ?>">Dashboard</a></li>
        <?php
            $menu=User_helper::get_html_menu();
            echo $menu;
        //trial menu section
        $results=Query_helper::get_info(TABLE_RND_SETUP_TRIAL_DATA_FORM,array('id value','name text'),array('status !="'.SYSTEM_STATUS_DELETE.'"'),0,0,array('ordering ASC','id ASC'));
        if(strlen($user->trail_data_edit)>2)
        {
            ?>
            <li>
                <a href="#left_menu_trail_data_edit" data-toggle="collapse" aria-expanded="false" class="">Trial Data Entry<span class="fe-menu-arrow"></span></a>
                <ul class="list-unstyled collapse" id="left_menu_trail_data_edit" style="">
                    <?php
                    foreach($results as $result)
                    {
                        if(strpos($user->trail_data_edit, ','.$result['value'].',') !== FALSE)
                        {
                        ?>
                        <li><a class="system_ajax" href="<?php echo site_url('trial_data_entry/index/'.$result['value']); ?>"><?php echo $result['text']; ?></a></li>
                        <?php
                        }
                    }
                    ?>
                </ul>
            </li>
            <?php
        }
        if(strlen($user->trail_data_report)>2)
        {
        ?>
        <li><a class="system_ajax" href="<?php echo site_url('trail_data_report'); ?>">Trail Data Report</a></li>
        <?php
        }
        ?>
    </ul>
</nav>
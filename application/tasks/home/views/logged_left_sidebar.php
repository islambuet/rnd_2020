<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
$CI = & get_instance();

?>
<nav id="system_left_sidebar" class="sidebar d-print-none">
    <ul class="list-unstyled">
        <li><a class="system_ajax" href="<?php echo site_url(); ?>">Dashboard</a></li>
        <?php
            $menu=User_helper::get_html_menu();
            echo $menu;
        ?>
    </ul>
</nav>
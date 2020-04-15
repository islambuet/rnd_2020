<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
$CI = & get_instance();

?>
<header id="system_top_bar" class="d-print-none">
    <!-- LOGO -->
    <div id="logo_container" class="d-none d-lg-block d-xl-block float-left">
        <img style="height: 25px;" src="images/logo-lg.png" alt="">
    </div>
    <!-- Left Sidebar handler -->
    <button id="handler_left_sidebar" class="handler-sidebar float-left">
        <i class="fe-menu"></i>
    </button>
    <!-- Right Sidebar handler disabled -->
    <!-- <button id="handler_right_sidebar" class="handler-sidebar float-right">
        <i class="fe-bell"></i>
    </button> -->
    <!-- Users options -->
    <ul class="list-unstyled float-right mb-0">
        <li class="dropdown">
            <a class="nav-link dropdown-toggle mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <img src="images/avatar-1.jpg" alt="user-image" class="rounded-circle" style="height: 32px;width: 32px;">
                                <span class="pro-user-name ml-1">
                                    Shaiful Islam  <i class="mdi mdi-chevron-down"></i>
                                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="fe-user"></i>
                    <span>Change Profile Picture</span>
                </a>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="fe-lock"></i>
                    <span>Change Password</span>
                </a>

                <div class="dropdown-divider"></div>
                <a href="<?php echo site_url('home/logout');?>" class="system_ajax dropdown-item">
                    <i class="fe-log-out"></i>
                    <span>Logout</span>
                </a>

            </div>
        </li>
    </ul>
</header>
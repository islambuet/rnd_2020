<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
$CI = & get_instance();
$user=User_helper::get_user();
?>
<header id="system_top_bar" class="d-print-none">
    <!-- LOGO -->
    <div id="logo_container" class="d-none d-lg-block d-xl-block float-left">
        <img style="height: 25px;" src="<?php echo Upload_helper::$IMAGE_BASE_URL.'images/logo-lg.png'; ?>" alt="">
    </div>
    <!-- Left Sidebar handler -->
    <button id="handler_left_sidebar" class="handler-sidebar float-left">
        <i class="fe-menu"></i>
    </button>
    <!-- Right Sidebar handler -->
    <!-- <button id="handler_right_sidebar" class="handler-sidebar float-right">
        <i class="fe-bell"></i>
    </button> -->
    <!-- Users options -->
    <ul class="list-unstyled float-right mb-0">
        <li class="dropdown">
            <a class="nav-link dropdown-toggle mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <img src="<?php echo Upload_helper::$IMAGE_BASE_URL.$user->image_location; ?>" alt="Image" class="rounded-circle" style="height: 32px;width: 32px;">
                                <span class="pro-user-name ml-1">
                                    <?php echo $user->name;?>  <i class="mdi mdi-chevron-down"></i>
                                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <!-- item-->
                <a href="<?php echo site_url('user/profile_picture');?>" class="system_ajax dropdown-item">
                    <i class="fe-user"></i>
                    <span>Change Profile Picture</span>
                </a>

                <!-- item-->
                <a href="<?php echo site_url('user/edit_password');?>" class="system_ajax dropdown-item">
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
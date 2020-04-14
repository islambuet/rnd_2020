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
    <!-- Right Sidebar handler -->
    <button id="handler_right_sidebar" class="handler-sidebar float-right">
        <i class="fe-bell"></i>
    </button>
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
<nav id="system_left_sidebar" class="sidebar d-print-none">
    <ul class="list-unstyled">
<li>
    <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false">Home</a>
    <ul class="collapse list-unstyled" id="homeSubmenu">
        <li>
            <a href="#">Home 1</a>
        </li>
        <li>
            <a href="#">Home 2</a>
        </li>
        <li>
            <a href="#">Home 3</a>
        </li>
    </ul>
</li>
<li>
    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false">Pages<span class="fe-menu-arrow"></span></a>

    <ul class="collapse list-unstyled" id="pageSubmenu">
        <li>
            <a href="#">Page 1</a>
        </li>
        <li>
            <a href="#">Page 2</a>
        </li>
        <li>
            <a href="#">Page 3</a>
        </li>
        <li>
            <a href="#pageSubmenu2" data-toggle="collapse" aria-expanded="false">page 4<span class="fe-menu-arrow"></span></a>

            <ul class="collapse list-unstyled" id="pageSubmenu2">
                <li>
                    <a href="#">Home 1</a>
                </li>
                <li>
                    <a href="#">Home 2</a>
                </li>
                <li>
                    <a href="#">Home 3</a>
                </li>
            </ul>
        </li>
    </ul>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact4</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact3</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact2</a>
</li>
<li>
    <a href="#">Portfolio</a>
</li>
<li>
    <a href="#">Contact1</a>
</li>
</ul>
</nav>
<nav id="system_right_sidebar" class="sidebar d-print-none">
    <ul class="list-unstyled" style="margin-bottom: 70px">
        <li>
            <a href="#pageSubmenu11" data-toggle="collapse" aria-expanded="false">Pages<span class="fe-menu-arrow"></span></a>

            <ul class="collapse list-unstyled" id="pageSubmenu11">
                <li>
                    <a href="#">Page 1</a>
                </li>
                <li>
                    <a href="#">Page 2</a>
                </li>
                <li>
                    <a href="#">Page 3</a>
                </li>
                <li>
                    <a href="#pageSubmenu2" data-toggle="collapse" aria-expanded="false">page 4<span class="fe-menu-arrow"></span></a>

                    <ul class="collapse list-unstyled" id="pageSubmenu2">
                        <li>
                            <a href="#">Home 1</a>
                        </li>
                        <li>
                            <a href="#">Home 2</a>
                        </li>
                        <li>
                            <a href="#">Home 3</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
</nav>
<div id="system_content">
    <h2>Heading started here</h2>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
    <div id="system_jqx_container">

    </div>
</div>
<script type="text/javascript">
    //initiate first ajax
    $(document).ready(function ()
    {
        $(document).ready(function ()
    {

        $(".sidebar").mCustomScrollbar({
            theme: "minimal"
        });
        $(document).off('click', '#handler_left_sidebar');
        $('#handler_left_sidebar').on('click', function ()
        {
            $('#system_left_sidebar').toggleClass('inactive');
            $('#system_content').toggleClass('inactive_left_sidebar');

            $('#system_right_sidebar').removeClass('active');

        });
        $(document).off('click', '#handler_right_sidebar');
        $('#handler_right_sidebar').on('click', function ()
        {
            $('#system_right_sidebar').toggleClass('active');
        });
    });
    });
</script>
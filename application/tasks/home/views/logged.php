<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
$CI = & get_instance();

?>
<?php
    $CI->load->view('home/logged_header');
    $CI->load->view('home/logged_left_sidebar');
    //$CI->load->view('home/logged_right_sidebar');
?>


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
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
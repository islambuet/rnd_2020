<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
$CI = & get_instance();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>A R MaliksSeeds</title>
        <link rel="shortcut icon" href="http://malikseeds.com/favicon.ico">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">



        <!-- Bootstrap CSS CDN -->
        <link href="<?php echo base_url('theme/css/bootstrap.min.css');?>" rel="stylesheet" type="text/css">
        <!-- Scrollbar Custom CSS -->
        <link href="<?php echo base_url('theme/css/jquery.mCustomScrollbar.css');?>" rel="stylesheet" type="text/css">
        <!-- icons CSS -->
        <link href="<?php echo base_url('theme/css/icons_feather.min.css');?>" rel="stylesheet" type="text/css">
        <!-- Jqx CSS -->
        <link rel="stylesheet" href="<?php echo base_url('theme/css/jqx/jqx.base.css'); ?>">
        <!-- Theme CSS -->
        <link href="<?php echo base_url('theme/css/style.css?version='.time());?>" rel="stylesheet" type="text/css">



    </head>
    <body>
        <!-- jQuery -->
        <script type="text/javascript" src="<?php echo base_url('theme/js/jquery-3.4.1.min.js'); ?>"></script>
        <!-- Popper.JS for scrollbar-->
        <script type="text/javascript" src="<?php echo base_url('theme/js/popper.min.js'); ?>"></script>
        <!-- jQuery Custom Scroller CDN -->
        <script type="text/javascript" src="<?php echo base_url('theme/js/jquery.mCustomScrollbar.concat.min.js'); ?>"></script>
        <!-- Bootstrap JS -->
        <script type="text/javascript" src="<?php echo base_url('theme/js/bootstrap.min.js'); ?>"></script>
        <!-- Jqx Grid JS -->
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxcore.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxgrid.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxscrollbar.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxgrid.edit.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxgrid.sort.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxgrid.pager.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxbuttons.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxcheckbox.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxlistbox.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxdropdownlist.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxmenu.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxgrid.filter.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxgrid.selection.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxgrid.columnsresize.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxdata.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxdatatable.js'); ?>"></script>
        <!--    only for color picker-->
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxcolorpicker.js'); ?>"></script>
        <!--    For column reorder-->
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxgrid.columnsreorder.js'); ?>"></script>
        <!--    For print-->
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxdata.export.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxgrid.export.js'); ?>"></script>
        <!--        for footer sum-->
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxgrid.aggregates.js'); ?>"></script>
        <!-- for header tool tip-->
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxtooltip.js'); ?>"></script>
        <!-- popup-->
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxwindow.js'); ?>"></script>
        <!-- for date-->
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxdatetimeinput.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('theme/js/jqx/jqxcalendar.js'); ?>"></script>

        <!-- For notificaiotn -->
        <script type="text/javascript" src="<?php echo base_url('theme/js/notify.js'); ?>"></script>
        <?php $CI->load->view('js_variables'); ?>
        <div id="system_main_container">
            <?php
            $user=User_helper::get_user();
            if($user)
            {
                $CI->load->view('home/logged');
            }
            ?>

        </div>
        <!-- loader when ajax request -->
        <div id="system_loading"><img src="<?php echo base_url('images/spinner.gif'); ?>"></div>




        <!-- System JS -->
        <script type="text/javascript" src="<?php echo base_url('theme/js/system/functions.js?version='.time()); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('theme/js/system/ajax.js?version='.time()); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('theme/js/system/html_elements_action.js?version='.time()); ?>"></script>
        <script type="text/javascript">
            //initiate first ajax
            $(document).ready(function ()
            {
                $.ajax({
                    url: location,
                    type: 'POST',
                    dataType: "JSON",
                    success: function (data, status)
                    {

                    },
                    error: function (xhr, desc, err)
                    {
                        console.log("Error Loading first template");

                    }
                });
            });
        </script>

    </body>

</html>

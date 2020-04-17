<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
$CI = & get_instance();
$CI->lang->load('home/offline');
?>
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card mb-0">
                <div class="card-body p-4">
                    <div class="text-center">
                        <img src="<?php echo Upload_helper::$IMAGE_BASE_URL.'images/logo-black.png'; ?>" alt="<?php echo $CI->lang->line('LABEL_COMPANY_NAME'); ?>" height="30">
                    </div>
                    <h4 class="text-primary text-center"><?php echo $CI->lang->line('LABEL_SITE_OFFLINE'); ?></h4>
                    <p class="text-muted text-center mb-0"><?php echo $CI->lang->line('MSG_SITE_OFFLINE1'); ?></p>
                    <p class="text-muted text-center mb-0"><?php echo $CI->lang->line('MSG_SITE_OFFLINE2'); ?></p>

                    <div class="text-center mt-2">
                        <img src="<?php echo Upload_helper::$IMAGE_BASE_URL.'images/maintenance.gif'; ?>" alt="<?php echo $CI->lang->line('LABEL_COMPANY_NAME'); ?>" class="w-75">
                    </div>
                </div>
            </div>
            <!-- end card-body -->
        </div>
        <!-- end card -->

    </div>
    <!-- end col -->

</div>
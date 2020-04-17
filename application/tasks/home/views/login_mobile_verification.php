<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$CI = & get_instance();
?>
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card mb-0 mt-5">

                <div class="card-body p-4">
                    <div>
                        <div class="text-center">
                            <img src="<?php echo Upload_helper::$IMAGE_BASE_URL.'images/logo-black.png'; ?>" alt="<?php echo $CI->lang->line('LABEL_COMPANY_NAME'); ?>" height="30">
                        </div>
                        <p class="alert alert-warning mt-2"><?php echo $CI->lang->line('WARNING_LOGIN_FAIL_1101'); ?></p>
                    </div>

                    <div class="mt-4">
                        <form class="system_ajax" action="<?php echo site_url('home/login');?>" method="post">
                            <div class="form-group">
                                <div class="col-12 p-0">
                                    <input class="form-control margin_bottom" type="text" name="code_verification" placeholder="Verification Code" value="" required>
                                </div>
                                <div class="col-12 mt-2 p-0">
                                    <a class="btn btn-primary system_ajax" href="<?php echo site_url('home/login');?>"><?php echo $CI->lang->line('LABEL_BACK'); ?></a>
                                    <input type="submit"  value="<?php echo $CI->lang->line('LABEL_VERIFY'); ?>" name="Login" class="btn btn-danger float-right">

                                </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- end card-body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end row -->
</div>
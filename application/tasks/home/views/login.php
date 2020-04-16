<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
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
                            <p class="mt-2"><?php echo $CI->lang->line('LABEL_LOGIN_FILL_UP'); ?></p>
                        </div>

                        <div class="mt-4">
                            <form class="system_ajax form-horizontal" action="<?php echo site_url('home/login');?>" method="post">
                                <div class="form-group row">
                                    <div class="col-12">
                                        <input class="form-control" type="text" name="username" required="" placeholder="<?php echo $CI->lang->line('LABEL_USERNAME'); ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input class="form-control" type="password" name="password" placeholder="<?php echo $CI->lang->line('LABEL_PASSWORD'); ?>">
                                        <div class="input-group-append eye_password" style="cursor: pointer;">
                                            <span class="input-group-text"><i class="fe-eye"></i></span>
                                        </div>
                                    </div>
                                <div class="form-group row text-center mt-2">
                                    <div class="col-12">
                                        <button class="btn btn-md btn-block btn-primary" type="submit"><?php echo $CI->lang->line('LABEL_SIGN_IN'); ?></button>
                                    </div>
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
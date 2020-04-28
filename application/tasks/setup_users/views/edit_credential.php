<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI=& get_instance();
$user=User_helper::get_user();
$action_buttons=array();
$action_buttons[]=array(
    'label'=>$CI->lang->line("BUTTON_CANCEL"),
    'class'=>'system_ajax',
    'href'=>site_url($CI->controller_name)
);
$action_buttons[]=array(
    'label'=>$CI->lang->line("BUTTON_REFRESH"),
    'class'=>'system_ajax',
    'href'=>site_url($CI->controller_name.'/system_edit_credential/'.$user_info['user_id'])
);
$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));
?>
<div class="card mt-2">
    <div class="card-header font-weight-bold">
        <?php
        echo sprintf($CI->lang->line('LABEL_TITLE_CHANGE_CREDENTIAL'),$user_info['name']);
        ?>
    </div>
    <div class="card-body">
        <div class="accordion" id="accordion">
            <div class="card">
                <div class="card-header">
                    <a class="btn-link" data-toggle="collapse" href="#password_container">
                        <?php echo $CI->lang->line('LABEL_TITLE_CHANGE_PASSWORD');?>
                    </a>
                </div>

                <div id="password_container" class="collapse"  data-parent="#accordion">
                    <div class="card-body">
                        <form id="save_form_password" class="system_ajax" action="<?php echo site_url($CI->controller_name.'/system_save_edit_password');?>" method="post">
                            <input type="hidden" name="system_user_token" value="<?php echo time().'_'.$user->user_id; ?>" />
                            <input type="hidden" name="id" value="<?php echo $user_info['user_id']; ?>" />
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="new_password" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_NEW_PASSWORD');?><span class="text-danger">*</span></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="new_password" id="new_password" value="">
                                        <div class="input-group-append eye_password" style="cursor: pointer;">
                                            <span class="input-group-text"><i class="fe-eye-off"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4 text-center text-lg-left pt-2 pt-lg-0">
                                    <button type="button" class="btn btn-dark mb-1 button_action_save" data-target-element="#save_form_password">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <a class="btn-link" data-toggle="collapse" href="#user_group_container">
                        <?php echo $CI->lang->line('LABEL_USER_GROUP');?>
                    </a>
                </div>

                <div id="user_group_container" class="collapse"  data-parent="#accordion">
                    <div class="card-body">
                        <form id="save_form_user_group" class="system_ajax" action="<?php echo site_url($CI->controller_name.'/system_save_edit_user_group');?>" method="post">
                            <input type="hidden" name="system_user_token" value="<?php echo time().'_'.$user->user_id; ?>" />
                            <input type="hidden" name="id" value="<?php echo $user_info['user_id']; ?>" />
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="user_group" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_USER_GROUP');?><span class="text-danger">*</span></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <select id="user_group" name="user_group" class="form-control">
                                        <option value=""><?php echo $this->lang->line('LABEL_SELECT');?></option>
                                        <?php
                                        foreach($user_groups as $user_group)
                                        {?>
                                            <option value="<?php echo $user_group['value']?>" <?php if($user_group['value']==$user_info['user_group']){ echo "selected";}?>><?php echo $user_group['text'];?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-4 text-center text-lg-left pt-2 pt-lg-0">
                                    <button type="button" class="btn btn-dark mb-1 button_action_save" data-target-element="#save_form_user_group">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function()
    {
        system_pre_tasks({controller:'<?php echo $CI->router->class; ?>'});
    });
</script>

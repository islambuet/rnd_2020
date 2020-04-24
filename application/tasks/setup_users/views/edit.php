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
    'type'=>'button',
    'label'=>$CI->lang->line("BUTTON_SAVE"),
    'class'=>'button_action_save',
    'data-target-element'=>'#save_form'
);
$action_buttons[]=array(
    'type'=>'button',
    'label'=>$CI->lang->line("BUTTON_CLEAR"),
    'class'=>'button_action_clear',
    'data-target-element'=>'#save_form'
);
$action_buttons[]=array(
    'label'=>$CI->lang->line("BUTTON_REFRESH"),
    'class'=>'system_ajax',
    'href'=>site_url($CI->controller_name.'/system_edit/'.$item['id'])
);

$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));
?>
<div class="card mt-2">
    <div class="card-header font-weight-bold">
        <?php
        echo sprintf($CI->lang->line('LABEL_TITLE_EDIT'),$user_info['name']);

        ?>
    </div>
    <div class="card-body">
        <form id="save_form" class="system_ajax" action="<?php echo site_url($CI->controller_name.'/system_save_add_edit');?>" method="post">
            <input type="hidden" class="system_save_new_status" name="system_save_new_status" value="0" />
            <input type="hidden" id="id" name="id" value="<?php echo $item['id']; ?>" />
            <input type="hidden" id="system_user_token" name="system_user_token" value="<?php echo time().'_'.$user->user_id; ?>" />
            <div id="accordion">

                <div class="card">
                    <div class="card-header">
                        <a class="btn-link" data-toggle="collapse" href="#collapse1">
                            <?php echo $CI->lang->line('LABEL_TITLE_CREDENTIALS');?>
                        </a>
                    </div>
                    <div id="collapse1" class="collapse">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_EMPLOYEE_ID');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <?php echo $item['employee_id'];?>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_EMPLOYEE_ID');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <?php echo $item['user_name'];?>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="ordering" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_ORDERING');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="user_info[ordering]" id="ordering" class="form-control" value="<?php echo $user_info['ordering'] ?>" >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <a class="collapsed btn-link" data-toggle="collapse" href="#collapse2">
                            <?php echo $CI->lang->line('LABEL_TITLE_GROUPS');?>
                        </a>
                    </div>
                    <div id="collapse2" class="collapse">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="designation" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_DESIGNATION_NAME');?><span class="text-danger">*</span></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <select id="designation" name="user_info[designation]" class="form-control">
                                        <option value="0"><?php echo $CI->lang->line('LABEL_SELECT'); ?></option>
                                        <?php
                                        foreach($designations as $designation)
                                        {?>
                                            <option value="<?php echo $designation['value']?>" <?php if($designation['value']==$user_info['designation']){ echo "selected";}?>><?php echo $designation['text'];?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="user_type_id" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_USER_TYPE');?><span class="text-danger">*</span></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <select id="user_type_id" name="user_info[user_type_id]" class="form-control">
                                        <option value=""><?php echo $this->lang->line('LABEL_SELECT');?></option>
                                        <?php
                                        foreach($user_types as $user_type)
                                        {?>
                                            <option value="<?php echo $user_type['value']?>" <?php if($user_type['value']==$user_info['user_type_id']){ echo "selected";}?>><?php echo $user_type['text'];?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="user_group" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_USER_GROUP');?><span class="text-danger">*</span></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <select id="user_group" name="user_info[user_group]" class="form-control">
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
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <a class="collapsed btn-link" data-toggle="collapse" href="#collapse3">
                            <?php echo $CI->lang->line('LABEL_TITLE_PERSONAL_INFO');?>
                        </a>
                    </div>
                    <div id="collapse3" class="collapse show">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="name" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_NAME');?><span class="text-danger">*</span></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="user_info[name]" id="name" class="form-control" value="<?php echo $user_info['name'];?>"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="email" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_EMAIL');?><span class="text-danger">*</span></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input id="email" type="text" name="user_info[email]" class="form-control" value="<?php echo $user_info['email'];?>"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="nid" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_NID');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="user_info[nid]" id="nid" class="form-control" value="<?php echo $user_info['nid'];?>"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="tin" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_TIN');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="user_info[tin]" id="tin" class="form-control" value="<?php echo $user_info['tin'];?>"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="father_name" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_FATHER_NAME');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="user_info[father_name]" id="father_name" class="form-control" value="<?php echo $user_info['father_name'];?>"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="mother_name" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_MOTHER_NAME');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="user_info[mother_name]" id="mother_name" class="form-control" value="<?php echo $user_info['mother_name'];?>"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="date_birth" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_DATE_BIRTH');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <div class="input-group">
                                        <input type="text" name="user_info[date_birth]" id="date_birth" class="form-control datepicker" value="<?php echo System_helper::display_date($user_info['date_birth']);?>" readonly/>
                                        <div class="input-group-append datepicker_handeler" style="cursor: pointer;">
                                            <span class="input-group-text"><i class="fe-calendar"></i></span>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_GENDER');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" id="gender_male" type="radio" value="Male" <?php if($user_info['gender']=='Male'){echo 'checked';} ?> name="user_info[gender]">
                                        <label class="form-check-label" for="gender_male">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" id="gender_female" type="radio" value="Female" <?php if($user_info['gender']=='Female'){echo 'checked';} ?> name="user_info[gender]">
                                        <label class="form-check-label" for="gender_female">Female</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_MARITAL_STATUS');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" id="status_marital_married" type="radio" value="Married" <?php if($user_info['status_marital']=='Married'){echo 'checked';} ?> name="user_info[status_marital]">
                                        <label class="form-check-label" for="status_marital_married">Married</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" id="status_marital_nomarried" type="radio" value="Un-Married" <?php if($user_info['status_marital']=='Un-Married'){echo 'checked';} ?> name="user_info[status_marital]">
                                        <label class="form-check-label" for="status_marital_nomarried">Un-Married</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="spouse_name" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_SPOUSE_NAME');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="user_info[spouse_name]" id="spouse_name" class="form-control" value="<?php echo $user_info['spouse_name'];?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <a class="collapsed btn-link" data-toggle="collapse" href="#collapse6">
                            <?php echo $CI->lang->line('LABEL_TITLE_CONTACT');?>
                        </a>
                    </div>
                    <div id="collapse6" class="collapse">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="mobile_no" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_MOBILE_NO');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="user_info[mobile_no]" id="mobile_no" class="form-control" value="<?php echo $user_info['mobile_no'];?>"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="mobile_no_personal" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_MOBILE_NO_PERSONAL');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="user_info[mobile_no_personal]" id="mobile_no_personal" class="form-control" value="<?php echo $user_info['mobile_no_personal'];?>"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="contact_person" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_CONTACT_PERSON');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="user_info[contact_person]" id="contact_person" class="form-control" value="<?php echo $user_info['contact_person'];?>"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="contact_no" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_CONTACT_NO');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="user_info[contact_no]" id="contact_no" class="form-control" value="<?php echo $user_info['contact_no'];?>"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="blood_group" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_BLOOD_GROUP');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <select id="blood_group" name="user_info[blood_group]" class="form-control">
                                        <option value=""><?php echo $this->lang->line('SELECT');?></option>
                                        <option value="A+" <?php if($user_info['blood_group']=='A+'){ echo "selected";}?>>A+</option>
                                        <option value="A-" <?php if($user_info['blood_group']=='A-'){ echo "selected";}?>>A-</option>
                                        <option value="AB+" <?php if($user_info['blood_group']=='AB+'){ echo "selected";}?>>AB+</option>
                                        <option value="AB-" <?php if($user_info['blood_group']=='AB-'){ echo "selected";}?>>AB-</option>
                                        <option value="B+" <?php if($user_info['blood_group']=='B+'){ echo "selected";}?>>B+</option>
                                        <option value="B-" <?php if($user_info['blood_group']=='B-'){ echo "selected";}?>>B-</option>
                                        <option value="O+" <?php if($user_info['blood_group']=='O+'){ echo "selected";}?>>O+</option>
                                        <option value="O-" <?php if($user_info['blood_group']=='O-'){ echo "selected";}?>>O-</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <a class="collapsed btn-link" data-toggle="collapse" href="#collapse4">
                            <?php echo $CI->lang->line('LABEL_TITLE_ADDRESS');?>
                        </a>
                    </div>
                    <div id="collapse4" class="collapse">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="address_present" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_ADDRESS_PRESENT');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <textarea id="address_present" class="form-control" name="user_info[address_present]"><?php echo $user_info['address_present'];?></textarea>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="address_permanent" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_ADDRESS_PERMANENT');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <textarea id="address_permanent" class="form-control" name="user_info[address_permanent]"><?php echo $user_info['address_permanent'];?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <a class="collapsed btn-link" data-toggle="collapse" href="#collapse5">
                            <?php echo $CI->lang->line('LABEL_TITLE_JOB');?>
                        </a>
                    </div>
                    <div id="collapse5" class="collapse">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="date_join" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_DATE_JOIN');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <div class="input-group">
                                        <input type="text" name="user_info[date_join]" id="date_join" class="form-control datepicker" value="<?php echo System_helper::display_date($user_info['date_join']);?>" readonly/>
                                        <div class="input-group-append datepicker_handeler" style="cursor: pointer;">
                                            <span class="input-group-text"><i class="fe-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="salary_basic" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_SALARY_BASIC');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="user_info[salary_basic]" id="salary_basic" class="form-control" value="<?php echo $user_info['salary_basic'];?>"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="salary_other" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_SALARY_OTHER');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="user_info[salary_other]" id="salary_other" class="form-control" value="<?php echo $user_info['salary_other'];?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </form>
    </div>
</div>
<script type="text/javascript">

    jQuery(document).ready(function()
    {
        system_pre_tasks({controller:'<?php echo $CI->router->class; ?>'});
        $('.datepicker').datepicker({dateFormat : 'dd-M-yy',changeMonth: true,changeYear: true,yearRange: "-100:+0",showButtonPanel: true});
    });
</script>
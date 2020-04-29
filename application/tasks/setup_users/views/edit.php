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
        echo sprintf($CI->lang->line('LABEL_TITLE_EDIT'),$item['name']);

        ?>
    </div>
    <div class="card-body">
        <form id="save_form" class="system_ajax" action="<?php echo site_url($CI->controller_name.'/system_save_add_edit');?>" method="post">
            <input type="hidden" class="system_save_new_status" name="system_save_new_status" value="0" />
            <input type="hidden" id="id" name="id" value="<?php echo $item['id']; ?>" />
            <input type="hidden" id="system_user_token" name="system_user_token" value="<?php echo time().'_'.$user->id; ?>" />
            <div id="accordion">

                <div class="card">
                    <div class="card-header">
                        <a class="btn-link" data-toggle="collapse" href="#title_identification">
                            <?php echo $CI->lang->line('LABEL_TITLE_IDENTIFICATION');?>
                        </a>
                    </div>
                    <div id="title_identification" class="collapse">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="employee_id" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_EMPLOYEE_ID');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="item[employee_id]" id="employee_id" class="form-control" value="<?php echo $item['employee_id']; ?>">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="user_name" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_USER_NAME');?><span class="text-danger">*</span></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="item[user_name]" id="user_name" class="form-control" value="<?php echo $item['user_name']; ?>">
                                    <small class="form-text text-muted"><?php echo $CI->lang->line('LABEL_USER_NAME_RULE');?><</small>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="ordering" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_ORDERING');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="item[ordering]" id="ordering" class="form-control" value="<?php echo $item['ordering'] ?>" >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <a class="btn-link" data-toggle="collapse" href="#title_authentication">
                            <?php echo $CI->lang->line('LABEL_TITLE_AUTHENTICATION');?>
                        </a>
                    </div>
                    <div id="title_authentication" class="collapse">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="password" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_NEW_PASSWORD');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="item[password]" id="password" value="">
                                        <div class="input-group-append eye_password" style="cursor: pointer;">
                                            <span class="input-group-text"><i class="fe-eye-off"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="ordering" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_STATUS');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <select id="status" name="item[status]" class="form-control" tabindex="-1">
                                        <option value="<?php echo SYSTEM_STATUS_ACTIVE; ?>"
                                            <?php
                                            if($item['status']==SYSTEM_STATUS_ACTIVE)
                                            {
                                                echo ' selected';
                                            }
                                            ?> ><?php echo SYSTEM_STATUS_ACTIVE; ?>
                                        </option>
                                        <option value="<?php echo SYSTEM_STATUS_INACTIVE; ?>"
                                            <?php
                                            if($item['status']==SYSTEM_STATUS_INACTIVE)
                                            {
                                                echo ' selected';
                                            }
                                            ?> ><?php echo SYSTEM_STATUS_INACTIVE; ?>
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="remarks_status_change" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_REMARKS_STATUS_CHANGE');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <textarea id="remarks_status_change" class="form-control" name="remarks_status_change"></textarea>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="day_mobile_authentication_off_end" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_DAY_MOBILE_AUTHENTICATION_OFF_END');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <?php
                                    $auth_day=0;
                                    if($item['time_mobile_authentication_off_end']>time())
                                    {
                                        $auth_day=ceil(($item['time_mobile_authentication_off_end']-time())/(3600*24));
                                    }
                                    ?>
                                    <input type="text" id="time_mobile_authentication_off_end" name="item[time_mobile_authentication_off_end]" class="form-control float_type_positive " value="<?php echo $auth_day; ?>"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="max_logged_browser" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_MAX_LOGGED_BROWSER');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <select name="item[max_logged_browser]" class="form-control">
                                        <?php
                                        for($i=1;$i<10;$i++)
                                        {?>
                                            <option value="<?php echo $i;?>" <?php if($i==$item['max_logged_browser']){ echo "selected";}?>><?php echo $i;?></option>
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
                        <a class="collapsed btn-link" data-toggle="collapse" href="#title_groups">
                            <?php echo $CI->lang->line('LABEL_TITLE_GROUPS');?>
                        </a>
                    </div>
                    <div id="title_groups" class="collapse">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="user_group" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_USER_GROUP');?><span class="text-danger">*</span></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <select id="user_group" name="item[user_group]" class="form-control">
                                        <option value=""><?php echo $this->lang->line('LABEL_SELECT');?></option>
                                        <?php
                                        foreach($user_groups as $user_group)
                                        {?>
                                            <option value="<?php echo $user_group['value']?>" <?php if($user_group['value']==$item['user_group']){ echo "selected";}?>><?php echo $user_group['text'];?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="designation" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_DESIGNATION_NAME');?><span class="text-danger">*</span></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <select id="designation" name="item[designation]" class="form-control">
                                        <option value="0"><?php echo $CI->lang->line('LABEL_SELECT'); ?></option>
                                        <?php
                                        foreach($designations as $designation)
                                        {?>
                                            <option value="<?php echo $designation['value']?>" <?php if($designation['value']==$item['designation']){ echo "selected";}?>><?php echo $designation['text'];?></option>
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
                                    <select id="user_type_id" name="item[user_type_id]" class="form-control">
                                        <option value=""><?php echo $this->lang->line('LABEL_SELECT');?></option>
                                        <?php
                                        foreach($user_types as $user_type)
                                        {?>
                                            <option value="<?php echo $user_type['value']?>" <?php if($user_type['value']==$item['user_type_id']){ echo "selected";}?>><?php echo $user_type['text'];?></option>
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
                        <a class="btn-link" data-toggle="collapse" href="#title_contact_no">
                            <?php echo $CI->lang->line('LABEL_TITLE_CONTACT_NO');?>
                        </a>
                    </div>
                    <div id="title_contact_no" class="collapse">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="mobile_no" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_MOBILE_NO');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="item[mobile_no]" id="mobile_no" class="form-control" value="<?php echo $item['mobile_no'];?>"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="mobile_no_personal" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_MOBILE_NO_PERSONAL');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="item[mobile_no_personal]" id="mobile_no_personal" class="form-control" value="<?php echo $item['mobile_no_personal'];?>"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="email" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_EMAIL');?><span class="text-danger">*</span></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input id="email" type="text" name="item[email]" class="form-control" value="<?php echo $item['email'];?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <a class="collapsed btn-link" data-toggle="collapse" href="#title_personal_info">
                            <?php echo $CI->lang->line('LABEL_TITLE_PERSONAL_INFO');?>
                        </a>
                    </div>
                    <div id="title_personal_info" class="collapse">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="name" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_NAME');?><span class="text-danger">*</span></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="item[name]" id="name" class="form-control" value="<?php echo $item['name'];?>"/>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="nid" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_NID');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="item[nid]" id="nid" class="form-control" value="<?php echo $item['nid'];?>"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="tin" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_TIN');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="item[tin]" id="tin" class="form-control" value="<?php echo $item['tin'];?>"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="father_name" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_FATHER_NAME');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="item[father_name]" id="father_name" class="form-control" value="<?php echo $item['father_name'];?>"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="mother_name" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_MOTHER_NAME');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="item[mother_name]" id="mother_name" class="form-control" value="<?php echo $item['mother_name'];?>"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="date_birth" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_DATE_BIRTH');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <div class="input-group">
                                        <input type="text" name="item[date_birth]" id="date_birth" class="form-control datepicker" value="<?php echo System_helper::display_date($item['date_birth']);?>" readonly/>
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
                                        <input class="form-check-input" id="gender_male" type="radio" value="Male" <?php if($item['gender']=='Male'){echo 'checked';} ?> name="item[gender]">
                                        <label class="form-check-label" for="gender_male">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" id="gender_female" type="radio" value="Female" <?php if($item['gender']=='Female'){echo 'checked';} ?> name="item[gender]">
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
                                        <input class="form-check-input" id="status_marital_married" type="radio" value="Married" <?php if($item['status_marital']=='Married'){echo 'checked';} ?> name="item[status_marital]">
                                        <label class="form-check-label" for="status_marital_married">Married</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" id="status_marital_nomarried" type="radio" value="Un-Married" <?php if($item['status_marital']=='Un-Married'){echo 'checked';} ?> name="item[status_marital]">
                                        <label class="form-check-label" for="status_marital_nomarried">Un-Married</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="spouse_name" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_SPOUSE_NAME');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="item[spouse_name]" id="spouse_name" class="form-control" value="<?php echo $item['spouse_name'];?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <a class="collapsed btn-link" data-toggle="collapse" href="#LABEL_TITLE_ADDRESS">
                            <?php echo $CI->lang->line('LABEL_TITLE_ADDRESS');?>
                        </a>
                    </div>
                    <div id="LABEL_TITLE_ADDRESS" class="collapse">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="address_present" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_ADDRESS_PRESENT');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <textarea id="address_present" class="form-control" name="item[address_present]"><?php echo $item['address_present'];?></textarea>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="address_permanent" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_ADDRESS_PERMANENT');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <textarea id="address_permanent" class="form-control" name="item[address_permanent]"><?php echo $item['address_permanent'];?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <a class="collapsed btn-link" data-toggle="collapse" href="#LABEL_TITLE_JOB">
                            <?php echo $CI->lang->line('LABEL_TITLE_JOB');?>
                        </a>
                    </div>
                    <div id="LABEL_TITLE_JOB" class="collapse">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="date_join" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_DATE_JOIN');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <div class="input-group">
                                        <input type="text" name="item[date_join]" id="date_join" class="form-control datepicker" value="<?php echo System_helper::display_date($item['date_join']);?>" readonly/>
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
                                    <input type="text" name="item[salary_basic]" id="salary_basic" class="form-control" value="<?php echo $item['salary_basic'];?>"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="salary_other" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_SALARY_OTHER');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="item[salary_other]" id="salary_other" class="form-control" value="<?php echo $item['salary_other'];?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <a class="collapsed btn-link" data-toggle="collapse" href="#LABEL_TITLE_CONTACT">
                            <?php echo $CI->lang->line('LABEL_TITLE_CONTACT');?>
                        </a>
                    </div>
                    <div id="LABEL_TITLE_CONTACT" class="collapse">
                        <div class="card-body">

                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="contact_person" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_CONTACT_PERSON');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="item[contact_person]" id="contact_person" class="form-control" value="<?php echo $item['contact_person'];?>"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="contact_no" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_CONTACT_NO');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <input type="text" name="item[contact_no]" id="contact_no" class="form-control" value="<?php echo $item['contact_no'];?>"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="blood_group" class="font-weight-bold float-right"><?php echo $CI->lang->line('LABEL_BLOOD_GROUP');?></label>
                                </div>
                                <div class="col-lg-4 col-8">
                                    <select id="blood_group" name="item[blood_group]" class="form-control">
                                        <option value=""><?php echo $this->lang->line('LABEL_SELECT');?></option>
                                        <option value="A+" <?php if($item['blood_group']=='A+'){ echo "selected";}?>>A+</option>
                                        <option value="A-" <?php if($item['blood_group']=='A-'){ echo "selected";}?>>A-</option>
                                        <option value="AB+" <?php if($item['blood_group']=='AB+'){ echo "selected";}?>>AB+</option>
                                        <option value="AB-" <?php if($item['blood_group']=='AB-'){ echo "selected";}?>>AB-</option>
                                        <option value="B+" <?php if($item['blood_group']=='B+'){ echo "selected";}?>>B+</option>
                                        <option value="B-" <?php if($item['blood_group']=='B-'){ echo "selected";}?>>B-</option>
                                        <option value="O+" <?php if($item['blood_group']=='O+'){ echo "selected";}?>>O+</option>
                                        <option value="O-" <?php if($item['blood_group']=='O-'){ echo "selected";}?>>O-</option>
                                    </select>
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
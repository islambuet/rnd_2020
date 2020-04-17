<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['test']= 'test';//dummy config to avoid loading error config file
//history tables
CONST TABLE_SYSTEM_HISTORY=  'arm_rnd_2020.system_history';//all action
CONST TABLE_SYSTEM_HISTORY_HACK=  'arm_rnd_2020.system_history_hack';//illegal access
CONST TABLE_SYSTEM_HISTORY_MOBILE_SMS=  'arm_rnd_2020.system_history_mobile_sms';//Mobile sms history
CONST TABLE_SYSTEM_HISTORY_LOGIN_VERIFICATION_CODE=  'arm_rnd_2020.system_history_login_verification_code';//sms used or unused



CONST TABLE_SYSTEM_TASK=  'arm_rnd_2020.system_task';

CONST TABLE_SYSTEM_USER_GROUP=  'arm_rnd_2020.system_user_group';
CONST TABLE_SYSTEM_USER_GROUP_ROLE=  'arm_rnd_2020.system_user_group_role';
CONST TABLE_SYSTEM_USER_PREFERENCE=  'arm_rnd_2020.system_user_preference';

CONST TABLE_SYSTEM_SESSION=  'arm_rnd_2020.system_session';//login session
CONST TABLE_SYSTEM_CONFIGURATION=  'arm_rnd_2020.system_configuration';//configuration system values
CONST TABLE_SYSTEM_SETUP_PRINT=  'arm_sms_2018_19.system_setup_print';//printing page setup


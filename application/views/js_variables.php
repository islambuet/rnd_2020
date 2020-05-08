<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$CI = & get_instance();
$system_crops=Query_helper::get_info(TABLE_RND_SETUP_CROP,array('id value','name text'),array('status ="'.SYSTEM_STATUS_ACTIVE.'"'));
$results=Query_helper::get_info(TABLE_RND_SETUP_TYPE,array('id value','name text','crop_id'),array('status ="'.SYSTEM_STATUS_ACTIVE.'"'),0,0,array('ordering ASC'));
$system_types=array();
foreach($results as $result)
{
    $system_types[$result['crop_id']][]=$result;
}
?>
<script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";
    var resized_image_files=[];

    var SYSTEM_IMAGE_SIZE_TO_RESIZE=409600;//1372022=1.3mb,409600=400KB
    var SYSTEM_IMAGE_MAX_WIDTH=400;
    var SYSTEM_IMAGE_MAX_HEIGHT=300;

    var ALERT_SELECT_ONE_ITEM = "<?php echo $CI->lang->line('ALERT_SELECT_ONE_ITEM'); ?>";
    var SYSTEM_CROPS=JSON.parse('<?php echo json_encode($system_crops);?>');
    var SYSTEM_TYPES=JSON.parse('<?php echo json_encode($system_types);?>');

</script>
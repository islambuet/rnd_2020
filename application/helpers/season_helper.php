<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Season_helper
{
    public static $current_season = null;
    public static function get_current_season()
    {
        $CI = & get_instance();
        if (Season_helper::$current_season)
        {
            return Season_helper::$current_season;
        }
        else
        {

            $year=date("Y");
            //$time=time();
            $time=System_helper::get_time('01-01-2020');
            $results=Query_helper::get_info(TABLE_RND_SETUP_SEASON,'*',array('status !="'.SYSTEM_STATUS_DELETE.'"'));

            $seasons=array();
            foreach($results as $result)
            {
                $seasons[System_helper::get_time($result['season_start_date'].'-'.$year)]=$result;
            }
            ksort($seasons);
            Season_helper::$current_season=end($seasons);
            foreach($seasons as $key=>$season)
            {
                if($key<=$time)
                {
                    Season_helper::$current_season=$season;
                }
            }
            return Season_helper::$current_season;

        }
    }

}
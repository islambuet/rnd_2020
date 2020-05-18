<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Season_helper
{
    public static $current_season = null;
    public static $all_seasons = null;
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
            $time=time();
            $all_seasons=Season_helper::get_all_seasons();

            $seasons=array();
            foreach($all_seasons as $season)
            {
                $seasons[System_helper::get_time($season['season_start_date'].'-'.$year)]=$season;
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
    public static function get_all_seasons()
    {
        $CI = & get_instance();
        if (Season_helper::$all_seasons)
        {
            return Season_helper::$all_seasons;
        }
        else
        {
            $results=Query_helper::get_info(TABLE_RND_SETUP_SEASON,'*',array('status !="'.SYSTEM_STATUS_DELETE.'"'),0,0,array('ordering ASC'));
            Season_helper::$all_seasons=array();
            foreach($results as $result)
            {
                Season_helper::$all_seasons[$result['id']]=$result;
            }
            return Season_helper::$all_seasons;

        }
    }

}
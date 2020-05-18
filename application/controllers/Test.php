<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    public function index()
    {
        $this->load->helper('season');
        echo '<pre>';
        print_r(Season_helper::get_current_season());
        print_r(Season_helper::get_all_seasons());
        echo '</pre>';

    }
}

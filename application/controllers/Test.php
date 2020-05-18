<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    public function index()
    {
        $a[1]='hi';
        $a[2]='hi';
        echo '<pre>';
        print_r(json_encode($a,JSON_FORCE_OBJECT ));

        echo '</pre>';

    }
}

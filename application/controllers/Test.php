<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    public function index()
    {
        $a="amara sonar bangla";
        $b[$a]=100;
        echo '<pre>';
        print_r($b);
        echo '</pre>';

    }
}

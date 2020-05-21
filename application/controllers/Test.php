<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    public function index()
    {
        $data[]=100;
        $data[]=200;
        $data[]=300;
        $data[]=400;
        $data[]=500;
        $exp_data=0;
        $exp='min(a[1],a[2],a[3])';
        try{
            $exp_data=eval ('return '.str_replace('a[','$data[',$exp).';');
        }
        catch (Throwable $t)
        {

        }


        echo '<pre>';

        print_r($exp_data);
        echo '</pre>';

    }
}

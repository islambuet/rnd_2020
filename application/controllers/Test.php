<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    public function index()
    {
        /*$data[]=100;
        $data[]=200;
        $data[]=300;
        $data[]=400;
        $data[]=500;
        $exp_data=0;
        $exp='min(a[1],a[2]a[3])';
        try{
            $exp_data=eval ('return '.str_replace('a[','$data[',$exp).';');
        }
        catch (Throwable $t)
        {

        }


        echo '<pre>';

        print_r($exp_data);
        echo '</pre>';*/
       //echo System_helper::get_time('2020-05-31 01:16:17 PM');
        $data[]=200;
        $data[]=300;
        $data[]=400;
        $data[]=500;
        $exp_data=0;
        $exp='((a[1]/0)*a[3])';
        try{
            //$value=eval("return ".$exp."; return false;");

            $exp_data=@eval ('return '.str_replace('a[','$data[',$exp).';return 100;');
            var_dump($exp_data);
        }
        catch (Throwable $t)
        {
            echo "exception";
        }


    }
}

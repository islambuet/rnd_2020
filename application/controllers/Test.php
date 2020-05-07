<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    public function index()
    {
        for($i=1;$i<200;$i++)
        {


            //echo str_pad($i^020,3,'0').'-'.str_pad($i^21,3).'-'.str_pad($i^22,3).'-'.str_pad($i^23,3).'-'.str_pad($i^24,3).'<br>';
            //echo str_pad(117^020,3,'0').'-'.str_pad($i^21,3).'-'.str_pad($i^22,3).'-'.str_pad($i^23,3).'-'.str_pad($i^24,3).'<br>';
            //echo str_pad(1^020,3,'0').str_pad(170^020,3,'0').'<br>';
            //echo str_pad($i^020,3,'0',STR_PAD_LEFT).'-'.str_pad($i^020,3,'0',STR_PAD_LEFT).'-'.str_pad($i^020,3,'0',STR_PAD_LEFT).'-'.str_pad($i^020,3,'0',STR_PAD_LEFT).'-'.str_pad($i^020,3,'0',STR_PAD_LEFT).'<br>';
            //$a=str_pad($i^2020,4,'0',STR_PAD_LEFT);
            //$b=$a^2020;
            $a=str_pad($i,4,0,STR_PAD_LEFT)^2020;
            $b=$a^2020;
            echo $i.'-'.$a.'-'.$b.'<br>';
            $a=str_pad($i,4,0,STR_PAD_LEFT)^2021;
            $b=$a^2021;
            echo $i.'-'.$a.'-'.$b.'<br>';
            $a=str_pad($i,4,0,STR_PAD_LEFT)^2022;
            $b=$a^2022;
            echo $i.'-'.$a.'-'.$b.'<br>';
            $a=str_pad($i,4,0,STR_PAD_LEFT)^2023;
            $b=$a^2023;
            echo $i.'-'.$a.'-'.$b.'<br><br><br>';


        }
    }
}

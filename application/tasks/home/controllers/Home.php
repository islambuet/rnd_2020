<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Root_controller
{
    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {
        $this->login_page();

    }
    public function login()
    {
        $this->logged_page();

    }
    public function logout()
    {
        $this->login_page();
    }
}

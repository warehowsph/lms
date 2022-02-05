<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
} 

class Assmon extends Admin_Controller
{

    public $sch_setting_detail = array();

    public function __construct()
    {
        parent::__construct();
        
    }

    public function index()
    {
        $data['title'] = 'Assessment Monitoring';
        $_result      = $this->assmon_model->get();
        $data['result'] = $_result;
        $this->load->view('layout/header', $data);
        $this->load->view('assmon/show', $data);
        $this->load->view('layout/footer', $data);
    }    
}

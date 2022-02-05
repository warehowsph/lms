<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Googlemeet extends General_Controller {

    function __construct() {
        
        parent::__construct();
        $this->load->model('lesson_model');
        $this->load->model('general_model');
        $this->load->model('discussion_model');
        $this->load->library('mailsmsconf');
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'lms/googlemeet');
    }

    function index() {
        
        $data['title'] = 'Google Meet Settings';
        $data['role'] = $this->general_model->get_role();
        $data['classes'] = $this->general_model->get_classes();
        $data['subjects'] = $this->general_model->get_subjects();
        $data['list'] = $this->general_model->get_all_staff();

        // echo '<pre>';print_r($data['staff']);exit();


        $this->load->view('layout/header');
        $this->load->view('lms/googlemeet/index', $data);
        $this->load->view('layout/footer');
    }

    function zoom_accounts() {
        
        $data['title'] = 'Zoom Accounts';
        $data['role'] = $this->general_model->get_role();
        $data['classes'] = $this->general_model->get_classes();
        $data['subjects'] = $this->general_model->get_subjects();

        $data['list'] = $this->general_model->get_zoom_accounts();
        
        // echo '<pre>';print_r($data['list']);exit();


        $this->load->view('layout/header');
        $this->load->view('lms/googlemeet/zoom_accounts', $data);
        $this->load->view('layout/footer');
    }

    function google_meet_updated(){

        $data['id'] = $_REQUEST['account_id'];
        $data['google_meet'] = $_REQUEST['google_meet'];

        $result = $this->lesson_model->lms_update("staff",$data);
        
        $lesson_data['account_id'] = $_REQUEST['account_id'];
        $lesson_data['google_meet'] = $_REQUEST['google_meet'];
        $result = $this->lesson_model->lms_update("lms_lesson",$lesson_data,"account_id");

        $msg   = $this->lang->line('success_message');
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
            echo json_encode($array);
        // print_r($result);
        // if ($result)
        // {
            
        // }
        // else 
        // {
        //     $msg   = "There is something Wrong";
        //     $array = array('status' => 'failed', 'error' => '', 'message' => $msg);
        //     echo json_encode($array);
        // }
    }

    function zoom_updated(){

        $data['id'] = $_REQUEST['account_id'];
        $data['zoom'] = $_REQUEST['zoom'];

        $result = $this->lesson_model->lms_update("staff",$data);

        if ($result)
        {
            $msg   = $this->lang->line('success_message');
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
            echo json_encode($array);
        }
        else 
        {
            $msg   = "There is something Wrong";
            $array = array('status' => 'failed', 'error' => '', 'message' => $msg);
            echo json_encode($array);
        }
    }




}
 
?>
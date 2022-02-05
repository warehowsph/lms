<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Final_grading extends General_Controller {

    function __construct() {
        
        parent::__construct();

        $this->session->set_userdata('top_menu', 'Grading');
        $this->writedb = $this->load->database('write_db', TRUE);
        $this->load->model('general_model');
        $this->load->model('lesson_model');
        $this->load->model('setting_model');
        $this->load->model('final_grade_model');
        $this->load->model('assessment_model');

        date_default_timezone_set('Asia/Manila');
    
    }


    function index(){
        $data['classes'] = $this->general_model->get_classes();
        $data['sections'] = $this->general_model->get_sections();
        $data['subjects'] = $this->assessment_model->lms_get('subjects',"","","id,name");
        $data['quarters'] = $this->general_model->lms_get('grading_quarter',"","");


        // print_r($_REQUEST);
        
        if(isset($_REQUEST['submit'])){
            $submitted_data['grade'] = $_REQUEST['grade'];
            $submitted_data['section'] = $_REQUEST['section'];
            $submitted_data['subject'] = $_REQUEST['subject'];
            $submitted_data['term'] = $_REQUEST['term'];

            $data['list'] = $this->final_grade_model->get_students($submitted_data['grade'],$submitted_data['section']);
            $data['grades'] = $this->get_grades();
    

        }
        
        $this->load->view('layout/header');
        $this->load->view('lms/final_grading/index', $data);
        $this->load->view('layout/footer');
    }

    function student_grades(){

        $data['quarters'] = $this->general_model->lms_get('grading_quarter',"","");

            
        // print_r($_REQUEST);
        
        if(isset($_REQUEST['submit'])){
            $submitted_data['term'] = $_REQUEST['term'];

            $data['list'] = $this->get_student_grades();
            

        }
        
        $this->load->view('layout/student/header');
        $this->load->view('lms/final_grading/student_grades', $data);
        $this->load->view('layout/student/footer');
    }

    public function get_student_grades(){

        $data['session_id'] = $this->setting_model->getCurrentSession();

        // print_r($this->session->userdata());
        $student_data = $this->session->userdata();
        // exit;

        $data['term'] = $_REQUEST['term'];
        $data['student_id'] = $student_data['student']['student_id'];
        $data['class_id'] = $student_data['current_class']['class_id'];
        $data['section_id'] = $student_data['current_class']['section_id'];
        // $data['subject_id'] = $_REQUEST['subject'];

        $this->db->select("final_grade.grade,subjects.name");
        $this->db->where("session_id",$data['session_id']);
        $this->db->where("term",$data['term']);
        $this->db->where("class_id",$data['class_id']);
        $this->db->where("section_id",$data['section_id']);
        $this->db->where("student_id",$data['student_id']);
        $this->db->join("subjects","final_grade.subject_id = subjects.id");
        $grades = $this->db->from("final_grade")->get()->result_array();
        // $return_data = array();
        $return_data = $grades;
        // foreach ($grades as $grades_key => $grades_value) {
        //    $return_data[$grades_value['student_id']] = $grades_value;
        // }
        
        return $return_data;
    }

    public function grade_update(){

        $data['session_id'] = $this->setting_model->getCurrentSession();
        $data['term'] = $_REQUEST['term'];
        $data['class_id'] = $_REQUEST['class_id'];
        $data['section_id'] = $_REQUEST['section_id'];
        $data['subject_id'] = $_REQUEST['subject_id'];
        $data['student_id'] = $_REQUEST['student_id'];
        $data['teacher_id'] = $this->session->userdata('id');
        $data['grade'] = $_REQUEST['grade'];

        $this->db->where("session_id",$data['session_id']);
        $this->db->where("term",$data['term']);
        $this->db->where("class_id",$data['class_id']);
        $this->db->where("section_id",$data['section_id']);
        $this->db->where("subject_id",$data['subject_id']);
        $this->db->where("student_id",$data['student_id']);
        $this->db->delete('final_grade');

        $this->db->select("*");
        $this->db->where("session_id",$data['session_id']);
        $this->db->where("term",$data['term']);
        $this->db->where("class_id",$data['class_id']);
        $this->db->where("section_id",$data['section_id']);
        $this->db->where("subject_id",$data['subject_id']);
        $update_grade = $this->db->from("final_grade")->get()->result_array();

        // print_r($update_grade);

        $this->final_grade_model->lms_create("final_grade",$data);
    }


    public function get_grades(){

        $data['session_id'] = $this->setting_model->getCurrentSession();
        $data['term'] = $_REQUEST['term'];
        $data['class_id'] = $_REQUEST['grade'];
        $data['section_id'] = $_REQUEST['section'];
        $data['subject_id'] = $_REQUEST['subject'];

        $this->db->select("*");
        $this->db->where("session_id",$data['session_id']);
        $this->db->where("term",$data['term']);
        $this->db->where("class_id",$data['class_id']);
        $this->db->where("section_id",$data['section_id']);
        $this->db->where("subject_id",$data['subject_id']);
        $grades = $this->db->from("final_grade")->get()->result_array();
        $return_data = array();
        foreach ($grades as $grades_key => $grades_value) {
           $return_data[$grades_value['student_id']] = $grades_value;
        }
        return $return_data;
    }
    

}
 
?>
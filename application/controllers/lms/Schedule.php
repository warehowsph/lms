<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends General_Controller {
    public $current_function;
    function __construct() {

        parent::__construct();
        $this->load->model('assessment_model');
        $this->load->model('general_model');
        $this->load->model('class_model');
        $this->load->model('lesson_model');
        $this->session->set_userdata('top_menu', 'Download Center');
        $this->session->set_userdata('sub_menu', 'lms/assessment');
        $this->writedb = $this->load->database('write_db', TRUE);
    }

    public function index(){

        $this->session->set_userdata('top_menu', 'Download Center');
        $this->session->set_userdata('sub_menu', 'content/lms_schedule');
        $data['resources'] = site_url('backend/lms/');
        $data['list'] = $this->assessment_model->all_assessment();
        $data['role'] = $this->general_model->get_role();
        $account_id = $this->general_model->get_account_id();
        $data['lessons'] = $this->lesson_model->lesson_schedule_admin($account_id);
        $main_color_array = array("#9e0000","#9e5700","#9e8400","#969e00","#699e00","#2f9e00","#009e7e","#00779e","#001a9e","#4a009e","#8c009e","#9e005f","#9e0000");
        $color_array = $main_color_array;

        foreach ($data['lessons'] as $lesson_schedule_key => $lesson_schedule_value) {
            $lesson_schedule_json[$lesson_schedule_key] = new stdClass();
            $lesson_schedule_json[$lesson_schedule_key]->title = html_entity_decode($lesson_schedule_value['lesson_name'])." Teacher: ".html_entity_decode($lesson_schedule_value['last_name']);
            $lesson_schedule_json[$lesson_schedule_key]->start = date("c",strtotime($lesson_schedule_value['start_date']));
            $lesson_schedule_json[$lesson_schedule_key]->end = date("c",strtotime($lesson_schedule_value['end_date']));
            $lesson_schedule_json[$lesson_schedule_key]->allDay = false;
            $the_random = 0;
            $lesson_schedule_json[$lesson_schedule_key]->color = $color_array[$the_random];
            $lesson_schedule_json[$lesson_schedule_key]->sections = $lesson_schedule_value['sections'];
            $lesson_schedule_json[$lesson_schedule_key]->topic = html_entity_decode($lesson_schedule_value['lesson_name']);
            unset($color_array[$the_random]);
            if(!empty($color_array)){
                $color_array = array_values($color_array);
            }else{
                $color_array = $main_color_array;
            }
            
        }
        $data['lesson_schedule'] = json_encode($lesson_schedule_json);
        // echo '<pre>';print_r($data['lesson_schedule']);exit();

        if($data['role']=='admin'){
            $this->load->view('layout/header');
        }else{

            $this->load->view('layout/student/header');
        }

        $this->load->view('lms/schedule/index', $data);
        $this->load->view('layout/footer');
    }

    public function reports($assessment_id){

        $this->session->set_userdata('top_menu', 'Download Center');
        $this->session->set_userdata('sub_menu', 'content/assessment');
        $data['list'] = $this->assessment_model->all_assessment();

        $data['role'] = $this->general_model->get_role();
        $current_session = $this->setting_model->getCurrentSession();

        $data['assessment'] = $this->assessment_model->lms_get('lms_assessment',$assessment_id,"id")[0];

        $query = $this->db
        ->select("*")
        ->from("lms_assessment AS lms_a")
        ->join("students AS s","find_in_set(s.id,lms_a.assigned) <> 0","left")
        ->join("lms_assessment_sheets AS lms_as","lms_as.account_id = s.id","left")
        ->join("student_session AS ss","s.id = ss.student_id","left")
        ->join("classes AS c","c.id = ss.class_id","left")
        ->join("sections AS sc","sc.id = ss.section_id","left")
        ->where("ss.session_id",$current_session)
        ->get();
        $students = $query->result_array();

        $data['students'] = $students;

        if($data['role']=='admin'){
            $this->load->view('layout/header');
        }else{

            $this->load->view('layout/student/header');
        }

        $this->load->view('lms/assessment/reports', $data);
        $this->load->view('layout/footer');
    }

    public function get_sheets($id) {
        if($id){
            $assessment = $this->assessment_model->lms_get('lms_assessment',$id,"id")[0];
            $assessment_sheets = $this->assessment_model->assessment_sheets($id);

            
            $json_sheet = json_decode($assessment['sheet']);
            $responses['data'] = array();
            $array_pos = 0;

            //var_dump($json_sheet[0]->type);
            // echo '<pre>';print_r($assessment_sheets);exit();
            foreach ($assessment_sheets as $row) {
                $json_respond = json_decode($row['answer']);
                //var_dump($json_respond);
                $answers_count['data'] = array();
                $resp_pos = 0;
                // echo '<pre>';print_r($json_respond);exit();
                if ($json_respond != null || $json_respond != '') {
                    foreach($json_respond as $respond) {
                        // var_dump($respond);
                        // echo($respond->type);
                        
                        if ($respond->type != "long_answer" && $respond->type != "short_answer") {
                            if (strpos($respond->answer, '1') > -1) {
                                if ($array_pos == 0) {
                                    $responses['data'][] = array (
                                        'answer_choices' => explode(',', $json_sheet[$resp_pos]->option_labels),
                                        'respondents' => 1,
                                        'answers_count' =>  explode(',', $respond->answer)
                                    );
                                } else {
                                    $responses['data'][$resp_pos]['respondents'] = $responses['data'][$resp_pos]['respondents'] + 1;
            
                                    $answer = explode(',', $respond->answer);
                                    $answerIdx = 0;
                                    foreach($answer as $ans) {
                                        $responses['data'][$resp_pos]['answers_count'][$answerIdx] = (string)((int)$responses['data'][$resp_pos]['answers_count'][$answerIdx] + (int)$ans);
                                        $answerIdx++;
                                    }
                                }
                                
                            } else {
                                if ($array_pos == 0) {
                                    $responses['data'][] = array (
                                        'answer_choices' => explode(',', $json_sheet[$resp_pos]->option_labels),
                                        'respondents' => 0,
                                        'answers_count' => explode(',', $respond->answer)
                                    );
                                } else {
                                    //
                                }
                            }
                        } else {
                            $responses['data'][] = array (
                                'answer_choices' => array(''),
                                'respondents' => 0,
                                'answers_count' =>  array('')
                            );
                        }                   
        
                        //var_dump($responses['data']);
                        $resp_pos++;
                    }
                }           

                //var_dump($responses['data'][$array_pos]['respondents']);            
                $array_pos++;
            }

            //var_dump($responses['data']);
            echo json_encode($responses['data']);
        }
        
    }

    public function assigned(){

        $this->page_title = "Assigned";
        $this->data = $this->assessment_model->assigned_assessment($this->session->userdata('id'));
        $this->sms_view(__FUNCTION__);
    }


    public function save(){

        $data['assessment_name'] = $_REQUEST['assessment_name'];
        $data['account_id'] = $this->customlib->getStaffID();
        $data['assigned'] = $_REQUEST['assigned'];

        $assessment_id = $this->assessment_model->lms_create("lms_assessment",$data);

        redirect(site_url()."lms/assessment/edit/".$assessment_id);
    }

    public function edit($id){

        if($id){
            $data['id'] = $id;
            $data['assessment'] = $this->assessment_model->lms_get("lms_assessment",$id,"id")[0];
            $data['resources'] = site_url('backend/lms/');
            $data['students'] = $this->lesson_model->get_students("lms_lesson",$id,"id");
            $data['classes'] = $this->class_model->getAll();
            $data['class_sections'] = $this->lesson_model->get_class_sections();
            

            $this->load->view('lms/assessment/edit', $data);

            
        }
        
    }

    public function answer($id){
        $data['id'] = $id;
        $data['account_id'] = $this->general_model->get_account_id();
        $this->db->select("*");
        $this->db->where("account_id", $data['account_id']);
        $this->db->where("assessment_id",$id);
        $this->db->where("response_status",1);

        $query = $this->db->get("lms_assessment_sheets");
        $response = $query->result_array();
        
        $data['assessment'] = $this->assessment_model->lms_get("lms_assessment",$id,"id")[0];
        
        $data['resources'] = site_url('backend/lms/');

        
            
        if(!empty($response)){
            echo "<script>alert('Assesment has already been answered Account ID:".$data['account_id']."');window.location.replace('".site_url('lms/assessment/index')."')</script>";
            
            $this->load->view('lms/assessment/answer', $data);
        }else{
            $this->db->select("*");
            $this->db->where("account_id",$data['account_id']);
            $this->db->where("assessment_id",$id);
            $new_query = $this->db->get("lms_assessment_sheets");
            $new_response = $new_query->result_array();
            if(empty($new_response)){
                $assessment_data['assessment_id'] = $id;
                $assessment_data['account_id'] = $data['account_id'];
                $new_assessment_id = $this->assessment_model->lms_create("lms_assessment_sheets",$assessment_data);
                $new_response = $this->assessment_model->lms_get("lms_assessment_sheets",$new_assessment_id,"id");
            }
            $data['assessment_sheet'] = $new_response[0];
            
            $this->load->view('lms/assessment/answer', $data);
        }
        
    }

    public function review($id,$account_id=""){
        $data['id'] = $id;
        if($account_id){
            $data['account_id'] = $account_id;
        }else{
            $data['account_id'] = $this->general_model->get_account_id();
        }
        

        $this->db->select("*");
        $this->db->where("account_id", $data['account_id']);
        $this->db->where("assessment_id",$id);
        $this->db->where("response_status",1);

        $query = $this->db->get("lms_assessment_sheets");
        $response = $query->result_array();
        
        $data['assessment'] = $this->assessment_model->lms_get("lms_assessment",$id,"id")[0];
        $data['resources'] = site_url('backend/lms/');
            
        if(!empty($response)){
            echo "<script>alert('Assesment has already been answered Account ID:".$data['account_id']."');window.location.replace('".site_url('lms/assessment/index')."')</script>";
            
            $this->load->view('lms/assessment/answer', $data);

        }else{

            $this->db->select("*");
            $this->db->where("account_id",$data['account_id']);
            $this->db->where("assessment_id",$id);
            $new_query = $this->db->get("lms_assessment_sheets");
            $new_response = $new_query->result_array();

            if(empty($new_response)){
                $assessment_data['assessment_id'] = $id;
                $assessment_data['account_id'] = $data['account_id'];
                $new_assessment_id = $this->assessment_model->lms_create("lms_assessment_sheets",$assessment_data);
                $new_response = $this->assessment_model->lms_get("lms_assessment_sheets",$new_assessment_id,"id");
            }

            $data['assessment_sheet'] = $new_response[0];
            
            $this->load->view('lms/assessment/review', $data);
        }
        
    }

    public function update(){
        $data['id'] = $_REQUEST['id'];
        $data['sheet'] = $_REQUEST['sheet'];
        $data['assigned'] = $_REQUEST['assigned'];
        $sheet = (array)json_decode($data['sheet']);
        $total_score = 0;
        //convert to array
        foreach ($sheet as $answer_key => $answer_value) {
            $sheet[$answer_key] = (array)$answer_value;
            $total_score +=1;
        }
        //convert to array
        
        $data['total_score'] = $total_score;
        $this->assessment_model->lms_update("lms_assessment",$data);
    }

    public function update_survey_sheet(){
        $data['assessment_id'] = $_REQUEST['assessment_id'];
        $data['respond'] = $_REQUEST['respond'];
        $data['account_id'] = $_REQUEST['account_id'];
        $data['response_status'] = 1;
        $this->db->select("*");
        $this->db->where("survey_id", $data["survey_id"]);
        $this->db->where("account_id", $data["account_id"]);
        $data['date_updated'] = date("Y-m-d H:i:s");
        $this->writedb->update("survey_sheets", $data);
    }

    public function upload($id){
        
        // print_r(strpos($_FILES['survey_form']['type'], "pdf"));

        if(strpos($_FILES['assessment_form']['type'], "pdf")!==0){
            $tmp_name = $_FILES['assessment_form']['tmp_name'];
            $file_name = $this->assessment_model->id_generator("assessment").".pdf";
            $dest = FCPATH."uploads/lms_assessment/".$id."/".$file_name;
            if(!is_dir(FCPATH."uploads/lms_assessment/".$id)){
                mkdir(FCPATH."uploads/lms_assessment/".$id);
            }
            
            if(move_uploaded_file($tmp_name, $dest)){
                $data['id'] = $id;
                $data['assessment_file'] = $file_name;

                $this->assessment_model->lms_update("lms_assessment",$data);
                
                echo "<script>alert('Successfully uploaded');window.location.replace('".site_url('lms/assessment/edit/'.$id)."')</script>";
            }
        }else{
            echo "<script>alert('Only PDF files are allowed');window.location.replace('".site_url('lms/assessment/edit/'.$id)."')</script>";
        }
        
    }

    public function delete($id){
        $data['id'] = $id;
        if($this->assessment_model->lms_delete("lms_assessment",$data)){
            redirect(site_url("lms/assessment/index"));
        }
    }

    public function answer_submit(){
        
        $data['id'] = $_REQUEST['id'];
        $data['assessment_id'] = $_REQUEST['assessment_id'];
        $data['answer'] = $_REQUEST['answer'];
        $answer = (array)json_decode($data['answer']);
        $assessment = $this->assessment_model->lms_get("lms_assessment",$data['assessment_id'],"id")[0];
        $assessment_answer = (array)json_decode($assessment['sheet']);
        
        //convert to array
        foreach ($answer as $answer_key => $answer_value) {
            $answer[$answer_key] = (array)$answer_value;
        }
        foreach ($assessment_answer as $answer_key => $answer_value) {
            $assessment_answer[$answer_key] = (array)$answer_value;
        }
        //convert to array
        $score = 0;
        $total_score = 0;
        foreach ($answer as $answer_key => $answer_value) {
            $total_score += 1;
            $assessment_value = $assessment_answer[$answer_key];
            if($answer_value['type']=="multiple_choice"||$answer_value['type']=="multiple_answer"){

                if($answer_value['answer'] == $assessment_value['correct']){
                    $score += 1;
                }
            }else if($answer_value['type']=="short_answer"){
                if(in_array(strtolower($answer_value['answer']), explode(",", strtolower($assessment_value['correct'])))){
                    $score += 1;
                }
            }else{

            }

        }

        $data['score'] = $score;

        print_r($this->assessment_model->lms_update("lms_assessment_sheets",$data));
    }

    public function check_answers($assessment_id){
        
    }

    public function analysis($id){
        $this->page_title = "Item Analysis";
        $assessment_sheets = $this->assessment_model->assessment_sheets($id);
        $assessment = $this->assessment_model->lms_get("lms_assessment",$id,"id")[0];
        $data['data'] = $assessment_sheets;
        $data['assessment'] = $assessment;
        // echo '<pre>';print_r($assessment);exit();
        // $data['id'] = $id;
        $data['resources'] = site_url('backend/lms/');

        $this->load->view('lms/assessment/analysis', $data);
    }
}

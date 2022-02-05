<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Conduct_ssapamp extends General_Controller {

    function __construct() {
        
        parent::__construct();

        $this->session->set_userdata('top_menu', 'Conduct');
        $this->writedb = $this->load->database('write_db', TRUE);
        $this->load->model('general_model');
        $this->load->model('setting_model');
        $this->load->model('session_model');
        $this->load->model('conduct_model');
        $this->load->model('class_model');

        
        $this->load->model('grading_conduct_ssapamp_model');
        $this->load->model('grading_studentconduct_ssapamp_model');
        $this->load->model('grading_ssapamp_model');

        date_default_timezone_set('Asia/Manila');
        $this->sch_setting_detail = $this->setting_model->getSetting();

        $url = $_SERVER['SERVER_NAME'];

        if (strpos($url,'localhost') !== false) {
            $this->mode = "offline";
        }elseif(strpos($url,'192.') !== false||strpos($url,'172.') !== false) {
            $this->mode = "offline";
        }else{
            $this->mode = "online";
        }
    }

    function index()
    {        
        // $grade_level = $this->input->post('class_id');
        // if ($grade_level!="1") {
        //     if ($this->sch_setting_detail->conduct_grading_type == "letter")
        //         $this->conduct_1();
        //     else if ($this->sch_setting_detail->conduct_grading_type == "number")
                // $this->conduct_2();
        // } else {
            $this->conduct_3();
        // }
    }

    function conduct_1()
    {
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'Academics/conduct');

        $data['classlist'] = $this->general_model->get_classes();
        $data['quarter_list'] = $this->general_model->lms_get('grading_quarter',"","");
        $data['session_list'] = $this->session_model->getAllSession();
        $data['sch_setting'] = $this->sch_setting_detail;
        // $data['real_role'] = $this->general_model->get_real_role();
        $data['user_id'] = $this->general_model->get_account_id();      
        $data['legend_list'] = $this->conduct_model->get_conduct_legend_list();  

        $this->form_validation->set_rules('session_id', $this->lang->line('current_session'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('quarter_id', $this->lang->line('quarter'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('student_id', $this->lang->line('student'), 'trim|required|xss_clean');
        
        if ($this->input->server('REQUEST_METHOD') == "GET") {   
            $session = $this->input->post('session_id');
            $quarter = $this->input->post('quarter_id');
            $grade_level = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $student = $this->input->post('student_id');
            $data['session_id'] = $session;
            $data['quarter_id'] = $quarter;
            $data['class_id'] = $grade_level;
            $data['section_id']  = $section;
            $data['student_id'] = $student;

            $this->load->view('layout/header');
            $this->load->view('lms/conduct/index', $data);
            $this->load->view('layout/footer');
        } else {
            if ($this->form_validation->run() == false) {
                $this->load->view('layout/header');
                $this->load->view('lms/conduct/index', $data);
                $this->load->view('layout/footer');
            } 
            else {                
                $session = $this->input->post('session_id');
                $quarter = $this->input->post('quarter_id');
                $grade_level = $this->input->post('class_id');
                $section = $this->input->post('section_id');
                $student = $this->input->post('student_id');

                $class_record = $this->conduct_model->get_student_conduct_record($session, $quarter, $grade_level, $section, $student, $data['user_id']);
                $data['resultlist'] = $class_record;
                // print_r($class_record);die();
                // $data['quarter_list'] = $this->general_model->lms_get('grading_quarter',"","");
                // $data['legend_list'] = $this->conduct_model->get_conduct_legend_list();                
                // $data['user_id'] = $this->general_model->get_account_id();
                $data['student'] = $this->student_model->get($student);
                $data['session_id'] = $session;
                $data['quarter_id'] = $quarter;
                $data['class_id'] = $grade_level;
                $data['section_id']  = $section;
                $data['student_id'] = $student;

                $this->load->view('layout/header');
                $this->load->view('lms/conduct/index', $data);
                $this->load->view('layout/footer');
            }
        }        
    }

    function conduct_2() 
    {
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'Academics/conduct');

        $data['classlist'] = $this->general_model->get_classes();
        $data['quarter_list'] = $this->general_model->lms_get('grading_quarter',"","");
        $data['session_list'] = $this->session_model->getAllSession();
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['legend_list'] = $this->conduct_model->get_conduct_legend_list();
        $data['user_id'] = $this->general_model->get_account_id();
        // $class = $this->class_model->get();
        // $data['studentlist'] = $class;

        $this->form_validation->set_rules('session_id', $this->lang->line('current_session'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('quarter_id', $this->lang->line('quarter'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

        if ($this->input->server('REQUEST_METHOD') == "GET") {   
            $session = $this->input->post('session_id');
            $quarter = $this->input->post('quarter_id');
            $grade_level = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $data['session_id'] = $session;
            $data['quarter_id'] = $quarter;
            $data['class_id'] = $grade_level;
            $data['section_id']  = $section;

            $this->load->view('layout/header');
            $this->load->view('lms/conduct/conduct_numeric', $data);
            $this->load->view('layout/footer');
        } else {
            if ($this->form_validation->run() == false) {
                $this->load->view('layout/header');
                $this->load->view('lms/conduct/conduct_numeric', $data);
                $this->load->view('layout/footer');
            } 
            else {                
                $schoolyear = $this->input->post('session_id');
                $quarter = $this->input->post('quarter_id');
                $grade_level = $this->input->post('class_id');
                $section = $this->input->post('section_id');    

                $class_record = $this->conduct_model->get_student_conduct_record_numeric($schoolyear, $quarter, $grade_level, $section, $data['user_id']);
                $data['resultlist'] = $class_record;
                // print_r(json_encode($class_record));die();
                // print_r($class_record);die();
                $data['session_id'] = $schoolyear;
                $data['quarter_id'] = $quarter;
                $data['class_id'] = $grade_level;
                $data['section_id']  = $section;

                $this->load->view('layout/header');
                $this->load->view('lms/conduct/conduct_numeric', $data);
                $this->load->view('layout/footer');
            }
        }
    }

    function conduct_3() 
    {
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'Academics/conduct');

        $data['classlist'] = $this->general_model->get_classes();
        $data['quarter_list'] = $this->general_model->lms_get('grading_quarter',"","");
        $data['session_list'] = $this->session_model->getAllSession();
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['legend_list'] = $this->conduct_model->get_conduct_legend_list();
        $data['user_id'] = $this->general_model->get_account_id();

        $data['Subjects'] = $this->grading_conduct_ssapamp_model->getList();
        // $class = $this->class_model->get();
        // $data['studentlist'] = $class;

        $data['legend_list'] = $this->grading_conduct_ssapamp_model->getLegend();

        $this->form_validation->set_rules('session_id', $this->lang->line('current_session'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('quarter_id', $this->lang->line('quarter'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

        $result1 = $this->grading_ssapamp_model->getLevelId("Pre-Kinder");
        $level_classid = $result1[0]->id;
        $prekinderid= $result1[0]->id;

        $result2 = $this->grading_ssapamp_model->getLevelId("Kindergarten");
        $kinderid= $result2[0]->id;

        $data['prekinder'] = $level_classid;
        $data['kinder'] = $level_classid;
        $data['level_classid'] = $level_classid;
        $data['conductformat'] = "0";
        if ($this->input->server('REQUEST_METHOD') == "GET") {   
            $schoolyear = $this->input->post('session_id');
            $session = $this->input->post('session_id');
            $quarter = $this->input->post('quarter_id');
            $grade_level = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $student_id = $this->input->post('student_id');
            $data['session_id'] = $session; // schoolyear
            $data['quarter_id'] = $quarter; // semester
            $data['class_id'] = $grade_level; // level
            $data['section_id']  = $section; // sectionid
            $data['student_id'] = $student_id;

            if ($grade_level==$prekinderid or $grade_level==$kinderid) {
                $l  =  $this->getGrading();        
                $data['legend_record'] =  $l;
                $data['conductformat'] = "1";
                $studentgradelist = $this->grading_studentconduct_ssapamp_model->getStudentGradeList($student_id,$grade_level,$section,$session,$quarter);
                if ( $studentgradelist) {
                    $g1 = $this->fetch_grades( $student_id,$grade_level,$section,$session,$quarter);
                    $data['resultlist'] = $g1['db_grades'];
                } else {
                    if (empty($student_id) or $grade_level=="0") { 
                    } else {
                        $this->post_initial_grade($student_id,$grade_level,$section,$session,$quarter);
                        $studentgradelist = $this->grading_studentconduct_ssapamp_model->getStudentGradeList($student_id,$grade_level,$section,$session,$quarter);
                        $g1 = $this->fetch_grades($student_id,$grade_level,$section,$session,$quarter);
                        $data['resultlist'] = $g1['db_grades'];
                    }
                    
                } 
            }
            else {
                // $class_record = $this->conduct_model->get_student_conduct_record_numeric($schoolyear, $quarter, $grade_level, $section, $data['user_id']);
                // $data['resultlist'] = $class_record;
            }

            $this->load->view('layout/header');
            $this->load->view('lms/conduct_ssapamp/conduct_ssa2', $data);
            // $this->load->view('lms/conduct_ssapamp/class_record_per_student_ssa', $data);
            $this->load->view('layout/footer');
        } else {
            if ($this->form_validation->run() == false) {
                $this->load->view('layout/header');
                $this->load->view('lms/conduct_ssapamp/conduct_ssa2', $data);
                // $this->load->view('lms/conduct_ssapamp/class_record_per_student_ssa', $data);
                $this->load->view('layout/footer');
            } 
            else { 
                $session = $this->input->post('session_id');   // schoolyear           
                $schoolyear = $this->input->post('session_id');
                $quarter = $this->input->post('quarter_id'); //semester
                $grade_level = $this->input->post('class_id'); //levelid
                $section = $this->input->post('section_id');    //section
                $student_id = $this->input->post('student_id');
                
                if ($grade_level==$prekinderid or $grade_level==$kinderid) {
                    $l  =  $this->getGrading();        
                    $data['legend_record'] =  $l;
                    $data['conductformat'] = "1";
                    $studentgradelist = $this->grading_studentconduct_ssapamp_model->getStudentGradeList($student_id,$grade_level,$section,$session,$quarter);
                    if ( $studentgradelist) {
                        $g1 = $this->fetch_grades( $student_id,$grade_level,$section,$session,$quarter);
                        $data['resultlist'] = $g1['db_grades'];
                    } else {
                        if (empty($student_id) or $grade_level=="0") { 
                        }
                        else {
                            $this->post_initial_grade($student_id,$grade_level,$section,$session,$quarter);
                            $studentgradelist = $this->grading_studentconduct_ssapamp_model->getStudentGradeList($student_id,$grade_level,$section,$session,$quarter);
                            $g1 = $this->fetch_grades( $student_id,$grade_level,$section,$session,$quarter);
                            $data['resultlist'] = $g1['db_grades'];
                        }
                        
                    } 
                }
                else {
                    $class_record = $this->conduct_model->get_student_conduct_record_numeric($schoolyear, $quarter, $grade_level, $section, $data['user_id']);
                    $data['resultlist'] = $class_record;
                }
                

                // print_r(json_encode($class_record));die();
                // print_r($class_record);die();
                $data['session_id'] = $schoolyear;
                $data['quarter_id'] = $quarter;
                $data['class_id'] = $grade_level;
                $data['section_id']  = $section;
                $data['student_id'] = $student_id;

                $this->load->view('layout/header');
                $this->load->view('lms/conduct_ssapamp/conduct_ssa2', $data);
                // $this->load->view('lms/conduct_ssapamp/class_record_per_student_ssa', $data);
                $this->load->view('layout/footer');
            }
        }
    }

    public function post_initial_grade($studentid,$levelid,$section,$session,$semester) {

        $fetchresult = $this->grading_conduct_ssapamp_model->getList();
        // var_dump($fetchresult);
        $counter=0;
        if (strlen($studentid)!=0) {
            foreach($fetchresult as $row) {
                $id    =   $row->id;
                $grade = $levelid;
                $lg = "U";
                $insert[] = array (
                    'studentid' => $studentid,
                    'conductid' => $id,
                    'levelid' => $levelid,
                    'sectionid' => $section,
                    'schoolyear' => $session,
                    'semester' => $semester
                    );
                $counter+=1;
            }
            if ($counter>0) {
                $result = $this->grading_studentconduct_ssapamp_model->batchinsert($insert);
            }
        }
    }

    public function fetch_grades($studentid,$grade_level,$section,$session,$quarter) {
        $grades = $this->grading_studentconduct_ssapamp_model->getGrades($studentid,$grade_level,$section,$session,$quarter);
        // var_dump($grades);
        $db_grades=array();
        if ($grades) {
            $clid = 1001;
            $ssid = 1001;
            $db_grades[] = array (
                'ssid' => $ssid,
                'clid' => $clid,
                'grade' => 0,
                'lg' => 0
            );
            foreach($grades as $row) {
                $ssid = $row['ssid'];
                $clid = $row['conductid'];
                $p1 = $row['grade'];
                $lg = $row['lg'];
                $db_grades[] = array (
                    'ssid' => $ssid,
                    'clid' => $clid,
                    'grade' => $p1,
                    'lg' => $lg
                );

            }
        }
        else {
            // echo "no data";
        }
        // echo "<br><br>";
        // var_dump($db_grades);
        $payLoad['db_grades']= $db_grades;

       

        return $payLoad;
    }

    public function getGrading()
    {
        $record = $this->grading_conduct_ssapamp_model->getLegend();       

        $legend_array = array();
        $out = "";
        if ($record) {
            
            foreach($record as $row) {
                $lg = $row->conduct_grade;
                $grade_description = $row->grade_description;
                $description = $row->description;
                $desc = $lg . '|' . $grade_description . '|' . $description . '*';
                $out .= $desc;
            }
        }

        $final = substr($out,0,-1);

        return $final;
    }


    public function save_conduct_grades() 
    {
        try {
            $conducts = $this->input->post('conduct');
            // print_r($conducts);die();
            $user_id = $this->input->post('user_id');
            $session_id = $this->input->post('session_id');
            $quarter_id = $this->input->post('quarter_id');
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $student_id = $this->input->post('student_id');
            
            $this->add_conduct_grades($session_id, $quarter_id, $class_id, $section_id, $student_id, $user_id, $conducts);

            $msg   = $this->lang->line('success_message');
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);    
        } catch (Exception $e) {
            $msg   = $this->lang->line('failed_message');
            $array = array('status' => 'failed', 'error' => '', 'message' => $msg);
        }
        
        echo json_encode($array);
    }   

    function add_conduct_grades($session, $quarter, $grade_level, $section, $student, $user, $conducts)
    {
        for($i = 0; $i < count($conducts); $i++) {
            $data = [];

            $id = "grading_conduct_".$this->mode."_".microtime(true)*10000;
            $id = $id.rand(1000,9999);

            if (!empty($conducts[$i]))
            {
                $conduct_data = explode('-', $conducts[$i]);            

                $data = array(
                    "id" => $id,
                    "school_year" => $session,
                    "quarter" => $quarter,                
                    "grade" => $grade_level,
                    "section_id" => $section,
                    "teacher_id" => $user,
                    "student_id" => $student,
                    "indicator_id" => $conduct_data[0],
                    "conduct" => $conduct_data[1],
                );
    
                // print_r($data);die();
    
                $this->conduct_model->save_conduct_grades($data);
            }            
        }
    }

    public function save_conduct_grades_numeric()
    {
        try 
        {
            $student_ids = $this->input->post('studentidhidden');
            $conduct_grades = $this->input->post('conductgrades');
            $school_year = $this->input->post('session_id');
            $quarter_id = $this->input->post('quarter_id');
            $grade_level = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $user_id = $this->input->post('user_id');

            $this->add_conduct_grades_numeric($school_year, $quarter_id, $grade_level, $section_id, $user_id, $student_ids, $conduct_grades);

            $msg   = $this->lang->line('success_message');
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);    
        } catch (Exception $e) {
            $msg   = $this->lang->line('failed_message');
            $array = array('status' => 'failed', 'error' => '', 'message' => $msg);
        }
        
        echo json_encode($array);
    }

    function add_conduct_grades_numeric($session, $quarter, $grade_level, $section, $user, $students, $conducts)
    {
        $maindata = [];

        for($i = 0; $i < count($students); $i++) {
            $data = [];
            $id = "grading_conduct_numeric_".$this->mode."_".microtime(true)*10000;
            $id = $id.rand(1000,9999);

            // print_r($conducts);

            if (!empty($conducts[$i]))
            {
                $data = array(
                    "id" => $id,
                    "school_year" => $session,
                    "quarter" => $quarter,                
                    "grade_level" => $grade_level,
                    "section_id" => $section,
                    "teacher_id" => $user,
                    "student_id" => $students[$i],
                    "conduct_num" => $conducts[$i],
                );
    
                // echo "<pre>"; print_r($data); echo"<pre>";
                // $this->conduct_model->save_conduct_grades_numeric($data);
                array_push($maindata, $data);
            } 
        }

        $this->conduct_model->save_conduct_grades_numeric($maindata);
    }
}

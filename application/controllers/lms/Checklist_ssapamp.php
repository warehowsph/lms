<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Checklist_ssapamp extends General_Controller {

    function __construct() {
        
        parent::__construct();

        $this->session->set_userdata('top_menu', 'Conduct');
        $this->writedb = $this->load->database('write_db', TRUE);
        $this->load->model('general_model');
        $this->load->model('setting_model');
        $this->load->model('session_model');
        $this->load->model('conduct_model');
        $this->load->model('class_model');

        $this->load->model('grading_checklist_ssapamp_model');
        $this->load->model('grading_conduct_ssapamp_model');
        $this->load->model('grading_studentgrade_ssapamp_model');
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
        // if ($this->sch_setting_detail->conduct_grading_type == "letter")
        //     $this->conduct_1();
        // else if ($this->sch_setting_detail->conduct_grading_type == "number")
        //     $this->conduct_2();
        $this->checklist_2();
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
   
    function checklist_1() 
    {
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'Academics/checklist');

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
            $this->load->view('lms/checklist_ssapamp/conduct_ssa', $data);
            $this->load->view('layout/footer');
        } else {
            if ($this->form_validation->run() == false) {
                $this->load->view('layout/header');
                $this->load->view('lms/checklist_ssapamp/conduct_ssa', $data);
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
                $this->load->view('lms/checklist_ssapamp/conduct_ssa', $data);
                $this->load->view('layout/footer');

            }
        }
    }


    function checklist_2() 
    {
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'Academics/checklist');

        $data['classlist'] = $this->general_model->get_classes();
        $data['quarter_list'] = $this->general_model->lms_get('grading_quarter',"","");
        $data['session_list'] = $this->session_model->getAllSession();
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['user_id'] = $this->general_model->get_account_id();

        $data['legend_list'] =  $this->grading_checklist_ssapamp_model->getLegend();       
        $data['Subjects'] = $this->grading_checklist_ssapamp_model->getList();
        $l  =  $this->getGrading();
        
        $data['legend_record'] =  $l;

        $data['Details'] = $this->grading_checklist_ssapamp_model->getDetailList();
        $data['cle'] = $this->grading_checklist_ssapamp_model->fetchData(1);
        $data['reading'] = $this->grading_checklist_ssapamp_model->fetchData(2);
        $data['math'] = $this->grading_checklist_ssapamp_model->fetchData(3);
        $data['mape'] = $this->grading_checklist_ssapamp_model->fetchData(4);
        $data['writting'] = $this->grading_checklist_ssapamp_model->fetchData(5);

        $cdid="";
        $wrec = $this->grading_checklist_ssapamp_model->fetchData(5);
        foreach ($wrec as $subjectrec) {
            $cdid=$subjectrec->id;
          }
        $data['writting_id'] = $cdid;

        $this->form_validation->set_rules('session_id', $this->lang->line('current_session'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('quarter_id', $this->lang->line('quarter'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

        $result1 = $this->grading_ssapamp_model->getLevelId("Pre-Kinder");
        $level_classid = $result1[0]->id;
        $data['level_classid'] = $level_classid;

        $data['section_list'] = $this->grading_ssapamp_model->getClassBySection($level_classid);

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

            // if ($grade_level=="1") {
                $studentgradelist = $this->grading_studentgrade_ssapamp_model->getStudent_Grades($student_id,$grade_level,$section,$session);
                if (!empty($studentgradelist))  {
                    $g1 = $this->fetch_grades( $student_id,$grade_level,$section,$session,$quarter );
                    $data['resultlist'] = $studentgradelist;
                    $data['db_cle'] = $g1['db_cle'];
                    $data['db_reading'] = $g1['db_reading'];
                    $data['db_math'] = $g1['db_math'];
                    $data['db_mape'] = $g1['db_mape'];
                    $data['db_writting'] = $g1['db_writting'];                    
                } else {
                    if (empty($student_id) or $grade_level=="0") {

                    } else {
                        $this->post_initial_grade($student_id,$grade_level,$section,$session);
                        $studentgradelist = $this->grading_studentgrade_ssapamp_model->getStudent_Grades($student_id,$grade_level,$section,$session);
                        $g1 = $this->fetch_grades( $student_id,$grade_level,$section,$session,$quarter );
                        $data['resultlist'] = $studentgradelist;
                        $data['db_cle'] = $g1['db_cle'];
                        $data['db_reading'] = $g1['db_reading'];
                        $data['db_math'] = $g1['db_math'];
                        $data['db_mape'] = $g1['db_mape'];
                        $data['db_writting'] = $g1['db_writting'];  
                    }
                                      
                } 
            // }
            // else {
            //     // $class_record = $this->conduct_model->get_student_conduct_record_numeric($schoolyear, $quarter, $grade_level, $section, $data['user_id']);
            //     // $data['resultlist'] = $class_record;
            // }

            $this->load->view('layout/header');
            $this->load->view('lms/checklist_ssapamp/checklist_ssa2', $data);
            $this->load->view('layout/footer');
            // $this->load->view('lms/checklist_ssapamp/checklist', $data);
        } else {
            if ($this->form_validation->run() == false) {
                $this->load->view('layout/header');
                $this->load->view('lms/checklist_ssapamp/checklist_ssa2', $data);
                $this->load->view('layout/footer');
            } 
            else { 
                $session = $this->input->post('session_id');   // schoolyear           
                $schoolyear = $this->input->post('session_id');
                $quarter = $this->input->post('quarter_id'); //semester
                $grade_level = $this->input->post('class_id'); //levelid
                $section = $this->input->post('section_id');    //section
                $student_id = $this->input->post('student_id');
                
                // if ($grade_level=="1") {
                    $studentgradelist = $this->grading_studentgrade_ssapamp_model->getStudent_Grades($student_id,$grade_level,$section,$session);
                    if (!empty($studentgradelist))  {
                        $g1 = $this->fetch_grades( $student_id,$grade_level,$section,$session,$quarter);
                        $data['resultlist'] = $studentgradelist;
                        $data['db_cle'] = $g1['db_cle'];
                        $data['db_reading'] = $g1['db_reading'];
                        $data['db_math'] = $g1['db_math'];
                        $data['db_mape'] = $g1['db_mape'];
                        $data['db_writting'] = $g1['db_writting'];
                    } else {
                        if (empty($student_id) or $grade_level=="0") {

                        } else {
                            $this->post_initial_grade($student_id,$grade_level,$section,$session);
                            $studentgradelist = $this->grading_studentgrade_ssapamp_model->getStudent_Grades($student_id,$grade_level,$section,$session);
                            $g1 = $this->fetch_grades( $student_id,$grade_level,$section,$session,$quarter );
                            $data['resultlist'] = $studentgradelist;
                            $data['db_cle'] = $g1['db_cle'];
                            $data['db_reading'] = $g1['db_reading'];
                            $data['db_math'] = $g1['db_math'];
                            $data['db_mape'] = $g1['db_mape'];
                            $data['db_writting'] = $g1['db_writting'];  
                        }                   
                    }
                // }
                // else {
                //     // $class_record = $this->conduct_model->get_student_conduct_record_numeric($schoolyear, $quarter, $grade_level, $section, $data['user_id']);
                //     // $data['resultlist'] = $class_record;
                // }
                

                // print_r(json_encode($class_record));die();
                // print_r($class_record);die();
                $data['session_id'] = $schoolyear;
                $data['quarter_id'] = $quarter;
                $data['class_id'] = $grade_level;
                $data['section_id']  = $section;
                $data['student_id'] = $student_id;

                $this->load->view('layout/header');
                $this->load->view('lms/checklist_ssapamp/checklist_ssa2', $data);
                $this->load->view('layout/footer');

               
                // $this->load->view('lms/checklist_ssapamp/checklist', $data);
                
            }
        }
    }


    // ================
    public function post_initial_grade($studentid,$grade_level,$section,$session) 
    {
        // 'studentid' => $studentid,
        // 'levelid' => $grade_level,
        // 'sectionid' => $section,
        // 'schoolyear' => $session,
        // 'checkdetaillistid' => $id,
        // 'period1' => $grade,
        // 'period2' => $grade,
        // 'finalgrade' => $finalgrade
        $fetchresult = $this->grading_checklist_ssapamp_model->getChecklistDetail();
        // var_dump($fetchresult);
        $counter=0;
        foreach($fetchresult as $row) {
            $id    =   $row->id;
            $grade = 0;
            $finalgrade = 0;
            $insert[] = array (
                'studentid' => $studentid,
                'levelid' => $grade_level,
                'sectionid' => $section,
                'schoolyear' => $session,
                'checkdetaillistid' => $id
                
                );
            $counter+=1;
        }
        if ($counter>0) {
            $result = $this->grading_studentgrade_ssapamp_model->batchinsert($insert);
        }
    }

    public function getGrading()
    {
        $record = $this->grading_checklist_ssapamp_model->getLegend();

        $legend_array = array();
        $out = "";
        if ($record) {
            
            foreach($record as $row) {
                $lg = $row['letter_grade'];
                $grade_description = $row['grade_description'];
                $description = $row['description'];
                $desc = $lg . '|' . $grade_description . '|' . $description . '*';
                $out .= $desc;
            }
        }

        $final = substr($out,0,-1);

        return $final;
    }

    public function fetch_grades($studentid,$levelid,$sectionid,$schoolyear,$quarter) 
    {
      
        $cle = $this->grading_studentgrade_ssapamp_model->getGrades($studentid,$levelid,$sectionid,$schoolyear,"1");
        $db_cle=array();
        if ($cle) {
            $clid = 1001;
            $ssid = 1001;
            $db_cle[] = array (
                'ssid' => $ssid,
                'clid' => $clid,
                'period1' => 0,
                'period2' => 0,
                'finalgrade' => 0,
                'class' => ""
            );
            foreach($cle as $row) {
                $ssid = $row['ssid'];
                $clid = $row['checkdetaillistid'];
                $p1 = $row['period1'];
                $p2 = $row['period2'];
                $fg = $row['finalgrade'];
                $class = $row['groupclass'];
                $db_cle[] = array (
                    'ssid' => $ssid,
                    'clid' => $clid,
                    'period1' => $p1,
                    'period2' => $p2,
                    'finalgrade' => $fg,
                    'class' => $class
                );

            }
        }
        else {
            // echo "no data";
        }
       
        $payLoad['db_cle']= $db_cle;

        $reading = $this->grading_studentgrade_ssapamp_model->getGrades($studentid,$levelid,$sectionid,$schoolyear,"2");
        $db_reading=array();
        if ($reading) {
            $clid = 1002;
            $ssid = 1002;
            $db_reading[] = array (
                'ssid' => $ssid,
                'clid' => $clid,
                'period1' => 0,
                'period2' => 0,
                'finalgrade' => 0,
                'class' => ""
            );
            foreach($reading as $row) {
                $ssid = $row['ssid'];
                $clid = $row['checkdetaillistid'];
                $p1 = $row['period1'];
                $p2 = $row['period2'];
                $fg = $row['finalgrade'];
                $class = $row['groupclass'];
                $db_reading[] = array (
                    'ssid' => $ssid,
                    'clid' => $clid,
                    'period1' => $p1,
                    'period2' => $p2,
                    'finalgrade' => $fg,
                    'class' => $class
                );

            }
        }
        else {
            echo "no data";
        }

        $payLoad['db_reading']= $db_reading;

        $math = $this->grading_studentgrade_ssapamp_model->getGrades($studentid,$levelid,$sectionid,$schoolyear,"3");
        $db_math=array();
        if ($math) {
            $clid = 1003;
            $ssid = 1003;
            $db_math[] = array (
                'ssid' => $ssid,
                'clid' => $clid,
                'period1' => 0,
                'period2' => 0,
                'finalgrade' => 0,
                'class' => ""
            );
            foreach($math as $row) {
                $ssid = $row['ssid'];
                $clid = $row['checkdetaillistid'];
                $p1 = $row['period1'];
                $p2 = $row['period2'];
                $fg = $row['finalgrade'];
                $class = $row['groupclass'];
                $db_math[] = array (
                    'ssid' => $ssid,
                    'clid' => $clid,
                    'period1' => $p1,
                    'period2' => $p2,
                    'finalgrade' => $fg,
                    'class' => $class
                );

            }
        }
        else {
            echo "no data";
        }

        $payLoad['db_math']= $db_math;

        $mape = $this->grading_studentgrade_ssapamp_model->getGrades($studentid,$levelid,$sectionid,$schoolyear,"4");
        $db_mape=array();
        if ($mape) {
            $clid = 1004;
            $ssid = 1004;
            $db_mape[] = array (
                'ssid' => $ssid,
                'clid' => $clid,
                'period1' => 0,
                'period2' => 0,
                'finalgrade' => 0,
                'class' => ""
            );
            foreach($mape as $row) {
                $ssid = $row['ssid'];
                $clid = $row['checkdetaillistid'];
                $p1 = $row['period1'];
                $p2 = $row['period2'];
                $fg = $row['finalgrade'];
                $class = $row['groupclass'];
                $db_mape[] = array (
                    'ssid' => $ssid,
                    'clid' => $clid,
                    'period1' => $p1,
                    'period2' => $p2,
                    'finalgrade' => $fg,
                    'class' => $class
                );
                // array_push($db_cle,$skilid);
                // echo "=========\n";
                // var_dump($row['JobSkillId']);
            }
        }
        else {
            echo "no data";
        }

        $payLoad['db_mape']= $db_mape;

        $writting = $this->grading_studentgrade_ssapamp_model->getGrades($studentid,$levelid,$sectionid,$schoolyear,"5");
        $db_writting=array();
        if ($writting) {
            $clid = 1004;
            $ssid = 1004;
            $db_writting[] = array (
                'ssid' => $ssid,
                'clid' => $clid,
                'period1' => 0,
                'period2' => 0,
                'finalgrade' => 0,
                'class' => ""
            );
            foreach($writting as $row) {
                $ssid = $row['ssid'];
                $clid = $row['checkdetaillistid'];
                $p1 = $row['period1'];
                $p2 = $row['period2'];
                $fg = $row['finalgrade'];
                $class = $row['groupclass'];
                $db_writting[] = array (
                    'ssid' => $ssid,
                    'clid' => $clid,
                    'period1' => $p1,
                    'period2' => $p2,
                    'finalgrade' => $fg,
                    'class' => $class
                );
                // array_push($db_cle,$skilid);
                // echo "=========\n";
                // var_dump($row['JobSkillId']);
            }
        }
        else {
            echo "no data";
        }

        $payLoad['db_writting']= $db_writting;


        return $payLoad;
    }   

    // ================

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

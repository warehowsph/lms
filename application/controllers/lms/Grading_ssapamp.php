<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grading_ssapamp extends CI_Controller {
    function __construct() {
        parent::__construct();
        // if(!$this->session->userdata('logged_in')){
        //     redirect('login');
        //   }
        // $this->config->load('app-config');
        $this->time = strtotime(date('d-m-Y H:i:s'));
        $this->load->model('grading_conduct_ssapamp_model');
        $this->load->model('grading_studentconduct_ssapamp_model');
        $this->load->model('grading_ssapamp_model');
        
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->library('encoding_lib');
        $this->load->library('email'); //-- used for email address validation
        // $this->load->model("classteacher_model");
        $this->load->model("timeline_model");
        $this->load->model('student_model');
        $this->load->model('class_model');
        $this->load->model('classsection_model');
        $this->load->model('studentsession_model');
        $this->load->model('session_model');
        $this->load->model('category_model');
        $this->load->model('grade_model');
        $this->load->model('studentfeemaster_model');
        $this->load->model('setting_model');
        $this->load->model('disable_reason_model');
        $this->load->model('hostel_model');
        $this->load->model('vehroute_model');
        $this->load->model('customfield_model');
        $this->load->model('onlinestudent_model');
        $this->load->model('feegroup_model');
        $this->load->model('feediscount_model');
        $this->load->model('user_model');
        $this->load->model('gradereport_model');

        $this->search_type = $this->customlib->get_searchtype();
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function update() {
        if ($this->input->is_ajax_request()) {

            $ajax_data = $this->input->post();
            $studentid = $ajax_data['studentid'];
            $ssid = $ajax_data['ssid'];
            $grade = $ajax_data['grade'];
            $lg = $ajax_data['lg'];
            $clid = $ajax_data['clid'];

            $updatearray=array();
            $updatearray = array (
                'grade' => $grade,
                'lg' => $lg
            );
            $this->grading_studentconduct_ssapamp_model->updatestudentgrade($studentid,$ssid,$clid,$updatearray);
        }
    }

    public function submit_record() {
        $datavalues = $this->input->post();
        // var_dump($datavalues);
        $ctr=0;
        $ssid_array=array();
        $ssid_array=$this->input->post('ssid');
        $grade_array=$this->input->post('grade');
        $lg_array=$this->input->post('final');
        $clid_array=$this->input->post('clid');
        $studentid=$this->input->post('studentid');
        // if(!empty($this->input->post('ssid')))
        // {
           
            foreach ( $this->input->post('ssid') as $obj)
            {
                // $ctr=$ctr+1;
                // $ssid_array[] = array (
                //     'index' => $ctr,
                //     'ssid' => $obj
                // );
                // echo "ssid: " . $obj; 
                // echo "<br>";  
            }
            foreach ( $this->input->post('grade') as $obj2)
            {
            //    echo "1st: " . $obj2;
            //    echo "<br>";                           
            }  

            foreach ( $this->input->post('clid') as $obj2)
            {
               echo "clid: " . $obj2;
               echo "<br>";                           
            }  

        // $ssid_array = array_combine($this->input->post('ssid'),$this->input->post('1st'));
        foreach ( $ssid_array as $idx => $val ) {
            $all_array[] = [ $val, $grade_array[$idx],$clid_array[$idx],$lg_array[$idx] ];
        }
        echo $studentid . "<br>";
        var_dump($all_array);
        foreach ($all_array as $rec)
        {
            echo "<br>ssid: " . $rec["0"] . " " . $rec["1"] . " " . $rec["2"] . " " . " " . $rec["3"] .  "<br>"; 
           
        //     $number = ($rec["1"] + $rec["2"] / 2);
        //     $p1 = (double) $rec["1"];
        //     $p2 = (double) $rec["2"];
        //     $sum=$p1 + $p2;
        //     $finalgrade=$sum / 2;
        //     $updatesql = "UPDATE studentgrade SET period1= " . $rec["1"].", period2=" . $rec["2"] . ", finalgrade=" . number_format($finalgrade,2) . " where id=" . $rec[0] . " and checkdetaillistid=" . $rec[3];
            $updatearray=array();
            $updatearray = array (
                'grade' => $rec["1"],
                'lg' => $rec["3"]
            );
            $this->grading_studentconduct_ssapamp_model->updatestudentgrade($studentid,$rec[0],$rec[2],$updatearray);
        //     echo $updatesql . "<br>";
        }           

    }

    public function index(){
        // $this->load->view('view_checklist');
        // $this->load->view('view_grade');

        // $parameters = "classid=$class_id&sectionid=$section_id&rollno=$row->roll_no&session=$session_id";

       
        

        $data['Subjects'] = $this->grading_conduct_ssapamp_model->getList();
       
        $this->load->view('/headerfooter/header');
        // var_dump($data2);
        
        $studentid="2";
        $levelid="1";
        $sectionid="1";
        $schoolyear="1";
        $studentgradelist = $this->grading_studentconduct_ssapamp_model->getStudentGradeList($studentid);
        if ( $studentgradelist) {
            $g1 = $this->fetch_grades( $studentid);
            $data['db_grades'] = $g1['db_grades'];
            // echo "not empty";
            // var_dump($g1);
        } else {
            $this->post_initial_grade($studentid);
            $studentgradelist = $this->grading_studentconduct_ssapamp_model->getStudentGradeList($studentid);
            $g1 = $this->fetch_grades( $studentid);
            $data['db_grades'] = $g1['db_grades'];
        }
        $data['studentid'] = $studentid;
        $data['levelid'] = $levelid;
        $data['sectionid'] = $sectionid;
        $data['schoolyear'] = $schoolyear;

        $this->load->view('/ssapamp/showconduct',$data);
        // $this->load->view('testarray',$data);
        $this->load->view('/headerfooter/footer');
        // echo "hello";        
    }

    public function class_record_per_student()
    {
       // if (!$this->rbac->hasPrivilege('class_record_quarterly', 'can_view')) {
       //     access_denied();
       // }
 
    //    $this->session->set_userdata('top_menu', 'Reports');
    //    $this->session->set_userdata('sub_menu', 'Reports/student_information');
    //    $this->session->set_userdata('subsub_menu', 'Reports/student_information/class_record_per_student');
 
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'Academics/conduct');

        $data['title'] = 'Class Record Per Student';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['session_list'] = $this->session_model->getAllSession();
    //    $data['conduct_grading_type'] = $this->sch_setting_detail->conduct_grading_type;
    //    $data['legend_list'] = $this->conduct_model->get_conduct_legend_list();
        $data['school_code'] = $this->sch_setting_detail->dise_code;
    
        $this->form_validation->set_rules('session_id', $this->lang->line('current_session'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('student_id', $this->lang->line('subject'), 'trim|required|xss_clean');
 
       if ($this->input->server('REQUEST_METHOD') == "GET") {
            $session = $this->input->post('session_id');
            $grade_level = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $student_id = $this->input->post('student_id');
            $data['session_id'] = $session;
            $data['class_id'] = $grade_level;
            $data['section_id']  = $section;
            $data['student_id'] = $student_id;


            $this->load->view('layout/header', $data);
            $this->load->view('reports/class_record_per_student', $data);
            $this->load->view('layout/footer', $data);

        } else {
        if ($this->form_validation->run() == false) { 
            $this->load->view('layout/header', $data);
            $this->load->view('reports/class_record_per_student', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $session = $this->input->post('session_id');
            $grade_level = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $student_id = $this->input->post('student_id');
            $student_conduct = null;

            $data['quarter_list'] = $this->gradereport_model->get_quarter_list();

            $data['session_id'] = $session;
            $data['class_id'] = $grade_level;
            $data['section_id']  = $section;
            $data['student_id'] = $student_id;
            $studentinfo = $this->student_model->get($student_id);
            $data['student'] = $studentinfo;
 
            // $class_record = $this->gradereport_model->get_student_class_record_unrestricted($session, $student_id, $grade_level, $section);

            // $data['resultlist'] = $class_record;


//
            $studentgradelist = $this->grading_studentconduct_ssapamp_model->getStudentGradeList($student_id);
            if ( $studentgradelist) {
                $g1 = $this->fetch_grades( $student_id);
                $data['db_grades'] = $g1['db_grades'];
            } else {
                $this->post_initial_grade($studentid,$levelid);
                $studentgradelist = $this->grading_studentconduct_ssapamp_model->getStudentGradeList($student_id);
                $g1 = $this->fetch_grades( $student_id);
                $data['db_grades'] = $g1['db_grades'];
            }

            $data['studentname'] = $studentinfo['lastname'] . ", " . $studentinfo['firstname'];
            $data['studentid'] = $studentid;
            $data['levelid'] = $grade_level;
            $data['sectionid'] = $section;
            $data['section'] = $student_record['section'];
            $data['schoolyear'] = $csession;
    //

            $this->load->view('layout/header', $data);
            $this->load->view('lms/conduct_ssapamp/class_record_per_student_ssa', $data);
            $this->load->view('layout/footer', $data);

          }
       }
    }
 

    

    public function view_index($id){
        // if (!$this->rbac->hasPrivilege('student', 'can_view')) {
        //     access_denied();
        //  }
        //
        $student               = $this->student_model->get($id);
        var_dump($id);
      // print_r("Debug Mode On <BR><BR>");
      // print_r($id);die();

      // var_dump($student);die;
        // $gradeList             = $this->grade_model->get();
        $studentSession        = $this->student_model->getStudentSession($id);
        $timeline              = $this->timeline_model->getStudentTimeline($id, $status = '');
        $data["timeline_list"] = $timeline;

        $student_session_id = $studentSession["student_session_id"];

        $student_session         = $studentSession["session"];
        // $data['sch_setting']     = $this->sch_setting_detail;
        // $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $current_student_session = $this->student_model->get_studentsession($student['student_session_id']);

        $data["session"]              = $current_student_session["session"];
        $csession = $current_student_session["session"];
         
        // $student_due_fee              = $this->studentfeemaster_model->getStudentFees($student['student_session_id']);
        // $student_discount_fee         = $this->feediscount_model->getStudentFeesDiscount($student['student_session_id']);
        // $data['student_discount_fee'] = $student_discount_fee;
        // $data['student_due_fee']      = $student_due_fee;
        // $siblings                     = $this->student_model->getMySiblings($student['parent_id'], $student['id']);

        $student_doc            = $this->student_model->getstudentdoc($id);

        $data['student_doc']    = $student_doc;
        $data['student_doc_id'] = $id;
        $category_list          = $this->category_model->get();
        $data['category_list']  = $category_list;
        $data['gradeList']      = $gradeList;
        $data['student']        = $student;
        $student_record = $student;
        $data['siblings']       = $siblings;
        $class_section          = $this->student_model->getClassSection($student["class_id"]);
        $data["class_section"]  = $class_section;
        $session                = $this->setting_model->getCurrentSession();
        // //
        // echo "student: " . $student_record['roll_no'];
        // echo "<br>";
        // echo "class section: " . $student_record['class'];
        // echo "<br>";
        // echo "session: $csession";
        // echo "<br>";
        // echo "student: " . $student_record['firstname'] . " " . $student_record['lastname'];
        // echo "<br>";
        // echo "section: " . $student_record['section'];
        // echo "<br>";

        $data['Subjects'] = $this->grading_conduct_ssapamp_model->getList();
       
        $this->load->view('/headerfooter/header');
        // // // var_dump($data2);
        
        $studentid=$id;
        $result1 = $this->grading_ssapamp_model->getLevelId($student_record['class']);
        $levelid = $result1[0]->id;
        $result2= $this->grading_ssapamp_model->getSectionId($student_record['section']);
        $sectionid = $result2[0]->id;
        $result3= $this->grading_ssapamp_model->getSchoolYearId($current_student_session["session"]);
        $schoolyear =  $result3[0]->id;

        // echo "levelid: " . $levelid;
        // echo "<br>";
        // echo "school year id: " . $schoolyear;
        // echo "<br>";
        // echo "section id: " . $sectionid;
        // echo "<br>";
        // echo "session: $csession";
        // echo "<br>";
        // echo "student: " . $student_record['firstname'] . " " . $student_record['lastname'];
        // echo "<br>";

        $studentgradelist = $this->grading_studentconduct_ssapamp_model->getStudentGradeList($studentid);
        if ( $studentgradelist) {
            $g1 = $this->fetch_grades( $studentid);
            $data['db_grades'] = $g1['db_grades'];
        } else {
            $this->post_initial_grade($studentid,$levelid);
            $studentgradelist = $this->grading_studentconduct_ssapamp_model->getStudentGradeList($studentid);
            $g1 = $this->fetch_grades( $studentid);
            $data['db_grades'] = $g1['db_grades'];
        }

        $data['studentname'] = $student_record['lastname'] . ", " . $student_record['firstname'];
        $data['studentid'] = $studentid;
        $data['levelid'] = $levelid;
        $data['sectionid'] = $sectionid;
        $data['section'] = $student_record['section'];
        $data['schoolyear'] = $csession;

        $this->load->view('/ssapamp/showconduct',$data);
        $this->load->view('/headerfooter/footer');
    }



    
    public function post_initial_grade($studentid,$levelid) {

        $fetchresult = $this->grading_conduct_ssapamp_model->getList();
        var_dump($fetchresult);
        $counter=0;
        foreach($fetchresult as $row) {
            $id    =   $row->id;
            $grade = $levelid;
            $lg = "U";
            $insert[] = array (
                'studentid' => $studentid,
                'conductid' => $id,
                'grade' => $grade,
                'lg' => $lg
                );
            $counter+=1;
        }
        if ($counter>0) {
            $result = $this->grading_studentconduct_ssapamp_model->batchinsert($insert);
        }

        echo "done";
    }

    public function fetch_grades($studentid) {
        $grades = $this->grading_studentconduct_ssapamp_model->getGrades($studentid);
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
}
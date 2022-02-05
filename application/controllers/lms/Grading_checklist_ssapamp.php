<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Grading_checklist_ssapamp extends CI_Controller
{
   function __construct()
   {
      parent::__construct();
      // if(!$this->session->userdata('logged_in')){
      //     redirect('login');
      //   }
      // $this->config->load('app-config');
      $this->load->model('grading_conduct_ssapamp_model');
      $this->load->model('grading_checklist_ssapamp_model');
      $this->load->model('grading_studentgrade_ssapamp_model');
      //
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
   }

   public function update()
   {
      if ($this->input->is_ajax_request()) {

         $ajax_data = $this->input->post();
         $studentid = $ajax_data['studentid'];
         $id = $ajax_data['id'];
         $period1 = $ajax_data['period1'];
         $period2 = $ajax_data['period2'];
         $finalgrade = $ajax_data['finalgrade'];
         $clid = $ajax_data['clid'];
         $quarter = $ajax_data['quarter'];

         $updatearray = array();
         if ($quarter == 1) {
            $updatearray = array(
               'period1' => $period1,
            );
         } else {
            $updatearray = array(
               'period2' => $period2,
               'finalgrade' => number_format($finalgrade, 2)
            );
         }


         $this->grading_studentgrade_ssapamp_model->updatestudentgrade($studentid, $id, $clid, $updatearray);
      }
   }

   public function submit_record()
   {
      $datavalues = $this->input->post();
      // var_dump($datavalues);
      $ctr = 0;
      $ssid_array = array();
      $ssid_array = $this->input->post('ssid');
      $grade_array = $this->input->post('grade');
      $lg_array = $this->input->post('final');
      $clid_array = $this->input->post('clid');
      $studentid = $this->input->post('studentid');
      // if(!empty($this->input->post('ssid')))
      // {

      foreach ($this->input->post('ssid') as $obj) {
         // $ctr=$ctr+1;
         // $ssid_array[] = array (
         //     'index' => $ctr,
         //     'ssid' => $obj
         // );
         // echo "ssid: " . $obj; 
         // echo "<br>";  
      }
      foreach ($this->input->post('grade') as $obj2) {
         //    echo "1st: " . $obj2;
         //    echo "<br>";                           
      }

      foreach ($this->input->post('clid') as $obj2) {
         echo "clid: " . $obj2;
         echo "<br>";
      }

      // $ssid_array = array_combine($this->input->post('ssid'),$this->input->post('1st'));
      foreach ($ssid_array as $idx => $val) {
         $all_array[] = [$val, $grade_array[$idx], $clid_array[$idx], $lg_array[$idx]];
      }
      echo $studentid . "<br>";
      var_dump($all_array);
      foreach ($all_array as $rec) {
         echo "<br>ssid: " . $rec["0"] . " " . $rec["1"] . " " . $rec["2"] . " " . " " . $rec["3"] .  "<br>";

         //     $number = ($rec["1"] + $rec["2"] / 2);
         //     $p1 = (double) $rec["1"];
         //     $p2 = (double) $rec["2"];
         //     $sum=$p1 + $p2;
         //     $finalgrade=$sum / 2;
         //     $updatesql = "UPDATE studentgrade SET period1= " . $rec["1"].", period2=" . $rec["2"] . ", finalgrade=" . number_format($finalgrade,2) . " where id=" . $rec[0] . " and checkdetaillistid=" . $rec[3];
         $updatearray = array();
         $updatearray = array(
            'grade' => $rec["1"],
            'lg' => $rec["3"]
         );
         $this->grading_studentconduct_ssapamp_model->updatestudentgrade($studentid, $rec[0], $rec[2], $updatearray);
         //     echo $updatesql . "<br>";
      }
   }

   public function view_index($id)
   {
      $student               = $this->student_model->get($id);
      $sid = strtok($id, '-');
      $quarterid = strtok('');
      // echo "student id : " . $sid;
      // echo "quarter : " . $quarterid;
      $studentid = $sid;
      // var_dump($id);
      // print_r("Debug Mode On <BR><BR>");
      // print_r($id);die();

      // var_dump($student);die;
      // $gradeList             = $this->grade_model->get();
      $studentSession        = $this->student_model->getStudentSession($studentid);
      $timeline              = $this->timeline_model->getStudentTimeline($studentid, $status = '');
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

      $student_doc            = $this->student_model->getstudentdoc($studentid);

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
      $data['legend_list'] =  $this->grading_checklist_ssapamp_model->getLegend();
      $l  =  $this->getGradingLegend();
      var_dump($l);
      $data['legend_record'] =  $l;
      $data['Subjects'] = $this->grading_checklist_ssapamp_model->getList();
      $data['Details'] = $this->grading_checklist_ssapamp_model->getDetailList();
      $data['cle'] = $this->grading_checklist_ssapamp_model->fetchData(1);
      $data['reading'] = $this->grading_checklist_ssapamp_model->fetchData(2);
      $data['math'] = $this->grading_checklist_ssapamp_model->fetchData(3);
      $data['mape'] = $this->grading_checklist_ssapamp_model->fetchData(4);


      // // // var_dump($data2);


      $result1 = $this->grading_ssapamp_model->getLevelId($student_record['class']);
      $levelid = $result1[0]->id;

      $result2 = $this->grading_ssapamp_model->getSectionId($student_record['section']);
      $sectionid = $result2[0]->id;

      $result3 = $this->grading_ssapamp_model->getSchoolYearId($current_student_session["session"]);
      $schoolyear =  $result3[0]->id;
      // echo "student id: " . $studentid;
      // echo "<br>";
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
      // echo "quarter id: " . $quarterid;
      // echo "<br>";



      $studentgradelist = $this->grading_studentgrade_ssapamp_model->getStudentGradeList($student_id, $levelid, $section, $schoolyear);
      if ($studentgradelist) {
         $g1 = $this->fetch_grades($studentid, $levelid, $sectionid, $schoolyear);
         $data['db_cle'] = $g1['db_cle'];
         $data['db_reading'] = $g1['db_reading'];
         $data['db_math'] = $g1['db_math'];
         $data['db_mape'] = $g1['db_mape'];
         $g3 = $g1['db_reading'];
         // echo "not empty";
         // var_dump($g3);
      } else {
         $this->post_initial_grade($studentid, $levelid, $sectionid, $schoolyear);
         $studentgradelist = $this->grading_studentgrade_ssapamp_model->getStudentGradeList($student_id, $levelid, $section, $schoolyear);
         $g1 = $this->fetch_grades($studentid, $levelid, $sectionid, $schoolyear);
         $data['db_cle'] = $g1['db_cle'];
         $data['db_reading'] = $g1['db_reading'];
         $data['db_math'] = $g1['db_math'];
         $data['db_mape'] = $g1['db_mape'];
         $g3 = $g1['db_reading'];
      }

      // $data['age']=$age;
      // $this->load->view('show_checklist',$data);
      // $this->load->view('footer');
      $data['quarter'] = $quarterid;
      $data['studentname'] = $student_record['lastname'] . ", " . $student_record['firstname'];
      $data['studentid'] = $studentid;
      $data['levelid'] = $levelid;
      $data['sectionid'] = $sectionid;
      $data['section'] = $student_record['section'];
      $data['schoolyear'] = $csession;

      // var_dump($data['reading']);
      var_dump($data['legend_list']);

      $this->load->view('/headerfooter/header');
      $this->load->view('/ssapamp/showchecklist2', $data);
      $this->load->view('/headerfooter/footer');
   }

   public function index()
   {
      // $this->load->view('view_checklist');
      // $this->load->view('view_grade');
      $data['Subjects'] = $this->grading_checklist_ssapamp_model->getList();
      $data['Details'] = $this->grading_checklist_ssapamp_model->getDetailList();
      $data['cle'] = $this->grading_checklist_ssapamp_model->fetchData(1);
      $data['reading'] = $this->grading_checklist_ssapamp_model->fetchData(2);
      $data['math'] = $this->grading_checklist_ssapamp_model->fetchData(3);
      $data['mape'] = $this->grading_checklist_ssapamp_model->fetchData(4);
      $this->load->view('header');
      // var_dump($data2);
      $studentid = "802";
      $dataresult = $this->students_ssapamp_model->getStudent($studentid);
      // var_dump($dataresult);

      foreach ($dataresult as $row) {

         $lastname  = $row['lastname'];
         $firstname = $row['firstname'];
         $section    = $row['section'];
         $age = $row['age'];
         // $ext       = $row['Extname'];
         // $birthdate = $row['Birthdate'];

         // $Y_date = explode('-',$birthdate);
         // $birthyear  = $Y_date[0];

         // $midext = trim($middle) . ' ' . trim($ext);
         // $foundrecord = TRUE;
         $studentname = $lastname . ', ' . $firstname;
      }



      $levelid = "1";
      $sectionid = "1";
      $schoolyear = "1";
      $studentgradelist = $this->grading_studentgrade_ssapamp_model->getStudentGradeList($studentid);
      if ($studentgradelist) {
         $g1 = $this->fetch_grades($studentid);
         $data['db_cle'] = $g1['db_cle'];
         $data['db_reading'] = $g1['db_reading'];
         $data['db_math'] = $g1['db_math'];
         $data['db_mape'] = $g1['db_mape'];
         $g3 = $g1['db_reading'];
         // echo "not empty";
         // var_dump($g3);
      } else {
         $this->post_initial_grade($studentid);
         $studentgradelist = $this->grading_studentgrade_ssapamp_model->getStudentGradeList($studentid);
         $g1 = $this->fetch_grades($studentid);
         $data['db_cle'] = $g1['db_cle'];
         $data['db_reading'] = $g1['db_reading'];
         $data['db_math'] = $g1['db_math'];
         $data['db_mape'] = $g1['db_mape'];
         $g3 = $g1['db_reading'];
      }
      $data['studentname'] = $studentname;
      $data['age'] = $age;
      $data['studentid'] = $studentid;
      $data['levelid'] = $levelid;
      $data['sectionid'] = $sectionid;
      $data['section'] = $section;
      $data['schoolyear'] = $schoolyear;
      $this->load->view('show_checklist', $data);
      $this->load->view('footer');
   }

   // ================
   public function post_initial_grade($studentid, $grade_level, $section, $session)
   {

      $fetchresult = $this->grading_checklist_ssapamp_model->getChecklistDetail();
      // var_dump($fetchresult);
      $counter = 0;
      foreach ($fetchresult as $row) {
         $id    =   $row->id;
         $grade = 70;
         $finalgrade = 70;
         $insert[] = array(
            'studentid' => $studentid,
            'levelid' => $grade_level,
            'sectionid' => $section,
            'schoolyear' => $session,
            'checkdetaillistid' => $id,
            'period1' => $grade,
            'period2' => $grade,
            'finalgrade' => $finalgrade
         );
         $counter += 1;
      }
      if ($counter > 0) {
         $result = $this->grading_studentgrade_ssapamp_model->batchinsert($insert);
      }
   }

   public function getGradingLegend()
   {
      $record = $this->grading_checklist_ssapamp_model->getLegend();

      $legend_array = array();
      $out = "";
      if ($record) {

         foreach ($record as $row) {
            $lg = $row['letter_grade'];
            $grade_description = $row['grade_description'];
            $description = $row['description'];
            $desc = $lg . '|' . $grade_description . '|' . $description . '*';
            $out .= $desc;
         }
      }

      $final = substr($out, 0, -1);

      return $final;
   }

   public function fetch_grades($studentid, $levelid, $sectionid, $schoolyear)
   {
      // echo "cle";
      // echo "<br>";
      // echo "<br>";
      // echo "student id: " . $studentid;
      // echo "<br>";
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
      // echo "quarter id: " . $quarter;
      // echo "<br>";        
      $cle = $this->grading_studentgrade_ssapamp_model->getGrades($studentid, $levelid, $sectionid, $schoolyear, "1");
      $db_cle = array();
      if ($cle) {
         $clid = 1001;
         $ssid = 1001;
         $db_cle[] = array(
            'ssid' => $ssid,
            'clid' => $clid,
            'period1' => 0,
            'period2' => 0,
            'finalgrade' => 0,
            'class' => ""
         );
         foreach ($cle as $row) {
            $ssid = $row['ssid'];
            $clid = $row['checkdetaillistid'];
            $p1 = $row['period1'];
            $p2 = $row['period2'];
            $fg = $row['finalgrade'];
            $class = $row['groupclass'];
            $db_cle[] = array(
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
      } else {
         // echo "no data";
      }
      // echo "<br>";
      $payLoad['db_cle'] = $db_cle;
      // echo "<br>";
      // echo "cle";
      // echo "<br>";
      // echo "reading";
      $reading = $this->grading_studentgrade_ssapamp_model->getGrades($studentid, $levelid, $sectionid, $schoolyear, "2");
      $db_reading = array();
      if ($reading) {
         $clid = 1002;
         $ssid = 1002;
         $db_reading[] = array(
            'ssid' => $ssid,
            'clid' => $clid,
            'period1' => 0,
            'period2' => 0,
            'finalgrade' => 0,
            'class' => ""
         );
         foreach ($reading as $row) {
            $ssid = $row['ssid'];
            $clid = $row['checkdetaillistid'];
            $p1 = $row['period1'];
            $p2 = $row['period2'];
            $fg = $row['finalgrade'];
            $class = $row['groupclass'];
            $db_reading[] = array(
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
      } else {
         echo "no data";
      }
      // echo "<br>";
      $payLoad['db_reading'] = $db_reading;
      // echo "<br>";
      // echo "reading";
      // echo "<br>";
      // echo "math";
      $math = $this->grading_studentgrade_ssapamp_model->getGrades($studentid, $levelid, $sectionid, $schoolyear, "3");
      $db_math = array();
      if ($math) {
         $clid = 1003;
         $ssid = 1003;
         $db_math[] = array(
            'ssid' => $ssid,
            'clid' => $clid,
            'period1' => 0,
            'period2' => 0,
            'finalgrade' => 0,
            'class' => ""
         );
         foreach ($math as $row) {
            $ssid = $row['ssid'];
            $clid = $row['checkdetaillistid'];
            $p1 = $row['period1'];
            $p2 = $row['period2'];
            $fg = $row['finalgrade'];
            $class = $row['groupclass'];
            $db_math[] = array(
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
      } else {
         echo "no data";
      }
      // echo "<br>";
      $payLoad['db_math'] = $db_math;
      // echo "<br>";
      // echo "math"; 
      // echo "<br>";
      // echo "mape";
      // echo "<br>";
      $mape = $this->grading_studentgrade_ssapamp_model->getGrades($studentid, $levelid, $sectionid, $schoolyear, "4");
      $db_mape = array();
      if ($mape) {
         $clid = 1004;
         $ssid = 1004;
         $db_mape[] = array(
            'ssid' => $ssid,
            'clid' => $clid,
            'period1' => 0,
            'period2' => 0,
            'finalgrade' => 0,
            'class' => ""
         );
         foreach ($mape as $row) {
            $ssid = $row['ssid'];
            $clid = $row['checkdetaillistid'];
            $p1 = $row['period1'];
            $p2 = $row['period2'];
            $fg = $row['finalgrade'];
            $class = $row['groupclass'];
            $db_mape[] = array(
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
      } else {
         echo "no data";
      }
      // echo "<br>";
      // echo "payload";
      // echo "<br>";
      $payLoad['db_mape'] = $db_mape;
      // var_dump($payLoad);
      // echo "<br>";
      return $payLoad;
   }
   // ================


}

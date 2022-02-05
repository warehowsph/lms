<?php

if (!defined('BASEPATH')) {
   exit('No direct script access allowed');
}

class Parents extends Parent_Controller
{
   public $payment_method;

   public function __construct()
   {
      parent::__construct();

      $this->load->model('paymentsetting_model');
      $this->load->model('student_model');
      $this->load->model('notification_model');
      $this->load->model('language_model');
      $this->load->model('user_model');
      $this->load->model('paymentsetting_model');
      $this->load->model('category_model');
      $this->load->model('timeline_model');
      $this->load->model('studentfeemaster_model');
      $this->load->model('examschedule_model');
      $this->load->model('grade_model');
      $this->load->model('examgroupstudent_model');
      $this->load->model('category_model');
      $this->load->model('feediscount_model');
      $this->load->model('subjecttimetable_model');
      $this->load->model('teachersubject_model');
      $this->load->model('setting_model');
      $this->load->model('attendencetype_model');
      $this->load->model('exam_model');
      $this->load->model('examschedule_model');
      $this->load->model('content_model');
      $this->load->model('staff_model');
      $this->load->model('studentsubjectattendence_model');
      $this->load->model('class_model');
      $this->load->model('apply_leave_model');
      $this->load->model('gradereport_model');
      $this->load->model('conduct_model');
      $this->load->model('parent_model');
      $this->load->model('feetype_model');
      $this->load->model('kampuspay_model');
      $this->load->model('general_model');
      $this->load->model('grading_ssapamp_model');
      $this->load->model('grading_studentgrade_ssapamp_model');
      $this->load->model('grading_checklist_ssapamp_model');

      $this->sch_setting_detail = $this->setting_model->getSetting();
      $this->payment_method = $this->paymentsetting_model->getActiveMethod();
      $this->out_trade_no = null;
   }

   public function unauthorized()
   {
      $data = array();
      $this->load->view('layout/parent/header');
      $this->load->view('unauthorized', $data);
      $this->load->view('layout/parent/footer');
   }

   public function dashboard()
   {
      $this->session->set_userdata('top_menu', 'My Children');
      $this->session->set_userdata('sub_menu', 'parent/parents/dashboard');
      $student_id   = $this->customlib->getStudentSessionUserID();
      $array_childs = array();
      $ch           = $this->session->userdata('parent_childs');
      foreach ($ch as $key_ch => $value_ch) {
         $array_childs[] = $this->student_model->get($value_ch['student_id']);
      }
      $data['student_list'] = $array_childs;

      $data['unread_notifications'] = $this->notification_model->getUnreadParentNotification();
      //echo "<pre>"; print_r($data['unread_notifications']); echo "<pre>";die;
      $this->load->view('layout/parent/header', $data);
      $this->load->view('parent/dashboard', $data);
      $this->load->view('layout/parent/footer', $data);
   }

   public function user_language($lang_id)
   {
      $session = $this->session->userdata('student');
      $id              = $session['id'];

      $data['lang_id'] = $lang_id;
      $language_result = $this->language_model->set_parentlang($id, $data);

      $language_name = $this->db->select('languages.language')->from('languages')->where('id', $lang_id)->get()->row_array();
      $student       = $this->session->userdata('student');

      if (!empty($student)) {
         $this->session->unset_userdata('student');
      }

      $language_array      = array('lang_id' => $lang_id, 'language' => $language_name['language']);
      $student['language'] = $language_array;
      $this->session->set_userdata('student', $student);
      $session         = $this->session->userdata('student');
   }

   public function download($student_id, $doc)
   {
      $this->load->helper('download');
      $filepath = "./uploads/student_documents/$student_id/" . $this->uri->segment(5);
      $data     = file_get_contents($filepath);
      $name     = $this->uri->segment(6);
      force_download($name, $data);
   }

   public function downloadSchool_content($file)
   {
      $this->load->helper('download');
      $filepath = "./uploads/school_content/material/" . $this->uri->segment(7);
      $data     = file_get_contents($filepath);
      $name     = $this->uri->segment(7);
      force_download($name, $data);
   }

   public function timeline_download($timeline_id, $doc)
   {
      $this->load->helper('download');
      $filepath = "./uploads/student_timeline/" . $doc;
      $data     = file_get_contents($filepath);
      $name     = $doc;
      force_download($name, $data);
   }

   public function changepass()
   {
      $data['title'] = 'Change Password';
      $this->form_validation->set_rules('current_pass', 'Current password', 'trim|required|xss_clean');
      $this->form_validation->set_rules('new_pass', 'New password', 'trim|required|xss_clean|matches[confirm_pass]');
      $this->form_validation->set_rules('confirm_pass', 'Confirm password', 'trim|required|xss_clean');
      if ($this->form_validation->run() == false) {
         $sessionData            = $this->session->userdata('loggedIn');
         $this->data['id']       = $sessionData['id'];
         $this->data['username'] = $sessionData['username'];
         $this->load->view('layout/parent/header', $data);
         $this->load->view('parent/change_password', $data);
         $this->load->view('layout/parent/footer', $data);
      } else {
         $sessionData = $this->session->userdata('student');
         $data_array  = array(
            'current_pass' => ($this->input->post('current_pass')),
            'new_pass'     => ($this->input->post('new_pass')),
            'user_id'      => $sessionData['id'],
            'user_name'    => $sessionData['username'],
         );
         $newdata = array(
            'id'       => $sessionData['id'],
            'password' => $this->input->post('new_pass'),
         );
         $query1 = $this->user_model->checkOldPass($data_array);
         if ($query1) {
            $query2 = $this->user_model->saveNewPass($newdata);
            if ($query2) {

               $this->session->set_flashdata('success_msg', 'Password changed successfully');
               $this->load->view('layout/parent/header', $data);
               $this->load->view('parent/change_password', $data);
               $this->load->view('layout/parent/footer', $data);
            }
         } else {

            $this->session->set_flashdata('error_msg', 'Invalid current password');
            $this->load->view('layout/parent/header', $data);
            $this->load->view('parent/change_password', $data);
            $this->load->view('layout/parent/footer', $data);
         }
      }
   }

   public function changeusername()
   {
      $sessionData = $this->customlib->getLoggedInUserData();

      $data['title'] = 'Change Username';
      $this->form_validation->set_rules('current_username', 'Current username', 'trim|required|xss_clean');
      $this->form_validation->set_rules('new_username', 'New username', 'trim|required|xss_clean|matches[confirm_username]');
      $this->form_validation->set_rules('confirm_username', 'Confirm username', 'trim|required|xss_clean');
      if ($this->form_validation->run() == false) {
      } else {

         $data_array = array(
            'username'     => $this->input->post('current_username'),
            'new_username' => $this->input->post('new_username'),
            'role'         => $sessionData['role'],
            'user_id'      => $sessionData['id'],
         );
         $newdata = array(
            'id'       => $sessionData['id'],
            'username' => $this->input->post('new_username'),
         );
         $is_valid = $this->user_model->checkOldUsername($data_array);

         if ($is_valid) {
            $is_exists = $this->user_model->checkUserNameExist($data_array);
            if (!$is_exists) {
               $is_updated = $this->user_model->saveNewUsername($newdata);
               if ($is_updated) {
                  $this->session->set_flashdata('success_msg', 'Username changed successfully');
                  redirect('parent/parents/changeusername');
               }
            } else {
               $this->session->set_flashdata('error_msg', 'Username Already Exists, Please choose other');
            }
         } else {
            $this->session->set_flashdata('error_msg', 'Invalid current username');
         }
      }
      $this->data['id']       = $sessionData['id'];
      $this->data['username'] = $sessionData['username'];
      $this->load->view('layout/parent/header', $data);
      $this->load->view('parent/change_username', $data);
      $this->load->view('layout/parent/footer', $data);
   }

   public function getstudent($id = null)
   {

      $this->session->set_userdata('top_menu', 'My Children');
      $this->session->set_userdata('sub_menu', 'parent/parents/getStudent');
      $this->auth->validate_child($id);
      $student_id = $id;

      $payment_setting         = $this->paymentsetting_model->get();
      $data['payment_setting'] = $payment_setting;
      $category                = $this->category_model->get();
      $data['category_list']   = $category;
      $student                 = $this->student_model->get($student_id);
      $timeline                = $this->timeline_model->getStudentTimeline($student["id"], $status = 'yes');
      $data["timeline_list"]   = $timeline;

      $student_doc            = $this->student_model->getstudentdoc($student_id);
      $data['student_doc']    = $student_doc;

      $class_id                     = $student['class_id'];
      $section_id                   = $student['section_id'];
      $data['title']                = 'Student Details';
      $student_session_id           = $student['student_session_id'];

      if ($student_session_id != '') //-- Check if student is already enrolled
      {
         $student_due_fee              = $this->studentfeemaster_model->getStudentFees($student_session_id);
         $student_discount_fee         = $this->feediscount_model->getStudentFeesDiscount($student_session_id);
         $data['student_discount_fee'] = $student_discount_fee;
         $data['student_due_fee']      = $student_due_fee;
         $examList                     = $this->examschedule_model->getExamByClassandSection($student['class_id'], $student['section_id']);
         $data['exam_grade']           = $this->grade_model->getGradeDetails();

         $data['exam_result'] = $this->examgroupstudent_model->searchStudentExams($student['student_session_id'], true, true);

         $data['student'] = $student;
      }


      $this->load->view('layout/parent/header', $data);
      $this->load->view('parent/student/getstudent', $data);
      $this->load->view('layout/parent/footer', $data);
   }

   public function getfees($id = null)
   {
      $this->auth->validate_child($id);
      $this->session->set_userdata('top_menu', 'Fees');
      $this->session->set_userdata('sub_menu', 'parent/parents/getFees');
      $category               = $this->category_model->get();
      $data['categorylist']   = $category;
      $paymentoption          = $this->customlib->checkPaypalDisplay();
      $data['paymentoption']  = $paymentoption;
      $data['payment_method'] = false;
      if (!empty($this->payment_method)) {
         $data['payment_method'] = true;
      }
      $student_id                   = $id;
      $student                      = $this->student_model->get($student_id);
      $class_id                     = $student['class_id'];
      $section_id                   = $student['section_id'];
      $data['title']                = 'Student Details';
      $student_due_fee              = $this->studentfeemaster_model->getStudentFees($student['student_session_id']);
      $student_discount_fee         = $this->feediscount_model->getStudentFeesDiscount($student['student_session_id']);
      $data['student_discount_fee'] = $student_discount_fee;
      $data['student_due_fee']      = $student_due_fee;
      $data['student']              = $student;
      $data['fee_types'] = $this->feetype_model->get();
      $data['linking_page'] = $this->getKampusPayBindUidURL();
      $userbind = $this->getUserQueryBindData();
      $data['account_linked'] = $userbind == null ? false : true;
      $this->load->view('layout/parent/header', $data);
      $this->load->view('parent/student/getfees', $data);
      $this->load->view('layout/parent/footer', $data);
   }

   public function gettimetable($id = null)
   {
      $this->auth->validate_child($id);
      $this->session->set_userdata('top_menu', 'Time Table');
      $this->session->set_userdata('sub_menu', 'parent/parents/gettimetable');
      $student_id = $id;
      $student    = $this->student_model->get($student_id);
      $class_id   = $student['class_id'];
      $section_id = $student['section_id'];

      $days        = $this->customlib->getDaysname();
      $days_record = array();
      foreach ($days as $day_key => $day_value) {

         $days_record[$day_key] = $this->subjecttimetable_model->getparentSubjectByClassandSectionDay($class_id, $section_id, $day_key);
      }
      $data['timetable'] = $days_record;

      $data['student'] = $student;
      $this->load->view('layout/parent/header', $data);
      $this->load->view('parent/student/gettimetable', $data);
      $this->load->view('layout/parent/footer', $data);
   }

   public function getsubject($id = null)
   {
      $this->auth->validate_child($id);
      $this->session->set_userdata('top_menu', 'Subjects');
      $this->session->set_userdata('sub_menu', 'parent/parents/getsubject');
      $student_id           = $id;
      $student              = $this->student_model->get($student_id);
      $data['student']      = $student;
      $class_id             = $student['class_id'];
      $section_id           = $student['section_id'];
      $data['title']        = 'Student Details';
      $subject_list         = $this->teachersubject_model->getSubjectByClsandSection($class_id, $section_id);
      $data['result_array'] = $subject_list;
      $this->load->view('layout/parent/header', $data);
      $this->load->view('parent/student/getsubject', $data);
      $this->load->view('layout/parent/footer', $data);
   }

   public function getattendence($id = null)
   {
      $this->auth->validate_child($id);
      $this->session->set_userdata('top_menu', 'Attendance');
      $this->session->set_userdata('sub_menu', 'parent/parents/getattendence');
      $setting_result = $this->setting_model->get();

      $setting_result = ($setting_result[0]);
      $setting_result['attendence_type'];
      $student_id = $id;
      $student    = $this->student_model->get($student_id);
      $data['student'] = $student;

      $this->load->view('layout/parent/header', $data);

      if ($setting_result['attendence_type']) {
         $this->load->view('parent/student/attendenceSubject', $data);
      } else {
         $this->load->view('parent/student/getattendence', $data);
      }

      $this->load->view('layout/parent/footer', $data);
   }

   public function getAjaxAttendence()
   {
      $year                 = $this->input->get('year');
      $month                = $this->input->get('month');
      $student_session_id   = $this->input->get('student_session');
      $result               = array();
      $new_date             = "01-" . $month . "-" . $year;
      $totalDays            = cal_days_in_month(CAL_GREGORIAN, $month, $year);
      $first_day_this_month = date('01-m-Y');
      $fst_day_str          = strtotime(date($new_date));
      $array                = array();
      for ($day = 2; $day <= $totalDays; $day++) {
         $fst_day_str        = ($fst_day_str + 86400);
         $date               = date('Y-m-d', $fst_day_str);
         $student_attendence = $this->attendencetype_model->getStudentAttendence($date, $student_session_id);
         if (!empty($student_attendence)) {
            $s           = array();
            $s['date']   = $date;
            $s['badge']  = false;
            $s['footer'] = "Extra information";
            $s['body']   = "Information for this date<\/p>You can add html<\/strong> in this block<\/p>";
            $type        = $student_attendence->type;
            $s['title']  = $type;
            if ($type == 'Present') {
               $s['classname'] = "grade-4";
            } else if ($type == 'Absent') {
               $s['classname'] = "grade-1";
            } else if ($type == 'Late') {
               $s['classname'] = "grade-3";
            } else if ($type == 'Late with excuse') {
               $s['classname'] = "grade-2";
            } else if ($type == 'Holiday') {
               $s['classname'] = "grade-5";
            } else if ($type == 'Half Day') {
               $s['classname'] = "grade-2";
            }
            $array[] = $s;
         }
      }
      if (!empty($array)) {
         echo json_encode($array);
      } else {
         echo false;
      }
   }

   public function getexams($id = null)
   {
      $this->auth->validate_child($id);
      $this->session->set_userdata('top_menu', 'Examination');
      $this->session->set_userdata('sub_menu', 'parent/parents/getexams');
      $student_id    = $id;
      $student       = $this->student_model->get($student_id);
      $class_id      = $student['class_id'];
      $section_id    = $student['section_id'];
      $data['title'] = 'Student Details';

      $data['exam_grade'] = $this->grade_model->getGradeDetails();

      $data['exam_result'] = $this->examgroupstudent_model->searchStudentExams($student['student_session_id'], true, true);

      $data['examSchedule'] = array();

      $data['student'] = $student;
      $this->load->view('layout/parent/header', $data);
      $this->load->view('parent/student/getexams', $data);
      $this->load->view('layout/parent/footer', $data);
   }

   public function getexamresult()
   {
      $student_id        = $this->uri->segment('4');
      $exam_id           = $this->uri->segment('5');
      $student           = $this->student_model->get($student_id);
      $class_id          = $student['class_id'];
      $section_id        = $student['section_id'];
      $data['title']     = 'Exam Result';
      $data['student']   = $student;
      $new_array         = array();
      $array             = array();
      $x                 = array();
      $exam_detail_array = $this->exam_model->get($exam_id);
      $exam_subjects     = $this->examschedule_model->getresultByStudentandExam($exam_id, $student_id);
      foreach ($exam_subjects as $key => $value) {
         $exam_array                     = array();
         $exam_array['exam_schedule_id'] = $value['exam_schedule_id'];
         $exam_array['exam_id']          = $value['exam_id'];
         $exam_array['full_marks']       = $value['full_marks'];
         $exam_array['passing_marks']    = $value['passing_marks'];
         $exam_array['exam_name']        = $value['name'];
         $exam_array['exam_type']        = $value['type'];
         $exam_array['attendence']       = $value['attendence'];
         $exam_array['get_marks']        = $value['get_marks'];
         $x[]                            = $exam_array;
      }
      $array['exam_name']   = $exam_detail_array['name'];
      $array['exam_result'] = $x;
      $new_array[]          = $array;
      $data['examSchedule'] = $new_array;
      $this->load->view('layout/parent/header', $data);
      $this->load->view('parent/student/examresult', $data);
      $this->load->view('layout/parent/footer', $data);
   }

   public function getexamtimetable()
   {
      $data['title']        = 'Student Details';
      $class_id             = $this->uri->segment('4');
      $section_id           = $this->uri->segment('5');
      $exam_id              = $this->uri->segment('6');
      $examSchedule         = $this->examschedule_model->getDetailbyClsandSection($class_id, $section_id, $exam_id);
      $data['examSchedule'] = $examSchedule;
      $exam_detail_array    = $this->exam_model->get($exam_id);
      $data['exam_name']    = $exam_detail_array['name'];
      $this->load->view('layout/parent/header', $data);
      $this->load->view('parent/student/examtimetable', $data);
      $this->load->view('layout/parent/footer', $data);
   }

   public function assignment()
   {
      $this->session->set_userdata('top_menu', 'Downloads');
      $this->session->set_userdata('sub_menu', 'content/assignment');
      $student_id = $this->customlib->getStudentSessionUserID();
      $student    = $this->student_model->get($student_id);

      $class_id           = $student['class_id'];
      $section_id         = $student['section_id'];
      $data['title_list'] = 'List of Assignment';
      $list               = $this->content_model->getListByCategoryforUser($class_id, $section_id, "Assignments");
      $data['list']       = $list;
      $this->load->view('layout/parent/header');
      $this->load->view('parent/student/assignment', $data);
      $this->load->view('layout/parent/footer');
   }

   public function studymaterial()
   {
      $this->session->set_userdata('top_menu', 'Downloads');
      $this->session->set_userdata('sub_menu', 'content/studymaterial');
      $student_id         = $this->customlib->getStudentSessionUserID();
      $student            = $this->student_model->get($student_id);
      $class_id           = $student['class_id'];
      $section_id         = $student['section_id'];
      $data['title_list'] = 'List of Assignment';
      $list               = $this->content_model->getListByCategoryforUser($class_id, $section_id, "Study Material");
      $data['list']       = $list;
      $this->load->view('layout/parent/header');
      $this->load->view('parent/student/studymaterial', $data);
      $this->load->view('layout/parent/footer');
   }

   public function download_docs($student_id)
   {
      $this->session->set_userdata('top_menu', 'Downloads');
      $this->session->set_userdata('sub_menu', 'parent/parents/getStudent' . $student_id);
      $student            = $this->student_model->get($student_id);
      $class_id           = $student['class_id'];
      $section_id         = $student['section_id'];
      $data['title_list'] = 'List of Syllabus';
      $list               = $this->content_model->getListByforUser($class_id, $section_id);

      $data['list'] = $list;

      $this->load->view('layout/parent/header');
      $this->load->view('parent/student/syllabus', $data);
      $this->load->view('layout/parent/footer');
   }

   public function syllabus()
   {
      $this->session->set_userdata('top_menu', 'Downloads');
      $this->session->set_userdata('sub_menu', 'content/syllabus');
      $student_id         = $this->customlib->getStudentSessionUserID();
      $student            = $this->student_model->get($student_id);
      $class_id           = $student['class_id'];
      $section_id         = $student['section_id'];
      $data['title_list'] = 'List of Syllabus';
      $list               = $this->content_model->getListByCategoryforUser($class_id, $section_id, "Syllabus");

      $data['list'] = $list;
      $this->load->view('layout/parent/header');
      $this->load->view('parent/student/syllabus', $data);
      $this->load->view('layout/parent/footer');
   }

   public function other()
   {
      $this->session->set_userdata('top_menu', 'Downloads');
      $this->session->set_userdata('sub_menu', 'content/other');
      $student_id         = $this->customlib->getStudentSessionUserID();
      $student            = $this->student_model->get($student_id);
      $class_id           = $student['class_id'];
      $section_id         = $student['section_id'];
      $data['title_list'] = 'List of Other Download';
      $list               = $this->content_model->getListByCategoryforUser($class_id, $section_id, "Other Download");
      $data['list']       = $list;
      $this->load->view('layout/parent/header');
      $this->load->view('parent/student/other', $data);
      $this->load->view('layout/parent/footer');
   }

   public function get_student_teachers($studentid)
   {

      $this->session->set_userdata('top_menu', 'teacher/index');
      $this->session->set_userdata('sub_menu', 'parent/parents/get_student_teachers_' . $studentid);
      $data['title'] = 'Add Teacher';

      $schoolsessionId = $this->setting_model->getCurrentSession();

      $currentClassSectionById = $this->student_model->currentClassSectionById($studentid, $schoolsessionId);

      $genderList = $this->customlib->getGender();
      $session_id = $this->session->userdata('parent_childs');

      $data['class_id']   = $class_id   = $currentClassSectionById['class_id'];
      $data['section_id'] = $section_id = $currentClassSectionById['section_id'];
      $data['resultlist'] = $this->subjecttimetable_model->getTeacherByClassandSection($class_id, $section_id);

      $subject            = array();
      $teachers           = array();
      foreach ($data['resultlist'] as $value) {
         $teachers[$value->staff_id][] = $value;
      }

      $session_id          = $this->session->userdata('student');
      $data['teacherlist'] = $teachers;
      $data['user_id']     = $session_id['id'];
      $data['role']        = $session_id['role'];
      $data['genderList']  = $genderList;

      $user_ratedstafflist         = $this->staff_model->get_RatedStaffByUser($session_id['id']);
      $data['user_ratedstafflist'] = $user_ratedstafflist;

      $all_rating = $this->staff_model->all_rating();
      $data['rate_canview'] = 0;

      foreach ($all_rating as $value) {
         if ($value['total'] >= 1) {
            $r = ($value['rate'] / $value['total']);

            $data['avg_rate'][$value['staff_id']] = $r;
            $data['rate_canview']                 = 1;
         } else {
            $data['avg_rate'][$value['staff_id']] = 0;
         }
         $data['reviews'][$value['staff_id']] = $value['total'];
      }

      $this->load->view('layout/parent/header', $data);
      $this->load->view('parent/teacher/teacherList', $data);
      $this->load->view('layout/parent/footer', $data);
   }

   public function getdaysubattendence()
   {
      $date = $this->input->post('date');
      $date = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date')));

      $attendencetypes = $this->attendencetype_model->get();
      // $date=date('2019-11-11');
      $timestamp = strtotime($date);
      $day       = date('l', $timestamp);

      $student_id = $this->input->post('student_id');

      $student = $this->student_model->get($student_id);

      $class_id           = $student['class_id'];
      $section_id         = $student['section_id'];
      $data['title']      = 'Student Details';
      $student_session_id = $student['student_session_id'];

      $result['attendencetypeslist'] = $attendencetypes;
      $result['attendence']          = $this->studentsubjectattendence_model->studentAttendanceByDate($class_id, $section_id, $day, $date, $student_session_id);
      $result_page                   = $this->load->view('parent/student/_getdaysubattendence', $result, true);
      echo json_encode(array('status' => 1, 'result_page' => $result_page));
   }

   public function excuse_letter()
   {
      // $this->auth->validate_child($id);
      $this->session->set_userdata('top_menu', 'Attendance');
      $this->session->set_userdata('sub_menu', 'parent/parents/excuse_letter');

      $ch = $this->session->userdata('parent_childs');
      foreach ($ch as $key_ch => $value_ch) {
         $array_childs[] = $this->student_model->get($value_ch['student_id']);
      }
      $data['student_list'] = $array_childs;

      $class = $this->class_model->get();
      $data['classlist'] = $class;
      $data['results'] = array();

      //$sessionData = $this->session->userdata('loggedIn');
      $parentid = $this->customlib->getUsersID();

      $listaudit = $this->apply_leave_model->get_children($parentid);
      $data['results'] = $listaudit;

      $this->load->view('layout/parent/header', $data);
      $this->load->view('parent/student/excuseletter', $data);
      $this->load->view('layout/parent/footer', $data);
   }

   public function excuse_letter_add()
   {
      $student_id = '';

      $this->form_validation->set_rules('apply_date', $this->lang->line('apply') . " " . $this->lang->line('date'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('from_date', $this->lang->line('from') . " " . $this->lang->line('date'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('to_date', $this->lang->line('to') . " " . $this->lang->line('date'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('student', $this->lang->line('student'), 'trim|required|xss_clean');

      if ($this->form_validation->run() == FALSE) {
         $msg = array(
            'apply_date' => form_error('apply_date'),
            'from_date' => form_error('from_date'),
            'to_date' => form_error('to_date'),
            'student' => form_error('student'),
         );

         $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
      } else {
         // //$student_session_id = $this->apply_leave_model->get_studentsessionId($_POST['class'], $_POST['section'], $_POST['student']);
         $student_session_id = $this->apply_leave_model->get_studentsessionId2($_POST['student']);

         $data = array(
            'apply_date' => date('Y-m-d', strtotime($this->input->post('apply_date'))),
            'from_date' => date('Y-m-d', strtotime($this->input->post('from_date'))),
            'to_date' => date('Y-m-d', strtotime($this->input->post('to_date'))),
            'student_session_id' => $student_session_id['id'],
            'reason' => $this->input->post('message'),
            'request_type' => '1'
         );

         if ($this->input->post('leave_id') == '') {
            $leave_id = $this->apply_leave_model->add($data);
         } else {
            $data['id'] = $this->input->post('leave_id');
            $this->apply_leave_model->add($data);
         }

         if (isset($_FILES["userfile"]) && !empty($_FILES['userfile']['name'])) {
            $fileInfo = pathinfo($_FILES["userfile"]["name"]);
            $img_name = $leave_id . '.' . $fileInfo['extension'];

            // move_uploaded_file($_FILES["userfile"]["tmp_name"], "./uploads/student_leavedocuments/" . $img_name);
            $this->load->library('s3');
            $s3 = new S3(AWS_ACCESS_KEY_ID, AWS_ACCESS_KEY_SECRET, false, S3_URI, AWS_REGION);
            $dest_file = $_SESSION['School_Code'] . "uploads/student_leavedocuments/" . $img_name;
            $s3->putObjectFile($_FILES["userfile"]["tmp_name"], S3_BUCKET, $dest_file, S3::ACL_PUBLIC_READ);

            $data = array('id' => $leave_id, 'docs' => $img_name);
            $this->apply_leave_model->add($data);
         }

         $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
      }

      echo json_encode($array);
   }

   public function get_details($id)
   {
      $data = $this->apply_leave_model->get($id, null, null);
      $data['from_date'] = date($this->customlib->getSchoolDateFormat(), strtotime($data['from_date']));
      $data['to_date'] = date($this->customlib->getSchoolDateFormat(), strtotime($data['to_date']));
      $data['apply_date'] = date($this->customlib->getSchoolDateFormat(), strtotime($data['apply_date']));
      echo json_encode($data);
   }

   public function remove_leave($id)
   {
      $this->apply_leave_model->remove_leave($id);
      redirect('parent/student/excuseletter');
   }

   public function getgrades($student_id)
   {
      // print_r($student_id);
      // die();
      $this->auth->validate_child($student_id);
      $this->session->set_userdata('top_menu', 'Grades');
      $this->session->set_userdata('sub_menu', 'parent/parents/grades');

      $student = $this->student_model->get($student_id);
      $data['student'] = $student;
      $class_id = $student['class_id'];
      $section_id = $student['section_id'];
      $student_current_class = $this->customlib->getStudentCurrentClsSection();

      $grade_level_info = $this->class_model->get_grade_level_info($class_id);
      $data['quarter_list'] = $this->gradereport_model->get_quarter_list($grade_level_info['term_alias'], $grade_level_info['term_length']);

      $data['legend_list'] = $this->conduct_model->get_conduct_legend_list();
      $data['school_code'] = strtolower($this->sch_setting_detail->dise_code);
      $data['month_days_list'] = $this->gradereport_model->get_month_days_list();
      $data['show_general_average'] = $this->sch_setting_detail->grading_general_average;
      $data['show_letter_grade'] = $this->sch_setting_detail->show_letter_grade;
      $data['show_average_column'] = $this->sch_setting_detail->show_average_column;
      $data['terms_allowed'] = $this->gradereport_model->get_terms_allowed($this->sch_setting_detail->session_id, $student_id);

      // print_r($data['terms_allowed']);
      // exit();

      if (strtolower($this->sch_setting_detail->dise_code) == 'lpms') {
         $studentinfo = $this->student_model->get($student_id);
         $class_record = $this->gradereport_model->get_student_class_record_unrestricted_lpms($this->sch_setting_detail->session_id, $student_id, $class_id, $section_id);
         $adviser = $this->classteacher_model->teacherByClassSection($class_id, $section_id);
         $data['student'] = $studentinfo;
         $data['school_year'] = $this->setting_model->getCurrentSessionName();
         $data['swh_scores'] = $this->gradereport_model->get_swh_score_quarterly($this->sch_setting_detail->session_id, $class_id, $section_id, $student_id);
         $data['resultlist'] = $class_record;
         $data['class_adviser'] = $adviser[0]['name'] . ' ' . $adviser[0]['surname'];
         $data['codes_table'] = $this->gradereport_model->grade_code_table();

         // $this->db->select("*");
         // $this->db->where("session_id", $this->sch_setting_detail->session_id);
         // $this->db->where("class_id", $class_id);
         // $this->db->where("section_id", $section_id);
         // $this->db->where("student_id", $student_id);
         // $student_attendance = $this->db->get("attendance_by_semester")->result_array()[0];

         $student_attendance = $this->gradereport_model->get_student_attendance_by_semester($this->sch_setting_detail->session_id, $class_id, $section_id, $student_id);

         if ($student_attendance) {
            $data['student_attendance'] = $student_attendance;
         } else {
            $data['student_attendance'] = array();
         }

         $this->load->view('layout/parent/header', $data);
         $this->load->view('parent/student/getclassrecord_lpms', $data);
         $this->load->view('layout/parent/footer', $data);
      } else if (strtolower($this->sch_setting_detail->dise_code) == 'ssapamp') {
         $result1 = $this->grading_ssapamp_model->getLevelId('Pre-Kinder');
         $prekinder = $result1[0]->id;
         $grade_level = $class_id;
         $data['prekinderid'] = $prekinder;
         $data['studentid'] = $student_id;
         $data['session'] = $this->sch_setting_detail->session_id;
         $data['class'] = $class_id;
         $data['section'] = $section_id;

         $student_attendance = $this->gradereport_model->get_student_attendance_by_month($this->sch_setting_detail->session_id, $class_id, $section_id, $student_id);

         if ($student_attendance) {
            $data['student_attendance'] = $student_attendance;
         } else {
            $data['student_attendance'] = array();
         }

         // $data['terms_allowed'] = $this->gradereport_model->get_terms_allowed($this->sch_setting_detail->session_id, $student_id);
         $data['ssap_conduct'] = $this->gradereport_model->get_conduct_ssapamp_restricted($this->sch_setting_detail->session_id, $class_id, $section_id, $student_id);

         // print_r($data);
         // die();

         // print_r($data['ssap_conduct']);
         // die();

         // print_r($data['terms_allowed']);
         // exit();

         if ($grade_level == $prekinder) {
            $class_record = $this->grading_studentgrade_ssapamp_model->get_student_checklist($this->sch_setting_detail->session_id, $class_id, $section_id, $student_id);
            $data['resultlist'] = $class_record;

            // print_r($class_record);
            // die();

            $legend = $this->grading_checklist_ssapamp_model->getLegend();
            $data['legend_list'] = $legend;
            $allow1 = $this->grading_ssapamp_model->getAllowed($student_id, $this->sch_setting_detail->session_id, 1);
            $allow2 = $this->grading_ssapamp_model->getAllowed($student_id, $this->sch_setting_detail->session_id, 2);

            // print_r($data);
            // die();

            $data['allow1'] = $allow1;
            $data['allow2'] = $allow2;

            $this->load->view('layout/parent/header', $data);
            $this->load->view('parent/student/getclassrecord_ssapamp', $data);
            $this->load->view('layout/parent/footer', $data);
         } else {
            $data['codes_table'] = $this->gradereport_model->grade_code_table();
            $class_record = $this->gradereport_model->get_student_class_record($this->sch_setting_detail->session_id, $student_id, $class_id, $section_id);
            $data['resultlist'] = $class_record;

            // print_r($data['resultlist']);
            // die();

            print_r($data['terms_allowed']);
            exit();

            $this->load->view('layout/parent/header', $data);
            $this->load->view('parent/student/getclassrecord', $data);
            $this->load->view('layout/parent/footer', $data);
         }
      } else {
         $data['codes_table'] = $this->gradereport_model->grade_code_table();

         $student_attendance = $this->gradereport_model->get_student_attendance_by_month($this->sch_setting_detail->session_id, $class_id, $section_id, $student_id);

         if ($student_attendance) {
            $data['student_attendance'] = $student_attendance;
         } else {
            $data['student_attendance'] = array();
         }

         $data['month_days_list'] = $this->gradereport_model->get_month_days_list();

         $class_record = $this->gradereport_model->get_student_class_record($this->sch_setting_detail->session_id, $student_id, $class_id, $section_id);
         $data['resultlist'] = $class_record;
         $data['conduct_grading_type'] = $this->sch_setting_detail->conduct_grading_type;
         $student_conduct = null;
         if ($this->sch_setting_detail->conduct_grade_view == 0) {
            if ($this->sch_setting_detail->conduct_grading_type == 'letter')
               $student_conduct = $this->gradereport_model->get_student_conduct($this->sch_setting_detail->session_id, $class_id, $section_id, $student_id);
            else if ($this->sch_setting_detail->conduct_grading_type == 'number')
               $student_conduct = $this->gradereport_model->get_student_conduct_numeric($this->sch_setting_detail->session_id, $class_id, $section_id, $student_id);
         }

         $data['student_conduct'] = $student_conduct;

         $this->load->view('layout/parent/header', $data);
         $this->load->view('parent/student/getclassrecord', $data);
         $this->load->view('layout/parent/footer', $data);
      }
   }

   public function kampuspay_transactions()
   {
      $this->session->set_userdata('top_menu', 'Fees');
      $this->session->set_userdata('sub_menu', 'parent/parents/kampuspay_transactions');
      $data['title'] = 'KampusPay Transactions';

      $userbind = $this->getUserQueryBindData();

      // print_r($userbind);
      // die();

      // if ($userbind != null) {
      //     // $userdata = $this->customlib->getUserData();

      //     $this->load->view('layout/parent/header', $data);
      //     $this->load->view("parent/kampuspay_transactions", $data);
      //     $this->load->view('layout/parent/footer', $data);
      // } else {
      //     $linking_page = $this->getKampusPayBindUidURL();
      //     $data['linking_page'] = $linking_page;
      //     // print_r($linking_page);
      //     // die();
      //     // header('Location: ' . $linking_page);
      //     // echo '<script>window.open("' . $linking_page . '","_blank")</script>';
      //     $this->load->view('layout/parent/header', $data);
      //     $this->load->view("parent/kampuspay_link_account", $data);
      //     $this->load->view('layout/parent/footer', $data);
      // }

      $this->load->view('layout/parent/header', $data);
      $this->load->view("parent/kampuspay_transactions", $data);
      $this->load->view('layout/parent/footer', $data);
   }

   public function getKampusPayTransactions($startdate, $enddate)
   {
      $start = strtotime($startdate);
      $end = strtotime($enddate);
      $sessionData = $this->customlib->getLoggedInUserData();
      // print_r($sessionData['id']);
      // die();
      $username = $this->parent_model->getUserName($sessionData['id']);
      $app_user_id = $username;

      $access_key = $this->sch_setting_detail->kampuspay_access_key;
      $key = strtoupper($this->sch_setting_detail->kampuspay_key);
      $ts = strval(strtotime("now"));
      // $sign = strtoupper(md5("access_key=" . $access_key . "&app_user_id=" . $app_user_id . "&bill_state=COMPLETED&bill_type=PAYMENT&end=" . $end . "&limit=3000&pay_way=bananapay&platform=Fucent&start=" . $start . "&ts=" . $ts . "&key=" . $key));
      $sign = strtoupper(md5("access_key=" . $access_key . "&app_user_id=" . $app_user_id . "&bill_state=COMPLETED&bill_type=PAYMENT&pay_way=bananapay&platform=Fucent&ts=" . $ts . "&key=" . $key));

      // echo ("<PRE>");
      // print_r("access_key=" . $access_key . "&app_user_id=" . $app_user_id . "&bill_state=COMPLETED&bill_type=PAYMENT&end=" . $end . "&limit=3000&pay_way=bananapay&platform=Fucent&start=" . $start . "&ts=" . $ts . "&key=" . $key);
      // echo ("<PRE>");
      // print_r(strtoupper(md5("access_key=" . $access_key . "&app_user_id=" . $app_user_id . "&bill_state=COMPLETED&bill_type=PAYMENT&end=" . $end . "&limit=3000&pay_way=bananapay&platform=Fucent&start=" . $start . "&ts=" . $ts . "&key=" . $key)));
      // echo ("<PRE>");
      // die();

      // $data_array =  array(
      //     "access_key" => "$access_key",
      //     "app_user_id" => $app_user_id,
      //     "bill_state" => "COMPLETED",
      //     "bill_type" => "PAYMENT",
      //     "end" => $end,
      //     "limit" => "3000",
      //     "pay_way" => "bananapay",
      //     "platform" => "Fucent",
      //     "start" => $start,
      //     "ts" => $ts,
      //     "sign" => $sign
      // );

      $data_array =  array(
         "access_key" => "$access_key",
         "app_user_id" => $app_user_id,
         "bill_state" => "COMPLETED",
         "bill_type" => "PAYMENT",
         "pay_way" => "bananapay",
         "platform" => "Fucent",
         "ts" => $ts,
         "sign" => $sign
      );

      // echo ("<PRE>");
      // print_r(json_encode($data_array));
      // echo ("<PRE>");
      // die();

      $data_string = json_encode($data_array);

      $url = 'http://test.bananapay.cn/phl/api/v3.0/Cashier.Payment.BananapayGlobalBillQuery';
      $ch = curl_init($url);

      curl_setopt_array($ch, array(
         CURLOPT_POST => true,
         CURLOPT_POSTFIELDS => $data_string,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_HTTPHEADER => array('Content-Type:application/json', 'Content-Length: ' . strlen($data_string))
      ));

      $response = curl_exec($ch);
      curl_close($ch);
      // $data = json_decode(file_get_contents('php://input'), true);
      $result = json_decode($response, true);

      $data = $result['results']['orders'];

      $retVal = array('data' => array());

      foreach ($data as $key => $value) {
         $retVal['data'][$key] = array(
            $value['trade_no'],
            $value['out_trade_no'],
            $value['subject'],
            $value['pay_amount'] / 100,
            $value['gmt_payment']
         );
      }

      // echo ("<PRE>");
      // print_r(json_encode($retVal));
      // echo ("<PRE>");
      // die();

      echo json_encode($retVal);
   }

   public function getKampusPayQRCode()
   {
      $tname = $this->input->post('tname');
      $tprice = $this->input->post('tprice');

      $sessionData = $this->customlib->getLoggedInUserData();
      // print_r($sessionData['id']);
      // die();
      $username = $this->parent_model->getUserName($sessionData['id']);

      $access_key = $this->sch_setting_detail->kampuspay_access_key;
      $key = strtoupper($this->sch_setting_detail->kampuspay_key);
      $ts = strval(strtotime("now"));
      $out_trade_no = $username . '-' . $ts;
      $sign = strtoupper(md5("access_key=" . $access_key . "&expire=60&fee_type=PHP&notify_url=https://www.google.com&out_trade_no=" . $out_trade_no . "&pay_way=bananapay&platform=Fucent&tname=" . $tname . "&tprice=" . $tprice . "&ts=" . $ts . "&key=" . $key));

      // echo ("<PRE>");
      // print_r("access_key=" . $access_key . "&expire=60&fee_type=PHP&notify_url=https://www.google.com&out_trade_no=" . $out_trade_no . "&pay_way=bananapay&platform=Fucent&tname=" . $tname . "&tprice=" . $tprice . "&ts=" . $ts . "&key=" . $key);
      // echo ("<PRE>");
      // print_r(strtoupper(md5("access_key=" . $access_key . "&expire=60&fee_type=PHP&notify_url=https://www.google.com&out_trade_no=" . $out_trade_no . "&pay_way=bananapay&platform=Fucent&tname=" . $tname . "&tprice=" . $tprice . "&ts=" . $ts . "&key=" . $key)));
      // echo ("<PRE>");
      // die();

      $data_array =  array(
         "access_key" => "$access_key",
         "expire" => "60",
         "fee_type" => "PHP",
         "notify_url" => "https://www.google.com",
         "out_trade_no" => $out_trade_no,
         "pay_way" => "bananapay",
         "platform" => "Fucent",
         "tname" => $tname,
         "tprice" => $tprice,
         "ts" => $ts,
         "sign" => $sign
      );

      // echo ("<PRE>");
      // print_r(json_encode($data_array));
      // echo ("<PRE>");
      // die();

      $data_string = json_encode($data_array);

      $url = 'http://test.bananapay.cn/phl/api/v3.0/Cashier.Payment.ScanPay';
      $ch = curl_init($url);

      curl_setopt_array($ch, array(
         CURLOPT_POST => true,
         CURLOPT_POSTFIELDS => $data_string,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_HTTPHEADER => array('Content-Type:application/json', 'Content-Length: ' . strlen($data_string))
      ));

      $response = curl_exec($ch);
      curl_close($ch);

      // echo ("<PRE>");
      // print_r($response);
      // echo ("<PRE>");
      // die();

      if ($response != null) {
         $data_transact =  array(
            "client_username" => $username,
            "transaction_details" => json_encode($data_array)
         );

         $this->kampuspay_model->m_saveTransaction($data_transact);
      }

      $result = json_decode($response, true);

      echo json_encode($result);
   }

   public function getKampusPayBindUidURL()
   {
      $sessionData = $this->customlib->getLoggedInUserData();
      $partner_uid = $this->parent_model->getUserName($sessionData['id']);

      $access_key = $this->sch_setting_detail->kampuspay_access_key;
      $key = strtoupper($this->sch_setting_detail->kampuspay_key);
      $ts = strval(strtotime("now"));
      $sign = strtoupper(md5("access_key=" . $access_key . "&partner_uid=" . $partner_uid . "&pay_way=bananapay&ts=" . $ts . "&type=bind&key=" . $key));

      // echo ("<PRE>");
      // print_r("access_key=" . $access_key . "&partner_uid=" . $partner_uid . "&pay_way=bananapay&ts=" . $ts . "&type=bind&key=" . $key);
      // echo ("<PRE>");
      // print_r(strtoupper(md5("access_key=" . $access_key . "&partner_uid=" . $partner_uid . "&pay_way=bananapay&ts=" . $ts . "&type=bind&key=" . $key)));
      // echo ("<PRE>");
      // die();

      $data_array =  array(
         "access_key" => $access_key,
         "partner_uid" => $partner_uid,
         "pay_way" => "bananapay",
         "ts" => $ts,
         "type" => "bind",
         "sign" => $sign
      );

      // echo ("<PRE>");
      // print_r(json_encode($data_array));
      // echo ("<PRE>");
      // die();

      $data_string = json_encode($data_array);

      $url = 'http://test.bananapay.cn/phl/api/v3.0/Cashier.Payment.BananapayBindUid';
      $ch = curl_init($url);

      curl_setopt_array($ch, array(
         CURLOPT_POST => true,
         CURLOPT_POSTFIELDS => $data_string,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_HTTPHEADER => array('Content-Type:application/json', 'Content-Length: ' . strlen($data_string))
      ));

      $response = curl_exec($ch);
      curl_close($ch);

      // echo ("<PRE>");
      // print_r($response);
      // echo ("<PRE>");
      // die();

      $result = json_decode($response, true);
      $error = $result['errno'];
      $message = $result['message'];
      $data = $result['results'];
      $url1 = $data['url'];

      $sign = strtoupper(md5("access_key=" . $access_key . "&partner_uid=" . $partner_uid . "&pay_way=bananapay&ts=" . $ts . "&type=unbind&key=" . $key));

      $data_array =  array(
         "access_key" => $access_key,
         "partner_uid" => $partner_uid,
         "pay_way" => "bananapay",
         "ts" => $ts,
         "type" => "unbind",
         "sign" => $sign
      );

      // echo ("<PRE>");
      // print_r(json_encode($data_array));
      // echo ("<PRE>");
      // die();

      $data_string = json_encode($data_array);

      $url = 'http://test.bananapay.cn/phl/api/v3.0/Cashier.Payment.BananapayBindUid';
      $ch = curl_init($url);

      curl_setopt_array($ch, array(
         CURLOPT_POST => true,
         CURLOPT_POSTFIELDS => $data_string,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_HTTPHEADER => array('Content-Type:application/json', 'Content-Length: ' . strlen($data_string))
      ));

      $response = curl_exec($ch);
      curl_close($ch);

      // echo ("<PRE>");
      // print_r($result);
      // echo ("<PRE>");
      // die();

      $result = json_decode($response, true);
      $error = $result['errno'];
      $message = $result['message'];
      $data = $result['results'];
      $url2 = $data['url'];

      // echo json_encode($data);
      $linking_page = 'https://test-cloudph.bananapay.com.ph/index.php?bindlink=' . urlencode($url1) . "&unbindlink=" . urlencode($url2);
      return $linking_page;
   }

   public function getKampusPayPaymentStatus()
   {
      $out_trade_no = $this->input->post('out_trade_no');
      $access_key = $this->sch_setting_detail->kampuspay_access_key;
      $key = strtoupper($this->sch_setting_detail->kampuspay_key);
      $ts = strval(strtotime("now"));
      $sign = strtoupper(md5("access_key=" . $access_key . "&out_trade_no=" . $out_trade_no . "&pay_way=bananapay&ts=" . $ts . "&key=" . $key));

      // echo ("<PRE>");
      // print_r("access_key=" . $access_key . "&out_trade_no=" . $out_trade_no . "&pay_way=bananapay&ts=" . $ts . "&key=" . $key);
      // echo ("<PRE>");
      // print_r(strtoupper(md5("access_key=" . $access_key . "&out_trade_no=" . $out_trade_no . "&pay_way=bananapay&ts=" . $ts . "&key=" . $key)));
      // echo ("<PRE>");
      // die();

      $data_array =  array(
         "access_key" => "$access_key",
         "out_trade_no" => $out_trade_no,
         "pay_way" => "bananapay",
         "ts" => $ts,
         "sign" => $sign
      );

      // echo ("<PRE>");
      // print_r(json_encode($data_array));
      // echo ("<PRE>");
      // die();

      $data_string = json_encode($data_array);

      $url = 'http://test.bananapay.cn/phl/api/v3.0/Cashier.Payment.QueryOrder';
      $ch = curl_init($url);

      curl_setopt_array($ch, array(
         CURLOPT_POST => true,
         CURLOPT_POSTFIELDS => $data_string,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_HTTPHEADER => array('Content-Type:application/json', 'Content-Length: ' . strlen($data_string))
      ));

      $response = curl_exec($ch);
      curl_close($ch);

      // echo ("<PRE>");
      // print_r($response);
      // echo ("<PRE>");

      $data = json_decode($response, true);

      // echo ("<PRE>");
      // print_r($data);
      // echo ("<PRE>");
      // die();

      echo json_encode($data);
   }

   function getUserQueryBindData()
   {
      $sessionData = $this->customlib->getLoggedInUserData();
      $username = $this->parent_model->getUserName($sessionData['id']);
      $app_user_id = $username;

      $access_key = $this->sch_setting_detail->kampuspay_access_key;
      $key = strtoupper($this->sch_setting_detail->kampuspay_key);
      $ts = strval(strtotime("now"));
      $sign = strtoupper(md5("access_key=" . $access_key . "&app_user_id=" . $app_user_id . "&pay_way=bananapay&platform=Fucent&ts=" . $ts . "&key=" . $key));

      $data_array =  array(
         "access_key" => "$access_key",
         "app_user_id" => $app_user_id,
         "pay_way" => "bananapay",
         "platform" => "Fucent",
         "ts" => $ts,
         "sign" => $sign
      );

      // echo ("<PRE>");
      // print_r(strtoupper(md5("access_key=" . $access_key . "&app_user_id=" . $app_user_id . "&pay_way=bananapay&platform=Fucent&ts=" . $ts . "&key=" . $key)));
      // echo ("<PRE>");

      // echo ("<PRE>");
      // print_r(json_encode($data_array));
      // echo ("<PRE>");
      // die();

      $data_string = json_encode($data_array);

      $url = 'http://test.bananapay.cn/phl/api/v3.0/Cashier.Payment.BananapayQueryBind';
      $ch = curl_init($url);

      curl_setopt_array($ch, array(
         CURLOPT_POST => true,
         CURLOPT_POSTFIELDS => $data_string,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_HTTPHEADER => array('Content-Type:application/json', 'Content-Length: ' . strlen($data_string))
      ));

      $response = curl_exec($ch);
      curl_close($ch);
      // $data = json_decode(file_get_contents('php://input'), true);
      $result = json_decode($response, true);
      $data = $result['results']['user'];

      // echo ("<PRE>");
      // print_r(json_encode($data));
      // echo ("<PRE>");
      // die();

      // echo json_encode($data);
      return $data;
   }
}

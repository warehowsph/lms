<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

class Lesson extends General_Controller
{

   function __construct()
   {
      parent::__construct();
      $this->load->model('lesson_model');
      $this->load->model('general_model');
      $this->load->model('discussion_model');
      $this->load->model('notification_model');
      $this->load->model('notificationsetting_model');
      $this->load->model('studentsubjectattendence_model');
      $this->load->model('subjecttimetable_model');
      $this->load->model('student_model');
      $this->load->model('conference_model');
      $this->load->model('conferencehistory_model');
      $this->load->model('class_model');

      // $this->load->model(array('conference_model', 'conferencehistory_model', 'class_model'));
      $this->load->library('mailsmsconf');
      $this->mailer;
      $this->session->set_userdata('top_menu', 'Download Center');
      $this->session->set_userdata('sub_menu', 'content/lesson');
      $this->writedb = $this->load->database('write_db', TRUE);

      date_default_timezone_set('Asia/Manila');
      // $url = base_url('lms_v2/index.php?/lms/lesson/initialize/'.$this->general_model->get_account_id().'/'.$this->general_model->get_role());    
   }

   function index($lesson_query = "today")
   {
      $this->session->set_userdata('top_menu', 'Download Center');
      $this->session->set_userdata('sub_menu', 'Lesson');
      $this->session->set_userdata('subsub_menu', 'content/lesson');

      $data['title'] = 'Lesson';

      $data['role'] = $this->general_model->get_role();
      $data['real_role'] = $this->general_model->get_real_role();
      $data['classes'] = $this->general_model->get_classes();
      $data['subjects'] = $this->general_model->get_subjects();
      $data['heading'] = "Current Lessons";
      $data['lesson_sched'] = "today";
      $data['lesson_query'] = $lesson_query;
      $data['user_id'] = $this->general_model->get_account_id();

      if ($data['role'] == 'admin') {
         $this->load->view('layout/header');
         if ($data['real_role'] == "7" || $data['real_role'] == "1") {
            $data['list'] = $this->lesson_model->admin_lessons($this->general_model->get_account_id(), "today");

            foreach ($data['list'] as $key => $value) {
               if ($value['zoom_id']) {
                  $zoom_data = $this->lesson_model->lms_get("conferences", $value['zoom_id'], "id")[0];
                  $data['list'][$key]['student_zoom_link'] = json_decode($zoom_data['return_response'])->join_url;
               }

               $teacher_info = $this->lesson_model->lms_get("staff", $value['account_id'], "id")[0];
               $data['list'][$key]['teacher_name'] = $teacher_info['name'];
               $data['list'][$key]['google_meet'] = $teacher_info['google_meet'];
            }
         } else {
            $data['list'] = $this->lesson_model->get_lessons($this->general_model->get_account_id(), "today");
         }
      } else {
         $this->load->view('layout/student/header');
         $data['list'] = $this->lesson_model->student_lessons($this->general_model->get_account_id(), "today");

         foreach ($data['list'] as $key => $value) {
            if ($value['zoom_id']) {
               $zoom_data = $this->lesson_model->lms_get("conferences", $value['zoom_id'], "id")[0];
               $data['list'][$key]['student_zoom_link'] = json_decode($zoom_data['return_response'])->join_url;
            }

            $teacher_info = $this->lesson_model->lms_get("staff", $value['account_id'], "id")[0];
            $data['list'][$key]['teacher_name'] = $teacher_info['name'];
            $data['list'][$key]['google_meet'] = $teacher_info['google_meet'];
         }
      }

      $this->load->view('lms/lesson/index', $data);
      $this->load->view('layout/footer');
   }

   function emails($lesson_id)
   {
      $this->db->select("lms_lesson_email_logs.*,students.firstname,students.lastname");
      $this->db->join("students", "lms_lesson_email_logs.student_id = students.id");
      $this->db->where("lms_lesson_email_logs.lesson_id", $lesson_id);
      $this->db->order_by("lms_lesson_email_logs.date_created", "desc");
      $return_data = $this->db->get("lms_lesson_email_logs")->result_array();
      echo json_encode($return_data);
   }

   function attendance($lesson_id)
   {
      $this->db->select("students.firstname,students.lastname,lms_lesson_logs.date_created as timestamp");
      $this->db->join("students", "lms_lesson_logs.account_id = students.id");
      $this->db->where("lms_lesson_logs.lesson_id", $lesson_id);
      $this->db->group_by("students.id");
      $this->db->order_by("students.firstname", "desc");
      $return_data = $this->db->get("lms_lesson_logs")->result_array();

      // print_r($this->db->last_query());
      // die();

      echo json_encode($return_data);
   }

   function index_create($lesson_query = "today")
   {
      $this->session->set_userdata('top_menu', 'Download Center');
      $this->session->set_userdata('sub_menu', 'Lesson');
      $this->session->set_userdata('subsub_menu', 'lesson/create');

      $data['title'] = 'Lesson';
      $data['role'] = $this->general_model->get_role();
      $data['real_role'] = $this->general_model->get_real_role();
      $data['classes'] = $this->general_model->get_classes();
      $data['subjects'] = $this->general_model->get_subjects();
      $data['heading'] = "Current Lessons";
      $data['lesson_sched'] = "today";
      $data['lesson_query'] = $lesson_query;

      if ($data['role'] == 'admin') {
         $this->load->view('layout/header');
         if ($data['real_role'] == "7" || $data['real_role'] == "1") {
            $data['list'] = $this->lesson_model->admin_lessons($this->general_model->get_account_id(), "today");
            foreach ($data['list'] as $key => $value) {
               if ($value['zoom_id']) {
                  $zoom_data = $this->lesson_model->lms_get("conferences", $value['zoom_id'], "id")[0];
                  $data['list'][$key]['student_zoom_link'] = json_decode($zoom_data['return_response'])->join_url;
               }

               $teacher_info = $this->lesson_model->lms_get("staff", $value['account_id'], "id")[0];
               $data['list'][$key]['teacher_name'] = $teacher_info['name'];
               $data['list'][$key]['google_meet'] = $teacher_info['google_meet'];
            }
         } else {
            $data['list'] = $this->lesson_model->get_lessons($this->general_model->get_account_id(), "today");
         }
      } else {
         $this->load->view('layout/student/header');
         $data['list'] = $this->lesson_model->student_lessons($this->general_model->get_account_id(), "today");
         foreach ($data['list'] as $key => $value) {
            if ($value['zoom_id']) {
               $zoom_data = $this->lesson_model->lms_get("conferences", $value['zoom_id'], "id")[0];
               $data['list'][$key]['student_zoom_link'] = json_decode($zoom_data['return_response'])->join_url;
            }

            $teacher_info = $this->lesson_model->lms_get("staff", $value['account_id'], "id")[0];
            $data['list'][$key]['teacher_name'] = $teacher_info['name'];
            $data['list'][$key]['google_meet'] = $teacher_info['google_meet'];
         }
      }

      $this->load->view('lms/lesson/index_create', $data);
      $this->load->view('layout/footer');
   }

   function upcoming($lesson_query = "upcoming")
   {
      $this->session->set_userdata('top_menu', 'Download Center');
      $this->session->set_userdata('sub_menu', 'Lesson');
      $this->session->set_userdata('subsub_menu', 'content/upcoming');

      $data['title'] = 'Lesson';
      $data['role'] = $this->general_model->get_role();
      $data['real_role'] = $this->general_model->get_real_role();
      $data['classes'] = $this->general_model->get_classes();
      $data['subjects'] = $this->general_model->get_subjects();
      $data['heading'] = "Upcoming Lessons";
      $data['lesson_sched'] = "upcoming";
      $data['lesson_query'] = $lesson_query;

      if ($data['role'] == 'admin') {
         $this->load->view('layout/header');
         if ($data['real_role'] == "7" || $data['real_role'] == "1") {
            $data['list'] = $this->lesson_model->admin_lessons($this->general_model->get_account_id(), "upcoming");
            foreach ($data['list'] as $key => $value) {
               if ($value['zoom_id']) {
                  $zoom_data = $this->lesson_model->lms_get("conferences", $value['zoom_id'], "id")[0];
                  $data['list'][$key]['student_zoom_link'] = json_decode($zoom_data['return_response'])->join_url;
               }

               $teacher_info = $this->lesson_model->lms_get("staff", $value['account_id'], "id")[0];
               $data['list'][$key]['teacher_name'] = $teacher_info['name'];
               $data['list'][$key]['google_meet'] = $teacher_info['google_meet'];
            }
         } else {
            $data['list'] = $this->lesson_model->get_lessons($this->general_model->get_account_id(), "upcoming");
         }
      } else {
         $this->load->view('layout/student/header');
         $data['list'] = $this->lesson_model->student_lessons($this->general_model->get_account_id(), "upcoming");
         foreach ($data['list'] as $key => $value) {
            if ($value['zoom_id']) {
               $zoom_data = $this->lesson_model->lms_get("conferences", $value['zoom_id'], "id")[0];
               $data['list'][$key]['student_zoom_link'] = json_decode($zoom_data['return_response'])->join_url;
            }

            $teacher_info = $this->lesson_model->lms_get("staff", $value['account_id'], "id")[0];
            $data['list'][$key]['teacher_name'] = $teacher_info['name'];
            $data['list'][$key]['google_meet'] = $teacher_info['google_meet'];
         }
      }

      $this->load->view('lms/lesson/index', $data);
      $this->load->view('layout/footer');
   }

   function past($lesson_query = "past", $lesson_name = "", $lesson_subject = "", $lesson_quarter = "")
   {

      $this->session->set_userdata('top_menu', 'Download Center');
      $this->session->set_userdata('sub_menu', 'Lesson');
      $this->session->set_userdata('subsub_menu', 'content/past');

      $data['title'] = 'Lesson';

      $data['role'] = $this->general_model->get_role();
      $data['real_role'] = $this->general_model->get_real_role();
      $data['classes'] = $this->general_model->get_classes();
      $data['subjects'] = $this->general_model->get_subjects();
      $data['user_id'] = $this->general_model->get_account_id();
      $data['heading'] = "Past Lessons";
      $data['lesson_sched'] = "past";
      $data['lesson_query'] = $lesson_query;
      $lesson_name = $_REQUEST['lesson_name'];
      $lesson_subject = $_REQUEST['lesson_subject'];
      $lesson_quarter = $_REQUEST['lesson_quarter'];

      if ($data['role'] == 'admin') {
         $this->load->view('layout/header');

         if ($data['real_role'] == "7" || $data['real_role'] == "1") {
            if (!$lesson_name && !$lesson_subject && !$lesson_quarter) {
               $data['list'] = $this->lesson_model->admin_lessons($this->general_model->get_account_id(), "past");
            } else {

               $data['list'] = $this->lesson_model->admin_lessons_search($this->general_model->get_account_id(), "past", $lesson_name, $lesson_subject, $lesson_quarter);
            }

            foreach ($data['list'] as $key => $value) {
               if ($value['zoom_id']) {
                  $zoom_data = $this->lesson_model->lms_get("conferences", $value['zoom_id'], "id")[0];
                  $data['list'][$key]['student_zoom_link'] = json_decode($zoom_data['return_response'])->join_url;
               }

               $teacher_info = $this->lesson_model->lms_get("staff", $value['account_id'], "id")[0];
               $data['list'][$key]['teacher_name'] = $teacher_info['name'];
               $data['list'][$key]['google_meet'] = $teacher_info['google_meet'];
            }
         } else {
            $data['list'] = $this->lesson_model->get_lessons($this->general_model->get_account_id(), "past");
         }
      } else {
         $this->load->view('layout/student/header');
         $data['list'] = $this->lesson_model->student_lessons($this->general_model->get_account_id(), "past");

         foreach ($data['list'] as $key => $value) {
            if ($value['zoom_id']) {
               $zoom_data = $this->lesson_model->lms_get("conferences", $value['zoom_id'], "id")[0];
               $data['list'][$key]['student_zoom_link'] = json_decode($zoom_data['return_response'])->join_url;
            }

            $teacher_info = $this->lesson_model->lms_get("staff", $value['account_id'], "id")[0];
            $data['list'][$key]['teacher_name'] = $teacher_info['name'];
            $data['list'][$key]['google_meet'] = $teacher_info['google_meet'];
         }
      }

      $this->load->view('lms/lesson/index', $data);
      $this->load->view('layout/footer');
   }

   function past_search($lesson_query = "past", $search = "")
   {

      $this->session->set_userdata('top_menu', 'Download Center');
      $this->session->set_userdata('sub_menu', 'content/past');

      $data['title'] = 'Lesson';

      $data['role'] = $this->general_model->get_role();
      $data['real_role'] = $this->general_model->get_real_role();
      $data['classes'] = $this->general_model->get_classes();
      $data['subjects'] = $this->general_model->get_subjects();
      $data['user_id'] = $this->general_model->get_account_id();
      $data['heading'] = "Past Lessons";
      $data['lesson_sched'] = "past";
      $data['lesson_query'] = $lesson_query;
      if ($data['role'] == 'admin') {
         $this->load->view('layout/header');
         if ($data['real_role'] == "7" || $data['real_role'] == "1") {
            $data['list'] = $this->lesson_model->admin_lessons_search($this->general_model->get_account_id(), "past", $search);
            foreach ($data['list'] as $key => $value) {
               if ($value['zoom_id']) {
                  $zoom_data = $this->lesson_model->lms_get("conferences", $value['zoom_id'], "id")[0];
                  $data['list'][$key]['student_zoom_link'] = json_decode($zoom_data['return_response'])->join_url;
               }

               $teacher_info = $this->lesson_model->lms_get("staff", $value['account_id'], "id")[0];
               $data['list'][$key]['teacher_name'] = $teacher_info['name'];
               $data['list'][$key]['google_meet'] = $teacher_info['google_meet'];
            }
         } else {
            $data['list'] = $this->lesson_model->get_lessons($this->general_model->get_account_id(), "past");
         }
      } else {


         $this->load->view('layout/student/header');
         $data['list'] = $this->lesson_model->student_lessons($this->general_model->get_account_id(), "past");
         foreach ($data['list'] as $key => $value) {
            if ($value['zoom_id']) {
               $zoom_data = $this->lesson_model->lms_get("conferences", $value['zoom_id'], "id")[0];
               $data['list'][$key]['student_zoom_link'] = json_decode($zoom_data['return_response'])->join_url;
            }

            $teacher_info = $this->lesson_model->lms_get("staff", $value['account_id'], "id")[0];
            $data['list'][$key]['teacher_name'] = $teacher_info['name'];
            $data['list'][$key]['google_meet'] = $teacher_info['google_meet'];
         }
      }

      $this->load->view('lms/lesson/index', $data);
      $this->load->view('layout/footer');
   }

   function lesson_bin()
   {

      $this->session->set_userdata('top_menu', 'Download Center');
      $this->session->set_userdata('sub_menu', 'Lesson');
      $this->session->set_userdata('subsub_menu', 'content/lesson_bin');

      $data['title'] = 'Lesson';

      $data['role'] = $this->general_model->get_role();
      $data['real_role'] = $this->general_model->get_real_role();
      $data['classes'] = $this->general_model->get_classes();
      $data['subjects'] = $this->general_model->get_subjects();
      $data['heading'] = "Past Lessons";
      $data['lesson_sched'] = "past";

      // print_r($data);
      // die();

      if ($data['role'] == 'admin') {
         $this->load->view('layout/header');
         // print_r($data);
         // die();
         if ($data['real_role'] == "7" || $data['real_role'] == "1") {
            $data['list'] = $this->lesson_model->admin_deleted($this->general_model->get_account_id(), "past");
            foreach ($data['list'] as $key => $value) {
               if ($value['zoom_id']) {
                  $zoom_data = $this->lesson_model->lms_get("conferences", $value['zoom_id'], "id")[0];
                  $data['list'][$key]['student_zoom_link'] = json_decode($zoom_data['return_response'])->join_url;
               }

               $teacher_info = $this->lesson_model->lms_get("staff", $value['account_id'], "id")[0];
               $data['list'][$key]['teacher_name'] = $teacher_info['name'];
               $data['list'][$key]['google_meet'] = $teacher_info['google_meet'];
            }
         } else {
            $data['list'] = $this->lesson_model->deleted_lessons($this->general_model->get_account_id(), "past");
         }
      } else {
         // $data['lesson_query'] = $lesson_query;
         $this->load->view('layout/student/header');
         $data['list'] = $this->lesson_model->student_lessons($this->general_model->get_account_id(), "past");

         foreach ($data['list'] as $key => $value) {
            if ($value['zoom_id']) {
               $zoom_data = $this->lesson_model->lms_get("conferences", $value['zoom_id'], "id")[0];
               $data['list'][$key]['student_zoom_link'] = json_decode($zoom_data['return_response'])->join_url;
            }

            $teacher_info = $this->lesson_model->lms_get("staff", $value['account_id'], "id")[0];
            $data['list'][$key]['teacher_name'] = $teacher_info['name'];
            $data['list'][$key]['google_meet'] = $teacher_info['google_meet'];
         }
      }

      $this->load->view('lms/lesson/lesson_bin', $data);
      $this->load->view('layout/footer');
   }

   function get_zoom_data($id = "")
   {

      // if($id){
      //     return $this->lesson_model->lms_get("conferences",$id,"id")[0];
      // }else{
      //     return "";
      // }

   }

   function shared()
   {

      $this->session->set_userdata('top_menu', 'Download Center');
      $this->session->set_userdata('sub_menu', 'Lesson');
      $this->session->set_userdata('subsub_menu', 'content/shared_lesson');

      $data['title'] = 'Lesson';

      $data['role'] = $this->general_model->get_role();
      $data['classes'] = $this->general_model->get_classes();
      $data['subjects'] = $this->general_model->get_subjects();

      if ($data['role'] == 'admin') {
         $this->load->view('layout/header');
         $data['list'] = $this->lesson_model->get_shared_lessons($this->general_model->get_account_id());
      } else {

         $this->load->view('layout/student/header');
         $data['list'] = $this->lesson_model->student_lessons($this->general_model->get_account_id());
      }


      $this->load->view('lms/lesson/shared', $data);
      $this->load->view('layout/footer');
   }

   function virtual()
   {

      $this->session->set_userdata('top_menu', 'Download Center');
      $this->session->set_userdata('sub_menu', 'content/virtual');

      $data['title'] = 'Lesson';

      $data['role'] = $this->general_model->get_role();
      $data['classes'] = $this->general_model->get_classes();
      $data['subjects'] = $this->general_model->get_subjects();

      if ($data['role'] == 'admin') {
         $this->load->view('layout/header');
         $data['list'] = $this->lesson_model->get_lessons_virtual_only($this->general_model->get_account_id());
      } else {

         $this->load->view('layout/student/header');
         $data['list'] = $this->lesson_model->student_lessons($this->general_model->get_account_id());
      }


      $this->load->view('lms/lesson/virtual', $data);
      $this->load->view('layout/footer');
   }

   function save($lesson_type = "classroom")
   {
      $data['lesson_name'] = $_REQUEST['content_title'];
      $data['subject_id'] = $_REQUEST['subject'];
      $data['grade_id'] = $_REQUEST['grade'];
      $data['education_level'] = $_REQUEST['education_level'];
      $data['term'] = $_REQUEST['term'];
      $data['lesson_type'] = $lesson_type;
      $data['account_id'] = $this->general_model->get_account_id();
      $data['google_meet'] = $this->general_model->get_account_name($data['account_id'], "admin")[0]['google_meet'];
      $data['start_date'] = date("Y/m/d H:i:s");
      $data['end_date'] = date("Y/m/d H:i:s", strtotime('now +1 hour'));

      $lesson_data = array(
         "account_id" => $this->general_model->get_account_id(),
         "name" => $_REQUEST['content_title'],
      );

      // $zoom_id = $this->add_lms_lesson($lesson_data);
      // $data['zoom_id'] = $zoom_id;

      $id = $this->lesson_model->lms_create("lms_lesson", $data);

      if (!is_dir(FCPATH . "uploads/lms_lesson/" . $id)) {
         mkdir(FCPATH . "uploads/lms_lesson/" . $id);
         mkdir(FCPATH . "uploads/lms_lesson/" . $id . "/thumbnails/");
         mkdir(FCPATH . "uploads/lms_lesson/" . $id . "/contents/");
      }

      redirect(site_url() . "lms/lesson/create/" . $id);
      // redirect(site_url() . "lms/lesson/create2/" . $id . "/" . $_REQUEST['grade']);
   }

   function create($id)
   {
      // print_r("EMN Debug Mode!!!");
      // die();

      $data['id'] = $id;
      $data['role'] = $this->general_model->get_role();

      // echo '<pre>';
      // print_r($data['students']);
      // exit();
      // $current_session = $this->setting_model->getCurrentSession();

      $data['lesson'] = $this->lesson_model->lms_get("lms_lesson", $id, "id")[0];

      if ($data['role'] != "student") {
         $data['students'] = $this->lesson_model->get_students("lms_lesson", $id, "id");
         // $data['classes'] = $this->class_model->getAll();
         $data['class_sections'] = $this->lesson_model->get_class_sections();
         $data['classes'] = $this->general_model->get_classes();
         $data['subjects'] = $this->general_model->get_subjects();
         $data['account_id'] = $this->general_model->get_account_id();
         $data['google_meet'] = $this->general_model->get_account_name($data['account_id'], "admin")[0]['google_meet'];

         try {
            if ($data['lesson']['zoom_id'] != null) {
               $data['conference'] = $this->lesson_model->lms_get("conferences", $data['lesson']['zoom_id'], "id")[0];
               $data['start_url'] = json_decode($data['conference']['return_response'])->start_url;
            }
         } catch (exception $e) {
            //code to handle the exception
         }

         $data['lms_google_meet'] = $data['lesson']['google_meet'];
      }

      // print_r($data);
      // die();

      if ($data['google_meet'] == "") {
         $data['virtual_status'] = "available";
      } else {
         $data['virtual_status'] = "not_available";
      }

      if ($data['role'] != "student") {
         if ($data['google_meet'] != $data['lms_google_meet']) {

            $update_google_meet_data['google_meet'] = $data['google_meet'];
            $update_google_meet_data['id'] = $data['id'];
            $this->lesson_model->lms_update("lms_lesson", $update_google_meet_data);
         }
      } else {
         $lesson_log_data['lesson_id'] = $id;
         $lesson_log_data['account_id'] = $this->general_model->get_account_id();
         $lesson_log_data['session_id'] = $this->setting_model->getCurrentSession();

         // print_r($data);
         // die();

         if ($data['lesson']['lesson_type'] == 'classroom')
            $this->lesson_model->lms_create("lms_lesson_logs", $lesson_log_data);
      }

      $data['lesson'] = $this->lesson_model->lms_get("lms_lesson", $id, "id")[0];
      $data['resources'] = site_url('backend/lms/');

      // if (!is_dir(FCPATH . "uploads/lms_lesson/" . $id)) {
      //    mkdir(FCPATH . "uploads/lms_lesson/" . $id);
      //    mkdir(FCPATH . "uploads/lms_lesson/" . $id . "/thumbnails/");
      //    mkdir(FCPATH . "uploads/lms_lesson/" . $id . "/contents/");
      // }

      // print_r($data);
      // die();

      if ($data['role'] != "student") {
         $this->load->view('lms/lesson/create', $data);
      } else {
         $this->load->view('lms/lesson/student_view', $data);
      }
   }

   function create2($id, $gradelevel)
   {
      $data['id'] = $id;

      // $current_session = $this->setting_model->getCurrentSession();
      $data['students'] = $this->lesson_model->get_students_per_level("lms_lesson", $id, "id", $gradelevel);
      // echo '<pre>';print_r($data['students']);exit();
      // $data['classes'] = $this->class_model->getAll();
      $data['class_sections'] = $this->lesson_model->get_class_sections();
      $data['role'] = $this->general_model->get_role();
      $data['classes'] = $this->general_model->get_classes();
      $data['subjects'] = $this->general_model->get_subjects();

      $data['account_id'] = $this->general_model->get_account_id();
      $data['google_meet'] = $this->general_model->get_account_name($data['account_id'], "admin")[0]['google_meet'];
      $data['lesson'] = $this->lesson_model->lms_get("lms_lesson", $id, "id")[0];
      $data['conference'] = $this->lesson_model->lms_get("conferences", $data['lesson']['zoom_id'], "id")[0];
      $data['start_url'] = json_decode($data['conference']['return_response'])->start_url;
      $data['lms_google_meet'] = $data['lesson']['google_meet'];

      // print_r($data);
      // die();

      if ($data['google_meet'] == "") {
         $data['virtual_status'] = "available";
      } else {
         $data['virtual_status'] = "not_available";
      }

      if ($data['role'] != "student") {
         if ($data['google_meet'] != $data['lms_google_meet']) {

            $update_google_meet_data['google_meet'] = $data['google_meet'];
            $update_google_meet_data['id'] = $data['id'];
            $this->lesson_model->lms_update("lms_lesson", $update_google_meet_data);
         }
      } else {
         $lesson_log_data['lesson_id'] = $id;
         $lesson_log_data['account_id'] = $this->general_model->get_account_id();
         $lesson_log_data['session_id'] = $this->setting_model->getCurrentSession();

         if ($data['lesson']['lesson_type'] == 'classroom')
            $this->lesson_model->lms_create("lms_lesson_logs", $lesson_log_data);
      }

      $data['lesson'] = $this->lesson_model->lms_get("lms_lesson", $id, "id")[0];
      $data['resources'] = site_url('backend/lms/');

      // if (!is_dir(FCPATH . "uploads/lms_lesson/" . $id)) {
      //    mkdir(FCPATH . "uploads/lms_lesson/" . $id);
      //    mkdir(FCPATH . "uploads/lms_lesson/" . $id . "/thumbnails/");
      //    mkdir(FCPATH . "uploads/lms_lesson/" . $id . "/contents/");
      // }

      if ($data['role'] != "student") {
         $this->load->view('lms/lesson/create', $data);
      } else {
         $this->load->view('lms/lesson/student_view', $data);
      }
   }

   function show($id)
   {

      $data['id'] = $id;
      $data['lesson'] = $this->lesson_model->lms_get("lms_lesson", $id, "id")[0];

      $data['link'] = $this->lesson_model->lms_get("lms_lesson", $id, "id");
      $current_session = $this->setting_model->getCurrentSession();
      $data['students'] = $this->lesson_model->get_students("lms_lesson", $id, "id");
      $data['classes'] = $this->class_model->getAll();
      $data['class_sections'] = $this->lesson_model->get_class_sections();
      $data['role'] = $this->general_model->get_role();
      $data['classes'] = $this->general_model->get_classes();
      $data['subjects'] = $this->general_model->get_subjects();
      // echo "<pre>";
      // print_r($data['role']);
      // exit();
      $data['resources'] = site_url('backend/lms/');
      if (!is_dir(FCPATH . "uploads/lms_lesson/" . $id)) {
         mkdir(FCPATH . "uploads/lms_lesson/" . $id);
         mkdir(FCPATH . "uploads/lms_lesson/" . $id . "/thumbnails/");
         mkdir(FCPATH . "uploads/lms_lesson/" . $id . "/contents/");
      }
      $this->load->view('lms/lesson/show', $data);
   }

   function import($id)
   {
      $date = date('m/d/Y h:i:s a', time());
      $data['id'] = $id;
      $data['lesson'] = $this->lesson_model->lms_get("lms_lesson", $id, "id")[0];
      $data['lesson']['account_id'] = $this->general_model->get_account_id();
      $data['lesson']['shared'] = 0;
      $data['lesson']['lesson_name'] = "(" . $date . ") " . $data['lesson']['lesson_name'];
      unset($data['lesson']['id']);
      unset($data['lesson']['assigned']);

      // print_r($data);
      // die();

      $this->lesson_model->lms_create("lms_lesson", $data['lesson'], false);
      $data['resources'] = site_url('backend/lms/');
      if (!is_dir(FCPATH . "uploads/lms_lesson/" . $id)) {
         mkdir(FCPATH . "uploads/lms_lesson/" . $id);
         mkdir(FCPATH . "uploads/lms_lesson/" . $id . "/thumbnails/");
         mkdir(FCPATH . "uploads/lms_lesson/" . $id . "/contents/");
      }

      $data['result'] = 'success';
      $data['message'] = 'Import successful!';
      echo json_encode($data);

      // redirect(site_url() . "lms/lesson/index/" . $id);
      // $this->load->view('lms/lesson/show', $data);
   }

   function view($id)
   {

      // $data['id'] = $id;
      // $data['lesson'] = $this->lesson_model->lms_get("lms_lesson", $id, "id")[0];
      // $data['link'] = $this->lesson_model->lms_get("lms_lesson", $id, "id");
      // $current_session = $this->setting_model->getCurrentSession();
      // $data['students'] = $this->lesson_model->get_students("lms_lesson", $id, "id");
      // $data['classes'] = $this->class_model->getAll();
      // $data['class_sections'] = $this->lesson_model->get_class_sections();
      // $data['role'] = $this->general_model->get_role();

      // // echo "<pre>";
      // // print_r($data['students']);
      // // exit();
      // $data['resources'] = site_url('backend/lms/');
      // if (!is_dir(FCPATH . "uploads/lms_lesson/" . $id)) {
      //    mkdir(FCPATH . "uploads/lms_lesson/" . $id);
      //    mkdir(FCPATH . "uploads/lms_lesson/" . $id . "/thumbnails/");
      //    mkdir(FCPATH . "uploads/lms_lesson/" . $id . "/contents/");
      // }
      // $this->load->view('lms/lesson/create', $data);

      // print_r("EMN Debug Mode!!!");
      // die();

      $data['id'] = $id;
      $data['role'] = $this->general_model->get_role();

      // echo '<pre>';
      // print_r($data['students']);
      // exit();
      // $current_session = $this->setting_model->getCurrentSession();

      if ($data['role'] != "student") {
         $data['students'] = $this->lesson_model->get_students("lms_lesson", $id, "id");
         // $data['classes'] = $this->class_model->getAll();
         $data['class_sections'] = $this->lesson_model->get_class_sections();
         $data['classes'] = $this->general_model->get_classes();
         $data['subjects'] = $this->general_model->get_subjects();
         $data['account_id'] = $this->general_model->get_account_id();
         $data['google_meet'] = $this->general_model->get_account_name($data['account_id'], "admin")[0]['google_meet'];
         $data['lesson'] = $this->lesson_model->lms_get("lms_lesson", $id, "id")[0];

         // print_r($data);
         // die();

         try {
            if ($data['lesson']['zoom_id'] != null) {
               $data['conference'] = $this->lesson_model->lms_get("conferences", $data['lesson']['zoom_id'], "id")[0];
               $data['start_url'] = json_decode($data['conference']['return_response'])->start_url;
            }
         } catch (exception $e) {
            //code to handle the exception
         }

         $data['lms_google_meet'] = $data['lesson']['google_meet'];
      }

      // print_r($data);
      // die();

      if ($data['google_meet'] == "") {
         $data['virtual_status'] = "available";
      } else {
         $data['virtual_status'] = "not_available";
      }

      if ($data['role'] != "student") {
         if ($data['google_meet'] != $data['lms_google_meet']) {

            $update_google_meet_data['google_meet'] = $data['google_meet'];
            $update_google_meet_data['id'] = $data['id'];
            $this->lesson_model->lms_update("lms_lesson", $update_google_meet_data);
         }
      } else {
         $lesson_log_data['lesson_id'] = $id;
         $lesson_log_data['account_id'] = $this->general_model->get_account_id();
         $lesson_log_data['session_id'] = $this->setting_model->getCurrentSession();
         // $this->lesson_model->lms_create("lms_lesson_logs", $lesson_log_data);
      }

      $data['lesson'] = $this->lesson_model->lms_get("lms_lesson", $id, "id")[0];

      $data['resources'] = site_url('backend/lms/');

      // if (!is_dir(FCPATH . "uploads/lms_lesson/" . $id)) {
      //    mkdir(FCPATH . "uploads/lms_lesson/" . $id);
      //    mkdir(FCPATH . "uploads/lms_lesson/" . $id . "/thumbnails/");
      //    mkdir(FCPATH . "uploads/lms_lesson/" . $id . "/contents/");
      // }

      // print_r($data);
      // die();

      if ($data['role'] != "student") {
         $this->load->view('lms/lesson/create', $data);
      } else {
         $this->load->view('lms/lesson/student_view', $data);
      }
   }

   public function update()
   {

      $data['id'] = $_REQUEST['id'];
      $data['lesson_name'] = $_REQUEST['title'];
      $data['content_order'] = json_encode($_REQUEST['content_order']);
      $data['content_pool'] = json_encode($_REQUEST['content_pool']);
      $data['folder_names'] = $_REQUEST['folder_names'];
      $data['assigned'] = $_REQUEST['assigned'];
      $data['email_notification'] = $_REQUEST['email_notification'];
      $data['lesson_type'] = $_REQUEST['lesson_type'];
      $data['start_date'] = $_REQUEST['start_date'];
      $data['end_date'] = $_REQUEST['end_date'];
      $data['learning_plan'] = $_REQUEST['learning_plan'];
      $data['subject_id'] = $_REQUEST['subject_id'];
      $data['grade_id'] = $_REQUEST['grade_id'];
      $data['education_level'] = $_REQUEST['education_level'];
      $data['term'] = $_REQUEST['term'];
      $data['shared'] = $_REQUEST['shared'];
      $data['allow_view'] = $_REQUEST['allow_view'];
      $gradeSection = $_REQUEST['grade_section'];

      $this->lesson_model->lms_update("lms_lesson", $data);
      // print_r($this->lesson_model->lms_update("lms_lesson", $data));

      //-- Create period attendance entries
      $grade_sections = explode(",", $gradeSection);
      $grade_section = null;

      // foreach ($grade_sections as $key => $value) {
      //    $grade_section = explode("_", $value);

      //    $class_id = $grade_section[0];
      //    $section_id = $grade_section[1];
      //    // $date = $_REQUEST['start_date'];
      //    // $day = date('l', $this->customlib->datetostrtotime($date));
      //    // $resultlist = $this->studentsubjectattendence_model->searchByStudentsAttendanceByDate($class_id, $section_id, $day, date('Y-m-d', $this->customlib->datetostrtotime($date)));

      //    $result = $this->student_model->searchByClassSection($class_id, $section_id);

      //    //Get subject_timetable_id

      //    // $arr = array(
      //    //    'student_session_id'   => $value,
      //    //    'attendence_type_id'   => 4,
      //    //    'remark'               => $this->input->post("remark" . $value),
      //    //    'subject_timetable_id' => $subject_timetable_id,
      //    //    'date'                 => date('Y-m-d', $this->customlib->datetostrtotime($date)),
      //    // );
      // }



      // thumbnails
      // $this->db->select("content_id");
      // $this->db->where("table_id","thumbnail_".$data['id']);
      // $query = $this->db->get("resources_queue");
      // $thumbnails_result = $query->result_array();
      // $thumbnails = [];
      // foreach ($thumbnails_result as $key => $value) {
      //     array_push($thumbnails, $value['content_id']);
      // }

      // foreach ($_REQUEST['content_pool'] as $key => $value) {
      //     if(!in_array($value['content']['result_id'], $thumbnails)){
      //         $download_data['table_id'] = "thumbnail_".$data['id'];
      //         $download_data['file_type'] = "image";
      //         $download_data['content_id'] = $value['content']['result_id'];
      //         $download_data['url'] = urldecode($value['content']['image']);
      //         $download_data['output_path'] = "C:\\xampp\htdocs\campus\\resources\uploads\blackboard\\".$data['id']."\\thumbnails\\";
      //         $type = $this->check_url_type($value['content']['image']);
      //         $download_data['filename'] = $value['content']['result_id'];
      //         $download_data['completed'] = 0;
      //         $download_data['status'] = "download";

      //         $this->lesson_model->lms_create("resources_queue",$download_data);
      //     }


      // }
      //thumbnails

      //contents
      // $this->db->select("content_id");
      // $this->db->where("table_id",$data['id']);
      // $query = $this->db->get("resources_queue");
      // $content_result = $query->result_array();
      // $contents = [];
      // foreach ($content_result as $key => $value) {
      //     array_push($contents, $value['content_id']);
      // }
      // foreach ($_REQUEST['content_pool'] as $key => $value) {


      //     if(!in_array($value['content']['result_id'], $contents)){

      //         $download_data['output_path'] = "C:\\xampp\htdocs\campus\\resources\uploads\blackboard\\".$data['id']."\\contents\\";
      //         $download_data['filename'] = $value['content']['result_id'];
      //         $download_data['table_id'] = $data['id'];
      //         $download_data['content_id'] = $value['content']['result_id'];
      //         $download_data['completed'] = 0;

      //         if($value['content']['type'] == "youtube"){
      //             $download_data['file_type'] = "video";
      //             $download_data['url'] = $this->youtube($value['content']['source']);
      //         }else{
      //             $download_data['file_type'] = urldecode($value['content']['type']);
      //             $download_data['url'] = urldecode($value['content']['source']);
      //         }

      //         if($value['content']['type'] == "website"){
      //             $download_data['status'] = "convert";

      //         }else{
      //             $download_data['status'] = "download";
      //         }


      //         if($download_data['url']!=""){
      //             $this->blackboard_model->create_new("resources_queue",$download_data);
      //         }

      //     }


      // }
      //contents
   }

   public function httpPost($url, $params)
   {
      $postData = '';
      //create name value pairs seperated by &
      foreach ($params as $k => $v) {
         $postData .= $k . '=' . $v . '&';
      }
      $postData = rtrim($postData, '&');

      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLOPT_POST, count($postData));
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

      $output = curl_exec($ch);

      curl_close($ch);
      return $output;
   }

   public function send_email_notification_godaddy($lesson_id = "", $email_notification = "", $student_ids = "")
   {
      if (!$lesson_id) {
         $student_ids = $_REQUEST['student_ids'];
         $lesson_id = $_REQUEST['lesson_id'];
         $email_notification = $_REQUEST['email_notification'];
      } else {
         $student_ids = explode(",", urldecode($student_ids));
      }

      echo "<pre>";


      $this->db->select("lms_lesson.lesson_name,lms_lesson.lesson_type,lms_lesson.start_date,staff.name,staff.surname");
      $this->db->from("lms_lesson");
      $this->db->join("staff", "lms_lesson.account_id = staff.id");
      $this->db->where("lms_lesson.id", $lesson_id);
      $lesson = $this->db->get()->result_array()[0];

      $this->db->select("students.id,students.firstname,students.lastname,users.username,users.password");
      $this->db->from("students");
      $this->db->join("users", "users.user_id = students.id");
      $this->db->where_in("students.id", $student_ids);
      $students = $this->db->get()->result_array();



      if ($email_notification == "true") {
         $all_emails = array();
         $sender_details['lesson_title'] = $lesson['lesson_name'];
         $sender_details['school_url_login_page'] = base_url('site/userlogin');
         $sender_details['school_code'] = $_SERVER['HTTP_HOST'];
         // $sender_details['start_date'] = $lesson['start_date'];
         $sender_details['start_date'] = date("F d, Y h:i A", strtotime($lesson['start_date']));
         $sender_details['lesson_type'] = ($lesson['lesson_type'] == "virtual") ? "Google Meet" : ucfirst($lesson['lesson_type']);
         $sender_details['teacher_name'] = $lesson['name'] . " " . $lesson['surname'];

         foreach ($students as $student_key => $student_value) {

            // $sender_details['email'] = $student_value['guardian_email'];

            $sender_details['sendees'][$student_key]['id'] = $student_value['id'];
            $sender_details['sendees'][$student_key]['student_name'] = $student_value['firstname'] . " " . $student_value['lastname'];
            $sender_details['sendees'][$student_key]['email'] = $student_value['guardian_email'];
            // $sender_details['sendees'][$student_key]['email'] = "cervezajoeven@gmail.com";
            $sender_details['sendees'][$student_key]['username'] = $student_value['username'];
            $sender_details['sendees'][$student_key]['password'] = $student_value['password'];
            // $this->mailsmsconf->mailsms('lesson_assigned', $sender_details);

         }

         $send_email['school'] = "school";
         $send_email['data'] = json_encode($sender_details);
         $url = "https://beta.campuscloudph.com/lms/lesson/email_api";
         // $url = "http://localhost/sms/lms/lesson/email_api";
         print_r($this->httpPost($url, $send_email));
         // echo "<center><h1>Success! You may exit this page now.</h1></center>";
      }
   }


   public function email_api()
   {
      $data = json_decode($_REQUEST['data']);
      $email_logs = array();
      foreach ($data->sendees as $sendees_key => $sendees_value) {

         $sender_details['lesson_title'] = $data->lesson_title;
         $sender_details['school_url_login_page'] = $data->school_url_login_page;
         $sender_details['school_code'] = $data->school_code;
         $sender_details['start_date'] = $data->start_date;
         $sender_details['lesson_type'] = $data->lesson_type;
         $sender_details['teacher_name'] = $data->teacher_name;
         $sender_details['id'] = $sendees_value->id;
         $sender_details['student_name'] = $sendees_value->student_name;
         $sender_details['email'] = $sendees_value->email;
         $sender_details['username'] = $sendees_value->username;
         $sender_details['password'] = $sendees_value->password;
         $email_logs[$sendees_key] = $this->mailsmsconf->mailsms('lesson_assigned', $sender_details);
      }
      $this->writedb->insert_batch("messages", $email_logs);
   }

   public function general_mail_api()
   {
      $data = json_decode($_REQUEST['data']);


      foreach ($data as $data_key => $data_value) {

         $sender_details['id'] = $data_value->id;
         $sender_details['email'] = $data_value->email;
         $sender_details['display_name'] = $data_value->display_name;
         $sender_details['username'] = $data_value->username;
         $sender_details['password'] = $data_value->password;
         $sender_details['url'] = $data_value->url;
         $this->mailsmsconf->mailsms('old_student_account', $sender_details);
      }
   }

   public function send_email_notification($lesson_id = "", $email_notification = "", $student_ids = "")
   {

      if (!$lesson_id) {
         $student_ids = $_REQUEST['student_ids'];
         $lesson_id = $_REQUEST['lesson_id'];
         $email_notification = $_REQUEST['email_notification'];
      } else {
         $student_ids = explode(",", urldecode($student_ids));
      }

      echo "<pre>";

      $this->db->select("lms_lesson.lesson_name,lms_lesson.lesson_type,lms_lesson.start_date,staff.name,staff.surname");
      $this->db->from("lms_lesson");
      $this->db->join("staff", "lms_lesson.account_id = staff.id");
      $this->db->where("lms_lesson.id", $lesson_id);
      $lesson = $this->db->get()->result_array()[0];

      $this->db->select("students.id,students.firstname,students.lastname,users.username,users.password,students.guardian_email");
      $this->db->from("students");
      $this->db->join("users", "users.user_id = students.id");
      $this->db->where_in("students.id", $student_ids);
      $students = $this->db->get()->result_array();

      if ($email_notification == "true") {
         foreach ($students as $student_key => $student_value) {

            $sender_details['id'] = $student_value['id'];
            // $sender_details['email'] = $student_value['guardian_email'];
            $sender_details['email'] = 'cervezajoeven@gmail.com';
            $sender_details['student_name'] = $student_value['firstname'] . " " . $student_value['lastname'];
            $sender_details['lesson_title'] = $lesson['lesson_name'];
            // $sender_details['start_date'] = date("F d, Y h:i A",strtotime($lesson['start_date']));
            $sender_details['start_date'] = date("F d, Y h:i A", strtotime($lesson['start_date']));
            $sender_details['lesson_type'] = ($lesson['lesson_type'] == "virtual") ? "Google Meet" : ucfirst($lesson['lesson_type']);
            $sender_details['teacher_name'] = $lesson['name'] . " " . $lesson['surname'];
            $sender_details['school_url_login_page'] = base_url('site/userlogin');
            $sender_details['username'] = $student_value['username'];
            $sender_details['password'] = $student_value['password'];
            print_r($sender_details);
            $this->mailsmsconf->mailsms('lesson_assigned', $sender_details);
         }
      }
   }


   public function new_send_email_notification($lesson_id = "", $email_notification = "", $student_ids = "")
   {

      if (!$lesson_id) {
         $student_ids = $_REQUEST['student_ids'];
         $lesson_id = $_REQUEST['lesson_id'];
         $email_notification = $_REQUEST['email_notification'];
      } else {
         $student_ids = explode(",", urldecode($student_ids));
      }

      $this->db->select("lms_lesson.lesson_name,lms_lesson.lesson_type,lms_lesson.start_date,staff.name,staff.surname");
      $this->db->from("lms_lesson");
      $this->db->join("staff", "lms_lesson.account_id = staff.id");
      $this->db->where("lms_lesson.id", $lesson_id);
      $lesson = $this->db->get()->result_array()[0];

      $this->db->select("students.id,students.firstname,students.lastname,users.username,users.password,students.guardian_email");
      $this->db->from("students");
      $this->db->join("users", "users.user_id = students.id");
      $this->db->where_in("students.id", $student_ids);
      $this->db->where("users.role", "student");
      $this->db->group_by("users.username");
      $students = $this->db->get()->result_array();
      // print_r($students);
      if ($email_notification == "true") {
         foreach ($students as $student_key => $student_value) {

            $sender_details['id'] = $student_value['id'];
            $sender_details['email'] = $student_value['guardian_email'];
            // $sender_details['email'] = 'cervezajoeven@gmail.com';
            $sender_details['student_name'] = $student_value['firstname'] . " " . $student_value['lastname'];
            $sender_details['lesson_title'] = $lesson['lesson_name'];
            // $sender_details['start_date'] = date("F d, Y h:i A",strtotime($lesson['start_date']));
            $sender_details['start_date'] = date("F d, Y h:i A", strtotime($lesson['start_date']));
            $sender_details['lesson_type'] = ($lesson['lesson_type'] == "virtual") ? "Google Meet" : ucfirst($lesson['lesson_type']);
            $sender_details['teacher_name'] = $lesson['name'] . " " . $lesson['surname'];
            $sender_details['school_url_login_page'] = base_url('site/userlogin');
            $sender_details['username'] = $student_value['username'];
            $sender_details['password'] = $student_value['password'];


            $msg = "<p>Good Day Parent,</p>

                        <p><strong>" . $sender_details['student_name'] . " </strong>was assigned to a lesson on the LMS.</p>

                        <p>Title: <strong>" . $sender_details['lesson_title'] . "</strong></p>

                        <p>Date: <strong>" . $sender_details['start_date'] . "</strong></p>

                        <p>Teacher: <strong>" . $sender_details['teacher_name'] . "</strong></p>

                        <p>Type: <strong>" . $sender_details['lesson_type'] . "</strong></p>

                        <p>Click here to access the system <strong>" . $sender_details['school_url_login_page'] . "</strong></p>

                        <p>In case you can&rsquo;t login your account here is the student&rsquo;s login credential.</p>

                        <p>Username: <strong>" . $sender_details['username'] . "</strong></p>

                        <p>Password: <strong>" . $sender_details['password'] . "</strong></p>

                        <p><strong>Please be on time and take care.&nbsp;</strong></p>";

            $lesson_email_log['lesson_id'] = $lesson_id;
            $lesson_email_log['student_id'] = $sender_details['id'];
            $lesson_email_log['receiver'] = $sender_details['email'];
            $lesson_email_log['username_sent'] = $sender_details['username'];
            $lesson_email_log['password_sent'] = $sender_details['password'];

            if ($this->mailer->send_mail($sender_details['email'], "Lesson Notification", $msg)) {

               $lesson_email_log['email_status'] = "Sent";
            } else {

               $lesson_email_log['email_status'] = "Not Sent";
            }
            $this->lesson_model->lms_create("lms_lesson_email_logs", $lesson_email_log);
         }
      }
   }

   public function delete($id)
   {

      $data['id'] = $id;
      $data['deleted'] = 1;
      $this->lesson_model->lms_update("lms_lesson", $data);

      $data['result'] = 'success';
      $data['message'] = 'Delete successful!';
      echo json_encode($data);
      // redirect(site_url() . "lms/lesson/index/");
   }

   public function retrieve($id)
   {

      $data['id'] = $id;
      $data['deleted'] = 0;
      $this->lesson_model->lms_update("lms_lesson", $data);
      echo "<script>alert();</script>";
      redirect(site_url() . "lms/lesson/index/");
   }

   public function get($id)
   {
      echo json_encode($this->lesson_model->lms_get("lms_lesson", $id, "id")[0]);
   }

   public function my_resources($search = "")
   {
      $account_id = $this->session->userdata('admin')['id'];
      $search_result = $this->lesson_model->search_my_resources($account_id, $search);
      echo json_encode($search_result);
   }

   public function my_resources_delete($id)
   {
      $data['id'] = $id;
      $data['deleted'] = 1;

      if ($this->lesson_model->lms_delete("lms_my_resources", $data)) {
         // redirect(site_url("lms/assessment/index"));
         $data['result'] = 'success';
         $data['message'] = 'Delete successful!';
      } else {
         $data['result'] = 'error';
         $data['message'] = 'Delete failed!';
      }

      echo json_encode($data);
   }

   public function cms_resources($search = "")
   {
      $account_id = $this->session->userdata('admin')['id'];
      $search_result = $this->lesson_model->search_cms_resources($account_id, $search);
      echo json_encode($search_result);
   }

   public function send_email_parent()
   {
      $this->db->select("*");
      $query = $this->db->get("students");
      $result = $query->result_array();
      echo "<pre>";
      $current_session = $this->setting_model->getCurrentSession();
      print_r("current session: " . $current_session);

      foreach ($result as $key => $value) {
         //for parent notification
         // $sender_details = array('student_id' => $value['id'], 'email' => 'cervezajoeven@gmail.com');
         $sender_details = array('id' => $value['id'], 'email' => $value['email']);

         print_r($sender_details);
         $this->mailsmsconf->mailsms('parent_notification', $sender_details);
      }
   }


   public function send_admission_details($id)
   {

      $student = $this->lesson_model->lms_get("students", $id, "id")[0];
      $sender_details = array('student_id' => $student['id'], 'email' => $student['guardian_email']);
      $this->mailsmsconf->mailsms('student_admission', $sender_details);
   }

   public function send_login_credential($id)
   {

      $student = $this->lesson_model->lms_get("students", $id, "id")[0];
      $sender_details = array('id' => $student['id'], 'email' => $student['guardian_email'], "credential_for" => "student", "resend" => true);

      $this->mailsmsconf->mailsms('login_credential', $sender_details);
   }

   public function upload($upload_type = "my_resources", $lesson_id = "")
   {
      if ($upload_type == "my_resources") {
         $file = $_FILES['upload_file'];

         foreach ($file['name'] as $key => $value) {

            $data['name'] = $value;

            $path_parts = pathinfo($file["name"][$key]);
            $extension = $path_parts['extension'];
            $image_array = array("png", "jpg", "jpeg", "svg", "gif");
            $video_array = array("mp4");
            if (in_array($extension, $image_array)) {
               $data['type'] = "image";
            } elseif (in_array($extension, $video_array)) {
               $data['type'] = "video";
            } else {
               $data['type'] = $extension;
            }


            $data['description'] = "";
            $data['text_value'] = "";
            $data['lms_lesson_ids'] = $lesson_id;
            $data['shared'] =  0;

            if ($upload_type == "my_resources") {
               $data['account_id'] = $this->session->userdata('admin')['id'];

               // if (!is_dir(FCPATH . "uploads/lms_my_resources/" . $data['account_id'])) {
               //    if (mkdir(FCPATH . "uploads/lms_my_resources/" . $data['account_id'])) {
               //    }
               // }

               $filename = $this->lesson_model->filename_generator() . "." . $extension;
               // echo "uploaded file: ";
               // var_dump(move_uploaded_file($file['tmp_name'][$key], FCPATH."uploads/lms_my_resources/".$data['account_id']."/".$filename));
               // var_dump($file['tmp_name'][$key], FCPATH . "uploads/lms_my_resources/" . $data['account_id'] . "/" . $filename);

               $this->load->library('s3');
               $s3 = new S3(AWS_ACCESS_KEY_ID, AWS_ACCESS_KEY_SECRET, false, S3_URI, AWS_REGION);
               $dest_file = $_SESSION['School_Code'] . "/uploads/lms_my_resources/" . $data['account_id'] . "/" . $filename;

               //if (move_uploaded_file($file['tmp_name'][$key], FCPATH . "uploads/lms_my_resources/" . $data['account_id'] . "/" . $filename)) 
               if ($s3->putObjectFile($file['tmp_name'][$key], S3_BUCKET, $dest_file, S3::ACL_PUBLIC_READ)) {
                  $data['filename'] = $filename;
                  $data['link'] =  $data['account_id'] . "/" . $filename;

                  $id = $this->lesson_model->lms_create("lms_my_resources", $data);
               }
            }
         }
      } elseif ($upload_type == "add_text") {
         $data['name'] = $_REQUEST['title'];
         $data['type'] = "text";
         $data['description'] = "";
         $data['text_value'] = $_REQUEST['text_value'];
         $data['account_id'] = $this->session->userdata('admin')['id'];
         $data['lms_lesson_ids'] = $lesson_id;
         $data['shared'] =  0;
         $id = $this->lesson_model->lms_create("lms_my_resources", $data);
         print_r($id);
      } elseif ($upload_type == "vimeo") {

         $data['name'] = $_REQUEST['name'];
         $data['type'] = $_REQUEST['type'];

         if (strpos($_REQUEST['link'], 'vimeo.com') !== false) {
            $data['link'] = "https://player.vimeo.com/video/" . explode("/", $_REQUEST['link'])[3];
            $data['type'] = "youtube";
         } else if (strpos($_REQUEST['link'], 'youtube.com') !== false) {

            $data['link'] = "https://www.youtube.com/embed/" . explode("?v=", $_REQUEST['link'])[1];
            $data['type'] = "youtube";
            $data['image'] = "https://i.ytimg.com/vi/" . explode("?v=", $_REQUEST['link'])[1] . "/maxresdefault.jpg";
         } else {

            $data['link'] = $_REQUEST['link'];
            $data['type'] = "website";
         }

         // $data['link'] = $_REQUEST['link'];
         $data['description'] = $_REQUEST['description'];
         $data['account_id'] = $this->session->userdata('admin')['id'];
         $data['lms_lesson_ids'] = $lesson_id;
         $data['shared'] =  0;
         $id = $this->lesson_model->lms_create("lms_my_resources", $data);
         print_r($data['link']);
      }
   }

   public function send_chat()
   {

      $data['account_id'] = $this->general_model->get_account_id();
      $data['account_type'] = $this->general_model->get_role();
      $data['content'] = $_REQUEST['content'];
      $data['lesson_id'] = $_REQUEST['lesson_id'];

      $this->lesson_model->lms_create("lms_discussion", $data);
   }

   public function fetch_chat()
   {

      $lesson_id = $_REQUEST['lesson_id'];
      $discussion = $this->discussion_model->lesson_discussion($lesson_id);

      $new_discussion = [];

      foreach ($discussion as $key => $value) {
         $profile = $this->general_model->get_account_name($value['account_id'], $value['account_type'])[0];
         if ($value['account_type'] == "student") {
            $discussion[$key]['firstname'] = $profile['firstname'];
            $discussion[$key]['lastname'] = $profile['lastname'];
         } else {
            $discussion[$key]['firstname'] = $profile['name'];
            $discussion[$key]['lastname'] = $profile['surname'];
         }

         // print_r($discussion);
      }

      echo json_encode($discussion);
   }

   public function add_lms_lesson($lesson_data = array())
   {

      $api_type = 'global';
      $params = array(
         'zoom_api_key' => $lesson_data['zoom_api_key'],
         'zoom_api_secret' => $lesson_data['zoom_api_secret'],
      );

      $this->load->library('zoom_api', $params);

      $account_data = $this->lesson_model->lms_get("staff", $lesson_data['account_id'], "id")[0];
      $title = $this->lesson_model->lms_get("lms_lesson", $lesson_data['lesson_id'], "id")[0]['lesson_name'];
      $zoom_email = $lesson_data['zoom_email'];

      $insert_array = array(
         'staff_id' => $lesson_data['account_id'],
         'title' => "(" . $lesson_data['name'] . " " . $lesson_data['surname'] . ") - " . $title,
         'date' => $lesson_data['start_date'],
         'zoom_email' => trim($zoom_email),
         'class_id' => 3,
         'section_id' => 63,
         'duration' => 60,
         'password' => "cloudph", //"Campuscloudph123",
         'created_id' => $lesson_data['account_id'],
         'api_type' => $api_type,
         'host_video' => "1",
         'client_video' => "1",
         'description' => "Zoom Class",
         'timezone' => $this->customlib->getTimeZone(),
      );

      // print_r($params);
      // die();

      // print_r($insert_array);
      // die();

      $response = $this->zoom_api->createAMeeting($insert_array);


      // echo "response";
      // print_r($response);
      if ($response) {
         if (isset($response->id)) {
            $insert_array['return_response'] = json_encode($response);
            $zoom_id = $this->conference_model->add($insert_array);

            $sender_details = array('class_id' => $this->input->post('class_id'), 'section_id' => $this->input->post('section_id'), 'title' => $this->input->post('title'), 'date' => $this->input->post('date'), 'duration' => $this->input->post('duration'));
            $this->mailsmsconf->mailsms('online_classes', $sender_details);

            $response = array('status' => 1, 'message' => $this->lang->line('success_message'));
         } else {
            $response = array('status' => 0, 'error' => array($response->message));
         }
      } else {
         $response = array('status' => 0, 'error' => array('Something went wrong.'));
      }

      return $zoom_id;
   }

   public function check_zoom_schedule()
   {

      // $start_date = $_POST['start_date'];
      // $end_date = $_POST['end_date'];
      // $lesson_id = $_POST['lesson_id'];
      // $account_id = $_POST['account_id'];

      $start_date = $_REQUEST['start_date'];
      $end_date = $_REQUEST['end_date'];
      $lesson_id = $_REQUEST['lesson_id'];
      $account_id = $_REQUEST['account_id'];

      $converted_start_date = date("Y-m-d", strtotime($start_date));
      $converted_end_date = date("Y-m-d", strtotime($end_date));

      $this->db->select("id,lesson_name,lesson_type,start_date,end_date,zoom_account_id");
      $this->db->from("lms_lesson");
      $this->db->or_where("start_date LIKE '%" . $converted_start_date . "%'");
      $this->db->or_where("end_date LIKE '%" . $converted_end_date . "%'");
      $this->db->where("id !=", $lesson_id);
      $this->db->where("lesson_type", "zoom");

      // echo "<pre>";
      // print_r($start_date);
      // echo "\n";
      // print_r($end_date);
      // echo "\n";
      // print_r($lesson_id);
      // echo "\n";

      $lesson_schedules = $this->db->get()->result_array();
      // echo "<pre>";
      // print_r($lesson_schedules);
      // exit;
      $conflict_zoom_ids = array();

      foreach ($lesson_schedules as $lesson_schedules_key => $lesson_schedules_value) {

         if ($lesson_id != $lesson_schedules_value['id']) {
            if ($lesson_schedules_value['lesson_type'] == "zoom") {
               $startTime = strtotime($start_date);
               $endTime   = strtotime($end_date);
               // print_r($startTime);
               // echo "\n";
               // print_r($endTime);
               // echo "\n";
               // print_r($lesson_id);
               // echo "\n";
               $chkStartTime = strtotime($lesson_schedules_value['start_date']);
               $chkEndTime   = strtotime($lesson_schedules_value['end_date']);

               if ($chkStartTime > $startTime && $chkEndTime < $endTime) {   #-> Check time is in between start and end time
                  // echo "1 Time is in between start and end time";
                  // print_r($lesson_schedules_value);
                  // echo "<br>";
                  if (!in_array($lesson_schedules_value['zoom_account_id'], $conflict_zoom_ids)) {
                     array_push($conflict_zoom_ids, $lesson_schedules_value['zoom_account_id']);
                  }
               } elseif (($chkStartTime > $startTime && $chkStartTime < $endTime) || ($chkEndTime > $startTime && $chkEndTime < $endTime)) {   #-> Check start or end time is in between start and end time
                  // echo "2 ChK start or end Time is in between start and end time";
                  // print_r($lesson_schedules_value);
                  // echo "<br>";
                  if (!in_array($lesson_schedules_value['zoom_account_id'], $conflict_zoom_ids)) {
                     array_push($conflict_zoom_ids, $lesson_schedules_value['zoom_account_id']);
                  }
               } elseif ($chkStartTime == $startTime || $chkEndTime == $endTime) {   #-> Check start or end time is at the border of start and end time
                  // echo "3 ChK start or end Time is at the border of start and end time";
                  // print_r($lesson_schedules_value);
                  // echo "<br>";
                  if (!in_array($lesson_schedules_value['zoom_account_id'], $conflict_zoom_ids)) {
                     array_push($conflict_zoom_ids, $lesson_schedules_value['zoom_account_id']);
                  }
               } elseif ($startTime < $chkStartTime && $endTime > $chkEndTime) {   #-> start and end time is in between  the check start and end time.
                  // echo "4 start and end Time is overlapping  chk start and end time";
                  // print_r($lesson_schedules_value);
                  // echo "<br>";
                  if (!in_array($lesson_schedules_value['zoom_account_id'], $conflict_zoom_ids)) {
                     array_push($conflict_zoom_ids, $lesson_schedules_value['zoom_account_id']);
                  }
               }
            }
         }
      }
      $this->db->select("id,email,api_key,api_secret");
      $this->db->from("lms_zoom_accounts");
      $this->db->where("owner", "school");
      if (!empty($conflict_zoom_ids)) {
         $this->db->where_not_in("id", $conflict_zoom_ids);
      }

      $lms_zoom_accounts = $this->db->get()->result_array();

      if (empty($lms_zoom_accounts)) {
         $json_encode = array("status" => "full", "message" => "There is no available zoom account for this schedule.");
         echo json_encode($json_encode);
      } else {


         $lesson_data['zoom_api_key'] = $lms_zoom_accounts[0]['api_key'];
         $lesson_data['zoom_api_secret'] = $lms_zoom_accounts[0]['api_secret'];
         $lesson_data['account_id'] = $account_id;
         $lesson_data['lesson_id'] = $lesson_id;
         $lesson_data['start_date'] = $start_date;
         $lesson_data['zoom_email'] = $lms_zoom_accounts[0]['email'];
         // print_r($lms_zoom_accounts);
         $this->add_lms_lesson($lesson_data);
         $lesson_update['id'] = $lesson_id;
         $lesson_update['zoom_account_id'] = $lms_zoom_accounts[0]['id'];
         $lesson_update['zoom_id'] = $this->add_lms_lesson($lesson_data);
         $this->lesson_model->lms_update("lms_lesson", $lesson_update);

         $json_encode = array("status" => "success", "message" => "Successful! You are assigned to " . $lms_zoom_accounts[0]['email']);
         $json_encode['zoom_email'] = $lms_zoom_accounts[0]['email'];
         $json_encode['start_url'] = json_decode($this->lesson_model->lms_get("conferences", $lesson_update['zoom_id'], "id")[0]['return_response'])->start_url;
         echo json_encode($json_encode);
      }
   }

   public function get_zoom_status($data = array())
   {
      $data['api_key'] = '0NP_jYnjS5WXxW5NRTZc0g';
      $data['api_secret'] = 'BsryxBYn3QYBcJM8tYw987P3aIzPKshcpJPI';
      $params = array(
         'zoom_api_key' => $data['api_key'],
         'zoom_api_secret' => $data['api_secret'],
      );
      $this->load->library('zoom_api', $params);

      print_r($this->zoom_api->checkStatus()->participants);
   }

   public function update_lesson_status()
   {

      $data['id'] = $_REQUEST['id'];
      $data['lesson_status'] = $_REQUEST['lesson_status'];


      print_r($this->lesson_model->lms_update("lms_lesson", $data));
   }

   public function zoom_checker($lesson_id = "")
   {
      // echo "<pre>";

      $data['api_key'] = '0NP_jYnjS5WXxW5NRTZc0g';
      $data['api_secret'] = 'BsryxBYn3QYBcJM8tYw987P3aIzPKshcpJPI';
      // $data['api_key'] = 'aIiAONgbR6SG_A1rC4Q2zw';
      // $data['api_secret'] = 'qsrKyRSAC2l9z9vPHZsUriybMn4NuPg1P06N';

      $params = array(
         'zoom_api_key' => $data['api_key'],
         'zoom_api_secret' => $data['api_secret'],
      );

      $this->load->library('zoom_api', $params);

      // print_r($data);
      // die();

      $data['lesson_id'] = $lesson_id;

      $this->db->select("*");
      $this->db->order_by("sort_order");
      $data['zoom_accounts'] = $this->db->get("lms_zoom_accounts")->result_array();
      $data['account_id'] = $this->general_model->get_account_id();
      $data['real_role'] = $this->general_model->get_real_role();

      // print_r($data);
      // die();

      foreach ($data['zoom_accounts'] as $key => $value) {
         if ($value['conference_id']) {
            $conference = $this->lesson_model->lms_get("conferences", $value['conference_id'], "id", "return_response")[0];
            $lesson = $this->lesson_model->lms_get("lms_lesson", $value['lesson_id'], "id", "lesson_name")[0];
            $staff = $this->lesson_model->lms_get("staff", $value['account_id'], "id", "CONCAT(name,' ',surname) as teacher_name")[0];
            $data['zoom_accounts'][$key]['join_url'] = json_decode($conference['return_response'])->join_url;
            $data['zoom_accounts'][$key]['lesson_name'] = $lesson['lesson_name'];
            $data['zoom_accounts'][$key]['teacher_name'] = $staff['teacher_name'];
         } else {
            $data['zoom_accounts'][$key]['join_url'] = "";
            $data['zoom_accounts'][$key]['lesson_name'] = "";
            $data['zoom_accounts'][$key]['teacher_name'] = "";
         }
      }
      $this->load->view('lms/lesson/zoom_checker', $data);
   }

   public function start_zoom($lesson_id, $zoom_id)
   {

      $the_lesson = $this->lesson_model->lms_get("lms_lesson", $lesson_id, "id")[0];
      $teacher_name = $this->lesson_model->lms_get("staff", $the_lesson['account_id'], "id", "name,surname")[0];
      $zoom_data = $this->lesson_model->lms_get("lms_zoom_accounts", $zoom_id, "email")[0];
      $teacher_account_id = $this->general_model->get_account_id();

      // $data['api_key'] = '0NP_jYnjS5WXxW5NRTZc0g';
      // $data['api_secret'] = 'BsryxBYn3QYBcJM8tYw987P3aIzPKshcpJPI';

      $data['api_key'] = $zoom_data['api_key'];
      $data['api_secret'] = $zoom_data['api_secret'];

      $params = array(
         'zoom_api_key' => $data['api_key'],
         'zoom_api_secret' => $data['api_secret'],
      );
      $this->load->library('zoom_api', $params);

      // print_r($data);
      // die();


      if ($zoom_data['account_id']) {
         // print_r($data);
         // die();

         if ($zoom_data['account_id'] == $teacher_account_id) {
            $conference = $this->lesson_model->lms_get("conferences", $zoom_data['conference_id'], "id")[0];
            $start_url = json_decode($conference['return_response'])->start_url;
            redirect($start_url);
         } else {

            echo "<h1>You can close this window.</h1>";
         }
      } else {
         $lesson_data['zoom_api_key'] = $data['api_key'];
         $lesson_data['zoom_api_secret'] = $data['api_secret'];

         $lesson_data['account_id'] = $the_lesson['account_id'];


         $lesson_data['lesson_id'] = $lesson_id;
         $lesson_data['start_date'] = date("Y-m-d H:i:s");
         $lesson_data['zoom_email'] = $zoom_id;
         $lesson_data['name'] = $teacher_name['name'];
         $lesson_data['surname'] = $teacher_name['surname'];

         // print_r($lesson_data);
         // die();

         $conference_id = $this->add_lms_lesson($lesson_data);



         $conference = $this->lesson_model->lms_get("conferences", $conference_id, "id")[0];
         $start_url = json_decode($conference['return_response'])->start_url;

         $lms_zoom_accounts['email'] = $zoom_id;
         $lms_zoom_accounts['account_id'] = $teacher_account_id;
         $lms_zoom_accounts['conference_id'] = $conference_id;
         $lms_zoom_accounts['lesson_id'] = $lesson_id;

         $this->lesson_model->lms_update("lms_zoom_accounts", $lms_zoom_accounts, "email");

         $lesson_update['id'] = $lesson_id;
         $lesson_update['zoom_id'] = $conference_id;
         $this->lesson_model->lms_update("lms_lesson", $lesson_update);
         redirect($start_url);
         // redirect(base_url('lms/lesson/zoom_checker/'.$lesson_id));
      }
      // $live_zoom_accounts = array();
      // $live_zoom = $this->zoom_api->check_live();

      // foreach ($live_zoom->meetings as $live_zoom_key => $live_zoom_value) {
      //     array_push($live_zoom_accounts, $live_zoom_value->email);
      // }
      // if(!in_array($zoom_id, $live_zoom_accounts)){

      //     redirect(json_decode($conference['return_response'])->start_url);
      // }else{
      //     echo "<script>alert('This zoom is already being used by other teachers. Please refresh the Zoom checker and pick another one.')</script>";
      // }


   }

   public function end_zoom($zoom_id)
   {
      $zoom_data = $this->lesson_model->lms_get("lms_zoom_accounts", $zoom_id, "email")[0];
      $lms_zoom_accounts['email'] = $zoom_id;
      $lms_zoom_accounts['account_id'] = "";
      $lms_zoom_accounts['conference_id'] = "";
      $lms_zoom_accounts['lesson_id'] = "";

      $this->lesson_model->lms_update("lms_zoom_accounts", $lms_zoom_accounts, "email");

      redirect(base_url('lms/lesson/zoom_checker/' . $zoom_id));
   }

   public function check_class($lesson_id, $user_id)
   {
      $the_lesson = $this->lesson_model->lms_get("lms_lesson", $lesson_id, "id")[0];
      // $lms2_link = base_url('lms_v2/index.php?/lms/lesson/initialize/'.$user_id.'/student/'.$lesson_id);
      $lms2_link = base_url('lms/lesson/create/' . $lesson_id);

      if ($the_lesson['lesson_type'] == "zoom" || $the_lesson['lesson_type'] == "virtual") {
         $start_date = strtotime($the_lesson['start_date']);
         $end_date = strtotime($the_lesson['end_date']);
         $current_date = strtotime(date("Y-m-d H:i:s"));
         $open['lesson_type'] = "zoom_google";

         if ($current_date > $end_date) {
            $open['video'] = "";
            $open['lms'] = $lms2_link;
         } else {
            if ($the_lesson['lesson_type'] == "virtual") {
               $teacher = $this->lesson_model->lms_get("staff", $the_lesson['account_id'], "id", "name,google_meet")[0];

               if ($the_lesson['allow_view'] == "1") {

                  $open['video'] = $teacher['google_meet'];
                  $open['lms'] = $lms2_link;
               } else {;
                  $open['video'] = $teacher['google_meet'];
                  $open['lms'] = "";
               }
            }

            if ($the_lesson['lesson_type'] == "zoom") {
               if ($the_lesson['zoom_id']) {
                  $conference = json_decode($this->lesson_model->lms_get("conferences", $the_lesson['zoom_id'], "id", "return_response")[0]['return_response'])->join_url;
                  if ($the_lesson['allow_view'] == "1") {
                     $open['video'] = $conference;
                     $open['lms'] = $lms2_link;
                  } else {

                     $open['video'] = $conference;
                     $open['lms'] = "";
                  }
               } else {
                  if ($the_lesson['allow_view'] == "1") {
                     $open['video'] = "";
                     $open['lms'] = $lms2_link;
                  } else {

                     $open['video'] = "";
                     $open['lms'] = "";
                  }
               }

               // print_r($the_lesson['zoom_id']);
            }
         }
      } else {
         $open['lesson_type'] = "others";
         $open['video'] = "";
         $open['lms'] = base_url('lms/lesson/create/' . $lesson_id);
      }

      $lesson_log_data['lesson_id'] = $lesson_id;
      $lesson_log_data['account_id'] = $this->general_model->get_account_id();
      $lesson_log_data['session_id'] = $this->setting_model->getCurrentSession();
      // $this->lesson_model->lms_create("lms_lesson_logs", $lesson_log_data);

      echo json_encode($open);
   }

   public function lesson_attendance($lesson_id = "", $date = "")
   {
      $this->session->set_userdata('top_menu', 'Download Center');
      $this->session->set_userdata('sub_menu', 'lesson/attendance');
      date_default_timezone_set('Asia/Manila');
      $data['title'] = 'Lesson Attendance';

      $data['role'] = $this->general_model->get_role();
      $data['real_role'] = $this->general_model->get_real_role();
      $data['classes'] = $this->general_model->get_classes();
      $data['subjects'] = $this->general_model->get_subjects();
      $data['heading'] = "Current Lessons";
      $data['lesson_sched'] = "today";

      if ($data['role'] == 'admin') {
         $this->load->view('layout/header');

         if ($data['real_role'] == "7" || $data['real_role'] == "1") {
            $data['list'] = $this->lesson_model->admin_deleted($this->general_model->get_account_id(), "today");

            foreach ($data['list'] as $key => $value) {
               if ($value['zoom_id']) {
                  $zoom_data = $this->lesson_model->lms_get("conferences", $value['zoom_id'], "id")[0];
                  $data['list'][$key]['student_zoom_link'] = json_decode($zoom_data['return_response'])->join_url;
               }

               $teacher_info = $this->lesson_model->lms_get("staff", $value['account_id'], "id")[0];
               $data['list'][$key]['teacher_name'] = $teacher_info['name'];
               $data['list'][$key]['google_meet'] = $teacher_info['google_meet'];
            }
         } else {
            $data['list'] = $this->lesson_model->get_lessons($this->general_model->get_account_id(), "today");
         }
      }

      $this->load->view('lms/lesson/lesson_attendance', $data);
      $this->load->view('layout/footer');
   }

   public function publish_lesson($lesson_id)
   {
      $data['id'] = $lesson_id;
      $data['published'] = 1;
      $this->lesson_model->lms_update("lms_lesson", $data);

      // redirect(site_url() . "lms/lesson/index/");
      $data['result'] = 'Publish successful!';
      echo json_encode($data);
   }

   public function unpublish_lesson($lesson_id)
   {
      $data['id'] = $lesson_id;
      $data['published'] = 0;
      $this->lesson_model->lms_update("lms_lesson", $data);

      // redirect(site_url() . "lms/lesson/index/");
      $data['result'] = 'Unpublish successful!';
      echo json_encode($data);
   }

   public function get_lesssons_by_level_subject($account_id, $level_id, $subject_id)
   {
      // $level_id = $_REQUEST['level_id'];
      // $subject_id = $_REQUEST['subject_id'];

      $real_role = $this->general_model->get_real_role();
      $account_id = "";

      if ($real_role != 1 && $real_role != 7)
         $account_id = $this->general_model->get_account_id();

      // print_r($real_role . "==" . $level_id . " == " . $subject_id);
      // die();

      $data = $this->lesson_model->get_lessons_by_level_subject($account_id, $level_id, $subject_id);
      echo json_encode($data);
   }

   public function get_lesson_logs($lesson_id, $levelid, $sectionid)
   {
      $result = array('data' => array());
      $data = $this->lesson_model->get_lesson_logs($lesson_id, $levelid, $sectionid);

      $radio = "";

      foreach ($data as $key => $value) {
         $radio = '<input type="text" id="" value="' . $value['jointime'] . '" disabled style="width: 100%">';

         $result['data'][$key] = array(
            $radio
         );
      } // /foreach

      echo json_encode($result);
   }

   public function enter_video_con($lesson_id)
   {
      $lesson_log_data['lesson_id'] = $lesson_id;
      $lesson_log_data['account_id'] = $this->general_model->get_account_id();
      $lesson_log_data['session_id'] = $this->setting_model->getCurrentSession();
      $this->lesson_model->lms_create("lms_lesson_logs", $lesson_log_data);

      // // echo '<script>alert("' . $vid_con_link . '")</script>';
      // // echo '<script>window.open("' . $vid_con_link . '","_blank")</script>';
      // // echo "<script type=\"text/javascript\">window.open('" . $vid_con_link . "', '_blank')</script>";
      $data['result'] = 'Logging successful!';
      echo json_encode($data);
   }
}

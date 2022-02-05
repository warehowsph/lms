<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Assessment extends General_Controller
{
   public $current_function;
   function __construct()
   {

      parent::__construct();
      $this->load->model('assessment_model');
      $this->load->model('general_model');
      $this->load->model('class_model');
      $this->load->model('lesson_model');
      $this->load->model('notification_model');
      $this->load->library('customlib');
      $this->load->library('mailsmsconf');

      $this->session->set_userdata('top_menu', 'Download Center');
      $this->session->set_userdata('sub_menu', 'lms/assessment');
      date_default_timezone_set('Asia/Manila');

      $this->writedb = $this->load->database('write_db', TRUE);
   }

   public function index()
   {
      $this->session->set_userdata('top_menu', 'Download Center');
      $this->session->set_userdata('sub_menu', 'Assessment');
      $this->session->set_userdata('subsub_menu', 'lms/assessment');
      $data['user_id'] = $this->general_model->get_account_id();
      $data['role'] = $this->general_model->get_role();
      $data['real_role'] = $this->general_model->get_real_role();

      if ($data['role'] == 'admin') {
         $this->load->view('layout/header');
         if ($data['real_role'] == 7 || $data['real_role'] == 1) {
            $data['list'] = $this->assessment_model->admin_all_assessment($this->general_model->get_account_id(), "current");
         } else {
            $data['list'] = $this->assessment_model->all_assessment($this->general_model->get_account_id(), "current");
         }
      } else {
         $data['list'] = $this->assessment_model->assigned_assessment($this->general_model->get_account_id());
         $this->load->view('layout/student/header');
      }

      $this->load->view('lms/assessment/index', $data);
      $this->load->view('layout/footer');
   }

   public function upcoming()
   {
      $this->session->set_userdata('top_menu', 'Download Center');
      $this->session->set_userdata('sub_menu', 'Assessment');
      $this->session->set_userdata('subsub_menu', 'lms/assessment/upcoming');
      $data['user_id'] = $this->general_model->get_account_id();
      $data['role'] = $this->general_model->get_role();
      $data['real_role'] = $this->general_model->get_real_role();

      if ($data['role'] == 'admin') {
         $this->load->view('layout/header');
         if ($data['real_role'] == 7 || $data['real_role'] == 1) {
            $data['list'] = $this->assessment_model->admin_all_assessment($this->general_model->get_account_id(), "upcoming");
         } else {
            $data['list'] = $this->assessment_model->all_assessment($this->general_model->get_account_id(), "upcoming");
         }
      } else {
         $data['list'] = $this->assessment_model->assigned_assessment($this->general_model->get_account_id());
         $this->load->view('layout/student/header');
      }

      $this->load->view('lms/assessment/upcoming', $data);
      $this->load->view('layout/footer');
   }

   public function past()
   {
      $this->session->set_userdata('top_menu', 'Download Center');
      $this->session->set_userdata('sub_menu', 'Assessment');
      $this->session->set_userdata('subsub_menu', 'lms/assessment/past');
      $data['user_id'] = $this->general_model->get_account_id();
      $data['role'] = $this->general_model->get_role();
      $data['real_role'] = $this->general_model->get_real_role();

      if ($data['role'] == 'admin') {
         $this->load->view('layout/header');
         if ($data['real_role'] == 7 || $data['real_role'] == 1) {
            $data['list'] = $this->assessment_model->admin_all_assessment($this->general_model->get_account_id(), "past");
         } else {
            $data['list'] = $this->assessment_model->all_assessment($this->general_model->get_account_id(), "past");
         }
      } else {
         $data['list'] = $this->assessment_model->assigned_assessment($this->general_model->get_account_id(), "past");
         $this->load->view('layout/student/header');
      }

      $this->load->view('lms/assessment/past', $data);
      $this->load->view('layout/footer');
   }

   public function shared()
   {
      $this->session->set_userdata('top_menu', 'Download Center');
      $this->session->set_userdata('sub_menu', 'Assessment');
      $this->session->set_userdata('subsub_menu', 'lms/assessment/shared');

      $data['user_id'] = $this->general_model->get_account_id();
      $data['role'] = $this->general_model->get_role();
      $data['real_role'] = $this->general_model->get_real_role();

      if ($data['role'] == 'admin') {
         $this->load->view('layout/header');
         $data['list'] = $this->assessment_model->shared_assessment($this->general_model->get_account_id());
      } else {
         $this->load->view('layout/student/header');
         $data['list'] = $this->assessment_model->shared_assessment($this->general_model->get_account_id());
      }

      $this->load->view('lms/assessment/shared', $data);
      $this->load->view('layout/footer');
   }

   public function create_index()
   {

      $this->session->set_userdata('top_menu', 'Download Center');
      $this->session->set_userdata('sub_menu', 'Assessment');
      $this->session->set_userdata('subsub_menu', 'lms/assessment/create');

      $data['user_id'] = $this->general_model->get_account_id();
      $data['role'] = $this->general_model->get_role();
      $data['real_role'] = $this->general_model->get_real_role();


      if ($data['role'] == 'admin') {

         $this->load->view('layout/header');
         if ($data['real_role'] == 7) {
            $data['list'] = $this->assessment_model->admin_all_assessment($this->general_model->get_account_id());
         } else {
            $data['list'] = $this->assessment_model->all_assessment($this->general_model->get_account_id());
         }
      } else {

         $data['list'] = $this->assessment_model->assigned_assessment($this->general_model->get_account_id());
         $this->load->view('layout/student/header');
      }

      $this->load->view('lms/assessment/create_index', $data);
      $this->load->view('layout/footer');
   }

   public function reports($assessment_id, $section = "all", $gender = "all")
   {

      $this->session->set_userdata('top_menu', 'Download Center');
      $this->session->set_userdata('sub_menu', 'content/assessment');
      $data['list'] = $this->assessment_model->all_assessment();

      $data['role'] = $this->general_model->get_role();
      $data['real_role'] = $this->general_model->get_real_role();
      $current_session = $this->setting_model->getCurrentSession();

      $data['assessment'] = $this->assessment_model->lms_get('lms_assessment', $assessment_id, "id")[0];
      $data['sections'] = $this->assessment_model->lms_get('sections', "", "", "id,section");
      $data['section'] = $section;
      $data['gender'] = $gender;


      $this->db->select("students.id as id,students.id as student_id,students.firstname,students.lastname,classes.class,sections.section,students.gender");

      $this->db->join("student_session", "students.id = student_session.student_id", "left");
      $this->db->join("classes", "classes.id = student_session.class_id", "left");
      $this->db->join("sections", "sections.id = student_session.section_id", "left");

      $this->db->where_in("students.id", explode(",", $data['assessment']['assigned']));
      if ($gender != "all") {
         $this->db->where("students.gender", $gender);
      }
      if ($section != "all") {
         $this->db->where("sections.id", $section);
      }

      $this->db->where("student_session.session_id", $current_session);
      $this->db->group_by("students.id");
      $this->db->order_by("lastname");
      $students = $this->db->get("students")->result_array();

      $student_answers = $this->lesson_model->lms_get("lms_assessment_sheets", $assessment_id, "assessment_id");

      $not_yet = 0;
      $answering = 0;
      $submitted = 0;
      foreach ($students as $student_key => $student_value) {

         $this->db->select("id,response_status");
         $this->db->from("lms_assessment_sheets");
         $this->db->where("account_id", $student_value['id']);
         $exist = $this->db->where("assessment_id", $assessment_id)->get()->result_array()[0];

         if ($exist) {

            if ($exist['response_status'] == 1) {

               $submitted += 1;
            } else {

               $answering += 1;
            }

            $this->db->select("MAX(date_created) as max_date");
            $this->db->from("lms_assessment_sheets");
            $this->db->where("response_status", $exist['response_status']);
            $this->db->where("account_id", $student_value['id']);
            $max_date = $this->db->where("assessment_id", $assessment_id)->get()->result_array()[0]['max_date'];

            $this->db->select("*");
            $this->db->from("lms_assessment_sheets");
            $this->db->where("account_id", $student_value['id']);
            $this->db->where("response_status", $exist['response_status']);
            $this->db->where("date_created", $max_date);
            $assessment_sheet_data = $this->db->where("assessment_id", $assessment_id)->get()->result_array()[0];
            if (!empty($assessment_sheet_data)) {
               // print_r($assessment_sheet_data);
               $students[$student_key]['assessment_sheet_id'] = $assessment_sheet_data['id'];
               $students[$student_key]['response_status'] = $assessment_sheet_data['response_status'];
               $students[$student_key]['student_activity'] = ($assessment_sheet_data['response_status'] == 1) ? "submitted" : "answering";
               $students[$student_key]['assessment_sheet_id'] = $assessment_sheet_data['id'];
               $students[$student_key]['score'] = ($assessment_sheet_data['score']) ? $assessment_sheet_data['score'] : 0;
               $students[$student_key]['total_score'] = $data['assessment']['total_score'];
               $students[$student_key]['start_date'] = date("M d, Y h:i A", strtotime($assessment_sheet_data['start_date']));
               $students[$student_key]['end_date'] = $assessment_sheet_data['end_date'] == null ? "" : date("M d, Y h:i A", strtotime($assessment_sheet_data['end_date']));
               $students[$student_key]['browser'] = $assessment_sheet_data['browser'];
               $students[$student_key]['browser_version'] = $assessment_sheet_data['browser_version'];
               $students[$student_key]['device'] = $assessment_sheet_data['device'];
               $students[$student_key]['os_platform'] = $assessment_sheet_data['os_platform'];
               $students[$student_key]['assessment_id'] = $data['assessment']['id'];
            } else {
            }
         } else {
            $not_yet += 1;
            $students[$student_key]['response_status'] = 0;
            $students[$student_key]['assessment_sheet_id'] = "";
            $students[$student_key]['student_activity'] = "not_yet";
            $students[$student_key]['score'] = ($student_value['score']) ? $student_value['score'] : 0;
            $students[$student_key]['total_score'] = $data['assessment']['total_score'];
            $students[$student_key]['browser'] = "";
            $students[$student_key]['browser_version'] = "";
            $students[$student_key]['os_platform'] = "";
            $students[$student_key]['assessment_id'] = $data['assessment']['id'];
         }
      }
      // echo '<pre>';exit();

      $data['answering'] = $answering;
      $data['submitted'] = $submitted;
      $data['not_yet'] = $not_yet;
      $data['students'] = $students;

      if ($data['role'] == 'admin') {
         $this->load->view('layout/header');
      } else {

         $this->load->view('layout/student/header');
      }

      $this->load->view('lms/assessment/reports', $data);
      $this->load->view('layout/footer');
   }

   public function get_sheets($id)
   {
      if ($id) {
         $assessment = $this->assessment_model->lms_get('lms_assessment', $id, "id")[0];
         $assessment_sheets = $this->assessment_model->assessment_sheets($id);

         $json_sheet = json_decode($assessment['sheet']);
         $responses['data'] = array();
         $array_pos = 0;

         //var_dump($json_sheet[0]->type);
         foreach ($assessment_sheets as $row) {
            $json_respond = json_decode($row['answer']);
            //var_dump($json_respond);
            $answers_count['data'] = array();
            $resp_pos = 0;
            // echo '<pre>';print_r($json_respond);exit();
            if ($json_respond != null || $json_respond != '') {
               foreach ($json_respond as $respond) {
                  // var_dump($respond);
                  // echo($respond->type);

                  if ($respond->type != "long_answer" && $respond->type != "short_answer" && $respond->type != "section") {

                     if (strpos($respond->answer, '1') > -1) {
                        if ($array_pos == 0) {
                           $responses['data'][] = array(
                              'type' => $respond->type,
                              'answer_choices' => explode(',', $json_sheet[$resp_pos]->option_labels),
                              'respondents' => 1,
                              'answers_count' =>  explode(',', $respond->answer)
                           );
                        } else {
                           $responses['data'][$resp_pos]['respondents'] = $responses['data'][$resp_pos]['respondents'] + 1;

                           $answer = explode(',', $respond->answer);
                           $answerIdx = 0;
                           foreach ($answer as $ans) {
                              $responses['data'][$resp_pos]['answers_count'][$answerIdx] = (string)((int)$responses['data'][$resp_pos]['answers_count'][$answerIdx] + (int)$ans);
                              $answerIdx++;
                           }
                        }
                     } else {
                        if ($array_pos == 0) {
                           $responses['data'][] = array(
                              'type' => $respond->type,
                              'answer_choices' => explode(',', $json_sheet[$resp_pos]->option_labels),
                              'respondents' => 0,
                              'answers_count' => explode(',', $respond->answer)
                           );
                        } else {
                           //
                        }
                     }
                  } else {
                     $responses['data'][] = array(
                        'type' => $respond->type,
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

   public function assigned()
   {

      $this->page_title = "Assigned";
      $this->data = $this->assessment_model->assigned_assessment($this->session->userdata('id'));
      $this->sms_view(__FUNCTION__);
   }


   public function save()
   {

      $data['assessment_name'] = $_REQUEST['assessment_name'];
      $data['account_id'] = $this->customlib->getStaffID();
      $data['assigned'] = $_REQUEST['assigned'];

      $assessment_id = $this->assessment_model->lms_create("lms_assessment", $data);

      redirect(site_url() . "lms/assessment/edit/" . $assessment_id);
   }

   public function edit($id)
   {
      if ($id) {
         $data['id'] = $id;
         $data['assessment'] = $this->assessment_model->lms_get("lms_assessment", $id, "id")[0];

         // print_r($data);
         // die();

         $data['resources'] = site_url('backend/lms/');
         $data['students'] = $this->lesson_model->get_students("lms_lesson", $id, "id");
         $data['classes'] = $this->class_model->getAll();
         $data['class_sections'] = $this->lesson_model->get_class_sections();

         $this->load->view('lms/assessment/edit', $data);
      }
   }

   public function answer($id)
   {
      date_default_timezone_set('Asia/Manila');
      $data['id'] = $id;
      $data['account_id'] = $this->general_model->get_account_id();
      $data['student_data'] = $this->general_model->get_account_name($data['account_id'], "student")[0];
      $data['student_name'] = $data['student_data']['firstname'] . " " . $data['student_data']['lastname'];

      $this->db->select("*");
      $this->db->where("account_id", $data['account_id']);
      $this->db->where("assessment_id", $id);
      $this->db->where("response_status", 1);


      $query = $this->db->get("lms_assessment_sheets");


      $response = $query->result_array();
      $attempt_data = $this->assessment_model->lms_get("lms_assessment", $id, "id")[0];

      $data['assessment'] = $this->assessment_model->lms_get("lms_assessment", $id, "id")[0];

      $data['resources'] = site_url('backend/lms/');


      if (count($response) >= $attempt_data['attempts']) {
         echo "<script>alert('Maximum Attempts Have Been Reached! Account ID:" . $data['account_id'] . "');window.location.replace('" . site_url('lms/assessment/index') . "')</script>";

         $this->load->view('lms/assessment/answer', $data);
      } else {
         $this->db->select("*");
         $this->db->where("account_id", $data['account_id']);
         $this->db->where("assessment_id", $id);
         $this->db->where("response_status", 0);
         $new_query = $this->db->get("lms_assessment_sheets");
         $new_response = $new_query->result_array();

         // echo '<pre>';print_r(strtotime("+10 minutes",strtotime(date("Y-m-d H:i:s"))));exit();
         if (empty($new_response)) {
            $assessment_data['assessment_id'] = $id;
            $assessment_data['account_id'] = $data['account_id'];
            $assessment_data['response_status'] = 0;
            $assessment_data['expiration'] = date("Y-m-d H:i:s", strtotime("+" . $data['assessment']['duration'] . " minutes", strtotime(date("Y-m-d H:i:s"))));

            $new_assessment_id = $this->assessment_model->lms_create("lms_assessment_sheets", $assessment_data);
            $new_response = $this->assessment_model->lms_get("lms_assessment_sheets", $new_assessment_id, "id");
         }
         $data['assessment_sheet'] = $new_response[0];

         $this->load->view('lms/assessment/answer', $data);
      }
   }

   public function review($id, $account_id = "")
   {
      $data['id'] = $id;

      if ($account_id) {
         $data['account_id'] = $account_id;
         $data['teacher_review'] = TRUE;
      } else {
         $data['account_id'] = $this->general_model->get_account_id();
         $data['teacher_review'] = FALSE;
      }

      $this->db->select("*");
      $this->db->where("account_id", $data['account_id']);
      $this->db->where("assessment_id", $id);
      $this->db->where("response_status", 1);
      $this->db->order_by("date_created", "desc");

      $query = $this->db->get("lms_assessment_sheets");
      $response = $query->result_array();

      $data['assessment'] = $this->assessment_model->lms_get("lms_assessment", $id, "id")[0];

      if (!$data['assessment']['allow_result_viewing'] || $data['assessment']['allow_result_viewing'] == 0) {
         if ($account_id) {
         } else {
            redirect(site_url('lms/assessment/index'));
         }
      }

      // print_r($data);die();

      $data['resources'] = site_url('backend/lms/');
      $data['student_data'] = $this->general_model->get_account_name($data['account_id'], "student")[0];
      $data['assessment_sheet'] = $response[0];

      // if(!$data['assessment_sheet']){
      //     echo "<script>alert('This quiz has not been submitted yet');</script>";
      // }
      $data['role'] = $this->general_model->get_role();

      $this->load->view('lms/assessment/review', $data);
   }

   public function allow_reanswer($assessment_sheet_id, $account_id = "")
   {
      $data['id'] = $assessment_sheet_id;
      $data['response_status'] = 0;

      $assessment_sheet_data = $this->assessment_model->lms_get("lms_assessment_sheets", $assessment_sheet_id, "id", "assessment_id")[0];
      $assessment_data = $this->assessment_model->lms_get("lms_assessment", $assessment_sheet_data['assessment_id'], "id", "duration")[0];

      $data['expiration'] = date("Y-m-d H:i:s", strtotime("+" . $assessment_data['duration'] . " minutes", strtotime(date("Y-m-d H:i:s"))));

      $assessment_sheet = $this->assessment_model->lms_update("lms_assessment_sheets", $data);
      redirect(site_url('lms/assessment/reports/' . $assessment_sheet['assessment_id']));
   }

   public function allow_reanswer_delete($assessment_sheet_id, $account_id = "")
   {
      $data['id'] = $assessment_sheet_id;
      $assessment_sheet_data = $this->assessment_model->lms_get("lms_assessment_sheets", $assessment_sheet_id, "id", "assessment_id")[0];
      $this->db->where("id", $assessment_sheet_id);
      $this->db->delete("lms_assessment_sheets");

      // redirect(site_url('lms/assessment/reports/' . $assessment_sheet_data['assessment_id']));

      $data['result'] = 'success';
      $data['message'] = 'The student may now retake the exam';
      echo json_encode($data);
   }

   public function update()
   {
      $data['id'] = $_REQUEST['id'];
      $data['sheet'] = $_REQUEST['sheet'];
      $data['assigned'] = $_REQUEST['assigned'];
      $data['duration'] = $_REQUEST['duration'];
      $data['percentage'] = $_REQUEST['percentage'];
      $data['attempts'] = $_REQUEST['attempts'];
      $data['start_date'] = $_REQUEST['start_date'];
      $data['end_date'] = $_REQUEST['end_date'];
      $data['email_notification'] = $_REQUEST['email_notification'];
      $data['allow_result_viewing'] = $_REQUEST['allow_result_viewing'];
      $data['enable_timer'] = $_REQUEST['enable_timer'];
      $data['assessment_name'] = $_REQUEST['assessment_name'];
      $data['term'] = $_REQUEST['term'];

      $sheet = (array)json_decode($data['sheet']);

      if ($data['email_notification'] == "1") {
         $sender_details = array('student_id' => 1, 'contact_no' => '+639953230083', 'email' => 'cervezajoeven@gmail.com');
         $this->mailsmsconf->mailsms('assessment_assigned', $sender_details);
      }
      // print_r($data['email_notification']);
      $total_score = 0;
      //convert to array
      foreach ($sheet as $answer_key => $answer_value) {
         if ($answer_value->type != "section") {
            $sheet[$answer_key] = (array)$answer_value;

            $total_score += $sheet[$answer_key]['points'];
         }
      }
      //convert to array
      $data['total_score'] = $total_score;
      $this->assessment_model->lms_update("lms_assessment", $data);
   }

   public function update_survey_sheet()
   {
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

   public function upload($id)
   {
      // print_r(strpos($_FILES['survey_form']['type'], "pdf"));
      if (strpos($_FILES['assessment_form']['type'], "pdf") !== 0) {
         $tmp_name = $_FILES['assessment_form']['tmp_name'];
         $file_name = $this->assessment_model->id_generator("assessment") . ".pdf";
         $dest = FCPATH . "uploads/lms_assessment/" . $id . "/" . $file_name;

         // $folderCreated = false;

         // if (!is_dir(FCPATH . "uploads/lms_assessment/" . $id)) {
         //    try {
         //       mkdir(FCPATH . "uploads/lms_assessment/" . $id);
         //       $folderCreated = true;
         //    } catch (ErrorException $ex) {
         //       // echo "<script>alert('Upload failed! (" . $ex->getMessage() . ")');window.location.replace('" . site_url('lms/assessment/edit/' . $id) . "')</script>";

         //       $data['status'] = 'failed';
         //       $data['message'] = 'Upload failed!';
         //       echo json_encode($data);
         //    }
         // } else
         //    $folderCreated = true;

         // $this->load->library('s3');
         // $s3 = new S3(AWS_ACCESS_KEY_ID, AWS_ACCESS_KEY_SECRET, false, S3_URI, AWS_REGION);

         // if ($s3->putObject($id . "/", S3_BUCKET, $_SESSION['School_Code'] . "/uploads/lms_assessment/", S3::ACL_PUBLIC_READ)) {
         //    $folderCreated = true;

         //    $data['status'] = 'success';
         //    $data['message'] = 'Folder created successfuly!(' . $_SESSION['School_Code'] . "uploads/lms_assessment/" . $id . ')';
         //    echo json_encode($data);
         // } else {
         //    $data['status'] = 'failed';
         //    $data['message'] = 'Upload failed!';
         //    echo json_encode($data);
         // }

         $folderCreated = true;

         if ($folderCreated == true) {
            $this->load->library('s3');
            $s3 = new S3(AWS_ACCESS_KEY_ID, AWS_ACCESS_KEY_SECRET, false, S3_URI, AWS_REGION);
            $dest_file = $_SESSION['School_Code'] . "/uploads/lms_assessment/" . $id . "/" . $file_name;

            // if (move_uploaded_file($tmp_name, $dest)) {
            if ($s3->putObjectFile($tmp_name, S3_BUCKET, $dest_file, S3::ACL_PUBLIC_READ)) {
               $data['id'] = $id;
               $data['assessment_file'] = $file_name;

               $this->assessment_model->lms_update("lms_assessment", $data);

               // echo "<script>alert('Successfully uploaded');window.location.replace('" . site_url('lms/assessment/edit/' . $id) . "')</script>";
               $data['status'] = 'success';
               $data['message'] = 'Upload Successful!';
               echo json_encode($data);
            } else {
               // echo "<script>alert('Upload failed!');window.location.replace('" . site_url('lms/assessment/edit/' . $id) . "')</script>";
               $data['status'] = 'failed';
               $data['message'] = 'Upload failed!';
               echo json_encode($data);
            }
         }
      } else {
         // echo "<script>alert('Only PDF files are allowed');window.location.replace('" . site_url('lms/assessment/edit/' . $id) . "')</script>";
         $data['status'] = 'failed';
         $data['message'] = 'Only PDF files are allowed';
         echo json_encode($data);
      }
   }

   public function duplicate($id)
   {
      if ($id) {
         $assessment = $this->assessment_model->lms_get('lms_assessment', $id, "id")[0];
         $duplicated = $assessment;
         $duplicated['assessment_name'] = $assessment['assessment_name'] . " (" . date("F d, Y h:i A") . ")";
         $duplicated['assigned'] = "";
         $duplicated['assessment_file'] = $assessment['assessment_file'];
         unset($duplicated['id']);
         unset($duplicated['date_created']);
         unset($duplicated['date_updated']);
         unset($duplicated['date_read']);
         unset($duplicated['date_deleted']);

         $newAssessmentID = null;
         $newAssessmentID = $this->assessment_model->lms_create('lms_assessment', $duplicated, FALSE);

         if ($newAssessmentID != null) {
            $this->copyQuestionaire($id, $newAssessmentID, $assessment['assessment_file']);

            $data['result'] = 'success';
            $data['message'] = 'Duplicate successful!';
         } else {
            $data['result'] = 'error';
            $data['message'] = 'Duplicate failed!';
         }

         echo json_encode($data);

         // redirect(site_url('lms/assessment/index'));
      }
   }

   public function copyQuestionaire($oldAssessmentID, $newAssessmentID, $file_name)
   {
      $this->load->library('s3');
      $s3 = new S3(AWS_ACCESS_KEY_ID, AWS_ACCESS_KEY_SECRET, false, S3_URI, AWS_REGION);
      $source_file = $_SESSION['School_Code'] . "/uploads/lms_assessment/" . $oldAssessmentID . "/" . $file_name;
      $dest_file = $_SESSION['School_Code'] . "/uploads/lms_assessment/" . $newAssessmentID . "/" . $file_name;

      if ($s3->copyObject(S3_BUCKET, $source_file, S3_BUCKET, $dest_file, S3::ACL_PUBLIC_READ)) {
         $data['id'] = $newAssessmentID;
         $data['assessment_file'] = $file_name;
         $this->assessment_model->lms_update("lms_assessment", $data);

         // $data['status'] = 'success';
         // $data['message'] = 'Upload Successful!';
         // echo json_encode($data);

         return true;
      } else {
         // $data['status'] = 'failed';
         // $data['message'] = 'Upload failed!';
         // echo json_encode($data);
         return false;
      }
   }

   // public function copyQuestionaire($oldAssessmentID, $newAssessmentID, $file_name)
   // {
   //    $folderCreated = false;

   //    if (!is_dir(FCPATH . "uploads/lms_assessment/" . $newAssessmentID)) {
   //       try {
   //          mkdir(FCPATH . "uploads/lms_assessment/" . $newAssessmentID);
   //          $folderCreated = true;
   //       } catch (ErrorException $ex) {
   //          echo "<script>alert('Upload failed! (" . $ex->getMessage() . ")');window.location.replace('" . site_url('lms/assessment/edit/' . $newAssessmentID) . "')</script>";
   //       }
   //    } else
   //       $folderCreated = true;

   //    $source = FCPATH . "uploads/lms_assessment/" . $oldAssessmentID . "/" . $file_name;
   //    $dest = FCPATH . "uploads/lms_assessment/" . $newAssessmentID . "/" . $file_name;

   //    if ($folderCreated == true) {
   //       copy($source, $dest);

   //       $data['id'] = $newAssessmentID;
   //       $data['assessment_file'] = $file_name;
   //       $this->assessment_model->lms_update("lms_assessment", $data);
   //       // echo "<script>alert('Successfully uploaded');window.location.replace('" . site_url('lms/assessment/edit/' . $newAssessmentID) . "')</script>";
   //    }
   // }

   public function delete($id)
   {
      $data['id'] = $id;
      $data['deleted'] = 1;

      if ($this->assessment_model->lms_update("lms_assessment", $data)) {
         // redirect(site_url("lms/assessment/index"));
         $data['result'] = 'success';
         $data['message'] = 'Delete successful!';
      } else {
         $data['result'] = 'error';
         $data['message'] = 'Delete failed!';
      }

      echo json_encode($data);
   }

   public function answer_submit()
   {

      $data['id'] = $_REQUEST['id'];
      $data['assessment_id'] = $_REQUEST['assessment_id'];
      $data['answer'] = $_REQUEST['answer'];
      $answer = (array)json_decode($data['answer']);
      $assessment = $this->assessment_model->lms_get("lms_assessment", $data['assessment_id'], "id")[0];
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

         $answer_value['answer'] = preg_replace('/\n/', '~nextline~', $answer_value['answer']);

         if ($answer_value['type'] == "multiple_choice" || $answer_value['type'] == "multiple_answer") {

            if ($answer_value['answer'] == $assessment_value['correct']) {
               if (array_key_exists("points", $assessment_value)) {

                  $score += $assessment_value['points'];
               } else {

                  $score += 1;
               }
            }
         } else if ($answer_value['type'] == "short_answer") {
            if (in_array(trim(strtolower($answer_value['answer'])), explode(",", trim(strtolower($assessment_value['correct']))))) {
               if (array_key_exists("points", $assessment_value)) {

                  $score += $assessment_value['points'];
               } else {

                  $score += 1;
               }
            }
         } else {
         }
      }

      $data['score'] = $score;
      $data['response_status'] = "1";

      print_r($this->assessment_model->lms_update("lms_assessment_sheets", $data));
   }

   public function auto_save()
   {

      $data['id'] = $_REQUEST['id'];
      $data['assessment_id'] = $_REQUEST['assessment_id'];
      $data['answer'] = $_REQUEST['answer'];
      $answer = (array)json_decode($data['answer']);
      $assessment = $this->assessment_model->lms_get("lms_assessment", $data['assessment_id'], "id")[0];
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

         $answer_value['answer'] = preg_replace('/\n/', '~nextline~', $answer_value['answer']);

         if ($answer_value['type'] == "multiple_choice" || $answer_value['type'] == "multiple_answer") {

            if ($answer_value['answer'] == $assessment_value['correct']) {
               if (array_key_exists("points", $assessment_value)) {

                  $score += $assessment_value['points'];
               } else {

                  $score += 1;
               }
            }
         } else if ($answer_value['type'] == "short_answer") {
            if (in_array(trim(strtolower($answer_value['answer'])), explode(",", trim(strtolower($assessment_value['correct']))))) {
               if (array_key_exists("points", $assessment_value)) {

                  $score += $assessment_value['points'];
               } else {

                  $score += 1;
               }
            }
         } else {
         }
      }

      $data['score'] = $score;
      $data['response_status'] = "0";

      print_r($this->assessment_model->lms_update("lms_assessment_sheets", $data));
   }

   public function recheck_answers($id)
   {

      $assessment = $this->assessment_model->lms_get("lms_assessment", $id, "id")[0];
      $students = explode(",", $assessment['assigned']);
      $this->db->select("account_id");
      $this->db->where("assessment_id", $id);
      $students = $this->db->get("lms_assessment_sheets")->result_array();
      // $students = $this->assessment_model->lms_get("lms_assessment_sheets",$id,"assessment_id");
      foreach ($students as $key => $value) {
         $students[$key] = $value['account_id'];
      }

      foreach ($students as $student_key => $student_value) {
         $this->db->select("MAX(date_created) as max_date");
         $this->db->where("assessment_id", $id);
         $this->db->where("account_id", $student_value);
         $this->db->where("response_status", 1);
         $max_date = $this->db->get("lms_assessment_sheets")->result_array()[0]['max_date'];

         $this->db->select("id,answer,account_id");
         $this->db->where("assessment_id", $id);
         $this->db->where("account_id", $student_value);
         $this->db->where("response_status", 1);
         $this->db->where("date_created", $max_date);
         $student_answer = $this->db->get("lms_assessment_sheets")->result_array()[0];
         $data['id'] = $student_answer['id'];
         $data['assessment_id'] = $assessment['id'];
         $data['answer'] = $student_answer['answer'];
         $answer = (array)json_decode($data['answer']);
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

            $answer_value['answer'] = preg_replace('/\n/', '~nextline~', $answer_value['answer']);

            // print_r($answer_value['answer']  . "<BR>");                

            if ($answer_value['type'] == "multiple_choice" || $answer_value['type'] == "multiple_answer") {

               if ($answer_value['answer'] == $assessment_value['correct']) {
                  if (array_key_exists("points", $assessment_value)) {

                     $score += $assessment_value['points'];
                  } else {

                     $score += 1;
                  }
               }
            } else if ($answer_value['type'] == "short_answer") {

               $student_answer = implode("|comma|", explode(",", trim(strtolower($answer_value['answer']))));

               if (in_array($student_answer, explode(",", trim(strtolower($assessment_value['correct']))))) {


                  if (array_key_exists("points", $assessment_value)) {

                     $score += $assessment_value['points'];
                  } else {

                     $score += 1;
                  }
               }
            } else {


               if (array_key_exists("score", $answer_value)) {

                  $score += $answer_value['score'];
               } else {
                  $score += 0;
               }
            }
         }

         // die();

         $data['score'] = $score;
         $data['response_status'] = "1";



         $this->assessment_model->lms_update("lms_assessment_sheets", $data);
      }

      // echo '<pre>';print_r($data);exit();

      redirect(base_url('lms/assessment/reports/') . $assessment['id']);
   }

   public function check_essays($id)
   {
      if ($id) {
         $data['id'] = $id;
         $data['assessment'] = $this->assessment_model->lms_get("lms_assessment", $id, "id")[0];
         $data['resources'] = site_url('backend/lms/');

         $this->db->select("id,firstname,lastname");
         $this->db->where_in("id", explode(",", $data['assessment']['assigned']));
         $this->db->order_by("lastname");
         $students = $this->db->get("students")->result_array();

         $student_answers = $this->lesson_model->lms_get("lms_assessment_sheets", $id, "assessment_id");

         foreach ($students as $student_key => $student_value) {

            foreach ($student_answers as $student_answers_key => $student_answers_value) {
               if ($student_value['id'] == $student_answers_value['account_id']) {


                  $students[$student_key]['has_answered'] = $student_answers_value['response_status'];
                  $students[$student_key]['answer'] = $student_answers_value['answer'];
                  $students[$student_key]['assessment_sheet_id'] = $student_answers_value['id'];
               }
            }
         }


         $data['students'] = $students;
         $data['classes'] = $this->class_model->getAll();
         $data['class_sections'] = $this->lesson_model->get_class_sections();

         $this->load->view('lms/assessment/check_essays', $data);
      }
   }

   public function fetch_essays($id, $account_id)
   {
      if ($id) {

         $data['id'] = $id;
         $data['assessment'] = $this->assessment_model->lms_get("lms_assessment", $id, "id")[0];
         $this->db->select("MAX(date_created) as max_date");
         $this->db->where("assessment_id", $id);
         $this->db->where("account_id", $account_id);
         $this->db->where("response_status", 1);
         $max_date = $this->db->get("lms_assessment_sheets")->result_array()[0]['max_date'];

         $this->db->select("*");
         $this->db->where("assessment_id", $id);
         $this->db->where("account_id", $account_id);
         $this->db->where("response_status", 1);
         $this->db->where("date_created", $max_date);

         $student_answers = $this->db->get("lms_assessment_sheets")->result_array()[0];

         // foreach ($students as $student_key => $student_value) {

         //     foreach ($student_answers as $student_answers_key => $student_answers_value) {
         //         if($student_value['id'] == $student_answers_value['account_id']){

         //             $students[$student_key]['has_answered'] = 1;
         //             $students[$student_key]['answer'] = $student_answers_value['answer'];

         //         }
         //     }

         // }

         echo json_encode($student_answers);
      }
   }

   public function update_essay()
   {
      $data['id'] = $_REQUEST['assessment_sheet_id'];
      $data['answer'] = json_encode($_REQUEST['updated_answer']);
      $the_data = $this->lesson_model->lms_update("lms_assessment_sheets", $data, "id");
      if ($the_data) {
         $return = array("status" => 1, "message" => "success");
      } else {
         $return = array("status" => 0, "message" => "fail");
      }
      echo json_encode($return);
   }

   public function consider_answer()
   {
      $data['id'] = $_REQUEST['assessment_id'];
      $data['considered_answer'] = $_REQUEST['considered_answer'];
      $data['considered_answer_order'] = $_REQUEST['considered_answer_order'];
      $update_sheet = $this->assessment_model->lms_get("lms_assessment", $data['id'], "id", "assessment_name,sheet")[0];
      $decoded_update_sheet = json_decode($update_sheet['sheet']);
      $exploded_correct = explode(",", $decoded_update_sheet[$data['considered_answer_order']]->correct);
      array_push($exploded_correct, implode("|comma|", explode(",", $data['considered_answer'])));
      $with_considered_answer = implode(",", $exploded_correct);
      $decoded_update_sheet[$data['considered_answer_order']]->correct = $with_considered_answer;

      $backup_data['id'] = $data['id'];
      $backup_data['backup_sheet'] = $update_sheet['sheet'];

      $new_data['id'] = $data['id'];
      $new_data['sheet'] = json_encode($decoded_update_sheet);

      $this->assessment_model->lms_update("lms_assessment", $backup_data);

      if ($this->assessment_model->lms_update("lms_assessment", $new_data)) {
         echo "true";
      } else {
         echo "false";
      }
   }

   public function analysis($id)
   {
      $this->page_title = "Item Analysis";
      $assessment_sheets = $this->assessment_model->assessment_sheets($id);
      $assessment = $this->assessment_model->lms_get("lms_assessment", $id, "id")[0];
      $data['data'] = $assessment_sheets;
      $data['assessment'] = $assessment;
      // echo '<pre>';print_r(json_decode($assessment['sheet']));exit();
      // $data['id'] = $id;
      $data['resources'] = site_url('backend/lms/');

      $this->load->view('lms/assessment/analysis', $data);
   }

   public function stored_json()
   {
      $id = $_REQUEST['assessment_id'];
      $sheet = $this->assessment_model->lms_get('lms_assessment', $id, "id", "sheet")[0]['sheet'];
      echo $sheet;
   }

   public function stored_answer()
   {
      $id = $_REQUEST['assessment_sheet_id'];
      $answer = $this->assessment_model->lms_get('lms_assessment_sheets', $id, "id", "answer")[0]['answer'];
      // echo '<pre>';print_r($answer);exit();
      echo $answer;
   }

   public function share($lesson_id)
   {
      $data['id'] = $lesson_id;
      $data['shared'] = 1;
      $this->assessment_model->lms_update("lms_assessment", $data);

      // redirect(site_url() . "lms/lesson/index/");
      $data['result'] = 'Share successful!';
      echo json_encode($data);
   }

   public function unshare($lesson_id)
   {
      $data['id'] = $lesson_id;
      $data['shared'] = 0;
      $this->assessment_model->lms_update("lms_assessment", $data);

      // redirect(site_url() . "lms/lesson/index/");
      $data['result'] = 'Unshare successful!';
      echo json_encode($data);
   }
}

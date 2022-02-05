<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends Front_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->config('form-builder');
      $this->load->config('app-config');
      $this->load->library(array('mailer', 'form_builder'));
      $this->load->helper(array('directory', 'customfield', 'custom', 'email'));
      $this->load->model(array(
         'frontcms_setting_model',
         'complaint_Model',
         'Visitors_model',
         'onlinestudent_model',
         'customfield_model',
         "Module_model",
         'cms_program_model',
         'cms_page_model',
         'cms_page_content_model',
         'cms_menu_model',
         'cms_menuitems_model'
      ));

      $this->load->model('section_model');
      $this->load->model('setting_model');
      $this->load->model('onlinestudent_model');
      $this->load->model('class_model');
      $this->load->model('student_model');
      $this->load->model('category_model');

      $this->blood_group = $this->config->item('bloodgroup');
      $this->load->library('Ajax_pagination');
      $this->load->library('module_lib');
      $this->banner_content         = $this->config->item('ci_front_banner_content');
      $this->perPage                = 12;
      $ban_notice_type              = $this->config->item('ci_front_notice_content');
      $this->data['banner_notices'] = $this->cms_program_model->getByCategory($ban_notice_type, array('start' => 0, 'limit' => 5));

      $this->load->library('mailsmsconf');

      $url = $_SERVER['SERVER_NAME'];

      if (strpos($url, 'localhost') !== false) {
         $this->mode = "offline";
      } elseif (strpos($url, '192.') !== false || strpos($url, '172.') !== false) {
         $this->mode = "offline";
      } else {
         $this->mode = "online";
      }
   }

   public function show_404()
   {
      $this->load->view('errors/error_message');
   }

   public function index()
   {
      $setting                     = $this->frontcms_setting_model->get();
      $this->data['active_menu']   = 'home';
      $this->data['page_side_bar'] = $setting->is_active_sidebar;
      $home_page                   = $this->config->item('ci_front_home_page_slug');
      $result                      = $this->cms_program_model->getByCategory($this->banner_content);
      $this->data['page']          = $this->cms_page_model->getBySlug($home_page);

      $school = $this->setting_model->get();
      $_SESSION['S3_BaseUrl'] = S3_BUCKET_BASE_URL . strtolower($school[0]['dise_code']) . "/";
      $_SESSION['School_Code'] = strtolower($school[0]['dise_code']);

      // print_r($_SESSION['S3_BaseUrl']);
      // die();

      if (!empty($result)) {
         $this->data['banner_images'] = $this->cms_program_model->front_cms_program_photos($result[0]['id']);
      }

      $this->load_theme('home');
   }

   public function page($slug)
   {
      $page = $this->cms_page_model->getBySlug($slug);
      if (!$page) {
         $this->data['page'] = $this->cms_page_model->getBySlug('404-page');
      } else {

         $this->data['page'] = $this->cms_page_model->getBySlug($slug);
      }

      if ($page['is_homepage']) {
         redirect('frontend');
      }
      $this->data['active_menu']       = $slug;
      $this->data['page_side_bar']     = $this->data['page']['sidebar'];
      $this->data['page_content_type'] = "";
      if (!empty($this->data['page']['category_content'])) {
         $content_array = $this->data['page']['category_content'];
         reset($content_array);
         $first_key            = key($content_array);
         $totalRec             = count($this->cms_program_model->getByCategory($content_array[$first_key]));
         $config['target']     = '#postList';
         $config['base_url']   = base_url() . 'welcome/ajaxPaginationData';
         $config['total_rows'] = $totalRec;
         $config['per_page']   = $this->perPage;
         $config['link_func']  = 'searchFilter';
         $this->ajax_pagination->initialize($config);
         //get the posts data
         $this->data['page']['category_content'][$first_key] = $this->cms_program_model->getByCategory($content_array[$first_key], array('limit' => $this->perPage));

         $this->data['page_content_type']                    = $content_array[$first_key];
         //load the view
      }
      $this->data['page_form'] = false;

      if (strpos($page['description'], '[form-builder:') !== false) {
         $this->data['page_form'] = true;
         $start                   = '[form-builder:';
         $end                     = ']';

         $form_name = $this->customlib->getFormString($page['description'], $start, $end);

         $form = $this->config->item($form_name);

         $this->data['form_name'] = $form_name;
         $this->data['form']      = $form;

         if (!empty($form)) {
            foreach ($form as $form_key => $form_value) {
               if (isset($form_value['validation'])) {
                  $display_string = ucfirst(preg_replace('/[^A-Za-z0-9\-]/', ' ', $form_value['id']));
                  $this->form_validation->set_rules($form_value['id'], $display_string, $form_value['validation']);
               }
            }
            if ($this->form_validation->run() == false) {
            } else {
               $setting = $this->frontcms_setting_model->get();

               $response_message = $form['email_title']['mail_response'];
               $record           = $this->input->post();

               if ($record['form_name'] == 'contact_us') {
                  $email     = $this->input->post('email');
                  $name      = $this->input->post('name');
                  $cont_data = array(
                     'name'    => $name . " (" . $email . ")",
                     'source'  => 'Online',
                     'email'   => $this->input->post('email'),
                     'purpose' => $this->input->post('subject'),
                     'date'    => date('Y-m-d'),
                     'note'    => $this->input->post('description') . " (Sent from online front site)",
                  );
                  $visitor_id = $this->Visitors_model->add($cont_data);
               }

               if ($record['form_name'] == 'complain') {
                  $complaint_data = array(
                     'complaint_type' => 'General',
                     'source'         => 'Online',
                     'name'           => $this->input->post('name'),
                     'email'          => $this->input->post('email'),
                     'contact'        => $this->input->post('contact_no'),
                     'date'           => date('Y-m-d'),
                     'description'    => $this->input->post('description'),
                  );
                  $complaint_id = $this->complaint_Model->add($complaint_data);
               }

               $email_subject = $record['email_title'];
               $mail_body     = "";
               unset($record['email_title']);
               unset($record['submit']);
               foreach ($record as $fetch_k_record => $fetch_v_record) {
                  $mail_body .= ucwords($fetch_k_record) . ": " . $fetch_v_record;
                  $mail_body .= "<br/>";
               }
               if (!empty($setting) && $setting->contact_us_email != "") {

                  $this->mailer->send_mail($setting->contact_us_email, $email_subject, $mail_body);
               }

               $this->session->set_flashdata('msg', $response_message);
               redirect('page/' . $slug, 'refresh');
            }
         }
      }

      $this->load_theme('pages/page');
   }

   public function ajaxPaginationData()
   {
      $page              = $this->input->post('page');
      $page_content_type = $this->input->post('page_content_type');
      if (!$page) {
         $offset = 0;
      } else {
         $offset = $page;
      }
      $data['page_content_type'] = $page_content_type;
      //total rows count
      $totalRec = count($this->cms_program_model->getByCategory($page_content_type));
      //pagination configuration
      $config['target']     = '#postList';
      $config['base_url']   = base_url() . 'welcome/ajaxPaginationData';
      $config['total_rows'] = $totalRec;
      $config['per_page']   = $this->perPage;
      $config['link_func']  = 'searchFilter';
      $this->ajax_pagination->initialize($config);
      //get the posts data
      $data['category_content'] = $this->cms_program_model->getByCategory($page_content_type, array('start' => $offset, 'limit' => $this->perPage));
      //load the view
      $this->load->view('themes/default/pages/ajax-pagination-data', $data, false);
   }

   public function read($slug)
   {

      $this->data['active_menu'] = 'home';
      $page                      = $this->cms_program_model->getBySlug($slug);

      $this->data['page_side_bar']  = $page['sidebar'];
      $this->data['featured_image'] = $page['feature_image'];
      $this->data['page']           = $page;
      $this->load_theme('pages/read');
   }

   public function getSections()
   {
      if (!$this->input->is_ajax_request()) {
         exit('No direct script access allowed');
      } else {

         $class_id = $this->input->post('class_id');
         $data     = $this->section_model->getClassBySectionAll($class_id);
         echo json_encode($data);
      }
   }

   function reArrayFilesMultiple()
   {
      $uploads = array();
      foreach ($_FILES as $key0 => $FILES) {
         foreach ($FILES as $key => $value) {
            foreach ($value as $key2 => $value2) {
               $uploads[$key0][$key2][$key] = $value2;
            }
         }
      }
      $files = $uploads;
      return $uploads; // prevent misuse issue
   }

   public function admission()
   {

      //joeven
      // $exceptions = array("tlc-nbs");
      // $school_code = explode('.', $HTTP_HOST)[0];
      //joeven
      if ($this->module_lib->hasActive('online_admission')) {
         $this->data['active_menu'] = 'home';
         $page                      = array('title' => 'Online Admission Form', 'meta_title' => 'online admission form', 'meta_keyword' => 'online admission form', 'meta_description' => 'online admission form');

         $this->data['page_side_bar'] = false;
         $this->data['featured_image'] = false;
         $this->data['page'] = $page;
         ///============
         $this->data['form_admission'] = $this->setting_model->getOnlineAdmissionStatus();

         //-- EMN --
         $this->data['enrollment_type_list'] = $this->onlinestudent_model->GetEnrollmentTypes();
         $this->data['payment_mode_list'] = $this->onlinestudent_model->GetModesOfPayment();
         $this->data['payment_scheme_list'] = $this->onlinestudent_model->GetPaymentSchemes();

         $this->data['school_code'] = $this->setting_model->getCurrentSchoolCode();
         $this->data['current_year'] = date("Y");

         ///////===
         $genderList = $this->customlib->getGender();
         $this->data['genderList'] = $genderList;
         $this->data['title'] = 'Add Student';
         $this->data['title_list'] = 'Recently Added Student';

         $data["student_categorize"] = 'class';
         $session = $this->setting_model->getCurrentSession();

         $class = $this->class_model->getAll();
         $this->data['classlist'] = $class;
         $userdata = $this->customlib->getUserData();
         // print_r("EMN Debug Mode");die();

         $category = $this->category_model->get();
         $this->data['categorylist'] = $category;
         $this->data['schoolname'] = $this->setting_model->getCurrentSchoolName();


         $enrollment_type = $this->input->post('enrollment_type');

         if ($enrollment_type == 'old') {
            $this->form_validation->set_rules('studentidnumber', $this->lang->line('required'), 'trim|required|xss_clean');
            $classname = strtolower($this->input->post('classname'));

            // var_dump($classname);
            // echo(strpos($classname, 'nursery'));
            // echo(strpos($classname, 'kinder'));
            // echo(strpos($classname, 'grade 1'));
            // die;

            // if (strpos($classname, "nursery") == false && strpos($classname, "kinder") == false && strpos($classname, "grade 1") == false)
            // $this->form_validation->set_rules('lrn_no', $this->lang->line('required'), 'trim|required|xss_clean');
         } else {
            $this->form_validation->set_rules('father_name', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('father_occupation', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('mother_name', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('mother_occupation', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('father_company_name', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('father_company_position', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('father_mobile', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('father_nature_of_business', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('father_dob', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('father_citizenship', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('father_religion', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('father_highschool', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('father_college', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('father_college_course', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('father_prof_affiliation', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('father_prof_affiliation_position', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('father_tech_prof', $this->lang->line('required'), 'trim|required|xss_clean');

            $this->form_validation->set_rules('mother_company_name', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('mother_company_position', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('mother_mobile', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('mother_nature_of_business', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('mother_dob', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('mother_citizenship', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('mother_religion', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('mother_highschool', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('mother_college', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('mother_college_course', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('mother_prof_affiliation', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('mother_prof_affiliation_position', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('mother_tech_prof', $this->lang->line('required'), 'trim|required|xss_clean');

            // $this->form_validation->set_rules('marriage', $this->lang->line('required'), 'trim|required|xss_clean');
            // $this->form_validation->set_rules('dom', $this->lang->line('required'), 'trim|required|xss_clean');
            // $this->form_validation->set_rules('church', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('family_together', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('parents_away', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('parents_civil_status', $this->lang->line('required'), 'trim|required|xss_clean');

            $this->form_validation->set_rules('guardian_is', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('guardian_name', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('guardian_phone', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('guardian_email', $this->lang->line('required'), 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('guardian_occupation', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('guardian_address', $this->lang->line('required'), 'trim|required|xss_clean');

            $this->form_validation->set_rules('current_address', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('permanent_address', $this->lang->line('required'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('living_with_parents', $this->lang->line('required'), 'trim|required|xss_clean');
         }

         $this->form_validation->set_rules('enrollment_type', $this->lang->line('required'), 'trim|required|xss_clean');
         $this->form_validation->set_rules('mode_of_payment', $this->lang->line('required'), 'trim|required|xss_clean');
         $this->form_validation->set_rules('email', $this->lang->line('required'), 'trim|required|valid_email|xss_clean');
         $this->form_validation->set_rules('firstname', $this->lang->line('required'), 'trim|required|xss_clean');
         $this->form_validation->set_rules('lastname', $this->lang->line('required'), 'trim|required|xss_clean');
         $this->form_validation->set_rules('gender', $this->lang->line('genrequiredder'), 'trim|required|xss_clean');
         $this->form_validation->set_rules('dob', $this->lang->line('required'), 'trim|required|xss_clean');
         $this->form_validation->set_rules('class_id', $this->lang->line('required'), 'trim|required|xss_clean');
         $this->form_validation->set_rules('preferred_education_mode', $this->lang->line('required'), 'trim|required|xss_clean');
         $this->form_validation->set_rules('has_siblings_enrolled', $this->lang->line('required'), 'trim|required|xss_clean');

         $this->form_validation->set_rules('payment_scheme', $this->lang->line('required'), 'trim|required|xss_clean');
         // // $this->form_validation->set_rules('accountid', $this->lang->line('required'), 'trim|required|xss_clean');

         // // if (empty($_FILES['document']['name']))
         // // {
         // //     $this->form_validation->set_rules('document', $this->lang->line('document'), 'required');
         // // }            

         if ($this->form_validation->run() == false) {
            // print_r('this is test');die();
            $this->load_theme('pages/admission');
         } else {
            // $sibling_name = $this->input->post("sibling_name");
            // $sibling_age = $this->input->post("sibling_age");
            // $sibling_civil_status = $this->input->post("sibling_civil_status");
            // $sibling_glo = $this->input->post("sibling_glo");
            // $sibling_nsc = $this->input->post("sibling_nsc");                

            // $this->addStudentSiblings("", $sibling_name, $sibling_age, $sibling_civil_status, $sibling_glo, $sibling_nsc);

            //==============
            $document_validate = true;
            // $file_validate    = $this->config->item('file_validate');
            $image_validate = $this->config->item('file_validate');

            $admission_docs = $this->reArrayFilesMultiple();

            if (isset($admission_docs)) {
               if (sizeof($FILES) < 6) {
                  foreach ($admission_docs as $key0 => $FILES) {
                     for ($i = 0; $i < sizeof($FILES); $i++) {
                        // var_dump($FILES[$i]["name"]);
                        // echo "<BR>";
                        if (!empty($FILES[$i]["name"])) {
                           $file_type         = $FILES[$i]['type'];
                           $file_size         = $FILES[$i]["size"];
                           $file_name         = $FILES[$i]["name"];
                           $allowed_extension = $image_validate['allowed_extension'];
                           $ext               = pathinfo($file_name, PATHINFO_EXTENSION);
                           $allowed_mime_type = $image_validate['allowed_mime_type'];
                           $finfo = finfo_open(FILEINFO_MIME_TYPE);
                           $mtype = finfo_file($finfo, $FILES[$i]['tmp_name']);
                           finfo_close($finfo);

                           if (!in_array($mtype, $allowed_mime_type)) {
                              $this->data['error_message'] = 'File Type Not Allowed';
                              $document_validate = false;
                              break;
                           }
                           if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                              $this->data['error_message'] = 'Extension Not Allowed';
                              $document_validate = false;
                              break;
                           }
                           if ($file_size > $image_validate['upload_size']) {
                              $this->data['error_message'] = 'Files should be less than' . number_format($image_validate['upload_size'] / 1048576, 2) . " MB";
                              $document_validate = false;
                              break;
                           }
                        }
                     }
                  }
               } else
                  $this->data['error_message'] = 'Only maximum of 5 files is allowed';
            }

            // var_dump($admission_docs);die;

            //=====================
            if ($document_validate) {
               //-- Get siblings (new)                   

               $class_id   = $this->input->post('class_id');
               $section_id = $this->onlinestudent_model->GetSectionID('No Section'); //--Assign "No Section" for online admissions

               //--Get Class_Section_ID
               $class_section_id = $this->onlinestudent_model->GetClassSectionID($class_id, $section_id);
               $current_session = $this->setting_model->getCurrentSession();
               $accountID = $this->input->post('accountid');
               // var_dump($accountID);die;

               if ($enrollment_type == 'old') {
                  //$idnum = $this->input->post('studentidnumber');
                  // $old_student_data = $this->student_model->GetStudentByRollNo($this->input->post('studentidnumber'));                        

                  if (!empty($accountID)) {
                     $old_student_data = $this->student_model->GetStudentByID($accountID);
                     $has_admission = $this->onlinestudent_model->HasPendingAdmission($old_student_data->firstname, $old_student_data->lastname, date('Y-m-d', strtotime($old_student_data->dob)));
                     $datenow = date("Y-m-d");

                     $data = array(
                        'roll_no'             => $old_student_data->roll_no,
                        'lrn_no'              => $old_student_data->lrn_no,
                        'firstname'           => $old_student_data->firstname,
                        'lastname'            => $old_student_data->lastname,
                        'mobileno'            => $old_student_data->mobileno,
                        'guardian_is'         => $old_student_data->guardian_is,
                        'dob'                 => date('Y-m-d', strtotime($this->input->post('dob'))),
                        'current_address'     => $old_student_data->current_address,
                        'permanent_address'   => $old_student_data->permanent_address,
                        'father_name'         => $old_student_data->father_name,
                        'father_phone'        => $old_student_data->father_phone,
                        'father_occupation'   => $old_student_data->father_occupation,
                        'mother_name'         => $old_student_data->mother_name,
                        'mother_phone'        => $old_student_data->mother_phone,
                        'mother_occupation'   => $old_student_data->mother_occupation,
                        'guardian_occupation' => $old_student_data->guardian_occupation,
                        'guardian_email'      => $old_student_data->guardian_email == '' ? $this->input->post('email') : $old_student_data->guardian_email,
                        'gender'              => $this->input->post('gender') != '' ? $this->input->post('gender') : $old_student_data->gender,
                        'guardian_name'       => $old_student_data->guardian_name,
                        'guardian_relation'   => $old_student_data->guardian_relation,
                        'guardian_phone'      => $old_student_data->guardian_phone,
                        'guardian_address'    => $old_student_data->guardian_address,
                        'admission_date'      => date('Y-m-d', strtotime($datenow)),
                        'measurement_date'    => date('Y-m-d', strtotime($datenow)),
                        'mode_of_payment'     => $this->input->post('mode_of_payment'),
                        'enrollment_type'     => $enrollment_type,
                        'middlename'          => $old_student_data->middlename,
                        'email'               => $this->input->post('email'),
                        'class_section_id'    => $class_section_id,

                        'father_company_name'              => $old_student_data->father_company_name,
                        'father_company_position'          => $old_student_data->father_company_position,
                        'father_nature_of_business'        => $old_student_data->father_nature_of_business,
                        'father_mobile'                    => $old_student_data->father_mobile,
                        'father_dob'                       => date('Y-m-d', strtotime($old_student_data->father_dob)),
                        'father_citizenship'               => $old_student_data->father_citizenship,
                        'father_religion'                  => $old_student_data->father_religion,
                        'father_highschool'                => $old_student_data->father_highschool,
                        'father_college'                   => $old_student_data->father_college,
                        'father_college_course'            => $old_student_data->father_college_course,
                        'father_post_graduate'             => $old_student_data->father_post_graduate,
                        'father_post_course'               => $old_student_data->father_post_course,
                        'father_prof_affiliation'          => $old_student_data->father_prof_affiliation,
                        'father_prof_affiliation_position' => $old_student_data->father_prof_affiliation_position,
                        'father_tech_prof'                 => $old_student_data->father_tech_prof,
                        'father_tech_prof_other'           => $old_student_data->father_tech_prof_other,

                        'mother_company_name'              => $old_student_data->mother_company_name,
                        'mother_company_position'          => $old_student_data->mother_company_position,
                        'mother_nature_of_business'        => $old_student_data->mother_nature_of_business,
                        'mother_mobile'                    => $old_student_data->mother_mobile,
                        'mother_dob'                       => date('Y-m-d', strtotime($old_student_data->mother_dob)),
                        'mother_citizenship'               => $old_student_data->mother_citizenship,
                        'mother_religion'                  => $old_student_data->mother_religion,
                        'mother_highschool'                => $old_student_data->mother_highschool,
                        'mother_college'                   => $old_student_data->mother_college,
                        'mother_college_course'            => $old_student_data->mother_college_course,
                        'mother_post_graduate'             => $old_student_data->mother_post_graduate,
                        'mother_post_course'               => $old_student_data->mother_post_course,
                        'mother_prof_affiliation'          => $old_student_data->mother_prof_affiliation,
                        'mother_prof_affiliation_position' => $old_student_data->mother_prof_affiliation_position,
                        'mother_tech_prof'                 => $old_student_data->mother_tech_prof,
                        'mother_tech_prof_other'           => $old_student_data->mother_tech_prof_other,

                        'marriage'                   => $old_student_data->marriage,
                        'dom'                        => date('Y-m-d', strtotime($old_student_data->dom)),
                        'church'                     => $old_student_data->church,
                        'family_together'            => $old_student_data->family_together,
                        'parents_away'               => $old_student_data->parents_away,
                        'parents_away_state'         => $old_student_data->parents_away_state,
                        'parents_civil_status'       => $old_student_data->parents_civil_status,
                        'parents_civil_status_other' => $old_student_data->parents_civil_status_other,

                        'session_id' => $current_session,
                        'guardian_address_is_current_address' => $old_student_data->guardian_address_is_current_address,
                        'permanent_address_is_current_address' => $old_student_data->permanent_address_is_current_address,
                        'living_with_parents' => $old_student_data->living_with_parents,
                        'living_with_parents_specify' => $old_student_data->living_with_parents_specify,

                        // 'has_siblings_enrolled' => $old_student_data->has_siblings_enrolled,
                        // 'siblings_specify' => $old_student_data->siblings_specify,
                        'has_siblings_enrolled' => $this->input->post('has_siblings_enrolled'),
                        'siblings_specify' => $this->input->post('siblings_specify'),
                        'preferred_education_mode' => $this->input->post('preferred_education_mode'),

                        'payment_scheme' => $this->input->post('payment_scheme'),

                        //-- Feb. 4, 2021
                        'has_special_needs' => $old_student_data->has_special_needs,
                        'has_assistive_device' => $old_student_data->has_assistive_device,
                        'general_health_condition' => $old_student_data->general_health_condition,
                        'health_complaints' => $old_student_data->health_complaints,
                        'father_work_from_home' => $old_student_data->father_work_from_home,
                        'mother_work_from_home' => $old_student_data->mother_work_from_home,
                        'guardian_work_from_home' => $old_student_data->guardian_work_from_home,
                        'family_pppp' => $old_student_data->family_pppp,
                     );

                     if (isset($admission_docs)) {
                        $doc_names = "";
                        for ($i = 0; $i < sizeof($FILES); $i++) {
                           if (!empty($FILES[$i]["name"])) {
                              $file_name = $FILES[$i]["name"];
                              $time = md5($file_name . microtime());
                              $fileInfo = pathinfo($file_name);
                              $doc_name = str_replace(",", "_", $fileInfo['filename']) . '_' . $time . '.' . $fileInfo['extension'];

                              // move_uploaded_file($FILES[$i]["tmp_name"], "./uploads/student_documents/online_admission_doc/" . $doc_name);
                              $this->load->library('s3');
                              $s3 = new S3(AWS_ACCESS_KEY_ID, AWS_ACCESS_KEY_SECRET, false, S3_URI, AWS_REGION);
                              $dest_file = $_SESSION['School_Code'] . "/uploads/student_documents/online_admission_doc/" . $doc_name;
                              $s3->putObjectFile($FILES[$i]["tmp_name"], S3_BUCKET, $dest_file, S3::ACL_PUBLIC_READ);

                              $doc_names .= $doc_names != "" ? "|" . $doc_name : $doc_name;
                           }
                        }

                        $data['document'] = $doc_names;
                     }

                     if (!isset($has_admission)) {
                        $insert_id = $this->onlinestudent_model->add($data);
                        $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('admission_success') . '</div>');
                     } else {
                        if ((int)$current_session > (int)$has_admission->session_id && (int)$old_student_data->session_id != (int)$current_session) {
                           $insert_id = $this->onlinestudent_model->add($data);
                           $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('admission_success') . '</div>');
                        } else {
                           if ((int)$old_student_data->session_id == (int)$current_session)
                              $this->session->set_flashdata('msg', '<div class="alert alert-info">' . $old_student_data->firstname . ' ' . $old_student_data->lastname . ' ' . $this->lang->line('already_enrolled') . '</div>');
                           else if ($has_admission->is_enroll == '0') {
                              // var_dump($has_admission);
                              // echo "<BR>".isset($has_admission)."<BR>".$current_session."<BR>";
                              // var_dump($old_student_data);
                              // die;
                              $this->session->set_flashdata('msg', '<div class="alert alert-info">' . $old_student_data->firstname . ' ' . $old_student_data->lastname . ' ' . $this->lang->line('has_pending_admission') . '</div>');
                           }
                        }
                     }

                     //--Send acknowledgement mail
                     $sender_details = array(
                        'admission_date' => date("Y-m-d"),
                        'firstname' => $old_student_data->firstname,
                        'lastname' => $old_student_data->lastname,
                        'guardian_name' => $old_student_data->guardian_name,
                        'email' => $old_student_data->guardian_email == '' ? $this->input->post('guardian_email') : $old_student_data->guardian_email
                     );
                     // var_dump($sender_details);die();
                     $this->mailsmsconf->mailsms('online_admission', $sender_details);

                     redirect(site_url('lms/survey/respond/lms_survey_offline_159146294820546101'));
                  } else
                     $this->session->set_flashdata('msg', '<div class="alert alert-info">We have received an incomplete data. Please try to do the admission again. We are sorry for your inconvinience.</div>');
               } else {
                  $alreadyEnrolled = $this->student_model->AlreadyEnrolled($this->input->post('firstname'), $this->input->post('lastname'), date('Y-m-d', strtotime($this->input->post('dob'))));

                  if (!$alreadyEnrolled) {
                     $has_admission = $this->onlinestudent_model->HasPendingAdmission($this->input->post('firstname'), $this->input->post('lastname'), date('Y-m-d', strtotime($this->input->post('dob'))));
                     $datenow = date("Y-m-d");

                     // $studentid = $this->onlinestudent_model->GetStudentIDNumber($accountID);
                     // var_dump($studentid);die;

                     $data = array(
                        'firstname'           => $this->input->post('firstname'),
                        'lastname'            => $this->input->post('lastname'),
                        'mobileno'            => $this->input->post('mobileno'),
                        'guardian_is'         => $this->input->post('guardian_is'),
                        'dob'                 => date('Y-m-d', strtotime($this->input->post('dob'))), // $this->customlib->dateFormatToYYYYMMDD($this->input->post('dob')),
                        'current_address'     => $this->input->post('current_address'),
                        'permanent_address'   => $this->input->post('permanent_address'),
                        'father_name'         => $this->input->post('father_name'),
                        'father_phone'        => $this->input->post('father_phone'),
                        'father_occupation'   => $this->input->post('father_occupation'),
                        'mother_name'         => $this->input->post('mother_name'),
                        'mother_phone'        => $this->input->post('mother_phone'),
                        'mother_occupation'   => $this->input->post('mother_occupation'),
                        'guardian_occupation' => $this->input->post('guardian_occupation'),
                        'guardian_email'      => $this->input->post('guardian_email'),
                        'gender'              => $this->input->post('gender'),
                        'guardian_name'       => $this->input->post('guardian_name'),
                        'guardian_relation'   => $this->input->post('guardian_relation'),
                        'guardian_phone'      => $this->input->post('guardian_phone'),
                        'guardian_address'    => $this->input->post('guardian_address'),
                        'admission_date'      => date('Y-m-d', strtotime($datenow)), // $this->customlib->dateFormatToYYYYMMDD($this->input->post('datenow')),
                        'measurement_date'    => date('Y-m-d', strtotime($datenow)), // $this->customlib->dateFormatToYYYYMMDD($this->input->post('datenow')),
                        'mode_of_payment'     => $this->input->post('mode_of_payment'),
                        'enrollment_type'     => $enrollment_type,
                        'middlename'          => $this->input->post('middlename'),
                        'email'               => $this->input->post('email'),
                        'class_section_id'    => $class_section_id,
                        'roll_no'             => $enrollment_type == "old_new" ? ($accountID != null ? $this->onlinestudent_model->GetStudentIDNumber($accountID) : $this->onlinestudent_model->GetStudentIDNumberByName($this->input->post('firstname'), $this->input->post('lastname'))) : "",
                        // 'roll_no'             => $enrollment_type == "old_new" ? $this->onlinestudent_model->GetStudentIDNumberByName($this->input->post('firstname'), $this->input->post('lastname')) : "",
                        // 'roll_no'             => $accountID,
                        'lrn_no'              => $this->input->post('lrn_no'),

                        'father_company_name'              => $this->input->post('father_company_name'),
                        'father_company_position'          => $this->input->post('father_company_position'),
                        'father_nature_of_business'        => $this->input->post('father_nature_of_business'),
                        'father_mobile'                    => $this->input->post('father_mobile'),
                        'father_dob'                       => date('Y-m-d', strtotime($this->input->post('father_dob'))), // $this->customlib->dateFormatToYYYYMMDD($this->input->post('father_dob')),
                        'father_citizenship'               => $this->input->post('father_citizenship'),
                        'father_religion'                  => $this->input->post('father_religion'),
                        'father_highschool'                => $this->input->post('father_highschool'),
                        'father_college'                   => $this->input->post('father_college'),
                        'father_college_course'            => $this->input->post('father_college_course'),
                        'father_post_graduate'             => $this->input->post('father_post_graduate'),
                        'father_post_course'               => $this->input->post('father_post_course'),
                        'father_prof_affiliation'          => $this->input->post('father_prof_affiliation'),
                        'father_prof_affiliation_position' => $this->input->post('father_prof_affiliation_position'),
                        'father_tech_prof'                 => $this->input->post('father_tech_prof'),
                        'father_tech_prof_other'           => $this->input->post('father_tech_prof_other'),

                        'mother_company_name'              => $this->input->post('mother_company_name'),
                        'mother_company_position'          => $this->input->post('mother_company_position'),
                        'mother_nature_of_business'        => $this->input->post('mother_nature_of_business'),
                        'mother_mobile'                    => $this->input->post('mother_mobile'),
                        'mother_dob'                       => date('Y-m-d', strtotime($this->input->post('mother_dob'))), // $this->customlib->dateFormatToYYYYMMDD($this->input->post('mother_dob')),
                        'mother_citizenship'               => $this->input->post('mother_citizenship'),
                        'mother_religion'                  => $this->input->post('mother_religion'),
                        'mother_highschool'                => $this->input->post('mother_highschool'),
                        'mother_college'                   => $this->input->post('mother_college'),
                        'mother_college_course'            => $this->input->post('mother_college_course'),
                        'mother_post_graduate'             => $this->input->post('mother_post_graduate'),
                        'mother_post_course'               => $this->input->post('mother_post_course'),
                        'mother_prof_affiliation'          => $this->input->post('mother_prof_affiliation'),
                        'mother_prof_affiliation_position' => $this->input->post('mother_prof_affiliation_position'),
                        'mother_tech_prof'                 => $this->input->post('mother_tech_prof'),
                        'mother_tech_prof_other'           => $this->input->post('mother_tech_prof_other'),

                        'marriage'                   => $this->input->post('marriage'),
                        'dom'                        => date('Y-m-d', strtotime($this->input->post('dom'))), // $this->customlib->dateFormatToYYYYMMDD($this->input->post('dom')),
                        'church'                     => $this->input->post('church'),
                        'family_together'            => $this->input->post('family_together'),
                        'parents_away'               => $this->input->post('parents_away'),
                        'parents_away_state'         => $this->input->post('parents_away_state'),
                        'parents_civil_status'       => $this->input->post('parents_civil_status'),
                        'parents_civil_status_other' => $this->input->post('parents_civil_status_other'),
                        'session_id' => $current_session,
                        'guardian_address_is_current_address' => $this->input->post('guardian_address_is_current_address') == "on" ? 1 : 0,
                        'permanent_address_is_current_address' => $this->input->post('permanent_address_is_current_address') == "on" ? 1 : 0,
                        'living_with_parents' => $this->input->post('living_with_parents'),
                        'living_with_parents_specify' => $this->input->post('living_with_parents_specify'),
                        'has_siblings_enrolled' => $this->input->post('has_siblings_enrolled'),
                        'siblings_specify' => $this->input->post('siblings_specify'),
                        'preferred_education_mode' => $this->input->post('preferred_education_mode'),
                        'payment_scheme' => $this->input->post('payment_scheme'),

                        //-- March 4, 2021
                        'birth_place' => $this->input->post('birth_place'),
                        'present_school' => $this->input->post('present_school'),
                        'present_school_address' => $this->input->post('present_school_address'),
                        'age_as_of' => $this->input->post('age_as_of'),
                        'nationality' => $this->input->post('nationality'),
                        'esc_grantee' => $this->input->post('esc_grantee'),
                        'voucher_recipient' => $this->input->post('voucher_recipient'),

                        'enrolled_here_before' => $this->input->post('enrolled_here_before'),
                        'enrolled_here_before_year' => $this->input->post('enrolled_here_before_year'),
                        'enrolled_here_before_level' => $this->input->post('enrolled_here_before_level'),
                        'parents_alumnus' => $this->input->post('parents_alumnus'),
                        'father_alumnus_batch_gs' => $this->input->post('father_alumnus_batch_gs'),
                        'mother_alumnus_batch_gs' => $this->input->post('mother_alumnus_batch_gs'),
                        'mother_alumnus_batch_hs' => $this->input->post('mother_alumnus_batch_hs'),
                        'has_internet' => $this->input->post('has_internet'),
                        'type_of_internet' => $this->input->post('type_of_internet'),

                        'has_special_needs' => $this->input->post('has_special_needs'),
                        'has_assistive_device' => $this->input->post('has_assistive_device'),
                        'general_health_condition' => $this->input->post('general_health_condition'),
                        'health_complaints' => $this->input->post('health_complaints'),
                        'father_work_from_home' => $this->input->post('father_work_from_home'),
                        'mother_work_from_home' => $this->input->post('mother_work_from_home'),
                        'guardian_work_from_home' => $this->input->post('guardian_work_from_home'),
                        'family_pppp' => $this->input->post('family_pppp'),
                     );

                     // echo "<pre>"; print_r(json_encode($data)); echo "</pre>"; die(); 

                     if (isset($admission_docs)) {
                        $doc_names = "";
                        for ($i = 0; $i < sizeof($FILES); $i++) {
                           if (!empty($FILES[$i]["name"])) {
                              $file_name = $FILES[$i]["name"];
                              $time = md5($file_name . microtime());
                              $fileInfo = pathinfo($file_name);
                              $doc_name = $fileInfo['filename'] . '_' . $time . '.' . $fileInfo['extension'];

                              // move_uploaded_file($FILES[$i]["tmp_name"], "./uploads/student_documents/online_admission_doc/" . $doc_name);
                              $this->load->library('s3');
                              $s3 = new S3(AWS_ACCESS_KEY_ID, AWS_ACCESS_KEY_SECRET, false, S3_URI, AWS_REGION);
                              $dest_file = $_SESSION['School_Code'] . "/uploads/student_documents/online_admission_doc/" . $doc_name;
                              $s3->putObjectFile($FILES[$i]["tmp_name"], S3_BUCKET, $dest_file, S3::ACL_PUBLIC_READ);

                              $doc_names .= $doc_names != "" ? "," . $doc_name : $doc_name;
                           }
                        }

                        $data['document'] = $doc_names;
                     }

                     if (!isset($has_admission)) {
                        $sibling_name = $this->input->post("sibling_name");
                        $sibling_age = $this->input->post("sibling_age");
                        $sibling_civil_status = $this->input->post("sibling_civil_status");
                        $sibling_glo = $this->input->post("sibling_glo");
                        $sibling_nsc = $this->input->post("sibling_nsc");
                        $sibling_dec = $this->input->post("sibling_dec");
                        $data['siblings'] = $this->addStudentSiblings($sibling_name, $sibling_age, $sibling_civil_status, $sibling_glo, $sibling_nsc, $sibling_dec);

                        $insert_id = $this->onlinestudent_model->add($data);

                        //-- Get siblings (new)
                        // $sibling_name = $this->input->post("sibling_name");
                        // $sibling_age = $this->input->post("sibling_age");
                        // $sibling_civil_status = $this->input->post("sibling_civil_status");
                        // $sibling_glo = $this->input->post("sibling_glo");
                        // $sibling_nsc = $this->input->post("sibling_nsc");
                        // $this->addStudentSiblings($insert_id, $sibling_name, $sibling_age, $sibling_civil_status, $sibling_glo, $sibling_nsc);

                        $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('admission_success') . '</div>');
                     } else {
                        if ($current_session > (int)$has_admission->session_id) {
                           $insert_id = $this->onlinestudent_model->add($data);
                           $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('admission_success') . '</div>');
                        } else {
                           if ($has_admission->is_enroll == '0')
                              $this->session->set_flashdata('msg', '<div class="alert alert-info">' . $has_admission->firstname . ' ' . $has_admission->lastname . ' ' . $this->lang->line('has_pending_admission') . '</div>');
                           else
                              $this->session->set_flashdata('msg', '<div class="alert alert-info">Your child ' . $has_admission->firstname . ' ' . $has_admission->lastname . ' ' . $this->lang->line('already_enrolled') . '</div>');
                        }
                     }

                     //--Send acknowledgement mail
                     $sender_details = array(
                        'admission_date' => date("Y-m-d"),
                        'firstname' => $this->input->post('firstname'),
                        'lastname' => $this->input->post('lastname'),
                        'guardian_name' => $this->input->post('guardian_name'),
                        'email' => $this->input->post('guardian_email'),
                        'school_name' => $this->setting_model->getCurrentSchoolName()
                     );
                     // var_dump($sender_details);die;
                     $this->mailsmsconf->mailsms('online_admission', $sender_details);

                     redirect(site_url('lms/survey/respond/lms_survey_offline_159146294820546101'));
                  } else
                     $this->session->set_flashdata('msg', '<div class="alert alert-info">Your child ' . $this->input->post('firstname') . ' ' . $this->input->post('lastname') . ' ' . $this->lang->line('already_enrolled') . '</div>');
               }

               redirect($_SERVER['HTTP_REFERER'], 'refresh');
            } else
               $this->session->set_flashdata('msg', '<div class="alert alert-info">' . $this->data['error_message'] . '</div>');

            $this->load_theme('pages/admission');
         }
      }
   }

   // function addStudentSiblings($admissionid, $name, $age, $civilstatus, $gradeoccupation, $schoolcompany) {
   //     $maindata = [];

   //     for($i = 0; $i < count($name); $i++) {
   //         $data = [];
   //         $id = "admission_sibling_".$this->mode."_".microtime(true)*10000;
   //         $id = $id.rand(1000,9999);

   //         $data = array(
   //             "id" => $id,
   //             "student_admission_id" => $admissionid,
   //             "name" => $name[$i],
   //             "age" => $age[$i],
   //             "civil_status" => $civilstatus[$i],
   //             "grade_occupation" => $gradeoccupation[$i],
   //             "school_company_name" => $schoolcompany[$i],
   //         );

   //         if (!empty($name[$i]))
   //             array_push($maindata, $data);
   //     }

   //     // echo "<pre>"; print_r($maindata); echo"<pre>";die();

   //     $this->onlinestudent_model->AddStudentSiblings($admissionid, $maindata);
   // }

   function addStudentSiblings($name, $age, $civilstatus, $gradeoccupation, $schoolcompany, $deceased)
   {
      $maindata = [];

      for ($i = 0; $i < count($name); $i++) {
         $data = [];
         $id = "admission_sibling_" . $this->mode . "_" . microtime(true) * 10000;
         $id = $id . rand(1000, 9999);

         $data = array(
            "id" => $id,
            "name" => $name[$i],
            "age" => $age[$i],
            "civil_status" => $civilstatus[$i],
            "grade_occupation" => $gradeoccupation[$i],
            "school_company_name" => $schoolcompany[$i],
            "deceased" => $deceased[$i] == "on" ? 1 : 0,
         );

         if (!empty($name[$i]))
            array_push($maindata, $data);
      }

      return json_encode($maindata);

      // echo "<pre>"; print_r($maindata); echo"<pre>";die();        
      // $this->onlinestudent_model->AddStudentSiblings($admissionid, $maindata);
   }

   public function survey()
   {
   }

   public function GetStudentDetails($idnumber)
   {
      $data = $this->student_model->GetStudentInfo($idnumber);
      echo json_encode($data);
   }

   public function AutoCompleteStudentNameForAdmission()
   {
      $returnData = array();
      $results = array('error' => false, 'data' => '');
      $name = $_GET['search'];
      $names = $this->student_model->GetNameListAdmission($name);

      if (empty($names))
         $results['error'] = true;
      else {
         if (!empty($names)) {
            foreach ($names as $row)
               $returnData[] = array("value" => $row['id'], "label" => $row['studentname']);
         }
      }

      // Return results as json encoded array
      echo json_encode($returnData);
      die;
   }

   public function GetStudentDetailsByID($idnumber)
   {
      $data = $this->student_model->GetStudentByID($idnumber);
      echo json_encode($data);
   }
}

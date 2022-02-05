<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

// require 'vendor/autoload.php';

// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Report extends Admin_Controller
{

   function __construct()
   {
      parent::__construct();

      $this->time = strtotime(date('d-m-Y H:i:s'));

      $this->payment_mode = $this->customlib->payment_mode();
      $this->load->model('gradereport_model');
      $this->load->model('general_model');
      $this->load->model('setting_model');
      $this->load->model('student_model');
      $this->load->model('studentfee_model');
      $this->load->model('feecategory_model');
      $this->load->model('feetype_model');
      $this->load->model('exam_model');
      $this->load->model('section_model');
      $this->load->model('examschedule_model');
      $this->load->model('class_model');
      $this->load->model('category_model');
      $this->load->model('expense_model');
      $this->load->model('examresult_model');
      $this->load->model('subjecttimetable_model');
      $this->load->model('onlineexam_model');
      $this->load->model('studentfeemaster_model');
      $this->load->model('bookissue_model');
      $this->load->model('book_model');
      $this->load->model('onlineexamresult_model');
      $this->load->model('itemstock_model');
      $this->load->model('itemissue_model');
      $this->load->model('income_model');
      $this->load->model('expense_model');
      $this->load->model('incomehead_model');
      $this->load->model('payroll_model');
      $this->load->model('expensehead_model');
      $this->load->model('staff_model');
      $this->load->model('leavetypes_model');
      $this->load->model('role_model');
      $this->load->model('designation_model');
      $this->load->model('customfield_model');
      $this->load->model('stuattendence_model');
      $this->load->model('session_model');
      $this->load->model('subject_model');
      $this->load->model('conduct_model');
      $this->load->model('lesson_model');
      $this->load->model('classteacher_model');
      $this->load->model('grading_ssapamp_model');
      $this->load->model('grading_studentgrade_ssapamp_model');
      $this->load->model('grading_checklist_ssapamp_model');
      $this->load->model('grading_conduct_ssapamp_model');
      $this->load->model('grading_studentconduct_ssapamp_model');

      $this->search_type = $this->customlib->get_searchtype();
      $this->sch_setting_detail = $this->setting_model->getSetting();
   }

   function pdfStudentFeeRecord()
   {
      $data = [];
      $class_id = $this->uri->segment(3);
      $section_id = $this->uri->segment(4);
      $student_id = $this->uri->segment(5);
      $student = $this->student_model->get($student_id);
      $setting_result = $this->setting_model->get();
      $data['settinglist'] = $setting_result;
      $data['student'] = $student;
      $student_due_fee = $this->studentfee_model->getDueFeeBystudent($class_id, $section_id, $student_id);
      $data['student_due_fee'] = $student_due_fee;
      $html = $this->load->view('reports/students_detail', $data, true);
      $pdfFilePath = $this->time . ".pdf";
      $this->fontdata = array(
         "opensans" => array(
            'R' => "OpenSans-Regular.ttf",
            'B' => "OpenSans-Bold.ttf",
            'I' => "OpenSans-Italic.ttf",
            'BI' => "OpenSans-BoldItalic.ttf",
         ),
      );
      $this->load->library('m_pdf');
      $this->m_pdf->pdf->WriteHTML($html);
      $this->m_pdf->pdf->Output($pdfFilePath, "D");
   }

   function pdfByInvoiceNo()
   {
      $data = [];
      $invoice_id = $this->uri->segment(3);
      $setting_result = $this->setting_model->get();
      $data['settinglist'] = $setting_result;
      $student_due_fee = $this->studentfee_model->getFeeByInvoice($invoice_id);
      $data['student_due_fee'] = $student_due_fee;
      $html = $this->load->view('reports/pdfinvoiceno', $data, true);
      $pdfFilePath = $this->time . ".pdf";
      $this->load->library('m_pdf');
      $this->m_pdf->pdf->WriteHTML($html);
      $this->m_pdf->pdf->Output($pdfFilePath, "D");
   }

   function pdfDepositeFeeByStudent($id)
   {
      $data = [];
      $data['title'] = 'Student Detail';
      $student = $this->student_model->get($id);
      $setting_result = $this->setting_model->get();
      $data['settinglist'] = $setting_result;
      $student_fee_history = $this->studentfee_model->getStudentFees($id);
      $data['student_fee_history'] = $student_fee_history;
      $data['student'] = $student;
      $array = array();
      $feecategory = $this->feecategory_model->get();
      foreach ($feecategory as $key => $value) {
         $dataarray = array();
         $value_id = $value['id'];
         $dataarray[$value_id] = $value['category'];
         $category = $value['category'];
         $datatype = array();
         $data_fee_type = array();
         $feetype = $this->feetype_model->getFeetypeByCategory($value['id']);
         foreach ($feetype as $feekey => $feevalue) {
            $ftype = $feevalue['id'];
            $datatype[$ftype] = $feevalue['type'];
         }
         $data_fee_type[] = $datatype;
         $dataarray[$category] = $datatype;
         $array[] = $dataarray;
      }
      $data['category_array'] = $array;
      $data['feecategory'] = $feecategory;
      $html = $this->load->view('reports/pdfStudentDeposite', $data, true);
      $pdfFilePath = $this->time . ".pdf";
      $this->load->library('m_pdf');
      $this->m_pdf->pdf->WriteHTML($html);
      $this->m_pdf->pdf->Output($pdfFilePath, "D");
   }

   function pdfStudentListByText()
   {
      $data = [];
      $search_text = $this->uri->segment(3);
      $setting_result = $this->setting_model->get();
      $data['settinglist'] = $setting_result;
      $resultlist = $this->student_model->searchFullText($search_text);
      $data['resultlist'] = $resultlist;
      $html = $this->load->view('reports/pdfStudentListByText', $data, true);
      $pdfFilePath = $this->time . ".pdf";
      $this->load->library('m_pdf');
      $this->m_pdf->pdf->WriteHTML($html);
      $this->m_pdf->pdf->Output($pdfFilePath, "D");
   }

   function marksreport()
   {
      $setting_result = $this->setting_model->get();
      $data['settinglist'] = $setting_result;
      $exam_id = $this->uri->segment(3);
      $class_id = $this->uri->segment(4);
      $section_id = $this->uri->segment(5);
      $data['exam_id'] = $exam_id;
      $data['class_id'] = $class_id;
      $data['section_id'] = $section_id;
      $exam_arrylist = $this->exam_model->get($exam_id);
      $data['exam_arrylist'] = $exam_arrylist;
      $section = $this->section_model->getClassNameBySection($class_id, $section_id);
      $data['class'] = $section;
      $examSchedule = $this->examschedule_model->getDetailbyClsandSection($class_id, $section_id, $exam_id);
      $studentList = $this->student_model->searchByClassSection($class_id, $section_id);
      $data['examSchedule'] = array();
      if (!empty($examSchedule)) {
         $new_array = array();
         $data['examSchedule']['status'] = "yes";
         foreach ($studentList as $stu_key => $stu_value) {
            $array = array();
            $array['student_id'] = $stu_value['id'];
            $array['roll_no'] = $stu_value['roll_no'];
            $array['firstname'] = $stu_value['firstname'];
            $array['lastname'] = $stu_value['lastname'];
            $array['admission_no'] = $stu_value['admission_no'];
            $array['dob'] = $stu_value['dob'];
            $array['father_name'] = $stu_value['father_name'];
            $x = array();
            foreach ($examSchedule as $ex_key => $ex_value) {
               $exam_array = array();
               $exam_array['exam_schedule_id'] = $ex_value['id'];
               $exam_array['exam_id'] = $ex_value['exam_id'];
               $exam_array['full_marks'] = $ex_value['full_marks'];
               $exam_array['passing_marks'] = $ex_value['passing_marks'];
               $exam_array['exam_name'] = $ex_value['name'];
               $exam_array['exam_type'] = $ex_value['type'];
               $student_exam_result = $this->examresult_model->get_result($ex_value['id'], $stu_value['id']);
               if (empty($student_exam_result)) {
                  $data['examSchedule']['status'] = "no";
               } else {
                  $exam_array['attendence'] = $student_exam_result->attendence;
                  $exam_array['get_marks'] = $student_exam_result->get_marks;
               }
               $x[] = $exam_array;
            }
            $array['exam_array'] = $x;
            $new_array[] = $array;
         }
         $data['examSchedule']['result'] = $new_array;
      } else {
         $s = array('status' => 'no');
         $data['examSchedule'] = $s;
      }
      $html = $this->load->view('reports/marksreport', $data, true);
      $pdfFilePath = $this->time . ".pdf";
      $this->load->library('m_pdf');
      $this->m_pdf->pdf->WriteHTML($html);
      $this->m_pdf->pdf->Output($pdfFilePath, "D");
      $this->load->view('reports/marksreport', $data);
   }

   function pdfStudentListByClassSection()
   {
      $data = [];
      $class_id = $this->uri->segment(3);
      $section_id = $this->uri->segment(4);
      $setting_result = $this->setting_model->get();
      $section = $this->section_model->getClassNameBySection($class_id, $section_id);
      $data['class'] = $section;
      $data['settinglist'] = $setting_result;
      $resultlist = $this->student_model->searchByClassSection($class_id, $section_id);
      $data['resultlist'] = $resultlist;
      $html = $this->load->view('reports/pdfStudentListByClassSection', $data, true);
      $pdfFilePath = $this->time . ".pdf";
      $this->load->library('m_pdf');
      $this->m_pdf->pdf->WriteHTML($html);
      $this->m_pdf->pdf->Output($pdfFilePath, "D");
   }

   function pdfStudentListDifferentCriteria()
   {
      $data = [];
      $class_id = $this->input->get('class_id');
      $section_id = $this->input->get('section_id');
      $category_id = $this->input->get('category_id');
      $gender = $this->input->get('gender');
      $rte = $this->input->get('rte');
      $setting_result = $this->setting_model->get();
      $class = $this->class_model->get($class_id);
      $data['class'] = $class;
      if ($section_id != "") {
         $section = $this->section_model->getClassNameBySection($class_id, $section_id);
         $data['section'] = $section;
      }
      if ($gender != "") {
         $data['gender'] = $gender;
      }
      if ($rte != "") {
         $data['rte'] = $rte;
      }
      if ($category_id != "") {
         $category = $this->category_model->get($category_id);
         $data['category'] = $category;
      }
      $data['settinglist'] = $setting_result;
      $resultlist = $this->student_model->searchByClassSectionCategoryGenderRte($class_id, $section_id, $category_id, $gender, $rte);
      $data['resultlist'] = $resultlist;
      $html = $this->load->view('reports/pdfStudentListDifferentCriteria', $data, true);
      $pdfFilePath = $this->time . ".pdf";
      $this->load->library('m_pdf');
      $this->m_pdf->pdf->WriteHTML($html);
      $this->m_pdf->pdf->Output($pdfFilePath, "D");
   }

   function pdfStudentListByClass()
   {
      $data = [];
      $class_id = $this->uri->segment(3);
      $section_id = "";
      $setting_result = $this->setting_model->get();
      $section = $this->class_model->get($class_id);
      $data['class'] = $section;
      $data['settinglist'] = $setting_result;
      $resultlist = $this->student_model->searchByClassSection($class_id, $section_id);
      $data['resultlist'] = $resultlist;
      $html = $this->load->view('reports/pdfStudentListByClass', $data, true);
      $pdfFilePath = $this->time . ".pdf";
      $this->load->library('m_pdf');
      $this->m_pdf->pdf->WriteHTML($html);
      $this->m_pdf->pdf->Output($pdfFilePath, "D");
   }

   function transactionSearch()
   {
      $data = [];
      $date_from = $this->input->get('datefrom');
      $date_to = $this->input->get('dateto');
      $setting_result = $this->setting_model->get();
      $data['exp_title'] = 'Transaction From ' . $date_from . " To " . $date_to;
      $date_from = date('Y-m-d', $this->customlib->datetostrtotime($date_from));
      $date_to = date('Y-m-d', $this->customlib->datetostrtotime($date_to));
      $expenseList = $this->expense_model->search("", $date_from, $date_to);
      $feeList = $this->studentfee_model->getFeeBetweenDate($date_from, $date_to);
      $data['expenseList'] = $expenseList;
      $data['feeList'] = $feeList;
      $data['settinglist'] = $setting_result;
      $html = $this->load->view('reports/transactionSearch', $data, true);
      $pdfFilePath = $this->time . ".pdf";
      $this->load->library('m_pdf');
      $this->m_pdf->pdf->WriteHTML($html);
      $this->m_pdf->pdf->Output($pdfFilePath, "D");
   }

   function pdfExamschdule()
   {
      $data = [];
      $setting_result = $this->setting_model->get();
      $data['settinglist'] = $setting_result;
      $exam_id = $this->uri->segment(3);
      $section_id = $this->uri->segment(4);
      $class_id = $this->uri->segment(5);
      $class = $this->class_model->get($class_id);
      $data['class'] = $class;
      $examSchedule = $this->examschedule_model->getDetailbyClsandSection($class_id, $section_id, $exam_id);
      $section = $this->section_model->getClassNameBySection($class_id, $section_id);
      $data['section'] = $section;
      $data['examSchedule'] = $examSchedule;
      $exam = $this->exam_model->get($exam_id);
      $data['exam'] = $exam;
      $html = $this->load->view('reports/examSchedule', $data, true);
      $pdfFilePath = $this->time . ".pdf";
      $this->load->library('m_pdf');
      $this->m_pdf->pdf->WriteHTML($html);
      $this->m_pdf->pdf->Output($pdfFilePath, "D");
   }
   function get_betweendate($type)
   {

      $this->load->view('reports/betweenDate');
   }

   function class_subject()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/student_information');
      $this->session->set_userdata('subsub_menu', 'Reports/student_information/class_subject_report');
      $data['title'] = 'Add Fees Type';
      $data['searchlist'] = $this->search_type;
      $class = $this->class_model->get('', $classteacher = 'yes');
      $data['classlist'] = $class;
      $data['search_type'] = '';
      $data['class_id'] = $class_id  = $this->input->post('class_id');
      $data['section_id'] = $section_id = $this->input->post('section_id');
      $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

      if ($this->form_validation->run() == false) {
         $data['subjects'] = array();
      } else {
         $data['section_list'] = $this->section_model->getClassBySection($this->input->post('class_id'));

         $data['resultlist'] = $this->subjecttimetable_model->getSubjectByClassandSection($class_id, $section_id);

         $subject = array();
         foreach ($data['resultlist'] as $value) {
            $subject[$value->subject_id][] = $value;
         }

         $data['subjects'] = $subject;
      }

      $this->load->view('layout/header', $data);
      $this->load->view('reports/class_subject', $data);
      $this->load->view('layout/footer', $data);
   }

   function admission_report()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/student_information');
      $this->session->set_userdata('subsub_menu', 'Reports/student_information/admission_report');
      $data['title'] = 'Add Fees Type';
      $data['searchlist'] = $this->search_type;
      $data['sch_setting']        = $this->sch_setting_detail;
      $data['adm_auto_insert']    = $this->sch_setting_detail->adm_auto_insert;
      $searchterm = '';
      $class = $this->class_model->get();
      $data['classlist'] = $class;
      foreach ($data['classlist'] as $key => $value) {
         $carray[] = $value['id'];
      }
      if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

         $between_date = $this->customlib->get_betweendate($_POST['search_type']);
         $data['search_type'] = $search_type = $_POST['search_type'];
      } else {

         $between_date = $this->customlib->get_betweendate('this_year');
         $data['search_type'] = $search_type = '';
      }

      $from_date = date('Y-m-d', strtotime($between_date['from_date']));

      $to_date = date('Y-m-d', strtotime($between_date['to_date']));
      // echo '<pre>';print_r($to_date);exit();
      $condition = " date_format(online_admissions.admission_date,'%Y-%m-%d') between  '" . $from_date . "' and '" . $to_date . "'";
      $data['filter_label'] = date($this->customlib->getSchoolDateFormat(), strtotime($from_date)) . " To " . date($this->customlib->getSchoolDateFormat(), strtotime($to_date));
      $this->form_validation->set_rules('search_type', $this->lang->line('search') . " " . $this->lang->line('type'), 'trim|required|xss_clean');

      $data['classes'] = $this->general_model->get_classes();
      if ($this->form_validation->run() == false) {

         $data['resultlist'] = array();
      } else {

         $other_variables['gender'] = $_REQUEST['gender'];
         $other_variables['enrollment_payment_status'] = $_REQUEST['enrollment_payment_status'];
         $other_variables['class'] = $_REQUEST['class'];
         $data['other_variables'] = $other_variables;
         $data['resultlist'] = $this->student_model->admission_report_joe($searchterm, $carray, $condition, $other_variables);
      }



      $this->load->view('layout/header', $data);
      $this->load->view('reports/admission_report', $data);
      $this->load->view('layout/footer', $data);
   }

   function enrollment_report()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/student_information');
      $this->session->set_userdata('subsub_menu', 'Reports/student_information/enrollment_report');
      $data['title'] = 'Add Fees Type';
      $data['searchlist'] = $this->search_type;
      $data['sch_setting']        = $this->sch_setting_detail;
      $data['adm_auto_insert']    = $this->sch_setting_detail->adm_auto_insert;
      $searchterm = '';
      $class = $this->class_model->get();
      $data['classlist'] = $class;
      foreach ($data['classlist'] as $key => $value) {
         $carray[] = $value['id'];
      }
      if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

         $between_date = $this->customlib->get_betweendate($_POST['search_type']);
         $data['search_type'] = $search_type = $_POST['search_type'];
      } else {

         $between_date = $this->customlib->get_betweendate('this_year');
         $data['search_type'] = $search_type = '';
      }

      $from_date = date('Y-m-d', strtotime($between_date['from_date']));

      $to_date = date('Y-m-d', strtotime($between_date['to_date']));
      // echo '<pre>';print_r($to_date);exit();
      $condition = "date_format(students.enrollment_payment_date,'%Y-%m-%d') between  '" . $from_date . "' and '" . $to_date . "'";
      $data['filter_label'] = date($this->customlib->getSchoolDateFormat(), strtotime($from_date)) . " To " . date($this->customlib->getSchoolDateFormat(), strtotime($to_date));
      $this->form_validation->set_rules('search_type', $this->lang->line('search') . " " . $this->lang->line('type'), 'trim|required|xss_clean');

      $data['classes'] = $this->general_model->get_classes();
      if ($this->form_validation->run() == false) {

         $data['resultlist'] = array();
      } else {

         $other_variables['gender'] = $_REQUEST['gender'];
         $other_variables['enrollment_payment_status'] = $_REQUEST['enrollment_payment_status'];
         $other_variables['class'] = $_REQUEST['class'];
         $data['other_variables'] = $other_variables;
         $data['resultlist'] = $this->student_model->enrollment_report_joe($searchterm, $carray, $condition, $other_variables);
      }



      $this->load->view('layout/header', $data);
      $this->load->view('reports/enrollment_report', $data);
      $this->load->view('layout/footer', $data);
   }

   function enrollment_summary_report()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/student_information');
      $this->session->set_userdata('subsub_menu', 'Reports/student_information/enrollment_summary_report');
      $data['title'] = 'Add Fees Type';
      $data['searchlist'] = $this->search_type;
      $data['sch_setting']        = $this->sch_setting_detail;
      $data['adm_auto_insert']    = $this->sch_setting_detail->adm_auto_insert;
      $searchterm = '';
      $class = $this->class_model->get();
      $data['classlist'] = $class;
      foreach ($data['classlist'] as $key => $value) {
         $carray[] = $value['id'];
      }
      if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

         $between_date = $this->customlib->get_betweendate($_POST['search_type']);
         $data['search_type'] = $search_type = $_POST['search_type'];
      } else {

         $between_date = $this->customlib->get_betweendate('this_year');
         $data['search_type'] = $search_type = '';
      }

      $from_date = date('Y-m-d', strtotime($between_date['from_date']));

      $to_date = date('Y-m-d', strtotime($between_date['to_date']));
      // echo '<pre>';print_r($to_date);exit();
      $condition = "date_format(students.enrollment_payment_date,'%Y-%m-%d') between  '" . $from_date . "' and '" . $to_date . "'";
      $data['filter_label'] = date($this->customlib->getSchoolDateFormat(), strtotime($from_date)) . " To " . date($this->customlib->getSchoolDateFormat(), strtotime($to_date));
      $this->form_validation->set_rules('search_type', $this->lang->line('search') . " " . $this->lang->line('type'), 'trim|required|xss_clean');

      $data['classes'] = $this->general_model->get_classes();
      if ($this->form_validation->run() == false) {

         $data['resultlist'] = array();
      } else {

         $other_variables['gender'] = $_REQUEST['gender'];
         $other_variables['enrollment_payment_status'] = $_REQUEST['enrollment_payment_status'];
         $other_variables['class'] = $_REQUEST['class'];
         $data['other_variables'] = $other_variables;
         $data['resultlist'] = $this->student_model->enrollment_summary_report_joe($searchterm, $carray, $condition, $other_variables);
      }



      $this->load->view('layout/header', $data);
      $this->load->view('reports/enrollment_summary_report', $data);
      $this->load->view('layout/footer', $data);
   }

   function sibling_report()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/student_information');
      $this->session->set_userdata('subsub_menu', 'Reports/student_information/sibling_report');
      $data['title'] = 'Add Fees Type';
      $data['searchlist'] = $this->search_type;
      $data['sch_setting']        = $this->sch_setting_detail;
      $data['adm_auto_insert']    = $this->sch_setting_detail->adm_auto_insert;
      $searchterm = '';
      $condition = array();
      $class = $this->class_model->get('', $classteacher = 'yes');
      $data['classlist'] = $class;

      $data['class_id'] = $class_id  = $this->input->post('class_id');
      $data['section_id'] = $section_id = $this->input->post('section_id');
      $data['section_list'] = $this->section_model->getClassBySection($this->input->post('class_id'));


      if (isset($_POST['class_id']) && $_POST['class_id'] != '') {

         $condition['classes.id'] = $_POST['class_id'];
      }

      if (isset($_POST['section_id']) && $_POST['section_id'] != '') {

         $condition['sections.id'] = $_POST['section_id'];
      }



      $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

      if ($this->form_validation->run() == false) {
         $data['resultlist'] = array();
      } else {
         $data['sibling_list'] = $this->student_model->sibling_reportsearch($searchterm, $carray = null, $condition);

         $sibling_parent = array();

         foreach ($data['sibling_list'] as $value) {

            $sibling_parent[] = $value['parent_id'];
         }

         $data['resultlist'] = $this->student_model->sibling_report($searchterm, $carray = null);
         //  echo $this->db->last_query();die;
         //echo "<pre>"; print_r($data['resultlist']); echo "<pre>";die;
         $sibling = array();

         foreach ($data['resultlist'] as $value) {

            if (in_array($value['parent_id'], $sibling_parent)) {

               $sibling[$value['parent_id']][] = $value;
            }
         }
         $data['resultlist'] = $sibling;
      }



      $this->load->view('layout/header', $data);
      $this->load->view('reports/sibling_report', $data);
      $this->load->view('layout/footer', $data);
   }

   function onlinefees_report()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/finance');
      $this->session->set_userdata('subsub_menu', 'Reports/finance/onlinefees_report');
      $data['searchlist'] = $this->customlib->get_searchtype();
      $data['group_by'] = $this->customlib->get_groupby();


      if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

         $dates = $this->customlib->get_betweendate($_POST['search_type']);
         $data['search_type'] = $_POST['search_type'];
      } else {

         $dates = $this->customlib->get_betweendate('this_year');
         $data['search_type'] = '';
      }

      $collection = array();
      $start_date = date('Y-m-d', strtotime($dates['from_date']));
      $end_date = date('Y-m-d', strtotime($dates['to_date']));
      $this->form_validation->set_rules('search_type', $this->lang->line('search') . " " . $this->lang->line('type'), 'trim|required|xss_clean');


      if ($this->form_validation->run() == false) {

         $data['collectlist'] = array();
      } else {

         $data['collectlist'] = $this->studentfeemaster_model->getOnlineFeeCollectionReport($start_date, $end_date);
      }

      $this->load->view('layout/header', $data);
      $this->load->view('reports/onlineFeesReport', $data);
      $this->load->view('layout/footer', $data);
   }

   public function studentbookissuereport()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/library');
      $this->session->set_userdata('subsub_menu', 'Reports/library/book_issue_report');
      $data['searchlist'] = $this->customlib->get_searchtype();
      if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

         $dates = $this->customlib->get_betweendate($_POST['search_type']);
         $data['search_type'] = $_POST['search_type'];
      } else {

         $dates = $this->customlib->get_betweendate('this_year');
         $data['search_type'] = '';
      }

      if (isset($_POST['members_type']) && $_POST['members_type'] != '') {

         $data['member_id'] = $_POST['members_type'];
      } else {

         $data['member_id'] = '';
      }

      $data['members'] = array('' => $this->lang->line('all'), 'student' => $this->lang->line('student'), 'teacher' => $this->lang->line('teacher'));
      $start_date = date('Y-m-d', strtotime($dates['from_date']));
      $end_date = date('Y-m-d', strtotime($dates['to_date']));
      $data['label'] = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));

      $data['issued_books'] = $this->bookissue_model->studentBookIssue_report($start_date, $end_date);

      $this->load->view('layout/header', $data);
      $this->load->view('reports/studentBookIssueReport', $data);
      $this->load->view('layout/footer', $data);
   }




   public function bookduereport()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/library');
      $this->session->set_userdata('subsub_menu', 'Reports/library/bookduereport');
      $data['searchlist'] = $this->customlib->get_searchtype();

      if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

         $dates = $this->customlib->get_betweendate($_POST['search_type']);
         $data['search_type'] = $_POST['search_type'];
      } else {

         $dates = $this->customlib->get_betweendate('this_year');
         $data['search_type'] = '';
      }

      if (isset($_POST['members_type']) && $_POST['members_type'] != '') {

         $data['member_id'] = $_POST['members_type'];
      } else {

         $data['member_id'] = '';
      }

      $data['members'] = array('' => $this->lang->line('all'), 'student' => $this->lang->line('student'), 'teacher' => $this->lang->line('teacher'));

      $start_date = date('Y-m-d', strtotime($dates['from_date']));
      $end_date = date('Y-m-d', strtotime($dates['to_date']));
      $data['label'] = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));
      $data['issued_books'] = $this->bookissue_model->bookduereport($start_date, $end_date);

      $this->load->view('layout/header', $data);
      $this->load->view('reports/bookduereport', $data);
      $this->load->view('layout/footer', $data);
   }

   public function bookinventory()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/library');
      $this->session->set_userdata('subsub_menu', 'Reports/library/bookinventory');

      $data['searchlist'] = $this->customlib->get_searchtype();

      if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

         $dates = $this->customlib->get_betweendate($_POST['search_type']);
         $data['search_type'] = $_POST['search_type'];
      } else {

         $dates = $this->customlib->get_betweendate('this_year');
         $data['search_type'] = '';
      }


      $start_date = date('Y-m-d', strtotime($dates['from_date']));
      $end_date = date('Y-m-d', strtotime($dates['to_date']));
      $data['label'] = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));
      $listbook = $this->book_model->bookinventory($start_date, $end_date);

      $data['listbook'] = $listbook;

      $this->load->view('layout/header', $data);
      $this->load->view('reports/bookinventory', $data);
      $this->load->view('layout/footer', $data);
   } //

   public function feescollectionreport()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/fees_collection');
      $this->session->set_userdata('subsub_menu', '');


      $this->load->view('layout/header');
      $this->load->view('reports/feescollectionreport');
      $this->load->view('layout/footer');
   }


   public function gerenalincomereport()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'reports/bookinventory');

      $data['searchlist'] = $this->customlib->get_searchtype();

      if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

         $dates = $this->customlib->get_betweendate($_POST['search_type']);
         $data['search_type'] = $_POST['search_type'];
      } else {

         $dates = $this->customlib->get_betweendate('this_year');
         $data['search_type'] = '';
      }

      $start_date = date('Y-m-d', strtotime($dates['from_date']));
      $end_date = date('Y-m-d', strtotime($dates['to_date']));
      $data['label'] = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));
      $listbook = $this->book_model->bookinventory($start_date, $end_date);

      $data['listbook'] = $listbook;

      $this->load->view('layout/header', $data);
      $this->load->view('reports/gerenalincomereport', $data);
      $this->load->view('layout/footer', $data);
   }

   public function studentinformation()
   {
      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/student_information');
      $this->session->set_userdata('subsub_menu', '');


      $data['school_code'] = $this->sch_setting_detail->dise_code;

      // print_r($data);
      // die();

      $this->load->view('layout/header');
      $this->load->view('reports/studentinformation', $data);
      $this->load->view('layout/footer');
   }

   public function attendance()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/attendance');
      $this->session->set_userdata('subsub_menu', '');

      $this->load->view('layout/header');
      $this->load->view('reports/attendance');
      $this->load->view('layout/footer');
   }

   public function examinations()
   {
      if (!$this->rbac->hasPrivilege('rank_report', 'can_view')) {
         access_denied();
      }
      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/examinations');
      $this->session->set_userdata('subsub_menu', '');

      $this->load->view('layout/header');
      $this->load->view('reports/examinations');
      $this->load->view('layout/footer');
   }

   public function library()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/library');
      $this->session->set_userdata('subsub_menu', '');

      $this->load->view('layout/header');
      $this->load->view('reports/library');
      $this->load->view('layout/footer');
   }

   public function inventory()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/inventory');
      $this->session->set_userdata('subsub_menu', '');

      $this->load->view('layout/header');
      $this->load->view('reports/inventory');
      $this->load->view('layout/footer');
   }


   public function onlineexams()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/online_examinations');
      $this->session->set_userdata('subsub_menu', 'Reports/online_examinations/onlineexams');
      $condition = "";
      $data['searchlist'] = $this->customlib->get_searchtype();
      $data['date_type'] = $this->customlib->date_type();

      $data['date_typeid'] = '';
      if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

         $dates = $this->customlib->get_betweendate($_POST['search_type']);
         $data['search_type'] = $_POST['search_type'];
      } else {

         $dates = $this->customlib->get_betweendate('this_year');
         $data['search_type'] = '';
      }

      $start_date = date('Y-m-d', strtotime($dates['from_date']));
      $end_date = date('Y-m-d', strtotime($dates['to_date']));

      $data['label'] = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));

      if (isset($_POST['date_type']) && $_POST['date_type'] != '') {

         $data['date_typeid'] = $_POST['date_type'];

         if ($_POST['date_type'] == 'exam_from_date') {

            $condition = " date_format(exam_from,'%Y-%m-%d') between '" . $start_date . "' and '" . $end_date . "'";
         } elseif ($_POST['date_type'] == 'exam_to_date') {

            $condition = " date_format(exam_to,'%Y-%m-%d') between '" . $start_date . "' and '" . $end_date . "'";
         }
      } else {

         $condition = " date_format(created_at,'%Y-%m-%d') between '" . $start_date . "' and '" . $end_date . "'";
      }


      $data['resultlist'] = $this->onlineexam_model->onlineexamReport($condition);

      $this->load->view('layout/header', $data);
      $this->load->view('reports/onlineexams', $data);
      $this->load->view('layout/footer', $data);
   }

   public function onlineexamsresult()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/examinations');
      $this->session->set_userdata('subsub_menu', 'Reports/examinations/onlineexamsresult');
      $condition = "";
      $data['searchlist'] = $this->customlib->get_searchtype();
      $data['date_type'] = $this->customlib->date_type();

      $data['date_typeid'] = '';
      if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

         $dates = $this->customlib->get_betweendate($_POST['search_type']);
         $data['search_type'] = $_POST['search_type'];
      } else {

         $dates = $this->customlib->get_betweendate('this_year');
         $data['search_type'] = '';
      }

      $start_date = date('Y-m-d', strtotime($dates['from_date']));
      $end_date = date('Y-m-d', strtotime($dates['to_date']));

      $data['label'] = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));

      if (isset($_POST['date_type']) && $_POST['date_type'] != '') {

         $data['date_typeid'] = $_POST['date_type'];

         if ($_POST['date_type'] == 'exam_from_date') {

            $condition = " date_format(exam_from,'%Y-%m-%d') between '" . $start_date . "' and '" . $end_date . "'";
         } elseif ($_POST['date_type'] == 'exam_to_date') {

            $condition = " date_format(exam_to,'%Y-%m-%d') between '" . $start_date . "' and '" . $end_date . "'";
         }
      } else {

         $condition = " date_format(created_at,'%Y-%m-%d') between '" . $start_date . "' and '" . $end_date . "'";
      }


      $data['resultlist'] = $this->onlineexam_model->onlineexamReport($condition);
      // echo $this->db->last_query();die;
      $this->load->view('layout/header', $data);
      $this->load->view('reports/onlineexamsresult', $data);
      $this->load->view('layout/footer', $data);
   }


   public function onlineexamattend()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/online_examinations');
      $this->session->set_userdata('subsub_menu', 'Reports/online_examinations/onlineexamattend');
      $condition = "";

      $data['searchlist'] = $this->customlib->get_searchtype();
      $data['date_type'] = $this->customlib->date_type();

      $data['date_typeid'] = '';
      if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

         $dates = $this->customlib->get_betweendate($_POST['search_type']);
         $data['search_type'] = $_POST['search_type'];
      } else {

         $dates = $this->customlib->get_betweendate('this_year');
         $data['search_type'] = '';
      }

      $start_date = date('Y-m-d', strtotime($dates['from_date']));
      $end_date = date('Y-m-d', strtotime($dates['to_date']));

      $data['label'] = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));

      if (isset($_POST['date_type']) && $_POST['date_type'] != '') {

         $data['date_typeid'] = $_POST['date_type'];

         if ($_POST['date_type'] == 'exam_from_date') {

            $condition = " and date_format(onlineexam.exam_from,'%Y-%m-%d') between '" . $start_date . "' and '" . $end_date . "'";
         } elseif ($_POST['date_type'] == 'exam_to_date') {

            $condition = " and date_format(onlineexam.exam_to,'%Y-%m-%d') between '" . $start_date . "' and '" . $end_date . "'";
         }
      } else {

         $condition = " and  date_format(onlineexam.created_at,'%Y-%m-%d') between '" . $start_date . "' and '" . $end_date . "'";
      }

      $data['resultlist'] = $this->onlineexam_model->onlineexamatteptreport($condition);

      $this->load->view('layout/header', $data);
      $this->load->view('reports/onlineexamattend', $data);
      $this->load->view('layout/footer', $data);
   }

   public function onlineexamrank()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/online_examinations');
      $this->session->set_userdata('subsub_menu', 'Reports/online_examinations/onlineexamrank');

      $exam_id = $class_id = $section_id = $condition = '';
      $studentrecord = array();
      $getResultByStudent1 = array();

      $examList         = $this->onlineexam_model->get();
      $data['examList'] = $examList;
      $class             = $this->class_model->get();
      $data['classlist'] = $class;

      if (isset($_POST['class_id']) && $_POST['class_id'] != '') {
         $class_id = $_POST['class_id'];
      }

      if (isset($_POST['section_id']) && $_POST['section_id'] != '') {
         $section_id = $_POST['section_id'];
      }

      if (isset($_POST['exam_id']) && $_POST['exam_id'] != '') {
         $exam_id = $_POST['exam_id'];
      }

      $data['resultlist'] = $this->onlineexamresult_model->getStudentResult($exam_id, $class_id, $section_id);

      foreach ($data['resultlist'] as $examresult_key => $examresult_value) {

         $studentrecord[$examresult_value['onlineexam_student_id']] = $examresult_value;
         $onlineexam_student_id = $examresult_value['onlineexam_student_id'];
         $examid = $examresult_value['exam_id'];
         $getResultByStudent = $this->onlineexamresult_model->onlineexamrank($onlineexam_student_id, $examid);

         if (!empty($getResultByStudent)) {
            $rank_array = array(
               'onlineexam_student_id' => $getResultByStudent[0]['onlineexam_student_id'],
               'correct_answer' => $getResultByStudent[0]['correct_answer'],
               'incorrect_answer' => $getResultByStudent[0]['incorrect_answer'],
               'total_questions' => $getResultByStudent[0]['total_questions'],
               'percentage' => (($getResultByStudent[0]['correct_answer'] / $getResultByStudent[0]['total_questions']) * 100),
            );
            $getResultByStudent1[$onlineexam_student_id] = $rank_array;
         }

         $getResultByStudent = array();
      }



      if (!empty($getResultByStudent1)) {
         usort($getResultByStudent1, function ($a, $b) {
            return $a['percentage'] - $b['percentage'];
         });
      }
      //echo "<pre>"; print_r($getResultByStudent1); echo "<pre>";die;
      $this->form_validation->set_rules('exam_id', $this->lang->line('exam'), 'required');


      if ($this->form_validation->run() == FALSE) {

         $data['studentrecord'] = '';
         $data['final_result'] = '';
      } else {
         $fdata = array();
         $data['studentrecord'] = $studentrecord;

         if (!empty($getResultByStudent1)) {

            foreach ($getResultByStudent1 as $key => $value) {

               if ($value['onlineexam_student_id'] != '') {
                  $fdata[] = $value;
               }
            }
         }


         $data['final_result'] = $fdata;
      }
      $this->load->view('layout/header', $data);
      $this->load->view('reports/onlineexamrank', $data);
      $this->load->view('layout/footer', $data);
   }

   public function inventorystock()
   {
      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/inventory');
      $this->session->set_userdata('subsub_menu', 'Reports/inventory/inventorystock');
      $data['stockresult'] = $this->itemstock_model->get_currentstock();

      $this->load->view('layout/header', $data);
      $this->load->view('reports/inventorystock', $data);
      $this->load->view('layout/footer', $data);
   }

   public function additem()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/inventory');
      $this->session->set_userdata('subsub_menu', 'Reports/inventory/additem');

      $data['searchlist'] = $this->customlib->get_searchtype();
      $data['date_type'] = $this->customlib->date_type();
      $data['date_typeid'] = '';

      if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

         $dates = $this->customlib->get_betweendate($_POST['search_type']);
         $data['search_type'] = $_POST['search_type'];
      } else {

         $dates = $this->customlib->get_betweendate('this_year');
         $data['search_type'] = '';
      }

      $start_date = date('Y-m-d', strtotime($dates['from_date']));
      $end_date = date('Y-m-d', strtotime($dates['to_date']));

      $data['label'] = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));
      $data['itemresult'] = $this->itemstock_model->get_ItemByBetweenDate($start_date, $end_date);

      $this->load->view('layout/header', $data);
      $this->load->view('reports/additem', $data);
      $this->load->view('layout/footer', $data);
   }


   public function issueinventory()
   {


      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/inventory');
      $this->session->set_userdata('subsub_menu', 'Reports/inventory/issueinventory');

      $data['searchlist'] = $this->customlib->get_searchtype();
      $data['date_type'] = $this->customlib->date_type();
      $data['date_typeid'] = '';

      if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

         $dates = $this->customlib->get_betweendate($_POST['search_type']);
         $data['search_type'] = $_POST['search_type'];
      } else {

         $dates = $this->customlib->get_betweendate('this_year');
         $data['search_type'] = '';
      }

      $start_date = date('Y-m-d', strtotime($dates['from_date']));
      $end_date = date('Y-m-d', strtotime($dates['to_date']));

      $data['label'] = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));
      $data['itemissueList'] = $this->itemissue_model->get_IssueInventoryReport($start_date, $end_date);


      $this->load->view('layout/header', $data);
      $this->load->view('reports/issueinventory', $data);
      $this->load->view('layout/footer', $data);
   }

   public function finance()
   {
      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/finance');
      $this->session->set_userdata('subsub_menu', '');
      $data['stockresult'] = $this->itemstock_model->get_currentstock();

      $this->load->view('layout/header', $data);
      $this->load->view('reports/finance', $data);
      $this->load->view('layout/footer', $data);
   }

   public function income()
   {
      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/finance');
      $this->session->set_userdata('subsub_menu', 'Reports/finance/income');
      $data['searchlist'] = $this->customlib->get_searchtype();
      $data['date_type'] = $this->customlib->date_type();
      $data['date_typeid'] = '';

      if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

         $dates = $this->customlib->get_betweendate($_POST['search_type']);
         $data['search_type'] = $_POST['search_type'];
      } else {

         $dates = $this->customlib->get_betweendate('this_year');
         $data['search_type'] = '';
      }

      $start_date = date('Y-m-d', strtotime($dates['from_date']));
      $end_date = date('Y-m-d', strtotime($dates['to_date']));

      $data['label'] = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));
      $incomeList = $this->income_model->search("", $start_date, $end_date);

      $data['incomeList'] = $incomeList;
      $this->load->view('layout/header', $data);
      $this->load->view('reports/income', $data);
      $this->load->view('layout/footer', $data);
   }

   public function expense()
   {
      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/finance');
      $this->session->set_userdata('subsub_menu', 'Reports/finance/expense');
      $data['searchlist'] = $this->customlib->get_searchtype();
      $data['date_type'] = $this->customlib->date_type();
      $data['date_typeid'] = '';

      if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

         $dates = $this->customlib->get_betweendate($_POST['search_type']);
         $data['search_type'] = $_POST['search_type'];
      } else {

         $dates = $this->customlib->get_betweendate('this_year');
         $data['search_type'] = '';
      }

      $start_date = date('Y-m-d', strtotime($dates['from_date']));
      $end_date = date('Y-m-d', strtotime($dates['to_date']));

      $data['label'] = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));
      $expenseList = $this->expense_model->search("", $start_date, $end_date);

      $data['expenseList'] = $expenseList;
      $this->load->view('layout/header', $data);
      $this->load->view('reports/expense', $data);
      $this->load->view('layout/footer', $data);
   }

   public function payroll()
   {
      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/finance');
      $this->session->set_userdata('subsub_menu', 'Reports/finance/payroll');
      $data['searchlist'] = $this->customlib->get_searchtype();
      $data['date_type'] = $this->customlib->date_type();
      $data['date_typeid'] = '';

      if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

         $dates = $this->customlib->get_betweendate($_POST['search_type']);
         $data['search_type'] = $_POST['search_type'];
      } else {

         $dates = $this->customlib->get_betweendate('this_year');
         $data['search_type'] = '';
      }

      $start_date = date('Y-m-d', strtotime($dates['from_date']));
      $end_date = date('Y-m-d', strtotime($dates['to_date']));

      $data['label'] = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));
      $data['payment_mode'] = $this->payment_mode;

      $result = $this->payroll_model->getbetweenpayrollReport($start_date, $end_date);

      $data['payrollList'] = $result;
      $this->load->view('layout/header', $data);
      $this->load->view('reports/payroll', $data);
      $this->load->view('layout/footer', $data);
   }

   public function incomegroup()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/finance');
      $this->session->set_userdata('subsub_menu', 'Reports/finance/incomegroup');
      $data['searchlist'] = $this->customlib->get_searchtype();
      $data['date_type'] = $this->customlib->date_type();
      $data['date_typeid'] = '';

      if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

         $dates = $this->customlib->get_betweendate($_POST['search_type']);
         $data['search_type'] = $_POST['search_type'];
      } else {

         $dates = $this->customlib->get_betweendate('this_year');
         $data['search_type'] = '';
      }
      $data['head_id'] = $head_id = "";
      if (isset($_POST['head']) && $_POST['head'] != '') {
         $data['head_id'] = $head_id = $_POST['head'];
      }

      $start_date = date('Y-m-d', strtotime($dates['from_date']));
      $end_date = date('Y-m-d', strtotime($dates['to_date']));

      $data['label'] = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));
      $incomeList = $this->income_model->searchincomegroup($start_date, $end_date, $head_id);

      $data['headlist'] = $this->incomehead_model->get();

      $data['incomeList'] = $incomeList;
      $this->load->view('layout/header', $data);
      $this->load->view('reports/incomegroup', $data);
      $this->load->view('layout/footer', $data);
   }
   public function expensegroup()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/finance');
      $this->session->set_userdata('subsub_menu', 'Reports/finance/expensegroup');
      $data['searchlist'] = $this->customlib->get_searchtype();
      $data['date_type'] = $this->customlib->date_type();
      $data['date_typeid'] = '';

      if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

         $dates = $this->customlib->get_betweendate($_POST['search_type']);
         $data['search_type'] = $_POST['search_type'];
      } else {

         $dates = $this->customlib->get_betweendate('this_year');
         $data['search_type'] = '';
      }

      $data['head_id'] = $head_id = "";
      if (isset($_POST['head']) && $_POST['head'] != '') {
         $data['head_id'] = $head_id = $_POST['head'];
      }

      $start_date = date('Y-m-d', strtotime($dates['from_date']));
      $end_date = date('Y-m-d', strtotime($dates['to_date']));

      $data['label'] = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));

      $result = $this->expensehead_model->searchexpensegroup($start_date, $end_date, $head_id);

      $data['headlist'] = $this->expensehead_model->get();

      $data['expenselist'] = $result;
      $this->load->view('layout/header', $data);
      $this->load->view('reports/expensegroup', $data);
      $this->load->view('layout/footer', $data);
   }

   public function student_profile()
   {


      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/student_information');
      $this->session->set_userdata('subsub_menu', 'Reports/student_information/student_profile');
      $data['title'] = 'Add Fees Type';
      $data['searchlist'] = $this->search_type;
      $data['sch_setting']        = $this->sch_setting_detail;
      $data['adm_auto_insert']    = $this->sch_setting_detail->adm_auto_insert;
      $searchterm = '';

      if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

         $between_date = $this->customlib->get_betweendate($_POST['search_type']);
         $data['search_type'] = $search_type = $_POST['search_type'];
      } else {

         $between_date = $this->customlib->get_betweendate('this_year');
         $data['search_type'] = $search_type = '';
      }
      $from_date = date('Y-m-d', strtotime($between_date['from_date']));
      $to_date = date('Y-m-d', strtotime($between_date['to_date']));
      $condition = " date_format(admission_date,'%Y-%m-%d') between  '" . $from_date . "' and '" . $to_date . "'";
      $data['filter_label'] = date($this->customlib->getSchoolDateFormat(), strtotime($from_date)) . " To " . date($this->customlib->getSchoolDateFormat(), strtotime($to_date));

      $data['sch_setting']        = $this->sch_setting_detail;
      $data['adm_auto_insert']    = $this->sch_setting_detail->adm_auto_insert;

      $this->form_validation->set_rules('search_type', $this->lang->line('search') . " " . $this->lang->line('type'), 'trim|required|xss_clean');
      if ($this->form_validation->run() == false) {
         $data['resultlist'] = array();
      } else {
         $data['resultlist'] = $this->student_model->student_profile($condition);
      }

      $this->load->view('layout/header', $data);
      $this->load->view('reports/student_profile', $data);
      $this->load->view('layout/footer', $data);
   }

   public function staff_report()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/human_resource');
      $this->session->set_userdata('subsub_menu', 'Reports/human_resource/staff_report');
      $data['title'] = 'Add Fees Type';
      $data['searchlist'] = $this->search_type;
      $data['sch_setting']        = $this->sch_setting_detail;
      $data['adm_auto_insert']    = $this->sch_setting_detail->adm_auto_insert;
      $searchterm = '';
      $condition = "";
      if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

         $between_date = $this->customlib->get_betweendate($_POST['search_type']);
         $data['search_type'] = $search_type = $_POST['search_type'];
      } else {

         $between_date = $this->customlib->get_betweendate('this_year');
         $data['search_type'] = $search_type = '';
      }

      $from_date = date('Y-m-d', strtotime($between_date['from_date']));

      $to_date = date('Y-m-d', strtotime($between_date['to_date']));

      $condition .= " and date_format(date_of_joining,'%Y-%m-%d') between  '" . $from_date . "' and '" . $to_date . "'";

      $data['filter_label'] = date($this->customlib->getSchoolDateFormat(), strtotime($from_date)) . " To " . date($this->customlib->getSchoolDateFormat(), strtotime($to_date));

      if (isset($_POST['staff_status']) && $_POST['staff_status'] != '') {
         if ($_POST['staff_status'] == 'both') {

            $search_status = "1,2";
         } elseif ($_POST['staff_status'] == '2') {

            $search_status = "0";
         } else {

            $search_status = "1";
         }
         $condition .= " and `staff`.`is_active` in (" . $search_status . ")";
         $data['status_val'] = $_POST['staff_status'];
      } else {
         $data['status_val'] = 1;
      }

      if (isset($_POST['role']) && $_POST['role'] != '') {
         $condition .= " and `staff_roles`.`role_id`=" . $_POST['role'];
         $data['role_val'] = $_POST['role'];
      }

      if (isset($_POST['designation']) && $_POST['designation'] != '') {
         $condition .= " and `staff_designation`.`id`=" . $_POST['designation'];
         $data['designation_val'] = $_POST['designation'];
      }

      $data['resultlist'] = $this->staff_model->staff_report($condition);

      $leave_type = $this->leavetypes_model->getLeaveType();
      foreach ($leave_type as $key => $leave_value) {
         $data['leave_type'][$leave_value['id']] = $leave_value['type'];
      }
      $data['status'] = $this->customlib->staff_status();
      $data['roles'] = $this->role_model->get();
      $data['designation'] = $this->designation_model->get();

      $data['fields'] = $this->customfield_model->get_custom_fields('staff', 1);
      $data['sch_setting']        = $this->sch_setting_detail;
      $data['adm_auto_insert']    = $this->sch_setting_detail->adm_auto_insert;
      $this->load->view('layout/header', $data);
      $this->load->view('reports/staff_report', $data);
      $this->load->view('layout/footer', $data);
   }

   public function attendancereport()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/attendance');
      $this->session->set_userdata('subsub_menu', 'Reports/attendence/attendancereport');
      $data['searchlist'] = $this->search_type;
      $data['sch_setting']        = $this->sch_setting_detail;
      $data['adm_auto_insert']    = $this->sch_setting_detail->adm_auto_insert;
      $class = $this->input->post('class_id');
      $section = $this->input->post('section_id');
      $data['class_id'] = $class;
      $data['section_id'] = $section;
      $class = $this->class_model->get();
      $data['classlist'] = $class;
      $searchterm = '';
      $condition = "";
      $date_condition = "";

      if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

         $between_date = $this->customlib->get_betweendate($_POST['search_type']);
         $data['search_type'] = $search_type = $_POST['search_type'];
      } else {

         $between_date = $this->customlib->get_betweendate('this_week');
         $data['search_type'] = $search_type = 'this_week';
      }

      $from_date = date('Y-m-d', strtotime($between_date['from_date']));
      $to_date = date('Y-m-d', strtotime($between_date['to_date']));
      $dates = array();
      $off_date = array();
      $current = strtotime($from_date);
      $last = strtotime($to_date);

      while ($current <= $last) {

         $date = date('Y-m-d', $current);
         $day = date("D", strtotime($date));
         $holiday = $this->stuattendence_model->checkholidatbydate($date);


         if ($day == 'Sun' || $holiday > 0) {
            $off_date[] = $date;
         } else {
            $dates[] = $date;
         }

         $current = strtotime('+1 day', $current);
      }


      $data['filter'] = date($this->customlib->getSchoolDateFormat(), strtotime($from_date)) . " To " . date($this->customlib->getSchoolDateFormat(), strtotime($to_date));
      $data['attendance_type'] = $this->attendencetype_model->getstdAttType('2');
      $this->form_validation->set_rules('attendance_type', $this->lang->line('attendence') . " " . $this->lang->line('type'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
      if ($this->form_validation->run() == FALSE) {

         $this->load->view('layout/header', $data);
         $this->load->view('reports/stuattendance', $data);
         $this->load->view('layout/footer', $data);
      } else {


         $data['attendance_type_id'] = $attendance_type_id = $this->input->post('attendance_type');
         $condition .= " and `student_attendences`.`attendence_type_id`=" . $this->input->post('attendance_type');
         foreach ($dates as $key => $value) {
         }


         if ($data['class_id'] != '') {
            $condition .= ' and class_id=' . $data['class_id'];
         }
         $condition .= " and date_format(student_attendences.date,'%Y-%m-%d') between '" . $from_date . "' and '" . $to_date . "'";
         if ($data['section_id'] != '') {
            $condition .= ' and section_id=' . $data['section_id'];
         }


         $data['student_attendences'] = $this->stuattendence_model->student_attendences($condition, $date_condition);

         $attd = array();

         foreach ($data['student_attendences'] as $value) {
            $std_id = $value['id'];
            $attd[$std_id][] = $value;
         }


         foreach ($attd as $key => $att_value) {
            $all_week = 1;
            foreach ($att_value as $value) {

               if (in_array($value['date'], $off_date)) {
               } else {
                  if (in_array($value['date'], $dates)) {
                     //echo "Match found";
                  } else {
                     $all_week = 0;
                  }
               }
            }
            if ($all_week == 1) {
               $fdata[] = $att_value[0];
            }
         }

         $dates = " '" . $from_date . "' and '" . $to_date . "'";

         $this->load->view('layout/header', $data);
         $this->load->view('reports/stuattendance', $data);
         $this->load->view('layout/footer', $data);
      }
   }

   public function biometric_attlog($offset = 0)
   {
      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/attendance');
      $this->session->set_userdata('subsub_menu', 'Reports/attendence/biometric_attlog');
      $data['sch_setting']        = $this->sch_setting_detail;
      $data['adm_auto_insert']    = $this->sch_setting_detail->adm_auto_insert;

      $config['total_rows'] = $this->stuattendence_model->biometric_attlogcount();

      $config['base_url'] = base_url() . "report/biometric_attlog";
      $config['per_page'] = 100;
      $config['uri_segment'] = '3';

      $config['full_tag_open'] = '<div class="pagination"><ul>';
      $config['full_tag_close'] = '</ul></div>';

      $config['first_link'] = '« First';
      $config['first_tag_open'] = '<li class="prev page">';
      $config['first_tag_close'] = '</li>';

      $config['last_link'] = 'Last »';
      $config['last_tag_open'] = '<li class="next page">';
      $config['last_tag_close'] = '</li>';

      $config['next_link'] = 'Next →';
      $config['next_tag_open'] = '<li class="next page">';
      $config['next_tag_close'] = '</li>';

      $config['prev_link'] = '← Previous';
      $config['prev_tag_open'] = '<li class="prev page">';
      $config['prev_tag_close'] = '</li>';

      $config['cur_tag_open'] = '<li ><a href="" class="active">';
      $config['cur_tag_close'] = '</a></li>';

      $config['num_tag_open'] = '<li class="page">';
      $config['num_tag_close'] = '</li>';


      $this->pagination->initialize($config);


      $query = $this->stuattendence_model->biometric_attlog(100, $this->uri->segment(3));

      $data['resultlist'] = $query;
      $this->load->view('layout/header', $data);
      $this->load->view('reports/biometric_attlog', $data);
      $this->load->view('layout/footer', $data);
   }

   public function class_record_summary()
   {
      // if (!$this->rbac->hasPrivilege('class_record_summary', 'can_view')) {
      //     access_denied();
      // }

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/student_information');
      $this->session->set_userdata('subsub_menu', 'Reports/student_information/class_record_summary');

      $data['title'] = 'Summary of Consolidated Grades';
      $class = $this->class_model->get();
      $data['classlist'] = $class;
      $data['sch_setting'] = $this->sch_setting_detail;
      $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
      $data['session_list'] = $this->session_model->getAllSession();
      // $data['quarter_list'] = $this->general_model->get_quarter_list();
      // $data['quarter_list'] = $this->general_model->get_quarter_list('Qtr', 4);
      // $data['quarter_list'] = $this->gradereport_model->get_quarter_list('Qtr', 4);
      // print_r($data['quarter_list']);
      // die();


      $data['school_code'] = $this->sch_setting_detail->dise_code;
      // $carray = array();

      // if (!empty($data["classlist"])) { $sch_setting->session_id
      //     foreach ($data["classlist"] as $ckey => $cvalue) {
      //         $carray[] = $cvalue["id"];
      //     }
      // }

      $this->form_validation->set_rules('session_id', $this->lang->line('current_session'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('quarter_id', $this->lang->line('quarter'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

      if ($this->input->server('REQUEST_METHOD') == "GET") {
         $this->load->view('layout/header', $data);
         if (strtoupper($this->sch_setting_detail->dise_code) == 'LPMS')
            $this->load->view('reports/class_record_summary_lpms', $data);
         else
            $this->load->view('reports/class_record_summary', $data);
         $this->load->view('layout/footer', $data);
      } else {
         if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            if (strtoupper($this->sch_setting_detail->dise_code) == 'LPMS')
               $this->load->view('reports/class_record_summary_lpms', $data);
            else
               $this->load->view('reports/class_record_summary', $data);
            $this->load->view('layout/footer', $data);
         } else {
            $session = $this->input->post('session_id');
            $quarter = $this->input->post('quarter_id');
            $grade_level = $this->input->post('class_id');
            $section = $this->input->post('section_id');

            if (strtoupper($this->sch_setting_detail->dise_code) == 'LPMS') {
               $class_record = $this->gradereport_model->get_class_record_lpms($session, $quarter, $grade_level, $section);
            } else {
               // print_r($data);
               // die();
               $class_record = $this->gradereport_model->get_class_record($session, $quarter, $grade_level, $section);
            }

            $data['resultlist'] = $class_record;
            $data['session_id'] = $session;
            $data['quarter_id'] = $quarter;
            $data['class_id'] = $grade_level;
            $data['section_id']  = $section;
            $data['subject_list'] = $this->gradereport_model->get_subject_list($grade_level, $session, $section);
            $data['codes_table'] = $this->gradereport_model->grade_code_table();
            $data['show_letter_grade'] = $this->sch_setting_detail->show_letter_grade;

            // print_r($class_record);
            // die();
            $this->load->view('layout/header', $data);
            if (strtoupper($this->sch_setting_detail->dise_code) == 'LPMS')
               $this->load->view('reports/class_record_summary_lpms', $data);
            else
               $this->load->view('reports/class_record_summary', $data);
            $this->load->view('layout/footer', $data);
         }
      }
   }

   public function class_record_quarterly()
   {
      // if (!$this->rbac->hasPrivilege('class_record_quarterly', 'can_view')) {
      //     access_denied();
      // }

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/student_information');
      $this->session->set_userdata('subsub_menu', 'Reports/student_information/class_record_quarterly');

      $data['title'] = 'Class Record Quarterly';
      $class = $this->class_model->get();
      $data['classlist'] = $class;
      $data['sch_setting'] = $this->sch_setting_detail;
      $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
      $data['session_list'] = $this->session_model->getAllSession();
      $data['teacher_list'] = $this->gradereport_model->get_teacher_list();

      $grade_level_info = $this->class_model->get_grade_level_info($class);

      // print_r($data);
      // exit();

      // $data['quarter_list'] = $this->gradereport_model->get_quarter_list($grade_level_info['term_alias'], $grade_level_info['term_length']);
      // $data['quarter_list'] = $this->gradereport_model->get_quarter_list('Qtr', 4);
      // $carray = array();

      // if (!empty($data["classlist"])) { $sch_setting->session_id
      //     foreach ($data["classlist"] as $ckey => $cvalue) {
      //         $carray[] = $cvalue["id"];
      //     }
      // }

      $this->form_validation->set_rules('session_id', $this->lang->line('current_session'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('teacher_id', $this->lang->line('teacher'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required|xss_clean');

      if ($this->input->server('REQUEST_METHOD') == "GET") {
         $this->load->view('layout/header', $data);
         $this->load->view('reports/class_record_quarterly', $data);
         $this->load->view('layout/footer', $data);
      } else {
         if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('reports/class_record_quarterly', $data);
            $this->load->view('layout/footer', $data);
         } else {
            $session = $this->input->post('session_id');
            $grade_level = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $subject = $this->input->post('subject_id');
            $teacher = $this->input->post('teacher_id');
            // print_r("CloudPH Debug Mode 2");die();
            $class_record = $this->gradereport_model->get_class_record_quarterly($session, $grade_level, $section, $subject, $teacher);
            // $class_record = $this->gradereport_model->get_class_record_quarterly($session, $grade_level, $section, $subject);
            // print_r($class_record);
            // exit();

            $data['resultlist'] = $class_record;
            $data['session_id'] = $session;
            $data['class_id'] = $grade_level;
            $data['section_id']  = $section;
            $data['teacher_id'] = $teacher;
            $data['subject_id']  = $subject;
            $subject_name = $this->subject_model->get_subject_name($subject);
            $data['subject_name'] = $subject_name;
            $this->load->view('layout/header', $data);
            $this->load->view('reports/class_record_quarterly', $data);
            $this->load->view('layout/footer', $data);
         }
      }
   }

   public function class_record_per_student()
   {
      // if (!$this->rbac->hasPrivilege('class_record_quarterly', 'can_view')) {
      //     access_denied();
      // }

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/student_information');
      $this->session->set_userdata('subsub_menu', 'Reports/student_information/class_record_per_student');

      $data['title'] = 'Class Record Per Student';
      $class = $this->class_model->get();
      $data['classlist'] = $class;
      $data['sch_setting'] = $this->sch_setting_detail;
      $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
      $data['session_list'] = $this->session_model->getAllSession();
      $data['conduct_grading_type'] = $this->sch_setting_detail->conduct_grading_type;
      $data['legend_list'] = $this->conduct_model->get_conduct_legend_list();
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

         if (strtolower($data['school_code']) == 'lpms') {
            $this->load->view('layout/header', $data);
            $this->load->view('reports/class_record_per_student_lpms', $data);
            $this->load->view('layout/footer', $data);
         } else {
            $this->load->view('layout/header', $data);
            $this->load->view('reports/class_record_per_student', $data);
            $this->load->view('layout/footer', $data);
         }
      } else {
         if ($this->form_validation->run() == false) {
            if (strtolower($data['school_code']) == 'lpms') {
               $this->load->view('layout/header', $data);
               $this->load->view('reports/class_record_per_student_lpms', $data);
               $this->load->view('layout/footer', $data);
            } else {
               $this->load->view('layout/header', $data);
               $this->load->view('reports/class_record_per_student', $data);
               $this->load->view('layout/footer', $data);
            }
         } else {
            $session = $this->input->post('session_id');
            $grade_level = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $student_id = $this->input->post('student_id');
            $student_conduct = null;

            if ($this->sch_setting_detail->conduct_grade_view == 0) {
               if (strtolower($data['school_code']) !== 'lpms') {
                  if ($this->sch_setting_detail->conduct_grading_type == 'letter')
                     $student_conduct = $this->gradereport_model->get_student_conduct($session, $grade_level, $section, $student_id);
                  else if ($this->sch_setting_detail->conduct_grading_type == 'number')
                     $student_conduct = $this->gradereport_model->get_student_conduct_numeric($session, $grade_level, $section, $student_id);
               } else {
               }
            }

            $data['student_conduct'] = $student_conduct;

            // print_r(json_encode($student_id));die();

            $grade_level_info = $this->class_model->get_grade_level_info($grade_level);
            $data['quarter_list'] = $this->gradereport_model->get_quarter_list($grade_level_info['term_alias'], $grade_level_info['term_length']);

            // print_r($data['quarter_list']);
            // die();
            // $data['quarter_list'] = $this->gradereport_model->get_quarter_list('Qtr', 4);

            $data['session_id'] = $session;
            $data['class_id'] = $grade_level;
            $data['section_id']  = $section;
            $data['student_id'] = $student_id;
            $studentinfo = $this->student_model->get($student_id);
            $data['student'] = $studentinfo;

            // print_r($data);
            // die();

            $data['month_days_list'] = $this->gradereport_model->get_month_days_list();

            $data['show_general_average'] = $this->sch_setting_detail->grading_general_average;
            $data['show_letter_grade'] = $this->sch_setting_detail->show_letter_grade;
            $data['show_average_column'] = $this->sch_setting_detail->show_average_column;
            $data['terms_allowed'] = $this->gradereport_model->get_terms_allowed($session, $student_id);

            if (strtolower($data['school_code']) == 'lpms') {
               $class_record = $this->gradereport_model->get_student_class_record_unrestricted_lpms($session, $student_id, $grade_level, $section);
               $data['resultlist'] = $class_record;

               // print_r($data);
               // die();

               $this->load->view('layout/header', $data);
               $this->load->view('reports/class_record_per_student_lpms', $data);
               $this->load->view('layout/footer', $data);
            } else if (strtolower($data['school_code']) == 'ssapamp') {

               $result1 = $this->grading_ssapamp_model->getLevelId('Pre-Kinder');
               $prekinder = $result1[0]->id;

               $student_attendance = $this->gradereport_model->get_student_attendance_by_month($session, $grade_level, $section, $student_id);

               if ($student_attendance) {
                  $data['student_attendance'] = $student_attendance;
               } else {
                  $data['student_attendance'] = array();
               }

               // print_r('<pre>');
               // print_r($data['student_attendance']);
               // print_r('</pre>');
               // die();

               $data['ssap_conduct'] = $this->gradereport_model->get_conduct_ssapamp($session, $grade_level, $section, $student_id);

               // print_r($data['ssap_conduct']);
               // die();

               if ($grade_level == $prekinder) {
                  // print_r($data['ssap_conduct']);
                  // die();

                  $class_record = $this->grading_studentgrade_ssapamp_model->get_student_checklist($session, $grade_level, $section, $student_id);

                  // print_r($data);
                  // die();

                  // print_r('<pre>');
                  // print_r($class_record);
                  // print_r('</pre>');
                  // die();

                  $data['resultlist'] = $class_record;
                  $legend = $this->grading_checklist_ssapamp_model->getLegend();
                  $data['legend_list'] = $legend;

                  $this->load->view('layout/header', $data);
                  $this->load->view('reports/class_record_per_student_ssapamp', $data);
                  $this->load->view('layout/footer', $data);
               } else {

                  $codes_table = $this->gradereport_model->grade_code_table();

                  if ($codes_table) {
                     $data['codes_table'] = $codes_table;
                  } else {
                     $data['codes_table'] = array();
                  }

                  // print_r('<pre>');
                  // print_r($codes_table);
                  // print_r('</pre>');
                  // die();

                  $class_record = $this->gradereport_model->get_student_class_record_unrestricted($session, $student_id, $grade_level, $section);
                  $data['resultlist'] = $class_record;

                  // print_r($data['resultlist']);
                  // die();

                  $this->load->view('layout/header', $data);
                  $this->load->view('reports/class_record_per_student', $data);
                  $this->load->view('layout/footer', $data);
               }
            } else {
               $codes_table = $this->gradereport_model->grade_code_table();

               if ($codes_table) {
                  $data['codes_table'] = $codes_table;
               } else {
                  $data['codes_table'] = array();
               }

               // print_r('<pre>');
               // print_r($codes_table);
               // print_r('</pre>');
               // die();

               $class_record = $this->gradereport_model->get_student_class_record_unrestricted($session, $student_id, $grade_level, $section);
               $data['resultlist'] = $class_record;

               $student_attendance = $this->gradereport_model->get_student_attendance_by_month($session, $grade_level, $section, $student_id);

               if ($student_attendance) {
                  $data['student_attendance'] = $student_attendance;
               } else {
                  $data['student_attendance'] = array();
               }

               $this->load->view('layout/header', $data);
               $this->load->view('reports/class_record_per_student', $data);
               $this->load->view('layout/footer', $data);
            }
         }
      }
   }

   public function student_report_card_lpms()
   {
      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/student_information');
      $this->session->set_userdata('subsub_menu', 'Reports/student_information/class_record_per_student');

      $data['title'] = 'Class Record Per Student';
      $class = $this->class_model->get();
      $data['classlist'] = $class;
      $data['sch_setting'] = $this->sch_setting_detail;
      $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
      $data['session_list'] = $this->session_model->getAllSession();
      // $data['conduct_grading_type'] = $this->sch_setting_detail->conduct_grading_type;
      $data['legend_list'] = $this->conduct_model->get_conduct_legend_list();

      $this->form_validation->set_rules('session_id', $this->lang->line('current_session'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('student_id', $this->lang->line('subject'), 'trim|required|xss_clean');


      $session = $this->input->get('session_id');
      $grade_level = $this->input->get('class_id');
      $section = $this->input->get('section_id');
      $student_id = $this->input->get('student_id');
      $adviser = $this->classteacher_model->teacherByClassSection($grade_level, $section);

      $class_record = $this->gradereport_model->get_student_class_record_unrestricted_lpms($session, $student_id, $grade_level, $section);
      // print_r(json_encode($class_record));die();
      // print_r($class_record);die();
      $grade_level_info = $this->class_model->get_grade_level_info($grade_level);
      $data['quarter_list'] = $this->gradereport_model->get_quarter_list($grade_level_info['term_alias'], $grade_level_info['term_length']);
      // $data['quarter_list'] = $this->gradereport_model->get_quarter_list('Qtr', 4);
      $data['resultlist'] = $class_record;
      $data['session_id'] = $session;
      $data['class_id'] = $grade_level;
      $data['section_id']  = $section;
      $data['student_id'] = $student_id;

      $studentinfo = $this->student_model->get($student_id);
      $data['student'] = $studentinfo;
      $data['school_year'] = $this->setting_model->getCurrentSessionName();
      $data['swh_scores'] = $this->gradereport_model->get_swh_score_quarterly($session, $grade_level, $section, $student_id);

      $data['class_adviser'] = $adviser[0]['name'] . ' ' . $adviser[0]['surname'];
      $data['codes_table'] = $this->gradereport_model->grade_code_table();

      $student_attendance = $this->gradereport_model->get_student_attendance_by_semester($session, $grade_level, $section, $student_id);

      if ($student_attendance) {
         $data['student_attendance'] = $student_attendance;
      } else {
         $data['student_attendance'] = array();
      }

      $this->load->view('reports/student_report_card_lpms', $data);
   }

   public function student_report_card_ssapamp()
   {
      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/student_information');
      $this->session->set_userdata('subsub_menu', 'Reports/student_information/class_record_per_student');

      $data['title'] = 'Student Report Card';
      $class = $this->class_model->get();
      $data['classlist'] = $class;
      $data['sch_setting'] = $this->sch_setting_detail;
      $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
      $data['session_list'] = $this->session_model->getAllSession();
      // $data['conduct_grading_type'] = $this->sch_setting_detail->conduct_grading_type;
      $data['legend_list'] = $this->conduct_model->get_conduct_legend_list();

      $this->form_validation->set_rules('session_id', $this->lang->line('current_session'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('student_id', $this->lang->line('subject'), 'trim|required|xss_clean');


      $session = $this->input->get('session_id');
      $grade_level = $this->input->get('class_id');
      $section = $this->input->get('section_id');
      $student_id = $this->input->get('student_id');
      $adviser = $this->classteacher_model->teacherByClassSection($grade_level, $section);

      $class_record = $this->gradereport_model->get_student_class_record_unrestricted_lpms($session, $student_id, $grade_level, $section);
      // print_r(json_encode($class_record));die();
      // print_r($class_record);die();
      $grade_level_info = $this->class_model->get_grade_level_info($grade_level);
      $data['quarter_list'] = $this->gradereport_model->get_quarter_list($grade_level_info['term_alias'], $grade_level_info['term_length']);
      // $data['quarter_list'] = $this->gradereport_model->get_quarter_list('Qtr', 4);
      $data['resultlist'] = $class_record;
      $data['session_id'] = $session;
      $data['class_id'] = $grade_level;
      $data['section_id']  = $section;
      $data['student_id'] = $student_id;

      $studentinfo = $this->student_model->get($student_id);
      $data['student'] = $studentinfo;
      $data['school_year'] = $this->setting_model->getCurrentSessionName();
      $data['swh_scores'] = $this->gradereport_model->get_swh_score_quarterly($session, $grade_level, $section, $student_id);

      $data['class_adviser'] = $adviser[0]['name'] . ' ' . $adviser[0]['surname'];
      $data['codes_table'] = $this->gradereport_model->grade_code_table();

      $student_attendance = $this->gradereport_model->get_student_attendance_by_semester($session, $grade_level, $section, $student_id);

      if ($student_attendance) {
         $data['student_attendance'] = $student_attendance;
      } else {
         $data['student_attendance'] = array();
      }

      $this->load->view('reports/student_report_card_ssapamp', $data);
   }

   public function student_report_card()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/student_information');
      $this->session->set_userdata('subsub_menu', 'Reports/student_information/class_record_per_student');

      $data['title'] = 'Class Record Per Student';
      $class = $this->class_model->get();
      $data['classlist'] = $class;
      $data['sch_setting'] = $this->sch_setting_detail;
      $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
      $data['session_list'] = $this->session_model->getAllSession();
      $data['conduct_grading_type'] = $this->sch_setting_detail->conduct_grading_type;
      $data['legend_list'] = $this->conduct_model->get_conduct_legend_list();

      $this->form_validation->set_rules('session_id', $this->lang->line('current_session'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('student_id', $this->lang->line('subject'), 'trim|required|xss_clean');


      $session = $this->input->get('session_id');
      $grade_level = $this->input->get('class_id');
      $section = $this->input->get('section_id');
      $student_id = $this->input->get('student_id');

      $student_conduct = null;
      if ($this->sch_setting_detail->conduct_grade_view == 0) {
         if ($this->sch_setting_detail->conduct_grading_type == 'letter')
            $student_conduct = $this->gradereport_model->get_student_conduct($session, $grade_level, $section, $student_id);
         else if ($this->sch_setting_detail->conduct_grading_type == 'number')
            $student_conduct = $this->gradereport_model->get_student_conduct_numeric($session, $grade_level, $section, $student_id);
      }

      $data['student_conduct'] = $student_conduct;

      $class_record = $this->gradereport_model->get_student_class_record_unrestricted($session, $student_id, $grade_level, $section);
      // print_r(json_encode($class_record));die();
      // print_r($class_record);die();
      $grade_level_info = $this->class_model->get_grade_level_info($grade_level);
      $data['quarter_list'] = $this->gradereport_model->get_quarter_list($grade_level_info['term_alias'], $grade_level_info['term_length']);
      // $data['quarter_list'] = $this->gradereport_model->get_quarter_list('Qtr', 4);
      $data['resultlist'] = $class_record;

      // print_r($student_conduct);
      // die();

      $data['session_id'] = $session;
      $data['class_id'] = $grade_level;
      $data['section_id']  = $section;
      $data['student_id'] = $student_id;
      $studentinfo = $this->student_model->get($student_id);
      $data['student'] = $studentinfo;

      $student_attendance = $this->gradereport_model->get_student_attendance_by_month($session, $grade_level, $section, $student_id);

      if ($student_attendance) {
         $data['student_attendance'] = $student_attendance;
      } else {
         $data['student_attendance'] = array();
      }

      if (strtoupper($this->sch_setting_detail->dise_code) == "SCHOLAANGELICUS") {
         $this->load->view('reports/student_report_card_schola', $data);
      } else {
         $this->load->view('reports/student_report_card', $data);
      }
   }

   public function print_cards_per_section()
   {

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/student_information');
      $this->session->set_userdata('subsub_menu', 'Reports/student_information/class_record_per_student');

      $data['title'] = 'Class Record Per Student';
      $class = $this->class_model->get();
      $data['classlist'] = $class;
      $data['sch_setting'] = $this->sch_setting_detail;
      $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
      $data['session_list'] = $this->session_model->getAllSession();
      // $data['conduct_grading_type'] = $this->sch_setting_detail->conduct_grading_type;
      $data['legend_list'] = $this->conduct_model->get_conduct_legend_list();
      $data['school_code'] = $this->sch_setting_detail->dise_code;

      $this->form_validation->set_rules('session_id', $this->lang->line('current_session'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('student_id', $this->lang->line('subject'), 'trim|required|xss_clean');

      if ($this->input->server('REQUEST_METHOD') == "GET") {
         $session = $this->input->post('session_id');
         $grade_level = $this->input->post('class_id');
         $section = $this->input->post('section_id');
         // $student_id = $this->input->post('student_id');
         $data['session_id'] = $session;
         $data['class_id'] = $grade_level;
         $data['section_id']  = $section;
         // $data['student_id'] = $student_id;

         $this->load->view('layout/header', $data);
         $this->load->view('reports/print_cards_per_section', $data);
         $this->load->view('layout/footer', $data);
      } else {
         if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('reports/print_cards_per_section', $data);
            $this->load->view('layout/footer', $data);
         } else {
            $session = $this->input->post('session_id');
            $grade_level = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            // $student_id = $this->input->post('student_id');

            // if (strtolower($this->sch_setting_detail->dise_code) == 'lpms') {
            //    $studentinfo = $this->student_model->get($student_id);
            //    $class_record = $this->gradereport_model->get_student_class_record_restricted_lpms($session, $student_id, $grade_level, $section);
            //    $adviser = $this->classteacher_model->teacherByClassSection($grade_level, $section);
            //    $data['student'] = $studentinfo;
            //    $data['school_year'] = $this->setting_model->getCurrentSessionName();
            //    $data['swh_scores'] = $this->gradereport_model->get_swh_score_quarterly_restricted($this->sch_setting_detail->session_id, $grade_level, $section, $student_id);
            //    $data['resultlist'] = $class_record;
            //    $data['class_adviser'] = $adviser[0]['name'] . ' ' . $adviser[0]['surname'];
            //    $data['codes_table'] = $this->gradereport_model->grade_code_table();

            //    $this->db->select("*");
            //    $this->db->where("session_id", $this->sch_setting_detail->session_id);
            //    $this->db->where("class_id", $grade_level);
            //    $this->db->where("section_id", $section);
            //    $this->db->where("student_id", $student_id);
            //    $student_attendance = $this->db->get("attendance_by_semester")->result_array()[0];

            //    if ($student_attendance) {
            //       $data['student_attendance'] = $student_attendance;
            //    } else {
            //       $data['student_attendance'] = array();
            //    }
            // } else {
            //    $student_conduct = null;
            //    if ($this->sch_setting_detail->conduct_grade_view == 0) {
            //       if ($this->sch_setting_detail->conduct_grading_type == 'letter')
            //          $student_conduct = $this->gradereport_model->get_student_conduct($session, $grade_level, $section, $student_id);
            //       else if ($this->sch_setting_detail->conduct_grading_type == 'number')
            //          $student_conduct = $this->gradereport_model->get_student_conduct_numeric($session, $grade_level, $section, $student_id);
            //    }

            //    $data['student_conduct'] = $student_conduct;
            //    $class_record = $this->gradereport_model->get_student_class_record_unrestricted($session, $student_id, $grade_level, $section);
            // }


            // print_r(json_encode($class_record));die();
            // print_r($class_record);die();

            $grade_level_info = $this->class_model->get_grade_level_info($grade_level);
            $data['quarter_list'] = $this->gradereport_model->get_quarter_list($grade_level_info['term_alias'], $grade_level_info['term_length']);
            // $data['quarter_list'] = $this->gradereport_model->get_quarter_list('Qtr', 4);
            // $data['resultlist'] = $class_record;
            $data['session_id'] = $session;
            $data['class_id'] = $grade_level;
            $data['section_id']  = $section;
            // $data['student_id'] = $student_id;
            // $studentinfo = $this->student_model->get($student_id);
            // $data['student'] = $studentinfo;
            $this->load->view('layout/header', $data);
            $this->load->view('reports/print_cards_per_section', $data);
            $this->load->view('layout/footer', $data);
         }
      }
   }

   public function print_section_cards()
   {
      $session = $this->input->post('session_id');
      $grade_level = $this->input->post('class_id');
      $section = $this->input->post('section_id');

      $this->db->select("
            students.id,
            students.firstname,
            students.lastname,
            students.middlename,
            students.admission_no,
            classes.class,
            sections.section,
            sessions.session,
        ");
      $this->db->where("session_id", $session);
      $this->db->where("class_id", $grade_level);
      $this->db->where("section_id", $section);
      $this->db->join("students", "student_session.student_id = students.id");
      $this->db->join("classes", "student_session.class_id = classes.id");
      $this->db->join("sections", "student_session.section_id = sections.id");
      $this->db->join("sessions", "student_session.session_id = sessions.id");
      $students = $this->db->get("student_session")->result_array();

      $grade_level_info = $this->class_model->get_grade_level_info($grade_level);
      $quarter_list = $this->gradereport_model->get_quarter_list($grade_level_info['term_alias'], $grade_level_info['term_length']);

      foreach ($students as $key => $value) {
         if (strtolower($this->sch_setting_detail->dise_code) == 'lpms') {
            $class_record = $this->gradereport_model->get_student_class_record_restricted_lpms($session, $value['id'], $grade_level, $section);
            $adviser = $this->classteacher_model->teacherByClassSection($grade_level, $section);
            $studentinfo = $this->student_model->get($value['id']);

            $students[$key]['studentinfo'] = $studentinfo;
            $students[$key]['resultlist'] = $class_record;
            $students[$key]['class_adviser'] = $adviser[0]['name'] . ' ' . $adviser[0]['surname'];
            $students[$key]['school_year'] = $this->setting_model->getCurrentSessionName();
            $students[$key]['swh_scores'] = $this->gradereport_model->get_swh_score_quarterly_restricted($this->sch_setting_detail->session_id, $grade_level, $section, $value['id']);
            $students[$key]['codes_table'] = $this->gradereport_model->grade_code_table();

            $student_attendance = $this->gradereport_model->get_student_attendance_by_month($session, $grade_level, $section, $value['id']);

            if ($student_attendance) {
               $students[$key]['student_attendance'] = $student_attendance;
            } else {
               $students[$key]['student_attendance'] = array();
            }

            $data['students'] = $students;
            $this->load->view('reports/print_section_cards_lpms', $data);
         } else {
            if ($this->sch_setting_detail->conduct_grading_type == 'letter')
               $student_conduct = $this->gradereport_model->get_student_conduct($session, $grade_level, $section, $value['id']);
            else if ($this->sch_setting_detail->conduct_grading_type == 'number')
               $student_conduct = $this->gradereport_model->get_student_conduct_numeric($session, $grade_level, $section, $value['id']);

            $class_record = $this->gradereport_model->get_student_class_record_unrestricted($session, $value['id'], $grade_level, $section);

            $student_attendance = $this->gradereport_model->get_student_attendance_by_month($session, $grade_level, $section, $value['id']);

            $students[$key]['student_conduct'] = $student_conduct;
            $students[$key]['resultlist'] = $class_record;
            $students[$key]['student_attendance'] = $student_attendance;
            $students[$key]['quarter_list'] = $quarter_list;

            $data['students'] = $students;
            $this->load->view('reports/print_section_cards', $data);
         }
      }
   }

   public function attendance_by_month()
   {
      // $this->session->set_userdata('top_menu', 'Reports');
      // $this->session->set_userdata('sub_menu', 'Reports/student_information');
      // $this->session->set_userdata('subsub_menu', 'Reports/student_information/class_record_per_student');
      $this->session->set_userdata('top_menu', 'Attendance');
      $this->session->set_userdata('sub_menu', 'subjectattendence/attendance_by_month');

      $data['title'] = 'Class Record Per Student';
      $class = $this->class_model->get();
      $data['classlist'] = $class;
      $data['sch_setting'] = $this->sch_setting_detail;
      $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
      $data['session_list'] = $this->session_model->getAllSession();
      $data['conduct_grading_type'] = $this->sch_setting_detail->conduct_grading_type;
      $data['legend_list'] = $this->conduct_model->get_conduct_legend_list();

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
         //-- ToDo: add new field (has_acp) from classes table for ACP values
         $data['section_id']  = $section;
         $data['student_id'] = $student_id;

         $this->load->view('layout/header', $data);
         $this->load->view('reports/attendance_by_month', $data);
         $this->load->view('layout/footer', $data);
      } else {
         if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('reports/attendance_by_month', $data);
            $this->load->view('layout/footer', $data);
         } else {
            $session = $this->input->post('session_id');
            $grade_level = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $student_id = $this->input->post('student_id');
            $term_id = $this->input->post('quarter_id');

            $student_conduct = null;
            if ($this->sch_setting_detail->conduct_grade_view == 0) {
               if ($this->sch_setting_detail->conduct_grading_type == 'letter')
                  $student_conduct = $this->gradereport_model->get_student_conduct($session, $grade_level, $section, $student_id);
               else if ($this->sch_setting_detail->conduct_grading_type == 'number')
                  $student_conduct = $this->gradereport_model->get_student_conduct_numeric($session, $grade_level, $section, $student_id);
            }

            $data['student_conduct'] = $student_conduct;

            $class_record = $this->gradereport_model->get_student_class_record_unrestricted($session, $student_id, $grade_level, $section);
            // print_r(json_encode($class_record));die();
            // print_r($class_record);die();

            // print_r($data);
            // die();

            $grade_level_info = $this->class_model->get_grade_level_info($grade_level);
            $data['quarter_list'] = $this->gradereport_model->get_quarter_list($grade_level_info['term_alias'], $grade_level_info['term_length']);
            // $data['quarter_list'] = $this->gradereport_model->get_quarter_list('Qtr', 4);

            $data['resultlist'] = $class_record;
            $data['session_id'] = $session;
            $data['class_id'] = $grade_level;
            $data['section_id']  = $section;
            $data['student_id'] = $student_id;
            $studentinfo = $this->student_model->get($student_id);
            $data['student'] = $studentinfo;

            // print_r($data);
            // die();

            $data['month_days_list'] = $this->gradereport_model->get_month_days_list();
            $data['term_id'] = $term_id;

            // print_r("<pre>");
            // print_r($data['month_days_list']);
            // print_r("<pre>");

            // print_r($data['month_days_list']);
            // die();

            $student_attendance = $this->gradereport_model->get_student_attendance_by_month($session, $grade_level, $section, $student_id);

            // print_r("<pre>");
            // print_r($student_attendance);
            // print_r("<pre>");
            // die();

            if ($student_attendance) {
               $data['student_attendance'] = $student_attendance;
            } else {
               $data['student_attendance'] = array();
            }
            $this->load->view('layout/header', $data);
            $this->load->view('reports/attendance_by_month', $data);
            $this->load->view('layout/footer', $data);
         }
      }
   }

   public function attendance_by_semester_lpms()
   {
      $this->session->set_userdata('top_menu', 'Attendance');
      $this->session->set_userdata('sub_menu', 'subjectattendence/attendance_by_month');

      $data['title'] = 'Class Record Per Student';
      $class = $this->class_model->get();
      $data['classlist'] = $class;
      $data['sch_setting'] = $this->sch_setting_detail;
      $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
      $data['session_list'] = $this->session_model->getAllSession();

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
         $this->load->view('reports/attendance_by_semester_lpms', $data);
         $this->load->view('layout/footer', $data);
      } else {
         if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('reports/attendance_by_semester_lpms', $data);
            $this->load->view('layout/footer', $data);
         } else {
            $session = $this->input->post('session_id');
            $grade_level = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $student_id = $this->input->post('student_id');

            $class_record = $this->gradereport_model->get_student_class_record_unrestricted($session, $student_id, $grade_level, $section);
            // print_r(json_encode($class_record));
            // die();

            $grade_level_info = $this->class_model->get_grade_level_info($grade_level);
            $data['quarter_list'] = $this->gradereport_model->get_quarter_list($grade_level_info['term_alias'], $grade_level_info['term_length']);
            // $data['quarter_list'] = $this->gradereport_model->get_quarter_list('Qtr', 4);
            $data['resultlist'] = $class_record;
            $data['session_id'] = $session;
            $data['class_id'] = $grade_level;
            $data['section_id']  = $section;
            $data['student_id'] = $student_id;
            $studentinfo = $this->student_model->get($student_id);
            $data['student'] = $studentinfo;

            $student_attendance = $this->gradereport_model->get_student_attendance_by_semester($session, $grade_level, $section, $student_id);

            if ($student_attendance) {
               $data['student_attendance'] = $student_attendance;
            } else {
               $data['student_attendance'] = array();
            }

            $this->load->view('layout/header', $data);
            $this->load->view('reports/attendance_by_semester_lpms', $data);
            $this->load->view('layout/footer', $data);
         }
      }
   }

   public function save_attendance()
   {
      $data['session_id'] = $_POST['session_id'];
      $data['class_id'] = $_POST['class_id'];
      $data['section_id'] = $_POST['section_id'];
      $data['student_id'] = $_POST['student_id'];

      $attendance = $_POST['present'];
      $absent = $_POST['absent'];
      $tardy = $_POST['tardy'];
      $acp = isset($_POST['acp']) ? $_POST['acp'] : "";

      foreach ($attendance as $key => $value) {
         if (!$value) {
            $attendance[$key] = 0;
         }
      }
      foreach ($absent as $key => $value) {
         if (!$value) {
            $absent[$key] = 0;
         }
      }
      foreach ($tardy as $key => $value) {
         if (!$value) {
            $tardy[$key] = 0;
         }
      }
      $data['attendance'] = json_encode($attendance);
      $data['absent'] = json_encode($absent);
      $data['tardy'] = json_encode($tardy);
      // $data['acp'] = $acp;

      $this->db->select("*");
      $this->db->where("session_id", $data['session_id']);
      $this->db->where("class_id", $data['class_id']);
      $this->db->where("section_id", $data['section_id']);
      $this->db->where("student_id", $data['student_id']);
      $checker = $this->db->get("attendance_by_month")->result_array();

      // $data_array = array(
      //    $data['session_id'],
      //    $data['class_id'],
      //    $data['section_id'],
      //    $data['student_id'],
      // );

      // $data_string = join("/", $data_array);
      // echo "<form action='" . site_url('report/attendance_by_month/') . "' method='POST' id='theForm'>";
      // echo "<input type='hidden' name='session_id' value='" . $data['session_id'] . "'>";
      // echo "<input type='hidden' name='class_id' value='" . $data['class_id'] . "'>";
      // echo "<input type='hidden' name='section_id' value='" . $data['section_id'] . "'>";
      // echo "<input type='hidden' name='student_id' value='" . $data['student_id'] . "'>";
      // echo "</form>";

      if ($checker) {
         // echo "update";
         $update_data['attendance'] = $data['attendance'];
         $update_data['absent'] = $data['absent'];
         $update_data['tardy'] = $data['tardy'];
         $update_data['id'] = $checker[0]['id'];
         $this->lesson_model->lms_update("attendance_by_month", $update_data);

         //echo "<script>alert('Updated Successfully');document.getElementById('theForm').submit();</script>";
      } else {
         // echo "new";
         $this->lesson_model->lms_create("attendance_by_month", $data, FALSE);
         //echo "<script>alert('Saved Successfully');document.getElementById('theForm').submit();</script>";
      }

      $msg = array('status' => 'success', 'error' => '', 'message' => 'Attendance Saved');
      echo json_encode($msg);
   }

   public function save_attendance_lpms()
   {
      $data['session_id'] = $_POST['session_id'];
      $data['class_id'] = $_POST['class_id'];
      $data['section_id'] = $_POST['section_id'];
      $data['student_id'] = $_POST['student_id'];

      $first = $_POST['first'];
      $second = $_POST['second'];
      $third = $_POST['third'];

      foreach ($first as $key => $value) {
         if (!$value) {
            $first[$key] = 0;
         }
      }
      foreach ($second as $key => $value) {
         if (!$value) {
            $second[$key] = 0;
         }
      }
      foreach ($third as $key => $value) {
         if (!$value) {
            $third[$key] = 0;
         }
      }
      $data['first_trim'] = json_encode($first);
      $data['second_trim'] = json_encode($second);
      $data['third_trim'] = json_encode($third);

      $this->db->select("*");
      $this->db->where("session_id", $data['session_id']);
      $this->db->where("class_id", $data['class_id']);
      $this->db->where("section_id", $data['section_id']);
      $this->db->where("student_id", $data['student_id']);
      $checker = $this->db->get("attendance_by_semester")->result_array();

      if ($checker) {
         // echo "update";
         $update_data['first_trim'] = $data['first_trim'];
         $update_data['second_trim'] = $data['second_trim'];
         $update_data['third_trim'] = $data['third_trim'];
         $update_data['id'] = $checker[0]['id'];
         $this->lesson_model->lms_update("attendance_by_semester", $update_data);
      } else {
         $this->lesson_model->lms_create("attendance_by_semester", $data, FALSE);
      }

      $msg = array('status' => 'success', 'error' => '', 'message' => 'Attendance Saved');
      echo json_encode($msg);
   }

   public function class_record_banig()
   {
      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/student_information');
      $this->session->set_userdata('subsub_menu', 'Reports/student_information/class_record_banig');

      $data['title'] = 'Class Record Banig';
      $class = $this->class_model->get();
      $data['classlist'] = $class;
      $data['sch_setting'] = $this->sch_setting_detail;
      $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
      $data['session_list'] = $this->session_model->getAllSession();

      $this->form_validation->set_rules('session_id', $this->lang->line('current_session'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
      $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

      if ($this->input->server('REQUEST_METHOD') == "GET") {
         $this->load->view('layout/header', $data);
         $this->load->view('reports/class_record_banig', $data);
         $this->load->view('layout/footer', $data);
      } else {
         if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('reports/class_record_banig', $data);
            $this->load->view('layout/footer', $data);
         } else {
            $session = $this->input->post('session_id');
            $grade_level = $this->input->post('class_id');
            $section = $this->input->post('section_id');

            $grade_level_info = $this->class_model->get_grade_level_info($grade_level);
            $data['quarter_list'] = $this->general_model->get_quarter_list($grade_level_info['term_alias'], $grade_level_info['term_length']);
            // $data['quarter_list'] = $this->gradereport_model->get_quarter_list('Qtr', 4);

            $data['subject_list'] = $this->gradereport_model->get_subject_list($grade_level, $session, $section);
            // print_r("CloudPH Debug Mode 2");
            // die();
            $class_record = $this->gradereport_model->generate_Banig($session, $grade_level, $section);
            // print_r($class_record);die();

            // $styleArray = array(
            //     'borders' => array(
            //         'outline' => array(
            //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
            //             'color' => array('argb' => '00000000'),
            //         ),
            //     ),
            // );

            // //-- Dump the data in excel file the force download to user
            // $spreadsheet = new Spreadsheet();
            // $sheet = $spreadsheet->getActiveSheet();
            // $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            // // $sheet->getColumnDimension('A')->setAutoSize(true);
            // $sheet->getStyle('A10:B10')->getAlignment()->setHorizontal('center');
            // // $sheet ->getStyle('A10')->applyFromArray($styleArray);
            // // $sheet ->getStyle('A11')->applyFromArray($styleArray);

            // $sheet->setCellValue('A10', 'NAME OF STUDENT');
            // $sheet->setCellValue('B10', 'GENDER');

            // $writer = new Xlsx($spreadsheet);
            // $filename = $session."_".$grade_level."_".$section.".xlsx";
            // $writer->save($filename);

            // die();

            $data['resultlist'] = $class_record;
            $data['session_id'] = $session;
            $data['class_id'] = $grade_level;
            $data['section_id']  = $section;
            $this->load->view('layout/header', $data);
            $this->load->view('reports/class_record_banig', $data);
            $this->load->view('layout/footer', $data);
         }
      }
   }

   public function conduct_record_per_student()
   {
      // if (!$this->rbac->hasPrivilege('class_record_quarterly', 'can_view')) {
      //     access_denied();
      // }

      $this->session->set_userdata('top_menu', 'Reports');
      $this->session->set_userdata('sub_menu', 'Reports/student_information');
      $this->session->set_userdata('subsub_menu', 'Reports/student_information/class_record_per_student');

      $data['title'] = 'Class Record Per Student';
      $class = $this->class_model->get();
      $data['classlist'] = $class;
      $data['sch_setting'] = $this->sch_setting_detail;
      $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
      $data['session_list'] = $this->session_model->getAllSession();

      $data['conduct_grading_type'] = $this->sch_setting_detail->conduct_grading_type;
      $data['legend_list'] = $this->conduct_model->get_conduct_legend_list();
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

         if (strtolower($data['school_code']) == 'lpms') {
            $this->load->view('layout/header', $data);
            $this->load->view('reports/class_record_per_student_lpms', $data);
            $this->load->view('layout/footer', $data);
         } elseif (strtolower($data['school_code']) == 'ssapamp') {
            $this->load->view('layout/header', $data);
            $this->load->view('reports/conduct_record_per_student_ssapamp', $data);
            $this->load->view('layout/footer', $data);
         } else {
            $this->load->view('layout/header', $data);
            $this->load->view('reports/class_record_per_student', $data);
            $this->load->view('layout/footer', $data);
         }
      } else {
         if ($this->form_validation->run() == false) {
            if (strtolower($data['school_code']) == 'lpms') {
               $this->load->view('layout/header', $data);
               $this->load->view('reports/class_record_per_student_lpms', $data);
               $this->load->view('layout/footer', $data);
            } elseif (strtolower($data['school_code']) == 'ssapamp') {
               $this->load->view('layout/header', $data);
               $this->load->view('reports/conduct_record_per_student_ssapamp', $data);
               $this->load->view('layout/footer', $data);
            } else {
               $this->load->view('layout/header', $data);
               $this->load->view('reports/class_record_per_student', $data);
               $this->load->view('layout/footer', $data);
            }
         } else {
            $session = $this->input->post('session_id');
            $grade_level = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $student_id = $this->input->post('student_id');
            $student_conduct = null;

            if ($this->sch_setting_detail->conduct_grade_view == 0) {
               if (strtolower($data['school_code']) !== 'lpms') {
                  // if (strtolower($data['school_code']) == 'SSAPAMP' and $grade_level=="1") {
                  //    $student_conduct = get_student_checklist($session, $grade_level, $section, $student_id)
                  // } else {
                  if ($this->sch_setting_detail->conduct_grading_type == 'letter')
                     $student_conduct = $this->gradereport_model->get_student_conduct($session, $grade_level, $section, $student_id);
                  else if ($this->sch_setting_detail->conduct_grading_type == 'number')
                     $student_conduct = $this->gradereport_model->get_student_conduct_numeric($session, $grade_level, $section, $student_id);
                  // }

               } else {
               }
            }

            $data['student_conduct'] = $student_conduct;

            // print_r(json_encode($student_id));die();

            $data['quarter_list'] = $this->gradereport_model->get_quarter_list();

            $data['session_id'] = $session;
            $data['class_id'] = $grade_level;
            $data['section_id']  = $section;
            $data['student_id'] = $student_id;
            $studentinfo = $this->student_model->get($student_id);
            $data['student'] = $studentinfo;
            $prekinder = "";
            $class_record = null;


            if (strtolower($data['school_code']) == 'lpms') {
               $class_record = $this->gradereport_model->get_student_class_record_unrestricted_lpms($session, $student_id, $grade_level, $section);
               $data['resultlist'] = $class_record;

               // print_r($data);
               // die();

               $this->load->view('layout/header', $data);
               $this->load->view('reports/class_record_per_student_lpms', $data);
               $this->load->view('layout/footer', $data);
            } else {
               if (strtolower($data['school_code']) == 'ssapamp') {

                  $resultx = $this->grading_ssapamp_model->getTermLength('pre-kinder');
                  $term = $resultx[0]->term_length;
                  $data['term_length'] = $term;
                  $result1 = $this->grading_ssapamp_model->getLevelId('pre-kinder');
                  $prekinder = $result1[0]->id;

                  $result2 = $this->grading_ssapamp_model->getLevelId('kindergarten');
                  $kinder = $result2[0]->id;

                  $data['prekinderid'] = $prekinder;

                  if ($grade_level == $prekinder or $grade_level == $kinder) {

                     // print_r($data);
                     // die();

                     $legendlist =  $this->grading_conduct_ssapamp_model->getLegend();

                     // print_r($data);
                     // die();

                     $data['legend_list'] = $legendlist;
                     //$studentid,$level,$section,$session

                     $class_record = $this->grading_studentconduct_ssapamp_model->get_student_conduct_list($student_id, $grade_level, $section, $session, 1);
                     $objarray = array();
                     $obj_ave_array = array();
                     $lettergradearray = array();
                     $pergradearray = array();
                     $totalgrade = 0;

                     for ($s = 1; $s <= $term; $s++) {
                        // $$variable = "sem" . $s;
                        $variable = "sem" . $s;
                        $semrecord = $this->grading_studentconduct_ssapamp_model->get_student_conduct_list($student_id, $grade_level, $section, $session, $s);
                        $semtotalrecord = $this->grading_studentconduct_ssapamp_model->get_student_conduct_average_list($student_id, $grade_level, $section, $session, $s);
                        // $objarray[$$variable] = $semrecord;
                        // $objarray[] = $semrecord;
                        // $$variable[]=array();
                        $pertotal = 0;
                        $itemctr = 0;
                        $pertotalave = 0;
                        $perlettergrade = "";

                        foreach ($semrecord as $semrows) {
                           $itemctr++;
                           $desc = $semrows->description;
                           $grade = $semrows->grade;
                           $lg = $semrows->LG;
                           $pertotal += $grade;
                        }
                        $pertotalave = $pertotal / $itemctr;
                        $pergradearray[] = $pertotalave;

                        $perlettergrade = $this->getConductLetterGrade($pertotalave, $legendlist);
                        $lettergradearray[] = $perlettergrade;

                        $totalgrade += $pertotal / $itemctr;

                        // $objarray[$variable]=$semrecord;
                        $objarray[] = $semrecord;
                        $obj_ave_array[] = $semtotalrecord;
                        $data[$variable] = $semrecord;

                        // }
                        // $sr['sem' . $s] = $$variable;
                        // $sem = "sem" . $s;
                        // $data[$sem] = $semrecord; 
                        // foreach ($semrecord as $recrows) {
                        // }
                        // $objarray[$s] = array();  
                        // $objarray[$s] = $semrecord;                     
                     }

                     $data['semesters'] = $objarray;
                     $data['semave'] = $obj_ave_array;
                     $data['lettergradearray'] = $lettergradearray;
                     $data['pergradearray'] = $pergradearray;
                     $average_grade = $totalgrade / $term;
                     $lettergrade = $this->getConductLetterGrade($average_grade, $legendlist);
                     $data['finallettergrade'] = $lettergrade;
                     $data['totalgrade'] = $totalgrade / $term;
                  } else {
                     // print_r($data);
                     // die();
                     $class_record = $this->gradereport_model->get_student_class_record_unrestricted($session, $student_id, $grade_level, $section);
                  }
               } else {

                  // print_r($data);
                  // die();

                  $class_record = $this->gradereport_model->get_student_class_record_unrestricted($session, $student_id, $grade_level, $section);
               }
               $namevar = "namevar";
               $data[$namevar] = "hello";

               $data['resultlist'] = $class_record;
               $this->load->view('layout/header', $data);
               // var_dump($data);
               if (strtolower($data['school_code']) == 'ssapamp') {
                  $this->load->view('reports/conduct_record_per_student_ssapamp', $data);
               } else {
                  $this->load->view('reports/class_record_per_student', $data);
               }

               $this->load->view('layout/footer', $data);
            }
         }
      }
   }

   public function getConductLetterGrade($grade, $legendrows)
   {
      $outlettergrade = "";
      $gradeval = round($grade, 0);
      foreach ($legendrows as $rows) {
         $letter = $rows->conduct_grade;
         $mingrade = $rows->mingrade;
         $maxgrade = $rows->maxgrade;
         $mingradeval = intval($mingrade);
         $maxgradeval = intval($maxgrade);
         $maxgrade = $rows->maxgrade;

         if ($gradeval >= $mingradeval and $gradeval <= $maxgradeval) {
            $outlettergrade = $letter;
         }
      }
      return $outlettergrade;
   }
}

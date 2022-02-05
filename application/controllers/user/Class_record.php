<?php

if (!defined('BASEPATH')) {
   exit('No direct script access allowed');
}

class Class_record extends Student_Controller
{

   public function __construct()
   {
      parent::__construct();
      $this->load->model('student_model');
      $this->load->model('gradereport_model');
      $this->load->model('setting_model');
      $this->load->model('conduct_model');
      $this->load->model('class_model');
      $this->load->model('grading_ssapamp_model');
      $this->load->model('grading_studentgrade_ssapamp_model');
      $this->load->model('grading_checklist_ssapamp_model');

      $this->sch_setting_detail = $this->setting_model->getSetting();
   }

   public function index()
   {
      $this->session->set_userdata('top_menu', 'Class_Record');

      $data['title'] = 'Grades';
      $student_current_class = $this->customlib->getStudentCurrentClsSection();
      $student_id = $this->customlib->getStudentSessionUserID();
      // print_r("CloudPH Debug Mode");die();
      $grade_level_info = $this->class_model->get_grade_level_info($student_current_class->class_id);
      $data['quarter_list'] = $this->gradereport_model->get_quarter_list($grade_level_info['term_alias'], $grade_level_info['term_length']);

      // $data['quarter_list'] = $this->gradereport_model->get_quarter_list();

      $data['legend_list'] = $this->conduct_model->get_conduct_legend_list();
      $data['school_code'] = strtolower($this->sch_setting_detail->dise_code);
      $data['month_days_list'] = $this->gradereport_model->get_month_days_list();
      $data['show_general_average'] = $this->sch_setting_detail->grading_general_average;
      $data['show_letter_grade'] = $this->sch_setting_detail->show_letter_grade;
      $data['show_average_column'] = $this->sch_setting_detail->show_average_column;
      $data['terms_allowed'] = $this->gradereport_model->get_terms_allowed($this->sch_setting_detail->session_id, $student_id);

      if (strtolower($this->sch_setting_detail->dise_code) == 'lpms') {
         $studentinfo = $this->student_model->get($student_id);
         $class_record = $this->gradereport_model->get_student_class_record_restricted_lpms($this->sch_setting_detail->session_id, $student_id, $student_current_class->class_id, $student_current_class->section_id);
         $adviser = $this->classteacher_model->teacherByClassSection($student_current_class->class_id, $student_current_class->section_id);
         $data['student'] = $studentinfo;
         $data['school_year'] = $this->setting_model->getCurrentSessionName();
         $data['swh_scores'] = $this->gradereport_model->get_swh_score_quarterly_restricted($this->sch_setting_detail->session_id, $student_current_class->class_id, $student_current_class->section_id, $student_id);
         $data['resultlist'] = $class_record;
         $data['class_adviser'] = $adviser[0]['name'] . ' ' . $adviser[0]['surname'];
         $data['codes_table'] = $this->gradereport_model->grade_code_table();

         // $this->db->select("*");
         // $this->db->where("session_id", $this->sch_setting_detail->session_id);
         // $this->db->where("class_id", $student_current_class->class_id);
         // $this->db->where("section_id", $student_current_class->section_id);
         // $this->db->where("student_id", $student_id);
         // $student_attendance = $this->db->get("attendance_by_semester")->result_array()[0];

         $student_attendance = $this->gradereport_model->get_student_attendance_by_semester($this->sch_setting_detail->session_id, $student_current_class->class_id, $student_current_class->section_id, $student_id);

         if ($student_attendance) {
            $data['student_attendance'] = $student_attendance;
         } else {
            $data['student_attendance'] = array();
         }

         $this->load->view('layout/student/header', $data);
         $this->load->view('user/class_record/class_record_lpms', $data);
         $this->load->view('layout/student/footer', $data);
      } else if (strtolower($data['school_code']) == 'ssapamp') {

         $result1 = $this->grading_ssapamp_model->getLevelId('Pre-Kinder');
         $prekinder = $result1[0]->id;
         $grade_level = $student_current_class->class_id;
         $data['prekinderid'] = $prekinder;
         $data['studentid'] = $student_id;
         $data['session'] = $this->sch_setting_detail->session_id;
         $data['class'] = $student_current_class->class_id;
         $data['section'] = $student_current_class->section_id;

         $student_attendance = $this->gradereport_model->get_student_attendance_by_month($this->sch_setting_detail->session_id, $student_current_class->class_id, $student_current_class->section_id, $student_id);

         if ($student_attendance) {
            $data['student_attendance'] = $student_attendance;
         } else {
            $data['student_attendance'] = array();
         }

         $data['terms_allowed'] = $this->gradereport_model->get_terms_allowed($this->sch_setting_detail->session_id, $student_id);
         $data['ssap_conduct'] = $this->gradereport_model->get_conduct_ssapamp_restricted($this->sch_setting_detail->session_id, $student_current_class->class_id, $student_current_class->section_id, $student_id);

         // print_r($data['ssap_conduct']);
         // die();

         if ($grade_level == $prekinder) {
            $class_record = $this->grading_studentgrade_ssapamp_model->get_student_checklist($this->sch_setting_detail->session_id, $student_current_class->class_id, $student_current_class->section_id, $student_id);
            $data['resultlist'] = $class_record;

            $legend = $this->grading_checklist_ssapamp_model->getLegend();
            $data['legend_list'] = $legend;
            $allow1 = $this->grading_ssapamp_model->getAllowed($student_id, $this->sch_setting_detail->session_id, 1);
            $allow2 = $this->grading_ssapamp_model->getAllowed($student_id, $this->sch_setting_detail->session_id, 2);

            // print_r($data);
            // die();

            $data['allow1'] = $allow1;
            $data['allow2'] = $allow2;

            $this->load->view('layout/student/header', $data);
            $this->load->view('user/class_record/class_record_ssap', $data);
            $this->load->view('layout/student/footer', $data);
         } else {
            $data['codes_table'] = $this->gradereport_model->grade_code_table();
            $class_record = $this->gradereport_model->get_student_class_record($this->sch_setting_detail->session_id, $student_id, $student_current_class->class_id, $student_current_class->section_id);
            $data['resultlist'] = $class_record;

            // print_r($data['resultlist']);
            // die();

            $this->load->view('layout/student/header', $data);
            $this->load->view('user/class_record/class_record', $data);
            $this->load->view('layout/student/footer', $data);
         }
      } else {
         $data['codes_table'] = $this->gradereport_model->grade_code_table();

         $student_attendance = $this->gradereport_model->get_student_attendance_by_month($this->sch_setting_detail->session_id, $student_current_class->class_id, $student_current_class->section_id, $student_id);

         if ($student_attendance) {
            $data['student_attendance'] = $student_attendance;
         } else {
            $data['student_attendance'] = array();
         }

         $data['month_days_list'] = $this->gradereport_model->get_month_days_list();

         $class_record = $this->gradereport_model->get_student_class_record($this->sch_setting_detail->session_id, $student_id, $student_current_class->class_id, $student_current_class->section_id);
         $data['resultlist'] = $class_record;
         $data['conduct_grading_type'] = $this->sch_setting_detail->conduct_grading_type;

         $student_conduct = null;
         if ($this->sch_setting_detail->conduct_grade_view == 0) {
            if ($this->sch_setting_detail->conduct_grading_type == 'letter')
               $student_conduct = $this->gradereport_model->get_student_conduct($this->sch_setting_detail->session_id, $student_current_class->class_id, $student_current_class->section_id, $student_id);
            else if ($this->sch_setting_detail->conduct_grading_type == 'number')
               $student_conduct = $this->gradereport_model->get_student_conduct_numeric($this->sch_setting_detail->session_id, $student_current_class->class_id, $student_current_class->section_id, $student_id);
         }

         $data['student_conduct'] = $student_conduct;

         $this->load->view('layout/student/header', $data);
         $this->load->view('user/class_record/class_record', $data);
         $this->load->view('layout/student/footer', $data);
      }
   }
}

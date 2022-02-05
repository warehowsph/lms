<?php

if (!defined('BASEPATH')) {
   exit('No direct script access allowed');
}

class Grading_model extends JOE_Model
{
   public function __construct()
   {
      parent::__construct();

      //-- Load database for writing
      $this->writedb = $this->load->database('write_db', TRUE);
   }

   public function get_student_list($current_session, $grade_level, $section)
   {
      $sql = "select students.id, concat(lastname, ', ', firstname, ' ', middlename) as name 
              from students
              left join student_session on students.id = student_session.student_id  and student_session.session_id = $current_session
              where student_session.class_id = $grade_level
              and student_session.section_id = $section
              order by gender desc, lastname";

      $query = $this->db->query($sql);
      return $query->result_array();
   }

   public function get_swh_items()
   {
      $query = $this->db->query("select id, sub from grading_swh order by sort_order");
      print_r($query);
      die();
      return $query->result_array();
   }

   public function get_lpms_swh_data($current_session, $quarter, $grade_level, $section)
   {
      $sql = "SELECT *
              FROM grading_swh_scores
              WHERE school_year_id = $current_session 
              and quarter_id = $quarter 
              and grade_level_id = $grade_level 
              and section_id = $section";

      $query = $this->db->query($sql);
      return $query->result_array();
   }
}

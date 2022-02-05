<?php
class Conduct_model extends MY_Model
{
   public function __construct()
   {
      parent::__construct();

      //-- Load database for writing
      $this->writedb = $this->load->database('write_db', TRUE);
   }

   public function get_conduct_legend_list()
   {
      $this->db->select('id, conduct_grade, description');
      $this->db->from('grading_conduct_legend');
      $this->db->order_by('id', "asc");
      $query = $this->db->get();
      $return = $query->result();

      return $return;
   }

   public function get_student_conduct_record($session, $quarter, $grade_level, $section, $student, $teacher)
   {
      // $sql = "SELECT 
      //           grading_conduct_indicators.id,
      //           grading_conduct.conduct,
      //           grading_conduct.conduct_num,
      //           grading_conduct_deped_indicators.indicator AS deped_indicators, 
      //           grading_conduct_core_indicators.core_indicator AS core_indicator,
      //           grading_conduct_indicators.indicator AS indicators 
      //           FROM grading_conduct_deped_indicators
      //           LEFT JOIN grading_conduct_core_indicators ON grading_conduct_core_indicators.deped_indicator_id = grading_conduct_deped_indicators.id
      //           LEFT JOIN grading_conduct_indicators ON grading_conduct_indicators.core_indicator_id = grading_conduct_core_indicators.id
      //           LEFT JOIN grading_conduct ON grading_conduct.indicator_id = grading_conduct_indicators.id
      //           AND grading_conduct.school_year = '$session' AND grading_conduct.quarter = '$quarter' 
      //           AND grading_conduct.grade = $grade_level AND grading_conduct.section_id = $section  
      //           AND grading_conduct.student_id = $student AND grading_conduct.teacher_id = $teacher 
      //           ORDER BY grading_conduct_indicators.id ASC";

      $sql = "SELECT 
               grading_conduct_indicators.id,
               grading_conduct.conduct,
               grading_conduct.conduct_num,
               grading_conduct_deped_indicators.indicator AS deped_indicators, 
               grading_conduct_indicators.indicator AS indicators 
               FROM grading_conduct_deped_indicators
               LEFT JOIN grading_conduct_indicators ON grading_conduct_indicators.core_indicator_id = grading_conduct_deped_indicators.id
               LEFT JOIN grading_conduct ON grading_conduct.indicator_id = grading_conduct_indicators.id
               AND grading_conduct.school_year = '$session' AND grading_conduct.quarter = '$quarter' 
               AND grading_conduct.grade = $grade_level AND grading_conduct.section_id = $section  
               AND grading_conduct.student_id = $student AND grading_conduct.teacher_id = $teacher 
               ORDER BY grading_conduct_indicators.id ASC";

      // print_r($sql);
      // die();

      $query = $this->db->query($sql);
      // print_r($this->db->last_query());
      // die();
      // print_r($this->db->error());die();
      return $query->result();
   }

   public function get_student_conduct_record_numeric($schoolyear, $quarter, $gradelevel, $section, $teacher)
   {
      $subquery = "";
      $quarter_columns = "";

      $sql = "SELECT students.id, students.roll_no, student_session.session_id, CONCAT(lastname, ', ', firstname, ' ', middlename) AS student_name, gender, conduct_num 
                FROM students 
                LEFT JOIN student_session ON student_session.student_id = students.id 
                LEFT JOIN grading_conduct_numeric GCN ON GCN.student_id = student_session.student_id 
                AND GCN.school_year = student_session.session_id 
                AND GCN.grade_level = student_session.class_id 
                AND GCN.section_id = student_session.section_id 
                AND GCN.quarter = $quarter 
                AND GCN.teacher_id = $teacher
                WHERE student_session.session_id = $schoolyear 
                AND student_session.class_id = $gradelevel 
                AND student_session.section_id = $section                
                ORDER BY gender DESC, student_name ASC";

      // print_r($sql);
      // die();

      $query = $this->db->query($sql);
      return $query->result();
   }

   public function save_conduct_grades($data)
   {
      $this->writedb->trans_start(); # Starting Transaction
      $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well

      $this->writedb->where('school_year', $data["school_year"]);
      $this->writedb->where('quarter', $data["quarter"]);
      $this->writedb->where('student_id', $data["student_id"]);
      $this->writedb->where('teacher_id', $data["teacher_id"]);
      $this->writedb->where('indicator_id', $data["indicator_id"]);
      $this->writedb->delete('grading_conduct');

      $this->writedb->insert('grading_conduct', $data);

      $this->writedb->trans_complete(); # Completing transaction

      if ($this->writedb->trans_status() === false) {
         # Something went wrong.
         $this->writedb->trans_rollback();
         return false;
      } else {
         return true;
      }
   }

   public function save_conduct_grades_numeric($data)
   {
      $this->writedb->trans_start(); # Starting Transaction
      $this->writedb->trans_strict(false);

      // print_r($data);

      $this->writedb->where('school_year', $data[0]["school_year"]);
      $this->writedb->where('quarter', $data[0]["quarter"]);
      $this->writedb->where('teacher_id', $data[0]["teacher_id"]);
      $this->writedb->where('grade_level', $data[0]["grade_level"]);
      $this->writedb->where('section_id', $data[0]["section_id"]);
      $this->writedb->delete('grading_conduct_numeric');

      $this->writedb->insert_batch('grading_conduct_numeric', $data);

      $this->writedb->trans_complete(); # Completing transaction

      if ($this->writedb->trans_status() === false) {
         # Something went wrong.
         $this->writedb->trans_rollback();
         return false;
      } else {
         return true;
      }
   }
}

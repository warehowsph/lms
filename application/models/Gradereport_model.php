<?php

if (!defined('BASEPATH')) {
   exit('No direct script access allowed');
}

class Gradereport_model extends CI_Model
{
   public function __construct()
   {
      parent::__construct();

      //-- Load database for writing
      $this->writedb = $this->load->database('write_db', TRUE);
   }

   public function get($table, $id = null)
   {
      $this->db->select()->from($table);

      if ($id != null)
         $this->db->where('id', $id);

      $this->db->order_by('id');
      $query = $this->db->get();

      if ($id != null)
         return $query->row_array();
      else
         return $query->result_array();
   }

   public function add($table, $data)
   {
      // var_dump($data);die;

      if (isset($data['id'])) {
         $this->writedb->where('id', $data['id']);
         $this->writedb->update($table, $data);
      } else {
         $this->writedb->insert($table, $data);
      }
   }

   public function remove($table, $id)
   {
      $this->writedb->trans_start(); # Starting Transaction
      $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
      //=======================Code Start===========================
      $this->writedb->where('id', $id);
      $this->writedb->delete($table);

      // $message   = DELETE_RECORD_CONSTANT . " On ".$table." id " . $id;
      // $action    = "Delete";
      // $record_id = $id;
      // $this->log($message, $record_id, $action);
      //======================Code End==============================
      $this->writedb->trans_complete(); # Completing transaction
      /*Optional*/
      if ($this->writedb->trans_status() === false) {
         # Something went wrong.
         $this->writedb->trans_rollback();
         return false;
      } else {
         //return $return_value;
      }
   }

   function check_data_exists($data, $table, $field)
   {
      $this->db->where($field, $data[$field]);
      $query = $this->db->get($table);

      if ($query->num_rows() > 0) {
         return TRUE;
      } else {
         return FALSE;
      }
   }

   public function get_class_record($school_year, $quarter, $grade_level, $section)
   {
      $subject_columns = "";
      $subquery = "";
      $average_column = "";
      $colcount = 0;

      $resultdata = $this->get_subject_list($grade_level, $school_year, $section);

      foreach ($resultdata as $row) {
         if (!empty($subject_columns)) {
            $subject_columns .= ", IFNULL(tbl" . $row->subject_id . ".quarterly_grade, 0) AS '" . $row->subject . "'";

            if ($row->in_average) {
               $average_column .= "+IFNULL(tbl" . $row->subject_id . ".quarterly_grade, 0)";
               $colcount++;
            }
         } else {
            $subject_columns .= " IFNULL(tbl" . $row->subject_id . ".quarterly_grade, 0) AS '" . $row->subject . "'";

            if ($row->in_average) {
               $average_column .= "IFNULL(tbl" . $row->subject_id . ".quarterly_grade, 0)";
               $colcount++;
            }
         }

         if ($row->transmuted)
            $quarterly_grade = "fn_transmuted_grade(ROUND(IFNULL(SUM(((total_scores/tot_highest_score)*100) * wspercent), 0), 2)) AS quarterly_grade";
         // $quarterly_grade = "fn_transmuted_grade(ROUND(CAST(IFNULL(SUM(((total_scores/tot_highest_score)*100) * wspercent), 0), 2) AS DECIMAL(8,1))) AS quarterly_grade";
         // $quarterly_grade = "IFNULL(fn_transmuted_grade(ROUND(SUM(((total_scores/tot_highest_score)*100) * wspercent), 2)), 0) AS quarterly_grade";
         else
            $quarterly_grade = "CASE
                                    WHEN MOD(SUM(((total_scores/tot_highest_score)*100) * wspercent), 1) = 0.5
                                      THEN ROUND(SUM(((total_scores/tot_highest_score)*100) * wspercent) + 0.1)
                                      ELSE ROUND(SUM(((total_scores/tot_highest_score)*100) * wspercent))
                                    END AS quarterly_grade";
         //$quarterly_grade = "IFNULL(ROUND(SUM(((total_scores/tot_highest_score)*100) * wspercent), 0), 0) AS quarterly_grade";

         $subquery .= " LEFT JOIN ( 
                             SELECT school_year, quarter, student_id, grade_level, section_id, subject_id, " . $quarterly_grade . "                            
                             FROM 
                             (
                               SELECT school_year, quarter, student_id, grade AS grade_level, section_id, subject_id, SUM(score) AS total_scores, 
                               SUM(highest_score) AS tot_highest_score, criteria_id, label AS criteria_label, (ws/100) AS wspercent 
                               FROM vw_class_record 
                               WHERE subject_id = " . $row->subject_id . " 
                               AND section_id  = " . $section . " 
                               AND grade  = " . $grade_level . " 
                               AND school_year = " . $school_year . " 
                               AND quarter = " . $quarter . " 
                               GROUP BY student_id, criteria_id, label 
                             ) tbl                              
                             GROUP BY school_year, quarter, student_id 
                           ) tbl" . $row->subject_id . " ON tbl" . $row->subject_id . ".student_id = students.id";
      }

      // $average_column = " AVG(".$average_column.") AS average";
      $average_column = " ((" . $average_column . ")/" . $colcount . ") AS average";
      // $average_column = " ROUND(CAST(((" . $average_column . ")/" . $colcount . ") AS DECIMAL(8,1))) AS average";

      $sql = "SELECT CONCAT(UPPER(lastname), ', ', UPPER(firstname), ' ', UPPER(middlename)) AS student_name, UPPER(gender) as gender, " . $subject_columns . ", " . $average_column . " 
                FROM students 
                LEFT JOIN student_session ON student_session.student_id = students.id 
                " . $subquery . " 
                WHERE student_session.class_id = " . $grade_level . " 
                AND student_session.section_id = " . $section . " 
                AND student_session.session_id = " . $school_year . " 
                AND students.is_active = 'yes' 
                ORDER BY gender DESC, student_name ASC";

      // print_r($sql);
      // die();
      $query = $this->db->query($sql);
      // print_r($this->db->last_query());
      // die();
      // print_r($this->db->error());
      // die();
      return $query->result();
   }

   public function get_class_record_lpms($school_year, $quarter, $grade_level, $section)
   {
      $subject_columns = "";
      $subquery = "";
      $average_column = "";
      $average_conduct_column = "";
      $colcount = 0;

      $resultdata = $this->get_subject_list($grade_level, $school_year, $section);

      foreach ($resultdata as $row) {
         if (!empty($subject_columns)) {
            $subject_columns .= ", IFNULL(tbl" . $row->subject_id . ".quarterly_grade, 0) AS '" . $row->subject . "'";
            $subject_columns .= ", IFNULL(tbl" . $row->subject_id . ".quarterly_grade_code, '') AS '" . $row->subject_id . " code'";
            $subject_columns .= ", IFNULL(tbl" . $row->subject_id . "_conduct.quarterly_grade_conduct, '') AS '" . $row->subject_id . " conduct'";

            $average_conduct_column .= "+IFNULL(tbl" . $row->subject_id . "_conduct.quarterly_grade_conduct, 0)";

            if ($row->in_average) {
               $average_column .= "+IFNULL(tbl" . $row->subject_id . ".quarterly_grade, 0)";
               $colcount++;
            }
         } else {
            $subject_columns .= " IFNULL(tbl" . $row->subject_id . ".quarterly_grade, 0) AS '" . $row->subject . "'";
            $subject_columns .= ", IFNULL(tbl" . $row->subject_id . ".quarterly_grade_code, '') AS '" . $row->subject_id . " code'";
            $subject_columns .= ", IFNULL(tbl" . $row->subject_id . "_conduct.quarterly_grade_conduct, '') AS '" . $row->subject_id . " conduct'";

            $average_conduct_column .= "IFNULL(tbl" . $row->subject_id . "_conduct.quarterly_grade_conduct, 0)";

            if ($row->in_average) {
               $average_column .= "IFNULL(tbl" . $row->subject_id . ".quarterly_grade, 0)";
               $colcount++;
            }
         }

         if ($row->transmuted) {
            $quarterly_grade = "fn_transmuted_grade(ROUND(IFNULL(SUM(((total_scores/tot_highest_score)*100) * wspercent), 0), 2)) AS quarterly_grade";
         } else {
            $quarterly_grade = "CASE
                                    WHEN MOD(SUM(((total_scores/tot_highest_score)*100) * wspercent), 1) = 0.5
                                      THEN ROUND(SUM(((total_scores/tot_highest_score)*100) * wspercent) + 0.1)
                                      ELSE ROUND(SUM(((total_scores/tot_highest_score)*100) * wspercent))
                                    END AS quarterly_grade";
         }

         $quarterly_grade_code = "fn_final_transmuted_grade_code(fn_transmuted_grade(ROUND(IFNULL(SUM(((total_scores/tot_highest_score)*100) * wspercent), 0), 2))) AS quarterly_grade_code";

         $subquery .= " LEFT JOIN ( 
                             SELECT school_year, quarter, student_id, grade_level, section_id, subject_id, " . $quarterly_grade . ", " . $quarterly_grade_code . "                          
                             FROM 
                             (
                               SELECT school_year, quarter, student_id, grade AS grade_level, section_id, subject_id, SUM(score) AS total_scores, 
                               SUM(highest_score) AS tot_highest_score, criteria_id, label AS criteria_label, (ws/100) AS wspercent 
                               FROM vw_class_record 
                               WHERE subject_id = " . $row->subject_id . " 
                               AND section_id  = " . $section . " 
                               AND grade  = " . $grade_level . " 
                               AND school_year = " . $school_year . " 
                               AND quarter = " . $quarter . " 
                               AND school_name <> 'Conduct' 
                               GROUP BY student_id, criteria_id, label 
                             ) tbl                              
                             GROUP BY school_year, quarter, student_id 
                           ) tbl" . $row->subject_id . " ON tbl" . $row->subject_id . ".student_id = students.id";

         $quarterly_grade_conduct = "ROUND(IFNULL(SUM(((total_scores/tot_highest_score)*100) * wspercent), 0), 2) AS quarterly_grade_conduct";

         $subquery .= " LEFT JOIN ( 
                              SELECT school_year, quarter, student_id, grade_level, section_id, subject_id, " . $quarterly_grade_conduct . " 
                              FROM 
                              (
                                SELECT school_year, quarter, student_id, grade AS grade_level, section_id, subject_id, SUM(score) AS total_scores, 
                                SUM(highest_score) AS tot_highest_score, criteria_id, label AS criteria_label, (ws/100) AS wspercent 
                                FROM vw_class_record 
                                WHERE subject_id = " . $row->subject_id . " 
                                AND section_id  = " . $section . " 
                                AND grade  = " . $grade_level . " 
                                AND school_year = " . $school_year . " 
                                AND quarter = " . $quarter . " 
                                AND school_name = 'Conduct' 
                                GROUP BY student_id, criteria_id, label 
                              ) tbl                              
                              GROUP BY school_year, quarter, student_id 
                            ) tbl" . $row->subject_id . "_conduct ON tbl" . $row->subject_id . "_conduct.student_id = students.id";
      }

      // $average_column = " AVG(".$average_column.") AS average";
      $average_column_code = " fn_final_transmuted_grade_code((" . $average_column . ")/" . $colcount . ") AS average_code";
      $average_column = " ROUND((" . $average_column . ")/" . $colcount . ") AS average";
      $average_conduct_column_code = " fn_conduct_code(ROUND((" . $average_conduct_column . ")/" . $colcount . ")) AS average_conduct_code";
      $average_conduct_column = " ROUND((" . $average_conduct_column . ")/" . $colcount . ") AS average_conduct";
      // $average_column = " ROUND(CAST(((" . $average_column . ")/" . $colcount . ") AS DECIMAL(8,1))) AS average";

      // $sql = "SELECT CONCAT(UPPER(lastname), ', ', UPPER(firstname), ' ', UPPER(middlename)) AS student_name, UPPER(gender) as gender, " . $subject_columns . ", " . $average_column . " , " . $average_column_code . ", " . $average_conduct_column . "  
      $sql = "SELECT CONCAT(UPPER(lastname), ', ', UPPER(firstname), ' ', UPPER(middlename)) AS student_name, UPPER(gender) as gender, " . $subject_columns . ", " . $average_column . " , " . $average_column_code . ", " . $average_conduct_column . ", " . $average_conduct_column_code . " 
              FROM students 
              LEFT JOIN student_session ON student_session.student_id = students.id 
              " . $subquery . " 
              WHERE student_session.class_id = " . $grade_level . " 
              AND student_session.section_id = " . $section . " 
              AND student_session.session_id = " . $school_year . " 
              AND students.is_active = 'yes' 
              ORDER BY gender DESC, student_name ASC";

      // print_r($sql);
      // die();
      $query = $this->db->query($sql);
      // print_r($this->db->last_query());
      // die();
      // print_r($this->db->error());
      // die();
      return $query->result();
   }

   // public function get_subject_list($gradelevel, $schoolyear)
   // {
   //    //-- Get subject list
   //    $sql = "SELECT classes.id AS grade_level_id, subjects.name AS subject, subject_group_subjects.subject_id, subjects.in_average, subjects.transmuted, subjects.code
   //              FROM subject_groups
   //              JOIN subject_group_subjects ON subject_group_subjects.subject_group_id = subject_groups.id
   //              JOIN subjects ON subjects.id = subject_group_subjects.subject_id
   //              JOIN subject_group_class_sections ON subject_group_class_sections.subject_group_id = subject_groups.id
   //              JOIN class_sections ON class_sections.id = subject_group_class_sections.class_section_id
   //              JOIN classes ON classes.id = class_sections.class_id
   //              WHERE classes.id = " . $gradelevel . " 
   //              AND subjects.graded = TRUE 
   //              AND subject_groups.session_id = " . $schoolyear . " 
   //              GROUP BY classes.id, subjects.name
   //              ORDER BY subject_groups.name, subjects.name ASC";

   //    $query = $this->db->query($sql);
   //    return $query->result();
   // }

   public function get_subject_list($gradelevel, $schoolyear, $section)
   {
      //-- Get subject list
      $sql = "SELECT classes.id AS grade_level_id, subjects.name AS subject, subject_group_subjects.subject_id, subjects.in_average, subjects.transmuted, subjects.code
                FROM subject_groups
                JOIN subject_group_subjects ON subject_group_subjects.subject_group_id = subject_groups.id
                JOIN subjects ON subjects.id = subject_group_subjects.subject_id
                JOIN subject_group_class_sections ON subject_group_class_sections.subject_group_id = subject_groups.id 
                JOIN class_sections ON class_sections.id = subject_group_class_sections.class_section_id
                JOIN classes ON classes.id = class_sections.class_id
                WHERE classes.id = " . $gradelevel . " 
                AND class_sections.section_id = " . $section . " 
                AND subjects.graded = TRUE 
                AND subject_groups.session_id = " . $schoolyear . " 
                GROUP BY classes.id, subjects.name
                ORDER BY subject_group_subjects.sort_order asc, subject_groups.name, subjects.name ASC";

      $query = $this->db->query($sql);
      return $query->result();
   }

   public function get_student_class_record($school_year, $student_id, $grade_level, $section)
   {
      $subquery = "";
      $quarter_columns = "";
      $average_columns = "";
      $average_column = "";
      $colcount = 0;

      $grade_level_info = $this->get_grade_level_info($grade_level);
      $dataresult = $this->get_quarter_list($grade_level_info['term_alias'], $grade_level_info['term_length']);

      foreach ($dataresult as $row) {
         if (!empty($quarter_columns)) {
            $quarter_columns .= ", IFNULL(tbl" . $row->id . ".quarterly_grade, 0) AS '" . str_replace(" ", "_", $row->name) . "'";
            $average_column .= "+IFNULL(tbl" . $row->id . ".quarterly_grade, 0)";
         } else {
            $quarter_columns .= " IFNULL(tbl" . $row->id . ".quarterly_grade, 0) AS '" . str_replace(" ", "_", $row->name) . "'";
            $average_column .= "IFNULL(tbl" . $row->id . ".quarterly_grade, 0)";
         }

         //-- IFNULL(ROUND(SUM(((total_scores/tot_highest_score)*100) * wspercent), 0), 0) 
         $subquery .= " LEFT JOIN 
                         (
                           SELECT school_year, quarter, tbl.student_id, grade_level, tbl.section_id, subject_id, 
                           CASE 
                           WHEN grading_allowed_students.view_allowed = 1 
                              THEN 
                                CASE 
                                  WHEN subjects.transmuted = 1 
                                    THEN fn_transmuted_grade(ROUND(IFNULL(SUM(((total_scores/tot_highest_score)*100) * wspercent), 0), 2)) 
                                    ELSE CASE
                                         WHEN MOD(SUM(((total_scores/tot_highest_score)*100) * wspercent), 1) = 0.5
                                           THEN ROUND(SUM(((total_scores/tot_highest_score)*100) * wspercent) + 0.1)
                                           ELSE ROUND(SUM(((total_scores/tot_highest_score)*100) * wspercent))
                                         END
                                END
                              ELSE 0 
                            END AS quarterly_grade 
                            FROM
                            (
                              SELECT school_year, quarter, student_id, grade AS grade_level, section_id, subject_id, SUM(score) AS total_scores, 
                              SUM(highest_score) AS tot_highest_score, criteria_id, label AS criteria_label, (ws/100) AS wspercent
                              FROM vw_class_record
                              WHERE section_id  = " . $section . " 
                              AND grade  = " . $grade_level . " 
                              AND school_year = " . $school_year . " 
                              AND quarter = " . $row->id . " 
                              AND student_id = " . $student_id . " 
                              GROUP BY student_id, criteria_id, label
                            ) tbl
                            LEFT JOIN grading_allowed_students ON grading_allowed_students.student_id = tbl.student_id AND grading_allowed_students.session_id = tbl.school_year AND grading_allowed_students.quarter_id = quarter 
                            LEFT JOIN subjects ON subjects.id = tbl.subject_id
                            GROUP BY school_year, quarter, student_id, subject_id
                         ) tbl" . $row->id . " ON tbl" . $row->id . ".subject_id = tblsubjects.subject_id";

         $colcount++;
      }

      $average_columns = " ((" . $average_column . ")/" . $colcount . ") AS average";
      // $average_columns = " ROUND(CAST(((" . $average_column . ")/" . $colcount . ") AS DECIMAL(8,1))) AS average";

      $sql = "SELECT main_subject, sub_subjects, subject AS Subjects, $quarter_columns, $average_columns, ROUND(CAST(((" . $average_column . ")/" . $colcount . ") AS DECIMAL(8,1))) AS final_grade, sort_order
                FROM 
                (
                  SELECT classes.id AS grade_level_id, subject_main.name as main_subject, subject_main.sub_subjects, subjects.name AS subject, subject_group_subjects.subject_id, subject_group_subjects.sort_order
                    FROM subject_groups
                    JOIN subject_group_subjects ON subject_group_subjects.subject_group_id = subject_groups.id
                    JOIN subjects ON subjects.id = subject_group_subjects.subject_id
                    LEFT JOIN subject_main on subject_main.id = subjects.main_subject
                    JOIN subject_group_class_sections ON subject_group_class_sections.subject_group_id = subject_groups.id
                    JOIN class_sections ON class_sections.id = subject_group_class_sections.class_section_id
                    JOIN classes ON classes.id = class_sections.class_id
                    WHERE classes.id = " . $grade_level . " 
                    AND class_sections.section_id = " . $section . " 
                    AND subjects.graded = TRUE 
                    AND subject_groups.session_id = " . $school_year . " 
                    GROUP BY classes.id, subjects.name
                    ORDER BY subject_group_subjects.sort_order asc, subject_groups.name, subjects.name ASC
                ) tblsubjects
                " . $subquery . " order by sort_order";

      // return($sql);
      $query = $this->db->query($sql);
      // print_r($this->db->last_query());
      // die();
      // print_r(json_encode($query->result()));die();
      return $query->result();
   }

   public function get_student_class_record_unrestricted($school_year, $student_id, $grade_level, $section)
   {
      $subquery = "";
      $quarter_columns = "";
      $average_columns = "";
      $average_column = "";
      $colcount = 0;

      $grade_level_info = $this->get_grade_level_info($grade_level);
      $dataresult = $this->get_quarter_list($grade_level_info['term_alias'], $grade_level_info['term_length']);

      // $dataresult = $this->get_quarter_list('Qtr', 2);

      foreach ($dataresult as $row) {
         if (!empty($quarter_columns)) {
            $quarter_columns .= ", IFNULL(tbl" . $row->id . ".quarterly_grade, 0) AS '" . str_replace(" ", "_", $row->name) . "'";
            $average_column .= "+IFNULL(tbl" . $row->id . ".quarterly_grade, 0)";
         } else {
            $quarter_columns .= " IFNULL(tbl" . $row->id . ".quarterly_grade, 0) AS '" . str_replace(" ", "_", $row->name) . "'";
            $average_column .= "IFNULL(tbl" . $row->id . ".quarterly_grade, 0)";
         }

         $subquery .= " LEFT JOIN 
                         (
                            SELECT school_year, quarter, tbl.student_id, grade_level, tbl.section_id, subject_id, 
                            CASE 
                              WHEN subjects.transmuted = 1 
                                THEN fn_transmuted_grade(ROUND(IFNULL(SUM(((total_scores/tot_highest_score)*100) * wspercent), 0), 2)) 
                                ELSE CASE
                                     WHEN MOD(SUM(((total_scores/tot_highest_score)*100) * wspercent), 1) = 0.5
                                       THEN ROUND(SUM(((total_scores/tot_highest_score)*100) * wspercent) + 0.1)
                                       ELSE ROUND(SUM(((total_scores/tot_highest_score)*100) * wspercent))
                                     END
                            END AS quarterly_grade 
                            FROM
                            (
                              SELECT school_year, quarter, student_id, grade AS grade_level, section_id, subject_id, SUM(score) AS total_scores, 
                              SUM(highest_score) AS tot_highest_score, criteria_id, label AS criteria_label, (ws/100) AS wspercent
                              FROM vw_class_record
                              WHERE section_id  = " . $section . " 
                              AND grade  = " . $grade_level . " 
                              AND school_year = " . $school_year . " 
                              AND quarter = " . $row->id . " 
                              AND student_id = " . $student_id . " 
                              GROUP BY student_id, criteria_id, label
                            ) tbl
                            LEFT JOIN subjects ON subjects.id = tbl.subject_id
                            GROUP BY school_year, quarter, student_id, subject_id
                         ) tbl" . $row->id . " ON tbl" . $row->id . ".subject_id = tblsubjects.subject_id ";

         $colcount++;
      }

      $average_columns = " ((" . $average_column . ")/" . $colcount . ") AS average";
      // $average_columns = " ROUND(CAST(((" . $average_column . ")/" . $colcount . ") AS DECIMAL(8,1))) AS average";

      $sql = "SELECT main_subject, sub_subjects, subject AS Subjects, $quarter_columns, $average_columns, ROUND(CAST(((" . $average_column . ")/" . $colcount . ") AS DECIMAL(8,1))) AS final_grade, sort_order 
                FROM 
                (
                  SELECT classes.id AS grade_level_id, subject_main.name as main_subject, subject_main.sub_subjects, subjects.name AS subject, subject_group_subjects.subject_id, subject_group_subjects.sort_order
                    FROM subject_groups
                    JOIN subject_group_subjects ON subject_group_subjects.subject_group_id = subject_groups.id
                    JOIN subjects ON subjects.id = subject_group_subjects.subject_id
                    LEFT JOIN subject_main on subject_main.id = subjects.main_subject
                    JOIN subject_group_class_sections ON subject_group_class_sections.subject_group_id = subject_groups.id
                    JOIN class_sections ON class_sections.id = subject_group_class_sections.class_section_id
                    JOIN classes ON classes.id = class_sections.class_id
                    WHERE classes.id = " . $grade_level . " 
                    AND class_sections.section_id = " . $section . " 
                    AND subjects.graded = TRUE 
                    AND subject_groups.session_id = " . $school_year . " 
                    GROUP BY classes.id, subjects.name
                    ORDER BY subject_group_subjects.sort_order asc, subject_groups.name, subjects.name
                ) tblsubjects
                " . $subquery . " order by sort_order";

      // return($sql);
      $query = $this->db->query($sql);
      // print_r($this->db->last_query());
      // exit();
      return $query->result();
   }

   public function get_student_class_record_restricted_lpms($school_year, $student_id, $grade_level, $section)
   {
      $subquery = "";
      $quarter_columns = "";
      $average_columns = "";
      $average_column = "";
      $grade_codes = "";
      $conduct_columns = "";
      $conduct_codes = "";
      $average_conduct_column = "";
      $average_conduct_columns = "";
      $colcount = 0;

      $grade_level_info = $this->get_grade_level_info($grade_level);
      $dataresult = $this->get_quarter_list($grade_level_info['term_alias'], $grade_level_info['term_length']);
      // $dataresult = $this->get_quarter_list();

      foreach ($dataresult as $row) {
         if (!empty($quarter_columns)) {
            $quarter_columns .= ", IFNULL(tbl" . $row->id . ".quarterly_grade, 0) AS '" . str_replace(" ", "_", $row->name) . "'";
            $grade_codes .= ", IFNULL(fn_final_transmuted_grade_code(tbl" . $row->id . ".quarterly_grade), '') AS '" . str_replace(" ", "_", $row->name) . "_CODE'";
            $average_column .= "+IFNULL(tbl" . $row->id . ".quarterly_grade, 0)";

            $conduct_columns .= ", IFNULL(tbl" . $row->id . "_conduct.quarterly_grade, 0) AS '" . str_replace(" ", "_", $row->name) . "_CONDUCT'";
            $conduct_codes .= ", IFNULL(fn_conduct_code(tbl" . $row->id . "_conduct.quarterly_grade), '') AS '" . str_replace(" ", "_", $row->name) . "_CONDUCTCODE'";
            $average_conduct_column .= "+IFNULL(tbl" . $row->id . "_conduct.quarterly_grade, 0)";
         } else {
            $quarter_columns .= " IFNULL(tbl" . $row->id . ".quarterly_grade, 0) AS '" . str_replace(" ", "_", $row->name) . "'";
            $grade_codes .= " IFNULL(fn_final_transmuted_grade_code(tbl" . $row->id . ".quarterly_grade), '') AS '" . str_replace(" ", "_", $row->name) . "_CODE'";
            $average_column .= "IFNULL(tbl" . $row->id . ".quarterly_grade, 0)";

            $conduct_columns .= " IFNULL(tbl" . $row->id . "_conduct.quarterly_grade, 0) AS '" . str_replace(" ", "_", $row->name) . "_CONDUCT'";
            $conduct_codes .= " IFNULL(fn_conduct_code(tbl" . $row->id . "_conduct.quarterly_grade), '') AS '" . str_replace(" ", "_", $row->name) . "_CONDUCTCODE'";
            $average_conduct_column .= "IFNULL(tbl" . $row->id . "_conduct.quarterly_grade, 0)";
         }

         $subquery .= " LEFT JOIN 
                         (
                           SELECT school_year, quarter, tbl.student_id, grade_level, tbl.section_id, subject_id, 
                           CASE 
                              WHEN grading_allowed_students.view_allowed = 1 
                              THEN 
                                 CASE 
                                    WHEN subjects.transmuted = 1 
                                    THEN fn_transmuted_grade(ROUND(IFNULL(SUM(((total_scores/tot_highest_score)*100) * wspercent), 0), 2)) 
                                    ELSE CASE
                                           WHEN MOD(SUM(((total_scores/tot_highest_score)*100) * wspercent), 1) = 0.5
                                           THEN ROUND(SUM(((total_scores/tot_highest_score)*100) * wspercent) + 0.1)
                                           ELSE ROUND(SUM(((total_scores/tot_highest_score)*100) * wspercent))
                                         END
                                 END
                              ELSE 0 
                            END AS quarterly_grade 
                            FROM
                            (
                              SELECT school_year, quarter, student_id, grade AS grade_level, section_id, subject_id, SUM(score) AS total_scores, 
                              SUM(highest_score) AS tot_highest_score, criteria_id, label AS criteria_label, (ws/100) AS wspercent
                              FROM vw_class_record
                              WHERE section_id  = " . $section . " 
                              AND grade  = " . $grade_level . " 
                              AND school_year = " . $school_year . " 
                              AND quarter = " . $row->id . " 
                              AND student_id = " . $student_id . " 
                              AND school_name <> 'Conduct'
                              GROUP BY student_id, criteria_id, label
                            ) tbl
                            LEFT JOIN subjects ON subjects.id = tbl.subject_id
                            LEFT JOIN grading_allowed_students ON grading_allowed_students.student_id = tbl.student_id AND grading_allowed_students.session_id = tbl.school_year AND grading_allowed_students.quarter_id = quarter 
                            GROUP BY school_year, quarter, student_id, subject_id
                         ) tbl" . $row->id . " ON tbl" . $row->id . ".subject_id = tblsubjects.subject_id";

         $subquery .= " LEFT JOIN 
                         (
                           SELECT school_year, quarter, tbl.student_id, grade_level, tbl.section_id, subject_id, 
                           CASE WHEN grading_allowed_students.view_allowed = 1 THEN ROUND(IFNULL(SUM(final_grade), 2)) ELSE 0 END AS quarterly_grade
                            FROM
                            (
                              SELECT school_year, quarter, student_id, grade AS grade_level, section_id, subject_id, SUM(score) AS total_scores, 
                              SUM(highest_score) AS tot_highest_score, criteria_id, label AS criteria_label, (ws/100) AS wspercent,
                              ((sum(score) / SUM(highest_score)) * 100) * (ws/100) as final_grade
                              FROM vw_class_record
                              WHERE section_id  = " . $section . " 
                              AND grade  = " . $grade_level . " 
                              AND school_year = " . $school_year . " 
                              AND quarter = " . $row->id . " 
                              AND student_id = " . $student_id . " 
                              AND school_name = 'Conduct'
                              GROUP BY student_id, criteria_id, label
                            ) tbl
                            LEFT JOIN subjects ON subjects.id = tbl.subject_id
                            LEFT JOIN grading_allowed_students ON grading_allowed_students.student_id = tbl.student_id AND grading_allowed_students.session_id = tbl.school_year AND grading_allowed_students.quarter_id = quarter 
                            GROUP BY school_year, quarter, student_id, subject_id
                         ) tbl" . $row->id . "_conduct ON tbl" . $row->id . "_conduct.subject_id = tblsubjects.subject_id";

         $colcount++;
      }

      $average_columns = " ((" . $average_column . ")/" . $colcount . ") AS average";
      // $average_columns = " ROUND(CAST(((" . $average_column . ")/" . $colcount . ") AS DECIMAL(8,1))) AS average";
      $average_conduct_columns = " ((" . $average_conduct_column . ")/" . $colcount . ") AS average_conduct";

      $sql = "SELECT main_subject, sub_subjects, subject AS Subjects, $quarter_columns, $grade_codes, $conduct_columns, $conduct_codes, 
              $average_columns, ROUND(CAST(((" . $average_column . ")/" . $colcount . ") AS DECIMAL(8,1))) AS final_grade,   
              $average_conduct_columns, fn_conduct_code(ROUND(CAST(((" . $average_conduct_column . ")/" . $colcount . ") AS DECIMAL(8,1)))) as final_conduct_code, sort_order
                FROM 
                (
                    SELECT classes.id AS grade_level_id, subject_main.name as main_subject, subject_main.sub_subjects, subjects.name AS subject, subject_group_subjects.subject_id, subject_group_subjects.sort_order
                    FROM subject_groups
                    JOIN subject_group_subjects ON subject_group_subjects.subject_group_id = subject_groups.id
                    JOIN subjects ON subjects.id = subject_group_subjects.subject_id
                    LEFT JOIN subject_main on subject_main.id = subjects.main_subject
                    JOIN subject_group_class_sections ON subject_group_class_sections.subject_group_id = subject_groups.id
                    JOIN class_sections ON class_sections.id = subject_group_class_sections.class_section_id
                    JOIN classes ON classes.id = class_sections.class_id
                    WHERE classes.id = " . $grade_level . " 
                    AND class_sections.section_id = " . $section . " 
                    AND subjects.graded = TRUE 
                    AND subject_groups.session_id = " . $school_year . " 
                    GROUP BY classes.id, subjects.name
                    ORDER BY subject_group_subjects.sort_order asc, subject_groups.name, subjects.name ASC
                ) tblsubjects
                " . $subquery . " order by sort_order";

      // return($sql);
      $query = $this->db->query($sql);
      // print_r($this->db->last_query());
      // die();
      return $query->result();
   }

   public function get_student_class_record_unrestricted_lpms($school_year, $student_id, $grade_level, $section)
   {
      $subquery = "";
      $quarter_columns = "";
      $average_columns = "";
      $average_column = "";
      $grade_codes = "";
      $conduct_columns = "";
      $conduct_codes = "";
      $average_conduct_column = "";
      $average_conduct_columns = "";
      $colcount = 0;

      $grade_level_info = $this->get_grade_level_info($grade_level);
      $dataresult = $this->get_quarter_list($grade_level_info['term_alias'], $grade_level_info['term_length']);
      // $dataresult = $this->get_quarter_list();

      foreach ($dataresult as $row) {
         if (!empty($quarter_columns)) {
            $quarter_columns .= ", IFNULL(tbl" . $row->id . ".quarterly_grade, 0) AS '" . str_replace(" ", "_", $row->name) . "'";
            $grade_codes .= ", IFNULL(fn_final_transmuted_grade_code(tbl" . $row->id . ".quarterly_grade), '') AS '" . str_replace(" ", "_", $row->name) . "_CODE'";
            $average_column .= "+IFNULL(tbl" . $row->id . ".quarterly_grade, 0)";

            $conduct_columns .= ", IFNULL(tbl" . $row->id . "_conduct.quarterly_grade, 0) AS '" . str_replace(" ", "_", $row->name) . "_CONDUCT'";
            $conduct_codes .= ", IFNULL(fn_conduct_code(tbl" . $row->id . "_conduct.quarterly_grade), '') AS '" . str_replace(" ", "_", $row->name) . "_CONDUCTCODE'";
            $average_conduct_column .= "+IFNULL(tbl" . $row->id . "_conduct.quarterly_grade, 0)";
         } else {
            $quarter_columns .= " IFNULL(tbl" . $row->id . ".quarterly_grade, 0) AS '" . str_replace(" ", "_", $row->name) . "'";
            $grade_codes .= " IFNULL(fn_final_transmuted_grade_code(tbl" . $row->id . ".quarterly_grade), '') AS '" . str_replace(" ", "_", $row->name) . "_CODE'";
            $average_column .= "IFNULL(tbl" . $row->id . ".quarterly_grade, 0)";

            $conduct_columns .= " IFNULL(tbl" . $row->id . "_conduct.quarterly_grade, 0) AS '" . str_replace(" ", "_", $row->name) . "_CONDUCT'";
            $conduct_codes .= " IFNULL(fn_conduct_code(tbl" . $row->id . "_conduct.quarterly_grade), '') AS '" . str_replace(" ", "_", $row->name) . "_CONDUCTCODE'";
            $average_conduct_column .= "IFNULL(tbl" . $row->id . "_conduct.quarterly_grade, 0)";
         }

         $subquery .= " LEFT JOIN 
                         (
                            SELECT school_year, quarter, tbl.student_id, grade_level, tbl.section_id, subject_id, 
                            CASE 
                              WHEN subjects.transmuted = 1 
                                THEN fn_transmuted_grade(ROUND(IFNULL(SUM(((total_scores/tot_highest_score)*100) * wspercent), 0), 2)) 
                                ELSE CASE
                                     WHEN MOD(SUM(((total_scores/tot_highest_score)*100) * wspercent), 1) = 0.5
                                       THEN ROUND(SUM(((total_scores/tot_highest_score)*100) * wspercent) + 0.1)
                                       ELSE ROUND(SUM(((total_scores/tot_highest_score)*100) * wspercent))
                                     END
                            END AS quarterly_grade 
                            FROM
                            (
                              SELECT school_year, quarter, student_id, grade AS grade_level, section_id, subject_id, SUM(score) AS total_scores, 
                              SUM(highest_score) AS tot_highest_score, criteria_id, label AS criteria_label, (ws/100) AS wspercent
                              FROM vw_class_record
                              WHERE section_id  = " . $section . " 
                              AND grade  = " . $grade_level . " 
                              AND school_year = " . $school_year . " 
                              AND quarter = " . $row->id . " 
                              AND student_id = " . $student_id . " 
                              AND school_name <> 'Conduct'
                              GROUP BY student_id, criteria_id, label
                            ) tbl
                            LEFT JOIN subjects ON subjects.id = tbl.subject_id
                            GROUP BY school_year, quarter, student_id, subject_id
                         ) tbl" . $row->id . " ON tbl" . $row->id . ".subject_id = tblsubjects.subject_id";

         $subquery .= " LEFT JOIN 
                         (
                            SELECT school_year, quarter, tbl.student_id, grade_level, tbl.section_id, subject_id, 
                            ROUND(IFNULL(SUM(final_grade), 2)) AS quarterly_grade
                            FROM
                            (
                              SELECT school_year, quarter, student_id, grade AS grade_level, section_id, subject_id, SUM(score) AS total_scores, 
                              SUM(highest_score) AS tot_highest_score, criteria_id, label AS criteria_label, (ws/100) AS wspercent,
                              ((sum(score) / SUM(highest_score)) * 100) * (ws/100) as final_grade
                              FROM vw_class_record
                              WHERE section_id  = " . $section . " 
                              AND grade  = " . $grade_level . " 
                              AND school_year = " . $school_year . " 
                              AND quarter = " . $row->id . " 
                              AND student_id = " . $student_id . " 
                              AND school_name = 'Conduct'
                              GROUP BY student_id, criteria_id, label
                            ) tbl
                            LEFT JOIN subjects ON subjects.id = tbl.subject_id
                            GROUP BY school_year, quarter, student_id, subject_id
                         ) tbl" . $row->id . "_conduct ON tbl" . $row->id . "_conduct.subject_id = tblsubjects.subject_id";

         $colcount++;
      }

      $average_columns = " ((" . $average_column . ")/" . $colcount . ") AS average";
      // $average_columns = " ROUND(CAST(((" . $average_column . ")/" . $colcount . ") AS DECIMAL(8,1))) AS average";
      $average_conduct_columns = " ((" . $average_conduct_column . ")/" . $colcount . ") AS average_conduct";

      $sql = "SELECT main_subject, sub_subjects, subject AS Subjects, $quarter_columns, $grade_codes, $conduct_columns, $conduct_codes, 
              $average_columns, ROUND(CAST(((" . $average_column . ")/" . $colcount . ") AS DECIMAL(8,1))) AS final_grade,   
              $average_conduct_columns, fn_conduct_code(ROUND(CAST(((" . $average_conduct_column . ")/" . $colcount . ") AS DECIMAL(8,1)))) as final_conduct_code, sort_order
                FROM 
                (
                    SELECT classes.id AS grade_level_id, subject_main.name as main_subject, subject_main.sub_subjects, subjects.name AS subject, subject_group_subjects.subject_id, subject_group_subjects.sort_order
                    FROM subject_groups
                    JOIN subject_group_subjects ON subject_group_subjects.subject_group_id = subject_groups.id
                    JOIN subjects ON subjects.id = subject_group_subjects.subject_id
                    LEFT JOIN subject_main on subject_main.id = subjects.main_subject
                    JOIN subject_group_class_sections ON subject_group_class_sections.subject_group_id = subject_groups.id
                    JOIN class_sections ON class_sections.id = subject_group_class_sections.class_section_id
                    JOIN classes ON classes.id = class_sections.class_id
                    WHERE classes.id = " . $grade_level . " 
                    AND class_sections.section_id = " . $section . " 
                    AND subjects.graded = TRUE 
                    AND subject_groups.session_id = " . $school_year . " 
                    GROUP BY classes.id, subjects.name
                    ORDER BY subject_group_subjects.sort_order asc, subject_groups.name, subjects.name ASC
                ) tblsubjects
                " . $subquery . " order by sort_order";

      // return($sql);
      $query = $this->db->query($sql);
      // print_r($this->db->last_query());
      // die();
      return $query->result();
   }

   // public function get_quarter_list($header_label = '', $terms = 4)
   // {
   //    $sqlStr = "SELECT id, name, concat(description, ' ', '" . $header_label . "') as description FROM grading_quarter LIMIT " . $terms;
   //    $query = $this->db->query($sqlStr);
   //    return $query->result();
   // }

   public function get_class_record_quarterly($school_year, $grade_level, $section, $subject, $teacher)
   {
      $quarter_columns = "";

      $grade_level_info = $this->get_grade_level_info($grade_level);
      $resultdata = $this->get_quarter_list($grade_level_info['term_alias'], $grade_level_info['term_length']);
      // $resultdata = $this->get_quarter_list();

      $average_columns = "";
      $average_column = "";
      $subquery = "";
      $colcount = 0;

      foreach ($resultdata as $row) {
         if (!empty($quarter_columns)) {
            $quarter_columns .= ", IFNULL(tbl" . $row->id . ".quarterly_grade, 0) AS '" . $row->name . "'";
            $average_column .= "+IFNULL(tbl" . $row->id . ".quarterly_grade, 0)";
         } else {
            $quarter_columns .= " IFNULL(tbl" . $row->id . ".quarterly_grade, 0) AS '" . $row->name . "'";
            $average_column .= "IFNULL(tbl" . $row->id . ".quarterly_grade, 0)";
         }

         $subquery .= " LEFT JOIN 
                         (
                            SELECT school_year, quarter, tbl.student_id, grade_level, tbl.section_id, subject_id, teacher_id, 
                            CASE 
                            WHEN subjects.transmuted = 1 
                              THEN fn_transmuted_grade(ROUND(IFNULL(SUM(((total_scores/tot_highest_score)*100) * wspercent), 0), 2)) 
                              ELSE CASE
                                     WHEN MOD(SUM(((total_scores/tot_highest_score)*100) * wspercent), 1) = 0.5
                                      THEN ROUND(SUM(((total_scores/tot_highest_score)*100) * wspercent) + 0.1)
                                      ELSE ROUND(SUM(((total_scores/tot_highest_score)*100) * wspercent))
                                   END
                            END AS quarterly_grade 
                            FROM
                            (
                              SELECT school_year, quarter, student_id, grade AS grade_level, section_id, subject_id, teacher_id, SUM(score) AS total_scores, 
                              SUM(highest_score) AS tot_highest_score, criteria_id, label AS criteria_label, (ws/100) AS wspercent
                              FROM vw_class_record
                              WHERE section_id  = " . $section . " 
                              AND grade  = " . $grade_level . " 
                              AND school_year = " . $school_year . " 
                              AND quarter = " . $row->id . " 
                              AND subject_id = " . $subject . " 
                              AND teacher_id = " . $teacher . " 
                              GROUP BY student_id, criteria_id, label
                            ) tbl
                            LEFT JOIN subjects ON subjects.id = tbl.subject_id
                            GROUP BY school_year, quarter, student_id
                         ) tbl" . $row->id . " ON tbl" . $row->id . ".student_id = students.id ";

         $colcount++;
      }

      // $average_columns = " ((" . $average_column . ")/" . $colcount . ") AS average";
      $average_columns = " ROUND(CAST(((" . $average_column . ")/" . $colcount . ") AS DECIMAL(8,1))) AS average";

      $sql = "SELECT CONCAT(lastname, ', ', firstname, ' ', middlename) AS student_name, UPPER(gender) AS gender, $quarter_columns, $average_columns, ROUND((" . $average_column . ")/" . $colcount . ") AS final_grade 
                FROM students 
                LEFT JOIN student_session ON student_session.student_id = students.id 
                " . $subquery . " 
                WHERE student_session.class_id = " . $grade_level . " 
                AND student_session.section_id = " . $section . " 
                AND student_session.session_id = " . $school_year . " 
                AND students.is_active = 'yes' 
                ORDER BY gender DESC, student_name ASC";

      // return $sql;
      $query = $this->db->query($sql);
      // print_r($this->db->last_query());
      // die();
      return $query->result();
   }

   public function get_teacher_list()
   {
      $sql = "SELECT id, CONCAT(TRIM(NAME), ' ', TRIM(surname)) AS teacher 
                FROM staff
                WHERE id IN (SELECT staff_id FROM staff_roles WHERE role_id = 2)
                ORDER BY teacher ASC";
      $query = $this->db->query($sql);
      return $query->result_array();
   }

   public function get_student_conduct($_year, $_grade_level_id, $_section_id, $_student_id)
   {
      $sql = "SELECT 
                DISTINCT(grading_conduct_indicators.id),
                grading_conduct_deped_indicators.indicator AS deped_indicators, 
                grading_conduct_core_indicators.core_indicator AS core_indicator,
                grading_conduct_indicators.indicator AS indicators,
                fn_final_conduct($_year, 1, $_grade_level_id, $_section_id, $_student_id, grading_conduct_indicators.id) AS first_quarter,
                fn_final_conduct($_year, 2, $_grade_level_id, $_section_id, $_student_id, grading_conduct_indicators.id) AS second_quarter,
                fn_final_conduct($_year, 3, $_grade_level_id, $_section_id, $_student_id, grading_conduct_indicators.id) AS third_quarter,
                fn_final_conduct($_year, 4, $_grade_level_id, $_section_id, $_student_id, grading_conduct_indicators.id) AS fourth_quarter
                FROM grading_conduct_deped_indicators
                LEFT JOIN grading_conduct_core_indicators ON grading_conduct_core_indicators.deped_indicator_id = grading_conduct_deped_indicators.id
                LEFT JOIN grading_conduct_indicators ON grading_conduct_indicators.core_indicator_id = grading_conduct_core_indicators.id
                LEFT JOIN grading_conduct ON grading_conduct.indicator_id = grading_conduct_indicators.id
                AND grading_conduct.school_year = $_year 
                AND grading_conduct.grade = $_grade_level_id
                AND grading_conduct.section_id = $_section_id
                AND grading_conduct.student_id = $_student_id
                ORDER BY grading_conduct_indicators.id ASC";

      $query = $this->db->query($sql);
      // print_r($this->db->error());die();
      return $query->result();
   }

   public function get_student_conduct_numeric($_school_year, $_grade_level_id, $_section_id, $_student_id)
   {
      $sql = "SELECT tbl1.conduct_grade AS first_quarter, tbl2.conduct_grade AS second_quarter,
                tbl3.conduct_grade AS third_quarter, tbl4.conduct_grade AS fourth_quarter
                FROM
                (
                SELECT grading_conduct_numeric.student_id, fn_conduct_transmuted_grade(ROUND(SUM(conduct_num) / COUNT(teacher_id), 0)) AS 'conduct_grade' 
                FROM grading_conduct_numeric
                WHERE school_year = $_school_year
                AND QUARTER = 1
                AND grade_level = $_grade_level_id
                AND section_id = $_section_id
                AND student_id = $_student_id
                ) tbl1
                
                LEFT JOIN 
                (
                SELECT grading_conduct_numeric.student_id, fn_conduct_transmuted_grade(ROUND(SUM(conduct_num) / COUNT(teacher_id), 0)) AS 'conduct_grade' 
                FROM grading_conduct_numeric
                WHERE school_year = $_school_year
                AND QUARTER = 2
                AND grade_level = $_grade_level_id
                AND section_id = $_section_id
                AND student_id = $_student_id
                ) tbl2 ON tbl2.student_id = tbl1.student_id
                
                LEFT JOIN 
                (
                SELECT grading_conduct_numeric.student_id, fn_conduct_transmuted_grade(ROUND(SUM(conduct_num) / COUNT(teacher_id), 0)) AS 'conduct_grade' 
                FROM grading_conduct_numeric
                WHERE school_year = $_school_year
                AND QUARTER = 3
                AND grade_level = $_grade_level_id
                AND section_id = $_section_id
                AND student_id = $_student_id
                ) tbl3 ON tbl3.student_id = tbl1.student_id
                
                LEFT JOIN 
                (
                SELECT grading_conduct_numeric.student_id, fn_conduct_transmuted_grade(ROUND(SUM(conduct_num) / COUNT(teacher_id), 0)) AS 'conduct_grade' 
                FROM grading_conduct_numeric
                WHERE school_year = $_school_year
                AND QUARTER = 4
                AND grade_level = $_grade_level_id
                AND section_id = $_section_id
                AND student_id = $_student_id
                ) tbl4 ON tbl4.student_id = tbl1.student_id";

      // print_r($sql);die();

      $query = $this->db->query($sql);
      return $query->result();

      // $this->db->select('fn_conduct_transmuted_grade(SUM(conduct_num) / COUNT(teacher_id)) AS \'conduct_grade\'');
      // $this->db->from('grading_conduct_numeric');
      // $this->db->where('school_year', $_school_year);
      // $this->db->where('quarter', $_quarter);
      // $this->db->where('grade_level', $_grade_level_id);
      // $this->db->where('section_id', $_section_id);
      // $this->db->where('student_id', $_student_id);
      // $this->db->order_by('description');

      // // $result = $this->db->get()->result_array();
      // $result = $this->db->get()->row();
      // return $result->conduct_grade;
   }

   public function generate_Banig($_school_year, $_grade_level, $_section)
   {
      $quarter_columns = "";
      $average_columns = "";
      $average_column = "";
      $subquery = "";
      $colcount = 0;

      $averageColumnsForMain = "";

      $grade_level_info = $this->get_grade_level_info($_grade_level);
      $quarters = $this->get_quarter_list($grade_level_info['term_alias'], $grade_level_info['term_length']);
      // $quarters = $this->get_quarter_list();

      // print_r($quarters);
      // die();

      foreach ($quarters as $row) {
         if (!empty($average_column))
            $average_column .= "+IFNULL(tbl" . $row->id . ".quarterly_grade, 0)";
         else
            $average_column .= "IFNULL(tbl" . $row->id . ".quarterly_grade, 0)";

         $subquery .= " LEFT JOIN 
                         (
                            SELECT school_year, quarter, tbl.student_id, grade_level, tbl.section_id, subject_id, teacher_id, 
                            CASE 
                            WHEN subjects.transmuted = 1 
                              THEN fn_transmuted_grade(ROUND(IFNULL(SUM(((total_scores/tot_highest_score)*100) * wspercent), 0), 2)) 
                              ELSE CASE
                                     WHEN MOD(SUM(((total_scores/tot_highest_score)*100) * wspercent), 1) = 0.5
                                      THEN ROUND(SUM(((total_scores/tot_highest_score)*100) * wspercent) + 0.1)
                                      ELSE ROUND(SUM(((total_scores/tot_highest_score)*100) * wspercent))
                                   END
                            END AS quarterly_grade 
                            FROM
                            (
                              SELECT school_year, quarter, student_id, grade AS grade_level, section_id, subject_id, teacher_id, SUM(score) AS total_scores, 
                              SUM(highest_score) AS tot_highest_score, criteria_id, label AS criteria_label, (ws/100) AS wspercent
                              FROM vw_class_record
                              WHERE section_id  = " . $_section . " 
                              AND grade  = " . $_grade_level . " 
                              AND school_year = " . $_school_year . " 
                              AND subject_id = ~replace_with_subject_id~ 
                              AND quarter = " . $row->id . " 
                              GROUP BY student_id, criteria_id, label
                            ) tbl
                            LEFT JOIN subjects ON subjects.id = tbl.subject_id
                            GROUP BY school_year, quarter, student_id
                         ) tbl" . $row->id . " ON tbl" . $row->id . ".student_id = students.id ";

         $colcount++;
      }

      $average_columns = " ROUND(CAST(((" . $average_column . ")/" . $colcount . ") AS DECIMAL(8,1))) AS average";

      $columns_selected = "";
      $sql = "";
      $left_join_on = "";
      $table_alias = "maintbl";
      $subject_counter = 1;
      $quarter_sum = [];

      // $subject_columns .= ", IFNULL(tbl" . $row->subject_id . ".quarterly_grade, 0) AS '" .$row->subject. "'" ;
      $subjects = $this->get_subject_list($_grade_level, $_school_year, $_section);
      foreach ($subjects as $row) {
         if (!empty($sql)) {
            $sql .= " LEFT JOIN ";
            $table_alias = "main_" . $row->subject_id;
            $left_join_on = " ON " . $table_alias . ".student_name_" . $subject_counter . " = maintbl.student_name_1 ";
         }

         //-- get fields to show
         if (empty($columns_selected)) {
            $columns_selected .= "student_name_" . $subject_counter . ", gender_" . $subject_counter;
         }

         $quarter_columns = "";
         $arrptr = 0;
         foreach ($quarters as $qrow) {
            if (!empty($quarter_columns))
               $quarter_columns .= ", IFNULL(tbl" . $qrow->id . ".quarterly_grade, 0) AS " . $qrow->name . "_" . $subject_counter;
            else
               $quarter_columns .= " IFNULL(tbl" . $qrow->id . ".quarterly_grade, 0) AS " . $qrow->name . "_" . $subject_counter;

            $quarter_sum[$arrptr] .= !empty($quarter_sum[$arrptr]) ? " + " : "";
            $quarter_sum[$arrptr] .= $qrow->name . "_" . $subject_counter;

            $columns_selected .= "," . $qrow->name . "_" . $subject_counter;

            $arrptr++;
         }

         $columns_selected .= ",average_" . $subject_counter;

         $sql .= "(SELECT CONCAT(lastname, ', ', firstname, ' ', middlename) AS student_name_" . $subject_counter . ", 
                    UPPER(gender) AS gender_" . $subject_counter . ", " . $quarter_columns . ", " . $average_columns . "_" . $subject_counter . "
                    FROM students 
                    LEFT JOIN student_session ON student_session.student_id = students.id 
                    " . str_replace('~replace_with_subject_id~', $row->subject_id, $subquery) . " 
                    WHERE student_session.class_id = " . $_grade_level . " 
                    AND student_session.section_id = " . $_section . " 
                    AND student_session.session_id = " . $_school_year . " 
                    AND students.is_active = 'yes') " . $table_alias . $left_join_on;

         if (empty($averageColumnsForMain))
            $averageColumnsForMain .= "average_" . $subject_counter;
         else
            $averageColumnsForMain .= "+average_" . $subject_counter;

         $subject_counter++;
      }

      // $main_sql = "SELECT " . $columns_selected . ", 
      //              ROUND(((~first_sum~)/~subject_count~), 2) as q1_ave, 
      //              ROUND(((~second_sum~)/~subject_count~), 2) as q2_ave, 
      //              ROUND(((~third_sum~)/~subject_count~), 2) as q3_ave, 
      //              ROUND(((~fourth_sum~)/~subject_count~), 2) as q4_ave FROM ";

      // $main_sql = str_replace("~first_sum~", $quarter_sum[0], $main_sql);
      // $main_sql = str_replace("~second_sum~", $quarter_sum[1], $main_sql);
      // $main_sql = str_replace("~third_sum~", $quarter_sum[2], $main_sql);
      // $main_sql = str_replace("~fourth_sum~", $quarter_sum[3], $main_sql);
      // $main_sql = str_replace("~subject_count~", count($subjects), $main_sql);

      // $query = $this->db->query("SELECT " . $columns_selected . ", q1_ave, q2_ave, q3_ave, q4_ave, ROUND(((" . $averageColumnsForMain . ")/" . count($subjects) . "), 3) AS computed_ave, ROUND(CAST(((" . $averageColumnsForMain . ")/" . count($subjects) . ") AS DECIMAL(8,1))) AS rounded_computed_ave
      //                              FROM (" . $main_sql . $sql . ") supermain ORDER BY gender_1 DESC, student_name_1 ASC");

      //-- Get average per quarter
      $averageStr = "";
      $averageColumns = "";
      $ctr = 0;
      foreach ($quarters as $row) {

         if ($averageStr == "")
            $averageStr .= " ROUND(((" . $quarter_sum[$ctr] . ")/" . count($subjects) . "), 2) as " . strtolower($row->name) . "_ave";
         else
            $averageStr .= ", ROUND(((" . $quarter_sum[$ctr] . ")/" . count($subjects) . "), 2) as " . strtolower($row->name) . "_ave ";

         $averageColumns .= strtolower($row->name) . "_ave, ";
         $ctr++;
      }

      $main_sql = "SELECT " . $columns_selected . ", " . $averageStr . " FROM ";

      $query = $this->db->query("SELECT " . $columns_selected . ", " . $averageColumns . " 
                                 ROUND(((" . $averageColumnsForMain . ")/" . count($subjects) . "), 3) AS computed_ave, 
                                 ROUND(CAST(((" . $averageColumnsForMain . ")/" . count($subjects) . ") AS DECIMAL(8,1))) AS rounded_computed_ave
                                 FROM (" . $main_sql . $sql . ") supermain ORDER BY gender_1 DESC, student_name_1 ASC");

      // print_r($this->db->last_query());
      // die();
      // print_r(json_encode($query->result()));die();
      return $query->result();
   }

   public function get_student_list($current_session, $grade_level, $section)
   {
      $sql = "select students.id, concat(lastname, ', ', firstname, ' ', middlename) as name 
              from students
              left join student_session on students.id = student_session.student_id  and student_session.session_id = " . $current_session . " 
              where student_session.class_id = " . $grade_level . " 
              and student_session.section_id = " . $section . " 
              AND students.is_active = 'yes' 
              order by gender desc, lastname";

      $query = $this->db->query($sql);

      // print_r($this->db->last_query());
      // die();
      return $query->result_array();
   }

   public function get_swh_items()
   {
      $query = $this->db->query("select id, sub from grading_swh order by sort_order");
      return $query->result_array();
   }

   public function get_lpms_swh_data($current_session, $quarter, $grade_level, $section, $student_id)
   {
      $sql = "SELECT *
              FROM grading_swh_scores
              WHERE school_year_id = " . $current_session . " 
              and quarter_id = " . $quarter . " 
              and grade_level_id = " . $grade_level . " 
              and section_id = " . $section . " 
              and student_id = " . $student_id;

      $query = $this->db->query($sql);
      return $query->result_array();
   }

   public function add_swh_data($data)
   {
      // print_r($data);
      // die();

      // $this->db->where('swh_item_id', $data['swh_item_id']);
      // $this->db->where('school_year_id', $data['school_year_id']);
      // $this->db->where('quarter_id', $data['quarter_id']);
      // $this->db->where('grade_level_id', $data['grade_level_id']);
      // $this->db->where('section_id', $data['section_id']);
      // $this->db->where('student_id', $data['student_id']);
      // $q = $this->db->get('grading_swh_scores');
      // // print_r($this->db->last_query());
      // // die();

      // if ($q->num_rows() > 0) {
      //    $this->db->where('swh_item_id', $data['swh_item_id']);
      //    $this->db->where('school_year_id', $data['school_year_id']);
      //    $this->db->where('quarter_id', $data['quarter_id']);
      //    $this->db->where('grade_level_id', $data['grade_level_id']);
      //    $this->db->where('section_id', $data['section_id']);
      //    $this->db->where('student_id', $data['student_id']);
      //    $this->writedb->delete('grading_swh_scores');
      //    // $this->writedb->update('grading_swh_scores', $data);
      //    // return;
      // } else {
      //    // $this->writedb->insert('grading_swh_scores', $data);
      //    // return $this->writedb->insert_id();
      // }

      $this->writedb->where('swh_item_id', $data['swh_item_id']);
      $this->writedb->where('student_id', $data['student_id']);
      $this->writedb->where('school_year_id', $data['school_year_id']);
      $this->writedb->where('quarter_id', $data['quarter_id']);
      $this->writedb->where('grade_level_id', $data['grade_level_id']);
      $this->writedb->where('section_id', $data['section_id']);
      $this->writedb->delete('grading_swh_scores');

      $this->writedb->insert('grading_swh_scores', $data);
      return $this->writedb->insert_id();
   }

   public function get_swh_score_quarterly($school_year, $grade_level, $section, $student_id)
   {
      $quarter_columns = "";
      $grade_level_info = $this->get_grade_level_info($grade_level);
      $resultdata = $this->get_quarter_list($grade_level_info['term_alias'], $grade_level_info['term_length']);
      // $resultdata = $this->get_quarter_list();
      $subquery = "";

      foreach ($resultdata as $row) {
         if (!empty($quarter_columns)) {
            $quarter_columns .= ", IFNULL(tbl" . $row->id . ".score, '') AS '" . $row->name . "_score'";
         } else {
            $quarter_columns .= " IFNULL(tbl" . $row->id . ".score, '') AS '" . $row->name . "_score'";
         }

         $subquery .= " left join 
                       (
                          select swh_item_id, score
                          from grading_swh_scores
                          where school_year_id = " . $school_year . " 
                          and quarter_id = " . $row->id . " 
                          and grade_level_id = " . $grade_level . " 
                          and section_id = " . $section . " 
                          and student_id = " . $student_id . " 
                        ) tbl" . $row->id . " ON tbl" . $row->id . ".swh_item_id = grading_swh.id";
      }

      $sql = "select grading_swh.id, grading_swh.main, grading_swh.sub, $quarter_columns 
              from grading_swh 
             " . $subquery . " 
              ORDER BY sort_order ASC";

      $query = $this->db->query($sql);
      // print_r($this->db->last_query());
      // die();
      return $query->result();
   }

   public function get_swh_score_quarterly_restricted($school_year, $grade_level, $section, $student_id)
   {
      $quarter_columns = "";
      $resultdata = $this->get_quarter_list();
      $subquery = "";

      foreach ($resultdata as $row) {
         if (!empty($quarter_columns)) {
            $quarter_columns .= ", IFNULL(tbl" . $row->id . ".score, '') AS '" . $row->name . "_score'";
         } else {
            $quarter_columns .= " IFNULL(tbl" . $row->id . ".score, '') AS '" . $row->name . "_score'";
         }

         $subquery .= " left join 
                       (
                          select swh_item_id, 
                          case when grading_allowed_students.view_allowed = 1 then score else null end as score
                          from grading_swh_scores
                          LEFT JOIN grading_allowed_students ON grading_allowed_students.student_id = grading_swh_scores.student_id AND grading_allowed_students.session_id = grading_swh_scores.school_year_id AND grading_allowed_students.quarter_id = " . $row->id . "  
                          where grading_swh_scores.school_year_id = " . $school_year . " 
                          and grading_swh_scores.quarter_id = " . $row->id . " 
                          and grading_swh_scores.grade_level_id = " . $grade_level . " 
                          and grading_swh_scores.section_id = " . $section . " 
                          and grading_swh_scores.student_id = " . $student_id . " 
                        ) tbl" . $row->id . " ON tbl" . $row->id . ".swh_item_id = grading_swh.id";
      }

      $sql = "select grading_swh.id, grading_swh.main, grading_swh.sub, $quarter_columns 
              from grading_swh 
             " . $subquery . " 
              ORDER BY sort_order ASC";

      $query = $this->db->query($sql);
      // print_r($this->db->last_query());
      // die();
      return $query->result();
   }

   public function grade_code_table()
   {
      $query = $this->db->query('select min_grade, max_grade, grade_code from grading_final_transmuted_grade_code');
      return $query->result();
   }

   public function conduct_transmutation_table()
   {
      $query = $this->db->query('select min_grade, max_grade, grade_code from grading_conduct_transmutation');
      return $query->result();
   }

   public function is_grade_view_allowed($school_year, $student_id, $quarter_id)
   {
      $query = $this->db->query('select view_allowed from grading_allowed_students where session_id = ' . $school_year . ' and student_id=' . $student_id . ' and quarter_id=' . $quarter_id);
      $result = $query->result()[0];
      return $result->view_allowed;
   }

   public function get_month_days_list()
   {
      $query = $this->db->query('select month, no_of_days, term from attendance_month_days order by sequence');
      // print_r($this->db->last_query());
      // exit();
      return $query->result();
   }

   public function get_student_attendance_by_month($session, $grade_level, $section, $student_id)
   {
      $this->db->select("*");
      $this->db->where("session_id", $session);
      $this->db->where("class_id", $grade_level);
      $this->db->where("section_id", $section);
      $this->db->where("student_id", $student_id);
      $student_attendance = $this->db->get("attendance_by_month")->result_array()[0];

      return $student_attendance;
   }

   public function get_student_attendance_by_semester($session, $grade_level, $section, $student_id)
   {
      $this->db->select("*");
      $this->db->where("session_id", $session);
      $this->db->where("class_id", $grade_level);
      $this->db->where("section_id", $section);
      $this->db->where("student_id", $student_id);
      $student_attendance = $this->db->get("attendance_by_semester")->result_array()[0];

      return $student_attendance;
   }

   public function get_quarter_list($header_label = '', $terms = 4)
   {
      $sql = "SELECT id, name, concat(description, ' ', '" . $header_label . "') as description FROM grading_quarter limit " . $terms;
      $query = $this->db->query($sql);
      return $query->result();
   }

   public function get_grade_level_info($grade_level_id)
   {
      return $this->db->select('*')->from('classes')->where('id', $grade_level_id)->get()->result_array()[0];
   }

   // public function get_conduct_ssapamp($session, $grade_level, $section, $student_id)
   // {
   //    $sql = "select studentid,
   //            sum(case when semester=1 then grade else 0 end)/6 s1,
   //            sum(case when semester=2 then grade else 0 end)/6 s2
   //            from 
   //            grading_studentconduct_ssapamp 
   //            where levelid = " . $grade_level . " 
   //            and studentid = " . $student_id . " 
   //            and sectionid= " . $section . " 
   //            and schoolyear = " . $session;
   //    $query = $this->db->query($sql);
   //    return $query->result()[0];
   // }

   public function get_conduct_ssapamp($session, $grade_level, $section, $student_id)
   {
      $sql = "select studentid,s1,s2,
              (select conduct_grade from grading_conduct_legend_ssapamp where s1 between mingrade and (maxgrade + 0.99)) a1,
              (select conduct_grade from grading_conduct_legend_ssapamp where s2 between mingrade and (maxgrade + 0.99)) a2,
              ((s1 + s2)/2) as totalave,
              (select conduct_grade from grading_conduct_legend_ssapamp where (s1 + s2)/2 between mingrade and maxgrade) finalgrade,va1,va2
              from (
                select studentid,
                sum(case when semester=1 then grade else 0 end)/6 s1,
                sum(case when semester=2 then grade else 0 end)/6 s2,
                (select view_allowed from grading_allowed_students where student_id = " . $student_id . " and quarter_id = 1 and session_id = " . $session . " ) va1,
                (select view_allowed from grading_allowed_students where student_id = " . $student_id . " and quarter_id = 2 and session_id = " . $session . " ) va2
                from 
                grading_studentconduct_ssapamp 
                where levelid = " . $grade_level . "  
                and sectionid = " . $section . "  
                and schoolyear = " . $session . " 
                and studentid = " . $student_id . "
               ) vv";

      // $sql = "select COALESCE (studentid,0) as studentid,average,semester,
      //       (select conduct_grade from grading_conduct_legend_ssapamp where cast(average as double) between mingrade and (maxgrade + 0.99)) as LG
      //       from (
      //       select gss.studentid,sum(gss.grade)/(select count(1) from grading_conduct_ssapamp) as average,gss.semester
      //       from grading_studentconduct_ssapamp gss 
      //       inner join grading_conduct_ssapamp gcs on gss.conductid = gcs.id
      //       where gss.studentid = " . $student_id . " and gss.levelid= " . $grade_level . " and gss.sectionid= " . $section . "  and gss.schoolyear=$session and gss.semester= " . $session . " 
      //       ) vv";

      $query = $this->db->query($sql);

      // print_r($this->db->last_query());
      // die();

      return $query->result()[0];
   }

   public function get_conduct_ssapamp_restricted($session, $grade_level, $section, $student_id)
   {
      $sql = "select studentid,s1,s2,
              case when va1 = 1 then a1 else '' end a1,
              case when va1 = 2 then a2 else '' end a2,
              finalgrade
              from (
              select studentid,s1,s2,
              (select conduct_grade from grading_conduct_legend_ssapamp where round(s1) between mingrade and (maxgrade + 0.99)) a1,
              (select conduct_grade from grading_conduct_legend_ssapamp where round(s2) between mingrade and (maxgrade + 0.99)) a2,
              ((s1 + s2)/2) as totalave,
              (select conduct_grade from grading_conduct_legend_ssapamp where round((s1 + s2)/2) between mingrade and maxgrade) finalgrade,va1,va2
              from (
                select studentid,
                sum(case when semester=1 then grade else 0 end)/6 s1,
                sum(case when semester=2 then grade else 0 end)/6 s2,
                (select view_allowed from grading_allowed_students where student_id = " . $student_id . " and quarter_id = 1 and session_id = " . $session . " ) va1,
                (select view_allowed from grading_allowed_students where student_id = " . $student_id . " and quarter_id = 2 and session_id = " . $session . " ) va2
                from 
                grading_studentconduct_ssapamp 
                where levelid = " . $grade_level . "  
                and sectionid = " . $section . "  
                and schoolyear = " . $session . " 
                and studentid = " . $student_id . "
               ) vv ) tbl";

      // $sql = "select COALESCE (studentid,0) as studentid,average,semester,
      //       (select conduct_grade from grading_conduct_legend_ssapamp where cast(average as double) between mingrade and (maxgrade + 0.99)) as LG
      //       from (
      //       select gss.studentid,sum(gss.grade)/(select count(1) from grading_conduct_ssapamp) as average,gss.semester
      //       from grading_studentconduct_ssapamp gss 
      //       inner join grading_conduct_ssapamp gcs on gss.conductid = gcs.id
      //       where gss.studentid = " . $student_id . " and gss.levelid= " . $grade_level . " and gss.sectionid= " . $section . "  and gss.schoolyear=$session and gss.semester= " . $session . " 
      //       ) vv";

      $query = $this->db->query($sql);

      // print_r($this->db->last_query());
      // die();

      return $query->result()[0];
   }

   public function get_terms_allowed($session, $student_id)
   {
      $sql = "select id, student_id, quarter_id from grading_allowed_students
              where student_id = " . $student_id . "
              and session_id = " . $session;

      $query = $this->db->query($sql);

      // print_r($this->db->last_query());
      // die();

      return $query->result();
   }
}

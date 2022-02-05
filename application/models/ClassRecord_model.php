<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Classrecord_model extends MY_Model 
{

    public function __construct() {
        parent::__construct();
        //-- Load database for writing
        $this->writedb = $this->load->database('write_db', TRUE);
    }

    public function get($table, $id=null) 
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

        $message   = DELETE_RECORD_CONSTANT . " On ".$table." id " . $id;
        $action    = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
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

    function check_data_exists($data, $table, $field) {
        $this->db->where($field, $data[$field]);        
        $query = $this->db->get($table);

        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_class_record($schoolyear, $quarter, $gradelevel, $section) {
        $subject_columns = "";
        $subquery = "";
        $average_column = "";
        $colcount = 0;

        $resultdata = $this->get_subject_list($gradelevel);

        foreach($resultdata as $row) {
            if (!empty($subject_columns)) {
                $subject_columns .= ", IFNULL(tbl" . $row->subject_id . ".quarterly_grade, 0) AS '" .$row->subject. "'" ;
                $average_column .= "+IFNULL(tbl" . $row->subject_id . ".quarterly_grade, 0)";
            }
            else {
                $subject_columns .= " IFNULL(tbl" . $row->subject_id . ".quarterly_grade, 0) AS '" .$row->subject. "'" ;
                $average_column .= "IFNULL(tbl" . $row->subject_id . ".quarterly_grade, 0)";
            }

            $subquery .= " LEFT JOIN ( 
                           SELECT school_year, quarter, student_id, grade_level, section_id, subject_id, IFNULL(fn_transmuted_grade(SUM(((total_scores/highest_score)*100) * wspercent)), 0) AS quarterly_grade 
                           FROM 
                           (SELECT school_year, quarter, student_id, grade AS grade_level, section_id, subject_id, SUM(score) AS total_scores, 
                            SUM(highest_score) AS highest_score, criteria_id, label AS criteria_label, (ws/100) AS wspercent 
                            FROM vw_class_record 
                            GROUP BY student_id, criteria_id, label 
                           ) tbl 
                           WHERE subject_id = ".$row->subject_id." 
                           GROUP BY school_year, quarter, student_id 
                         ) tbl".$row->subject_id." ON tbl".$row->subject_id.".student_id = students.id";

            $colcount++;
        }

        // $average_column = " AVG(".$average_column.") AS average";
        $average_column = " ((".$average_column.")/".$colcount.") AS average";

        $sql = "SELECT CONCAT(lastname, ', ', firstname, ' ', middlename) AS student_name, UPPER(gender), ".$subject_columns.", ".$average_column." 
                FROM students 
                LEFT JOIN student_session ON student_session.student_id = students.id 
                ".$subquery." 
                WHERE student_session.class_id = ".$gradelevel." 
                AND student_session.section_id = ".$section." 
                AND student_session.session_id = ".$schoolyear." 
                ORDER BY gender DESC, student_name ASC";

        // return $sql;
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_subject_list($gradelevel)
    {
        //-- Get subject list
        $sql = "SELECT classes.id AS grade_level_id, subjects.name AS subject, subject_group_subjects.subject_id -- subject_groups.id, subject_groups.name, subject_group_subjects.subject_id, subjects.name, subject_group_class_sections.class_section_id, classes.id as grade_level_id, classes.class as grade_level
                FROM subject_groups
                LEFT JOIN subject_group_subjects ON subject_group_subjects.subject_group_id = subject_groups.id
                LEFT JOIN subjects ON subjects.id = subject_group_subjects.subject_id
                LEFT JOIN subject_group_class_sections ON subject_group_class_sections.subject_group_id = subject_groups.id
                LEFT JOIN class_sections ON class_sections.id = subject_group_class_sections.class_section_id
                LEFT JOIN classes ON classes.id = class_sections.class_id
                WHERE classes.id = ".$gradelevel." 
                AND subjects.graded = TRUE 
                GROUP BY classes.id, subjects.name
                ORDER BY subject_groups.name, subjects.name ASC";

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_student_class_record($school_year, $student_id, $grade_level, $section) {        
        $subquery = "";
        $quarter_columns = "";        
        
        $dataresult = $this->get_quarter_list();        

        foreach($dataresult as $row) {
            if (!empty($quarter_columns)) {
                $quarter_columns .= ", IFNULL(tbl".$row->id.".quarterly_grade, 0) AS '".$row->description."'";
            }
            else {
                $quarter_columns .= " IFNULL(tbl".$row->id.".quarterly_grade, 0) AS '".$row->description."'";
            }

            $subquery .= " LEFT JOIN 
                         (
                            SELECT school_year, quarter, tbl.student_id, grade_level, tbl.section_id, subject_id, IFNULL(fn_transmuted_grade(SUM(((total_scores/highest_score)*100) * wspercent)), 0) AS quarterly_grade
                            FROM
                            (
                              SELECT school_year, quarter, student_id, grade AS grade_level, section_id, subject_id, SUM(score) AS total_scores, 
                              SUM(highest_score) AS highest_score, criteria_id, label AS criteria_label, (ws/100) AS wspercent
                              FROM vw_class_record
                              GROUP BY student_id, criteria_id, label
                            ) tbl
                            LEFT JOIN student_session ON student_session.student_id = tbl.student_id 
                            WHERE quarter = ".$row->id." 
                            AND tbl.student_id = ".$student_id." 
                            AND student_session.session_id = ".$school_year." 
                            AND student_session.section_id = ".$section." 
                            GROUP BY school_year, quarter, student_id
                         ) tbl".$row->id." ON tbl".$row->id.".subject_id = vw_class_subjects.subject_id";

            $colcount++;
        }

        $sql = "SELECT subject_name AS Subjects, ".$quarter_columns." 
                FROM vw_class_subjects
                ".$subquery." 
                WHERE graded = true
                AND grade_level_id = ".$grade_level;

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_quarter_list() {
        $sql = "SELECT * FROM grading_quarter";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_class_record_quarterly($schoolyear, $gradelevel, $section, $subject, $teacher) {
        $quarter_columns = "";        
        $resultdata = $this->get_quarter_list();
        $average_columns = "";
        $average_column = "";
        $colcount = 0;

        foreach($resultdata as $row) {
            if (!empty($quarter_columns)) {
                $quarter_columns .= ", IFNULL(tbl".$row->id.".quarterly_grade, 0) AS '".$row->description."'";
                $average_column .= "+IFNULL(tbl" . $row->id . ".quarterly_grade, 0)";
            }
            else {
                $quarter_columns .= " IFNULL(tbl".$row->id.".quarterly_grade, 0) AS '".$row->description."'";
                $average_column .= "IFNULL(tbl" . $row->id . ".quarterly_grade, 0)";
            }

            $subquery .= " LEFT JOIN 
                         (
                            SELECT school_year, quarter, tbl.student_id, grade_level, tbl.section_id, subject_id, teacher_id, IFNULL(fn_transmuted_grade(SUM(((total_scores/highest_score)*100) * wspercent)), 0) AS quarterly_grade
                            FROM
                            (
                              SELECT school_year, quarter, student_id, grade AS grade_level, section_id, subject_id, teacher_id, SUM(score) AS total_scores, 
                              SUM(highest_score) AS highest_score, criteria_id, label AS criteria_label, (ws/100) AS wspercent
                              FROM vw_class_record
                              GROUP BY student_id, criteria_id, label
                            ) tbl
                            LEFT JOIN student_session ON student_session.student_id = tbl.student_id 
                            WHERE quarter = ".$row->id." 
                            AND student_session.session_id = ".$schoolyear." 
                            AND student_session.section_id = ".$section." 
                            AND subject_id = ".$subject." 
                            AND teacher_id = ".$teacher." 
                            GROUP BY school_year, quarter, student_id
                         ) tbl".$row->id." ON tbl".$row->id.".student_id = students.id ";

            $colcount++;
        }

        $average_columns = " ((".$average_column.")/".$colcount.") AS average";

        $sql = "SELECT CONCAT(lastname, ', ', firstname, ' ', middlename) AS student_name, UPPER(gender) AS Gender, $quarter_columns, $average_columns, ROUND((".$average_column.")/".$colcount.") AS final_grade 
                FROM students 
                LEFT JOIN student_session ON student_session.student_id = students.id 
                ".$subquery." 
                WHERE student_session.class_id = ".$gradelevel." 
                AND student_session.section_id = ".$section." 
                AND student_session.session_id = ".$schoolyear." 
                ORDER BY gender DESC, student_name ASC";

        // return $sql;
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_teacher_list() {
        $sql = "SELECT id, CONCAT(TRIM(NAME), ' ', TRIM(surname)) AS teacher 
                FROM staff
                WHERE id IN (SELECT staff_id FROM staff_roles WHERE role_id = 2)
                ORDER BY teacher ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}

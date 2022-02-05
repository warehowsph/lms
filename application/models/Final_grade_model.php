<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Final_grade_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */

    public function get_students($class_id,$section_id){
        $current_session = $this->setting_model->getCurrentSession();
        $this->db->select("students.id,students.firstname,students.lastname,students.middlename,student_session.class_id,student_session.section_id,students.is_active");
        $this->db->join("students","students.id = student_session.student_id");
        $this->db->where("session_id",$current_session);
        $this->db->where("students.is_active","yes");
        $this->db->where("student_session.class_id",$class_id);
        $this->db->where("student_session.section_id",$section_id);
        $this->db->order_by("students.lastname","asc");

        $query = $this->db->get("student_session");

        $result = $query->result_array();
        return $result;
    }

    

}

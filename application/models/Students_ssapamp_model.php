<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Students_ssapamp_model extends CI_Model {

    public function getStudent($studentid)
    {
        $query=$this->db->query("select students.id,students.roll_no,students.lastname,students.firstname,
        student_session.student_id ,student_session.session_id,student_session.class_id,
        student_session.section_id,sections.`section`,students.dob,
        TIMESTAMPDIFF(YEAR, students.dob, CURDATE()) AS age 
        from students 
        inner join student_session on students.id=student_session.student_id 
        inner join sections on sections.id=student_session.section_id 
        where students.id = $studentid");
        $result = $query->result_array();

        return $result;
    }

}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grading_ssapamp_model extends CI_Model {

    public function getSectionId($section)
    {
        $this->db->where('section',$section);
        $this->db->select('id');
        $query=$this->db->get('sections');
        return $query->result();
    }

    public function getSchoolYearId($schoolyear)
    {       
        $this->db->where('session',$schoolyear);
        $this->db->select('id');
        $query=$this->db->get('sessions');
        return $query->result();
    }

    public function getLevelId($level)
    {
        // $this->db->where('class',$level);
        // $this->db->select('id');
        $level=strtolower($level);
        // $query=$this->db->get('classes');
        $sql = "select id from classes where lcase(class)='$level'";
        $query = $this->db->query($sql);    
        return $query->result();
    }

    public function getTermLength($level)
    {
        // $this->db->where('class',$level);
        // $this->db->select('term_length');
        // $query=$this->db->get('classes');
        // var_dump($this->db->last_query());
        $level=strtolower($level);
        $sql = "select term_length from classes where lcase(class)='$level'";
        $query = $this->db->query($sql); 
        return $query->result();
    }

    public function getClassBySection($classid) {
        // $userdata = $this->customlib->getUserData();
        // $role_id = $userdata["role_id"];
        // $carray = array();
     
        // if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
        //     $section=$this->teacher_model->get_teacherrestricted_modesections($userdata["id"],$classid);
   
           
        // } else {
        $this->db->select('class_sections.id,class_sections.section_id,sections.section');
        $this->db->from('class_sections');
        $this->db->join('sections', 'sections.id = class_sections.section_id');
        $this->db->where('class_sections.class_id', $classid);
        $this->db->order_by('class_sections.id');
        $query = $this->db->get();
       $section= $query->result_array();
    // }
        return $section;
    }

    public function getAllowed($studentid,$session,$quarter) {
        $query = $this->db->query("select * from grading_allowed_students where student_id = $studentid and session_id  =$session and quarter_id = $quarter and view_allowed = 1");       
        return $query->num_rows();
    }

    


    //

}
<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

class General_model extends MY_Model
{

   public function __construct()
   {
      parent::__construct();
      $this->current_session = $this->setting_model->getCurrentSession();
   }

   /**
    * This funtion takes id as a parameter and will fetch the record.
    * If id is not provided, then it will fetch all the records form the table.
    * @param int $id
    * @return mixed
    */

   public function get_role()
   {
      $current_session = $this->setting_model->getCurrentSession();
      $userdata = $this->session->userdata();

      if (array_key_exists('student', $userdata)) {
         $role = "student";
      } else {
         $role = "admin";
      }

      return $role;
   }

   public function get_account_id()
   {

      $current_session = $this->setting_model->getCurrentSession();
      $userdata = $this->session->userdata();

      if (array_key_exists('student', $userdata)) {
         $account_id = $userdata['student']['student_id'];
      } else if (array_key_exists('admin', $userdata)) {
         $account_id = $userdata['admin']['id'];
      } else {
         redirect(base_url('site/userlogin'));
         // $account_id = 1;
      }
      return $account_id;
   }
   public function get_real_role()
   {

      $account_id = $this->get_account_id();
      $this->db->select("*");
      $this->db->where("staff_id", $account_id);
      $real_role = $this->db->get("staff_roles")->result_array()[0]['role_id'];

      return $real_role;
   }

   public function get_account_name($account_id = "", $account_type = "")
   {

      if ($account_type == "admin") {
         return $this->db->select("*")->where("id", $account_id)->get("staff")->result_array();
      } else {
         return $this->db->select("*")->where("id", $account_id)->get("students")->result_array();
      }
   }

   public function get_all_staff()
   {
      return $this->db->select("*")->get("staff")->result_array();
   }

   public function get_classes()
   {
      return $this->db->select("*")->order_by("sort_order", "ASC")->get("classes")->result_array();
   }
   public function get_sections()
   {
      return $this->db->select("*")->order_by("section", "ASC")->get("sections")->result_array();
   }

   public function get_subjects()
   {
      return $this->db->select("*")->where("graded", 1)->order_by("name", "ASC")->get("subjects")->result_array();
   }

   public function get_students_class_section($class_id, $section_id)
   {
      $current_session = $this->setting_model->getCurrentSession();
      $this->db->select("students.id,students.firstname,students.lastname,students.middlename,student_session.class_id,student_session.section_id,students.is_active,students.gender");
      $this->db->join("students", "students.id = student_session.student_id");
      $this->db->where("session_id", $current_session);
      $this->db->where("student_session.class_id", $class_id);
      $this->db->where("student_session.section_id", $section_id);
      $this->db->where("students.is_active", "yes");
      $this->db->order_by("students.lastname", "asc");
      $this->db->order_by("students.firstname", "asc");

      $query = $this->db->get("student_session");

      $result = $query->result_array();
      return $result;
   }

   public function get_zoom_accounts()
   {

      // $current_session = $this->setting_model->getCurrentSession();
      // $userdata = $this->session->userdata();

      // if(array_key_exists('student', $userdata)){
      //     $account_id = $userdata['student']['student_id'];
      // }else if(array_key_exists('admin', $userdata)){
      //     $account_id = $userdata['admin']['id'];
      // }else{ 
      //     $account_id = 1;
      // }
      return $this->db->select("*")
         ->order_by("date_created", "desc")
         ->get("lms_zoom_accounts")
         ->result_array();
   }


   // public function get_quarter_list()
   // {
   //    return $this->db->select("*")->get("grading_quarter")->result_array();
   // }

   public function get_quarter_list($header_label = '', $terms = 4)
   {
      $sql = "SELECT id, name, concat(description, ' ', '" . $header_label . "') as description FROM grading_quarter limit " . $terms;
      $query = $this->db->query($sql);
      return $query->result_array();
   }
}

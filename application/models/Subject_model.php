<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

class Subject_model extends MY_Model
{

   public function __construct()
   {
      parent::__construct();
      //-- Load database for writing
      $this->writedb = $this->load->database('write_db', TRUE);
      $this->load->model('teacher_model');
   }

   public function get($id = null)
   {

      $subject_condition = 0;
      $userdata = $this->customlib->getUserData();

      $role_id = $userdata["role_id"];


      if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
         if ($userdata["class_teacher"] == 'yes') {



            $my_classes = $this->teacher_model->my_classes($userdata['id']);


            if (!empty($my_classes)) {
               $subject_condition = 0;
            } else {
               $subject_condition = 1;
               $my_subjects = $this->teacher_model->get_examsubjects($userdata['id']);
            }
         }
      }
      $this->db->select()->from('subjects');
      if ($id != null) {
         $this->db->where('id', $id);
      } else {
         if ($subject_condition == 1) {
            $this->db->where_in('subjects.id', $my_subjects);
         }
         $this->db->order_by('name');
      }
      $query = $this->db->get();
      if ($id != null) {
         return $query->row_array();
      } else {
         return $query->result_array();
      }
   }

   public function remove($id)
   {
      $this->writedb->trans_start(); # Starting Transaction
      $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
      //=======================Code Start===========================
      $this->writedb->where('id', $id);
      $this->writedb->delete('subjects');
      $message      = DELETE_RECORD_CONSTANT . " On subjects id " . $id;
      $action       = "Delete";
      $record_id    = $id;
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

   public function add($data)
   {
      $this->writedb->trans_start(); # Starting Transaction
      $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
      //=======================Code Start===========================
      if (isset($data['id'])) {
         $this->writedb->where('id', $data['id']);
         $this->writedb->update('subjects', $data);

         // print_r($this->writedb->last_query());
         // die();
         $message      = UPDATE_RECORD_CONSTANT . " On subjects id " . $data['id'];
         $action       = "Update";
         $record_id    = $data['id'];
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
      } else {
         $this->writedb->insert('subjects', $data);
         $id = $this->writedb->insert_id();
         $message      = INSERT_RECORD_CONSTANT . " On subjects id " . $id;
         $action       = "Insert";
         $record_id    = $id;
         $this->log($message, $record_id, $action);
         //echo $this->writedb->last_query();die;
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
         return $id;
      }
   }

   function check_data_exists($data)
   {
      $this->db->where('name', $data['name']);
      $query = $this->db->get('subjects');
      if ($query->num_rows() > 0) {
         return TRUE;
      } else {
         return FALSE;
      }
   }

   function check_code_exists($data)
   {
      $this->db->where('code', $data['code']);
      $query = $this->db->get('subjects');
      if ($query->num_rows() > 0) {
         return TRUE;
      } else {
         return FALSE;
      }
   }

   // public function get_subject_list($gradelevel) {
   //     $sql = "SELECT classes.id AS grade_level_id, subjects.name AS subject, subject_group_subjects.subject_id 
   //             FROM subject_groups
   //             JOIN subject_group_subjects ON subject_group_subjects.subject_group_id = subject_groups.id
   //             JOIN subjects ON subjects.id = subject_group_subjects.subject_id
   //             JOIN subject_group_class_sections ON subject_group_class_sections.subject_group_id = subject_groups.id
   //             JOIN class_sections ON class_sections.id = subject_group_class_sections.class_section_id
   //             JOIN classes ON classes.id = class_sections.class_id
   //             WHERE classes.id = ".$gradelevel." 
   //             AND subjects.graded = TRUE 
   //             GROUP BY classes.id, subjects.name
   //             ORDER BY subject_groups.name, subjects.name ASC";

   //     // print_r($sql);die();

   //     $query = $this->db->query($sql);
   //     return $query->result_array();
   // }

   public function get_subject_list($gradelevel, $schoolyear)
   {
      $sql = "SELECT classes.id AS grade_level_id, subjects.name AS subject, subject_group_subjects.subject_id, subjects.in_average, subjects.transmuted 
                FROM subject_groups
                JOIN subject_group_subjects ON subject_group_subjects.subject_group_id = subject_groups.id
                JOIN subjects ON subjects.id = subject_group_subjects.subject_id
                JOIN subject_group_class_sections ON subject_group_class_sections.subject_group_id = subject_groups.id
                JOIN class_sections ON class_sections.id = subject_group_class_sections.class_section_id
                JOIN classes ON classes.id = class_sections.class_id
                WHERE classes.id = " . $gradelevel . " 
                AND subjects.graded = TRUE 
                AND subject_groups.session_id = " . $schoolyear . " 
                GROUP BY classes.id, subjects.name
                ORDER BY subject_groups.name, subjects.name ASC";

      // print_r($sql);die();

      $query = $this->db->query($sql);
      return $query->result_array();
   }

   public function get_subject_name($id)
   {
      $result = $this->db->select('name')->from('subjects')->where('id', $id)->limit(1)->get()->row();
      return $result->name;
   }
}

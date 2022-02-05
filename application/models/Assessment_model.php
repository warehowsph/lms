<?php
class Assessment_model extends MY_Model
{

   public $table = "lms_assessment";

   public function all_assessment($account_id = "1", $timeperiod = "current")
   {
      // print_r($account_id);
      // $this->db->select('*');
      $this->db->select('*,lms_assessment.date_created as date_created, lms_assessment.id as id');
      $this->db->from('lms_assessment');
      $this->db->join('staff', 'staff.id = lms_assessment.account_id', 'left');
      $this->db->where('lms_assessment.deleted', 0);
      $this->db->where('lms_assessment.account_id', $account_id);

      if ($timeperiod == "current") {
         $this->db->where('lms_assessment.start_date <= now()');
         $this->db->where('lms_assessment.end_date >= now()');
      } else if ($timeperiod == "past") {
         $this->db->where('now() > lms_assessment.end_date');
      } else if ($timeperiod == "upcoming") {
         $this->db->where('lms_assessment.start_date > now()');
      }

      $this->db->order_by('lms_assessment.date_created', "desc");
      $this->db->limit(2500);

      $query = $this->db->get();
      // print_r($this->db->last_query());
      // die();

      $return = $query->result_array();
      return $return;
   }

   public function admin_all_assessment($account_id = "1", $timeperiod = "current")
   {
      // print_r($account_id);
      // $this->db->select('*');
      $this->db->select('*,lms_assessment.date_created as date_created, lms_assessment.id as id');
      $this->db->from('lms_assessment');
      $this->db->join('staff', 'staff.id = lms_assessment.account_id', 'left');
      $this->db->where('lms_assessment.deleted', 0);

      if ($timeperiod == "current") {
         $this->db->where('lms_assessment.start_date <= now()');
         $this->db->where('lms_assessment.end_date >= now()');
      } else if ($timeperiod == "past") {
         $this->db->where('now() > lms_assessment.end_date');
      } else if ($timeperiod == "upcoming") {
         $this->db->where('lms_assessment.start_date > now()');
      }

      $this->db->order_by('lms_assessment.date_created', "desc");
      $this->db->limit(2500);

      $query = $this->db->get();
      // print_r($this->db->last_query());
      // die();

      $return = $query->result_array();
      return $return;
   }

   public function get_assessments($account_id)
   {

      // $this->db->select('*');
      $this->db->select('*,lms_assessment.date_created as date_created, lms_assessment.id as id');
      $this->db->from('lms_assessment');
      $this->db->join('staff', 'staff.employee_id = lms_assessment.account_id', 'left');
      $this->db->where('lms_assessment.deleted', 0);
      $this->db->order_by('lms_assessment.date_created', "desc");

      $query = $this->db->get();
      $return = $query->result_array();

      return $return;
   }

   public function assigned_assessment($account_id, $timeperiod = "current")
   {
      $this->db->select('*,lms_assessment.id as id,(SELECT COUNT(lms_assessment_sheets.id) FROM lms_assessment_sheets WHERE lms_assessment_sheets.assessment_id = lms_assessment.id AND lms_assessment_sheets.account_id = ' . $account_id . ' AND lms_assessment_sheets.response_status = 1 ) as student_attempt');
      $this->db->from('lms_assessment');
      $this->db->join('staff', "staff.id = lms_assessment.account_id");
      $this->db->where("FIND_IN_SET('" . $account_id . "', lms_assessment.assigned) !=", 0);

      if ($timeperiod == "past") {
         $this->db->where('allow_result_viewing = 1');
         $this->db->where('now() > lms_assessment.end_date');

         $monthNow = date("m");

         // if ($monthNow > 6) {
         //    $this->db->where('YEAR(end_date) = YEAR(now()) AND MONTH(end_date) > 6');
         // } else {
         //    $this->db->where('YEAR(end_date) = year(now()) and month(end_date) <= 6');
         //    $this->db->where('YEAR(end_date) = YEAR(now()) - 1 AND MONTH(end_date) > 6');
         // }
      } else {
         // $this->db->where('lms_assessment.start_date >=', date('Y-m-d H:i:s'));
         // $this->db->where('lms_assessment.end_date <=', date('Y-m-d H:i:s'));
         $this->db->where('now() >= lms_assessment.start_date');
         $this->db->where('now() <= lms_assessment.end_date');
      }

      $this->db->where("deleted", 0);

      $query = $this->db->get();
      $return = $query->result_array();
      // echo '<pre>';
      // print_r($this->db->last_query());
      // exit();

      return $return;
   }

   public function assessment_sheets($assessment_id)
   {
      $this->db->select('*');
      $this->db->from('lms_assessment_sheets');
      $this->db->where("assessment_id", $assessment_id);
      $this->db->where("deleted", 0);

      $query = $this->db->get();
      $return = $query->result_array();

      return $return;
   }

   public function delete_assessment($table, $id)
   {
      $data['id'] = $id;
      $data['deleted'] = 1;

      $this->assessment_model->lms_update($table, $data);
      return true;
   }

   public function shared_assessment()
   {
      // print_r($account_id);
      // $this->db->select('*');
      $this->db->select('*,lms_assessment.date_created as date_created, lms_assessment.id as id');
      $this->db->from('lms_assessment');
      $this->db->join('staff', 'staff.id = lms_assessment.account_id', 'left');
      $this->db->where('lms_assessment.deleted', 0);
      $this->db->where('lms_assessment.shared', 1);

      $this->db->order_by('lms_assessment.date_created', "desc");
      $this->db->limit(2500);

      $query = $this->db->get();
      // print_r($this->db->last_query());
      // die();

      $return = $query->result_array();
      return $return;
   }
}

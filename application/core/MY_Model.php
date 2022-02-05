<?php
defined('BASEPATH') or exit('No direct script access allowed');
class MY_Model extends CI_Model
{

   public function __construct()
   {
      parent::__construct();
      $this->load->library('user_agent');
      $this->writedb = $this->load->database('write_db', TRUE);
   }

   public function log($message = NULL, $record_id = NULL, $action = NULL)
   {
      $user_id = $this->customlib->getStaffID();

      $ip = $this->input->ip_address();

      if ($this->agent->is_browser()) {
         $agent = $this->agent->browser() . ' ' . $this->agent->version();
      } elseif ($this->agent->is_robot()) {
         $agent = $this->agent->robot();
      } elseif ($this->agent->is_mobile()) {

         $agent = $this->agent->mobile();
      } else {
         $agent = 'Unidentified User Agent';
      }

      $platform = $this->agent->platform(); // Platform info (Windows, Linux, Mac, etc.)

      $insert = array(
         'message'    => $message,
         'user_id'    => $user_id,
         'record_id'    => $record_id,
         'ip_address' => $ip,
         'platform'   => $platform,
         'agent'      => $agent,
         'action'     => $action,
      );

      $this->db->insert('logs', $insert);
   }
   /* campuscloud development */
   public function lms_get($table = "", $value = "", $where = "", $select = "*")
   {
      if ($table) {
         $this->db->select($select);
         if ($value) {
            if ($where) {
               $this->db->where($where, $value);
            } else {
               die("Where is not defined!");
            }
         }
         $query = $this->db->get($table);
         $return = $query->result_array();
         return $return;
      } else {
         die("Table name was not defined.");
      }
   }

   public function lms_getv2($table = "", $value = "", $where = "", $select = "*", $orderby = "", $orderdirection = "ASC")
   {
      if ($table) {
         $this->db->select($select);
         if ($value) {
            if ($where) {
               $this->db->where($where, $value);
            } else {
               die("Where is not defined!");
            }
         }
         if ($orderby) {
            $this->db->order_by($orderby, $orderdirection);
         }
         $query = $this->db->get($table);
         $return = $query->result_array();
         return $return;
      } else {
         die("Table name was not defined.");
      }
   }

   public function lms_create($table = "", $data = array(), $escape = TRUE)
   {
      if ($table && is_string($table)) {
         if (!empty($data)) {
            $id = $table . "_" . $this->mode . "_" . microtime(true) * 10000;
            $id = $id . rand(1000, 9999);
            $data['id'] = $id;

            $escaped_data = array();

            foreach ($data as $data_key => $data_value) {
               if ($escape)
                  $escaped_data[$data_key] = html_escape($data_value);
               else
                  $escaped_data[$data_key] = $data_value;
            }

            $escaped_data['date_created'] = date("Y-m-d H:i:s");
            // print_r($escaped_data);die();
            if ($this->writedb->insert($table, $escaped_data))
               return $id;
            else {
               print_r($this->writedb->error());
               return false;
            }
         } else
            exit("Data is empty");
      } else {
         echo "Table name was not declared.";
         return false;
      }
   }
   public function sms_create($table = "", $data = array())
   {

      if ($table && is_string($table)) {

         if (!empty($data)) {

            $escaped_data = array();
            foreach ($data as $data_key => $data_value) {
               $escaped_data[$data_key] = html_escape($data_value);
            }

            $escaped_data['created_at'] = date("Y-m-d H:i:s");
            if ($this->writedb->insert($table, $escaped_data)) {
               return $id;
            } else {
               print_r($this->writedb->error());
               return false;
            }
         } else {
            exit("Data is empty");
         }
      } else {
         echo "Table name was not declared.";
         return false;
      }
   }

   public function lms_update($table = "", $data = array(), $where = "id")
   {

      if ($table && is_string($table)) {
         $this->writedb->where($where, $data[$where]);
         $data['date_updated'] = date("Y-m-d H:i:s");
         if ($this->writedb->update($table, $data)) {
            $this->writedb->where($where, $data[$where]);
            $query = $this->writedb->get($table);
            $return = $query->result_array()[0];
            return $return;
         } else {
            return false;
         }
      } else {
         echo "Table name was not declared.";
         exit();
      }
   }

   public function lms_delete($table = "", $data = array())
   {

      if ($table && is_string($table)) {
         $this->writedb->where("id", $data["id"]);
         $data['date_deleted'] = date("Y-m-d H:i:s");
         $data['deleted'] = 1;
         if ($this->writedb->update($table, $data)) {
            $this->writedb->where("id", $data["id"]);
            $query = $this->writedb->get($table);
            $return = $query->result_array()[0];
            return $return;
         } else {
            return false;
         }
      } else {
         echo "Table name was not declared.";
         exit();
      }
   }

   public function lms_true_delete($table = "", $data = array())
   {
      if ($table && is_string($table)) {
         $this->writedb->where("id", $data["id"]);
         $this->writedb->delete($table);
      } else {
         echo "Table name was not declared.";
         exit();
      }
   }


   public function id_generator($table)
   {
      $id = $table . "_" . $this->mode . "_" . microtime(true) * 10000;
      $id = $id . rand(1000, 1999);
      return $id;
   }
   public function filename_generator()
   {
      $id = microtime(true) * 10000;
      $id = $id . rand(1000, 1999);
      return $id;
   }
}

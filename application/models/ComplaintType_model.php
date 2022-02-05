<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ComplaintType_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        //-- Load database for writing
        $this->writedb = $this->load->database('write_db', TRUE);
    }

    public function add($table, $data) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->writedbdb->insert($table, $data);
		$id=$this->writedb->insert_id();
        $message      = INSERT_RECORD_CONSTANT." On  ".$table. " id ".$id;
        $action       = "Insert";
        $record_id    = $id;
        $this->log($message, $record_id, $action);
		//echo $this->db->last_query();die;
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

    public function get($table, $id = null) {
        $this->db->select()->from($table);
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function update($table, $complaint_type_id, $data) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->writedb->where('id', $complaint_type_id);
        $query = $this->writedb->update($table, $data);
		$message      = UPDATE_RECORD_CONSTANT." On  ".$table."  id ".$complaint_type_id;
        $action       = "Update";
        $record_id    = $complaint_type_id;
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
		
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($table, $id) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->writedb->where('id', $id);
        $this->writedb->delete($table);
		$message      = DELETE_RECORD_CONSTANT." On  ".$table."  id ".$id;
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

}

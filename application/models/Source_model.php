<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class source_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_session_name = $this->setting_model->getCurrentSessionName();
        $this->start_month = $this->setting_model->getStartMonth();
        //-- Load database for writing
        $this->writedb = $this->load->database('write_db', TRUE);
    }

    function add($source) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->writedb->insert('source', $source);
		$id=$this->writedb->insert_id();
        $message      = INSERT_RECORD_CONSTANT." On source id ".$id;
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

    public function source_list($id = null) {
        $this->db->select()->from('source');
        if ($id != null) {
            $this->db->where('source.id', $id);
        } else {
            $this->db->order_by('source.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function delete($id) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->writedb->where('id', $id);
        $this->writedb->delete('source');
		$message      = DELETE_RECORD_CONSTANT." On  source id ".$id;
        $action       = "Dalete";
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

    public function update($id, $data) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->writedb->where('id', $id);
        $this->writedb->update('source', $data);
		$message      = UPDATE_RECORD_CONSTANT." On  source id ".$id;
        $action       = "Update";
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

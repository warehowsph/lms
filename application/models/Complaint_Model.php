<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class complaint_Model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_session_name = $this->setting_model->getCurrentSessionName();
        $this->start_month = $this->setting_model->getStartMonth();
        //-- Load database for writing
        $this->writedb = $this->load->database('write_db', TRUE);
    }

    public function add($data) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->writedb->insert('complaint', $data);
        $query = $this->writedb->insert_id();
		$message      = INSERT_RECORD_CONSTANT." On  Complain id ".$query;
        $action       = "Insert";
        $record_id    = $query;
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
		return $query;
    }

    public function image_add($complaint_id, $image) {
        $array = array('id' => $complaint_id);
        $this->writedb->set('image', $image);
        $this->writedb->where($array);
        $this->writedb->update('complaint');
    }

    public function complaint_list($id = null) {
        $this->db->select()->from('complaint');
        if ($id != null) {
            $this->db->where('complaint.id', $id);
        } else {
            $this->db->order_by('complaint.id', "desc");
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function image_delete($id, $img_name) {
        $file = "./uploads/front_office/complaints/" . $img_name;
        unlink($file);
        $this->writedb->where('id', $id);
        $this->writedb->delete('complaint');
        $controller_name = $this->uri->segment(2);
    }

    public function compalaint_update($id, $data) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->writedb->where('id', $id);
        $this->writedb->update('complaint', $data);
		$message      = UPDATE_RECORD_CONSTANT." On Complaint id ".$id;
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

    function delete($id) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->writedb->where('id', $id);
        $this->writedb->delete('complaint');
		$message      = DELETE_RECORD_CONSTANT." On Complaint id ".$id;
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

    function getComplaintType() {
        $this->db->select('*');
        $this->db->from('complaint_type');
        $query = $this->db->get();
        return $query->result_array();
    }

    function getComplaintSource() {

        $this->db->select('*');
        $this->db->from('source');
        $query = $this->db->get();
        return $query->result_array();
    }

}

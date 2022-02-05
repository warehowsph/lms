<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Marksheet_model extends MY_model {

    function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        //-- Load database for writing
        $this->writedb = $this->load->database('write_db', TRUE);
    }

   


    public function get($id = null) {
        $this->db->select()->from('template_marksheets');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    public function getidcardbyid($idcard) {
        $this->db->select('*');
        $this->db->from('  template_marksheets');
        $this->db->where('id', $idcard);
        $query = $this->db->get();
        return $query->result();
    }

      public function add($data) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->writedb->where('id', $data['id']);
            $this->writedb->update('template_marksheets', $data);
			$message      = UPDATE_RECORD_CONSTANT." On  marksheets id ".$data['id'];
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
            $this->writedb->insert('template_marksheets', $data);
            $id=$this->writedb->insert_id();
			$message      = INSERT_RECORD_CONSTANT." On  marksheets id ".$id;
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

    function remove($id){
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->writedb->where('id',$id);
        $this->writedb->delete('template_marksheets');
		$message      = DELETE_RECORD_CONSTANT." On marksheets id ".$id;
        $action       = "Delete";
        $record_id    = $id;
        $this->log($message, $record_id, $action);
        $this->writedb->trans_complete();
        if ($this->writedb->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }


}

?>
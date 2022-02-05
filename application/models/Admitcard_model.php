<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admitcard_model extends MY_model {

    function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        //-- Load database for writing
        $this->writedb = $this->load->database('write_db', TRUE);
    }

    // public function getcard() {
    //     $this->db->select('*');
    //     $this->db->from('template_admitcards');
    //     $query = $this->db->get();        
    //     return $query->result();
    // }


    public function get($id = null) {
        $this->db->select()->from('template_admitcards');
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
        $this->db->from('template_admitcards');
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
            $this->writedb->update('template_admitcards', $data);
            //echo $this->writedb->last_query();die;
			$message      = UPDATE_RECORD_CONSTANT." On  admit cards id ". $data['id'];
			$action       = "Update";
			$record_id    =  $id = $data['id'];
			$this->log($message, $record_id, $action);			
           
        } else {
            $this->writedb->insert('template_admitcards', $data);
           
			$id=$this->writedb->insert_id();
			$message      = INSERT_RECORD_CONSTANT." On admit cards id ".$id;
			$action       = "Insert";
			$record_id    = $id;
			$this->log($message, $record_id, $action);
			
			
			//return $id;
        }
		//======================Code End==============================

			$this->writedb->trans_complete(); # Completing transaction
			/*Optional*/

			if ($this->writedb->trans_status() === false) {
				# Something went wrong.
				$this->writedb->trans_rollback();
				return false;

			} else {
				return $id;
			}
        
    }

    public function remove($id){
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->writedb->where('id',$id);
        $this->writedb->delete('template_admitcards');
		$message      = DELETE_RECORD_CONSTANT." On admit cards id ".$id;
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
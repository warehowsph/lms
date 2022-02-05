<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Notificationsetting_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        //-- Load database for writing
        $this->writedb = $this->load->database('write_db', TRUE);
    }

    public function get($id = null)
    {
        $this->db->select()->from('notification_setting');
        if ($id != null) {
            $this->db->where('notification_setting.id', $id);
        } else {
            $this->db->order_by('notification_setting.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    public function add($data)
    {
        $this->db->select()->from('notification_setting');
        $this->db->where('notification_setting.type', $data['type']);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            $result = $q->row();

            $this->writedb->where('id', $result->id);
            $this->writedb->update('notification_setting', $data);
        } else {
            $this->writedb->insert('notification_setting', $data);
            return $this->writedb->insert_id();
        }
    }

    public function update($data)
    {
        $this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
		//=======================Code Start===========================
        $this->writedb->where('id', $data['id']);
        $this->writedb->update('notification_setting', $data);
		$message      = UPDATE_RECORD_CONSTANT." On notification setting id ".$data['id'];
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
			return true;
		}
    }

    public function updatebatch($update_array)
    {

        $this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
		//=======================Code Start===========================
        if (isset($update_array) && !empty($update_array)) {

            $this->writedb->update_batch('notification_setting', $update_array, 'id');
        }
		foreach($update_array as $ua){
			$message      = UPDATE_RECORD_CONSTANT." On notification setting id ".$ua['id'];
			$action       = "Update";
			$record_id    = $ua['id'];
			$this->log($message, $record_id, $action);
		}
		//======================Code End==============================
        $this->writedb->trans_complete(); # Completing transaction

        if ($this->writedb->trans_status() === false) {
            $this->writedb->trans_rollback();
            return false;
        } else {
            $this->writedb->trans_commit();
            return true;
        }

    }

}

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Feegroup_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        //-- Load database for writing
        $this->writedb = $this->load->database('write_db', TRUE);
    }

    public function get($id = null) {
        $this->db->select()->from('fee_groups');
        $this->db->where('is_system', 0);
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

    public function getFeesByGroupFiltered()
    {
        $this->db->select('fee_session_groups.*,fee_groups.name as `group_name`');
        $this->db->from('fee_session_groups');
        $this->db->join('fee_groups', 'fee_groups.id = fee_session_groups.fee_groups_id');
        $this->db->where('fee_session_groups.session_id', $this->current_session);
        $this->db->where('fee_groups.is_system', 0);
        $this->db->order_by('fee_groups.name', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->writedb->where('id', $id);
        $this->writedb->where('is_system', 0);
        $this->writedb->delete('fee_groups');
		$message      = DELETE_RECORD_CONSTANT." On  fee groups id ".$id;
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

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->writedb->where('id', $data['id']);
            $this->writedb->update('fee_groups', $data);
			$message      = UPDATE_RECORD_CONSTANT." On  fee groups id ".$data['id'];
			$action       = "Update";
			$record_id    = $id = $data['id'];
			$this->log($message, $record_id, $action);
			
        } else {
            $this->writedb->insert('fee_groups', $data);
            $id= $this->writedb->insert_id();
			$message      = INSERT_RECORD_CONSTANT." On  fee groups id ".$id;
			$action       = "Insert";
			$record_id    = $id;
			$this->log($message, $record_id, $action);
			
			//return $id;
        }
		//echo $this->writedb->last_query();die;
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

    public function check_exists($str) {
        $name = $this->security->xss_clean($str);
        $id = $this->input->post('id');
        if (!isset($id)) {
            $id = 0;
        }

        if ($this->check_data_exists($name, $id)) {
            $this->form_validation->set_message('check_exists', 'Record already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function check_data_exists($name, $id) {
        $this->db->where('name', $name);
        $this->db->where('id !=', $id);

        $query = $this->db->get('fee_groups');
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function checkGroupExistsByName($name) {
        $this->db->where('name', $name);
        $query = $this->db->get('fee_groups');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

}

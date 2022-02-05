<?php

/**
 * 
 */
class Leavetypes_model extends MY_model {

    function __construct() {
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date = $this->setting_model->getDateYmd();
        //-- Load database for writing
        $this->writedb = $this->load->database('write_db', TRUE);
    }

    public function addLeaveType($data) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->writedb->where('id', $data['id']);
            $this->writedb->update('leave_types', $data);
			$message      = UPDATE_RECORD_CONSTANT." On leave types id ".$data['id'];
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
            $this->writedb->insert('leave_types', $data);
            $id = $this->writedb->insert_id();
			$message      = INSERT_RECORD_CONSTANT." On leave types id ".$id;
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
			return $id ;
        }
    }

    public function getLeaveType() {

        $query = $this->db->get('leave_types');
        return $query->result_array();
    }

    public function deleteLeaveType($id) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->writedb->where('id', $id);
        $this->writedb->delete('leave_types');
		$message      = DELETE_RECORD_CONSTANT." On subjects id ".$id;
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

    public function valid_leave_type($str) {
        $type = $this->input->post('type');
        $id = $this->input->post('leavetypeid');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_data_exists($type, $id)) {
            $this->form_validation->set_message('check_exists', 'Record already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function check_data_exists($name, $id) {

        if ($id != 0) {
            $data = array('id != ' => $id, 'type' => $name);
            $query = $this->db->where($data)->get('leave_types');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {

            $this->db->where('type', $name);
            $query = $this->db->get('leave_types');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

}

?>
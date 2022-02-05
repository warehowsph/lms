<?php

/**
 * 
 */
class Department_model extends MY_model {

    public function __construct()
    {
        parent::__construct();
        //-- Load database for writing
        $this->writedb = $this->load->database('write_db', TRUE);
    }

    public function valid_department($str) {
        $type = $this->input->post('type');
        $id = $this->input->post('departmenttypeid');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_department_exists($type, $id)) {
            $this->form_validation->set_message('check_exists', 'Record already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function check_department_exists($name, $id) {

        if ($id != 0) {
            $data = array('id != ' => $id, 'department_name' => $name);
            $query = $this->db->where($data)->get('department');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {

            $this->db->where('department_name', $name);
            $query = $this->db->get('department');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    function deleteDepartment($id) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->writedb->where("id", $id)->delete("department");
		$message      = DELETE_RECORD_CONSTANT." On department id ".$id;
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

    function getDepartmentType($id = null) {

        if (!empty($id)) {

            $query = $this->db->where("id", $id)->get('department');
            return $query->row_array();
        } else {

            $query = $this->db->get("department");
            return $query->result_array();
        }
    }

    public function addDepartmentType($data) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->writedb->where('id', $data['id']);
            $this->writedb->update('department', $data);
			$message      = UPDATE_RECORD_CONSTANT." On department id ".$data['id'];
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
            $this->writedb->insert('department', $data);
            $id = $this->writedb->insert_id();
			$message      = INSERT_RECORD_CONSTANT." On department id ".$id;
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

}

?>
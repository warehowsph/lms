<?php

/**
 * 
 */
class Designation_model extends MY_model {

    public function __construct()
    {
        parent::__construct();
        //-- Load database for writing
        $this->writedb = $this->load->database('write_db', TRUE);
    }

    public function get($id = null) {

        if (!empty($id)) {
            $query = $this->db->where("id", $id)->get("staff_designation");
            return $query->row_array();
        } else {
            $query = $this->db->where("is_active", "yes")->get("staff_designation");

            return $query->result_array();
        }
    }

    public function valid_designation() {

        $type = $this->input->post('type');
        $id = $this->input->post('designationid');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_designation_exists($type, $id)) {
            $this->form_validation->set_message('check_exists', 'Record already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function check_designation_exists($name, $id) {

        if ($id != 0) {
            $data = array('id != ' => $id, 'designation' => $name);
            $query = $this->db->where($data)->get('staff_designation');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {

            $this->db->where('designation', $name);
            $query = $this->db->get('staff_designation');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    function deleteDesignation($id) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->writedb->where("id", $id)->delete("staff_designation");
		$message      = DELETE_RECORD_CONSTANT." On staff designation id ".$id;
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

    function addDesignation($data) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data["id"])) {

            $this->writedb->where("id", $data["id"])->update("staff_designation", $data);
			$message      = UPDATE_RECORD_CONSTANT." On  staff designation id ".$data['id'];
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

            $this->writedb->insert("staff_designation", $data);
			$id=$this->writedb->insert_id();
			$message      = INSERT_RECORD_CONSTANT." On  staff designation id ".$id;
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
        }
    }

}

?>
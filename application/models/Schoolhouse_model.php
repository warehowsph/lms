<?php
class Schoolhouse_model extends MY_model {

    public function __construct() {
        parent::__construct();
        //-- Load database for writing
        $this->writedb = $this->load->database('write_db', TRUE);
    }

    public function get($id = null) {

        if (!empty($id)) {

            $query = $this->db->where("id", $id)->get("school_houses");

            return $query->row_array();
        } else {

            $query = $this->db->get("school_houses");
            return $query->result_array();
        }
    }

    public function add($data) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data["id"])) {
            $this->writedb->where("id", $data["id"])->update("school_houses", $data);
			$message      = UPDATE_RECORD_CONSTANT." On  school houses id ".$data["id"];
			$action       = "Update";
			$record_id    = $data["id"];
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
            $this->writedb->insert("school_houses", $data);
			$id=$this->writedb->insert_id();
			$message      = INSERT_RECORD_CONSTANT." On school houses id ".$id;
			$action       = "Insert";
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

    public function delete($id) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->writedb->where("id", $id)->delete("school_houses");
		$message      = DELETE_RECORD_CONSTANT." On school houses id ".$id;
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

?>
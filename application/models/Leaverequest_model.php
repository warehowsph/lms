<?php

/**
 * 
 */
class Leaverequest_model extends MY_model {

    public function __construct() {
        parent::__construct();
        //-- Load database for writing
        $this->writedb = $this->load->database('write_db', TRUE);
    }

    public function staff_leave_request($id = null) {

        if ($id != null) {
            $this->db->where("staff_leave_request.staff_id", $id);
        }

        $query = $this->db->select('staff.name,staff.surname,staff.employee_id,staff_leave_request.*,leave_types.type')->join("staff", "staff.id = staff_leave_request.staff_id")->join("leave_types", "leave_types.id = staff_leave_request.leave_type_id")->where("staff.is_active", "1")->order_by("staff_leave_request.id", "desc")->get("staff_leave_request");

        return $query->result_array();
    }

    public function user_leave_request($id = null) {


        $query = $this->db->select('staff.name,staff.surname,staff.employee_id,staff_leave_request.*,leave_types.type')->join("staff", "staff.id = staff_leave_request.staff_id")->join("leave_types", "leave_types.id = staff_leave_request.leave_type_id")->where("staff.is_active", "1")->where("staff.id", $id)->order_by("staff_leave_request.id", "desc")->get("staff_leave_request");

        return $query->result_array();
    }

    public function allotedLeaveType($id) {

        $query = $this->db->select('staff_leave_details.*,leave_types.type,leave_types.id as typeid')->where(array('staff_id' => $id))->join("leave_types", "staff_leave_details.leave_type_id = leave_types.id")->get("staff_leave_details");

        return $query->result_array();
    }

    public function countLeavesData($staff_id, $leave_type_id) {

        $query1 = $this->db->select('sum(leave_days) as approve_leave')->where(array('staff_id' => $staff_id, 'status' => 'approve', 'leave_type_id' => $leave_type_id))->get("staff_leave_request");
        return $query1->row_array();
    }

    public function changeLeaveStatus($data, $staff_id) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->writedb->where("id", $staff_id)->update("staff_leave_request", $data);
		$message      = UPDATE_RECORD_CONSTANT." On staff leave request id ".$staff_id;
			$action       = "Update";
			$record_id    = $staff_id;
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

    public function getLeaveSummary() {

        $query = $this->db->select('*')->get("staff");

        return $query->result_array();
    }

    function addLeaveRequest($data) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {

            $this->writedb->where("id", $data["id"]);
            $this->writedb->update("staff_leave_request", $data);
			$message      = UPDATE_RECORD_CONSTANT." On staff leave request id ".$data['id'];
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

            $this->writedb->insert("staff_leave_request", $data);
			$id = $this->writedb->insert_id();		
			$message      = INSERT_RECORD_CONSTANT." On staff leave request id ".$id;
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
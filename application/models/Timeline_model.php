<?php

class Timeline_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        //-- Load database for writing
        $this->writedb = $this->load->database('write_db', TRUE);
    }

    public function add($data) {

        if (isset($data["id"])) {

            $this->writedb->where("id", $data["id"])->update("student_timeline", $data);
        } else {

            $this->writedb->insert("student_timeline", $data);
            return $this->writedb->insert_id();
        }
    }

    public function add_staff_timeline($data) {

        if (isset($data["id"])) {

            $this->writedb->where("id", $data["id"])->update("staff_timeline", $data);
        } else {

            $this->writedb->insert("staff_timeline", $data);
            return $this->writedb->insert_id();
        }
    }

    public function getStudentTimeline($id, $status = '') {

        if (!empty($status)) {

            $this->db->where("status", "yes");
        }
        $query = $this->db->where("student_id", $id)->order_by("timeline_date", "asc")->get("student_timeline");
        return $query->result_array();
    }

    public function getStaffTimeline($id, $status = '') {


        if (!empty($status)) {

            $this->db->where("status", $status);
        }
        $query = $this->db->where("staff_id", $id)->order_by("timeline_date", "asc")->get("staff_timeline");
        return $query->result_array();
    }

    public function delete_timeline($id) {

        $this->writedb->where("id", $id)->delete("student_timeline");
    }

    public function delete_staff_timeline($id) {

        $this->writedb->where("id", $id)->delete("staff_timeline");
    }

}

?>
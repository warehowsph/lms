<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Assessment_model extends JOE_Model {

    public function __construct() {
        parent::__construct();

    }

    public function response($id,$account_id,$response_status=1){

        $this->read->select("*");
        $this->read->where("account_id", $account_id);
        $this->read->where("assessment_id", $id);
        $this->read->where("response_status",$response_status);
        $query = $this->read->get("lms_assessment_sheets");
        return $query->result_array();

    }



}

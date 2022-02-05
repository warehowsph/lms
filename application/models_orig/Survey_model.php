<?php
class Survey_model extends MY_Model {

	public $table = "lms_survey";

	public function all_survey($account_id=1){

        $this->db->select('*,lms_survey.date_created as date_created, lms_survey.id as id');
        $this->db->from('lms_survey');
        $this->db->join('staff', 'staff.employee_id = lms_survey.account_id','left');
        $this->db->where('lms_survey.deleted',0);
        $this->db->where('lms_survey.account_id',$account_id);
        $this->db->order_by('lms_survey.date_created',"desc");

        $query = $this->db->get();

        $return = $query->result_array();
        return $return;
    }

    public function assigned_survey($account_id){

        $this->db->select('*');
        $this->db->from('lms_survey');
        $this->db->where("FIND_IN_SET('".$account_id."', assigned) !=", 0);
        $this->db->where("deleted", 0);

        $query = $this->db->get();

        $return = $query->result_array();
        return $return;
    }

    public function delete_survey($table,$id){
        $data['id'] = $id;
        $data['deleted'] = 1;
        
        $this->db->where('id',$id);
        $this->survey_model->update($table,$data);
        return true;
    }

    public function survey($id) {

        $this->db->select('sheet');
        $this->db->from('lms_survey');
        $this->db->where('lms_survey.id', $id);

        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    public function survey_responses($id) {
        $this->db->select('respond');
        $this->db->from('lms_survey_sheets');
        $this->db->where('survey_id', $id);
        $this->db->where('respond !=', null);
        $this->db->where('respond !=', '');

        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    public function survey_sheets($id) {

        $this->db->select('lms_survey_sheets.id, lms_survey_sheets.account_id, respond, lms_survey.id AS survey_id, lms_survey.survey_name AS survey_name, lms_survey.survey_file AS survey_pdf_file_name, lms_survey.date_created AS survey_date_created');
        $this->db->from('lms_survey_sheets');
        $this->db->join('lms_survey', 'lms_survey.id = lms_survey_sheets.survey_id', 'left');
        $this->db->where('lms_survey_sheets.survey_id', $id);
        $this->db->where('lms_survey_sheets.respond !=', null);
        $this->db->where('lms_survey_sheets.respond !=', '');

        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }
	
}

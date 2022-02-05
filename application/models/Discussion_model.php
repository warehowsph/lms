<?php
class Discussion_model extends MY_Model {

	public $table = "lms_discussion";

	public function lesson_discussion($lesson_id){

        // $this->db->select('*');
        $this->db->select('lms_discussion.content,lms_discussion.account_id,lms_discussion.account_type');
        $this->db->from('lms_discussion');
        $this->db->where('lms_discussion.deleted',0);
        $this->db->where('lms_discussion.lesson_id',$lesson_id);
        // $this->db->order_by('lms_discussion.date_created',"desc");

        $query = $this->db->get();

        $return = $query->result_array();
        
        return $return;
    }
	
}

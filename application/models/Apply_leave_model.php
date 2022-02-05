<?php
 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class apply_leave_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date = $this->setting_model->getDateYmd();
        //-- Load database for writing
        $this->writedb = $this->load->database('write_db', TRUE);
    }

    public function get($id = null, $carray=null,$section_array) {
        $this->db->select('student_applyleave.*, students.firstname, students.lastname, staff.name as staff_name, students.id as stud_id, staff.surname, classes.id as class_id,
                           sections.id as section_id, classes.class, sections.section');
        $this->db->from('student_applyleave');
        $this->db->join('student_session', 'student_session.id = student_applyleave.student_session_id');
        $this->db->join('students','students.id=student_session.student_id','inner');
        $this->db->join('staff','staff.id=student_applyleave.approve_by','left');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');

        if($carray!=null){
            $this->db->where_in('classes.id', $carray);
        }

         if($section_array!=null){
            $this->db->where_in('sections.id', $section_array);
        }
        
        if ($id != null) {
            $this->db->where('student_applyleave.id', $id);
        } 
        else {           
            $this->db->order_by('student_applyleave.id');
        }

        $this->db->where('student_session.session_id', $this->current_session);
        $query = $this->db->get();

        if ($id != null) {
            return $query->row_array();
        } 
        else {
            return $query->result_array();
        }
    }

    public function get_student($student_session_id = null) {

        // $this->db->select()->from('student_applyleave');

        // if ($student_session_id != null) {

        //     $this->db->where('student_session_id', $student_session_id);

        // } else {
           
        //     $this->db->order_by('id');
        // }

        // $query = $this->db->get();

        
        //     return $query->result_array();
        $this->db->select('student_applyleave.*,students.firstname,students.lastname,staff.name as staff_name,staff.surname,classes.id as class_id,sections.id as section_id,classes.class,sections.section')->from('student_applyleave')->join('student_session', 'student_session.id = student_applyleave.student_session_id')->join('students','students.id=student_session.student_id','inner');
        $this->db->join('staff','staff.id=student_applyleave.approve_by','left');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('student_session.id', $student_session_id);

        $query = $this->db->get();
        return $query->result_array();
        

    }

     public function add($data) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->writedb->where('id', $data['id']);
            $this->writedb->update('student_applyleave', $data);
			$message      = UPDATE_RECORD_CONSTANT." On  student apply leave id ".$data['id'];
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
            $this->writedb->insert('student_applyleave', $data);
            $id = $this->writedb->insert_id();
			$message = INSERT_RECORD_CONSTANT." On student apply leave id ".$id;
			$action = "Insert";
			$record_id = $id;
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
            
			return $id;
        }
    }

    public function get_studentsessionId($class_id,$section_id,$student_id){
        $where['class_id']=$class_id;
        $where['section_id']=$section_id;
        $where['student_id']=$student_id;

       return $this->db->select('id')->from('student_session')->where($where)->get()->row_array();


    } 

    public function get_studentsessionId2($student_id) {
        $where['student_id']=$student_id;
        return $this->db->select('id')->from('student_session')->where($where)->get()->row_array();
    } 

    public function remove_leave($id){
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->writedb->where('id',$id);
        $this->writedb->delete('student_applyleave');
		$message      = DELETE_RECORD_CONSTANT." On student apply leave id ".$id;
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

    public function get_children($parentid) {
        $this->db->select('student_applyleave.*, students.firstname, students.lastname, staff.name as staff_name, students.id as stud_id, staff.surname, classes.id as class_id,
                           sections.id as section_id, classes.class, sections.section');
        $this->db->from('student_applyleave');
        $this->db->join('student_session', 'student_session.id = student_applyleave.student_session_id');
        $this->db->join('students','students.id=student_session.student_id','inner');
        $this->db->join('staff','staff.id=student_applyleave.approve_by','left');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->where('students.parent_id', $parentid);
        $this->db->order_by('student_applyleave.id');
        
        $query = $this->db->get();

        if ($id != null) {
            return $query->row_array();
        } 
        else {
            return $query->result_array();
        }
    }
}
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lesson_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */

    public function get_lessons($account_id="",$folder="today"){

        $this->db->select("*, lms_lesson.id as id,subjects.name as subject_name");
        $this->db->join("subjects","subjects.id = lms_lesson.subject_id");
        $this->db->join("classes","classes.id = lms_lesson.grade_id","left");
        $this->db->join("staff","staff.id = lms_lesson.account_id");
        $this->db->where("lms_lesson.account_id",$account_id);
        if($folder=="today"){
            $this->db->where('start_date <=', date('Y-m-d H:i:s'));
            $this->db->where('end_date >=', date('Y-m-d H:i:s'));
        }else if($folder=="upcoming"){
            $this->db->where('start_date >', date('Y-m-d H:i:s'));
        }else{
            $this->db->where('end_date <', date('Y-m-d H:i:s'));
        }
        $this->db->where('lms_lesson.deleted',0);
        $this->db->order_by('lms_lesson.date_created',"desc");
        $query = $this->db->get("lms_lesson");

        $result = $query->result_array();
        return $result;
    }

    public function admin_lessons($account_id="",$folder="today"){
        date_default_timezone_set('Asia/Manila');

        $this->db->select("*, lms_lesson.id as id, subjects.name as subject_name,staff.google_meet as teacher_google_meet");
        $this->db->join("subjects","subjects.id = lms_lesson.subject_id");
        $this->db->join("classes","classes.id = lms_lesson.grade_id","left");
        $this->db->join("staff","staff.id = lms_lesson.account_id");
        if($folder=="today"){
            $this->db->where('start_date <=', date('Y-m-d H:i:s'));
            $this->db->where('end_date >=', date('Y-m-d H:i:s'));
        }else if($folder=="upcoming"){
            $this->db->where('start_date >', date('Y-m-d H:i:s'));
        }else{
            $this->db->where('end_date <', date('Y-m-d H:i:s'));
        }
        
        $this->db->where('lms_lesson.deleted',0);
        $this->db->order_by('lms_lesson.start_date',"desc");
        $query = $this->db->get("lms_lesson");

        $result = $query->result_array();
        return $result;
    }

    public function admin_deleted($account_id="",$folder="today"){
        date_default_timezone_set('Asia/Manila');
        $this->db->select("*, lms_lesson.id as id, subjects.name as subject_name");
        $this->db->join("subjects","subjects.id = lms_lesson.subject_id");
        $this->db->join("classes","classes.id = lms_lesson.grade_id","left");
        $this->db->join("staff","staff.id = lms_lesson.account_id");
        $this->db->where('lms_lesson.deleted',1);
        $this->db->order_by('lms_lesson.start_date',"desc");
        $query = $this->db->get("lms_lesson");

        $result = $query->result_array();
        return $result;
    }

    public function get_lessons_no_virtual($account_id=""){

        $this->db->select("*, lms_lesson.id as id");
        $this->db->join("subjects","subjects.id = lms_lesson.subject_id");
        $this->db->join("classes","classes.id = lms_lesson.grade_id");
        $this->db->where("lms_lesson.account_id",$account_id);
        $this->db->where('lms_lesson.lesson_type !=',"virtual");
        $this->db->where('lms_lesson.deleted',0);
        $this->db->order_by('lms_lesson.date_created',"desc");
        $query = $this->db->get("lms_lesson");

        $result = $query->result_array();
        return $result;
    }

    public function get_lessons_virtual_only($account_id=""){

        $this->db->select("*, lms_lesson.id as id");
        $this->db->join("subjects","subjects.id = lms_lesson.subject_id");
        $this->db->join("classes","classes.id = lms_lesson.grade_id");
        $this->db->where("lms_lesson.account_id",$account_id);
        $this->db->where('lms_lesson.lesson_type',"virtual");
        $this->db->where('lms_lesson.deleted',0);
        $this->db->order_by('lms_lesson.date_created',"desc");
        $query = $this->db->get("lms_lesson");

        $result = $query->result_array();
        return $result;
    }


    public function get_shared_lessons($account_id=""){

        $this->db->select("*, lms_lesson.id as id");
        $this->db->join("subjects","subjects.id = lms_lesson.subject_id");
        $this->db->join("classes","classes.id = lms_lesson.grade_id","left");
        $this->db->where("lms_lesson.shared","1");
        $this->db->where('deleted',0);
        $this->db->order_by('lms_lesson.date_created','asc');
        $query = $this->db->get("lms_lesson");

        $result = $query->result_array();
        return $result;
    }

    public function lesson_schedule_admin($account_id=""){

        $this->db->select("*, lms_lesson.id as id");
        $this->db->join("subjects","subjects.id = lms_lesson.subject_id");
        $this->db->join("classes","classes.id = lms_lesson.grade_id");
        $this->db->join("staff","staff.id = lms_lesson.account_id");
        $this->db->where("lms_lesson.account_id",$account_id);
        $this->db->where('deleted',0);
        $query = $this->db->get("lms_lesson");

        $result = $query->result_array();
        return $result;
    }

    public function student_lessons($account_id="",$folder="today"){
        date_default_timezone_set('Asia/Manila');
        $this->db->select("*,subjects.name as subject_name,lms_lesson.id as lesson_id");
        $this->db->where("FIND_IN_SET('".$account_id."', lms_lesson.assigned) !=", 0);
        $this->db->join("subjects","subjects.id = lms_lesson.subject_id");
        $this->db->join("classes","classes.id = lms_lesson.grade_id");
        $this->db->join("staff","staff.id = lms_lesson.account_id");
        if($folder=="today"){
            $this->db->where('start_date <=', date('Y-m-d H:i:s'));
            $this->db->where('end_date >=', date('Y-m-d H:i:s'));
        }else if($folder=="upcoming"){
            $this->db->where('start_date >', date('Y-m-d H:i:s'));
        }else{
            $this->db->where('end_date <', date('Y-m-d H:i:s'));
        }
        // $this->db->where('start_date <=', date('Y-m-d H:i:s'));
        // $this->db->where('end_date >=', date('Y-m-d H:i:s'));
        $this->db->where('lms_lesson.deleted',0);
        $this->db->order_by('lms_lesson.start_date',"asc");
        $query = $this->db->get("lms_lesson");

        $result = $query->result_array();
        return $result;
    }

    public function upcoming_lessons($account_id=""){
        date_default_timezone_set('Asia/Manila');
        $this->db->select("*");
        $this->db->where("FIND_IN_SET('".$account_id."', lms_lesson.assigned) !=", 0);
        $this->db->where('start_date LIKE', date('Y-m-d'));
        $this->db->or_where('start_date >', date('Y-m-d H:i:s'));
        $this->db->where('end_date >=', date('Y-m-d H:i:s'));
        $this->db->where('lms_lesson.deleted',0);
        $this->db->order_by('lms_lesson.start_date',"asc");

        $query = $this->db->get("lms_lesson");

        $result = $query->result_array();
        return $result;
    }

    public function get_students(){
        $current_session = $this->setting_model->getCurrentSession();
        $this->db->select("students.id,students.firstname,students.lastname,student_session.class_id,student_session.section_id,students.is_active");
        $this->db->join("students","students.id = student_session.student_id");
        $this->db->where("session_id",$current_session);
        $this->db->where("students.is_active","yes");
        $this->db->order_by("students.lastname","asc");

        $query = $this->db->get("student_session");

        $result = $query->result_array();
        return $result;
    }

    public function get_class_sections(){
        $this->db->select("*");
        $this->db->join("classes","classes.id = class_sections.class_id");
        $this->db->join("sections","sections.id = class_sections.section_id");
        $query = $this->db->get("class_sections");
        $result = $query->result_array();
        return $result;
        
    }
    
    public function search_my_resources($account_id="",$search=""){
        $this->db->select("*");
        if($search){
            $this->db->like("name",$search);    
        }
        $this->db->where("account_id",$account_id);
        $this->db->order_by("date_created","desc");
        $query = $this->db->get("lms_my_resources");
        $result = $query->result_array();
        return $result;
    }

    public function search_cms_resources($account_id="",$search=""){
        $this->db->select("*");
        if($search){
            $this->db->like("name",$search);    
        }
        $this->db->order_by("date_created","desc");
        $query = $this->db->get("lms_cms_resources");
        $result = $query->result_array();
        return $result;
    }

    

}

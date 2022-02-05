<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

class Lesson_model extends MY_Model
{

   public function __construct()
   {
      parent::__construct();
      $this->current_session = $this->setting_model->getCurrentSession();
   }

   /**
    * This funtion takes id as a parameter and will fetch the record.
    * If id is not provided, then it will fetch all the records form the table.
    * @param int $id
    * @return mixed
    */

   public function get_lessons($account_id = "", $folder = "today")
   {

      $this->db->select("*, lms_lesson.id as id,subjects.name as subject_name");
      $this->db->join("subjects", "subjects.id = lms_lesson.subject_id");
      $this->db->join("classes", "classes.id = lms_lesson.grade_id", "left");
      $this->db->join("staff", "staff.id = lms_lesson.account_id");
      $this->db->where("lms_lesson.account_id", $account_id);

      $sort_type = 'desc';

      if ($folder == "today") {
         $this->db->where('start_date <=', date('Y-m-d H:i:s'));
         $this->db->where('end_date >=', date('Y-m-d H:i:s'));
      } else if ($folder == "upcoming") {
         $this->db->where('start_date >', date('Y-m-d H:i:s'));
         $sort_type = 'asc';
      } else {
         $this->db->where('end_date <', date('Y-m-d H:i:s'));
      }
      $this->db->where('lms_lesson.deleted', 0);
      $this->db->order_by('lms_lesson.start_date', $sort_type);
      $query = $this->db->get("lms_lesson");

      $result = $query->result_array();
      return $result;
   }

   public function admin_lessons($account_id = "", $folder = "today")
   {
      date_default_timezone_set('Asia/Manila');

      $this->db->select("*, lms_lesson.id as id, subjects.name as subject_name,staff.google_meet as teacher_google_meet");
      $this->db->join("subjects", "subjects.id = lms_lesson.subject_id");
      $this->db->join("classes", "classes.id = lms_lesson.grade_id", "left");
      $this->db->join("staff", "staff.id = lms_lesson.account_id");
      // $this->db->where('published', 1);

      $sort_type = 'desc';

      if ($folder == "today") {
         $this->db->where('start_date <=', date('Y-m-d H:i:s'));
         $this->db->where('end_date >=', date('Y-m-d H:i:s'));
      } else if ($folder == "upcoming") {
         $this->db->where('start_date >', date('Y-m-d H:i:s'));
         $sort_type = 'asc';
      } else {
         $this->db->where('end_date <', date('Y-m-d H:i:s'));
         $this->db->limit(2500);
      }

      $this->db->where('lms_lesson.deleted', 0);
      $this->db->order_by('lms_lesson.start_date', $sort_type);
      // $this->db->order_by('lms_lesson.date_created', $sort_type);
      $query = $this->db->get("lms_lesson");

      // print_r($this->db->last_query());
      // die();

      $result = $query->result_array();

      return $result;
   }

   public function admin_lessons_search($account_id = "", $folder = "past", $search = "", $lesson_subject = "", $lesson_quarter = "")
   {
      date_default_timezone_set('Asia/Manila');

      $this->db->select("*, lms_lesson.id as id, subjects.name as subject_name,staff.google_meet as teacher_google_meet");
      $this->db->join("subjects", "subjects.id = lms_lesson.subject_id");
      $this->db->join("classes", "classes.id = lms_lesson.grade_id", "left");
      $this->db->join("staff", "staff.id = lms_lesson.account_id");
      // $this->db->where('published', 1);

      $sort_type = 'desc';

      if ($folder == "today") {
         $this->db->where('start_date <=', date('Y-m-d H:i:s'));
         $this->db->where('end_date >=', date('Y-m-d H:i:s'));
      } else if ($folder == "upcoming") {
         $this->db->where('start_date >', date('Y-m-d H:i:s'));
         $sort_type = 'asc';
      } else {
         $this->db->where('end_date <', date('Y-m-d H:i:s'));
         $this->db->limit(2500);
      }

      if ($search) {
         $this->db->like('lms_lesson.lesson_name', $search, 'both');
      }
      if ($lesson_subject) {

         $this->db->where("subjects.id", $lesson_subject);
      }
      if ($lesson_quarter) {
         $this->db->where("lms_lesson.term", $lesson_quarter);
      }
      // print_r($lesson_quarter);
      $this->db->where('lms_lesson.deleted', 0);
      // $this->db->order_by('lms_lesson.start_date', "desc");
      $this->db->order_by('lms_lesson.start_date', $sort_type);
      $query = $this->db->get("lms_lesson");
      $result = $query->result_array();

      return $result;
   }

   public function admin_deleted($account_id = "", $folder = "today")
   {
      date_default_timezone_set('Asia/Manila');
      $this->db->select("*, lms_lesson.id as id, subjects.name as subject_name");
      $this->db->join("subjects", "subjects.id = lms_lesson.subject_id");
      $this->db->join("classes", "classes.id = lms_lesson.grade_id", "left");
      $this->db->join("staff", "staff.id = lms_lesson.account_id");
      $this->db->where('lms_lesson.deleted', 1);
      $this->db->order_by('lms_lesson.start_date', "desc");
      $query = $this->db->get("lms_lesson");

      $result = $query->result_array();
      return $result;
   }

   public function deleted_lessons($account_id = "", $folder = "today")
   {
      date_default_timezone_set('Asia/Manila');
      $this->db->select("*, lms_lesson.id as id, subjects.name as subject_name");
      $this->db->join("subjects", "subjects.id = lms_lesson.subject_id");
      $this->db->join("classes", "classes.id = lms_lesson.grade_id", "left");
      $this->db->join("staff", "staff.id = " . $account_id);
      $this->db->where('lms_lesson.deleted', 1);
      $this->db->where("lms_lesson.account_id", $account_id);
      $this->db->order_by('lms_lesson.start_date', "desc");
      $query = $this->db->get("lms_lesson");

      // print_r($this->db->last_query());
      // die();
      $result = $query->result_array();
      return $result;
   }

   public function get_lessons_no_virtual($account_id = "")
   {

      $this->db->select("*, lms_lesson.id as id");
      $this->db->join("subjects", "subjects.id = lms_lesson.subject_id");
      $this->db->join("classes", "classes.id = lms_lesson.grade_id");
      $this->db->where("lms_lesson.account_id", $account_id);
      $this->db->where('lms_lesson.lesson_type !=', "virtual");
      $this->db->where('lms_lesson.deleted', 0);
      $this->db->order_by('lms_lesson.date_created', "desc");
      $query = $this->db->get("lms_lesson");

      $result = $query->result_array();
      return $result;
   }

   public function get_lessons_virtual_only($account_id = "")
   {

      $this->db->select("*, lms_lesson.id as id");
      $this->db->join("subjects", "subjects.id = lms_lesson.subject_id");
      $this->db->join("classes", "classes.id = lms_lesson.grade_id");
      $this->db->where("lms_lesson.account_id", $account_id);
      $this->db->where('lms_lesson.lesson_type', "virtual");
      $this->db->where('lms_lesson.deleted', 0);
      $this->db->order_by('lms_lesson.date_created', "desc");
      $query = $this->db->get("lms_lesson");

      $result = $query->result_array();
      return $result;
   }


   public function get_shared_lessons($account_id = "")
   {

      $this->db->select("*, lms_lesson.id as id");
      $this->db->join("subjects", "subjects.id = lms_lesson.subject_id");
      $this->db->join("classes", "classes.id = lms_lesson.grade_id", "left");
      $this->db->where("lms_lesson.shared", "1");
      $this->db->where('deleted', 0);
      $this->db->order_by('lms_lesson.date_created', 'asc');
      $this->db->limit(2500);
      $query = $this->db->get("lms_lesson");

      $result = $query->result_array();
      return $result;
   }

   public function lesson_schedule_admin($account_id = "")
   {

      $this->db->select("*, lms_lesson.id as id");
      $this->db->join("subjects", "subjects.id = lms_lesson.subject_id");
      $this->db->join("classes", "classes.id = lms_lesson.grade_id");
      $this->db->join("staff", "staff.id = lms_lesson.account_id");
      $this->db->where("lms_lesson.account_id", $account_id);
      $this->db->where('deleted', 0);
      $query = $this->db->get("lms_lesson");

      $result = $query->result_array();
      return $result;
   }

   public function student_lessons($account_id = "", $folder = "today")
   {
      date_default_timezone_set('Asia/Manila');
      $this->db->select("*,subjects.name as subject_name,lms_lesson.id as lesson_id");
      $this->db->where("FIND_IN_SET('" . $account_id . "', lms_lesson.assigned) !=", 0);
      $this->db->join("subjects", "subjects.id = lms_lesson.subject_id");
      $this->db->join("classes", "classes.id = lms_lesson.grade_id");
      $this->db->join("staff", "staff.id = lms_lesson.account_id");
      // $this->db->join("staff", "staff.id = '" . $account_id . "'");
      $this->db->where('published', 1);

      $sort_type = 'desc';

      if ($folder == "today") {
         $this->db->where('start_date <=', date('Y-m-d H:i:s'));
         $this->db->where('end_date >=', date('Y-m-d H:i:s'));
      } else if ($folder == "upcoming") {
         $this->db->where('start_date >', date('Y-m-d H:i:s'));
         $sort_type = 'asc';
      } else {
         $this->db->where('end_date <', date('Y-m-d H:i:s'));
      }
      // $this->db->where('start_date <=', date('Y-m-d H:i:s'));
      // $this->db->where('end_date >=', date('Y-m-d H:i:s'));
      $this->db->where('lms_lesson.deleted', 0);
      $this->db->order_by('lms_lesson.start_date', $sort_type);
      $query = $this->db->get("lms_lesson");

      // print_r($this->db->last_query());
      // die();
      $result = $query->result_array();
      return $result;
   }

   public function upcoming_lessons($account_id = "")
   {
      date_default_timezone_set('Asia/Manila');
      $this->db->select("*");
      $this->db->where("FIND_IN_SET('" . $account_id . "', lms_lesson.assigned) !=", 0);
      $this->db->where('start_date LIKE', date('Y-m-d'));
      $this->db->or_where('start_date >', date('Y-m-d H:i:s'));
      $this->db->where('end_date >=', date('Y-m-d H:i:s'));
      $this->db->where('lms_lesson.deleted', 0);
      $this->db->order_by('lms_lesson.start_date', "asc");

      $query = $this->db->get("lms_lesson");

      $result = $query->result_array();
      return $result;
   }

   public function get_students()
   {
      $current_session = $this->setting_model->getCurrentSession();
      $this->db->select("students.id,students.firstname,students.lastname,students.middlename,student_session.class_id,student_session.section_id,students.is_active");
      $this->db->join("students", "students.id = student_session.student_id");
      $this->db->where("session_id", $current_session);
      $this->db->where("students.is_active", "yes");
      $this->db->order_by("students.lastname", "asc");

      $query = $this->db->get("student_session");

      $result = $query->result_array();
      return $result;
   }

   public function get_students_per_level($gradelevel)
   {
      $current_session = $this->setting_model->getCurrentSession();
      $this->db->select("students.id,students.firstname,students.lastname,students.middlename,student_session.class_id,student_session.section_id,students.is_active");
      $this->db->join("students", "students.id = student_session.student_id");
      $this->db->where("session_id", $current_session);
      $this->db->where("students.is_active", "yes");
      $this->db->where("student_session.class_id", $gradelevel);
      $this->db->order_by("students.lastname", "asc");

      $query = $this->db->get("student_session");

      $result = $query->result_array();
      return $result;
   }

   public function get_class_sections()
   {
      $this->db->select("*");
      $this->db->join("classes", "classes.id = class_sections.class_id");
      $this->db->join("sections", "sections.id = class_sections.section_id");
      $query = $this->db->get("class_sections");
      $result = $query->result_array();
      return $result;
   }

   public function search_my_resources($account_id = "", $search = "")
   {
      $this->db->select("*");
      if ($search) {
         $this->db->like("name", $search);
      }
      $this->db->where("account_id", $account_id);
      $this->db->where("deleted", 0);
      $this->db->order_by("date_created", "desc");
      $query = $this->db->get("lms_my_resources");
      $result = $query->result_array();
      return $result;
   }

   public function search_cms_resources($account_id = "", $search = "")
   {
      $this->db->select("*");
      if ($search) {
         $this->db->like("name", $search);
      }
      $this->db->order_by("date_created", "desc");
      $query = $this->db->get("lms_cms_resources");
      $result = $query->result_array();
      return $result;
   }

   public function get_subject_timetable($gradelevel)
   {
      $subject_condition = "";
      $userdata = $this->customlib->getUserData();
      $role_id = $userdata["role_id"];

      if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
         if ($userdata["class_teacher"] == 'yes') {

            $my_classes = $this->teacher_model->my_classes($userdata['id']);

            if (!empty($my_classes)) {
               if (in_array($gradelevel, $my_classes)) {
                  $subject_condition = "";
               } else {

                  // $my_subjects = $this->teacher_model->get_subjectby_classid($class_id, $section_id, $userdata['id']);
                  // $subject_condition = " and subject_group_subjects.id in(" . $my_subjects['subject'] . ")";
                  $subject_condition = " and subject_timetable.staff_id = " . $userdata['id'];
               }
            } else {
               // $my_subjects = $this->teacher_model->get_subjectby_classid($class_id, $section_id, $userdata['id']);
               // $subject_condition = " and subject_group_subjects.id in(" . $my_subjects['subject'] . ")";
               $subject_condition = " and subject_timetable.staff_id = " . $userdata['id'];
            }
         }
      }

      $subject_condition = $subject_condition . " and staff.is_active=1";

      $sql = "SELECT `subject_group_subjects`.`subject_id`,subjects.name as `subject_name`,subjects.code,subjects.type,staff.name,staff.surname,staff.employee_id,`subject_timetable`.* 
              FROM `subject_timetable` 
              JOIN `subject_group_subjects` ON `subject_timetable`.`subject_group_subject_id` = `subject_group_subjects`.`id` 
              inner JOIN subjects on subject_group_subjects.subject_id = subjects.id 
              INNER JOIN staff on staff.id=subject_timetable.staff_id 
              WHERE `subject_timetable`.`class_id` = " . $gradelevel .
         " AND `subject_timetable`.`session_id` = " . $this->current_session . " " . $subject_condition;

      $query = $this->db->query($sql);
      return $query->result();
   }

   public function get_lessons_by_level_subject($account_id = "", $levelid, $subjectid)
   {
      $this->db->select("lms_lesson.id, concat('(', DATE_FORMAT(lms_lesson.start_date, '%b %d, %Y %h:%i %p'), ') ', lms_lesson.lesson_name) as lesson_name");

      if ($account_id != "")
         $this->db->where("lms_lesson.account_id", $account_id);

      $this->db->where("lms_lesson.subject_id", $subjectid);
      $this->db->where("lms_lesson.grade_id", $levelid);
      $this->db->where('lms_lesson.assigned <>', null);
      $this->db->where('lms_lesson.deleted', 0);
      $this->db->where('end_date <', date('Y-m-d H:i:s'));
      $this->db->order_by('lms_lesson.start_date', "desc");
      $this->db->order_by('lms_lesson.lesson_name', "asc");
      $query = $this->db->get("lms_lesson");

      // print_r($this->db->last_query());
      // die();

      $result = $query->result_array();
      return $result;
   }

   public function get_lesson_logs($lessonid, $levelid, $sectionid)
   {
      $this->db->select("concat(lastname, ', ', firstname) as name, DATE_FORMAT(lms_lesson_logs.date_created, '%b %d, %Y %h:%i %p') as jointime");
      // $this->db->select("lms_lesson_logs.date_created as join_time");
      $this->db->join("student_session", "student_session.student_id = students.id", "left");
      $this->db->join("lms_lesson_logs", "lms_lesson_logs.account_id = students.id and lms_lesson_logs.lesson_id = '$lessonid'", "left");
      $this->db->where("student_session.class_id", $levelid);
      $this->db->where("student_session.section_id", $sectionid);
      $this->db->where("student_session.session_id", $this->current_session);
      $this->db->group_by("students.lastname, students.firstname");
      $this->db->order_by("students.lastname", "asc");
      $this->db->order_by("students.firstname", "asc");
      $this->db->order_by("lms_lesson_logs.date_created", "asc");
      $return_data = $this->db->get("students")->result_array();

      // print_r($this->db->last_query());
      // die();

      return $return_data;
   }
}

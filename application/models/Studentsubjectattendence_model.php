<?php

if (!defined('BASEPATH')) {
   exit('No direct script access allowed');
}

class Studentsubjectattendence_model extends CI_Model
{

   public function __construct()
   {
      parent::__construct();
      $this->current_session = $this->setting_model->getCurrentSession();
      $this->current_date = $this->setting_model->getDateYmd();
      //-- Load database for writing
      $this->writedb = $this->load->database('write_db', TRUE);
   }

   public function add($insert_array, $update_array)
   {

      $this->writedb->trans_start();
      $this->writedb->trans_strict(false);
      if (!empty($insert_array)) {

         $this->writedb->insert_batch('student_subject_attendances', $insert_array);
      }
      if (!empty($update_array)) {
         $this->writedb->update_batch('student_subject_attendances', $update_array, 'id');
      }
      $this->writedb->trans_complete();

      if ($this->writedb->trans_status() === false) {

         $this->writedb->trans_rollback();
         return false;
      } else {

         $this->writedb->trans_commit();
         return true;
      }
   }

   // public function searchAttendenceClassSection($class_id, $section_id, $subject_timetable_id, $date)
   // {
   //    $sql = "SELECT  IFNULL(student_subject_attendances.id, '0') as student_subject_attendance_id,student_subject_attendances.subject_timetable_id,student_subject_attendances.attendence_type_id, 
   //            IFNULL(student_subject_attendances.date, 'xxx') as date,student_subject_attendances.remark,students.*,student_session.id as student_session_id 
   //            FROM students 
   //            INNER JOIN student_session on students.id=student_session.student_id and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id =" . $this->db->escape($section_id) . "  
   //            AND student_session.session_id=" . $this->db->escape($this->current_session) . 
   //            " LEFT JOIN student_subject_attendances on student_session.id=student_subject_attendances.student_session_id and student_subject_attendances.subject_timetable_id=" . $this->db->escape($subject_timetable_id) . 
   //            " and date=" . $this->db->escape($date) . 
   //            " where `students`.`is_active`='yes' ORDER BY students.lastname";

   //    $query = $this->db->query($sql);
   //    // print_r($this->db->last_query());
   //    // die();
   //    return $query->result_array();
   // }

   public function searchAttendenceClassSection($class_id, $section_id, $subject_timetable_id, $date, $lessonid)
   {
      $sql = "SELECT  IFNULL(student_subject_attendances.id, '0') AS student_subject_attendance_id,student_subject_attendances.subject_timetable_id,student_subject_attendances.attendence_type_id, 
              IFNULL(student_subject_attendances.date, 'xxx') AS date,student_subject_attendances.remark,students.*,student_session.id AS student_session_id, DATE_FORMAT(lms_lesson_logs.date_created, '%b %d, %Y %h:%i %p') AS time_entered, DATE_FORMAT(lms_lesson.start_date, '%b %d, %Y %h:%i %p') AS start_date 
              FROM students 
              INNER JOIN student_session ON students.id=student_session.student_id AND student_session.class_id=" . $this->db->escape($class_id) . " AND student_session.section_id =" . $this->db->escape($section_id) . " AND student_session.session_id=" . $this->db->escape($this->current_session) .
         " LEFT JOIN student_subject_attendances ON student_session.id=student_subject_attendances.student_session_id AND student_subject_attendances.subject_timetable_id=" . $this->db->escape($subject_timetable_id) .
         " AND date=" . $this->db->escape($date) . " AND student_subject_attendances.lesson_id = '$lessonid' " .
         "LEFT JOIN `lms_lesson_logs` ON `lms_lesson_logs`.`account_id` = `students`.`id` AND `lms_lesson_logs`.`lesson_id` = '$lessonid' AND `lms_lesson_logs`.`session_id` = " . $this->db->escape($this->current_session) .
         "LEFT JOIN lms_lesson ON lms_lesson.id = lms_lesson_logs.lesson_id " .
         " WHERE `students`.`is_active`='yes' GROUP BY `students`.`lastname`, `students`.`firstname` ORDER BY students.lastname";

      $query = $this->db->query($sql);
      // print_r($this->db->last_query());
      // die();
      return $query->result_array();
   }

   public function getStudentMontlyAttendence($class_id, $section_id, $from_date, $to_date, $year, $month_number, $student_id)
   {

      $student_array = array();

      $student_array['students_attendances'] = array();
      for ($i = 1; $i <= $to_date; $i++) {

         $date = $year . "-" . $month_number . "-" . sprintf("%02d", $i);

         $day = date('l', strtotime($date));

         $students_time_table = $this->searchByStudentAttendanceByDate($class_id, $section_id,  $day, $date, $student_id);
         $a = array();
         $a['date'] = $this->customlib->dateformat($date);
         $a['day'] = $day;
         $a['subjects'] = array();
         $a['attendances'] = array();

         if (!empty($students_time_table)) {
            $students_time_table = json_decode($students_time_table);

            $a['subjects'] = ($students_time_table->subjects);
            foreach ($students_time_table->student_record as $students_time_table_key => $students_time_table_value) {
               $a['attendances'] = ($students_time_table->student_record[$students_time_table_key]);
            }
         }
         $student_array['students_attendances'][$i] = $a;
      }
      return $student_array;
   }




   public function searchByStudentAttendanceByDate($class_id, $section_id, $day, $date, $student_id)
   {

      $sql = "SELECT subject_timetable.*,subjects.id as `subject_id`,subjects.name,subjects.code,subjects.type FROM `subject_timetable` INNER JOIN subject_group_subjects on subject_group_subjects.id=subject_timetable.subject_group_subject_id INNER JOIN subjects on subjects.id=subject_group_subjects.subject_id WHERE subject_timetable.class_id=" . $this->db->escape($class_id) . " AND subject_timetable.section_id=" . $this->db->escape($section_id) . " and subject_timetable.session_id=" . $this->db->escape($this->current_session) . " and subject_timetable.day=" . $this->db->escape($day);

      $query = $this->db->query($sql);
      // echo $this->db->last_query();die;
      $subjects = $query->result();

      if (!empty($subjects)) {
         $count = 1;
         $append_sql = "";
         $append_param = "";
         foreach ($subjects as $subject_key => $subject_value) {
            $append_param .= ",student_subject_attendances_" . $count . ".attendence_type_id as attendence_type_id_" . $count;
            $append_sql .= " LEFT JOIN student_subject_attendances as student_subject_attendances_" . $count . " on  student_subject_attendances_" . $count . ".student_session_id=student_session.id and student_subject_attendances_" . $count . ".subject_timetable_id=" . $this->db->escape($subject_value->id) . " and student_subject_attendances_" . $count . ".date=" . $this->db->escape($date);
            $count++;
         }
         $sql_student_record = "SELECT students.id,students.firstname" . $append_param . " FROM `students` INNER JOIN student_session on students.id=student_session.student_id and student_session.class_id=" . $this->db->escape($class_id) . " AND student_session.section_id=" . $this->db->escape($section_id) . " AND student_session.session_id=" . $this->db->escape($this->current_session) . $append_sql . " WHERE students.id=" . $student_id;
         $query = $this->db->query($sql_student_record);
         $student_record = $query->result();
         return json_encode(array('subjects' => $subjects, 'student_record' => $student_record));
      }

      return false;
   }



   public function studentAttendanceByDate($class_id, $section_id, $day, $date, $student_session_id)
   {
      $sql = "SELECT subject_timetable.*,subject_group_subjects.subject_group_id,subjects.id as `subject_id`,subjects.name,subjects.code,subjects.type,student_subject_attendances.student_session_id,student_subject_attendances.attendence_type_id,student_subject_attendances.date,student_subject_attendances.remark,student_subject_attendances.id as `student_subject_attendance_id`,student_subject_attendances.date  FROM `subject_timetable` INNER JOIN subject_group_subjects on subject_group_subjects.id = subject_timetable.subject_group_subject_id and subject_group_subjects.session_id=" . $this->current_session . " INNER JOIN subjects on subjects.id=subject_group_subjects.subject_id LEFT JOIN student_subject_attendances on student_subject_attendances.subject_timetable_id=subject_timetable.id and student_subject_attendances.student_session_id=" . $this->db->escape($student_session_id) . " WHERE subject_timetable.class_id=" . $this->db->escape($class_id) . " AND subject_timetable.section_id=" . $this->db->escape($section_id) . " and subject_timetable.day=" . $this->db->escape($day) . "and student_subject_attendances.date=" . $this->db->escape($date);
      $query = $this->db->query($sql);
      $attendance = $query->result();
      return $attendance;
   }



   public function getStudentsMontlyAttendence($class_id, $section_id, $from_date, $to_date, $year, $month_number)
   {

      $student_array = array();
      $student_array['class_students'] = $this->student_model->searchByClassSectionWithSession($class_id, $section_id);
      //echo "<pre>"; 
      // print_r($student_array['class_students']);
      //  echo "<pre>"; die;
      $student_array['students_attendances'] = array();
      for ($i = 1; $i <= $to_date; $i++) {

         $date = $year . "-" . $month_number . "-" . sprintf("%02d", $i);

         $day = date('l', strtotime($date));

         $students_time_table = $this->searchByStudentsAttendanceByDate($class_id, $section_id, $day, $date);
         // echo "<pre>"; 
         //print_r($students_time_table);
         // echo "<pre>"; die;
         $a = array();
         $a['date'] = $date;
         $a['day'] = $day;
         $a['subjects'] = array();
         $a['students'] = array();

         if (!empty($students_time_table)) {
            $students_time_table = json_decode($students_time_table);

            $a['subjects'] = ($students_time_table->subjects);
            foreach ($students_time_table->student_record as $students_time_table_key => $students_time_table_value) {
               $a['students'][$students_time_table_value->id] = ($students_time_table->student_record[$students_time_table_key]);
            }
         }
         $student_array['students_attendances'][$i] = $a;
      }

      return $student_array;
   }

   public function searchByStudentsAttendanceByDate($class_id, $section_id, $day, $date)
   {

      $sql = "SELECT subject_timetable.*,subjects.id as `subject_id`,subjects.name,subjects.code,subjects.type 
              FROM `subject_timetable` 
              INNER JOIN subject_group_subjects on subject_group_subjects.id=subject_timetable.subject_group_subject_id 
              INNER JOIN subjects on subjects.id=subject_group_subjects.subject_id 
              WHERE subject_timetable.class_id=" . $this->db->escape($class_id) . " 
              AND subject_timetable.section_id=" . $this->db->escape($section_id) . " 
              AND subject_timetable.session_id=" . $this->db->escape($this->current_session) . " 
              AND subject_timetable.day=" . $this->db->escape($day);

      $query = $this->db->query($sql);
      //echo $this->db->last_query();die;
      $subjects = $query->result();

      if (!empty($subjects)) {
         $count = 1;
         $append_sql = "";
         $append_param = "";

         foreach ($subjects as $subject_key => $subject_value) {
            $append_param .= ",student_subject_attendances_" . $count . ".attendence_type_id as attendence_type_id_" . $count;
            $append_sql .= " LEFT JOIN student_subject_attendances as student_subject_attendances_" . $count . " on  student_subject_attendances_" . $count . ".student_session_id=student_session.id and student_subject_attendances_" . $count . ".subject_timetable_id=" . $this->db->escape($subject_value->id) . " and student_subject_attendances_" . $count . ".date=" . $this->db->escape($date);
            $count++;
         }

         $sql_student_record = "SELECT students.id,students.firstname,students.lastname,students.admission_no " . $append_param .
            " FROM `students` INNER JOIN student_session on students.id=student_session.student_id and student_session.class_id=" . $this->db->escape($class_id) .
            " AND student_session.section_id=" . $this->db->escape($section_id) .
            " AND student_session.session_id=" . $this->db->escape($this->current_session) . $append_sql .
            " ORDER BY students.lastname";
         $query = $this->db->query($sql_student_record);
         $student_record = $query->result();

         // print_r(json_encode(array('subjects' => $subjects, 'student_record' => $student_record)));
         // die();
         return json_encode(array('subjects' => $subjects, 'student_record' => $student_record));
      }

      return false;
   }

   public function attendanceYearCount()
   {

      $query = $this->db->select("distinct year(date) as year")->get("student_subject_attendances");

      return $query->result_array();
   }


   function is_biometricAttendence()
   {


      $this->db->select(
         'sch_settings.id,sch_settings.biometric,sch_settings.attendence_type,sch_settings.is_rtl,sch_settings.timezone,
          sch_settings.name,sch_settings.email,sch_settings.biometric,sch_settings.biometric_device,sch_settings.phone,languages.language,
          sch_settings.address,sch_settings.dise_code,sch_settings.date_format,sch_settings.currency,sch_settings.currency_symbol,sch_settings.start_month,sch_settings.session_id,sch_settings.image,sch_settings.theme,sessions.session'
      );

      $this->db->from('sch_settings');
      $this->db->join('sessions', 'sessions.id = sch_settings.session_id');
      $this->db->join('languages', 'languages.id = sch_settings.lang_id');
      $this->db->order_by('sch_settings.id');
      $query  = $this->db->get();
      $result = $query->row();

      if ($result->biometric) {
         return true;
      }

      return false;
   }
}

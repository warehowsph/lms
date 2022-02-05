<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Grading_studentconduct_ssapamp_model extends CI_Model
{

   public function getList()
   {
      $query = $this->db->query("select id,alpha,checklistname from grading_checklist_ssapamp order by id");
      return $query->result();
   }

   public function getGrades($studentid, $level, $section, $session, $quarter)
   {
      //    SELECT studentgrade.studentid,studentgrade.checklistid,studentgrade.period1,studentgrade.period2,studentgrade.finalgrade,checklistdetails.detail,checklist.checklistname,checklist.id FROM studentgrade sg 
      // inner join checklistdetails cd on sg.checklistid=cd.id
      // inner join checklist c on c.id=cd.checklistid WHERE sg.studentid=1;
      $this->db->select("grading_studentconduct_ssapamp.studentid,grading_studentconduct_ssapamp.id as ssid,grading_studentconduct_ssapamp.conductid,grading_studentconduct_ssapamp.grade,grading_studentconduct_ssapamp.lg,grading_conduct_ssapamp.description,grading_conduct_ssapamp.id");
      $this->db->where('grading_studentconduct_ssapamp.studentid', $studentid);
      $this->db->where('grading_studentconduct_ssapamp.levelid', $level);
      $this->db->where('grading_studentconduct_ssapamp.sectionid', $section);
      $this->db->where('grading_studentconduct_ssapamp.schoolyear', $session);
      $this->db->where('grading_studentconduct_ssapamp.semester', $quarter);
      $this->db->from('grading_studentconduct_ssapamp');
      $this->db->join('grading_conduct_ssapamp', 'grading_studentconduct_ssapamp.conductid = grading_conduct_ssapamp.id');
      // var_dump($this->db->last_query());
      $query = $this->db->get();

      if ($query->num_rows() != 0) {
         return $query->result_array();
      } else {
         return false;
      }
   }

   public function getStudentGradeList($studentid, $level, $section, $session, $quarter)
   {

      $this->db->select("conductid,grade,lg");
      $this->db->where('studentid', $studentid);
      $this->db->where('levelid', $level);
      $this->db->where('sectionid', $section);
      $this->db->where('schoolyear', $session);
      $this->db->where('semester', $quarter);
      $this->db->from('grading_studentconduct_ssapamp');

      $query = $this->db->get();
      //   var_dump($this->db->last_query());

      if ($query->num_rows() != 0) {
         return $query->result_array();
      } else {
         return false;
      }
   }

   public function get_student_conduct_list($studentid, $level, $section, $session, $semester)
   {
      $sql = "select gss.studentid,gss.conductid,gcs.description,gss.grade,gss.semester,
       (select conduct_grade from grading_conduct_legend_ssapamp where ROUND(gss.grade) between mingrade and maxgrade) as LG
       from grading_studentconduct_ssapamp gss 
       inner join grading_conduct_ssapamp gcs on gss.conductid = gcs.id
       where gss.studentid =$studentid and gss.levelid=$level and gss.sectionid=$section and gss.schoolyear=$session and gss.semester=$semester";

      $query = $this->db->query($sql);
      //    var_dump($this->db->last_query());
      // print_r($this->db->error());die();
      return $query->result();
   }


   public function get_student_conduct_average_list($studentid, $level, $section, $session, $semester)
   {
      $sql = "select COALESCE (studentid,0) as studentid,average,semester,
        (select conduct_grade from grading_conduct_legend_ssapamp where cast(average as double) between mingrade and (maxgrade + 0.99)) as LG
        from (
        select gss.studentid,sum(gss.grade)/(select count(1) from grading_conduct_ssapamp) as average,gss.semester
        from grading_studentconduct_ssapamp gss 
        inner join grading_conduct_ssapamp gcs on gss.conductid = gcs.id
        where gss.studentid =$studentid and gss.levelid=$level and gss.sectionid=$section and gss.schoolyear=$session and gss.semester=$semester
         ) vv";
      $query = $this->db->query($sql);
      return $query->result();
   }



   public function updatestudentgrade($studentid, $sgid, $conductid, $dataarray)
   {
      $this->db->where('id', $sgid);
      $this->db->where('conductid', $conductid);
      $this->db->update('grading_studentconduct_ssapamp', $dataarray);
      // var_dump($this->db->last_query());
   }


   public function batchinsert($dataarray)
   {
      $this->db->insert_batch('grading_studentconduct_ssapamp', $dataarray);
      if ($this->db->affected_rows() > 0) {
         $inserted_id = $this->db->insert_id();
         return 1;
      } else {
         return 0; // false;
      }
   }
}

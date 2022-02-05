<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Grading_studentgrade_ssapamp_model extends CI_Model
{

   public function getList()
   {
      // $query=$this->db->query("select id,alpha,checklistname from checklist order by id");
      // return $query->result();
      $query = $this->db->query("select id,alpha,checklistname from grading_checklist_ssapamp order by id");
      return $query->result();
   }

   public function getGrades($studentid, $levelid, $sectionid, $schoolyear, $checklistid)
   {
      //    SELECT studentgrade.studentid,studentgrade.checklistid,studentgrade.period1,studentgrade.period2,studentgrade.finalgrade,grading_checklistdetails_ssapamp.detail,grading_checklist_ssapamp.checklistname,grading_checklist_ssapamp.id FROM studentgrade sg 
      // inner join checklistdetails cd on sg.checklistid=cd.id
      // inner join checklist c on c.id=cd.checklistid WHERE sg.studentid=1;
      $this->db->select("grading_studentgrade_ssapamp.studentid,grading_studentgrade_ssapamp.id as ssid,grading_studentgrade_ssapamp.checkdetaillistid,grading_studentgrade_ssapamp.period1,grading_studentgrade_ssapamp.period2,grading_studentgrade_ssapamp.finalgrade,grading_checklistdetails_ssapamp.detail,grading_checklist_ssapamp.checklistname,grading_checklist_ssapamp.id,grading_checklistdetails_ssapamp.groupclass");
      $this->db->where('grading_studentgrade_ssapamp.studentid', $studentid);
      $this->db->where('grading_studentgrade_ssapamp.levelid', $levelid);
      $this->db->where('grading_studentgrade_ssapamp.sectionid', $sectionid);
      $this->db->where('grading_studentgrade_ssapamp.schoolyear', $schoolyear);
      $this->db->where('grading_checklist_ssapamp.id', $checklistid);
      $this->db->from('grading_studentgrade_ssapamp');
      $this->db->join('grading_checklistdetails_ssapamp', 'grading_studentgrade_ssapamp.checkdetaillistid = grading_checklistdetails_ssapamp.id');
      $this->db->join('grading_checklist_ssapamp', 'grading_checklistdetails_ssapamp.checklistid = grading_checklist_ssapamp.id');

      $query = $this->db->get();
      // var_dump($this->db->last_query());

      if ($query->num_rows() != 0) {
         return $query->result_array();
      } else {
         return false;
      }
   }

   public function getStudentGradeList($studentid, $level, $section, $session)
   {

      $this->db->select("checkdetaillistid,period1,period2,finalgrade");
      $this->db->where('studentid', $studentid);
      $this->db->where('levelid', $level);
      $this->db->where('sectionid', $section);
      $this->db->where('schoolyear', $session);
      // $this->db->where('semester', $quarter);
      $this->db->from('grading_studentgrade_ssapamp');

      $query = $this->db->get();
      // var_dump($this->db->last_query());
      return $query->result();
      // if($query->num_rows() != 0)
      // {
      // return $query->result_array();
      // }
      // else
      // {
      //     return false;
      // }
   }

   public function getStudent_Grades($studentid, $level, $section, $session)
   {

      $array = array('studentid' => $studentid, 'levelid' => $level, 'sectionid' => $section, 'schoolyear' => $session);

      $this->db->select("checkdetaillistid,period1,period2,finalgrade");
      $this->db->where($array);
      $this->db->from('grading_studentgrade_ssapamp');
      $query = $this->db->get();
      // var_dump($this->db->last_query());
      $data = array();
      if ($query !== FALSE && $query->num_rows() > 0) {
         $data = $query->result_array();
      }
      return $data;
   }

   public function updatestudentgrade($studentid, $clid, $cdid, $dataarray)
   {
      $this->db->where('id', $clid);
      $this->db->where('checkdetaillistid', $cdid);
      $this->db->update('grading_studentgrade_ssapamp', $dataarray);
      // var_dump($this->db->last_query());
   }


   public function batchinsert($dataarray)
   {
      $this->db->insert_batch('grading_studentgrade_ssapamp', $dataarray);
      if ($this->db->affected_rows() > 0) {
         $inserted_id = $this->db->insert_id();
         return 1;
      } else {
         return 0; // false;
      }
   }

   public function getCLE($studentid, $level, $section, $session, $semester)
   {
      $checklistid = 1;
      $sql = "select 
        (sum(gss.period$semester) /4) as grade
        from grading_studentgrade_ssapamp gss 
        left join grading_checklistdetails_ssapamp gcs 
        on gss.checkdetaillistid = gcs.id 
        where gcs.checklistid = $checklistid 
        and gss.studentid = $studentid
        and gss.levelid = $level
        and gss.sectionid = $section
        and gss.schoolyear = $session
        group by gss.studentid ";

      $query = $this->db->query($sql);
      return $query->result();
   }

   public function getReading($studentid, $level, $section, $session, $semester, $subitem)
   {
      $checklistid = 2;
      if ($subitem == "1" && $subitem == "2") {
         $sql = "select 
            (sum(gss.period$semester) /6) as grade
            from grading_studentgrade_ssapamp gss 
            inner join grading_checklistdetails_ssapamp gcs 
            on gss.checkdetaillistid = gcs.id 
            where gcs.checklistid = $checklistid
            and gcs.gclid= $subitem
            and gss.studentid = $studentid
            and gss.levelid = $level
            and gss.sectionid = $section
            and gss.schoolyear = $session";
      } else {
         $sql = "select (sum(period$semester)/7) as grade from 
            (
            select 
            sum(gss.period$semester) as period$semester
            from grading_studentgrade_ssapamp gss 
            inner join grading_checklistdetails_ssapamp gcs 
            on gss.checkdetaillistid = gcs.id 
            where gcs.checklistid = $checklistid
            and gcs.gclid= $subitem
            and gss.studentid = $studentid
            and gss.levelid = $level
            and gss.sectionid = $section
            and gss.schoolyear = $session
            union 
            select 
            (sum(gss.period$semester) / 2) as period$semester 
            from grading_studentgrade_ssapamp gss 
            inner join grading_checklistdetails_ssapamp gcs 
            on gss.checkdetaillistid = gcs.id 
            where gcs.checklistid = $checklistid
            and gcs.groupclass ='reading2g'
            and gss.studentid = $studentid
            and gss.levelid = $level
            and gss.sectionid = $section
            and gss.schoolyear = $session
            ) vv";
      }

      $query = $this->db->query($sql);
      return $query->result();
   }

   public function getMath($studentid, $level, $section, $session, $semester, $subitem)
   {
      $checklistid = 3;
      $sql = "select (sum(period$semester)/8) as grade from
        (
        select 
        sum(gss.period$semester) as period$semester 
        from grading_studentgrade_ssapamp gss 
        inner join grading_checklistdetails_ssapamp gcs 
        on gss.checkdetaillistid = gcs.id 
        where gcs.checklistid = $checklistid
        and gcs.gclid=4
        and gss.studentid = $studentid
        and gss.levelid = $level
        and gss.sectionid = $section
        and gss.schoolyear = $session
        union
        select 
        (sum(gss.period$semester) /2) as period$semester 
        from grading_studentgrade_ssapamp gss 
        inner join grading_checklistdetails_ssapamp gcs 
        on gss.checkdetaillistid = gcs.id 
        where gcs.checklistid = $checklistid
        and gcs.gradchecklistid='4e'
        and gss.studentid = $studentid
        and gss.levelid = $level
        and gss.sectionid = $section
        and gss.schoolyear = $session
        ) vv";

      $query = $this->db->query($sql);
      return $query->result();
   }

   public function getMape($studentid, $level, $section, $session, $semester, $subitem)
   {
      $checklistid = 4;
      //  Music
      //  Arts
      if ($subitem == "5" && $subitem == "6") {
         $sql = "select 
        sum(gss.period1) /3
        from grading_studentgrade_ssapamp gss 
        inner join grading_checklistdetails_ssapamp gcs 
        on gss.checkdetaillistid = gcs.id 
        where gcs.checklistid = $checklistid
        and gcs.gclid=$subitem
        and gss.studentid = $studentid
        and gss.levelid = $level
        and gss.sectionid = $section
        and gss.schoolyear = $session";
      } else {
         // Psychomotor
         $sql = "select (sum(period$semester) /2)  as period$semester  from 
        (
        select 
        (sum(gss.period$semester) /6) as period$semester 
        from grading_studentgrade_ssapamp gss 
        inner join grading_checklistdetails_ssapamp gcs 
        on gss.checkdetaillistid = gcs.id 
        where gcs.checklistid = $checklistid
        and gcs.gradchecklistid='7a'
        and gss.studentid = $studentid
        and gss.levelid = $level
        and gss.sectionid = $section
        and gss.schoolyear = $session        
        union all 
        select 
        (sum(gss.period$semester) /8) as period$semester
        from grading_studentgrade_ssapamp gss 
        inner join grading_checklistdetails_ssapamp gcs 
        on gss.checkdetaillistid = gcs.id 
        where gcs.checklistid = $checklistid
        and gcs.gradchecklistid='7b'
        and gss.studentid = $studentid
        and gss.levelid = $level
        and gss.sectionid = $section
        and gss.schoolyear = $session
        ) vv";
      }
   }

   function getWritting($studentid, $level, $section, $session, $semester, $subitem)
   {
      $checklistid = 5;

      $sql = "select 
        gss.period$semester as grade
        from grading_studentgrade_ssapamp gss 
        left join grading_checklistdetails_ssapamp gcs 
        on gss.checkdetaillistid = gcs.id 
        where gcs.checklistid = $checklistid
        and gss.studentid = $studentid
        and gss.levelid = $level
        and gss.sectionid = $section
        and gss.schoolyear = $session
        group by gss.studentid ";

      $query = $this->db->query($sql);
      return $query->result();
   }

   public function get_student_checklist($_year, $_grade_level_id, $_section_id, $_student_id)
   {
      // _year INT, _quarter INT, _grade_level INT, _section INT, _student_id INT, _semester INT, _subject_id INT) RETURNS varchar(5)
      //   $sql = "select checklistname, 
      //   fn_checklist_grade_ssap($_year, 1,$_grade_level_id, $_section_id,  $_student_id, 1, id) as Q1,
      //   fn_checklist_grade_ssap($_year, 2,$_grade_level_id, $_section_id,  $_student_id, 1, id) as Q2      
      //   from grading_checklist_ssapamp";

      // $sql = "select checklistname,Q1,Q2,((Q1 + Q2) /2) as average,
      // (select letter_grade from grading_checklist_legend_ssapamp where ((Q1 + Q2) /2) between mingrade and maxgrade) as final_grade 
      // from 
      // (
      // select checklistname, 
      // fn_checklist_grade_ssap($_year, 1,$_grade_level_id, $_section_id,  $_student_id, 1, id) as Q1,
      // fn_checklist_grade_ssap($_year, 2,$_grade_level_id, $_section_id,  $_student_id, 1, id) as Q2  
      // from grading_checklist_ssapamp
      // ) vv";

      // $sql = "select checklistname,ROUND(Q1) as Q1,ROUND(Q2) as Q2,ROUND((Q1 + Q2) /2) as average,
      // (select letter_grade from grading_checklist_legend_ssapamp where ROUND((Q1 + Q2) /2) between mingrade and maxgrade) as final_grade 
      // from 
      // (
      // select checklistname, 
      // fn_checklist_grade_ssap($_year, 1,$_grade_level_id, $_section_id,  $_student_id, 1, id) as Q1,
      // fn_checklist_grade_ssap($_year, 2,$_grade_level_id, $_section_id,  $_student_id, 1, id) as Q2  
      // from grading_checklist_ssapamp
      // ) vv";

      $sql = "select checklistname,
      ROUND(Q1) as Q1,
      ROUND(Q2) as Q2,
      (select letter_grade from grading_checklist_legend_ssapamp where ROUND(Q1) between mingrade and maxgrade) as LG1,
      (select letter_grade from grading_checklist_legend_ssapamp where ROUND(Q2) between mingrade and maxgrade) as LG2,
      ROUND((Q1 + Q2) /2) as average,
            (select letter_grade from grading_checklist_legend_ssapamp where ROUND((Q1 + Q2) /2) between mingrade and maxgrade) as final_grade 
            from 
            (
            select checklistname, 
            fn_checklist_grade_ssap($_year, 1,$_grade_level_id, $_section_id,  $_student_id, 1, id) as Q1,
            fn_checklist_grade_ssap($_year, 2,$_grade_level_id, $_section_id,  $_student_id, 1, id) as Q2  
            from grading_checklist_ssapamp
            ) vv  ";

      $query = $this->db->query($sql);
      // print_r($this->db->last_query());
      // die();
      return $query->result();
   }
}

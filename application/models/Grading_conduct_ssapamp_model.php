<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Grading_conduct_ssapamp_model extends CI_Model
{

   public function getList()
   {
      $query = $this->db->query("select id,alpha,description from grading_conduct_ssapamp order by id");
      return $query->result();
   }

   public function getLegend()
   {
      $query = $this->db->query("select id,conduct_grade,grade_description,description,mingrade,maxgrade from grading_conduct_legend_ssapamp order by id");
      return $query->result();
   }
}

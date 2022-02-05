<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grading_checklist_ssapamp_model extends CI_Model {

    public function getList()
    {
        $sql ="select gcs.id,gcs.alpha,gcs.checklistname,gcs.groupclass,
        v.totallevels
        from 
        grading_checklist_ssapamp gcs 
        inner join 
        (select checklistid ,count(1) as totallevels from grading_checklistdetails_ssapamp group by checklistid ) v
        on v.checklistid=id
        order by id";
        // $query=$this->db->query("select id,alpha,checklistname,groupclass from grading_checklist_ssapamp order by id");
        $query=$this->db->query($sql);
        return $query->result();
    }

    public function getChecklistDetail()
    {
        $response = array();
        // $query=$this->db->query("SELECT id,graded FROM checklistdetails where graded=1");
        $query = $this->db->select('id,graded')->from('grading_checklistdetails_ssapamp')->where('graded', 1)->order_by('sequenceid')->get(); 
        return $query->result();
    }

    public function getDetailList()
    {
        $response = array();
        $query=$this->db->query("SELECT d.id,c.checklistname,d.checklistid,d.detail,d.mainsub,d.graded,d.itemseq FROM grading_checklistdetails_ssapamp d inner join grading_checklist_ssapamp c on c.id=d.checklistid");
        // $query = $this->db->select('id,checklistid,detail')->from('checklistdetails')->where('checklistid', $checklistid)->get(); 
        return $query->result();
    }

    public function getGroupDetailList($checklistid)
    {
        $response = array();
        // 
        $query = $this->db->select('id,alpha,checklistname')->from('grading_checklistdetails_ssapamp')->where('checklistid', $checklistid)->order_by('sequenceid')->get(); 
        return $query->result();
    }

    public function fetchData($checklistid) {
        $this->db->select("grading_checklistdetails_ssapamp.id,grading_checklist_ssapamp.checklistname,grading_checklistdetails_ssapamp.checklistid,grading_checklistdetails_ssapamp.detail,grading_checklistdetails_ssapamp.mainsub,grading_checklistdetails_ssapamp.graded,grading_checklistdetails_ssapamp.itemseq,grading_checklistdetails_ssapamp.groupclass");
        $this->db->where('grading_checklistdetails_ssapamp.checklistid', $checklistid);
        $this->db->from('grading_checklistdetails_ssapamp');
        $this->db->join('grading_checklist_ssapamp', 'grading_checklist_ssapamp.id = grading_checklistdetails_ssapamp.checklistid');
        
        
       $query = $this->db->get();
    //    var_dump($this->db->last_query());
        return $query->result();
        // if($query->num_rows() != 0)
        // {
        //     return $query->result_array();
        // }
        // else
        // {
        //     return false;
        // }
    }

    public function getLegend()
    {
        $query=$this->db->query("select id,letter_grade,grade_description,description,mingrade,maxgrade from grading_checklist_legend_ssapamp order by id");        
        return $query->result_array();
    }

}
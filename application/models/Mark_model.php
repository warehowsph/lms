<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mark_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        //-- Load database for writing
        $this->writedb = $this->load->database('write_db', TRUE);
    }

    public function get($id = null) {
        $this->db->select()->from('exam_results');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function remove($id) {
        $this->writedb->where('id', $id);
        $this->writedb->delete('exam_results');
    }

    public function add($data) {
        if (isset($data['id'])) {
            $this->writedb->where('id', $data['id']);
            $this->writedb->update('exam_results', $data);
        } else {
            $this->writedb->insert('exam_results', $data);
            return $this->writedb->insert_id();
        }
    }

}

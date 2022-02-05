<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Timetable_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        //-- Load database for writing
        $this->writedb = $this->load->database('write_db', TRUE);
    }

    public function remove($id) {
        $this->writedb->where('id', $id);
        $this->writedb->delete('timetables');
    }

    public function add($data) {
        if (($data['id']) != 0) {
            $this->writedb->where('id', $data['id']);
            $this->writedb->update('timetables', $data); 
        } else {
            $this->writedb->insert('timetables', $data); 
            return $this->writedb->insert_id(); 
        }
    }

    public function get($data) {
        $query = $this->db->get_where('timetables', $data);
        return $query->result_array();
    }

}

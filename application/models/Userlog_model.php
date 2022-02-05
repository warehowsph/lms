<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Userlog_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        //-- Load database for writing
        $this->writedb = $this->load->database('write_db', TRUE);
    }

    public function get($id = null) {
        $this->db->select()->from('userlog')->limit(2000, 0);
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('login_datetime', 'desc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array(); 
        } else {
            return $query->result_array(); 
        }
    }

    public function getByRole($role) {
        $this->db->select()->from('userlog')->limit(2000, 0);
        $this->db->where('role', $role);
        $this->db->order_by('login_datetime', 'desc');
        $query = $this->db->get();
        return $query->result_array();   
    }

    public function getByRoleStaff() {
        $this->db->select()->from('userlog')->limit(2000, 0);
        $this->db->where('role!=', 'Parent');
        $this->db->where('role!=', 'Student');
        $this->db->order_by('login_datetime', 'desc');
        $query = $this->db->get();
        return $query->result_array();    
    }


    public function add($data) {
        if (isset($data['id'])) {
            $this->writedb->where('id', $data['id']);
            $this->writedb->update('userlog', $data);
        } else {
            $this->writedb->insert('userlog', $data);
        }
    }

}

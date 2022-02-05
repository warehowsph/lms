<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Feereminder_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        //-- Load database for writing
        $this->writedb = $this->load->database('write_db', TRUE);
    }

    public function get($id = null,$active=null)
    {
        $this->db->select()->from('fees_reminder');
        if($active != null){
            $this->db->where('fees_reminder.is_active', $active);
        }
        if ($id != null) {
            $this->db->where('fees_reminder.id', $id);
        } else {
            $this->db->order_by('fees_reminder.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    public function add($data)
    {
        $this->db->select()->from('fees_reminder');
        $this->db->where('fees_reminder.type', $data['type']);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            $result = $q->row();

            $this->writedb->where('id', $result->id);
            $this->writedb->update('fees_reminder', $data);
        } else {
            $this->writedb->insert('fees_reminder', $data);
            return $this->writedb->insert_id();
        }
    }

      public function update($data)
    {
        

            $this->writedb->where('id', $data['id']);
            $this->writedb->update('fees_reminder', $data);
      
    }

    public function updatebatch($update_array)
    {

        $this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well

        if (isset($update_array) && !empty($update_array)) {

            $this->writedb->update_batch('fees_reminder', $update_array, 'id');
        }

        $this->writedb->trans_complete(); # Completing transaction

        if ($this->writedb->trans_status() === false) {
            $this->writedb->trans_rollback();
            return false;
        } else {
            $this->writedb->trans_commit();
            return true;
        }

    }

}

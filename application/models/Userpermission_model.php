<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Userpermission_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        //-- Load database for writing
        $this->writedb = $this->load->database('write_db', TRUE);
    }

    public function getUserPermission($user_id = null) {
        $where = "";
        $query = "SELECT permissions.*,IF(user_permissions.id > 0,1,0) as `user_permissions_id` FROM permissions left JOIN user_permissions on user_permissions.permission_id=permissions.id and user_permissions.staff_id=$user_id";

        $query = $this->db->query($query);

        return $query->result();
    }

    public function getInsertBatch($insert_array, $staff_id, $delete_arrary = array()) {
        print_r($insert_array);
        $this->writedb->trans_start();
        $this->writedb->trans_strict(FALSE);

        if (!empty($insert_array)) {
            $this->writedb->insert_batch('user_permissions', $insert_array);
        }
        if (!empty($delete_arrary)) {

            $this->writedb->where('staff_id', $staff_id);
            $this->writedb->where_in('permission_id', $delete_arrary);
            $this->writedb->delete('user_permissions');
        }


        $this->writedb->trans_complete();


        if ($this->writedb->trans_status() === FALSE) {

            $this->writedb->trans_rollback();
            return FALSE;
        } else {

            $this->writedb->trans_commit();
            return $staff_id;
        }
    }

}

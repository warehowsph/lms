<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Rolepermission_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        //-- Load database for writing
        $this->writedb = $this->load->database('write_db', TRUE);
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function getPermissionByRole($role_id) {
        $this->db->select('`roles_permissions`.*, permission_category.id as permission_category_id,permission_category.name as permission_category_name,permission_category.short_code as permission_category_code');
        $this->db->from('roles_permissions');
        
        $this->db->join('permission_category', 'permission_category.id=roles_permissions.perm_cat_id');
        $this->db->where('roles_permissions.role_id', $role_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function getInsertBatch($insert_array, $role_id, $delete_array) {


        $this->writedb->trans_start();
        $this->writedb->trans_strict(FALSE);
        if (!empty($insert_array)) {

            $this->writedb->insert_batch('role_permissions', $insert_array);
        }

# Updating data
        if (!empty($delete_array)) {
            $this->writedb->where('role_id', $role_id);
            $this->writedb->where_in('permission_id', $delete_array);
            $this->writedb->delete('role_permissions');
        }
        $this->writedb->trans_complete();

        if ($this->writedb->trans_status() === FALSE) {

            $this->writedb->trans_rollback();
            return FALSE;
        } else {

            $this->writedb->trans_commit();
            return TRUE;
        }
    }

    public function getPermissionWithSelectedByRole($role_id) {
        $sql = "SELECT permissions.*, role_permissions.id as `role_permission_id`,IF(role_permissions.id IS NULL,0,1) AS role_permission_state FROM `permissions` LEFT JOIN role_permissions on permissions.id=role_permissions.permission_id and role_permissions.role_id =$role_id";

        $query = $this->db->query($sql);
        return $query->result();
    }

}

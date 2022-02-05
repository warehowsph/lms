<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Frontcms_setting_model extends MY_Model {

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
    public function get($id = null) {
        $this->db->select()->from('front_cms_settings');

        if ($id != null) {

            $this->db->where('id', $id);

        } else {

            $this->db->order_by('id');

        }

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->row();

        } else {

            return false;
            
        }
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id']) && $data['id'] != 0) {
            $this->writedb->where('id', $data['id']);
            $this->writedb->update('front_cms_settings', $data);
			$message      = UPDATE_RECORD_CONSTANT." On Front CMS Setting id ". $data['id'];
			$action       = "Update";
			$record_id    =  $data['id'];
			$this->log($message, $record_id, $action);
			//======================Code End==============================

			$this->writedb->trans_complete(); # Completing transaction
			/*Optional*/

			if ($this->writedb->trans_status() === false) {
				# Something went wrong.
				$this->writedb->trans_rollback();
				return false;

			} else {
				//return $return_value;
			}
        } else {
            $this->writedb->insert('front_cms_settings', $data);
            $insert_id = $this->writedb->insert_id();
			$message      = INSERT_RECORD_CONSTANT." On payment settings id ".$insert_id;
			$action       = "Insert";
			$record_id    = $insert_id;
			$this->log($message, $record_id, $action);
			//echo $this->writedb->last_query();die;
			//======================Code End==============================

			$this->writedb->trans_complete(); # Completing transaction
			/*Optional*/

			if ($this->writedb->trans_status() === false) {
				# Something went wrong.
				$this->writedb->trans_rollback();
				return false;

			} else {
				//return $return_value;
			}
			return $insert_id;
        }
    }

    public function valid_check_exists($str) {
        $url = $this->input->post('url');
        $id = $this->input->post('id');

        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_data_exists($url, $id)) {
            $this->form_validation->set_message('check_exists', 'URL already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}

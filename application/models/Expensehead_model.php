<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Expensehead_model extends MY_Model {

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
        $this->db->select()->from('expense_head');
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

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->writedb->where('id', $id);
        $this->writedb->delete('expense_head');
		$message      = DELETE_RECORD_CONSTANT." On  expense head id ".$id;
        $action       = "Delete";
        $record_id    = $id;
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
        if (isset($data['id'])) {
            $this->writedb->where('id', $data['id']);
            $this->writedb->update('expense_head', $data);
			$message      = UPDATE_RECORD_CONSTANT." On  expense head id ".$data['id'];
			$action       = "Update";
			$record_id    = $data['id'];
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
            $this->writedb->insert('expense_head', $data);
			$id=$this->writedb->insert_id();
			$message      = INSERT_RECORD_CONSTANT." On  expense head id ".$id;
			$action       = "Insert";
			$record_id    = $id;
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
        }
    }

    public function searchexpensegroup($start_date, $end_date,$head_id=null){
        $this->db->select('GROUP_CONCAT(expenses.id,"@",expenses.date,"@",expenses.name,"@",expenses.invoice_no,"@",expenses.amount) as expense, expense_head.exp_category,expenses.exp_head_id,sum(expenses.amount) as total_amount')->from('expenses');
            $this->db->join('expense_head', 'expenses.exp_head_id = expense_head.id');
            $this->db->where('expenses.date >=', $start_date);
            $this->db->where('expenses.date <=', $end_date);
            if($head_id!=null){
                $this->db->where('expenses.exp_head_id',$head_id);
            }
            $this->db->group_by('expenses.exp_head_id');
            $query = $this->db->get();
            return $query->result_array();
    }

}

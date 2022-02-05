<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Examsubject_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        //-- Load database for writing
        $this->writedb = $this->load->database('write_db', TRUE);

    }

    public function add($insert_array, $update_array, $not_be_del,$exam_id)
    {

        if (!empty($insert_array)) {
            foreach ($insert_array as $insert_key => $insert_value) {
                $this->writedb->insert('exam_group_class_batch_exam_subjects', $insert_array[$insert_key]);
                   $not_be_del[]= $this->writedb->insert_id();
            }
        }
        if (!empty($update_array)) {
          $this->writedb->update_batch('exam_group_class_batch_exam_subjects',$update_array, 'id'); 
        }

            if (!empty($not_be_del)) {

                $this->writedb->where('exam_group_class_batch_exams_id', $exam_id);
                $this->writedb->where_not_in('id', $not_be_del);
                $this->writedb->delete('exam_group_class_batch_exam_subjects');
            }


    }

}

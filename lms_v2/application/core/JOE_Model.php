<?php
defined('BASEPATH') or exit('No direct script access allowed');
class JOE_Model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->read = $this->load->database('read', TRUE);
        $this->db = $this->load->database('default', TRUE);
    }

    public function lms_get($table="",$value="",$where="",$select="*") {

        if($table){
            $this->read->select($select);
            if($value){
                if($where){
                    $this->read->where($where,$value);
                }else{
                    die("Where is not defined!");
                }
            }
            $query = $this->read->get($table);
            $return = $query->result_array();
            return $return;
        }else{
            die("Table name was not defined.");
        }
    }

    public function lms_create($table="",$data=array(),$escape=TRUE){

        if($table&&is_string($table)){

            if(!empty($data)){
                $id = $table."_".$this->mode."_".microtime(true)*10000;
                $id = $id.rand(1000,9999);
                $data['id'] = $id;
                
                $escaped_data = array();

                foreach ($data as $data_key => $data_value) {
                    if($escape){
                        $escaped_data[$data_key] = html_escape($data_value);
                    }else{
                        $escaped_data[$data_key] = $data_value;
                    }
                    
                }
                
                $escaped_data['date_created'] = date("Y-m-d H:i:s");
                if($this->db->insert($table, $escaped_data)){
                    return $id;
                }else{ 
                    print_r($this->writedb->error());
                    return false; 
                }
            }else{
                exit("Data is empty");
            }
            
            
        }else{
            echo "Table name was not declared.";
            return false;
        }
    }

    public function lms_update($table="",$data=array(),$where="id"){

        if($table&&is_string($table)){
            $this->db->where($where, $data[$where]);
            $data['date_updated'] = date("Y-m-d H:i:s");
            if($this->db->update($table, $data)){
                $this->db->where($where, $data[$where]);
                $query = $this->db->get($table);
                $return = $query->result_array()[0];
                return $return;
            }else{
                return false;
            }

        }else{
            echo "Table name was not declared.";
            exit();
        }
    }


}

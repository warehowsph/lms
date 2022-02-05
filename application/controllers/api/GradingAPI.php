<?php
// if (!defined('BASEPATH'))
//     exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
     

class GradingAPI Extends REST_Controller {
    public function __construct() {        
        parent::__construct();
        //-- Temporary
        // $this->load->database();
    }

    //-- For grading_class_record table --
    public function class_record_get($id = 0) {
        if(!empty($id)) {
            $data = $this->db->get_where("grading_class_record", ['id' => $id])->row_array();
        } else {
            $data = $this->db->get("grading_class_record")->result();
        }
     
        $this->response($data, REST_Controller::HTTP_OK);
    }

    public function class_record_post() {
        $input = $this->input->post();
        $create = $this->db->insert('grading_class_record', $input);
        // print_r($input);die();
     
        if ($create)
            $this->response(['Item creation successfully.'], REST_Controller::HTTP_OK);
        else
            $this->response(['Item creation failed.'], REST_Controller::HTTP_OK);
    }

    public function class_record_put($id) {
        $data = $this->parsePutRequest(file_get_contents('php://input'));
        // print_r($data);die();

        $this->db->update('grading_class_record', $data, array('id'=>$id));

        if ($this->db->affected_rows() > 0)     
            $this->response(['Item updated successfully.'], REST_Controller::HTTP_OK);
        else
            $this->response(['Item update failed.'], REST_Controller::HTTP_OK);
    }

    public function class_record_delete($id) {
        $this->db->delete('grading_class_record', array('id'=>$id));

        if ($this->db->affected_rows() > 0)     
            $this->response(['Item deleted successfully.'], REST_Controller::HTTP_OK);
        else
            $this->response(['Item delete failed.'], REST_Controller::HTTP_OK);
    }

    //-- For grading_column table --
    public function column_get($id = 0) {
        if(!empty($id)) {
            $data = $this->db->get_where("grading_column", ['id' => $id])->row_array();
        } else {
            $data = $this->db->get("grading_column")->result();
        }
     
        $this->response($data, REST_Controller::HTTP_OK);
    }

    public function column_post() {
        $input = $this->input->post();
        $this->db->insert('grading_column', $input);
     
        $this->response(['Item created successfully.'], REST_Controller::HTTP_OK);
    }

    public function column_put($id) {
        $data = $this->parsePutRequest(file_get_contents('php://input'));
        // print_r($data);die();
        $this->db->update('grading_column', $data, array('id'=>$id));
     
        if ($this->db->affected_rows() > 0)     
            $this->response(['Item updated successfully.'], REST_Controller::HTTP_OK);
        else
            $this->response(['Item update failed.'], REST_Controller::HTTP_OK);
    }

    public function column_delete($id) {
        $this->db->delete('grading_column', array('id'=>$id));
       
        if ($this->db->affected_rows() > 0)     
            $this->response(['Item deleted successfully.'], REST_Controller::HTTP_OK);
        else
            $this->response(['Item delete failed.'], REST_Controller::HTTP_OK);
    }

    //-- For grading_column_scores table --
    public function column_scores_get($id = 0) {
        if(!empty($id)) {
            $data = $this->db->get_where("grading_column_scores", ['id' => $id])->row_array();
        } else {
            $data = $this->db->get("grading_column_scores")->result();
        }
     
        $this->response($data, REST_Controller::HTTP_OK);
    }

    public function column_scores_post() {
        $input = $this->input->post();
        $this->db->insert('grading_column_scores', $input);
     
        $this->response(['Item created successfully.'], REST_Controller::HTTP_OK);
    }

    public function column_scores_put($id) {
        $data = $this->parsePutRequest(file_get_contents('php://input'));
        // print_r($data);die();
        $this->db->update('grading_column_scores', $data, array('id'=>$id));
     
        if ($this->db->affected_rows() > 0)     
            $this->response(['Item updated successfully.'], REST_Controller::HTTP_OK);
        else
            $this->response(['Item update failed.'], REST_Controller::HTTP_OK);
    }

    public function column_scores_delete($id) {
        $this->db->delete('grading_column_scores', array('id'=>$id));
       
        if ($this->db->affected_rows() > 0)     
            $this->response(['Item deleted successfully.'], REST_Controller::HTTP_OK);
        else
            $this->response(['Item delete failed.'], REST_Controller::HTTP_OK);
    }

    //-- For grading_column_section table --
    public function column_section_get($id = 0) {
        if(!empty($id)) {
            $data = $this->db->get_where("grading_column_section", ['id' => $id])->row_array();
        } else {
            $data = $this->db->get("grading_column_section")->result();
        }
     
        $this->response($data, REST_Controller::HTTP_OK);
    }

    public function column_section_post() {
        $input = $this->input->post();
        $this->db->insert('grading_column_section', $input);
     
        $this->response(['Item created successfully.'], REST_Controller::HTTP_OK);
    }

    public function column_section_put($id) {
        $data = $this->parsePutRequest(file_get_contents('php://input'));
        // print_r($data);die();
        $this->db->update('grading_column_section', $data, array('id'=>$id));
     
        if ($this->db->affected_rows() > 0)     
            $this->response(['Item updated successfully.'], REST_Controller::HTTP_OK);
        else
            $this->response(['Item update failed.'], REST_Controller::HTTP_OK);
    }

    public function column_section_delete($id) {
        $this->db->delete('grading_column_section', array('id'=>$id));
       
        if ($this->db->affected_rows() > 0)     
            $this->response(['Item deleted successfully.'], REST_Controller::HTTP_OK);
        else
            $this->response(['Item delete failed.'], REST_Controller::HTTP_OK);
    }

    //-- For grading_criteria table --
    public function criteria_get($id = 0) {
        if(!empty($id)) {
            $data = $this->db->get_where("grading_criteria", ['id' => $id])->row_array();
        } else {
            $data = $this->db->get("grading_criteria")->result();
        }
     
        $this->response($data, REST_Controller::HTTP_OK);
    }

    public function criteria_post() {
        $input = $this->input->post();
        $this->db->insert('grading_criteria', $input);
     
        $this->response(['Item created successfully.'], REST_Controller::HTTP_OK);
    }

    public function criteria_put($id) {
        $data = $this->parsePutRequest(file_get_contents('php://input'));
        // print_r($data);die();
        $this->db->update('grading_criteria', $data, array('id'=>$id));
     
        if ($this->db->affected_rows() > 0)     
            $this->response(['Item updated successfully.'], REST_Controller::HTTP_OK);
        else
            $this->response(['Item update failed.'], REST_Controller::HTTP_OK);
    }

    public function criteria_delete($id) {
        $this->db->delete('grading_criteria', array('id'=>$id));
       
        if ($this->db->affected_rows() > 0)     
            $this->response(['Item deleted successfully.'], REST_Controller::HTTP_OK);
        else
            $this->response(['Item delete failed.'], REST_Controller::HTTP_OK);
    }

    //-- For grading_remarks table --
    public function remarks_get($id = 0) {
        if(!empty($id)) {
            $data = $this->db->get_where("grading_remarks", ['id' => $id])->row_array();
        } else {
            $data = $this->db->get("grading_remarks")->result();
        }
     
        $this->response($data, REST_Controller::HTTP_OK);
    }

    public function remarks_post() {
        $input = $this->input->post();
        $this->db->insert('grading_remarks', $input);
     
        $this->response(['Item created successfully.'], REST_Controller::HTTP_OK);
    }

    public function remarks_put($id) {
        $data = $this->parsePutRequest(file_get_contents('php://input'));
        // print_r($data);die();
        $this->db->update('grading_remarks', $data, array('id'=>$id));
     
        if ($this->db->affected_rows() > 0)     
            $this->response(['Item updated successfully.'], REST_Controller::HTTP_OK);
        else
            $this->response(['Item update failed.'], REST_Controller::HTTP_OK);
    }

    public function remarks_delete($id) {
        $this->db->delete('grading_remarks', array('id'=>$id));
       
        if ($this->db->affected_rows() > 0)     
            $this->response(['Item deleted successfully.'], REST_Controller::HTTP_OK);
        else
            $this->response(['Item delete failed.'], REST_Controller::HTTP_OK);
    }

    function parsePutRequest($stream_data)
    {
        $raw_data = $stream_data;
        $boundary = substr($raw_data, 0, strpos($raw_data, "\r\n"));

        // Fetch each part
        $parts = array_slice(explode($boundary, $raw_data), 1);
        $data = array();

        foreach ($parts as $part) {
            // If this is the last part, break
            if ($part == "--\r\n") break; 

            // Separate content from headers
            $part = ltrim($part, "\r\n");
            list($raw_headers, $body) = explode("\r\n\r\n", $part, 2);

            // Parse the headers list
            $raw_headers = explode("\r\n", $raw_headers);
            $headers = array();
            foreach ($raw_headers as $header) {
                list($name, $value) = explode(':', $header);
                $headers[strtolower($name)] = ltrim($value, ' '); 
            } 

            // Parse the Content-Disposition to get the field name, etc.
            if (isset($headers['content-disposition'])) {
                $filename = null;
                preg_match(
                    '/^(.+); *name="([^"]+)"(; *filename="([^"]+)")?/', 
                    $headers['content-disposition'], 
                    $matches
                );
                list(, $type, $name) = $matches;
                isset($matches[4]) and $filename = $matches[4]; 

                // handle your fields here
                switch ($name) {
                    // this is a file upload
                    case 'userfile':
                        file_put_contents($filename, $body);
                        break;

                    // default for all other files is to populate $data
                    default: 
                        $data[$name] = substr($body, 0, strlen($body) - 2);
                        break;
                } 
            }

        }
        return $data;
    }
}
?>
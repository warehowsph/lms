<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lesson extends JOE_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct() {

        parent::__construct();
        $this->load->model('lesson_model');
  }

  public function index(){

	}

	public function initialize($user_id,$role,$lesson_id){

		$userdata = array(
        'user_id'  => $user_id,
        'role'     => $role,
		);

		$this->session->set_userdata($userdata);
		redirect(site_url('lms/lesson/student_view/'.$lesson_id));
	}
	public function student_view($id,$tester='false'){

		$data['role'] = "student";
		if($tester=='false'){
			if(!array_key_exists('user_id',$this->session->userdata())){
				echo "User was not initialized. Please go back to the previous page.";
				exit;
			}else{

				$data['role'] = $this->session->userdata('role');
			}
		}

		$data['id'] = $id;
		$data['resources'] = base_url('resources/lms/');
		// $data['classes'] = $this->lesson_model->lms_get("classes",$id,"id","id,class");
		// $data['class_sections'] = $this->lesson_model->lms_get("class_sections",$id,"id","id,class_id,section_id");
		// $data['subjects'] = $this->lesson_model->lms_get("subjects",$id,"id","id,name");
		// $data['conference'] = $this->lesson_model->lms_get("conferences",$data['lesson']['zoom_id'],"id")[0];
    // $data['start_url'] = json_decode($data['conference']['return_response'])->start_url;
    // $data['lms_google_meet'] = $data['lesson']['google_meet'];
		// print_r($data['lesson']);
		// exit;
		$this->load->view('lms/lesson/student_view', $data);

	}

	public function api_get_lesson($id){
		echo json_encode($this->lesson_model->lms_get("lms_lesson",$id,"id")[0]);
	}
}

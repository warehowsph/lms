<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Assessment extends JOE_Controller
{

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
   function __construct()
   {

      parent::__construct();
      $this->load->model('assessment_model');
      $this->load->model('setting_model');
   }

   public function index()
   {
   }

   public function initialize($user_id, $role, $id)
   {
      $userdata = array(
         'user_id'  => $user_id,
         'role'     => $role,
      );

      $this->session->set_userdata($userdata);

      if ($role == "student") {
         redirect(site_url('lms/assessment/answer/' . $id));
      } else {
         redirect(site_url('lms/assessment/answer/' . $id . '/false/teacher'));
      }
   }


   public function answer($id, $tester = 'false', $role = "student")
   {
      // echo "<pre>";
      $data['role'] = "student";
      $data['mode'] = $this->mode;
      $data['school_code'] = $this->school_code;
      $school = $this->setting_model->get();

      if ($tester == 'false') {
         if (!array_key_exists('user_id', $this->session->userdata())) {
            echo "User was not initialized. Please go back to the previous page.";
            exit;
         } else {
            $data['role'] = $this->session->userdata('role');
            $data['account_id'] = $this->session->userdata('user_id');
         }
      } else {
         $random_students = $this->assessment_model->lms_get("students", "", "", "id");
         $random_student = array_rand($random_students, 1);

         $data['account_id'] = $random_students[$random_student]['id'];
      }

      $data['id'] = $id;
      $data['resources'] = base_url('resources/lms/');
      $data['old_resources'] = old_url('backend/lms/');

      $data['student_data'] = $this->assessment_model->lms_get("students", $data['account_id'], "id", "firstname,lastname")[0];
      $data['student_name'] = $data['student_data']['firstname'] . " " . $data['student_data']['lastname'];
      $data['assessment'] = $this->assessment_model->lms_get("lms_assessment", $id, "id", "id,attempts,duration,assessment_file,assessment_name,enable_timer")[0];
      $data['s3bucketurl'] = S3_BUCKET_BASE_URL . strtolower($school[0]['dise_code']) . "/";

      // print_r($data);
      // die();

      $response = $this->assessment_model->response($id, $data['account_id'], 1);

      if (count($response) >= $data['assessment']['attempts']) {
         echo "<script>alert('Maximum Attempts Have Been Reached! Account ID:" . $data['account_id'] . "');window.location.replace('" . old_url('lms/assessment/index') . "')</script>";
         $this->load->view('lms/assessment/answer', $data);
      } else {
         $new_response = $this->assessment_model->response($id, $data['account_id'], 0);

         if (empty($new_response)) {
            $assessment_data['assessment_id'] = $id;
            $assessment_data['account_id'] = $data['account_id'];
            $assessment_data['response_status'] = 0;
            $assessment_data['expiration'] = date("Y-m-d H:i:s", strtotime("+" . $data['assessment']['duration'] . " minutes", strtotime(date("Y-m-d H:i:s"))));
            $assessment_data['start_date'] = date("Y-m-d H:i:s");
            $browser = $this->getBrowserInfo();
            $assessment_data['user_agent'] = $browser['user_agent'];
            $assessment_data['browser'] = $browser['browser'];
            $assessment_data['browser_version'] = $browser['browser_version'];
            $assessment_data['device'] = $browser['device'];
            $assessment_data['os_platform'] = $browser['os_platform'];

            $new_assessment_id = $this->assessment_model->lms_create("lms_assessment_sheets", $assessment_data);

            $new_response = $this->assessment_model->lms_get("lms_assessment_sheets", $new_assessment_id, "id", "id,expiration");
         }

         // print_r($new_response[0]);die();

         $data['assessment_sheet'] = $new_response[0];

         $this->load->view('lms/assessment/answer', $data);
      }
   }

   public function getBrowserInfo()
   {
      $browserInfo = array('user_agent' => '', 'browser' => '', 'browser_version' => '', 'os_platform' => '', 'pattern' => '', 'device' => '');

      $u_agent = $_SERVER['HTTP_USER_AGENT'];
      $bname = 'Unknown';
      $ub = 'Unknown';
      $version = "";
      $platform = 'Unknown';

      $deviceType = 'Desktop';

      if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $u_agent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($u_agent, 0, 4))) {

         $deviceType = 'Mobile';
      }

      if ($_SERVER['HTTP_USER_AGENT'] == 'Mozilla/5.0(iPad; U; CPU iPhone OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B314 Safari/531.21.10') {
         $deviceType = 'Tablet';
      }

      if (stristr($_SERVER['HTTP_USER_AGENT'], 'Mozilla/5.0(iPad;')) {
         $deviceType = 'Tablet';
      }

      //$detect = new Mobile_Detect();

      //First get the platform?
      if (preg_match('/linux/i', $u_agent)) {
         $platform = 'linux';
      } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
         $platform = 'mac';
      } elseif (preg_match('/windows|win32/i', $u_agent)) {
         $platform = 'windows';
      }

      // Next get the name of the user agent yes seperately and for good reason
      if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
         $bname = 'IE';
         $ub = "MSIE";
      } else if (preg_match('/Firefox/i', $u_agent)) {
         $bname = 'Mozilla Firefox';
         $ub = "Firefox";
      } else if (preg_match('/Chrome/i', $u_agent) && (!preg_match('/Opera/i', $u_agent) && !preg_match('/OPR/i', $u_agent))) {
         $bname = 'Chrome';
         $ub = "Chrome";
      } else if (preg_match('/Safari/i', $u_agent) && (!preg_match('/Opera/i', $u_agent) && !preg_match('/OPR/i', $u_agent))) {
         $bname = 'Safari';
         $ub = "Safari";
      } else if (preg_match('/Opera/i', $u_agent) || preg_match('/OPR/i', $u_agent)) {
         $bname = 'Opera';
         $ub = "Opera";
      } else if (preg_match('/Netscape/i', $u_agent)) {
         $bname = 'Netscape';
         $ub = "Netscape";
      } else if ((isset($u_agent) && (strpos($u_agent, 'Trident') !== false || strpos($u_agent, 'MSIE') !== false))) {
         $bname = 'Internet Explorer';
         $ub = 'Internet Explorer';
      }


      // finally get the correct version number
      $known = array('Version', $ub, 'other');
      $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';

      if (!preg_match_all($pattern, $u_agent, $matches)) {
         // we have no matching number just continue
      }

      // see how many we have
      $i = count($matches['browser']);
      if ($i != 1) {
         //we will have two since we are not using 'other' argument yet
         //see if version is before or after the name
         if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
            $version = $matches['version'][0];
         } else {
            $version = @$matches['version'][1];
         }
      } else {
         $version = $matches['version'][0];
      }

      // check if we have a number
      if ($version == null || $version == "") {
         $version = "?";
      }

      return array(
         'user_agent' => $u_agent,
         'browser'      => $bname,
         'browser_version'   => $version,
         'os_platform'  => $platform,
         'pattern'   => $pattern,
         'device'    => $deviceType
      );
   }

   public function stored_json()
   {
      $id = $_REQUEST['assessment_id'];
      $sheet = $this->assessment_model->lms_get('lms_assessment', $id, "id", "sheet")[0]['sheet'];
      echo $sheet;
   }

   public function stored_answer()
   {
      $id = $_REQUEST['assessment_sheet_id'];
      $answer = $this->assessment_model->lms_get('lms_assessment_sheets', $id, "id", "answer")[0]['answer'];
      echo $answer;
   }

   public function auto_save()
   {

      $data['id'] = $_REQUEST['id'];
      $data['assessment_id'] = $_REQUEST['assessment_id'];
      $data['answer'] = $_REQUEST['answer'];
      $answer = (array)json_decode($data['answer']);
      $assessment = $this->assessment_model->lms_get("lms_assessment", $data['assessment_id'], "id")[0];
      $assessment_answer = (array)json_decode($assessment['sheet']);

      //convert to array
      foreach ($answer as $answer_key => $answer_value) {
         $answer[$answer_key] = (array)$answer_value;
      }
      foreach ($assessment_answer as $answer_key => $answer_value) {
         $assessment_answer[$answer_key] = (array)$answer_value;
      }
      //convert to array
      $score = 0;
      $total_score = 0;
      foreach ($answer as $answer_key => $answer_value) {
         $total_score += 1;
         $assessment_value = $assessment_answer[$answer_key];
         if ($answer_value['type'] == "multiple_choice" || $answer_value['type'] == "multiple_answer") {

            if ($answer_value['answer'] == $assessment_value['correct']) {
               if (array_key_exists("points", $assessment_value)) {

                  $score += $assessment_value['points'];
               } else {

                  $score += 1;
               }
            }
         } else if ($answer_value['type'] == "short_answer") {
            if (in_array(trim(strtolower($answer_value['answer'])), explode(",", trim(strtolower($assessment_value['correct']))))) {
               if (array_key_exists("points", $assessment_value)) {

                  $score += $assessment_value['points'];
               } else {

                  $score += 1;
               }
            }
         } else {
         }
      }

      $data['score'] = $score;
      $data['response_status'] = "0";

      print_r($this->assessment_model->lms_update("lms_assessment_sheets", $data));
   }

   public function assessment_answer_history()
   {

      $data['assessment_sheet_id'] = $_REQUEST['id'];
      $data['assessment_id'] = $_REQUEST['assessment_id'];
      $data['answer'] = $_REQUEST['answer'];
      $answer = (array)json_decode($data['answer']);
      $assessment = $this->assessment_model->lms_get("lms_assessment", $data['assessment_id'], "id")[0];
      $assessment_answer = (array)json_decode($assessment['sheet']);
      $data['account_id'] = $this->session->userdata('user_id');
      //convert to array
      foreach ($answer as $answer_key => $answer_value) {
         $answer[$answer_key] = (array)$answer_value;
      }
      foreach ($assessment_answer as $answer_key => $answer_value) {
         $assessment_answer[$answer_key] = (array)$answer_value;
      }
      //convert to array
      $score = 0;
      $total_score = 0;
      foreach ($answer as $answer_key => $answer_value) {
         $total_score += 1;
         $assessment_value = $assessment_answer[$answer_key];
         if ($answer_value['type'] == "multiple_choice" || $answer_value['type'] == "multiple_answer") {

            if ($answer_value['answer'] == $assessment_value['correct']) {
               if (array_key_exists("points", $assessment_value)) {

                  $score += $assessment_value['points'];
               } else {

                  $score += 1;
               }
            }
         } else if ($answer_value['type'] == "short_answer") {
            if (in_array(trim(strtolower($answer_value['answer'])), explode(",", trim(strtolower($assessment_value['correct']))))) {
               if (array_key_exists("points", $assessment_value)) {

                  $score += $assessment_value['points'];
               } else {

                  $score += 1;
               }
            }
         } else {
         }
      }

      $data['score'] = $score;
      $data['response_status'] = "0";
      unset($data['score']);
      unset($data['response_status']);
      print_r($data);
      print_r($this->assessment_model->lms_create("lms_assessment_answer_history", $data, FALSE));
   }

   public function answer_submit()
   {

      $data['id'] = $_REQUEST['id'];
      $data['assessment_id'] = $_REQUEST['assessment_id'];
      $data['answer'] = $_REQUEST['answer'];
      $answer = (array)json_decode($data['answer']);
      $assessment = $this->assessment_model->lms_get("lms_assessment", $data['assessment_id'], "id")[0];
      $assessment_answer = (array)json_decode($assessment['sheet']);

      //convert to array
      foreach ($answer as $answer_key => $answer_value) {
         $answer[$answer_key] = (array)$answer_value;
      }
      foreach ($assessment_answer as $answer_key => $answer_value) {
         $assessment_answer[$answer_key] = (array)$answer_value;
      }
      //convert to array
      $score = 0;
      $total_score = 0;
      foreach ($answer as $answer_key => $answer_value) {
         $total_score += 1;
         $assessment_value = $assessment_answer[$answer_key];
         if ($answer_value['type'] == "multiple_choice" || $answer_value['type'] == "multiple_answer") {

            if ($answer_value['answer'] == $assessment_value['correct']) {
               if (array_key_exists("points", $assessment_value)) {

                  $score += $assessment_value['points'];
               } else {

                  $score += 1;
               }
            }
         } else if ($answer_value['type'] == "short_answer") {
            if (in_array(trim(strtolower($answer_value['answer'])), explode(",", trim(strtolower($assessment_value['correct']))))) {
               if (array_key_exists("points", $assessment_value)) {

                  $score += $assessment_value['points'];
               } else {

                  $score += 1;
               }
            }
         } else {
         }
      }

      $data['score'] = $score;
      $data['response_status'] = "1";
      $data['end_date'] = date("Y-m-d H:i:s");
      print_r($this->assessment_model->lms_update("lms_assessment_sheets", $data));
   }
}

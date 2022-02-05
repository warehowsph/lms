<?php

defined('BASEPATH') or exit('No direct script access allowed');

use \Firebase\JWT\JWT;

require_once APPPATH . 'third_party/omnipay/vendor/autoload.php';

class Zoom_api
{

   public $CI;
   private $zoom_api_key = '';
   private $zoom_api_secret = '';

   public function __construct($parameters = array())
   {

      // print_r($parameters);
      // die();

      $this->CI = &get_instance();
      if (!empty($parameters)) {
         $this->zoom_api_key = $parameters['zoom_api_key'];
         $this->zoom_api_secret = $parameters['zoom_api_secret'];

         if ($this->zoom_api_key == "" && $this->zoom_api_secret == "") {
            $setting_result = $this->CI->setting_model->getSchoolDetail();
            $this->zoom_api_key = $setting_result->zoom_api_key;
            $this->zoom_api_secret = $setting_result->zoom_api_secret;
         }

         // print_r($this->zoom_api_key);
         // die();
      }
   }

   protected function sendRequest($data, $zoom_email)
   {
      if ($zoom_email == "rmorancil01@campuscloudph.com") {
         $request_url = 'https://api.zoom.us/v2/users/me/meetings';
      } else {
         $request_url = 'https://api.zoom.us/v2/users/' . $zoom_email . '/meetings';
      }

      $headers = array(
         'authorization: Bearer ' . $this->generateJWTKey(),
         'content-type: application/json',
      );

      // print_r($request_url);
      // die();

      // print_r($headers);
      // die();

      $postFields = json_encode($data);

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_URL, $request_url);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

      $response = curl_exec($ch);
      $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      $err = curl_error($ch);
      curl_close($ch);

      // print_r($response);
      // die();

      if (!$response) {

         return false;
      }
      return json_decode($response);
   }

   private function generateJWTKey()
   {
      $key = $this->zoom_api_key;
      $secret = $this->zoom_api_secret;
      $token = array(
         "iss" => $key,
         "exp" => time() + 3600,
      );

      return JWT::encode($token, $secret);
   }


   public function checkStatus()
   {

      $request_url = 'https://api.zoom.us/v2/metrics/meetings/94457704984/participants';


      $headers = array(
         'authorization: Bearer ' . $this->generateJWTKey(),
         'content-type: application/json',
      );

      //   $postFields = json_encode($data);
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_URL, $request_url);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $response = curl_exec($ch);
      $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      $err = curl_error($ch);
      curl_close($ch);

      if (!$response) {

         return false;
      }

      return json_decode($response);
   }

   public function check_live()
   {

      $request_url = 'https://api.zoom.us/v2/metrics/meetings';


      $headers = array(
         'authorization: Bearer ' . $this->generateJWTKey(),
         'content-type: application/json',
      );

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_URL, $request_url);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $response = curl_exec($ch);
      $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      $err = curl_error($ch);
      curl_close($ch);

      if (!$response) {

         return false;
      }

      return json_decode($response);
   }

   public function get_meeting($meeting_id)
   {

      $request_url = 'https://api.zoom.us/v2/meetings/' . $meeting_id;


      $headers = array(
         'authorization: Bearer ' . $this->generateJWTKey(),
         'content-type: application/json',
      );

      // $postFields = json_encode($data);
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_URL, $request_url);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $response = curl_exec($ch);
      $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      $err = curl_error($ch);
      curl_close($ch);

      if (!$response) {

         return false;
      }

      return json_decode($response);
   }
   public function getJWT()
   {
      return $this->generateJWTKey();
   }

   public function createAMeeting($data = array())
   {
      $post_time = $data['date'];
      $start_time = date("Y-m-d\TH:i:s", strtotime($post_time));
      $data['join_before_host'] = false;
      $createAMeetingArray = array();

      if (!empty($data['alternative_host_ids'])) {
         if (count($data['alternative_host_ids']) > 1) {
            $alternative_host_ids = implode(",", $data['alternative_host_ids']);
         } else {
            $alternative_host_ids = $data['alternative_host_ids'][0];
         }
      }
      $createAMeetingArray['topic'] = $data['title'];
      $createAMeetingArray['agenda'] = !empty($data['agenda']) ? $data['agenda'] : "";
      $createAMeetingArray['type'] = !empty($data['type']) ? $data['type'] : 2; //Scheduled
      $createAMeetingArray['start_time'] = $start_time;
      $createAMeetingArray['timezone'] = $data['timezone'];
      $createAMeetingArray['schedule_for'] = $data['zoom_email'];
      $createAMeetingArray['password'] = !empty($data['password']) ? $data['password'] : "";
      $createAMeetingArray['duration'] = !empty($data['duration']) ? $data['duration'] : 60;
      $createAMeetingArray['settings'] = array(
         'join_before_host' => false,
         'host_video' => !empty($data['host_video']) ? true : false,
         'participant_video' => !empty($data['client_video']) ? true : false,
         'mute_upon_entry' => !empty($data['option_mute_participants']) ? true : false,
         'enforce_login' => !empty($data['option_enforce_login']) ? true : false,
         'auto_recording' => !empty($data['option_auto_recording']) ? $data['option_auto_recording'] : "none",
         'alternative_hosts' => isset($alternative_host_ids) ? $alternative_host_ids : "",
      );

      $zoom_email = $data['zoom_email'];

      // print_r($zoom_email);
      // die();

      return $this->sendRequest($createAMeetingArray, $zoom_email);
   }

   public function deleteMeeting($meetingId)
   {
      $request_url = 'https://api.zoom.us/v2/meetings/' . $meetingId;
      $headers = array(
         'authorization: Bearer ' . $this->generateJWTKey(),
         'content-type: application/json',
      );
      $get_param = array('meetingId' => $meetingId);
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_URL, $request_url);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $response = curl_exec($ch);
      $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      $err = curl_error($ch);
      curl_close($ch);
      if (!$response) {
         return false;
      }
      return json_decode($response);
   }
}

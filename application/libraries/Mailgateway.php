<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mailgateway
{

    private $_CI;

    public function __construct()
    {
        $this->_CI = &get_instance();
        $this->_CI->load->model('setting_model');
        $this->_CI->load->model('studentfeemaster_model');
        $this->_CI->load->model('student_model');
        $this->_CI->load->model('teacher_model');
        $this->_CI->load->model('librarian_model');
        $this->_CI->load->model('accountant_model');
        $this->_CI->load->model('lesson_model');
        $this->_CI->load->model('my_model');
        $this->_CI->load->library('mailer');        
        $this->_CI->mailer;
        $this->sch_setting = $this->_CI->setting_model->get();
    }

    function console_log( $data ) {
        $output  = "<script>console.log( 'PHP debugger: ";
        $output .= json_encode(print_r($data, true));
        $output .= "' );</script>";
        echo $output;
    }

    public function sentMail($sender_details, $template, $subject)
    {
        // $msg = $this->getContent($sender_details, $template);
        $msg = $this->general_data($template, $sender_details);

        $send_to = $sender_details->guardian_email;
        if (!empty($this->_CI->mail_config) && $send_to != "") {

            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

    public function sentRegisterMail($id, $send_to, $template)
    {
        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $subject = "Admission Confirm";
            $msg = $this->getStudentRegistrationContent($id, $template);
            if ($this->_CI->mailer->send_mail($send_to, $subject, $msg)){
                return $email_log = array("title"=>"Success - Admission Email","message"=>$msg,"send_mail"=>1,"is_group"=>0,"is_individual"=>1,"receiver"=>$send_to);
                $this->_CI->lesson_model->sms_create("messages",$email_log);
                $this->_CI->my_model->log("Student admission notice for ".$id." sent", $id, "Email");
            }else{
                return $email_log = array("title"=>"Not Sent - Admission Email","message"=>$msg,"send_mail"=>1,"is_group"=>0,"is_individual"=>1,"receiver"=>$send_to);
                $this->_CI->lesson_model->sms_create("messages",$email_log);
            }
        }
    }

    public function sendEnrollmentConfirmation($details, $template)
    {        
        if (!empty($this->_CI->mail_config) && $details['email'] != "") 
        {
            $subject = "Enrollment Confirmation";
            $msg = $this->fillStudentTemplate($details, $template);

            // print_r("Debug Mode On <BR><BR>");
            // print_r($msg);die();
            try {
                if ($this->_CI->mailer->send_mail($details['email'], $subject, $msg))
                {
                    return $email_log = array("title"=>"Success - Enrollment Confirmation Email","message"=>$msg,"send_mail"=>1,"is_group"=>0,"is_individual"=>1,"receiver"=>$details['email']);
                    // $this->_CI->lesson_model->sms_create("messages",$email_log);
                    // $this->_CI->my_model->log("Student online admission application notice sent", $id, "Email");
                }else{
                    return $email_log = array("title"=>"Not Sent - Enrollment Confirmation Email","message"=>$msg,"send_mail"=>1,"is_group"=>0,"is_individual"=>1,"receiver"=>$details['email']);
                    // $this->_CI->lesson_model->sms_create("messages",$email_log);
                }
            }
            catch(Exception $e) {
                print_r('Message: ' .$e->getMessage());
            }
        }
    }

    public function sendOnlineAdmissionApplicationMail($details, $template)
    {
        // print_r("On Debug Mode");die();

        if (!empty($this->_CI->mail_config) && $details['email'] != "") 
        {
            $subject = "Online Admission Application";
            // var_dump($template);die();
            $msg = $this->fillStudentTemplate($details, $template);
            // var_dump($msg);die();

            if ($this->_CI->mailer->send_mail($details['email'], $subject, $msg))
            {
                return $email_log = array("title"=>"Success - Online Admission Application Email","message"=>$msg,"send_mail"=>1,"is_group"=>0,"is_individual"=>1,"receiver"=>$details['email']);
                // $this->_CI->lesson_model->sms_create("messages",$email_log);
                // $this->_CI->my_model->log("Student online admission application notice sent", $id, "Email");
            }else{
                return $email_log = array("title"=>"Not Sent - Online Admission Application Email","message"=>$msg,"send_mail"=>1,"is_group"=>0,"is_individual"=>1,"receiver"=>$details['email']);
                // $this->_CI->lesson_model->sms_create("messages",$email_log);
            }
        }
    }

    public function send_lesson_details($id, $send_to, $template,$data)
    {
        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $subject = "LMS Lesson Notification";
            $msg = $this->general_data($id, $template,$data);
            // echo '<pre>';print_r($data);exit();
            if ($this->_CI->mailer->send_mail($send_to, $subject, $msg)){
                echo "Sent - ".$data['email']." - ".$data['student_name']."<br>";
                $email_log = array("title"=>"Success - ".$data['school_code']." - Lesson Notification Email - ".$data['teacher_name']." (".$data['lesson_title'].")","message"=>$msg,"send_mail"=>1,"is_group"=>0,"is_individual"=>1,"receiver"=>$send_to,"created_at"=>date("Y-m-d H:i:s"));
                return $email_log;
                $this->_CI->lesson_model->sms_create("messages",$email_log);
                $this->_CI->my_model->log("Lesson Notification Email ".$id." sent", $id, "Email");
            }else{
                echo "Not Sent - ".$data['email']." - ".$data['student_name']."<br>";
                $email_log = array("title"=>"Not Sent - ".$data['school_code']." - Lesson Notification Email - ".$data['teacher_name']." (".$data['lesson_title'].")","message"=>$msg,"send_mail"=>1,"is_group"=>0,"is_individual"=>1,"receiver"=>$send_to,"created_at"=>date("Y-m-d H:i:s"));
                return $email_log;
                $this->_CI->lesson_model->sms_create("messages",$email_log);
            }
        }
    }

    public function sendLoginCredential($chk_mail_sms, $sender_details, $template)
    {
        $msg     = $this->getLoginCredentialContent($sender_details['credential_for'], $sender_details, $template);

        // print_r("Debug Mode On <BR><BR>");
        // print_r($msg);die();

        $send_to = $sender_details['email'];

        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $subject = "Login Credential";
            if ($this->_CI->mailer->send_mail($send_to, $subject, $msg)){
                $email_log = array("title"=>"Success - Login Credential Email","message"=>$msg,"send_mail"=>1,"is_group"=>0,"is_individual"=>1,"receiver"=>$send_to);
                // $this->_CI->lesson_model->sms_create("messages",$email_log);
                $this->_CI->my_model->log(strtoupper($sender_details['credential_for']) . " login credentials for ".$sender_details['username']." sent", $sender_details['id'], "Email");
                return("Success");
            }else{
                $email_log = array("title"=>"Not Sent - Login Credential Email","message"=>$msg,"send_mail"=>1,"is_group"=>0,"is_individual"=>1,"receiver"=>$send_to);
                // $this->_CI->lesson_model->sms_create("messages",$email_log);
                return("Failed");
            }
        }
    }

    public function sendLoginCredentialJoe($chk_mail_sms, $sender_details, $template)
    {
        $msg     = $this->general_data($template, $sender_details);

        $send_to = $sender_details['email'];

        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $subject = "Login Credential";
            $return["msg"] = $msg;
            $return["send_to"] = $send_to;
            $return["subject"] = $subject;
            if ($this->_CI->mailer->send_mail($send_to, $subject, $msg)){                
                echo "Success - ".$sender_details['email'];
                $email_log = array("title"=>"Success - Resend Login Credential","message"=>$msg,"send_mail"=>1,"is_group"=>0,"is_individual"=>1,"receiver"=>$send_to);
                $this->_CI->lesson_model->sms_create("messages",$email_log);
                $this->_CI->my_model->log(strtoupper($sender_details['credential_for']) . " login credentials for ".$sender_details['username']." sent", $sender_details['id'], "Email");
                return("Success");
            }else{
                $email_log = array("title"=>"Not Sent - Resend Login Credential","message"=>$msg,"send_mail"=>1,"is_group"=>0,"is_individual"=>1,"receiver"=>$send_to);
                $this->_CI->lesson_model->sms_create("messages",$email_log);
                return("Failed");
            }
            // return $return;                
        }
            
    }

    public function sentAddFeeMail($detail, $template)
    {
        $send_to = $detail->email;
        $msg     = $this->getAddFeeContent($detail, $template);
        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $subject = "Fees Received";

            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

    public function sentExamResultMail($detail, $template)
    {

        $msg     = $this->fillStudentTemplate($detail, $template);     
        $send_to = $detail['guardian_email'];
        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $subject = "Exam Result";           
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);

        }
    }

    public function sentHomeworkStudentMail($detail, $template)
    {

        if (!empty($this->_CI->mail_config)) {
            foreach ($detail as $student_key => $student_value) {
                $send_to =$student_key;
                if ($send_to != "") {
                    $msg     = $this->getHomeworkStudentContent($detail[$student_key], $template);
                    $subject = "HomeWork Notice";                    
                    $this->_CI->mailer->send_mail($send_to, $subject, $msg);
                }

            }
        }  

    }

    public function sentAbsentStudentMail($detail, $template)
    {

        $send_to = $detail['email'];
        $msg     = $this->getAbsentStudentContent($detail, $template);
      
        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $subject = "Absent Notice";
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

    public function getAddFeeContent($data, $template)
    {
        $currency_symbol      = $this->sch_setting[0]['currency_symbol'];
        $school_name          = $this->sch_setting[0]['name'];
        $invoice_data         = json_decode($data->invoice);
        $data->invoice_id     = $invoice_data->invoice_id;
        $data->sub_invoice_id = $invoice_data->sub_invoice_id;
        $fee                  = $this->_CI->studentfeemaster_model->getFeeByInvoice($data->invoice_id, $data->sub_invoice_id);
        $a                    = json_decode($fee->amount_detail);
        $record               = $a->{$data->sub_invoice_id};
        $fee_amount           = number_format((($record->amount + $record->amount_fine)), 2, '.', ',');
        $data->firstname      = $fee->firstname;
        $data->lastname       = $fee->lastname;
        $data->class          = $fee->class;
        $data->section        = $fee->section;
        $data->fee_amount     = $currency_symbol . $fee_amount;

        foreach ($data as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }

        return $template;
    }

    public function getHomeworkStudentContent($student_detail, $template)
    {

        foreach ($student_detail as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;

    }

    public function getAbsentStudentContent($student_detail, $template)
    {

        $session_name = $this->_CI->setting_model->getCurrentSessionName();

        $student_detail['current_session_name'] = $session_name;

        foreach ($student_detail as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }

        return $template;

    }

    public function getStudentRegistrationContent($id, $template)
    {

        $session_name                    = $this->_CI->setting_model->getCurrentSessionName();
        $student                         = $this->_CI->student_model->get($id);
        $student['current_session_name'] = $session_name;

        foreach ($student as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }

        return $template;
    }    

    public function getLoginCredentialContent($credential_for, $sender_details, $template)
    {
        if ($credential_for == "student") {
            
            // print_r("Debug Mode On <BR><BR>");
            // print_r($sender_details);die();

            $sender_details['url']          = site_url('site/userlogin');

            if ($sender_details['display_name'] == '') {
                $student                        = $this->_CI->student_model->getStudentDetails($sender_details['id']);
                $sender_details['display_name'] = $student['firstname'] . " " . $student['lastname'];
            }
            
        } elseif ($credential_for == "parent") {

            $sender_details['url']          = site_url('site/userlogin');

            if ($sender_details['display_name'] == '') {
                $parent                         = $this->_CI->student_model->get($sender_details['id']);            
                $sender_details['display_name'] = $parent['guardian_name'];
            }            

        } elseif ($credential_for == "staff") {
            $staff                          = $this->_CI->staff_model->get($sender_details['id']);
            $sender_details['url']          = site_url('site/login');
            $sender_details['display_name'] = $staff['name'];
        }
        // print_r($sender_details);
        foreach ($sender_details as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }

        // print_r($template);

        return $template;
    }

    public function getLoginCredentialContentJoe($credential_for, $sender_details, $template)
    {
        echo "<pre>";
        if ($credential_for == "student") {
            $student                        = $this->_CI->student_model->get($sender_details['id']);
            $sender_details['url']          = site_url('site/userlogin');
            $sender_details['display_name'] = $student['firstname'] . " " . $student['lastname'];
            $sender_details['username'] = $student['username'];
            $sender_details['password'] = $student['password'];

        } elseif ($credential_for == "parent") {
            $parent                         = $this->_CI->student_model->get($sender_details['id']);
            $sender_details['url']          = site_url('site/userlogin');
            $sender_details['display_name'] = $parent['guardian_name'];

        } elseif ($credential_for == "staff") {
            $staff                          = $this->_CI->staff_model->get($sender_details['id']);
            $sender_details['url']          = site_url('site/login');
            $sender_details['display_name'] = $staff['name'];
        }
        print_r($sender_details);

        foreach ($sender_details as $key => $value) {

            $template = str_replace('{{' . $key . '}}', $value, $template);
        }

        return $template;
    }

    public function fillStudentTemplate($student_result_detail, $template)
    {
       
        foreach ($student_result_detail as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;

    }

    // public function getContent($sender_details, $template)
    // {

    //     foreach ($sender_details as $key => $value) {
    //         $template = str_replace('{{' . $key . '}}', $value, $template);
    //     }

    //     return $template;
    // }

    public function general_data($template, $data)
    {

        foreach ($data as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }

        return $template;
    }
}

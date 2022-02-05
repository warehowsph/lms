<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Onlinestudent_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        // $this->load->library('mailsmsconf');
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date = $this->setting_model->getDateYmd();
    }

    public function add($data) {    
        $this->db->where('firstname', $data['firstname']);
        $this->db->where('lastname', $data['lastname']);
        $this->db->where('dob', $data['dob']);
        $q = $this->db->get('online_admissions');

        if ($q->num_rows() > 0) {
            if ($rec['is_enroll'] == 0)
            {
                $rec = $q->row_array();
                $this->db->where('id', $rec['id']);
                $this->db->update('online_admissions', $data);
                $message   = UPDATE_RECORD_CONSTANT . " On online_admissions id " . $rec['id'];
                $action    = "Update";
                $record_id = $rec['id'];
                $this->log($message, $record_id, $action);
            }
        } else {
            $this->db->insert('online_admissions', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On online_admissions id " . $session_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
        }

        // $this->db->insert('online_admissions', $data);
        // return $this->db->insert_id();       

        return $record_id;
    }

    public function get($id = null,$carray=null) {
        $this->db->select('online_admissions.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,
                           vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,online_admissions.hostel_room_id,
                           class_sections.id as class_section_id,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,online_admissions.id,online_admissions.admission_no , 
                           online_admissions.roll_no,online_admissions.admission_date,online_admissions.firstname,  online_admissions.lastname,online_admissions.image,    online_admissions.mobileno, 
                           online_admissions.email ,online_admissions.state ,   online_admissions.city , online_admissions.pincode , online_admissions.note, online_admissions.religion, online_admissions.cast, 
                           school_houses.house_name,   online_admissions.dob ,online_admissions.current_address, online_admissions.previous_school, online_admissions.guardian_is, online_admissions.permanent_address,
                           IFNULL(online_admissions.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,online_admissions.adhar_no,online_admissions.samagra_id,
                           online_admissions.bank_account_no,online_admissions.bank_name, online_admissions.ifsc_code , online_admissions.guardian_name , online_admissions.father_pic ,online_admissions.height, 
                           online_admissions.weight,online_admissions.measurement_date, online_admissions.mother_pic , online_admissions.guardian_pic , online_admissions.guardian_relation,online_admissions.guardian_phone,
                           online_admissions.guardian_address,online_admissions.is_enroll ,online_admissions.created_at,online_admissions.document ,online_admissions.updated_at,online_admissions.father_name,
                           online_admissions.father_phone,online_admissions.blood_group,online_admissions.school_house_id,online_admissions.father_occupation,online_admissions.mother_name,online_admissions.mother_phone,
                           online_admissions.mother_occupation,online_admissions.guardian_occupation,online_admissions.gender,online_admissions.guardian_is,online_admissions.rte,online_admissions.guardian_email,
                           online_admissions.enrollment_type, online_admissions.mode_of_payment,online_admissions.middlename,online_admissions.lrn_no,
                           online_admissions.father_company_name,online_admissions.father_company_position,online_admissions.father_nature_of_business,online_admissions.father_mobile,online_admissions.father_email,
                           online_admissions.father_dob,online_admissions.father_citizenship,online_admissions.father_religion,online_admissions.father_highschool,online_admissions.father_college,
                           online_admissions.father_college_course,online_admissions.father_post_graduate,online_admissions.father_post_course,online_admissions.father_prof_affiliation,
                           online_admissions.father_prof_affiliation_position,online_admissions.father_tech_prof,online_admissions.father_tech_prof_other,
                           online_admissions.mother_company_name,online_admissions.mother_company_position,online_admissions.mother_nature_of_business,online_admissions.mother_mobile,online_admissions.mother_email,
                           online_admissions.mother_dob,online_admissions.mother_citizenship,online_admissions.mother_religion,online_admissions.mother_highschool,online_admissions.mother_college,
                           online_admissions.mother_college_course,online_admissions.mother_post_graduate,online_admissions.mother_post_course,online_admissions.mother_prof_affiliation,
                           online_admissions.mother_prof_affiliation_position,online_admissions.mother_tech_prof,online_admissions.mother_tech_prof_other,
                           online_admissions.marriage,online_admissions.dom,online_admissions.church,online_admissions.family_together,online_admissions.parents_away,online_admissions.parents_away_state,
                           online_admissions.parents_civil_status,online_admissions.parents_civil_status_other,
                           online_admissions.guardian_address_is_current_address,online_admissions.permanent_address_is_current_address,online_admissions.living_with_parents,online_admissions.living_with_parents_specify,
                           online_admissions.has_siblings_enrolled, online_admissions.siblings_specify, online_admissions.preferred_education_mode, online_admissions.enrollment_payment_status,
                           online_admissions.payment_scheme');
        $this->db->from('online_admissions');
        $this->db->join('class_sections', 'class_sections.id = online_admissions.class_section_id', 'left');
        $this->db->join('classes', 'class_sections.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = class_sections.section_id', 'left');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = online_admissions.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('categories', 'online_admissions.category_id = categories.id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = online_admissions.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = online_admissions.school_house_id', 'left');

        //echo $this->db->last_query(); die;

        if($carray!=null){
            //$this->db->where_in('classes.id', $carray);
        }

        if ($id != null) {
            $this->db->where('online_admissions.id', $id);
        } else {
            $this->db->order_by('online_admissions.id', 'desc');
        }

        $query = $this->db->get();

        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function update($data, $action = "save") {
        $record_update_status = true;
        // var_dump($data);die;

        if (isset($data['id'])) {
            $this->db->trans_begin();
            $data_id = $data['id'];
            $class_section_id = $data['class_section_id'];
            $enroll_type = $data['enrollment_type'];
            $student_id = 0;
            $user_password = '';
            $parent_password = '';
            $sibling_id = $data['sibling_id'];
            unset($data['sibling_id']);
            $feesmaster = $data['feesmaster'];
            unset($data['feesmaster']);
            $feesdiscount = $data['feesdiscount'];
            unset($data['feesdiscount']);

            // var_dump($feesmaster);die;
            
            if ($action == "enroll") {
			//==========================
                $insert = true;
                $sch_setting_detail = $this->setting_model->getSetting();

                if ($sch_setting_detail->adm_auto_insert) {
                    if ($sch_setting_detail->adm_update_status) {
                        // $admission_no = $sch_setting_detail->adm_prefix . $sch_setting_detail->adm_start_from;
                        //$last_student = $this->student_model->lastRecord();
                        $last_student = $this->student_model->lastRecordByAdmissionNo();
                        $last_admission_digit = str_replace($sch_setting_detail->adm_prefix, "", $last_student->admission_no);
                        $admission_no = $sch_setting_detail->adm_prefix . sprintf("%0" . $sch_setting_detail->adm_no_digit . "d", $last_admission_digit + 1);                        
                        $data['admission_no'] = $admission_no;
                    } else {
                        $admission_no = $sch_setting_detail->adm_prefix . $sch_setting_detail->adm_start_from;
                        $data['admission_no'] = $admission_no;
                    }

                    //-- Set id number equal to admission no for all non old students
                    if ($enroll_type != 'old') 
                        $data['roll_no'] = $admission_no;
                }                

                $admission_no_exists = $this->student_model->check_adm_exists($data['admission_no']);
                //$lrn_num_exists = $this->student_model->check_roll_exists($data['roll_no']); //-- LRN Number

                if ($admission_no_exists) {
                    $insert = false;
                    $record_update_status = false;
                }

				//============================
                if ($insert) {
                    $this->db->select('class_sections.*')->from('class_sections');
                    $this->db->where('class_sections.id', $data['class_section_id']);
                    $query = $this->db->get();
                    $classs_section_result = $query->row();
                    unset($data['class_section_id']);
                    unset($data['id']);     
                    $student_id = null;   

                    if ($enroll_type == 'old') 
                    {
                        $student_id = $this->GetStudentID($data['roll_no']);
                        
                        $old_data = array (
                            'admission_no' => $data['admission_no'],
                            'admission_date' => date('Y-m-d', strtotime($data['admission_date'])),
                            'mode_of_payment' => $data['mode_of_payment'],
                            'enrollment_type' => $data['enrollment_type'],
                            'gender' => $data['gender'],
                            'dob' => date('Y-m-d', strtotime($data['dob'])),
                            'guardian_email' => $data['email'],
                            'payment_scheme' => $data['payment_scheme'],
                            'preferred_education_mode' => $data['preferred_education_mode'],
                        );
                        
                        $this->db->where('id', $student_id);
                        $this->db->update('students', $old_data);                        
                    } 
                    else if ($enroll_type == 'old_new') 
                    {
                        $student_id = $data['roll_no'] != null && $data['roll_no'] != '' ? $this->GetStudentID($data['roll_no']) : $this->GetStudentIDNumberByName($data['firstname'], $data['lastname']);
                        $data['enrollment_type'] = 'old';

                        if (isset($student_id)) {
                            $this->db->where('id', $student_id);
                            $this->db->update('students', $data);
                        } else {
                            //-- Treat as new student
                            $data['roll_no'] = $data['admission_no'];
                            $this->db->insert('students', $data);
                            $student_id = $this->db->insert_id();
                        }

                        // var_dump($student_id);die;
                    }
                    else 
                    {
                        $data['roll_no'] = $data['admission_no'];
                        $this->db->insert('students', $data);
                        $student_id = $this->db->insert_id();
                    }                    
                   
                    $data_new = array(
                        'student_id' => $student_id,
                        'class_id' => $classs_section_result->class_id,
                        'section_id' => $classs_section_result->section_id,
                        'session_id' => $this->current_session,
                    );

                    // $this->db->insert('student_session', $data_new);
                    // $student_session_id = $this->db->insert_id();

                    // var_dump($student_session_id);die;
                    $student_session_id = $this->student_model->add_student_session($data_new); //-- This updates the existing student session                    
                    

                    if (isset($student_session_id))
                    {
                        //-- Assign fees master
                        if (isset($feesmaster)) 
                        {
                            foreach($feesmaster as $feemaster)
                            {
                                $fee_session_group_id = $this->GetFeeSessionGroupID($feemaster);

                                $insert_array = array(
                                    'student_session_id'   => $student_session_id,
                                    'fee_session_group_id' => $fee_session_group_id,
                                );

                                $this->studentfeemaster_model->add($insert_array);
                            }
                        }

                        //-- Assign discount
                        if (isset($feesdiscount))
                        {
                            foreach($feesdiscount as $discount_id)
                            {
                                $insert_array = array(
                                    'student_session_id' => $student_session_id,
                                    'fees_discount_id' => $discount_id,
                                );

                                $this->feediscount_model->allotdiscount($insert_array);
                            }
                        }
                    }                   
                    
                    //if ($enroll_type != 'old') 
                    {
                        // var_dump($enroll_type);die;
                        // if (strcmp(strtoupper($enroll_type), "OLD") !== 0) {
                            $isOld = null;

                            // if ($enroll_type == 'old_new') 
                            //     $isOld = $data['roll_no'] != null && $data['roll_no'] != '' ? $this->GetStudentID($data['roll_no']) : $this->GetStudentIDNumberByName($data['firstname'], $data['lastname']);
                            
                            // if (!isset($isOld)) {
                                $user_password      = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);

                                $data_student_login = array(
                                    'username' => $this->student_login_prefix . $student_id,
                                    'password' => $user_password,
                                    'user_id'  => $student_id,
                                    'role'     => 'student',
                                );
                                $this->user_model->add($data_student_login);
                                // var_dump($data_student_login);die;

                                if ($sibling_id > 0) 
                                {
                                    $student_sibling = $this->student_model->get($sibling_id);
                                    $update_student  = array(
                                        'id'        => $student_id,
                                        'parent_id' => $student_sibling['parent_id'],
                                    );
                                    $student_sibling = $this->student_model->add($update_student);
                                } 
                                else 
                                {
                                    $parent_password   = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
                                    $temp              = $student_id;
                                    $data_parent_login = array(
                                        'username' => $this->parent_login_prefix . $student_id,
                                        'password' => $parent_password,
                                        'user_id'  => 0,
                                        'role'     => 'parent',
                                        'childs'   => $temp,
                                    );
                                    $ins_parent_id  = $this->user_model->add($data_parent_login);
                                    $update_student = array(
                                        'id'        => $student_id,
                                        'parent_id' => $ins_parent_id,
                                    );
                                    $this->student_model->add($update_student);
                                }
                            // }
                        // }

                        //============== Update setting modal =================
                        if ($sch_setting_detail->adm_auto_insert) {
                            if ($sch_setting_detail->adm_update_status == 0) {
                                $data_setting=array();
                                $data_setting['id']=$sch_setting_detail->id;
                                $data_setting['adm_update_status'] = 1;
                                $this->setting_model->add($data_setting);
                            }
                        }
                        //===================================================

                        //if ($action == "enroll")
                        {
                            $sender_details = array('student_id' => $student_id, 'contact_no' => $this->input->post('guardian_phone'), 'email' => $this->input->post('guardian_email'));
                            // var_dump($sender_details);die;
                            $this->mailsmsconf->mailsms('student_admission', $sender_details);

                            //if ($enroll_type != 'old')
                            {
                                $student_login_detail = array('id' => $student_id, 'credential_for' => 'student', 'username' => $this->student_login_prefix . $student_id, 'password' => $user_password, 'contact_no' => $this->input->post('mobileno'), 'email' => $this->input->post('email'));
                                $this->mailsmsconf->mailsms('login_credential', $student_login_detail);
                                
                                $parent_login_detail = array('id' => $student_id, 'credential_for' => 'parent', 'username' => $this->parent_login_prefix . $student_id, 'password' => $parent_password, 'contact_no' => $this->input->post('guardian_phone'), 'email' => $this->input->post('guardian_email'));
                                $this->mailsmsconf->mailsms('login_credential', $parent_login_detail);
                            }
                        }
                    }

                    $data['is_enroll'] = 1;
                    $data['class_section_id'] = $class_section_id;
                }
            }

            //var_dump($data);die;

            $this->db->where('id', $data_id);
            $this->db->update('online_admissions', $data);
			
			$message      = UPDATE_RECORD_CONSTANT." On  online admissions id ".$data_id;
			$action       = "Update";
			$record_id    = $data_id;
            $this->log($message, $record_id, $action);
			
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
        }

        return $record_update_status;
    }

     public function remove($id) {
		$this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('online_admissions');
		$message      = DELETE_RECORD_CONSTANT." On online admissions id ".$id;
        $action       = "Delete";
        $record_id    = $id;
        $this->log($message, $record_id, $action);
		//======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /*Optional*/
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
        //return $return_value;
        }
    }

    public function GetStudentByRollNo($roll_no) 
    {
        $this->db->select('online_admissions.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,
                           vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,online_admissions.hostel_room_id,
                           class_sections.id as class_section_id,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,online_admissions.id,online_admissions.admission_no , 
                           online_admissions.roll_no,online_admissions.admission_date,online_admissions.firstname,  online_admissions.lastname,online_admissions.image,    online_admissions.mobileno, 
                           online_admissions.email ,online_admissions.state ,   online_admissions.city , online_admissions.pincode , online_admissions.note, online_admissions.religion, 
                           online_admissions.cast, school_houses.house_name,   online_admissions.dob ,online_admissions.current_address, online_admissions.previous_school,
                           online_admissions.guardian_is, online_admissions.permanent_address,IFNULL(online_admissions.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,
                           online_admissions.adhar_no,online_admissions.samagra_id,online_admissions.bank_account_no,online_admissions.bank_name, online_admissions.ifsc_code , 
                           online_admissions.guardian_name , online_admissions.father_pic ,online_admissions.height ,online_admissions.weight,online_admissions.measurement_date, online_admissions.mother_pic , 
                           online_admissions.guardian_pic , online_admissions.guardian_relation,online_admissions.guardian_phone,online_admissions.guardian_address,online_admissions.is_enroll ,
                           online_admissions.created_at,online_admissions.document ,online_admissions.updated_at,online_admissions.father_name,online_admissions.father_phone,online_admissions.blood_group,
                           online_admissions.school_house_id,online_admissions.father_occupation,online_admissions.mother_name,online_admissions.mother_phone,
                           online_admissions.mother_occupation,online_admissions.guardian_occupation,online_admissions.gender,online_admissions.guardian_is,online_admissions.rte,online_admissions.guardian_email,
                           online_admissions.enrollment_type, online_admissions.mode_of_payment,online_admissions.middlename,online_admissions.lrn_no,
                           online_admissions.father_company_name,online_admissions.father_company_position,online_admissions.father_nature_of_business,online_admissions.father_mobile,online_admissions.father_email,
                           online_admissions.father_dob,online_admissions.father_citizenship,online_admissions.father_religion,online_admissions.father_highschool,online_admissions.father_college,
                           online_admissions.father_college_course,online_admissions.father_post_graduate,online_admissions.father_post_course,online_admissions.father_prof_affiliation,
                           online_admissions.father_prof_affiliation_position,online_admissions.father_tech_prof,online_admissions.father_tech_other,
                           online_admissions.mother_company_name,online_admissions.mother_company_position,online_admissions.mother_nature_of_business,online_admissions.mother_mobile,online_admissions.mother_email,
                           online_admissions.mother_dob,online_admissions.mother_citizenship,online_admissions.mother_religion,online_admissions.mother_highschool,online_admissions.mother_college,
                           online_admissions.mother_college_course,online_admissions.mother_post_graduate,online_admissions.mother_post_course,online_admissions.mother_prof_affiliation,
                           online_admissions.mother_prof_affiliation_position,online_admissions.mother_tech_prof,online_admissions.mother_tech_prof_other,
                           online_admissions.payment_scheme');
        $this->db->from('online_admissions');
        $this->db->join('class_sections', 'class_sections.id = online_admissions.class_section_id', 'left');
        $this->db->join('classes', 'class_sections.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = class_sections.section_id', 'left');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = online_admissions.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('categories', 'online_admissions.category_id = categories.id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = online_admissions.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = online_admissions.school_house_id', 'left');
        $this->db->where('students.roll_no', $roll_no);

        $query = $this->db->get();
        return $query->result_array();
    }

    public function GetStudentByLRNNo($lrn_no) 
    {
        $this->db->select('online_admissions.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,
                           vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,online_admissions.hostel_room_id,
                           class_sections.id as class_section_id,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,online_admissions.id,online_admissions.admission_no , 
                           online_admissions.roll_no,online_admissions.admission_date,online_admissions.firstname,  online_admissions.lastname,online_admissions.image,    online_admissions.mobileno, 
                           online_admissions.email ,online_admissions.state, online_admissions.city, online_admissions.pincode , online_admissions.note, online_admissions.religion, 
                           online_admissions.cast, school_houses.house_name, online_admissions.dob, online_admissions.current_address, online_admissions.previous_school,
                           online_admissions.guardian_is, online_admissions.permanent_address,IFNULL(online_admissions.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,
                           online_admissions.adhar_no,online_admissions.samagra_id,online_admissions.bank_account_no,online_admissions.bank_name, online_admissions.ifsc_code , 
                           online_admissions.guardian_name , online_admissions.father_pic ,online_admissions.height ,online_admissions.weight,online_admissions.measurement_date, online_admissions.mother_pic , 
                           online_admissions.guardian_pic , online_admissions.guardian_relation,online_admissions.guardian_phone,online_admissions.guardian_address,online_admissions.is_enroll ,
                           online_admissions.created_at,online_admissions.document ,online_admissions.updated_at,online_admissions.father_name,online_admissions.father_phone,online_admissions.blood_group,
                           online_admissions.school_house_id,online_admissions.father_occupation,online_admissions.mother_name,online_admissions.mother_phone,
                           online_admissions.mother_occupation,online_admissions.guardian_occupation,online_admissions.gender,online_admissions.guardian_is,online_admissions.rte,online_admissions.guardian_email,
                           online_admissions.enrollment_type, online_admissions.mode_of_payment,online_admissions.middlename,online_admissions.lrn_no,
                           online_admissions.father_company_name,online_admissions.father_company_position,online_admissions.father_nature_of_business,online_admissions.father_mobile,online_admissions.father_email,
                           online_admissions.father_dob,online_admissions.father_citizenship,online_admissions.father_religion,online_admissions.father_highschool,online_admissions.father_college,
                           online_admissions.father_college_course,online_admissions.father_post_graduate,online_admissions.father_post_course,online_admissions.father_prof_affiliation,
                           online_admissions.father_prof_affiliation_position,online_admissions.father_tech_prof,online_admissions.father_tech_prof,
                           online_admissions.mother_company_name,online_admissions.mother_company_position,online_admissions.mother_nature_of_business,online_admissions.mother_mobile,online_admissions.mother_email,
                           online_admissions.mother_dob,online_admissions.mother_citizenship,online_admissions.mother_religion,online_admissions.mother_highschool,online_admissions.mother_college,
                           online_admissions.mother_college_course,online_admissions.mother_post_graduate,online_admissions.mother_post_course,online_admissions.mother_prof_affiliation,
                           online_admissions.mother_prof_affiliation_position,online_admissions.mother_tech_prof,online_admissions.mother_tech_prof,
                           online_admissions.payment_scheme');
        $this->db->from('online_admissions');
        $this->db->join('class_sections', 'class_sections.id = online_admissions.class_section_id', 'left');
        $this->db->join('classes', 'class_sections.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = class_sections.section_id', 'left');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = online_admissions.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('categories', 'online_admissions.category_id = categories.id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = online_admissions.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = online_admissions.school_house_id', 'left');
        $this->db->where('students.lrn_no', $lrn_no);

        $query = $this->db->get();
        return $query->result_array();
    }

    public function HasPendingAdmission($firstname, $lastname, $birthdate)
    {
        $this->db->select('firstname, lastname, dob, is_enroll, session_id');
        $this->db->from('online_admissions');
        $this->db->where(array('firstname' => $firstname, 'lastname' => $lastname, 'dob' => $birthdate));
        $this->db->order_by('online_admissions.session_id', 'DESC');
        $result = $this->db->get()->row();
        return $result;
    }

    public function GetStudentID1($lrn_no, $roll_no)
    {
        $result = $this->db->select('id')->from('students')->where('roll_no', $roll_no)->or_where('lrn_no', $lrn_no)->limit(1)->get()->row();
        return $result->id;
    }

    public function GetStudentIDLRN($lrnNumber)
    {
        $result = $this->db->select('id')->from('students')->where('lrn_no', $lrnNumber)->limit(1)->get()->row();
        return $result->id;
    }

    public function GetStudentID($idnumber)
    {
        $result = $this->db->select('id')->from('students')->where('roll_no', $idnumber)->limit(1)->get()->row();
        return $result->id;
    }

    public function GetStudentIDNumberByName($firstname, $lastname)
    {
        $result = $this->db->select('roll_no')->from('students')->where('firstname', $firstname)->where('lastname', $lastname)->limit(1)->get()->row();
        return $result->id;
    }

    public function GetStudentIDNumber($accountid)
    {
        $result = $this->db->select('roll_no')->from('students')->where('id', $accountid)->limit(1)->get()->row();
        return $result->roll_no;
    }

    public function GetClassSectionID($class_id, $section_id)
    {
        $result = $this->db->select('id')->from('class_sections')->where('class_id', $class_id)->where('section_id', $section_id)->limit(1)->get()->row();
        return $result->id;
    }

    public function GetSectionID($section_name)
    {
        $result = $this->db->select('id')->from('sections')->where('section', $section_name)->limit(1)->get()->row();
        return $result->id;
    }

    public function GetEnrollmentTypes()
    {
        $this->db->select('e_type, description');
        $this->db->from('enrollment_type');
        $this->db->order_by('description');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function GetModesOfPayment()
    {
        $this->db->select('mode, description');
        $this->db->from('mode_of_payment');
        $this->db->order_by('description');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function GetPaymentSchemes()
    {
        $this->db->select('scheme, description');
        $this->db->from('payment_scheme');
        $this->db->order_by('description');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function GetFeeSessionGroupID($feegroupid)
    {
        $result = $this->db->select('id')->from('fee_session_groups')->where('fee_groups_id', $feegroupid)->where('session_id', $this->current_session)->limit(1)->get()->row();
        return $result->id;
    }

    // public function GetNameListAdmission($name)
    // {
    //     $this->db->select("DISTINCT(id), CONCAT(studentname, ' (Birthdate: ', dob, ')') AS studentname");
    //     $this->db->from("(SELECT students.id, CONCAT(students.firstname, ' ', students.lastname) AS studentname, students.dob FROM students) tbl1");
    //     if ($name != "")
    //         $this->db->where("LOWER(studentname) like '%".strtolower(urldecode($name))."%'");
    //     $this->db->order_by('studentname', 'asc');
    //     $query = $this->db->get();
    //     $result = ($query->num_rows() > 0) ? $query->result_array() : FALSE;

    //     return $result;
    // }
}

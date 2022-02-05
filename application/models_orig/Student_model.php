<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Student_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date    = $this->setting_model->getDateYmd();
        $this->schoolname = $this->setting_model->getCurrentSchoolName(); 
    }

    public function getBirthDayStudents($date, $email = false, $contact_no = false)
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no, 
                           students.roll_no,students.admission_date,students.firstname,students.lastname,students.image,students.mobileno,students.email,students.state,students.city,students.pincode,
                           students.religion,students.dob,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,
                           students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code,students.guardian_name,students.guardian_relation,students.guardian_phone,
                           students.guardian_address,students.is_active,students.created_at,students.updated_at,students.father_name,students.rte,students.gender,users.id as `user_tbl_id`,users.username,
                           users.password as `user_tbl_password`,users.is_active as `user_tbl_active`,students.app_key,students.parent_app_key,students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                          students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                          students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                          students.father_prof_affiliation_position,students.father_tech_prof,
                          students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                          students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                          students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                          students.mother_prof_affiliation_position,students.mother_tech_prof,
                          students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                          students.preferred_education_mode, students.enrollment_payment_status,
                          students.payment_scheme');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->where('users.role', 'student');
        if ($email) {
            $this->db->where('students.email !=', "");
        }
        if ($contact_no) {
            $this->db->where('students.mobileno !=', "");
        }

        $this->db->where("DATE_FORMAT(students.dob,'%m-%d') = DATE_FORMAT('" . $date . "','%m-%d')");

        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function getStudents()
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no,
                          students.roll_no,students.admission_date,students.firstname,students.lastname,students.image,students.mobileno,students.email,students.state,students.city,students.pincode,
                          students.religion,students.dob,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,
                          students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name,students.ifsc_code,students.guardian_name,students.guardian_relation,students.guardian_phone,
                          students.guardian_address,students.is_active,students.created_at,students.updated_at,students.father_name,students.rte,students.gender,users.id as `user_tbl_id`,users.username,
                          users.password as `user_tbl_password`,users.is_active as `user_tbl_active`,students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no,
                          students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                          students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                          students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                          students.father_prof_affiliation_position,students.father_tech_prof,
                          students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                          students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                          students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                          students.mother_prof_affiliation_position,students.mother_tech_prof,
                          students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                          students.preferred_education_mode, students.enrollment_payment_status,
                          students.payment_scheme');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->where('users.role', 'student');

        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAppStudents()
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no,
                           students.roll_no,students.admission_date,students.firstname,students.lastname,students.image,students.mobileno, students.email ,students.state,students.city,students.pincode,
                           students.religion,students.dob,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,
                           students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.app_key ,students.guardian_relation,
                           students.guardian_phone,students.guardian_address,students.is_active,students.created_at,students.updated_at,students.father_name,students.rte,students.gender,users.id as `user_tbl_id`,
                           users.username,users.password as `user_tbl_password`,users.is_active as `user_tbl_active`,students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                          students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                          students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                          students.father_prof_affiliation_position,students.father_tech_prof,
                          students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                          students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                          students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                          students.mother_prof_affiliation_position,students.mother_tech_prof,
                          students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                          students.preferred_education_mode, students.enrollment_payment_status,
                          students.payment_scheme');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->where('students.app_key !=', "");
        $this->db->where('users.role', 'student');

        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result();
    }

    public function getRecentRecord($id = null)
    {
        $this->db->select('classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,
                           students.firstname,students.lastname,students.image,students.mobileno, students.email ,students.state,students.city,students.pincode,students.religion,students.dob,
                           students.current_address,students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, 
                           students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at,
                           students.updated_at,students.father_name,students.father_phone,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,
                           students.guardian_occupation,students.gender,students.guardian_is,students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                          students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                          students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                          students.father_prof_affiliation_position,students.father_tech_prof,
                          students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                          students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                          students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                          students.mother_prof_affiliation_position,students.mother_tech_prof,
                          students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                          students.preferred_education_mode, students.enrollment_payment_status,
                          students.payment_scheme');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->where('student_session.session_id', $this->current_session);
        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {

        }
        $this->db->order_by('students.id', 'desc');
        $this->db->limit(5);
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getStudentByClassSectionID($class_id = null, $section_id = null, $id = null)
    {
        $this->db->select('student_session.transport_fees,students.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,
                           vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,
                           student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,
                           students.admission_no , students.roll_no,students.admission_date,students.firstname,students.lastname,students.image,students.mobileno, students.email ,students.state,
                           students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
                           students.guardian_is,students.parent_id,students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, 
                           students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , 
                           students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,
                           students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,
                           students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,students.app_key,students.parent_app_key,
                           students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                          students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                          students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                          students.father_prof_affiliation_position,students.father_tech_prof,
                          students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                          students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                          students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                          students.mother_prof_affiliation_position,students.mother_tech_prof,
                          students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                          students.preferred_education_mode, students.enrollment_payment_status,
                          students.payment_scheme');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');

        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');
        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {
            $this->db->where('students.is_active', 'yes');
            $this->db->order_by('students.id', 'desc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getByStudentSession($student_session_id)
    {
        $this->db->select('student_session.transport_fees,students.app_key,students.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,
                           hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,
                           students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,
                           sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,students.mobileno,
                           students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,
                           students.current_address, students.previous_school,students.guardian_is,students.parent_id,students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,
                           students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, 
                           students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,
                           students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,
                           students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,
                           students.dis_note,students.app_key,students.parent_app_key,students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                          students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                          students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                          students.father_prof_affiliation_position,students.father_tech_prof,
                          students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                          students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                          students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                          students.mother_prof_affiliation_position,students.mother_tech_prof,
                          students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                          students.preferred_education_mode, students.enrollment_payment_status,
                          students.payment_scheme');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');

        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');
 
        $this->db->where('student_session.id', $student_session_id);
      
            $query = $this->db->get();
  
            return $query->row_array();
       
    }

     public function get($id = null)
    {
        $this->db->select("student_session.transport_fees,students.app_key,students.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,
                           hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,
                           students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,
                           sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, 
                           students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,
                           students.current_address, students.previous_school,students.guardian_is,students.parent_id,students.permanent_address,students.category_id,students.adhar_no,
                           students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,
                           students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,
                           students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,
                           students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, 
                           users.username,users.password,students.dis_reason,students.dis_note,students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                           students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                           students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                           students.father_prof_affiliation_position,students.father_tech_prof,students.father_tech_prof_other,
                           students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                           students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                           students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                           students.mother_prof_affiliation_position,students.mother_tech_prof,students.mother_tech_prof_other,
                           students.marriage,students.dom,students.church,students.family_together,students.parents_away,students.parents_away_state,
                           students.parents_civil_status,students.parents_civil_status_other,
                           students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                           students.preferred_education_mode, students.enrollment_payment_status,
                           students.payment_scheme,'".$this->schoolname."' AS school_name");
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id', 'left');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');

        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');
        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {
            $this->db->where('students.is_active', 'yes');
            $this->db->order_by('students.id', 'desc');
        }
        $this->db->limit(1);
        $query = $this->db->get();

        // echo($this->db->last_query());
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }

        
    }


    public function findByAdmission($admission_no = null)
    {

        $this->db->select('student_session.transport_fees,students.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,
                           vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,
                           student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,
                           students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   
                           students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
                           students.guardian_is,students.parent_id,students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, 
                           students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , 
                           students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,
                           students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,
                           students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,students.mode_of_payment,
                           students.enrollment_type,students.middlename,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                           students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                           students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                           students.father_prof_affiliation_position,students.father_tech_prof,students.father_tech_prof_other,
                           students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                           students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                           students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                           students.mother_prof_affiliation_position,students.mother_tech_prof,students.mother_tech_prof_other,
                           students.marriage,students.dom,students.church,students.family_together,students.parents_away,students.parents_away_state,
                           students.parents_civil_status,students.parents_civil_status_other,
                           students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                           students.preferred_education_mode, students.enrollment_payment_status,
                           students.payment_scheme');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');

        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');
            $this->db->where('students.is_active', 'yes');
            $this->db->where('students.admission_no', $admission_no);
       


        $query = $this->db->get();

if ($query->num_rows() > 0) {
        return $query->row();

}
return false;
    }

    public function guardian_credential($parent_id)
    {
        $this->db->select('id,user_id,username,password')->from('users');
        $this->db->where('id', $parent_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function search_student()
    {
        $this->db->select('classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,
                           students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     
                           students.dob ,students.current_address,    students.permanent_address,students.category_id,    students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, 
                           students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,
                           students.updated_at,students.father_name,students.father_phone,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,
                           students.guardian_occupation,students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                           students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                           students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                           students.father_prof_affiliation_position,students.father_tech_prof,students.father_tech_prof_other,
                           students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                           students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                           students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                           students.mother_prof_affiliation_position,students.mother_tech_prof,students.mother_tech_prof_other,
                           students.marriage,students.dom,students.church,students.family_together,students.parents_away,students.parents_away_state,
                           students.parents_civil_status,students.parents_civil_status_other,
                           students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                           students.preferred_education_mode, students.enrollment_payment_status,
                           students.payment_scheme');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {
            $this->db->order_by('students.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getstudentdoc($id)
    {
        $this->db->select()->from('student_doc');
        $this->db->where('student_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }
 
    public function searchByClassSection($class_id = null, $section_id = null)
    {
        $i = 1;

        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);

        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no,
                           students.roll_no,students.admission_date,students.firstname,students.lastname,students.image,students.mobileno,students.email,students.state,students.city,students.pincode,
                           students.religion,students.dob,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,
                           students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,
                           students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.app_key,students.parent_app_key,students.rte,students.gender,
                           students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no,students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                           students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                           students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                           students.father_prof_affiliation_position,students.father_tech_prof,students.father_tech_prof_other,
                           students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                           students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                           students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                           students.mother_prof_affiliation_position,students.mother_tech_prof,students.mother_tech_prof_other,
                           students.marriage,students.dom,students.church,students.family_together,students.parents_away,students.parents_away_state,
                           students.parents_civil_status,students.parents_civil_status_other,
                           students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                           students.preferred_education_mode, students.enrollment_payment_status,
                           students.payment_scheme,' . $field_variable);
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', "yes");
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        //$this->db->order_by('students.id');
        $this->db->order_by('students.admission_no', 'asc');

        $query = $this->db->get();

        return $query->result_array();
    }

    public function searchByClassSectionWithoutCurrent($class_id = null, $section_id = null, $student_id = null)
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , 
                           students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , 
                           students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,
                           IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , 
                           students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,
                           students.gender,students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                           students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                           students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                           students.father_prof_affiliation_position,students.father_tech_prof,students.father_tech_prof_other,
                           students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                           students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                           students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                           students.mother_prof_affiliation_position,students.mother_tech_prof,students.mother_tech_prof_other,
                           students.marriage,students.dom,students.church,students.family_together,students.parents_away,students.parents_away_state,
                           students.parents_civil_status,students.parents_civil_status_other,
                           students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                           students.preferred_education_mode, students.enrollment_payment_status,
                           students.payment_scheme');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', "yes");
        $this->db->where('students.id !=', $student_id);
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        $this->db->order_by('students.id');

        $query = $this->db->get();

        return $query->result_array();
    }

    public function searchByClassSectionCategoryGenderRte($class_id = null, $section_id = null
        , $category = null, $gender = null, $rte = null) {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , 
                           students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , 
                           students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,students.category_id, categories.category,   
                           students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,
                           students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender,
                           students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                           students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                           students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                           students.father_prof_affiliation_position,students.father_tech_prof,students.father_tech_prof_other,
                           students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                           students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                           students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                           students.mother_prof_affiliation_position,students.mother_tech_prof,students.mother_tech_prof_other,
                           students.marriage,students.dom,students.church,students.family_together,students.parents_away,students.parents_away_state,
                           students.parents_civil_status,students.parents_civil_status_other,
                           students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                           students.preferred_education_mode, students.enrollment_payment_status,
                           students.payment_scheme');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        if ($category != null) {
            $this->db->where('students.category_id', $category);
        }
        if ($gender != null) {
            $this->db->where('students.gender', $gender);
        }
        if ($rte != null) {
            $this->db->where('students.rte', $rte);
        }
        $this->db->order_by('students.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchFullText($searchterm, $carray = null)
    {
        $userdata = $this->customlib->getUserData();
        $staff_id=$userdata['id'];
        $i               = 1;
        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);

        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
                $sections=$this->teacher_model->get_teacherrestricted_modeallsections($staff_id);
                foreach ($sections as $key => $value) {
                   $sections_id[]=$value['section_id'];
                }
               $this->db->where_in("student_session.section_id", $sections_id);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }
        $this->db->select('classes.id AS `class_id`,students.id,student_session.id as student_session_id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , 
                           students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , 
                           students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,
                           IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , 
                           students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,
                           students.rte,student_session.session_id,students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no,students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                           students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                           students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                           students.father_prof_affiliation_position,students.father_tech_prof,students.father_tech_prof_other,
                           students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                           students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                           students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                           students.mother_prof_affiliation_position,students.mother_tech_prof,students.mother_tech_prof_other,
                           students.marriage,students.dom,students.church,students.family_together,students.parents_away,students.parents_away_state,
                           students.parents_civil_status,students.parents_civil_status_other,
                           students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                           students.preferred_education_mode, students.enrollment_payment_status,
                           students.payment_scheme,' . $field_variable);
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->group_start();
        $this->db->like('CONCAT(students.firstname," ",students.lastname)', $searchterm);
        $this->db->or_like('students.guardian_name', $searchterm);
        $this->db->or_like('students.adhar_no', $searchterm);
        $this->db->or_like('students.samagra_id', $searchterm);
        $this->db->or_like('students.roll_no', $searchterm);
        $this->db->or_like('students.admission_no', $searchterm);
        $this->db->or_like('students.preferred_education_mode', $searchterm);
        $this->db->group_end();
        $this->db->order_by('students.id');
        $query = $this->db->get();
       
        //echo $this->db->last_query();die;
        return $query->result_array();
    }

    public function admission_report($searchterm, $carray = null, $condition = null)
    {
        $userdata = $this->customlib->getUserData();
       

        $i               = 1;
        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);
 if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
            } else {
               
            }
        }
        $this->db->select('classes.id AS `class_id`,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,
                           students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     
                           students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      
                           students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name , students.guardian_relation,
                           students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id,
                           students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no,students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                           students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                           students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                           students.father_prof_affiliation_position,students.father_tech_prof,students.father_tech_prof_other,
                           students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                           students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                           students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                           students.mother_prof_affiliation_position,students.mother_tech_prof,students.mother_tech_prof_other,
                           students.marriage,students.dom,students.church,students.family_together,students.parents_away,students.parents_away_state,
                           students.parents_civil_status,students.parents_civil_status_other,
                           students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                           students.preferred_education_mode, students.enrollment_payment_status,
                           students.payment_scheme,' . $field_variable);
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id','left');
        $this->db->join('sections', 'sections.id = student_session.section_id','left');
        $this->db->join('categories', 'students.category_id = categories.id','left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->group_start();
        $this->db->like('students.firstname', $searchterm);
        $this->db->or_like('students.lastname', $searchterm);
        $this->db->or_like('students.guardian_name', $searchterm);
        $this->db->or_like('students.adhar_no', $searchterm);
        $this->db->or_like('students.samagra_id', $searchterm);
        $this->db->or_like('students.roll_no', $searchterm);
        $this->db->or_like('students.admission_no', $searchterm);
        $this->db->group_end();
        //$this->db->group_by('students.id');
        if ($condition != null) {

            $this->db->where($condition);

        }

        $this->db->order_by('students.id');
        $query = $this->db->get();

        // echo($this->db->last_query());

        return $query->result_array();
    }

    public function admission_report_joe($searchterm, $carray = null, $condition = null,$other_variables=array("gender"=>"all","enrollment_payment_status"=>"all","class"=>"all"))
    {
        $this->db->select("*");
        $this->db->where($condition);
        $this->db->where('online_admissions.session_id',$this->current_session);
        if(array_key_exists("gender", $other_variables)){
          if($other_variables['gender']=="male"){
            $this->db->where('online_admissions.gender',"male");

          }else if($other_variables['gender']=="female"){
            $this->db->where('online_admissions.gender',"female");

          }

        }
        if(array_key_exists("enrollment_payment_status", $other_variables)){
          
          if($other_variables['enrollment_payment_status']=="paid"){
            $this->db->where('online_admissions.enrollment_payment_status',"paid");

          }else if($other_variables['enrollment_payment_status']=="unpaid"){
            $this->db->where('online_admissions.enrollment_payment_status',"unpaid");

          }

        }

        if(array_key_exists("class", $other_variables)){

          if($other_variables['class']!="all"){
            $this->db->where('class_sections.class_id',$other_variables['class']);

          }

        }


        $this->db->join('class_sections','online_admissions.class_section_id = class_sections.id');
        $this->db->join('classes','class_sections.class_id = classes.id');
        $this->db->join('sections','class_sections.section_id = sections.id');
        $data = $this->db->get("online_admissions")->result_array();
        // echo '<pre>';print_r($data);exit();
        return $data;
    }

    public function enrollment_report_joe($searchterm, $carray = null, $condition = null,$other_variables=array("gender"=>"all","enrollment_payment_status"=>"all","class"=>"all"))
    {
        $this->db->select("*");
        $this->db->where($condition);

        $this->db->where('student_session.session_id',$this->current_session);
        if(array_key_exists("gender", $other_variables)){
          if($other_variables['gender']=="male"){
            $this->db->where('students.gender',"male");

          }else if($other_variables['gender']=="female"){
            $this->db->where('students.gender',"female");

          }

        }
        // if(array_key_exists("enrollment_payment_status", $other_variables)){
          
        //   if($other_variables['enrollment_payment_status']=="paid"){
        //     $this->db->where('students.enrollment_payment_status',"paid");

        //   }else if($other_variables['enrollment_payment_status']=="unpaid"){
        //     $this->db->where('students.enrollment_payment_status',"unpaid");

        //   }

        // }

        if(array_key_exists("class", $other_variables)){

          if($other_variables['class']!="all"){
            $this->db->where('student_session.class_id',$other_variables['class']);

          }

        }


        $this->db->join('students','students.id = student_session.student_id');
        // $this->db->join('class_sections','class_sections.class_section_id = class_sections.id');
        $this->db->join('classes','student_session.class_id = classes.id');
        $this->db->join('sections','student_session.section_id = sections.id');
        $data = $this->db->get("student_session")->result_array();
        // echo '<pre>';print_r($data);exit();
        return $data;
    }

    public function enrollment_summary_report_joe($searchterm, $carray = null, $condition = null,$other_variables=array("gender"=>"all","enrollment_payment_status"=>"all","class"=>"all"))
    {
        $this->db->select("classes.class,
          SUM(if(students.gender = 'male',1,0)) as male,
          SUM(if(students.gender = 'female',1,0)) as female,
          COUNT(students.gender) as total
          ");
        $this->db->where($condition);

        $this->db->where('student_session.session_id',$this->current_session);
        // if(array_key_exists("gender", $other_variables)){
        //   if($other_variables['gender']=="male"){
        //     $this->db->where('students.gender',"male");

        //   }else if($other_variables['gender']=="female"){
        //     $this->db->where('students.gender',"female");

        //   }

        // }
        // if(array_key_exists("enrollment_payment_status", $other_variables)){
          
        //   if($other_variables['enrollment_payment_status']=="paid"){
        //     $this->db->where('students.enrollment_payment_status',"paid");

        //   }else if($other_variables['enrollment_payment_status']=="unpaid"){
        //     $this->db->where('students.enrollment_payment_status',"unpaid");

        //   }

        // }

        // if(array_key_exists("class", $other_variables)){

        //   if($other_variables['class']!="all"){
        //     $this->db->where('student_session.class_id',$other_variables['class']);

        //   }

        // }


        $this->db->join('students','students.id = student_session.student_id');
        // $this->db->join('class_sections','class_sections.class_section_id = class_sections.id');
        $this->db->join('classes','student_session.class_id = classes.id');
        $this->db->join('sections','student_session.section_id = sections.id');
        $this->db->group_by('student_session.class_id');
        $data = $this->db->get("student_session")->result_array();
        // echo '<pre>';print_r($data);exit();
        return $data;
    }

    public function sibling_report($searchterm, $carray = null, $condition = null)
    {
        $userdata = $this->customlib->getUserData();
        
       

        $i               = 1;
        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);
 if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }
        $this->db->select('classes.id AS `class_id`,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,
                           students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     
                           students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      
                           students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name,students.mother_name , students.guardian_name , 
                           students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,
                           student_session.session_id,students.parent_id,students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no,students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                           students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                           students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                           students.father_prof_affiliation_position,students.father_tech_prof,students.father_tech_prof_other,
                           students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                           students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                           students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                           students.mother_prof_affiliation_position,students.mother_tech_prof,students.mother_tech_prof_other,
                           students.marriage,students.dom,students.church,students.family_together,students.parents_away,students.parents_away_state,
                           students.parents_civil_status,students.parents_civil_status_other,
                           students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                           students.preferred_education_mode, students.enrollment_payment_status,
                           students.payment_scheme,' . $field_variable);
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        if ($condition != null) {

            $this->db->where($condition);

        }
        $this->db->group_by('students.admission_no');
        $this->db->order_by('students.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function sibling_reportsearch($searchterm, $carray = null, $condition = null)
    {

        
        $userdata = $this->customlib->getUserData();
       

        $i               = 1;
        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);
 if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }
        $this->db->select('students.parent_id')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        if ($condition != null) {

            $this->db->where($condition);

        }
        $this->db->group_by('students.parent_id');
        $this->db->group_by('students.admission_no');
        $this->db->order_by('students.father_name');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getStudentListBYStudentsessionID($array)
    {
        $array = implode(',', $array);
        $sql   = ' SELECT students.* FROM students INNER join (SELECT * FROM `student_session` WHERE `student_session`.`id` IN (' . $array . ')) as student_session on students.id=student_session.student_id';
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function remove($id)
    {
        $this->db->trans_start();

        $sql   = "SELECT * FROM `users` WHERE childs LIKE '%," . $id . ",%' OR childs LIKE '" . $id . ",%' OR childs LIKE '%," . $id . "' OR childs = " . $id;
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            $result      = $query->row();
            $array_slice = explode(',', $result->childs);
            if (count($array_slice) > 1) {
                $arr    = array_diff($array_slice, array($id));
                $update = implode(",", $arr);
                $data   = array('childs' => $update);

                $this->db->where('id', $result->id);
                $this->db->update('users', $data);
            } else {
                $this->db->where('id', $result->id);
                $this->db->delete('users');
            }
        }

        $this->db->where('id', $id);
        $this->db->delete('students');

        $this->db->where('student_id', $id);
        $this->db->delete('student_session');

        $this->db->where('user_id', $id);
        $this->db->where('role', 'student');
        $this->db->delete('users');
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function doc_delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('student_doc');
    }

    public function add($data, $data_setting = array())
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('students', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On students id " . $data['id'];
            $action    = "Update";
            $record_id = $insert_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            if (!empty($data_setting)) {

                if ($data_setting['adm_auto_insert']) {
                    if ($data_setting['adm_update_status'] == 0) {
                        $data_setting['adm_update_status'] = 1;
                        $this->setting_model->add($data_setting);
                    }
                }
                $this->db->insert('students', $data);
                $insert_id = $this->db->insert_id();
                $message   = INSERT_RECORD_CONSTANT . " On students id " . $insert_id;
                $action    = "Insert";
                $record_id = $insert_id;
                $this->log($message, $record_id, $action);
                
                return $insert_id;
            }
        }				
    }

    public function add_student_sibling($data_sibling)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('student_sibling', $data_sibling);
            $message   = UPDATE_RECORD_CONSTANT . " On  student sibling id " . $data['id'];
            $action    = "Update";
            $record_id = $insert_id = $data['id'];
            $this->log($message, $record_id, $action);
            
        } else {
            $this->db->insert('student_sibling', $data_sibling);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On student sibling id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
            
            //return $insert_id;
        }
		//echo $this->db->last_query();die;
            //======================Code End==============================

            $this->db->trans_complete(); # Completing transaction
            /*Optional*/

            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;

            } else {
                return $insert_id;
            }
    }

    public function add_student_session($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        $session_id=0;
        //=======================Code Start===========================
        $this->db->where('session_id', $data['session_id']);
        $this->db->where('student_id', $data['student_id']);
        $q = $this->db->get('student_session');

        if ($q->num_rows() > 0) {
            $rec = $q->row_array();
            $this->db->where('id', $rec['id']);
            $this->db->update('student_session', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  student session id " . $rec['id'];
            $action    = "Update";
            $record_id = $rec['id'];
            $this->log($message, $record_id, $action);
            // $session_id = $record_id;

        } else {
            $this->db->insert('student_session', $data);
            $session_id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On  student session id " . $session_id;
            $action    = "Insert";
            $record_id = $session_id;
            $this->log($message, $record_id, $action);
            
        }
		//echo $this->db->last_query();die;
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /*Optional*/

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return 0;

        } else {
            return $session_id;
        }
    }

    public function add_student_session_update($data)
    {
        $this->db->where('session_id', $data['session_id']);
        $q = $this->db->get('student_session');
        if ($q->num_rows() > 0) {
            $this->db->where('session_id', $student_session);
            $this->db->update('student_session', $data);
        } else {
            $this->db->insert('student_session', $data);
            return $this->db->insert_id();
        }
    }

    public function adddoc($data)
    {
        $this->db->insert('student_doc', $data);
        return $this->db->insert_id();
    }

    public function read_siblings_students($parent_id)
    {
        $this->db->select('*')->from('students');
        $this->db->where('parent_id', $parent_id);
        $this->db->where('students.is_active', 'yes');
        $query = $this->db->get();
        return $query->result();
    }

    public function getMySiblings($parent_id, $student_id)
    {

        $this->db->select('students.*,classes.id as `class_id`,classes.class,sections.id as `section_id`,sections.section,student_session.session_id as `session_id`')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where_not_in('students.id', $student_id);
        $this->db->where('students.parent_id', $parent_id);
        $this->db->where('students.is_active', 'yes');
        $query = $this->db->get();
        return $query->result();
    }

    public function getAttedenceByDateandClass($date)
    {
        $sql   = "SELECT IFNULL(student_attendences.id, 0) as attencence FROM `student_session`left JOIN student_attendences on student_attendences.student_session_id=student_session.id and student_attendences.date=" . $this->db->escape($date) . " and student_attendences.attendence_type_id != 2 where student_session.class_id=7 and student_session.session_id=$this->current_session";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function searchCurrentSessionStudents()
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , 
                           students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , 
                           students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,
                           IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , 
                           students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,
                           students.gender,students.enrollment_type,students.middlename,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                           students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                           students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                           students.father_prof_affiliation_position,students.father_tech_prof,students.father_tech_prof_other,
                           students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                           students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                           students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                           students.mother_prof_affiliation_position,students.mother_tech_prof,students.mother_tech_prof_other,
                           students.marriage,students.dom,students.church,students.family_together,students.parents_away,students.parents_away_state,
                           students.parents_civil_status,students.parents_civil_status_other,
                           students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                           students.preferred_education_mode, students.enrollment_payment_status,
                           students.payment_scheme');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);

        $this->db->order_by('students.firstname', 'asc');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchLibraryStudent($class_id = null, $section_id = null)
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,IFNULL(libarary_members.id,0) as `libarary_member_id`,
                           IFNULL(libarary_members.library_card_no,0) as `library_card_no`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  
                           students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,
                           students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,
                           students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,
                           students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender,students.mode_of_payment,
                           students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                           students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                           students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                           students.father_prof_affiliation_position,students.father_tech_prof,students.father_tech_prof_other,
                           students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                           students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                           students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                           students.mother_prof_affiliation_position,students.mother_tech_prof,students.mother_tech_prof_other,
                           students.marriage,students.dom,students.church,students.family_together,students.parents_away,students.parents_away_state,
                           students.parents_civil_status,students.parents_civil_status_other,
                           students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                           students.preferred_education_mode, students.enrollment_payment_status,
                           students.payment_scheme'); 
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('libarary_members', 'libarary_members.member_id = students.id and libarary_members.member_type = "student"', 'left');

        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchNameLike($searchterm)
    {
        $this->db->select('classes.id AS `class_id`,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,
                           students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,students.religion,     
                           students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,     
                           students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name , students.guardian_relation,
                           students.guardian_email,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,students.app_key,
                           students.parent_app_key,student_session.session_id,students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                           students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                           students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                           students.father_prof_affiliation_position,students.father_tech_prof,students.father_tech_prof_other,
                           students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                           students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                           students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                           students.mother_prof_affiliation_position,students.mother_tech_prof,students.mother_tech_prof_other,
                           students.marriage,students.dom,students.church,students.family_together,students.parents_away,students.parents_away_state,
                           students.parents_civil_status,students.parents_civil_status_other,
                           students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                           students.preferred_education_mode, students.enrollment_payment_status,
                           students.payment_scheme');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->group_start();
        $this->db->like('students.firstname', $searchterm);
        $this->db->or_like('students.lastname', $searchterm);
        $this->db->group_end();
        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchGuardianNameLike($searchterm)
    {
        $this->db->select('classes.id AS `class_id`,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,
                           students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     
                           students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,
                           students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name , students.guardian_relation,
                           students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.guardian_email,students.rte,
                           student_session.session_id,students.app_key,students.parent_app_key,students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                           students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                           students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                           students.father_prof_affiliation_position,students.father_tech_prof,students.father_tech_prof_other,
                           students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                           students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                           students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                           students.mother_prof_affiliation_position,students.mother_tech_prof,students.mother_tech_prof_other,
                           students.marriage,students.dom,students.church,students.family_together,students.parents_away,students.parents_away_state,
                           students.parents_civil_status,students.parents_civil_status_other,
                           students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                           students.preferred_education_mode, students.enrollment_payment_status,
                           students.payment_scheme'); 
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->group_start();
        $this->db->like('students.guardian_name', $searchterm);

        $this->db->group_end();
        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchByClassSectionWithSession($class_id = null, $section_id = null, $session_id = null)
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , 
                           students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , 
                           students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,
                           IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , 
                           students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,
                           students.gender,students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                           students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                           students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                           students.father_prof_affiliation_position,students.father_tech_prof,students.father_tech_prof_other,
                           students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                           students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                           students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                           students.mother_prof_affiliation_position,students.mother_tech_prof,students.mother_tech_prof_other,
                           students.marriage,students.dom,students.church,students.family_together,students.parents_away,students.parents_away_state,
                           students.parents_civil_status,students.parents_civil_status_other,
                           students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                           students.preferred_education_mode, students.enrollment_payment_status,
                           students.payment_scheme'); 
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');

        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function getPreviousSessionStudent($previous_session_id, $class_id, $section_id)
    {
        $sql = "SELECT student_session.student_id as student_id, student_session.id as current_student_session_id, student_session.class_id as current_session_class_id ,
                previous_session.id as previous_student_session_id,students.firstname,students.lastname,students.admission_no,students.roll_no,students.father_name,
                students.admission_date,students.mode_of_payment,students.enrollment_type, students.guardian_email 
                FROM `student_session` 
                left JOIN (SELECT * FROM `student_session` where session_id=$previous_session_id) as previous_session on student_session.student_id=previous_session.student_id 
                INNER join students on students.id = student_session.student_id 
                where student_session.session_id=$this->current_session 
                and student_session.class_id=$class_id 
                and student_session.section_id=$section_id 
                and students.is_active='yes' 
                ORDER BY students.firstname ASC";

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function studentGuardianDetails($carray)
    {
        $userdata = $this->customlib->getUserData();

        $this->db->SELECT("students.admission_no,students.firstname,students.mobileno,students.father_phone,students.mother_phone,students.lastname,students.father_name,students.mother_name,
                           students.guardian_name,students.guardian_relation,students.guardian_phone,students.id,classes.class,sections.section,students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no");
        $this->db->join("student_session", "student_session.student_id = students.id");
        $this->db->join("classes", "student_session.class_id = classes.id");
        $this->db->join("sections", "student_session.section_id = sections.id");
        $this->db->where("students.is_active", "yes");
        $this->db->where('student_session.session_id', $this->current_session);

        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if (!empty($carray)) {
                $this->db->where_in("student_session.class_id", $carray);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }

        $query = $this->db->get("students");

        return $query->result_array();
    }

    public function searchGuardianDetails($class_id, $section_id)
    {
        $this->db->SELECT("students.admission_no,students.firstname,students.lastname,students.mobileno,students.father_phone,students.mother_phone,students.father_name,students.mother_name,
                           students.guardian_name,students.guardian_relation,students.guardian_phone,students.id,classes.class,sections.section,students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no");
        $this->db->join("student_session", "student_session.student_id = students.id");
        $this->db->join("classes", "student_session.class_id = classes.id");
        $this->db->join("sections", "student_session.section_id = sections.id");
        $this->db->where("students.is_active", "yes");
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where(array('student_session.class_id' => $class_id, 'student_session.section_id' => $section_id));
        $query = $this->db->get("students");

        return $query->result_array();
    }

    public function studentAdmissionDetails($carray = null)
    {
        $userdata = $this->customlib->getUserData();
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {

            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }

        $query = $this->db->SELECT("students.firstname,students.lastname,students.is_active, students.mobileno, students.id as sid ,students.admission_no, students.admission_date, students.guardian_name, 
                                    students.guardian_relation, students.guardian_phone, classes.class, sessions.id, sections.section,students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no")->join("student_session", "students.id = student_session.student_id")->join("classes", "student_session.class_id = classes.id")->join("sections", "student_session.section_id = sections.id")->join("sessions", "student_session.session_id = sessions.id")->group_by("students.id")->get("students");

        return $query->result_array();
    }

    public function studentSessionDetails($id)
    {
        $query = $this->db->query("SELECT min(sessions.session) as start , max(sessions.session) as end, min(classes.class) as startclass, max(classes.class) as endclass from sessions join student_session on (sessions.id = student_session.session_id) join classes on (classes.id = student_session.class_id) where student_session.student_id = " . $id);

        return $query->row_array();
    }

    public function searchAdmissionDetails($class_id, $year)
    {
        if (!empty($year)) {

            $data = array('year(admission_date)' => $year,'student_session.class_id'=>$class_id);
        } else {
            $data = array('student_session.class_id' => $class_id);
        }

        $query = $this->db->SELECT("students.firstname,students.lastname,students.is_active, students.mobileno, students.id as sid ,students.admission_no, students.admission_date, 
                                    students.guardian_name, students.guardian_relation, students.guardian_phone, classes.class, sessions.id, sections.section,students.mode_of_payment,
                                    students.enrollment_type,students.middlename,students.lrn_no")->join("student_session", "students.id = student_session.student_id")->join("classes", "student_session.class_id = classes.id")->join("sections", "student_session.section_id = sections.id")->join("sessions", "student_session.session_id = sessions.id")->where($data)->group_by("students.id")->get("students");

        return $query->result_array();
    }

    public function admissionYear()
    {
       $query = $this->db->SELECT("distinct(year(admission_date)) as year")->where_not_in('admission_date',array('0000-00-00','1970-01-01'))->get("students");

        return $query->result_array();
    }

    public function getStudentSession($id)
    {

        $query = $this->db->query("SELECT  max(sessions.id) as student_session_id, max(sessions.session) as session from sessions join student_session on (sessions.id = student_session.session_id)  where student_session.student_id = " . $id);

        return $query->row_array();
    }

    public function valid_student_roll()
    {
        $roll_no    = $this->input->post('roll_no');
        $student_id = $this->input->post('studentid');
        $class      = $this->input->post('class_id');

        if ($roll_no != "") {

            if (!isset($student_id)) {
                $student_id = 0;
            }

            if ($this->check_rollno_exists($roll_no, $student_id, $class)) {
                $this->form_validation->set_message('check_exists', 'ID Number should be unique at Class level');
                return false;
            } else {
                return true;
            }
        }
        return true;
    }

    public function check_rollno_exists($roll_no, $student_id, $class)
    {
        if ($student_id != 0) {
            $data  = array('students.id != ' => $student_id, 'student_session.class_id' => $class, 'students.roll_no' => $roll_no);
            $query = $this->db->where($data)->join("student_session", "students.id = student_session.student_id")->get('students');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {

            $this->db->where(array('class_id' => $class, 'roll_no' => $roll_no));
            $query = $this->db->join("student_session", "students.id = student_session.student_id")->get('students');
           // echo $this->db->last_query();die;
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }

    }

    public function gethouselist()
    {

        $query = $this->db->where("is_active", "yes")->get("school_houses");

        return $query->result_array();
    }

    public function disableStudent($id, $data)
    {

        $this->db->where("id", $id)->update("students", $data);
    }

    public function getdisableStudent()
    {
        $this->db->select('classes.id AS `class_id`,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,
                           students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     
                           students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      
                           students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name , students.guardian_relation,
                           students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id,dis_reason,
                           dis_note,students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                           students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                           students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                           students.father_prof_affiliation_position,students.father_tech_prof,students.father_tech_prof_other,
                           students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                           students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                           students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                           students.mother_prof_affiliation_position,students.mother_tech_prof,students.mother_tech_prof_other,
                           students.marriage,students.dom,students.church,students.family_together,students.parents_away,students.parents_away_state,
                           students.parents_civil_status,students.parents_civil_status_other,
                           students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                           students.preferred_education_mode, students.enrollment_payment_status,
                           students.payment_scheme'); 
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'no');
        $this->db->order_by('students.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function disablestudentByClassSection($class, $section)
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , 
                           students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , 
                           students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,
                           IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , 
                           students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,
                           students.gender,dis_reason,dis_note,students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                           students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                           students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                           students.father_prof_affiliation_position,students.father_tech_prof,students.father_tech_prof_other,
                           students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                           students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                           students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                           students.mother_prof_affiliation_position,students.mother_tech_prof,students.mother_tech_prof_other,
                           students.marriage,students.dom,students.church,students.family_together,students.parents_away,students.parents_away_state,
                           students.parents_civil_status,students.parents_civil_status_other,
                           students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                           students.preferred_education_mode, students.enrollment_payment_status,
                           students.payment_scheme'); 
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', "no");
        if ($class != null) {
            $this->db->where('student_session.class_id', $class);
        }
        if ($section != null) {
            $this->db->where('student_session.section_id', $section);
        }
        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function disablestudentFullText($searchterm)
    {
        $userdata = $this->customlib->getUserData();
        $this->db->select('classes.id AS `class_id`,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,
                           students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     
                           students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      
                           students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name , students.guardian_relation,
                           students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id,dis_reason,
                           dis_note,students.mode_of_payment,students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                           students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                           students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                           students.father_prof_affiliation_position,students.father_tech_prof,students.father_tech_prof_other,
                           students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                           students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                           students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                           students.mother_prof_affiliation_position,students.mother_tech_prof,students.mother_tech_prof_other,
                           students.marriage,students.dom,students.church,students.family_together,students.parents_away,students.parents_away_state,
                           students.parents_civil_status,students.parents_civil_status_other,
                           students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                           students.preferred_education_mode, students.enrollment_payment_status,
                           students.payment_scheme'); 
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'no');
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {

            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        } else {
            $this->db->group_start();
            $this->db->like('students.firstname', $searchterm);
            $this->db->or_like('students.lastname', $searchterm);
            $this->db->or_like('students.guardian_name', $searchterm);
            $this->db->or_like('students.adhar_no', $searchterm);
            $this->db->or_like('students.samagra_id', $searchterm);
            $this->db->or_like('students.roll_no', $searchterm);
            $this->db->or_like('students.admission_no', $searchterm);
            $this->db->group_end();
        }
        $this->db->order_by('students.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getClassSection($id)
    {
 
        $query = $this->db->SELECT("*")->join("sections", "class_sections.section_id = sections.id")->where("class_sections.class_id", $id)->get("class_sections");
        return $query->result_array();
    }

    public function getStudentClassSection($id, $sessionid)
    {

        $query = $this->db->SELECT("students.firstname,students.id,students.lastname,students.image,student_session.section_id,students.enrollment_payment_status")->join("student_session", "students.id = student_session.student_id")->where("student_session.class_id", $id)->where("student_session.session_id", $sessionid)->where("students.is_active", "yes")->get("students");

        return $query->result_array();
        //SELECT `students`.`firstname`, `students`.`id`, `students`.`lastname`, `students`.`image`, `student_session`.`section_id` FROM `students` JOIN `student_session` ON `students`.`id` = `student_session`.`student_id` WHERE `student_session`.`class_id` = '1' AND `student_session`.`session_id` = '14' AND `students`.`is_active` = 'yes'
    }

    public function getStudentsByArray($array)
    {
        $i               = 1;
        $custom_fields   = $this->customfield_model->get_custom_fields('students');
     
        $field_var_array = array();
       if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);

        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , 
                           students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , 
                           students.pincode ,     students.religion,     students.dob ,students.current_address,students.blood_group ,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,
                           IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.cast,students.bank_name, students.ifsc_code , students.guardian_name , 
                           students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.mother_name,students.updated_at,students.father_name,
                           students.rte,students.gender,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`,users.is_active as `user_tbl_active`,students.mode_of_payment,
                           students.enrollment_type,students.middlename,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                           students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                           students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                           students.father_prof_affiliation_position,students.father_tech_prof,students.father_tech_prof_other,
                           students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                           students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                           students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                           students.mother_prof_affiliation_position,students.mother_tech_prof,students.mother_tech_prof_other,
                           students.marriage,students.dom,students.church,students.family_together,students.parents_away,students.parents_away_state,
                           students.parents_civil_status,students.parents_civil_status_other,
                           students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                           students.preferred_education_mode, students.enrollment_payment_status,
                           students.payment_scheme,' . $field_variable); 
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');
        $this->db->where_in('students.id', $array);
        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result();
    }

    public function get_studentsession($student_session_id)
    {

        $query = $this->db->select('sessions.session')->join("student_session", "sessions.id = student_session.session_id")->where('student_session.id', $student_session_id)->get("sessions");
        return $query->row_array();
    }

    public function check_adm_exists($admission_no)
    {
        $this->db->where(array('admission_no' => $admission_no));
        $query = $this->db->get('students');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function lastRecord()
    {
        $last_row = $this->db->select('*')->order_by('id', "desc")->limit(1)->get('students')->row();
        return $last_row;
    }

    public function lastRecordByAdmissionNo()
    {
        $last_row = $this->db->select('*')->order_by('admission_no', "desc")->limit(1)->get('students')->row();
        return $last_row;
    }

    public function currentClassSectionById($studentid, $schoolsessionId)
    {
        return $this->db->select('class_id,section_id')->from('student_session')->where('session_id', $schoolsessionId)->where('student_id', $studentid)->get()->row_array();

    }

    public function reportClassSection($class_id = null, $section_id = null)
    {

        $i = 1;

        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);

        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , 
                           students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , 
                           students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,
                           IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , 
                           students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,
                           students.gender,students.mode_of_payment,students.enrollment_type,students.middlename,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                           students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                           students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                           students.father_prof_affiliation_position,students.father_tech_prof,students.father_tech_prof_other,
                           students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                           students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                           students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                           students.mother_prof_affiliation_position,students.mother_tech_prof,students.mother_tech_prof_other,
                           students.marriage,students.dom,students.church,students.family_together,students.parents_away,students.parents_away_state,
                           students.parents_civil_status,students.parents_civil_status_other,
                           students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                           students.preferred_education_mode, students.enrollment_payment_status,
                           students.payment_scheme,' . $field_variable);
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', "yes");

        $this->db->where('student_session.class_id', $class_id);

        $this->db->where('student_session.section_id', $section_id);

        $this->db->group_by('students.id');
        $this->db->order_by('students.admission_no', 'asc');

        $query = $this->db->get();

        return $query->result_array();
    }

    public function getAllClassSection($class_id = null, $section_id = null)
    {

        $where = array();

        if ($class_id != null) {
            $where['class_id'] = $class_id;
        }

        if ($section_id != null) {
            $where['section_id'] = $section_id;
        }

        return $this->db->select('*')->from('class_sections')->join('classes', 'class_sections.class_id=classes.id', 'inner')->join('sections', 'class_sections.section_id=sections.id', 'inner')->where($where)->get()->result_array();
    }

    public function student_profile($condition)
    {

        $this->db->select('student_session.transport_fees,students.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,
                           vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,
                           student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,
                           students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   
                           students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
                           students.guardian_is,students.parent_id,students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, 
                           students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , 
                           students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,
                           students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,
                           students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,category,students.mode_of_payment,
                           students.enrollment_type,students.middlename,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                           students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                           students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                           students.father_prof_affiliation_position,students.father_tech_prof,students.father_tech_prof_other,
                           students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                           students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                           students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                           students.mother_prof_affiliation_position,students.mother_tech_prof,students.mother_tech_prof_other,
                           students.marriage,students.dom,students.church,students.family_together,students.parents_away,students.parents_away_state,
                           students.parents_civil_status,students.parents_civil_status_other,
                           students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                           students.payment_scheme');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->join('categories', 'categories.id = students.category_id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');
        $this->db->where('students.is_active', 'yes');
        if ($condition != '') {
            $this->db->where($condition);
        }
        $this->db->order_by('students.id', 'desc');
        $query = $this->db->get();
        return $query->result_array();

    }

    public function bulkdelete($students)
    { 
        if (!empty($students)) {

            $this->db->trans_start();
            $student_comma_seprate = implode(', ', $students);
			//delete from students
            $this->db->where_in('id', $students);
            $this->db->delete('students');
			
			//delete from users
            $this->db->where_in('user_id', $students);
            $this->db->where_in('role', 'student');
            $this->db->delete('users');
            //delete from custom_field_value
			
            $sql = "DELETE FROM custom_field_values WHERE id IN (select * from (SELECT t2.id as `id` FROM `custom_fields` INNER JOIN custom_field_values as t2 on t2.custom_field_id=custom_fields.id WHERE custom_fields.belong_to='students' and t2.belong_table_id IN (" . implode(', ', $students) . ")) as m2)";

            $query = $this->db->query($sql);

            $sql_parent = "DELETE from users WHERE id in (SELECT id from (SELECT users.*,students.id as `student_id` FROM `users` LEFT JOIN students on users.id= students.parent_id WHERE role ='parent') as a WHERE a.student_id IS NULL)";
            $query      = $this->db->query($sql_parent);

            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                return false;
            } else {
                return true;
            }

        }
    }

    public function valid_student_admission_no()
    {

        $admission_no    = $this->input->post('admission_no');
        $student_id = $this->input->post('studentid');
     

        if ($admission_no != "") {

            if (!isset($student_id)) {
                $student_id = 0;
            }

            if ($this->check_admission_no_exists($admission_no, $student_id)) {
                $this->form_validation->set_message('check_admission_no_exists', 'Admission No Exists');
                return false;
            } else {
                return true;
            }
        }
        return true;
    }

    public function check_admission_no_exists($admission_no, $student_id)
    {

        if ($student_id != 0) {
            $data  = array('students.id != ' => $student_id, 'students.admission_no' => $admission_no);
            $query = $this->db->where($data)->join("student_session", "students.id = student_session.student_id")->get('students');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {

            $this->db->where(array('class_id' => $class, 'admission_no' => $admission_no));
            $query = $this->db->join("student_session", "students.id = student_session.student_id")->get('students');
     
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }

    }

    public function GetStudentByID($id) 
    {
        $this->db->select('student_session.transport_fees,students.app_key,students.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,
                           hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,
                           students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,
                           sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,students.mobileno, 
                           students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,
                           students.current_address, students.previous_school,students.guardian_is,students.parent_id,students.permanent_address,students.category_id,students.adhar_no,
                           students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,
                           students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,
                           students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,
                           students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, 
                           users.username,users.password,students.dis_reason,students.dis_note,students.mode_of_payment,students.enrollment_type,students.middlename,student_session.session_id,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                           students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                           students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                           students.father_prof_affiliation_position,students.father_tech_prof,students.father_tech_prof_other,
                           students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                           students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                           students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                           students.mother_prof_affiliation_position,students.mother_tech_prof,students.mother_tech_prof_other,
                           students.marriage,students.dom,students.church,students.family_together,students.parents_away,
                           students.parents_away_state,students.parents_civil_status,students.parents_civil_status_other,
                           students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                           students.preferred_education_mode, students.enrollment_payment_status,
                           students.payment_scheme');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id', 'left');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('students.id', $id);
        $this->db->order_by('student_session.session_id', 'DESC');
        //$this->db->where('students.is_active', 'yes');
        $this->db->limit(1);

        // $query = $this->db->get();
        // return $query->result();
        $result = $this->db->get()->row();
        // echo $this->db->last_query();die;
        return $result;
    }

    public function GetStudentByRollNo($roll_no) 
    {
        $this->db->select('student_session.transport_fees,students.app_key,students.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,
                           hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,
                           students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,
                           sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,students.mobileno, 
                           students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,
                           students.current_address, students.previous_school,students.guardian_is,students.parent_id,students.permanent_address,students.category_id,students.adhar_no,
                           students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,
                           students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,
                           students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,
                           students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, 
                           users.username,users.password,students.dis_reason,students.dis_note,students.mode_of_payment,students.enrollment_type,students.middlename,student_session.session_id,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                           students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                           students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                           students.father_prof_affiliation_position,students.father_tech_prof,students.father_tech_prof_other,
                           students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                           students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                           students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                           students.mother_prof_affiliation_position,students.mother_tech_prof,students.mother_tech_prof_other,
                           students.marriage,students.dom,students.church,students.family_together,students.parents_away,
                           students.parents_away_state,students.parents_civil_status,students.parents_civil_status_other,
                           students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                           students.preferred_education_mode, students.enrollment_payment_status,
                           students.payment_scheme');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id', 'left');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('students.roll_no', $roll_no);
        $this->db->order_by('student_session.session_id', 'DESC');
        //$this->db->where('students.is_active', 'yes');
        $this->db->limit(1);

        // $query = $this->db->get();
        // return $query->result();
        $result = $this->db->get()->row();
        // echo $this->db->last_query();die;
        return $result;
    }

    public function GetStudentByLRNNo($lrn_no) 
    {
        $this->db->select('student_session.transport_fees,students.app_key,students.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,
                           hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,
                           students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,
                           sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,students.mobileno, 
                           students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,
                           students.current_address, students.previous_school,students.guardian_is,students.parent_id,students.permanent_address,students.category_id,students.adhar_no,
                           students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,
                           students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,
                           students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,
                           students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, 
                           users.username,users.password,students.dis_reason,students.dis_note,students.mode_of_payment,students.enrollment_type,students.middlename,student_session.session_id,students.lrn_no,
                           students.father_company_name,students.father_company_position,students.father_nature_of_business,students.father_mobile,students.father_email,
                           students.father_dob,students.father_citizenship,students.father_religion,students.father_highschool,students.father_college,
                           students.father_college_course,students.father_post_graduate,students.father_post_course,students.father_prof_affiliation,
                           students.father_prof_affiliation_position,students.father_tech_prof,students.father_tech_prof_other,
                           students.mother_company_name,students.mother_company_position,students.mother_nature_of_business,students.mother_mobile,students.mother_email,
                           students.mother_dob,students.mother_citizenship,students.mother_religion,students.mother_highschool,students.mother_college,
                           students.mother_college_course,students.mother_post_graduate,students.mother_post_course,students.mother_prof_affiliation,
                           students.mother_prof_affiliation_position,students.mother_tech_prof,students.mother_tech_prof_other,
                           students.marriage,students.dom,students.church,students.family_together,students.parents_away,students.parents_away_state,
                           students.parents_civil_status,students.parents_civil_status_other,
                           students.guardian_address_is_current_address,students.permanent_address_is_current_address,students.living_with_parents,students.living_with_parents_specify,
                           students.preferred_education_mode, students.enrollment_payment_status,
                           students.payment_scheme');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id', 'left');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('students.lrn_no', $lrn_no);
        $this->db->or_where('students.roll_no', $lrn_no);
        $this->db->or_where('students.admission_no', $lrn_no);
        $this->db->order_by('student_session.session_id', 'DESC');
        //$this->db->where('students.is_active', 'yes');
        $this->db->limit(1);
        $result = $this->db->get()->row();
        //var_dump($result);die;

        return $result;
    }    

    public function check_roll_exists($roll_no)
    {
        $this->db->where(array('roll_no' => $roll_no));
        $query = $this->db->get('students');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function GetStudentInfo($id_no)
    {
        $this->db->select('students.id, students.roll_no, students.lrn_no, students.firstname, students.middlename, students.lastname, students.dob, students.gender'); 
        $this->db->from('students');
        $this->db->where('students.lrn_no', $id_no);
        $this->db->or_where('students.roll_no', $id_no);
        $this->db->or_where('students.admission_no', $id_no);
        $this->db->or_where('students.id', $id_no);
        $this->db->limit(1);

        // $query = $this->db->get();
        // return $query->result();
        $result = $this->db->get()->row();
        return $result;
    }

    public function GetLRNList($lrn)
    {
        // $this->db->select('DISTINCT(students.roll_no) AS roll_no');
        // $this->db->from('students');
        // if ($lrn != "")
        //     $this->db->where("students.roll_no like '".strtolower(urldecode($lrn))."%'");
        // $this->db->order_by('students.roll_no', 'asc');
        $this->db->select('DISTINCT(students.lrn_no) AS lrn_no');
        $this->db->from('students');
        if ($lrn != "")
            $this->db->where("students.lrn_no like '".strtolower(urldecode($lrn))."%'");
        $this->db->order_by('students.lrn_no', 'asc');
        $query = $this->db->get();
        $result = ($query->num_rows() > 0) ? $query->result_array() : FALSE;

        return $result;

        //echo $this->db->last_query(); die;
        //var_dump($query->result()); die;
    }

    public function GetNameList($name)
    {
        $this->db->select("DISTINCT(roll_no), studentname");
        $this->db->from("(SELECT students.roll_no, CONCAT(students.firstname, ' ', students.lastname) AS studentname FROM students) tbl1");
        if ($name != "")
            $this->db->where("LOWER(studentname) like '%".strtolower(urldecode($name))."%'");
        $this->db->order_by('studentname', 'asc');
        $query = $this->db->get();
        $result = ($query->num_rows() > 0) ? $query->result_array() : FALSE;

        return $result;
    }

    public function GetNameListEnrolled($name)
    {
        $this->db->select("DISTINCT(roll_no), studentname");
        $this->db->from("(SELECT students.roll_no, CONCAT(students.firstname, ' ', students.lastname) AS studentname 
                          FROM students
                          JOIN student_session ON students.id = student_session.student_id
                          WHERE student_session.session_id = ".$this->current_session.") tbl1");
        if ($name != "")
            $this->db->where("LOWER(studentname) like '".strtolower(urldecode($name))."%'");
        $this->db->order_by('studentname', 'asc');
        $query = $this->db->get();
        $result = ($query->num_rows() > 0) ? $query->result_array() : FALSE;

        return $result;
    }

    public function GetNameListAdmission($name)
    {
        $this->db->select("DISTINCT(id), CONCAT(studentname, ' (Birthdate: ', dob, ')') AS studentname");
        $this->db->from("(SELECT students.id, CONCAT(students.firstname, ' ', students.lastname) AS studentname, students.dob FROM students) tbl1");
        if ($name != "")
            $this->db->where("LOWER(studentname) like '%".strtolower(urldecode($name))."%'");
        $this->db->order_by('studentname', 'asc');
        $query = $this->db->get();
        $result = ($query->num_rows() > 0) ? $query->result_array() : FALSE;

        return $result;
    }

    // public function DeleteStudent($id)
    // {
    //     $this->db->where('id', $id);
    //     $this->db->delete('students');
    // }

    public function GetStudentID($idnumber)
    {
        $result = $this->db->select('id')->from('students')->where('roll_no', $idnumber)->limit(1)->get()->row();
        return $result->id;
    }

    public function AlreadyEnrolled($firstname, $lastname, $birthdate)
    {
        // $this->db->select('firstname, lastname, dob, is_enroll, session_id');
        // $this->db->from('students');
        // $this->db->where(array('firstname' => $firstname, 'lastname' => $lastname, 'dob' => $birthdate));
        // $result = $this->db->get();

        $this->db->where(array('firstname' => $firstname, 'lastname' => $lastname, 'dob' => $birthdate, 'is_enroll' => 1));
        $query = $this->db->get('online_admissions');

        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
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

    public function GetFeeSessionGroupID($feegroupid)
    {
        $result = $this->db->select('id')->from('fee_session_groups')->where('fee_groups_id', $feegroupid)->where('session_id', $this->current_session)->limit(1)->get()->row();
        return $result->id;
    }

    public function UpdateEnrollmentPaymentStatus($idnumber, $status)
    {        
        $data = array(
            'enrollment_payment_status' => $status,
        );
        if($status=="paid"){
          $data = array(
              'enrollment_payment_status' => $status,
              'enrollment_payment_date' => date("Y-m-d H:i:s"),
              'updated_at' => date("Y-m-d H:i:s"),
          );
        }

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well

        $this->db->where('roll_no', $idnumber);
        $update = $this->db->update('students', $data);

        $this->db->where('roll_no', $idnumber);
        $update = $this->db->update('online_admissions', $data);
        
        // $this->db_exceptions->checkForError();
        // return ($update == true) ? true : false;        

        $this->db->trans_complete(); # Completing transaction
        /*Optional*/

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;

        } else {
            return true;
        }

        // if ($this->db->affected_rows() > 0) 
        //     return true; 
        // else
        //     return false;
    }
}

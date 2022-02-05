<?php

define('THEMES_DIR', 'themes');
define('BASE_URI', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));

class JOEVEN_Controller extends CI_Controller
{

    protected $langs = array();
    public $mode;

    public function __construct()
    {

        parent::__construct();
        $this->config->load('license');
        $this->load->helper('language');

        $this->load->library('auth');
        $this->load->library('module_lib');
        $this->load->library('pushnotification');
        $this->load->library('jsonlib');
        $this->load->helper(array('directory', 'customfield', 'custom'));
        $this->load->model(array('setting_model', 'customfield_model', 'onlinestudent_model', 'houselist_model', 'onlineexam_model', 'onlineexamquestion_model', 'onlineexamresult_model', 'examstudent_model', 'admitcard_model', 'marksheet_model', 'chatuser_model', 'examgroupstudent_model', 'examgroup_model', 'batchsubject_model'));

        $url = $_SERVER['SERVER_NAME'];

        if (strpos($url,'localhost') !== false) {
            $this->mode = "offline";
        }elseif(strpos($url,'192.') !== false||strpos($url,'172.') !== false) {
            $this->mode = "offline";
        }else{
            $this->mode = "online";
        }

        if ($this->session->has_userdata('admin')) {

            $admin    = $this->session->userdata('admin');
            $language = ($admin['language']['language']);

        } else if ($this->session->has_userdata('student')) {

            $student  = $this->session->userdata('student');
            $language = ($student['language']['language']);

        } else {
            $language = "English";
        }
        $this->config->set_item('language', strtotime($language));
        $lang_array = array('form_validation_lang');
        $map        = directory_map(APPPATH . "./language/" . $language . "/app_files");
        foreach ($map as $lang_key => $lang_value) {
            $lang_array[] = 'app_files/' . str_replace(".php", "", $lang_value);
        }

        $this->load->language($lang_array, $language);
    }

}

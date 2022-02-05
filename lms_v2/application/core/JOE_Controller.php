<?php
class JOE_Controller extends CI_Controller
{
    public $mode;
    public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('general');
        date_default_timezone_set('Asia/Manila');


        $url = $_SERVER['SERVER_NAME'];
        $HTTP_HOST = $_SERVER['HTTP_HOST'];
        $database_name = explode('.', $HTTP_HOST)[0];
        $this->school_code = $database_name;
        if (strpos($url,'localhost') !== false) {
            $this->mode = "offline";
        }elseif(strpos($url,'192.') !== false||strpos($url,'172.') !== false) {
            $this->mode = "offline";
        }else{
            $this->mode = "online";
        }

    }
}

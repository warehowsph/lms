<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Assmon_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function get() {
        $sqlQry = "SELECT * FROM 
                    (
                    (SELECT 'Baliuag University' AS school_name, assessment_name, start_date, end_date FROM campus_baliuagu.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'Bulacan Ecumenical' AS school_name, assessment_name, start_date, end_date FROM campus_bulacanecumenical.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'C7 Preschool' AS school_name, assessment_name, start_date, end_date FROM campus_c7preschool.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'CICOSAT' AS school_name, assessment_name, start_date, end_date FROM campus_cicosat.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'CSA' AS school_name, assessment_name, start_date, end_date FROM campus_csa.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'College of Saint Lawrence' AS school_name, assessment_name, start_date, end_date FROM campus_csl.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'DBTI' AS school_name, assessment_name, start_date, end_date FROM campus_dbti.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'GIFTED' AS school_name, assessment_name, start_date, end_date FROM campus_gifted.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'HTS GRADE 1' AS school_name, assessment_name, start_date, end_date FROM campus_htsgrade1.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'HTS LIPA' AS school_name, assessment_name, start_date, end_date FROM campus_htslipa.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'HTS MK' AS school_name, assessment_name, start_date, end_date FROM campus_htsmk.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'HTS PG' AS school_name, assessment_name, start_date, end_date FROM campus_htspg.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'HTS PRESCHOOL' AS school_name, assessment_name, start_date, end_date FROM campus_htspreschool.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'ISBB' AS school_name, assessment_name, start_date, end_date FROM campus_isbb.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'JSMJC' AS school_name, assessment_name, start_date, end_date FROM campus_jsmjc.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'PHSI' AS school_name, assessment_name, start_date, end_date FROM campus_phsi.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'RAINBOW' AS school_name, assessment_name, start_date, end_date FROM campus_rainbow.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'RCAMES Guadalupe Catholic School' AS school_name, assessment_name, start_date, end_date FROM campus_rcamesgcs.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'RCAMES Our Lady of Guadalupe Minor Seminary' AS school_name, assessment_name, start_date, end_date FROM campus_rcamesolgms.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'RCAMES San Isidro Catholic School' AS school_name, assessment_name, start_date, end_date FROM campus_rcamessics.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'RCAMES Saint John the Baptist Catholic School' AS school_name, assessment_name, start_date, end_date FROM campus_rcamessjbcs.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'SIM' AS school_name, assessment_name, start_date, end_date FROM campus_sim.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'SMHS' AS school_name, assessment_name, start_date, end_date FROM campus_smhs.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'SMSBS' AS school_name, assessment_name, start_date, end_date FROM campus_smsbs.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'SMECS' AS school_name, assessment_name, start_date, end_date FROM campus_smecs.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'SMHS' AS school_name, assessment_name, start_date, end_date FROM campus_smhs.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'SOPHIA MEYCAUAYAN' AS school_name, assessment_name, start_date, end_date FROM campus_sophiameycauayan.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'ST. SCHOLASTICA''S ACADEMY PAMPANGA' AS school_name, assessment_name, start_date, end_date FROM campus_ssapamp.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'STEPS MANDALUYONG' AS school_name, assessment_name, start_date, end_date FROM stepsmandaluyong.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'LCC-SILVERCREST' AS school_name, assessment_name, start_date, end_date FROM `campus_lcc-silvercrest`.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    UNION 
                    (SELECT 'TLC-NBS' AS school_name, assessment_name, start_date, end_date FROM `campus_tlc-nbs`.lms_assessment WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC)
                    ) tblmain
                    order by school_name, start_date";
    }
}
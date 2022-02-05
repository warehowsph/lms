<style type="text/css">
    .ui-autocomplete {
        max-height: 300px;
        overflow-y: scroll;
        overflow-x: hidden;
    }
</style>

<?php
if (!$form_admission) {
?>
    <div class="alert alert-danger">
        <?php echo $this->lang->line('admission_form_disable_please_contact_to_administrator'); ?></div>
<?php
    return;
}
?>

<?php if ($this->session->flashdata('msg')) {
    echo $this->session->flashdata('msg');
} ?>

<style>
    .req {
        color: red;
    }

    .modal-dialog {
        overflow-y: initial !important
    }

    .modal-body {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }

    /* .hr-sect {
        display: flex;
        flex-basis: 100%;
        align-items: center;
        color: rgba(0, 0, 0, 0.35);
        margin: 8px 0px;
    }
    .hr-sect::before,
    .hr-sect::after {
        content: "";
        flex-grow: 1;
        background: rgba(0, 0, 0, 0.35);
        height: 1px;
        font-size: 0px;
        line-height: 0px;
        margin: 0px 8px;
    } */

    .wrapper {
        display: flex;
        align-items: center;
        margin: 15px;
    }

    .line {
        border-top: 1px solid grey;
        flex-grow: 1;
        margin: 0 10px;
    }
</style>

<div class="modal fade" tabindex="-1" role="dialog" id="privacyPolicy">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><b>Data Privacy Policy</b></h4>
            </div>

            <div class="modal-body">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="<?php echo base_url(); ?>page/data-privacy" allowfullscreen></iframe>
                </div>
            </div>
            <div class="modal-footer">
                <div class="pull-right">
                    <button type="button" class="onlineformbtn" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="termsCondition">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><b>Enrollment Terms and Condition</b></h4>
            </div>

            <div class="modal-body">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="<?php echo base_url(); ?>page/terms-and-conditions" allowfullscreen></iframe>
                </div>
            </div>
            <div class="modal-footer">
                <div class="pull-right">
                    <button type="button" class="onlineformbtn" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="admissionguidelines">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3><b><?php echo $this->lang->line('enrolment_guidelines'); ?></b></h3>
            </div>

            <div class="modal-body">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="<?php echo base_url(); ?>page/admission-guidelines" allowfullscreen></iframe>
                </div>
            </div>
            <div class="modal-footer">
                <!-- <div class="checkbox pull-left">
                <label><input type="checkbox" value="">Option 1</label>
            </div> -->
                <div class="pull-right">
                    <button type="button" class="onlineformbtn" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<form id="form1" class="spaceb60 onlineform" action="<?php echo current_url() ?>" id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group pull-right">
                <button type="button" class="onlineformbtn pull-right pb-3" onclick="ShowGuidelines()"><?php echo $this->lang->line('enrolment_guidelines'); ?></button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="" class="control-label">Student Enrollment Type</label><small class='req'> *</small>
                <select id="enrollment_type" name="enrollment_type" class="form-control" onchange="DoOnChange(this)">
                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                    <?php foreach ($enrollment_type_list as $etype) { ?>
                        <option value="<?php echo $etype['e_type'] ?>" <?php if (set_value('enrollment_type') == $etype['e_type']) echo "selected=selected" ?>><?php echo $etype['description'] ?></option>
                    <?php } ?>
                    <!-- <?php //foreach ($enrollment_type_list as $enrollType_key => $enrollType_value) { 
                            ?>
                        <option value="<?php //echo $enrollType_key; 
                                        ?>" <?php //echo(set_value('enrollment_type') == $enrollType_key ? 'selected' : ''); 
                                                                            ?>><?php //echo $enrollType_value; 
                                                                                                                                                                ?></option>
                    <?php //}
                    ?> -->
                </select>
                <span class="text-danger"><?php echo form_error('enrollment_type'); ?></span>
            </div>
        </div>
        <div class="col-md-9" id="id_number_input">
            <div class="form-group">
                <!-- <label for="studentidnumber"><?php echo $this->lang->line('student_id'); ?></label> -->
                <input type="hidden" value="<?php echo set_value('accountid'); ?>" name="accountid" id="accountid">
                <label for="studentidnumber"><b>Search Student By:</b> </label>
                <input type="radio" name="search_by" <?php if (set_value('search_by') == 'lrn') echo "checked" ?> value="lrn" checked><span class="text-primary"> ID / LRN</span>
                <input type="radio" name="search_by" <?php if (set_value('search_by') == 'name') echo "checked" ?> value="name"><span class="text-primary"> Name</span>
                <input id="studentidnumber" name="studentidnumber" placeholder="Enter Student ID or LRN" type="text" class="form-control all-fields" value="<?php echo set_value('studentidnumber'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('studentidnumber'); ?></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <input type="hidden" id="classname" name="classname" value="">
                <label for="class_id"><?php echo $this->lang->line('enrolling_for'); ?></label><small class="req"> *</small>
                <select id="class_id" name="class_id" class="form-control all-fields" onchange="SetClassName(this)">
                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                    <?php foreach ($classlist as $class) { ?>
                        <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                    <?php } ?>
                </select>
                <span class="text-danger"><?php echo form_error('class_id'); ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="" class="control-label"><?php echo $this->lang->line('mode_of_payment'); ?></label><small class='req'> *</small>
                <select id="mode_of_payment" name="mode_of_payment" class="form-control all-fields">
                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                    <?php foreach ($payment_mode_list as $pmode) { ?>
                        <option value="<?php echo $pmode['mode'] ?>" <?php if (set_value('mode_of_payment') == $pmode['mode']) echo "selected=selected" ?>><?php echo $pmode['description'] ?></option>
                    <?php } ?>
                </select>
                <span class="text-danger"><?php echo form_error('mode_of_payment'); ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="" class="control-label"><?php echo $this->lang->line('payment_scheme'); ?></label><small class='req'> *</small>
                <select id="payment_scheme" name="payment_scheme" class="form-control all-fields">
                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                    <?php foreach ($payment_scheme_list as $pscheme) { ?>
                        <option value="<?php echo $pscheme['scheme'] ?>" <?php if (set_value('payment_scheme') == $pscheme['scheme']) echo "selected=selected" ?>><?php echo $pscheme['description'] ?></option>
                    <?php } ?>
                </select>
                <span class="text-danger"><?php echo form_error('payment_scheme'); ?></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="lrn_no"><?php echo $this->lang->line('lrn_no'); ?></label>
                <input id="lrn_no" name="lrn_no" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('lrn_no'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('lrn_no'); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="firstname"><?php echo $this->lang->line('first_name'); ?></label><small class="req"> *</small>
                <input id="firstname" name="firstname" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('firstname'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('firstname'); ?></span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="middlename"><?php echo $this->lang->line('middle_name'); ?></label>
                <input id="middlename" name="middlename" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('middlename'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('middlename'); ?></span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="lastname"><?php echo $this->lang->line('last_name'); ?></label><small class="req"> *</small>
                <input id="lastname" name="lastname" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('lastname'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('lastname'); ?></span>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputFile"> <?php echo $this->lang->line('gender'); ?></label><small class="req"> *</small>
                <select class="form-control all-fields" name="gender" id="gender">
                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                    <?php foreach ($genderList as $key => $value) { ?>
                        <option value="<?php echo strtolower($key); ?>" <?php if (strtolower(set_value('gender')) == strtolower($key)) echo "selected"; ?>><?php echo $value; ?></option>
                    <?php } ?>
                </select>
                <span class="text-danger"><?php echo form_error('gender'); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('date_of_birth'); ?></label><small class="req"> *</small>
                <input type="date" class="form-control all-fields" value="<?php echo set_value('dob'); ?>" id="dob" name="dob" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('dob'); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('email'); ?></label><small class="req"> *</small>
                <input id="email" name="email" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('email'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('email'); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1"> <?php echo $this->lang->line('upload') . " " . $this->lang->line('documents'); ?></label>
                <input id="document" name="document[]" type="file" multiple class="form-control all-fields" value="<?php echo set_value('document'); ?>" />
                <span class="text-danger"><?php echo form_error('document'); ?></span>
            </div>
        </div>
    </div>
    <!--./row-->
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo $this->lang->line('has_siblings_enrolled'); ?></label><small class="req"> *</small>
                <label class="radio-inline">
                    <input type="radio" name="has_siblings_enrolled" <?php echo set_value('has_siblings_enrolled') == "yes" ? "checked" : ""; ?> value="yes"> <?php echo $this->lang->line('yes'); ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="has_siblings_enrolled" <?php echo set_value('has_siblings_enrolled') == "no" ? "checked" : ""; ?> value="no"> <?php echo $this->lang->line('no'); ?>
                </label>
                <span class="text-danger"><?php echo form_error('has_siblings_enrolled'); ?></span>
            </div>
            <div class="form-group">
                <label><?php echo $this->lang->line('siblings_specify'); ?></label>
                <input id="siblings_specify" disabled name="siblings_specify" placeholder="If yes, please specify the name(s)" type="text" class="form-control all-fields" value="<?php echo set_value('siblings_specify'); ?>" autocomplete="off" />
            </div>
        </div>
        <div class="col-md-9">
            <div class="form-group">
                <label><?php echo $this->lang->line('preferred_education_mode'); ?></label><small class="req"> *</small>
                <label class="radio-inline">
                    <input type="radio" name="preferred_education_mode" <?php echo set_value('preferred_education_mode') == "techbased" ? "checked" : ""; ?> value="techbased"> <?php echo $this->lang->line('techbased'); ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="preferred_education_mode" <?php echo set_value('preferred_education_mode') == "modulebased" ? "checked" : ""; ?> value="modulebased"> <?php echo $this->lang->line('modulebased'); ?>
                </label>
                <span class="text-danger"><?php echo form_error('preferred_education_mode'); ?></span>
            </div>
        </div>
    </div>
    <!-- <div clss="row">
        <div class="col-12">        
            <div class="form-group">
                <label><?php echo $this->lang->line('preferred_education_mode'); ?></label><small class="req"> *</small> 
                <label class="radio-inline">
                    <input type="radio" name="preferred_education_mode" <?php echo set_value('preferred_education_mode') == "techbased" ? "checked" : ""; ?> value="techbased"> <?php echo $this->lang->line('techbased'); ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="preferred_education_mode" <?php echo set_value('preferred_education_mode') == "modulebased" ? "checked" : ""; ?> value="modulebased"> <?php echo $this->lang->line('modulebased'); ?>
                </label>
                <span class="text-danger"><?php echo form_error('preferred_education_mode'); ?></span>
            </div>
        </div>
    </div> -->
    <!-- Start Parent Details -->
    <div class="row" id="parentdetail">
        <div class="wrapper">
            <h4 class="pagetitleh2"><?php echo $this->lang->line('parent_detail'); ?></h4>
            <div class="line"></div>
        </div>
        <!-- <div class="col-md-12"><h4 class="pagetitleh2"><?php //echo $this->lang->line('parent_detail'); 
                                                            ?></h4></div> -->
        <!-- Start Father section -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('father_name') . " (Last Name, First Name, Middle Name)"; ?></label><small class="req"> *</small>
                <input id="father_name" name="father_name" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('father_name'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('father_name'); ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('father_phone'); ?></label>
                <input id="father_phone" name="father_phone" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('father_phone'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('father_phone'); ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('father_occupation'); ?></label><small class="req"> *</small>
                <input id="father_occupation" name="father_occupation" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('father_occupation'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('father_occupation'); ?></span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="father_company_name"><?php echo $this->lang->line('company'); ?></label><small class="req"> *</small>
                <input id="father_company_name" name="father_company_name" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('father_company_name'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('father_company_name'); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="father_company_position"><?php echo $this->lang->line('position'); ?></label><small class="req"> *</small>
                <input id="father_company_position" name="father_company_position" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('father_company_position'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('father_company_position'); ?></span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="father_nature_of_business"><?php echo $this->lang->line('nature_of_business'); ?></label><small class="req"> *</small>
                <input id="father_nature_of_business" name="father_nature_of_business" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('father_nature_of_business'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('father_nature_of_business'); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="father_mobile"><?php echo $this->lang->line('mobile'); ?></label><small class="req"> *</small>
                <input id="father_mobile" name="father_mobile" pattern="[+][0-9]{2}[0-9]{3}[0-9]{7}" placeholder="e.g. +639999999999" type="text" class="form-control all-fields" value="<?php echo set_value('father_mobile'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('father_mobile'); ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="father_dob"><?php echo $this->lang->line('date_of_birth'); ?></label><small class="req"> *</small>
                <input type="date" class="form-control all-fields" value="<?php echo set_value('father_dob'); ?>" id="father_dob" name="father_dob" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('father_dob'); ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="father_citizenship"><?php echo $this->lang->line('citizenship'); ?></label><small class="req"> *</small>
                <input id="father_citizenship" name="father_citizenship" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('father_citizenship'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('father_citizenship'); ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="father_religion"><?php echo $this->lang->line('religion'); ?></label><small class="req"> *</small>
                <input id="father_religion" name="father_religion" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('father_religion'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('father_religion'); ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="father_highschool"><?php echo $this->lang->line('highschool'); ?></label><small class="req"> *</small>
                <input id="father_highschool" name="father_highschool" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('father_highschool'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('father_highschool'); ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="father_college"><?php echo $this->lang->line('college'); ?></label><small class="req"> *</small>
                <input id="father_college" name="father_college" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('father_college'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('father_college'); ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="father_college_course"><?php echo $this->lang->line('college_course'); ?></label><small class="req"> *</small>
                <input id="father_college_course" name="father_college_course" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('father_college_course'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('father_college_course'); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="father_post_graduate"><?php echo $this->lang->line('post_graduate'); ?></label>
                <input id="father_post_graduate" name="father_post_graduate" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('father_post_graduate'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('father_post_graduate'); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="father_post_course"><?php echo $this->lang->line('degree_attained'); ?></label>
                <input id="father_post_course" name="father_post_course" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('father_post_course'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('father_post_course'); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="father_prof_affiliation"><?php echo $this->lang->line('prof_affil'); ?></label><small class="req"> *</small>
                <input id="father_prof_affiliation" name="father_prof_affiliation" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('father_prof_affiliation'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('father_prof_affiliation'); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="father_prof_affiliation_position"><?php echo $this->lang->line('position_held'); ?></label><small class="req"> *</small>
                <input id="father_prof_affiliation_position" name="father_prof_affiliation_position" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('father_prof_affiliation_position'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('father_prof_affiliation_position'); ?></span>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label><?php echo $this->lang->line('tech_prof'); ?></label><small class="req"> *</small>
            <label class="radio-inline">
                <input type="radio" name="father_tech_prof" <?php echo set_value('father_tech_prof') == "yes" ? "checked" : ""; ?> value="yes"> <?php echo $this->lang->line('yes'); ?>
            </label>
            <label class="radio-inline">
                <input type="radio" name="father_tech_prof" <?php echo set_value('father_tech_prof') == "no" ? "checked" : ""; ?> value="no"> <?php echo $this->lang->line('no'); ?>
            </label>
            <label class="radio-inline">
                <input type="radio" name="father_tech_prof" <?php echo set_value('father_tech_prof') == "others" ? "checked" : ""; ?> value="others"> <?php echo $this->lang->line('other'); ?>
            </label>
            <span class="text-danger"><?php echo form_error('father_tech_prof'); ?></span>
        </div>
        <div class="form-group col-md-6">
            <label><?php echo $this->lang->line('others_specify'); ?></label>
            <input id="father_tech_prof_other" disabled name="father_tech_prof_other" placeholder="If others, please specify" type="text" class="form-control all-fields" value="<?php echo set_value('father_tech_prof_other'); ?>" autocomplete="off" />
        </div>
        <!-- End Father section -->
        <div class="col-md-12">
            <hr class="style1">
        </div>
        <!-- Start Mother section -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('mother_name') . " (Last Name, First Name, Middle Name)"; ?></label><small class="req"> *</small>
                <input id="mother_name" name="mother_name" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('mother_name'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('mother_name'); ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('mother_phone'); ?></label>
                <input id="mother_phone" name="mother_phone" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('mother_phone'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('mother_phone'); ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('mother_occupation'); ?></label><small class="req"> *</small>
                <input id="mother_occupation" name="mother_occupation" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('mother_occupation'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('mother_occupation'); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="mother_company_name"><?php echo $this->lang->line('company'); ?></label><small class="req"> *</small>
                <input id="mother_company_name" name="mother_company_name" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('mother_company_name'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('mother_company_name'); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="mother_company_position"><?php echo $this->lang->line('position'); ?></label><small class="req"> *</small>
                <input id="mother_company_position" name="mother_company_position" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('mother_company_position'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('mother_company_position'); ?></span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="mother_nature_of_business"><?php echo $this->lang->line('nature_of_business'); ?></label><small class="req"> *</small>
                <input id="mother_nature_of_business" name="mother_nature_of_business" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('mother_nature_of_business'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('mother_nature_of_business'); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="mother_mobile"><?php echo $this->lang->line('mobile'); ?></label><small class="req"> *</small>
                <input id="mother_mobile" name="mother_mobile" pattern="^\+(?:[0-9] ?){6,25}[0-9]$" placeholder="e.g. +639999999999" type="text" class="form-control all-fields" value="<?php echo set_value('mother_mobile'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('mother_mobile'); ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="mother_dob"><?php echo $this->lang->line('date_of_birth'); ?></label><small class="req"> *</small>
                <input type="date" class="form-control all-fields" value="<?php echo set_value('mother_dob'); ?>" id="mother_dob" name="mother_dob" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('mother_dob'); ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="mother_citizenship"><?php echo $this->lang->line('citizenship'); ?></label><small class="req"> *</small>
                <input id="mother_citizenship" name="mother_citizenship" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('mother_citizenship'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('mother_citizenship'); ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="mother_religion"><?php echo $this->lang->line('religion'); ?></label><small class="req"> *</small>
                <input id="mother_religion" name="mother_religion" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('mother_religion'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('mother_religion'); ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="mother_highschool"><?php echo $this->lang->line('highschool'); ?></label><small class="req"> *</small>
                <input id="mother_highschool" name="mother_highschool" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('mother_highschool'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('mother_highschool'); ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="mother_college"><?php echo $this->lang->line('college'); ?></label><small class="req"> *</small>
                <input id="mother_college" name="mother_college" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('mother_college'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('mother_college'); ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="mother_college_course"><?php echo $this->lang->line('college_course'); ?></label><small class="req"> *</small>
                <input id="mother_college_course" name="mother_college_course" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('mother_college_course'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('mother_college_course'); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="mother_post_graduate"><?php echo $this->lang->line('post_graduate'); ?></label>
                <input id="mother_post_graduate" name="mother_post_graduate" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('mother_post_graduate'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('mother_post_graduate'); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="mother_post_course"><?php echo $this->lang->line('degree_attained'); ?></label>
                <input id="mother_post_course" name="mother_post_course" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('mother_post_course'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('mother_post_course'); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="mother_prof_affiliation"><?php echo $this->lang->line('prof_affil'); ?></label><small class="req"> *</small>
                <input id="mother_prof_affiliation" name="mother_prof_affiliation" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('mother_prof_affiliation'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('mother_prof_affiliation'); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="mother_prof_affiliation_position"><?php echo $this->lang->line('position_held'); ?></label><small class="req"> *</small>
                <input id="mother_prof_affiliation_position" name="mother_prof_affiliation_position" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('mother_prof_affiliation_position'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('mother_prof_affiliation_position'); ?></span>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label><?php echo $this->lang->line('tech_prof'); ?></label><small class="req"> *</small>
            <label class="radio-inline">
                <input type="radio" name="mother_tech_prof" <?php echo set_value('mother_tech_prof') == "yes" ? "checked" : ""; ?> value="yes"> <?php echo $this->lang->line('yes'); ?>
            </label>
            <label class="radio-inline">
                <input type="radio" name="mother_tech_prof" <?php echo set_value('mother_tech_prof') == "no" ? "checked" : ""; ?> value="no"> <?php echo $this->lang->line('no'); ?>
            </label>
            <label class="radio-inline">
                <input type="radio" name="mother_tech_prof" <?php echo set_value('mother_tech_prof') == "others" ? "checked" : ""; ?> value="others"> <?php echo $this->lang->line('other'); ?>
            </label>
            <span class="text-danger"><?php echo form_error('mother_tech_prof'); ?></span>
        </div>
        <div class="form-group col-md-6">
            <label><?php echo $this->lang->line('others_specify'); ?></label>
            <input id="mother_tech_prof_other" disabled name="mother_tech_prof_other" placeholder="If others, please specify" type="text" class="form-control all-fields" value="<?php echo set_value('mother_tech_prof_other'); ?>" autocomplete="off" />
        </div>
        <!-- End Mother section -->
        <div class="col-md-12">
            <hr class="style1">
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="marriage"><?php echo $this->lang->line('marriage'); ?>(<i>e.g. Catholic</i>)</label>
                <input id="marriage" name="marriage" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('marriage'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('marriage'); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="dom"><?php echo $this->lang->line('dom'); ?></label>
                <input type="date" class="form-control all-fields" value="<?php echo set_value('dom'); ?>" id="dom" name="dom" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('dom'); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="church"><?php echo $this->lang->line('church'); ?></label>
                <input id="church" name="church" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('church'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('church'); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="family_together"><?php echo $this->lang->line('family_together'); ?></label><small class="req"> *</small>
                <select class="form-control all-fields" name="family_together" id="family_together">
                    <option value="">Select</option>
                    <option value="yes" <?php if (strtolower(set_value('family_together')) == "yes") echo "selected"; ?>>Yes</option>
                    <option value="no" <?php if (strtolower(set_value('family_together')) == "no") echo "selected"; ?>>No</option>
                </select>
                <span class="text-danger"><?php echo form_error('family_together'); ?></span>
            </div>
        </div>

        <div class="col-md-8">
            <div class="form-group">
                <label><?php echo $this->lang->line('parents_away'); ?></label><small class="req"> *</small>
                <label class="radio-inline">
                    <input type="radio" name="parents_away" <?php echo set_value('parents_away') == "yes" ? "checked" : ""; ?> value="yes"> <?php echo $this->lang->line('yes'); ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="parents_away" <?php echo set_value('parents_away') == "no" ? "checked" : ""; ?> value="no"> <?php echo $this->lang->line('no'); ?>
                </label>
                <span class="text-danger"><?php echo form_error('parents_away'); ?></span>
            </div>
        </div>
        <div class="form-group col-md-4">
            <label><?php echo $this->lang->line('parents_away_state'); ?></label>
            <input id="parents_away_state" disabled name="parents_away_state" placeholder="If yes, state details" type="text" class="form-control all-fields" value="<?php echo set_value('parents_away_state'); ?>" autocomplete="off" />
        </div>

        <div class="col-md-6">

            <div class="form-group">
                <label><?php echo $this->lang->line('parent_civil_status'); ?></label><small class="req"> *</small>
                <label class="radio-inline">
                    <input type="radio" name="parents_civil_status" <?php echo set_value('parents_civil_status') == "married" ? "checked" : ""; ?> value="married"> <?php echo $this->lang->line('married'); ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="parents_civil_status" <?php echo set_value('parents_civil_status') == "separated" ? "checked" : ""; ?> value="separated"> <?php echo $this->lang->line('separated'); ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="parents_civil_status" <?php echo set_value('parents_civil_status') == "widow_er" ? "checked" : ""; ?> value="widow_er"> <?php echo $this->lang->line('widower'); ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="parents_civil_status" <?php echo set_value('parents_civil_status') == "others" ? "checked" : ""; ?> value="others"> <?php echo $this->lang->line('other'); ?>
                </label>
                <span class="text-danger"><?php echo form_error('parent_civil_status'); ?></span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label><?php echo $this->lang->line('others_specify'); ?></label>
                <input id="parents_civil_status_other" disabled name="parents_civil_status_other" placeholder="If others, please specify" type="text" class="form-control all-fields" value="<?php echo set_value('parents_civil_status_other'); ?>" autocomplete="off" />
            </div>
        </div>
    </div>
    <!-- End Parent Details -->

    <!-- Start Guardian Details -->
    <div class="row" id="guardiandetail1">
        <div class="wrapper">
            <h4 class="pagetitleh2"><?php echo $this->lang->line('guardian_detail'); ?></h4>
            <div class="line"></div>
        </div>
        <div class="form-group col-md-12">
            <label><?php echo $this->lang->line('if_guardian_is'); ?><small class="req"> *</small>&nbsp;&nbsp;&nbsp;</label>
            <label class="radio-inline">
                <input type="radio" name="guardian_is" <?php echo set_value('guardian_is') == "father" ? "checked" : ""; ?> value="father"> <?php echo $this->lang->line('father'); ?>
            </label>
            <label class="radio-inline">
                <input type="radio" name="guardian_is" <?php echo set_value('guardian_is') == "mother" ? "checked" : ""; ?> value="mother"> <?php echo $this->lang->line('mother'); ?>
            </label>
            <label class="radio-inline">
                <input type="radio" name="guardian_is" <?php echo set_value('guardian_is') == "other" ? "checked" : ""; ?> value="other"> <?php echo $this->lang->line('other'); ?>
            </label>
            <span class="text-danger"><?php echo form_error('guardian_is'); ?></span>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_name'); ?></label><small class="req"> *</small>
                <input id="guardian_name" name="guardian_name" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('guardian_name'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('guardian_name'); ?></span>
            </div>

        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_relation'); ?></label>
                <input id="guardian_relation" name="guardian_relation" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('guardian_relation'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('guardian_relation'); ?></span>
            </div>
        </div>
    </div>

    <div class="row" id="guardiandetail2">
        <div class="col-md-4">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_mobile_eg'); ?></label><small class="req"> *</small>
                <input id="guardian_phone" name="guardian_phone" placeholder="e.g. +639999999999" type="text" class="form-control all-fields" value="<?php echo set_value('guardian_phone'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('guardian_phone'); ?></span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_occupation'); ?></label><small class="req"> *</small>
                <input id="guardian_occupation" name="guardian_occupation" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('guardian_occupation'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('guardian_occupation'); ?></span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_email'); ?></label><small class="req"> *</small>
                <input id="guardian_email" name="guardian_email" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('guardian_email'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('guardian_email'); ?></span>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_address'); ?></label><small class="req"> *</small>
                <textarea id="guardian_address" name="guardian_address" placeholder="" class="form-control all-fields" rows="2"><?php echo set_value('guardian_address'); ?></textarea>
                <span class="text-danger"><?php echo form_error('guardian_address'); ?></span>
            </div>
        </div>
    </div>

    <div class="row" id="student_address">
        <div class="wrapper">
            <h4 class="pagetitleh2"><?php echo $this->lang->line('student_additional_details'); ?></h4>
            <div class="line"></div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="esc_grantee"><?php echo "ESC Grantee (G8 - G10)"; ?></label>
                <label class="radio-inline">
                    <input type="radio" name="esc_grantee" <?php echo set_value('esc_grantee') == "yes" ? "checked" : ""; ?> value="yes"> <?php echo $this->lang->line('yes'); ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="esc_grantee" <?php echo set_value('esc_grantee') == "no" ? "checked" : ""; ?> value="no"> <?php echo $this->lang->line('no'); ?>
                </label>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="voucher_recipient"><?php echo "Voucher Recipient"; ?></label>
                <label class="radio-inline">
                    <input type="radio" name="voucher_recipient" <?php echo set_value('voucher_recipient') == "yes" ? "checked" : ""; ?> value="yes"> <?php echo $this->lang->line('yes'); ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="voucher_recipient" <?php echo set_value('voucher_recipient') == "no" ? "checked" : ""; ?> value="no"> <?php echo $this->lang->line('no'); ?>
                </label>
            </div>
        </div>

        <div class="col-md-3">
            <!-- <div class="form-group form-inline"> -->
            <div class="form-group">
                <label for="age_as_of"><?php echo "Age as of Aug. " . $current_year; ?></label>
                <input id="age_as_of" name="age_as_of" placeholder="" type="number" min="0" class="form-control all-fields" value="<?php echo set_value('age_as_of'); ?>" />
                <span class="text-danger"><?php echo form_error('age_as_of'); ?></span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="nationality"><?php echo $this->lang->line('nationality'); ?></label>
                <input id="nationality" name="nationality" placeholder="" type="text" class="form-control all-fields" value="<?php echo set_value('nationality'); ?>" />
                <span class="text-danger"><?php echo form_error('nationality'); ?></span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="birth_place"><?php echo "Place of Birth"; ?></label>
                <textarea rows="3" id="birth_place" name="birth_place" placeholder="" class="form-control"><?php echo set_value('birth_place'); ?></textarea>
                <span class="text-danger"><?php echo form_error('birth_place'); ?></span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="present_school"><?php echo "Present School (currently enrolled)"; ?></label>
                <textarea rows="3" id="present_school" name="present_school" placeholder="" class="form-control"><?php echo set_value('present_school'); ?></textarea>
                <span class="text-danger"><?php echo form_error('present_school'); ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="present_school_address"><?php echo "School Address" ?></label><small class="req"> *</small>
                <textarea rows="3" id="present_school_address" name="present_school_address" placeholder="" class="form-control"><?php echo set_value('present_school_address'); ?></textarea>
                <span class="text-danger"><?php echo form_error('present_school_address'); ?></span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="enrolled_here_before"><?php echo "Has applicant been enrolled at " . $school_code; ?></label>
                <label class="radio-inline">
                    <input type="radio" name="enrolled_here_before" <?php echo set_value('enrolled_here_before') == "yes" ? "checked" : ""; ?> value="yes"> <?php echo $this->lang->line('yes'); ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="enrolled_here_before" <?php echo set_value('enrolled_here_before') == "no" ? "checked" : ""; ?> value="no"> <?php echo $this->lang->line('no'); ?>
                </label>
                <span class="text-danger"><?php echo form_error('enrolled_here_before'); ?></span>
            </div>

        </div>

        <div class="col-md-4">
            <div class="form-group">
                <input id="enrolled_here_before_year" disabled name="enrolled_here_before_year" placeholder="If yes, what school year?" type="text" class="form-control all-fields" value="<?php echo set_value('enrolled_here_before_year'); ?>" autocomplete="off" />
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <input id="enrolled_here_before_level" disabled name="enrolled_here_before_level" placeholder="What grade level?" type="text" class="form-control all-fields" value="<?php echo set_value('enrolled_here_before_level'); ?>" autocomplete="off" />
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="parents_alumnus"><?php echo "Are applicant's parents an alumnus/alumni of " . $school_code; ?></label>
                <label class="radio-inline">
                    <input type="radio" name="parents_alumnus" <?php echo set_value('parents_alumnus') == "yes" ? "checked" : ""; ?> value="yes"> <?php echo $this->lang->line('yes'); ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="parents_alumnus" <?php echo set_value('parents_alumnus') == "no" ? "checked" : ""; ?> value="no"> <?php echo $this->lang->line('no'); ?>
                </label>

                <label><?php echo "If yes, what batch?"; ?></label>
                <span class="text-danger"><?php echo form_error('parents_alumnus'); ?></span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <input id="father_alumnus_batch_gs" disabled name="father_alumnus_batch_gs" placeholder="Father Grade 6" type="text" class="form-control all-fields" value="<?php echo set_value('father_alumnus_batch_gs'); ?>" autocomplete="off" />
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <input id="mother_alumnus_batch_gs" disabled name="mother_alumnus_batch_gs" placeholder="Mother GS" type="text" class="form-control all-fields" value="<?php echo set_value('mother_alumnus_batch_gs'); ?>" autocomplete="off" />
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <input id="mother_alumnus_batch_hs" disabled name="mother_alumnus_batch_hs" placeholder="Mother HS" type="text" class="form-control all-fields" value="<?php echo set_value('mother_alumnus_batch_hs'); ?>" autocomplete="off" />
            </div>
        </div>

        <div class="col-md-5">
            <div class="form-group">
                <label for="has_internet"><?php echo "Do you have internet connection at home?"; ?></label>
                <label class="radio-inline">
                    <input type="radio" name="has_internet" <?php echo set_value('has_internet') == "yes" ? "checked" : ""; ?> value="yes"> <?php echo $this->lang->line('yes'); ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="has_internet" <?php echo set_value('has_internet') == "no" ? "checked" : ""; ?> value="no"> <?php echo $this->lang->line('no'); ?>
                </label>
                <span class="text-danger"><?php echo form_error('has_internet'); ?></span>
            </div>
        </div>

        <div class="col-md-7">
            <div class="form-group">
                <label><?php echo "Type of internet connection"; ?></label>
                <select class="form-control all-fields" name="type_of_internet" id="type_of_internet" disabled>
                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                    <option value="mobile" <?php if (strtolower(set_value('mobile')) == 'mobile') echo "selected"; ?>>Mobile Data</option>
                    <option value="dsl" <?php if (strtolower(set_value('dsl')) == 'dsl') echo "selected"; ?>>DSL</option>
                </select>
                <span class="text-danger"><?php echo form_error('type_of_internet'); ?></span>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label><?php echo "Siblings of the applicant starting from the eldest"; ?></label>
                <!-- <button class="pull-right" id="addRow">Add new row</button> -->
                <table id="siblings_admission" class="table table-bordered example" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width: 25%;">Name</th>
                            <th style="width: 10%;">Age</th>
                            <th style="width: 10%;">Civil Status</th>
                            <th style="width: 25%;">Grade Level/Occupation</th>
                            <th style="width: 30%;">Name of School/Company</th>
                            <th style="width: 30%;">Deceased</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < 5; $i++) { ?>
                            <tr>
                                <td><input class="form-control" type="text" name="sibling_name[]" value="<?php echo set_value('sibling_name[' . $i . ']') ?>"></td>
                                <td><input class="form-control" type="number" name="sibling_age[]" value="<?php echo set_value('sibling_age[' . $i . ']') ?>" min="0"></td>
                                <td>
                                    <select class="form-control" name="sibling_civil_status[]">
                                        <option value=""></option>
                                        <option value="single" <?php echo set_value('sibling_civil_status[' . $i . ']') == 'single' ? 'selected' : "" ?>>Single</option>
                                        <option value="married" <?php echo set_value('sibling_civil_status[' . $i . ']') == 'married' ? 'selected' : "" ?>>Married</option>
                                        <option value="separated" <?php echo set_value('sibling_civil_status[' . $i . ']') == 'separated' ? 'selected' : "" ?>>Separated</option>
                                        <option value="widower" <?php echo set_value('sibling_civil_status[' . $i . ']') == 'widower' ? 'selected' : "" ?>>Widower</option>
                                    </select>
                                </td>
                                <td><input class="form-control" type="text" name="sibling_glo[]" value="<?php echo set_value('sibling_glo[' . $i . ']') ?>"></td>
                                <td><input class="form-control" type="text" name="sibling_nsc[]" value="<?php echo set_value('sibling_nsc[' . $i . ']') ?>"></td>
                                <td align="center"><input class="checkbox" type="checkbox" name="sibling_dec[]" value="<?php echo set_value('sibling_dec[' . $i . ']') == "on" ? "checked" : ""; ?>"></td>
                            </tr>
                        <?php }
                        ?>
                    </tbody>
                    <tfoot></tfoot>
                </table>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="current_address"><?php echo $this->lang->line('current_address'); ?></label><small class="req"> *</small>
                <textarea rows="3" id="current_address" name="current_address" placeholder="" class="form-control"><?php echo set_value('current_address'); ?></textarea>
                <span class="text-danger"><?php echo form_error('current_address'); ?></span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="permanent_address"><?php echo $this->lang->line('permanent_address'); ?></label><small class="req"> *</small>
                <textarea rows="3" id="permanent_address" name="permanent_address" placeholder="" class="form-control"><?php echo set_value('current_address'); ?></textarea>
                <span class="text-danger"><?php echo form_error('permanent_address'); ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="guardian_address_is_current_address" name="guardian_address_is_current_address" <?php echo set_value('guardian_address_is_current_address') == "on" ? "checked" : ""; ?>>
                    <?php echo $this->lang->line('if_guardian_address_is_current_address'); ?>
                </label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="permanent_address_is_current_address" name="permanent_address_is_current_address" <?php echo set_value('permanent_address_is_current_address') == "on" ? "checked" : ""; ?>>
                    <?php echo $this->lang->line('if_permanent_address_is_current_address'); ?>
                </label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label><?php echo $this->lang->line('living_with_parents'); ?></label><small class="req"> *</small>
                <label class="radio-inline">
                    <input type="radio" name="living_with_parents" <?php echo set_value('living_with_parents') == "yes" ? "checked" : ""; ?> value="yes"> <?php echo $this->lang->line('yes'); ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="living_with_parents" <?php echo set_value('living_with_parents') == "no" ? "checked" : ""; ?> value="no"> <?php echo $this->lang->line('no'); ?>
                </label>
            </div>
            <div class="form-group">
                <label><?php echo $this->lang->line('living_with_parents_specify'); ?></label>
                <input id="living_with_parents_specify" disabled name="living_with_parents_specify" placeholder="If no, please specify" type="text" class="form-control all-fields" value="<?php echo set_value('living_with_parents_specify'); ?>" autocomplete="off" />
            </div>
        </div>
    </div>
    <!-- End Guardian Details -->


    <div class="row" id="deped">
        <div class="wrapper">
            <h4 class="pagetitleh2">Other Information</h4>
            <div class="line"></div>
        </div>

        <div class="form-group col-md-12">
            <label>Does the learner have special education needs? (i.e. physical, mental, developmental disability, medical condition, giftedness, among others)<small class="req"> *</small>&nbsp;&nbsp;&nbsp;</label>
            <label class="radio-inline">
                <input type="radio" name="has_special_needs" <?php echo set_value('has_special_needs') == "yes" ? "checked" : ""; ?> value="yes"> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="has_special_needs" <?php echo set_value('has_special_needs') == "no" ? "checked" : ""; ?> value="no"> No
            </label>

            <span class="text-danger"><?php echo form_error('has_special_needs'); ?></span>
        </div>

        <div class="form-group col-md-12">
            <label>Do you have any assistive technology devices available at home? (i.e. screen reader, Braille, DAISY)<small class="req"> *</small>&nbsp;&nbsp;&nbsp;</label>
            <label class="radio-inline">
                <input type="radio" name="has_assistive_device" <?php echo set_value('has_assistive_device') == "yes" ? "checked" : ""; ?> value="yes"> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="has_assistive_device" <?php echo set_value('has_assistive_device') == "no" ? "checked" : ""; ?> value="no"> No
            </label>

            <span class="text-danger"><?php echo form_error('has_assistive_device'); ?></span>
        </div>

        <div class="form-group col-md-6">
            <label>General Condition of Health<small class="req"> *</small>&nbsp;&nbsp;&nbsp;</label>
            <textarea rows="3" id="general_health_condition" name="general_health_condition" placeholder="" class="form-control"><?php echo set_value('general_health_condition'); ?></textarea>

            <span class="text-danger"><?php echo form_error('general_health_condition'); ?></span>
        </div>

        <div class="form-group col-md-6">
            <label>Common Health Complaints<small class="req"> *</small>&nbsp;&nbsp;&nbsp;</label>
            <textarea rows="3" id="health_complaints" name="health_complaints" placeholder="" class="form-control"><?php echo set_value('health_complaints'); ?></textarea>

            <span class="text-danger"><?php echo form_error('health_complaints'); ?></span>
        </div>

        <div class="form-group col-md-6">
            <label>Working from home due to community quarantine? (Father)<small class="req"> *</small>&nbsp;&nbsp;&nbsp;</label>
            <label class="radio-inline">
                <input type="radio" name="father_work_from_home" <?php echo set_value('father_work_from_home') == "yes" ? "checked" : ""; ?> value="yes"> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="father_work_from_home" <?php echo set_value('father_work_from_home') == "no" ? "checked" : ""; ?> value="no"> No
            </label>

            <span class="text-danger"><?php echo form_error('assistive_technology'); ?></span>
        </div>
        <div class="form-group col-md-6">
            <label>Working from home due to community quarantine? (Mother)<small class="req"> *</small>&nbsp;&nbsp;&nbsp;</label>
            <label class="radio-inline">
                <input type="radio" name="mother_work_from_home" <?php echo set_value('mother_work_from_home') == "yes" ? "checked" : ""; ?> value="yes"> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="mother_work_from_home" <?php echo set_value('mother_work_from_home') == "no" ? "checked" : ""; ?> value="no"> No
            </label>

            <span class="text-danger"><?php echo form_error('assistive_technology'); ?></span>
        </div>

        <div class="form-group col-md-6">
            <label>Working from home due to community quarantine? (Guardian)<small class="req"> *</small>&nbsp;&nbsp;&nbsp;</label>
            <label class="radio-inline">
                <input type="radio" name="guardian_work_from_home" <?php echo set_value('guardian_work_from_home') == "yes" ? "checked" : ""; ?> value="yes"> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="guardian_work_from_home" <?php echo set_value('guardian_work_from_home') == "no" ? "checked" : ""; ?> value="no"> No
            </label>

            <span class="text-danger"><?php echo form_error('assistive_technology'); ?></span>
        </div>

        <div class="form-group col-md-6">
            <label>Is your family a beneficiary of 4P's?<small class="req"> *</small>&nbsp;&nbsp;&nbsp;</label>
            <label class="radio-inline">
                <input type="radio" name="family_pppp" <?php echo set_value('family_pppp') == "yes" ? "checked" : ""; ?> value="yes"> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="family_pppp" <?php echo set_value('family_pppp') == "no" ? "checked" : ""; ?> value="no"> No
            </label>

            <span class="text-danger"><?php echo form_error('common_complaints'); ?></span>
        </div>

    </div>

    <!-- <div class="row" id="otherparentdetail">
        <div class="col-md-12"><h4 class="pagetitleh2"><?php echo $this->lang->line('other_parent_detail'); ?></h4></div>
        <div class="nav-tabs-custom theme-shadow">
            <ul class="nav nav-tabs" style="margin:15px">
                <li class="active"><a href="#father" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('father'); ?></a></li>
                <li class=""><a href="#mother" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('mother'); ?></a></li>
            </ul>
        </div>

        <div class="tab-content" style="margin:15px;">
            <div class="tab-pane active" id="father">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="father_company_name"><?php echo $this->lang->line('company'); ?></label>
                            <input id="father_company_name" name="father_company_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('father_company_name'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('father_company_name'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="father_company_position"><?php echo $this->lang->line('position'); ?></label>
                            <input id="father_company_position" name="father_company_position" placeholder="" type="text" class="form-control"  value="<?php echo set_value('father_company_position'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('father_company_position'); ?></span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="father_nature_of_business"><?php echo $this->lang->line('nature_of_business'); ?></label>
                            <input id="father_nature_of_business" name="father_nature_of_business" placeholder="" type="text" class="form-control"  value="<?php echo set_value('father_nature_of_business'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('father_nature_of_business'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="father_mobile"><?php echo $this->lang->line('mobile'); ?></label>
                            <input id="father_mobile" name="father_mobile" pattern="[+][0-9]{2}[0-9]{3}[0-9]{7}" placeholder="e.g. +639999999999" type="text" class="form-control"  value="<?php echo set_value('father_mobile'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('father_mobile'); ?></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="father_dob"><?php echo $this->lang->line('date_of_birth'); ?></label>
                            <input  type="text" class="form-control date2"  value="<?php echo set_value('father_dob'); ?>" id="father_dob" name="father_dob" readonly="readonly" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('father_dob'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="father_citizenship"><?php echo $this->lang->line('citizenship'); ?></label>
                            <input id="father_citizenship" name="father_citizenship" placeholder="" type="text" class="form-control"  value="<?php echo set_value('father_citizenship'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('father_citizenship'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="father_religion"><?php echo $this->lang->line('religion'); ?></label>
                            <input id="father_religion" name="father_religion" placeholder="" type="text" class="form-control"  value="<?php echo set_value('father_religion'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('father_religion'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="father_highschool"><?php echo $this->lang->line('highschool'); ?></label>
                            <input id="father_highschool" name="father_highschool" placeholder="" type="text" class="form-control"  value="<?php echo set_value('father_highschool'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('father_highschool'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="father_college"><?php echo $this->lang->line('college'); ?></label>
                            <input id="father_college" name="father_college" placeholder="" type="text" class="form-control"  value="<?php echo set_value('father_college'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('father_college'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="father_college_course"><?php echo $this->lang->line('college_course'); ?></label>
                            <input id="father_college_course" name="father_college_course" placeholder="" type="text" class="form-control"  value="<?php echo set_value('father_college_course'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('father_college_course'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="father_post_graduate"><?php echo $this->lang->line('post_graduate'); ?></label>
                            <input id="father_post_graduate" name="father_post_graduate" placeholder="" type="text" class="form-control"  value="<?php echo set_value('father_post_graduate'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('father_post_graduate'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="father_post_course"><?php echo $this->lang->line('degree_attained'); ?></label>
                            <input id="father_post_course" name="father_post_course" placeholder="" type="text" class="form-control"  value="<?php echo set_value('father_post_course'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('father_post_course'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="father_prof_affiliation"><?php echo $this->lang->line('prof_affil'); ?></label>
                            <input id="father_prof_affiliation" name="father_prof_affiliation" placeholder="" type="text" class="form-control"  value="<?php echo set_value('father_prof_affiliation'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('father_prof_affiliation'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="father_prof_affiliation_position"><?php echo $this->lang->line('position_held'); ?></label>
                            <input id="father_prof_affiliation_position" name="father_prof_affiliation_position" placeholder="" type="text" class="form-control"  value="<?php echo set_value('father_prof_affiliation_position'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('father_prof_affiliation_position'); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="form-group col-md-6">
                        <label><?php echo $this->lang->line('tech_prof'); ?>
                        <label class="radio-inline">
                            <input type="radio" name="father_tech_prof" <?php echo set_value('father_tech_prof') == "yes" ? "checked" : ""; ?> value="yes"> <?php echo $this->lang->line('yes'); ?>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="father_tech_prof" <?php echo set_value('father_tech_prof') == "no" ? "checked" : ""; ?> value="no"> <?php echo $this->lang->line('no'); ?>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="father_tech_prof" <?php echo set_value('father_tech_prof') == "others" ? "checked" : ""; ?> value="others"> <?php echo $this->lang->line('other'); ?>
                        </label>
                        <span class="text-danger"><?php echo form_error('father_tech_prof'); ?></span>
                    </div>
                    <div class="form-group col-md-6">
                        <input id="father_tech_prof_other" name="father_tech_prof_other" placeholder="If others, please specify" type="text" class="form-control"  value="<?php echo set_value('father_tech_prof_other'); ?>" autocomplete="off"/>
                    </div>
                </div>

            </div>

            <div class="tab-pane" id="mother">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="mother_company_name"><?php echo $this->lang->line('company'); ?></label>
                            <input id="mother_company_name" name="mother_company_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mother_company_name'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('mother_company_name'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="mother_company_position"><?php echo $this->lang->line('position'); ?></label>
                            <input id="mother_company_position" name="mother_company_position" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mother_company_position'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('mother_company_position'); ?></span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="mother_nature_of_business"><?php echo $this->lang->line('nature_of_business'); ?></label>
                            <input id="mother_nature_of_business" name="mother_nature_of_business" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mother_nature_of_business'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('mother_nature_of_business'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="mother_mobile"><?php echo $this->lang->line('mobile'); ?></label>
                            <input id="mother_mobile" name="mother_mobile" pattern="^\+(?:[0-9] ?){6,25}[0-9]$" placeholder="e.g. +639999999999" type="text" class="form-control"  value="<?php echo set_value('mother_mobile'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('mother_mobile'); ?></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="mother_dob"><?php echo $this->lang->line('date_of_birth'); ?></label>
                            <input  type="text" class="form-control date2"  value="<?php echo set_value('mother_dob'); ?>" id="mother_dob" name="mother_dob" readonly="readonly" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('mother_dob'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="mother_citizenship"><?php echo $this->lang->line('citizenship'); ?></label>
                            <input id="mother_citizenship" name="mother_citizenship" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mother_citizenship'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('mother_citizenship'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="mother_religion"><?php echo $this->lang->line('religion'); ?></label>
                            <input id="mother_religion" name="mother_religion" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mother_religion'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('mother_religion'); ?></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="mother_highschool"><?php echo $this->lang->line('highschool'); ?></label>
                            <input id="mother_highschool" name="mother_highschool" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mother_highschool'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('mother_highschool'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="mother_college"><?php echo $this->lang->line('college'); ?></label>
                            <input id="mother_college" name="mother_college" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mother_college'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('mother_college'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="mother_college_course"><?php echo $this->lang->line('college_course'); ?></label>
                            <input id="mother_college_course" name="mother_college_course" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mother_college_course'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('mother_college_course'); ?></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mother_post_graduate"><?php echo $this->lang->line('post_graduate'); ?></label>
                            <input id="mother_post_graduate" name="mother_post_graduate" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mother_post_graduate'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('mother_post_graduate'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mother_post_course"><?php echo $this->lang->line('degree_attained'); ?></label>
                            <input id="mother_post_course" name="mother_post_course" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mother_post_course'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('mother_post_course'); ?></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mother_prof_affiliation"><?php echo $this->lang->line('prof_affil'); ?></label>
                            <input id="mother_prof_affiliation" name="mother_prof_affiliation" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mother_prof_affiliation'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('mother_prof_affiliation'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mother_prof_affiliation_position"><?php echo $this->lang->line('position_held'); ?></label>
                            <input id="mother_prof_affiliation_position" name="mother_prof_affiliation_position" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mother_prof_affiliation_position'); ?>" autocomplete="off"/>
                            <span class="text-danger"><?php echo form_error('mother_prof_affiliation_position'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label><?php echo $this->lang->line('tech_prof'); ?>
                        <label class="radio-inline">
                            <input type="radio" name="mother_tech_prof" <?php echo set_value('mother_tech_prof') == "yes" ? "checked" : ""; ?> value="yes"> <?php echo $this->lang->line('yes'); ?>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="mother_tech_prof" <?php echo set_value('mother_tech_prof') == "no" ? "checked" : ""; ?> value="no"> <?php echo $this->lang->line('no'); ?>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="mother_tech_prof" <?php echo set_value('mother_tech_prof') == "other" ? "checked" : ""; ?> value="other"> <?php echo $this->lang->line('other'); ?>
                        </label>
                        <span class="text-danger"><?php echo form_error('mother_tech_prof'); ?></span>
                    </div>
                    <div class="form-group col-md-6">
                    <input id="mother_tech_prof_other" name="mother_tech_prof_other" placeholder="If others, please specify" type="text" class="form-control"  value="<?php echo set_value('mother_tech_prof_other'); ?>" autocomplete="off"/>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <div class="form-group pull-right">

                    <p class="text-justify"><input type="checkbox" value="" id="iagree">&nbsp;<?php $this->setting_model->getDataPrivacyChkboxText(); ?></p>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group pull-right">
                <!-- <label><input type="checkbox" value="" id="iagree"> I agree with the <a href="#" onclick="ShowPrivacyPolicy()">Data Privacy Policy</a></label> -->
                <?php
                //joeven
                $exceptions = array("tlc-nbs");
                $school_code = explode('.', $HTTP_HOST)[0];
                //joeven 
                ?>
                <?php if (!in_array($school_code, $exceptions)) : ?>
                    <button disabled='disabled' id="save_admission" type="submit" class="onlineformbtn">Next</button>
                <?php else : ?>
                    <button disabled='disabled' id="save_admission" type="submit" class="onlineformbtn">Submit</button>
                <?php endif; ?>

            </div>
        </div>
    </div>
    <!--./row-->
</form>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // $('#father_tech_prof_other').fadeOut();
        // $('#mother_tech_prof_other').fadeOut();

        $('.date2').datepicker({
            "autoclose": true,
            "todayHighlight": true,
            "setDate": new Date()
        });

        if ($('#enrollment_type').val() == 'old') {
            $('#studentidnumber').prop('disabled', false);
            $('#firstname').prop('readonly', true);
            $('#middlename').prop('readonly', true);
            $('#lastname').prop('readonly', true);
            $('#gender').prop('readonly', true);
            $('#dob').prop('readonly', true);
            $('#id_number_input').fadeIn();
            $("#student_address").slideUp();
            $('#parentdetail').slideUp();
            $('#guardiandetail1').slideUp();
            $('#guardiandetail2').slideUp();
            $('#otherparentdetail').slideUp();
            $('#deped').slideUp();
        } else $('#id_number_input').fadeOut();
    });

    $('input:radio[name="guardian_is"]').change(
        function() {
            if ($(this).is(':checked')) {
                var value = $(this).val();
                if (value === "father") {
                    $('#guardian_name').val($('#father_name').val());
                    $('#guardian_phone').val($('#father_phone').val());
                    $('#guardian_occupation').val($('#father_occupation').val());
                    $('#guardian_relation').val("Father");
                } else if (value === "mother") {
                    $('#guardian_name').val($('#mother_name').val());
                    $('#guardian_phone').val($('#mother_phone').val());
                    $('#guardian_occupation').val($('#mother_occupation').val());
                    $('#guardian_relation').val("Mother");
                } else {
                    $('#guardian_name').val("");
                    $('#guardian_phone').val("");
                    $('#guardian_occupation').val("");
                    $('#guardian_relation').val("");
                }
            }
        }
    );

    $('input:radio[name="father_tech_prof"]').change(
        function() {
            if ($(this).is(':checked')) {
                var value = $(this).val();
                if (value === "others") {
                    $('#father_tech_prof_other').prop('disabled', false);
                } else {
                    $('#father_tech_prof_other').prop('disabled', true);
                }
            }
        }
    );

    $('input:radio[name="mother_tech_prof"]').change(
        function() {
            if ($(this).is(':checked')) {
                var value = $(this).val();
                if (value === "others") {
                    $('#mother_tech_prof_other').prop('disabled', false);
                } else {
                    $('#mother_tech_prof_other').prop('disabled', true);
                }
            }
        }
    );

    $('input:radio[name="parents_away"]').change(
        function() {
            if ($(this).is(':checked')) {
                var value = $(this).val();
                if (value === "yes") {
                    $('#parents_away_state').prop('disabled', false);
                } else {
                    $('#parents_away_state').prop('disabled', true);
                }
            }
        }
    );

    $('input:radio[name="parents_civil_status"]').change(
        function() {
            if ($(this).is(':checked')) {
                var value = $(this).val();
                if (value === "others") {
                    $('#parents_civil_status_other').prop('disabled', false);
                } else {
                    $('#parents_civil_status_other').prop('disabled', true);
                }
            }
        }
    );

    $("#iagree").change(function() {
        if (this.checked) {
            $('#save_admission').prop('disabled', false);
        } else {
            $('#save_admission').prop('disabled', true);
        }
    });

    $('#guardian_address_is_current_address').click(function() {
        if ($(this).is(':checked')) {
            $('#permanent_address_is_current_address').prop("checked", false);
        }
    });

    $('#permanent_address_is_current_address').click(function() {
        if ($(this).is(':checked')) {
            $('#guardian_address_is_current_address').prop("checked", false);
        }
    });

    $('input:radio[name="living_with_parents"]').change(
        function() {
            if ($(this).is(':checked')) {
                var value = $(this).val();
                if (value === "no") {
                    $('#living_with_parents_specify').prop('disabled', false);
                } else {
                    $('#living_with_parents_specify').prop('disabled', true);
                }
            }
        }
    );

    $('input:radio[name="has_siblings_enrolled"]').change(
        function() {
            if ($(this).is(':checked')) {
                var value = $(this).val();
                if (value === "yes") {
                    $('#siblings_specify').prop('disabled', false);
                } else {
                    $('#siblings_specify').prop('disabled', true);
                }
            }
        }
    );

    $('input:radio[name="enrolled_here_before"]').change(
        function() {
            if ($(this).is(':checked')) {
                var value = $(this).val();
                if (value === "yes") {
                    $('#enrolled_here_before_year').prop('disabled', false);
                    $('#enrolled_here_before_level').prop('disabled', false);
                } else {
                    $('#enrolled_here_before_year').prop('disabled', true);
                    $('#enrolled_here_before_level').prop('disabled', true);
                }
            }
        }
    );

    $('input:radio[name="parents_alumnus"]').change(
        function() {
            if ($(this).is(':checked')) {
                var value = $(this).val();
                if (value === "yes") {
                    $('#father_alumnus_batch_gs').prop('disabled', false);
                    $('#mother_alumnus_batch_gs').prop('disabled', false);
                    $('#mother_alumnus_batch_hs').prop('disabled', false);
                } else {
                    $('#father_alumnus_batch_gs').prop('disabled', true);
                    $('#mother_alumnus_batch_gs').prop('disabled', true);
                    $('#mother_alumnus_batch_hs').prop('disabled', true);
                }
            }
        }
    );

    $('input:radio[name="has_internet"]').change(
        function() {
            if ($(this).is(':checked')) {
                var value = $(this).val();
                if (value === "yes") {
                    $('#type_of_internet').prop('disabled', false);
                } else {
                    $('#type_of_internet').prop('disabled', true);
                }
            }
        }
    );

    function SetClassName(sel) {
        var text = sel.options[sel.selectedIndex].text;
        $("#classname").val(text);
    }

    function DoOnChange(sel) {
        $('.text-danger').html('');
        $('.alert').alert('close');

        ClearEntries();

        if (sel.value == "old") {
            $('#studentidnumber').prop('disabled', false);
            $('#lrn_no').prop('readonly', true);
            $('#firstname').prop('readonly', true);
            $('#middlename').prop('readonly', true);
            $('#lastname').prop('readonly', true);
            $('#gender').prop('readonly', true);
            // $('#dob').prop('readonly', true);
            $('#id_number_input').fadeIn();
            $("#student_address").slideUp();
            $('#parentdetail').slideUp();
            $('#guardiandetail1').slideUp();
            $('#guardiandetail2').slideUp();
            $('#otherparentdetail').slideUp();
            $('#deped').slideUp();
        } else if (sel.value == "old_new") {
            $('#studentidnumber').prop('disabled', false);
            $('#lrn_no').prop('readonly', false);
            $('#firstname').prop('readonly', false);
            $('#middlename').prop('readonly', false);
            $('#lastname').prop('readonly', false);
            $('#gender').prop('readonly', false);
            // $('#dob').prop('readonly', false);
            $('#id_number_input').fadeIn();
            $("#student_address").slideDown();
            $('#parentdetail').slideDown();
            $('#guardiandetail1').slideDown();
            $('#guardiandetail2').slideDown();
            $('#otherparentdetail').slideDown();
            $('#deped').slideDown();
        } else {
            $('#studentidnumber').prop('disabled', true);
            $('#lrn_no').prop('readonly', false);
            $('#firstname').prop('readonly', false);
            $('#middlename').prop('readonly', false);
            $('#lastname').prop('readonly', false);
            $('#gender').prop('readonly', false);
            // $('#dob').prop('readonly', false);
            $('#id_number_input').fadeOut();
            $("#student_address").slideDown();
            $('#parentdetail').slideDown();
            $('#guardiandetail1').slideDown();
            $('#guardiandetail2').slideDown();
            $('#otherparentdetail').slideDown();
            $('#deped').slideDown();

        }
    }

    function AutoFillDetails(data) {
        $("#accountid").val('');
        $('#lrn_no').val('');
        $('#firstname').val('');
        $('#middlename').val('');
        $('#lastname').val('');
        $('#gender').val('');
        $('#dob').val('');
        $("#accountid").val(data.id);
        $('#lrn_no').val(data.lrn_no);
        $('#firstname').val(data.firstname);
        $('#middlename').val(data.middlename);
        $('#lastname').val(data.lastname);
        $('#gender').val(data.gender);
        $('#dob').val(data.dob);
    }

    function ClearRadio(name) {
        const chbx = document.getElementsByName(name);

        for (let i = 0; i < chbx.length; i++) {
            chbx[i].checked = false;
        }
    }

    function ClearEntries() {
        $('.all-fields').val('');
        ClearRadio("father_tech_prof");
        ClearRadio("mother_tech_prof");
        ClearRadio("parents_away");
        ClearRadio("parents_civil_status");
        ClearRadio("guardian_is");
    }

    // function clearAllInputs() {
    //     $('#form1').find(':input').each(function() {
    //         if(this.type == 'submit'){
    //             //do nothing
    //         }
    //         else if(this.type == 'checkbox' || this.type == 'radio') {
    //             this.checked = false;
    //         }
    //         else if(this.type == 'file'){
    //             var control = $(this);
    //             control.replaceWith( control = control.clone( true ));
    //         }else{
    //             $(this).val('');
    //         }
    //     });
    // }

    function ShowGuidelines() {
        $('#admissionguidelines').modal("show");
    }

    function ShowPrivacyPolicy() {
        $('#privacyPolicy').modal("show");
    }

    function ShowTermsAndCondition() {
        $('#termsCondition').modal("show");
    }

    $('#studentidnumber').keyup(function(e) {
        var key = e.which;
        var searchby = $('input[name="search_by"]:checked').val();

        if (searchby == 'lrn') {
            if ($("#studentidnumber").val() != '') {
                var url = '<?php echo base_url(); ?>' + 'welcome/GetStudentDetails/' + $("#studentidnumber").val();
                $.get(url)
                    .done(function(data) {
                        //alert( "Data Loaded: " + data );
                        if (data != "null")
                            AutoFillDetails(JSON.parse(data));
                        else {
                            $("#accountid").val('');
                            $('#lrn_no').val('');
                            $('#firstname').val('');
                            $('#middlename').val('');
                            $('#lastname').val('');
                            $('#gender').val('');
                            $('#dob').val('');
                        }
                    });
            }
        } else if (searchby == 'name') {
            if ($("#studentidnumber").val() != '') {
                $("#studentidnumber").autocomplete({
                    autofocus: true,
                    source: function(request, response) {
                        // Fetch data
                        $.ajax({
                            url: '<?php echo base_url() . "welcome/AutoCompleteStudentNameForAdmission"; ?>',
                            type: 'get',
                            dataType: "json",
                            data: {
                                search: request.term
                            },
                            success: function(data) {
                                response(data);
                            }
                        });
                    },
                    select: function(event, ui) {
                        $("#studentidnumber").val(ui.item.label.substr(0, ui.item.label.indexOf(' (')));

                        var url = '<?php echo base_url(); ?>' + 'welcome/GetStudentDetails/' + ui.item.value;
                        $.get(url)
                            .done(function(data) {
                                AutoFillDetails(JSON.parse(data));
                            });

                        return false;
                    }
                }).keyup(function() {
                    //$('#form1')[0].reset();
                });
            }
        }
    });

    $('input:radio[name="search_by"]').change(
        function() {
            if ($(this).is(':checked')) {
                var value = $(this).val();
                $("#accountid").val('');
                $('#lrn_no').val('');
                $('#firstname').val('');
                $('#middlename').val('');
                $('#lastname').val('');
                $('#gender').val('');
                $('#dob').val('');

                if (value == "lrn") {
                    // $("#studentidnumber").removeClass('ui-autocomplete-input');
                    $("#studentidnumber").autocomplete({
                        disabled: true
                    });
                    $("#studentidnumber").attr("placeholder", "Enter Student ID or LRN").val("").focus().blur();
                } else {
                    $("#studentidnumber").autocomplete({
                        disabled: false
                    });
                    $("#studentidnumber").addClass('ui-autocomplete-input');
                    $("#studentidnumber").attr("placeholder", "Enter Student Name").val("").focus().blur();
                }
            }
        }
    );

    // $('#privacyPolicy').on('shown.bs.modal', function() {
    //     $(this).find('iframe').attr('src','http://www.google.com')
    // });

    //$('input[name="genderS"]:checked').val();

    $("input[type='submit']").one('click', function(event) {
        $(this).preventDefault();
    });

    $("#studentidnumber").on("input", function() {
        $("#accountid").val('');
        $('#lrn_no').val('');
        $('#firstname').val('');
        $('#middlename').val('');
        $('#lastname').val('');
        $('#gender').val('');
        $('#dob').val('');
    });
</script>
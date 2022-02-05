<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
         <i class="fa fa-download"></i> <?php echo $this->lang->line('download_center'); ?>
      </h1>

   </section>

   <!-- Main content -->
   <section class="content">
      <div class="row">
         <?php
         if ($this->rbac->hasPrivilege('upload_content', 'can_add')) { ?>
            <div class="col-md-12">
               <!-- Horizontal Form -->
               <div class="box removeboxmius">
                  <div class="box-header ptbnull"></div>
                  <div class="box-header with-border">
                     <h3 class="box-title">Create Grades</h3>
                  </div><!-- /.box-header -->
                  <!-- form start -->

                  <form id="form1" action="<?php echo site_url('lms/grading/create') ?>" id="lesson" name="employeeform" method="post" enctype='multipart/form-data' accept-charset="utf-8">
                     <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?>
                           <?php echo $this->session->flashdata('msg') ?>
                        <?php } ?>
                        <?php echo $this->customlib->getCSRF(); ?>

                        <div class="form-group">
                           <label for="exampleInputEmail1">Grade/Level</label><small class="req"> *</small>
                           <select autofocus="" id="grade_id" name="grade" placeholder="" type="text" class="form-control">
                              <option value=""><?php echo $this->lang->line('select'); ?></option>
                              <?php foreach ($classes as $key => $value) : ?>
                                 <option value="<?php echo $value['id'] ?>"><?php echo $value['class'] ?></option>
                              <?php endforeach; ?>
                           </select>
                           <span class="text-danger"><?php echo form_error('content_title'); ?></span>
                        </div>

                        <div class="form-group">
                           <label for="exampleInputEmail1">Section</label><small class="req"> *</small>
                           <select autofocus="" id="section_id" name="section" placeholder="" type="text" class="form-control">
                              <option value=""><?php echo $this->lang->line('select'); ?></option>
                           </select>
                           <span class="text-danger"><?php echo form_error('content_title'); ?></span>
                        </div>

                        <div class="form-group">
                           <label for="exampleInputEmail1">Subject</label><small class="req"> *</small>
                           <select autofocus="" id="subject_id" name="subject" placeholder="" type="text" class="form-control">
                              <?php foreach ($subjects as $key => $value) : ?>
                                 <option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                              <?php endforeach; ?>
                           </select>
                           <span class="text-danger"><?php echo form_error('content_title'); ?></span>
                        </div>

                        <div class="form-group">
                           <label for="exampleInputEmail1">Template</label><small class="req"> *</small>
                           <select autofocus="" id="template_id" name="template" placeholder="" type="text" class="form-control">

                              <?php if (strtolower($schoolcode) == "scholaangelicus" || strtolower($schoolcode) == "mcam" || strtolower($schoolcode) == "htspg" || strtolower($schoolcode) == "htsmk" || strtolower($schoolcode) == "htslipa") : ?>
                                 <option value="saioriginal">Original</option>
                              <?php elseif (strtolower($schoolcode) == "lpms") : ?>
                                 <option value="lpmsoriginal">Original</option>
                                 <option value="lpmsmapeh">MAPEH</option>
                                 <option value="lpmsrobotics">Robotics</option>
                                 <option value="lpmstle">TLE</option>
                                 <option value="lpmsconduct">Conduct</option>
                              <?php elseif (strtolower($schoolcode) == "ssapamp") : ?>
                                 <option value="original">Original</option>
                                 <option value="ssapamp_cle">CLE - Pre School</option>
                                 <option value="ssapamp_math">Math - Pre School</option>
                                 <option value="ssapamp_mapeh">MAPEH - Pre School</option>
                                 <option value="ssapamp_reading">Reading - Pre School</option>
                                 <option value="ssapamp_writing">Writing - Pre School</option>
                              <?php else : ?>
                                 <option value="original">Original</option>
                                 <option value="mapeh">MAPEH</option>
                                 <option value="penmanship">Penmanship</option>
                                 <option value="epp_comp">EPP/COMP</option>
                                 <option value="cled">CLEd</option>
                              <?php endif; ?>

                              <?php if ($this->school_code == "csl") : ?>
                                 <option value="csl_college">CSL College</option>
                                 <option value="csl_elem">CSL Elem & SHS</option>
                              <?php endif; ?>
                           </select>
                           <span class="text-danger"><?php echo form_error('content_title'); ?></span>
                        </div>

                        <?php if (strtolower($schoolcode) == "lpms") : ?>
                           <div class="form-group">
                              <label for="exampleInputEmail1">Term</label><small class="req"> *</small>
                              <select autofocus="" id="quarter_id" name="quarter" placeholder="" type="text" class="form-control">
                                 <?php foreach ($quarters as $key => $value) : ?>
                                    <option value="<?php echo $value['id'] ?>"><?php echo $value['description'] ?></option>
                                 <?php endforeach; ?>
                              </select>
                              <span class="text-danger"><?php echo form_error('content_title'); ?></span>
                           </div>
                        <?php else : ?>
                           <div class="form-group">
                              <label for="exampleInputEmail1">Term</label><small class="req"> *</small>
                              <select autofocus="" id="quarter_id" name="quarter" placeholder="" type="text" class="form-control">
                                 <?php foreach ($quarters as $key => $value) : ?>
                                    <option value="<?php echo $value['id'] ?>"><?php echo $value['description'] ?></option>
                                 <?php endforeach; ?>
                              </select>
                              <span class="text-danger"><?php echo form_error('content_title'); ?></span>
                           </div>
                        <?php endif ?>
                     </div><!-- /.box-body -->

                     <div class="box-footer">
                        <?php if (strtolower($schoolcode) == "lpms") : ?>
                           <button type="button" id="swh" class="btn btn-info pull-left"><?php echo 'Create Study and Work Habits' ?></button>
                        <?php endif ?>
                        <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                     </div>
                  </form>
               </div>
            </div>
         <?php } ?>
      </div>
      <div class="row">
         <!-- left column -->

         <!-- right column -->
         <div class="col-md-12">

            <!-- Horizontal Form -->

            <!-- general form elements disabled -->

         </div>
         <!--/.col (right) -->
      </div> <!-- /.row -->
   </section><!-- /.content -->
</div><!-- /.content-wrapper -->


<script>
   var class_id;
   var base_url = '<?php echo base_url() ?>';

   function getSectionByClass(class_id, section_id = -1) {
      if (class_id != "" && section_id != "") {
         $('#section_id').html("");
         $('#subject_id').html("");

         var base_url = '<?php echo base_url() ?>';
         var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

         $.ajax({
            type: "GET",
            url: base_url + "sections/getByClass",
            data: {
               'class_id': class_id
            },
            dataType: "json",
            beforeSend: function() {
               $('#section_id').addClass('dropdownloading');
            },
            success: function(data) {
               $.each(data, function(i, obj) {
                  var sel = "";
                  if (section_id == obj.section_id) {
                     sel = "selected";
                  }
                  div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
               });
               $('#section_id').append(div_data);
            },
            complete: function() {
               $('#section_id').removeClass('dropdownloading');
            }
         });
      }
   }

   function getSubjectList(class_id, section_id, subject_id = -1) {
      if (class_id != "" && section_id != "") {
         $('#subject_id').html("");

         var base_url = '<?php echo base_url() ?>';
         var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

         $.ajax({
            type: "GET",
            url: base_url + "lms/grading/get_subject_list",
            data: {
               'grade_level_id': class_id,
               'section_id': section_id
            },
            dataType: "json",
            beforeSend: function() {
               $('#subject_id').addClass('dropdownloading');
            },
            success: function(data) {
               $.each(data, function(i, obj) {
                  var sel = "";
                  if (subject_id == obj.subject_id) {
                     sel = "selected";
                  }
                  div_data += "<option value=" + obj.subject_id + " " + sel + ">" + obj.subject + "</option>";
               });

               $('#subject_id').append(div_data);
            },
            error: function(response) {
               $('#subject_id').html(response); // display error response from the PHP script
            },
            complete: function() {
               $('#subject_id').removeClass('dropdownloading');
            }
         });
      }
   }


   $('.filter').select2();

   function check_class(lesson_id) {
      var url = "<?php echo base_url('lms/lesson/check_class/'); ?>" + lesson_id;

      $.ajax({
         url: url,
         method: "POST",
      }).done(function(data) {
         var parsed_data = JSON.parse(data);
         alert("If the zoom or google meet wont appear please turn off the pop-up blocker on your browser.");
         if (parsed_data.video != "") {
            window.open(parsed_data.video, "_blank");
         }
         if (parsed_data.lms != "") {
            window.location.href = parsed_data.lms;
         }
      });
   }

   $(document).ready(function() {
      var class_id = $('#grade_id').val();
      var section_id = '<?php echo set_value('section_id') ?>';

      getSectionByClass(class_id, section_id);

      $('.detail_popover').popover({
         placement: 'right',
         trigger: 'hover',
         container: 'body',
         html: true,
         content: function() {
            return $(this).closest('td').find('.fee_detail_popover').html();
         }
      });

      $(".lesson_status").change(function() {
         var lesson_status_val = $(this).val();

         alert($(this).val());
      });
   });

   $(document).on('change', '#grade_id', function(e) {
      $('#section_id').html("");
      var class_id = $(this).val();

      getSectionByClass(class_id);
   });

   $(document).on('change', '#section_id', function(e) {
      var class_id = $('#grade_id').val();
      var section_id = $('#section_id').val();
      $('#subject_id').html("");

      getSubjectList(class_id, section_id);
   });

   $("#template_id").change(function() {
      // $('.filter2').select2();
      // $('.filter2').select2('destroy');

      // if ($(this).val() == "csl_college") {
      //    $("#quarter_id").find("option[value='1']").text("Prelim");
      //    $("#quarter_id").find("option[value='2']").text("Midterm");
      //    $("#quarter_id").find("option[value='3']").text("Semifinal");
      //    $("#quarter_id").find("option[value='4']").text("Final");
      // } 
      // else if ($(this).val() == "lpmsconduct" || $(this).val() == "lpmsoriginal") {
      //    $("#quarter_id").find("option[value='1']").text("1st");
      //    $("#quarter_id").find("option[value='2']").text("2nd");
      //    $("#quarter_id").find("option[value='3']").text("3rd");
      // } else {
      //    $("#quarter_id").find("option[value='1']").text("1st");
      //    $("#quarter_id").find("option[value='2']").text("2nd");
      //    $("#quarter_id").find("option[value='3']").text("3rd");
      //    $("#quarter_id").find("option[value='4']").text("4th");
      // }
      // $('.filter2').select2();
   });

   $("#swh").click(function() {
      window.location.href = '<?php echo base_url('lms/grading/lpms_swh'); ?>';
   });
</script>
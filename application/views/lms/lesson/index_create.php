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
         if ($this->rbac->hasPrivilege('upload_content', 'can_add')) {
         ?>
            <div class="col-md-12">
               <!-- Horizontal Form -->
               <div class="box box-primary">
                  <div class="box-header with-border">
                     <h3 class="box-title">Create <?php echo $this->lang->line('lesson'); ?></h3>
                  </div><!-- /.box-header -->
                  <!-- form start -->

                  <form id="form1" action="<?php echo site_url('lms/lesson/save') ?>" id="lesson" name="employeeform" method="post" enctype='multipart/form-data' accept-charset="utf-8">
                     <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?>
                           <?php echo $this->session->flashdata('msg') ?>
                        <?php } ?>
                        <?php echo $this->customlib->getCSRF(); ?>
                        <div class="form-group">
                           <label for="exampleInputEmail1">Lesson Title</label><small class="req"> *</small>
                           <input autofocus="" id="content_title" name="content_title" placeholder="" type="text" class="form-control" value="<?php echo set_value('content_title'); ?>" />
                           <span class="text-danger"><?php echo form_error('content_title'); ?></span>
                        </div>
                        <div class="form-group">
                           <label for="exampleInputEmail1">Subject</label><small class="req"> *</small>
                           <select autofocus="" id="subject_id" name="subject" placeholder="" type="text" class="form-control filter">
                              <?php foreach ($subjects as $key => $value) : ?>
                                 <option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                              <?php endforeach; ?>
                           </select>
                           <span class="text-danger"><?php echo form_error('content_title'); ?></span>
                        </div>

                        <div class="form-group">
                           <label for="exampleInputEmail1">Grade/Level</label><small class="req"> *</small>
                           <select autofocus="" id="grade_id" name="grade" placeholder="" type="text" class="form-control filter">
                              <?php foreach ($classes as $key => $value) : ?>
                                 <option value="<?php echo $value['id'] ?>"><?php echo $value['class'] ?></option>
                              <?php endforeach; ?>
                           </select>
                           <span class="text-danger"><?php echo form_error('content_title'); ?></span>
                        </div>

                        <div class="form-group">
                           <label for="exampleInputEmail1">Education Level</label><small class="req"> *</small>
                           <select autofocus="" id="" name="education_level" placeholder="" type="text" class="form-control">
                              <option value="pre_school">Pre-School</option>
                              <option value="grade_school">Grade School</option>
                              <option value="junior">Junior Highschool</option>
                              <option value="senior">Senior Highschool</option>
                              <option value="tertiary">Tertiary</option>
                              <option value="all_levels">All Levels</option>
                           </select>
                           <span class="text-danger"><?php //echo form_error('content_title'); 
                                                      ?></span>
                        </div>



                        <div class="form-group">
                           <label for="exampleInputEmail1">Term</label><small class="req"> *</small>
                           <select autofocus="" id="" name="term" placeholder="" type="text" class="form-control">
                              <option value="1">1st Term</option>
                              <option value="2">2nd Term</option>
                              <option value="3">3rd Term</option>
                              <option value="4">4th Term</option>
                           </select>
                           <span class="text-danger"><?php echo form_error('content_title'); ?></span>
                        </div>


                     </div><!-- /.box-body -->

                     <div class="box-footer">
                        <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                     </div>
                  </form>
               </div>

            </div>
            <!--/.col (right) -->
            <!-- left column -->
            <!-- <?php } ?> -->



            <!-- right column -->

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
   var teacher_id = <?php echo $accountid; ?>;
   var role_id = <?php echo $real_role; ?>;

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

   function getSubjects(grade_id, subject_id, teacher_id, role_id, subject_timetable_id) {
      if (grade_id != "" && subject_id != '') {
         $('#subject_timetable_id').html("");
         var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
         $.ajax({
            type: "POST",
            url: baseurl + "admin/subjectgroup/getSubjectByGradeAndTeacher",
            data: {
               'grade_id': grade_id,
               'subject_id': subject_id,
               'teacher_id': teacher_id,
               'role_id': role_id
            },
            dataType: "json",
            success: function(data) {
               $('#subject_timetable_id').html("");

               $.each(data, function(i, obj) {
                  var select = "";
                  if (subject_timetable_id == obj.id) {
                     var select = "selected=selected";
                  }

                  div_data += "<option value=" + obj.id + " " + select + ">" + obj.subject_name + " (" + obj.day + " " + obj.time_from + " - " + obj.time_to + ") (" + obj.name + " " + obj.surname + ")" + "</option>";
               });
               $('#subject_timetable_id').append(div_data);
            }
         });
      }
   }

   $(document).on('change', '#grade_id', function(e) {
      var grade_id = $('#grade_id').val();
      var subject_id = $('#subject_id').val();
      getSubjects(grade_id, subject_id, teacher_id, role_id, 0);
   });

   $(document).on('change', '#subject_id', function(e) {
      var grade_id = $('#grade_id').val();
      var subject_id = $('#subject_id').val();
      getSubjects(grade_id, subject_id, teacher_id, role_id, 0);
   });
</script>
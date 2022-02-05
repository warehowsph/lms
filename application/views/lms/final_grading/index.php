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
                     <h3 class="box-title">Final Grade Encoding</h3>
                  </div><!-- /.box-header -->
                  <!-- form start -->

                  <form id="form1" action="" id="lesson" method="post" enctype='multipart/form-data' accept-charset="utf-8">
                     <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?>
                           <?php echo $this->session->flashdata('msg') ?>
                        <?php } ?>
                        <?php echo $this->customlib->getCSRF(); ?>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label for="exampleInputEmail1">Grade/Level</label><small class="req"> *</small>
                              <select autofocus="" id="grade_id" name="grade" placeholder="" type="text" class="form-control filter" required="">
                                 <option value="">Select Class</option>
                                 <?php foreach ($classes as $key => $value) : ?>
                                    <option value="<?php echo $value['id'] ?>" <?php if ($_REQUEST['grade'] == $value['id']) : echo 'selected';
                                                                                 endif; ?>><?php echo $value['class'] ?></option>
                                 <?php endforeach; ?>
                              </select>
                              <span class="text-danger"><?php echo form_error('content_title'); ?></span>
                           </div>
                        </div>

                        <div class="col-md-3">
                           <div class="form-group">
                              <label for="exampleInputEmail1">Section</label><small class="req"> *</small>
                              <select autofocus="" id="section_id" name="section" placeholder="" type="text" class="form-control filter" required="">
                                 <option value="">Select Section</option>
                                 <?php foreach ($sections as $key => $value) : ?>
                                    <option value="<?php echo $value['id'] ?>" <?php if ($_REQUEST['section'] == $value['id']) : echo 'selected';
                                                                                 endif; ?>><?php echo $value['section'] ?></option>
                                 <?php endforeach; ?>
                              </select>
                              <span class="text-danger"><?php echo form_error('content_title'); ?></span>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label for="exampleInputEmail1">Subject</label><small class="req"> *</small>
                              <select autofocus="" id="subject_id" name="subject" placeholder="" type="text" class="form-control filter" required="">
                                 <option value="">Select Subject</option>
                                 <?php foreach ($subjects as $key => $value) : ?>
                                    <option value="<?php echo $value['id'] ?>" <?php if ($_REQUEST['subject'] == $value['id']) : echo 'selected';
                                                                                 endif; ?>><?php echo $value['name'] ?></option>
                                 <?php endforeach; ?>
                              </select>
                              <span class="text-danger"><?php echo form_error('content_title'); ?></span>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label for="exampleInputEmail1">Term</label><small class="req"> *</small>
                              <select autofocus="" id="term_id" name="term" placeholder="" type="text" class="form-control filter" required="">
                                 <option value="">Select Term</option>
                                 <?php foreach ($quarters as $key => $value) : ?>
                                    <option value="<?php echo $value['id'] ?>" <?php if ($_REQUEST['term'] == $value['id']) : echo 'selected';
                                                                                 endif; ?>><?php echo $value['description'] ?></option>
                                 <?php endforeach; ?>
                              </select>
                              <span class="text-danger"><?php echo form_error('content_title'); ?></span>
                           </div>
                        </div>
                        <button type="submit" name="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('search'); ?></button>

                        <table class="table table-striped table-bordered table-hover example nowrap">
                           <thead>
                              <tr>
                                 <th>Student Name</th>
                                 <th>Grade</th>

                              </tr>
                           </thead>
                           <tbody>

                              <?php foreach ($list as $list_key => $list_data) : ?>

                                 <tr>
                                    <td class="mailbox-name" student_id="">
                                       <?php echo $list_data['lastname'] ?>,<?php echo $list_data['firstname'] ?>
                                    </td>
                                    <td class="mailbox-name">

                                       <input type="number" student_id="<?php echo $list_data['id'] ?>" class="form-control grades_update" name="grades[<?php echo $list_data['id'] ?>]" value="<?php echo $grades[$list_data['id']]['grade'] ?>">
                                    </td>


                                 </tr>

                              <?php endforeach; ?>

                           </tbody>
                        </table><!-- /.table -->
                        <button type="submit" name="submit" class="btn btn-danger pull-right">Lock</button>

                     </div><!-- /.box-body -->

                     <div class="box-footer">

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

      $(".grades_update").on("change", function() {
         var url = "<?php echo base_url('lms/final_grading/grade_update/'); ?>";
         var term = '<?php echo $_REQUEST["term"] ?>';
         var class_id = '<?php echo $_REQUEST["grade"] ?>';
         var section_id = '<?php echo $_REQUEST["section"] ?>';
         var subject_id = '<?php echo $_REQUEST["subject"] ?>';
         var student_id = $(this).attr("student_id");
         var grade = $(this).val();
         var transfer_data = {
            term: term,
            class_id: class_id,
            section_id: section_id,
            subject_id: subject_id,
            student_id: student_id,
            grade: grade,
         };

         $.ajax({
            url: url,
            method: "POST",
            data: transfer_data,
         }).done(function(data) {
            console.log(data);
         });
      });
   });
</script>
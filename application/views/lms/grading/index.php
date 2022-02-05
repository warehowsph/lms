<!-- <script type="text/javascript" src="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
         <i class="fa fa-download"></i> Grading
      </h1>

   </section>

   <!-- Main content -->
   <section class="content">
      <div class="row">

         <div class="col-md-<?php
                              if ($this->rbac->hasPrivilege('upload_content', 'can_add')) {
                                 echo "12";
                              } else {
                                 echo "12";
                              }
                              ?>">
            <!-- general form elements -->
            <div class="box box-primary">
               <div class="box-header ptbnull">

                  <h3 class="box-title titlefix">Edit Grading</h3>

                  <div class="box-tools pull-right">

                  </div><!-- /.box-tools -->
               </div><!-- /.box-header -->
               <div class="box-body">
                  <div class="mailbox-controls">
                     <!-- Check all button -->
                     <div class="pull-right">

                     </div><!-- /.pull-right -->
                  </div>

                  <div class="mailbox-messages table-responsive">
                     <div class="download_label"><?php echo $this->lang->line('content_list'); ?></div>

                     <table class="table table-striped table-bordered table-hover example nowrap">
                        <thead>
                           <tr>
                              <th>Description</th>
                              <th>Subject</th>
                              <th>Teacher</th>
                              <th>Action</th>
                              <th>Date Created</th>

                           </tr>
                        </thead>
                        <tbody>

                           <?php foreach ($list as $list_key => $list_data) : ?>

                              <tr>
                                 <td class="mailbox-name">
                                    <?php echo ($list_data['school_name'] == 'Conduct' ? '(' . $list_data['school_name'] . ')' : ''); ?> <?php echo $list_data['class'] ?> - <?php echo $list_data['section'] ?> (<?php echo $list_data['description'] ?>)
                                 </td>
                                 <td class="mailbox-name">
                                    <?php echo $list_data['subject_name']; ?>
                                 </td>
                                 <td class="mailbox-name">
                                    <?php echo $list_data['teacher_name']; ?> <?php echo $list_data['teacher_surname']; ?>
                                 </td>
                                 <td class="mailbox-name">
                                    <?php if ($list_data['school_name'] == "Conduct") : ?>
                                       <a data-placement="right" href="<?php echo site_url('lms/grading/edit/' . $list_data['id'] . "/conduct") ?>" class="btn btn-default btn-xs duplicate" data-toggle="tooltip" title="Edit">
                                          <i class="fa fa-edit"></i>
                                       </a>
                                    <?php else : ?>
                                       <a data-placement="right" href="<?php echo site_url('lms/grading/edit/' . $list_data['id']) ?>" class="btn btn-default btn-xs duplicate" data-toggle="tooltip" title="Edit">
                                          <i class="fa fa-edit"></i>
                                       </a>
                                    <?php endif ?>

                                    <!-- <a data-placement="right" href="<?php //echo site_url('lms/grading/edit_column/'.$list_data['id']) 
                                                                           ?>" class="btn btn-default btn-xs duplicate"  data-toggle="tooltip" title="Edit">
                                                    <i class="fa fa-list"></i>
                                                </a> -->

                                    <a data-placement="right" href="#" class="btn btn-default btn-xs duplicate" data-toggle="tooltip" title="Delete" onclick="delete_confirm('<?php echo site_url('lms/grading/delete/' . $list_data['id']); ?>')">
                                       <i class="fa fa-times"></i>
                                    </a>

                                    <!-- <a data-placement="right" href="<?php echo site_url('lms/grading/delete/' . $list_data['id']) ?>" class="btn btn-default btn-xs duplicate" data-toggle="tooltip" title="Delete">
                                       <i class="fa fa-times"></i>
                                    </a> -->

                                 </td>



                                 <td class="mailbox-name">
                                    <?php echo $list_data['gcr_created_at']; ?>
                                 </td>



                              </tr>
                           <?php endforeach; ?>

                        </tbody>
                     </table><!-- /.table -->
                  </div><!-- /.mail-box-messages -->

               </div><!-- /.box-body -->

            </div>
         </div>
         <!--/.col (left) -->


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
<div class="modal fade" id="initial" tabindex="-1" role="dialog" aria-labelledby="initial" style="padding-left: 0 !important">
   <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content modal-media-content">
         <div class="modal-header modal-media-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="box-title">Enter Class.</h4>
         </div>
         <div class="modal-body pt0 pb0" id="">
            <!-- <div class="container"> -->

            <table class="table table-responsive">
               <tr>

                  <th colspan="2">
                     <center>
                        <h4 class="note"> Please click which will you open
                     </center>
                     </h4>
                  </th>
               </tr>
               <tr>
                  <td>
                     <center><a href="" id="view_lesson" target="_blank"><button class="btn btn-success">View Lesson</button></a></center>
                  </td>
                  <td>
                     <center><a href="" id="enter_video" target="_blank"><button class="btn btn-primary">Enter Video Conference</button></a></center>
                  </td>
               </tr>
            </table>
            <!-- </div> -->

         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="email_logs" tabindex="-1" role="dialog" aria-labelledby="email_logs" style="padding-left: 0 !important">
   <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content modal-media-content">
         <div class="modal-header modal-media-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="box-title">Email Logs for <span class="lesson_title_email_log"><span></h4>
         </div>
         <div class="modal-body" id="">

            <table class="table table-responsive" id="myTable">
               <thead>
                  <tr>
                     <th>Student Name</th>
                     <th>Receiver</th>
                     <th>Status</th>
                     <th>Username Sent</th>
                     <th>Password Sent</th>
                     <th>Timestamp</th>
                  </tr>
               </thead>

               <tbody>
                  <tr>
                     <td>Joeven Cerveza</td>
                     <td>cervezajoeven@gmail.com</td>
                     <td>Sent</td>
                     <td>student</td>
                     <td>student</td>
                     <td>August 28, 2020 2:00 AM</td>
                  </tr>
               </tbody>
               <tfoot>
                  <tr>
                     <th>Student Name</th>
                     <th>Receiver</th>
                     <th>Status</th>
                     <th>Username Sent</th>
                     <th>Password Sent</th>
                     <th>Timestamp</th>
                  </tr>
               </tfoot>

            </table>

         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="attendance" tabindex="-1" role="dialog" aria-labelledby="email_logs" style="padding-left: 0 !important">
   <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content modal-media-content">
         <div class="modal-header modal-media-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="box-title">Attendance for <span class="lesson_title_attendance"><span></h4>
         </div>
         <div class="modal-body" id="">

            <table class="table table-responsive" id="attendance_table">
               <thead>
                  <tr>
                     <th>Student Name</th>
                     <th>Timestamp</th>
                  </tr>
               </thead>

               <tbody>
                  <tr>
                     <td>Joeven Cerveza</td>
                     <td>August 28, 2020 2:00 AM</td>
                  </tr>
               </tbody>
               <tfoot>
                  <tr>
                     <th>Student Name</th>
                     <th>Timestamp</th>
                  </tr>
               </tfoot>

            </table>

         </div>
      </div>
   </div>
</div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
   var table = $('#myTable').DataTable();
   var attendance_table = $('#attendance_table').DataTable();
   var user_id = '<?php echo $user_id ?>';

   function check_class(lesson_id) {
      var url = "<?php echo base_url('lms/lesson/check_class/'); ?>" + lesson_id + '/' + user_id;
      console.log(url);

      $.ajax({
         url: url,
         method: "POST",
      }).done(function(data) {
         console.log(data);
         var parsed_data = JSON.parse(data);
         $('#initial').modal('show');

         if (parsed_data.video != "") {
            $("#enter_video").show();
            $("#enter_video").attr("href", parsed_data.video);
         } else {
            $("#enter_video").hide();
            $(".note").text("The teacher have not started the zoom class yet.");
         }

         if (parsed_data.lms != "") {
            $("#view_lesson").show();
            $("#view_lesson").attr("href", parsed_data.lms);

            if (parsed_data.video == "") {
               if (parsed_data.lesson_type == "others") {
                  $(".note").text("You are only allowed to view lesson. Since its not a zoom or google meet class.");
               } else {
                  $(".note").text("The teacher haven't started the class yet. But you are allowed to view the lesson");
               }
            } else {
               $(".note").text("Please select an action.");
            }
         } else {
            $("#view_lesson").hide();
            $(".note").text("The teacher have not allowed the viewing of lesson yet. Only the video conference.");
         }

         if (parsed_data.lms == "" && parsed_data.video == "") {
            $(".note").text("The teacher may have not allowed viewing of lesson and haven't started the class yet. Please wait for the teacher to start.");
         }
      });
   }

   function email_logs(lesson_id, lesson_name) {
      var url = "<?php echo base_url('lms/lesson/emails/'); ?>" + lesson_id;
      $.ajax({
         url: url,
         method: "POST",
      }).done(function(data) {
         var parsed_data = JSON.parse(data);
         $('#email_logs').modal('show');
         table.clear().draw();
         $(".lesson_title_email_log").text(lesson_name);
         $.each(parsed_data, function(key, value) {
            table.row.add([value.firstname + " " + value.lastname, value.receiver, value.email_status, value.username_sent, value.password_sent, value.date_created]).draw().node();
         });

      });
   }

   function attendance(lesson_id, lesson_name) {
      var url = "<?php echo base_url('lms/lesson/attendance/'); ?>" + lesson_id;
      $.ajax({
         url: url,
         method: "POST",
      }).done(function(data) {
         var parsed_data = JSON.parse(data);
         $('#attendance').modal('show');
         attendance_table.clear().draw();
         $(".lesson_title_attendance").text(lesson_name);
         $.each(parsed_data, function(key, value) {
            attendance_table.row.add([value.firstname + " " + value.lastname, value.timestamp]).draw().node();
         });

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

   function delete_confirm(url) {
      Swal.fire({
         title: 'Delete grading sheet',
         text: 'Are you sure you want to delete this grading sheet?',
         showCancelButton: true,
         confirmButtonText: `Yes`,
         confirmButtonColor: '#3085d6',
         icon: 'question',
      }).then((result) => {
         /* Read more about isConfirmed, isDenied below */
         if (result.isConfirmed) {
            $.ajax({
               url: url,
               method: "POST",
            }).done(function(data) {
               var parsed_data = JSON.parse(data);
               Swal.fire({
                  icon: parsed_data.result,
                  confirmButtonColor: '#3085d6',
                  // title: 'Hurray!',
                  title: parsed_data.message,
                  // footer: '<a href="">Why do I have this issue?</a>'
               }).then(function() {
                  location.reload();
               });
            });
         } else if (result.isDenied) {
            // Swal.fire('Changes are not saved', '', 'info')
         }
      })
   }
</script>
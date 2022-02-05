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

         <div class="col-md-12">


            <!-- general form elements -->
            <div class="box box-primary">
               <div class="box-header ptbnull">
                  <h3 class="box-title titlefix">Assessment List</h3>
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
                              <th class="text-right"><?php echo $this->lang->line('action'); ?>
                              <th>Title</th>
                              <th>Availability</th>
                              <th>Term</th>
                              <th>Attempts</th>
                              <th>Assigned By</th>
                              </th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach ($list as $list_key => $list_data) : ?>

                              <tr>
                                 <td class="mailbox-date pull-right">
                                    <?php if ($role == "admin") : ?>

                                       <a data-placement="right" href="<?php echo site_url('lms/assessment/reports/' . $list_data['id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="Reports">
                                          <i class="fa fa-file"></i>
                                       </a>

                                       <?php if ($list_data['shared'] == 1) { ?>
                                          <a data-placement="right" href="#" class="btn btn-default btn-xs" data-toggle="tooltip" title="Unshare" onclick="unshare_assessment('<?php echo $list_data['id'] ?>');">
                                             <i class="fa fa-newspaper-o"></i>
                                          </a>
                                       <?php } else { ?>
                                          <a data-placement="right" href="#" class="btn btn-default btn-xs" data-toggle="tooltip" title="Share" onclick="share_assessment('<?php echo $list_data['id'] ?>');">
                                             <i class="fa fa-newspaper-o"></i>
                                          </a>
                                       <?php } ?>

                                       <a data-placement="right" href="#" class="btn btn-default btn-xs duplicate" onclick="duplicate_confirm('<?php echo site_url('lms/assessment/duplicate/' . $list_data['id']) ?>')" data-toggle="tooltip" title="Duplicate">
                                          <i class="fa fa-files-o"></i>
                                       </a>

                                       <a data-placement="right" href="<?php echo site_url('lms/assessment/edit/' . $list_data['id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                          <i class="fa fa-edit"></i>
                                       </a>

                                       <a data-placement="right" href="#" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="delete_confirm('<?php echo site_url('lms/assessment/delete/' . $list_data['id']); ?>')">
                                          <i class="fa fa-remove"></i>
                                       </a>

                                    <?php elseif ($role == "student") : ?>
                                       <?php if ($list_data['student_attempt'] >= 1) : ?>
                                          <a data-placement="right" href="<?php echo site_url('lms/assessment/review/' . $list_data['id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="View Answer">
                                             <i class="fa fa-eye"></i>
                                          </a>
                                       <?php endif; ?>
                                       <?php if ($list_data['student_attempt'] < $list_data['attempts']) : ?>
                                          <span data-placement="right" href="" class="btn btn-default btn-xs" data-toggle="tooltip" title="Answer" onclick="take_quiz('<?php echo site_url('lms_v2/index.php/lms/assessment/initialize/' . $user_id . '/student/' . $list_data['id']); ?>')">
                                             <i class="fa fa-edit"></i>
                                          </span>
                                       <?php endif; ?>
                                    <?php endif; ?>

                                 </td>

                                 <td class="mailbox-name">
                                    <?php echo $list_data['assessment_name'] ?>
                                 </td>

                                 <td class="mailbox-name">
                                    <?php echo date("F d h:i A", strtotime($list_data['start_date'])); ?> - <?php echo date("F d h:i A", strtotime($list_data['end_date'])); ?>
                                 </td>
                                 <td class="text-center">
                                    <?php
                                    $term = "";
                                    switch ($list_data['term']) {
                                       case 1:
                                          $term = "1st";
                                          break;
                                       case 2:
                                          $term = "2nd";
                                          break;
                                       case 3:
                                          $term = "3rd";
                                          break;
                                       case 4:
                                          $term = "4th";
                                    }
                                    print_r($term); ?>
                                 </td>
                                 <td class="mailbox-name text-center">

                                    <?php if ($role == "student") : ?><?php print_r($list_data['student_attempt']) ?>/<?php endif; ?> <?php print_r($list_data['attempts']) ?>
                                 </td>

                                 <td>
                                    <?php print_r($list_data['name']) ?> <?php print_r($list_data['surname']) ?>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
   function duplicate_confirm(url) {

      // if (confirm("Are you sure you want to duplicate this assessment?")) {
      //    window.location.href = url;
      // }
      Swal.fire({
         title: 'Duplicate Assessment',
         text: 'Are you sure you want to duplicate this assessment?',
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

   function delete_confirm(url) {
      Swal.fire({
         title: 'Delete Assessment',
         text: 'Are you sure you want to delete this assessment?',
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

   $(document).ready(function() {
      $("#btnreset").click(function() {
         $("#form1")[0].reset();
      });

      var class_id = $('#class_id').val();
      var section_id = '<?php echo set_value('section_id') ?>';
      getSectionByClass(class_id, section_id);

      $(document).on('change', '#class_id', function(e) {
         $('#section_id').html("");
         var class_id = $(this).val();
         var base_url = '<?php echo base_url() ?>';
         var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
         $.ajax({
            type: "GET",
            url: base_url + "sections/getByClass",
            data: {
               'class_id': class_id
            },
            dataType: "json",
            success: function(data) {
               $.each(data, function(i, obj) {
                  div_data += "<option value=" + obj.id + ">" + obj.section + "</option>";
               });
               $('#section_id').append(div_data);
            }
         });
      });

      function getSectionByClass(class_id, section_id) {
         if (class_id != "" && section_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
               type: "GET",
               url: base_url + "sections/getByClass",
               data: {
                  'class_id': class_id
               },
               dataType: "json",
               success: function(data) {
                  $.each(data, function(i, obj) {
                     var sel = "";
                     if (section_id == obj.id) {
                        sel = "selected";
                     }
                     div_data += "<option value=" + obj.id + " " + sel + ">" + obj.section + "</option>";
                  });
                  $('#section_id').append(div_data);
               }
            });
         }
      }



   });
   $(document).ready(function() {

      $(document).on("click", '.content_available', function(e) {
         var avai_value = $(this).val();
         if (avai_value === "student") {
            console.log(avai_value);
            if ($(this).is(":checked")) {

               $(this).closest("div").parents().find('.upload_content').removeClass("content_disable");

            } else {
               $(this).closest("div").parents().find('.upload_content').addClass("content_disable");

            }
         }
      });
      $("#chk").click(function() {
         if ($(this).is(":checked")) {
            $("#class_id").prop("disabled", true);
         } else {
            $("#class_id").prop("disabled", false);
         }
      });
      if ($("#chk").is(":checked")) {
         $("#class_id").prop("disabled", true);
      } else {
         $("#class_id").prop("disabled", false);
      }

   });
</script>

<script>
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
   });

   function take_quiz(url) {
      // if (confirm("Are you sure you want to take this quiz?")) {
      //    window.location.replace(url);
      // }

      Swal.fire({
         text: 'Are you sure you want to take this quiz?',
         showCancelButton: true,
         confirmButtonText: `Yes`,
         confirmButtonColor: '#3085d6',
         icon: 'question',
      }).then((result) => {
         /* Read more about isConfirmed, isDenied below */
         if (result.isConfirmed) {
            // Swal.fire('Saved!', '', 'success')
            window.location.replace(url);
         } else if (result.isDenied) {
            // Swal.fire('Changes are not saved', '', 'info')
         }
      })
   }

   function delete_assessment() {
      alert("fire");
   }

   function unshare_assessment(lesson_id) {
      var url = "<?php echo base_url('lms/assessment/unshare/'); ?>" + lesson_id;
      Swal.fire({
         title: "Unshare Assessment",
         text: 'Are you sure you wan\'t to unshare this assessment?',
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
                  icon: 'success',
                  confirmButtonColor: '#3085d6',
                  // title: 'Hurray!',
                  title: parsed_data.result,
                  // footer: '<a href="">Why do I have this issue?</a>'
               }).then(function() {
                  location.reload();
               });
            });
         } else if (result.isDenied) {
            // Swal.fire('Changes are not saved', '', 'info')
            Swal.close();
         }
      });
   }

   function share_assessment(lesson_id) {
      var url = "<?php echo base_url('lms/assessment/share/'); ?>" + lesson_id;

      Swal.fire({
         title: "Share Assessment",
         text: 'Are you sure you wan\'t to share this lesson?',
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
               // alert(parsed_data.result);
               // location.reload();
               Swal.fire({
                  icon: 'success',
                  confirmButtonColor: '#3085d6',
                  // title: 'Hurray!',
                  title: parsed_data.result,
                  // footer: '<a href="">Why do I have this issue?</a>'
               }).then(function() {
                  location.reload();
               });
            });
         } else if (result.isDenied) {
            // Swal.fire('Changes are not saved', '', 'info')
            Swal.close();
         }
      });

   }
</script>
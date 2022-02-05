<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
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
                  <h3 class="box-title titlefix">Assessment Details</h3>
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
                     <table class="table table-striped table-bordered">
                        <tr>
                           <th>Title</th>
                           <td><?php echo $assessment['assessment_name'] ?></td>
                        </tr>
                        <tr>
                           <th>Total Score</th>
                           <td><?php echo $assessment['total_score'] ?></td>
                        </tr>
                        <tr>
                           <th>Start Date</th>
                           <td><?php echo date("F d, Y H:i:s", strtotime($assessment['start_date'])) ?></td>
                        </tr>
                        <tr>
                           <th>End Date</th>
                           <td><?php echo date("F d, Y H:i:s", strtotime($assessment['end_date'])) ?></td>
                        </tr>
                        <tr>
                           <th>Duration</th>
                           <td><?php echo $assessment['duration'] ?></td>
                        </tr>
                        <tr>
                           <th>Passing Percentage</th>
                           <td><?php echo $assessment['percentage'] ?>%</td>
                        </tr>
                        <tr>
                           <th>Analysis Report</th>
                           <td>
                              <a href="<?php echo site_url('lms/assessment/analysis/') . $assessment['id'] ?>">
                                 <button class="btn btn-primary">Item Analysis</button>
                              </a>
                           </td>
                        </tr>
                        <tr>
                           <th>Check Essays</th>
                           <td>
                              <a href="<?php echo site_url('lms/assessment/check_essays/') . $assessment['id'] ?>">
                                 <button class="btn btn-primary">Check Essays</button>
                              </a>
                           </td>
                        </tr>
                        <tr>
                           <th>Recheck Answers</th>
                           <td>
                              <a href="<?php echo site_url('lms/assessment/recheck_answers/') . $assessment['id'] ?>">
                                 <button class="btn btn-primary">Recheck Answers</button>
                              </a>
                           </td>
                        </tr>
                     </table>
                  </div><!-- /.mail-box-messages -->

               </div><!-- /.box-body -->

            </div>
         </div>
         <!--/.col (left) -->


         <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
               <div class="box-header ptbnull">
                  <h3 class="box-title titlefix">Students List</h3>


                  <style type="text/css">
                     .filter {
                        width: 150px;
                        display: inline;
                     }

                     .legend {
                        height: 35px;
                        width: 40px;
                        padding-top: 8px;
                        text-align: center;
                        border-radius: 50%;
                        font-weight: bold;
                     }
                  </style>
                  <div class="box-tools pull-right">

                     Filters:
                     <select class="form-control filter section">
                        <option value="all">All Section</option>
                        <?php foreach ($sections as $section_key => $section_value) : ?>
                           <option value="<?php echo $section_value['id'] ?>" <?php echo ($section == $section_value['id']) ? "selected" : ""; ?>><?php echo $section_value['section'] ?></option>
                        <?php endforeach; ?>
                     </select>

                     <select class="form-control filter gender">
                        <option <?php echo ($gender == "all") ? "selected" : ""; ?> value="all">All Gender</option>
                        <option <?php echo ($gender == "male") ? "selected" : ""; ?> value="male">Male</option>
                        <option <?php echo ($gender == "female") ? "selected" : ""; ?> value="female">Female</option>
                     </select>
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
                     <style type="text/css">
                        .red {
                           background-color: #f6c3c3 !important;
                        }

                        .green {
                           background-color: #c3f6c9 !important;
                        }

                        .yellow {
                           background-color: #f6f2c3 !important;
                        }
                     </style>
                     <table class="table">
                        <tr>
                           <th width="100px">Legend</th>
                           <th></th>
                        </tr>
                        <tr>
                           <td>Submitted</td>
                           <td>
                              <div class="legend green"><?php echo $submitted ?></div>
                           </td>
                        </tr>
                        <tr>
                           <td>Pending</td>
                           <td>
                              <div class="legend yellow"><?php echo $answering ?></div>
                           </td>
                        </tr>
                        <tr>
                           <td>Not Started</td>
                           <td>
                              <div class="legend red"><?php echo $not_yet ?></div>
                           </td>
                        </tr>
                     </table>
                     <table class="table table-striped table-bordered table-hover example nowrap">
                        <thead>
                           <tr>
                              <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                              <th>Name</th>
                              <th>Grade</th>
                              <th>Section</th>
                              <th>Score</th>
                              <th>Percentage</th>
                              <th>Status</th>
                              <th>Start Date</th>
                              <th>Submitted Date</th>
                              <th>Gender</th>
                              <th>Browser</th>
                              <th>Device</th>
                              <?php if ($real_role == 7) : ?>
                                 <th>Version</th>
                                 <th>OS</th>
                              <?php endif; ?>
                              <!-- <th>Essays</th> -->
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach ($students as $list_key => $list_data) : ?>
                              <?php if ($list_data['student_activity'] == "submitted") : ?>
                                 <?php $row_color = "green"; ?>
                              <?php elseif ($list_data['student_activity'] == "answering") : ?>
                                 <?php $row_color = "yellow"; ?>
                              <?php else : ?>
                                 <?php $row_color = "red"; ?>

                              <?php endif; ?>
                              <tr class="<?php echo $row_color ?>">
                                 <td class="mailbox-date pull-right">
                                    <center>
                                       <?php if ($list_data['student_activity'] == "submitted") : ?>
                                          <a data-placement="right" href="<?php echo site_url('lms/assessment/review/' . $list_data['assessment_id'] . '/' . $list_data['student_id']); ?>" class="btn btn-default btn-xs <?php echo $row_color ?>" data-toggle="tooltip" title="View Answer Sheet">
                                             <i class="fa fa-eye"></i>
                                          </a>
                                       <?php endif; ?>

                                       <?php if ($list_data['student_activity'] == "answering") : ?>
                                          <a data-placement="right" href="<?php echo site_url('lms_v2/index.php/lms/assessment/initialize/' . $list_data['student_id'] . '/student/' . $list_data['assessment_id']); ?>" class="btn btn-default btn-xs <?php echo $row_color ?>" data-toggle="tooltip" title="Check Answer Sheet (To login as this student. This can fix issues on students who can't submit their assessment due to browser issue)">
                                             <i class="fa fa-play"></i>
                                          </a>
                                       <?php endif; ?>
                                       <?php //if ($real_role == 7) : 
                                       ?>
                                       <!-- <a data-placement="right" href="<?php echo site_url('lms_v2/index.php/lms/assessment/initialize/' . $list_data['student_id'] . '/student/' . $list_data['assessment_id']); ?>" class="btn btn-default btn-xs <?php echo $row_color ?>" data-toggle="tooltip" title="Check Answer Sheet (To login as this student. This can fix issues on students who can't submit their assessment due to browser issue)">
                                             <i class="fa fa-play"></i>
                                          </a> -->
                                       <?php //endif; 
                                       ?>
                                       <?php if ($list_data['student_activity'] == "submitted") : ?>
                                          <a data-placement="right" href="#" onclick="allow_reanswer('<?php echo site_url('lms/assessment/allow_reanswer_delete/' . $list_data['assessment_sheet_id'] . '/' . $list_data['student_id']); ?>')" class="btn btn-default btn-xs <?php echo $row_color ?>" data-toggle="tooltip" title="Allow Retake">
                                             <i class="fa fa-pencil"></i>
                                          </a>
                                       <?php endif; ?>
                                    </center>


                                 </td>

                                 <td class="mailbox-name">
                                    <?php echo $list_data['lastname'] ?>, <?php echo $list_data['firstname'] ?>
                                 </td>

                                 <td class="mailbox-name">
                                    <?php echo $list_data['class'] ?>
                                 </td>
                                 <td class="mailbox-name">
                                    <?php echo $list_data['section'] ?>
                                 </td>
                                 <td>
                                    <?php echo $list_data['score'] ?>
                                 </td>
                                 <td>
                                    <?php echo round(($list_data['score'] / $list_data['total_score']) * 100) ?>%
                                    <?php $current_percentage = round(($list_data['score'] / $list_data['total_score']) * 100) ?>
                                 </td>
                                 <td>
                                    <?php if ($list_data['student_activity'] == "submitted") : ?>
                                       <?php echo ($current_percentage >= $assessment['percentage']) ? "Pass" : "Fail"; ?>
                                    <?php endif; ?>
                                 </td>
                                 <td class="mailbox-name">
                                    <?php echo $list_data['start_date'] ?>
                                 </td>
                                 <td class="mailbox-name">
                                    <?php echo $list_data['end_date']; ?>
                                 </td>
                                 <td class="mailbox-name">
                                    <?php echo $list_data['gender'] ?>
                                 </td>
                                 <td>
                                    <?php echo $list_data['browser'] ?>
                                 </td>
                                 <td>
                                    <?php echo $list_data['device'] ?>
                                 </td>
                                 <?php if ($real_role == 7) : ?>
                                    <td>
                                       <?php echo $list_data['browser_version'] ?>
                                    </td>
                                    <td>
                                       <?php echo $list_data['os_platform'] ?>
                                    </td>
                                 <?php endif; ?>
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
      <input type="hidden" id="url" value="<?php echo site_url() ?>" name="">
      <input type="hidden" id="assessment_id" value="<?php echo $assessment['id'] ?>" name="">
   </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
   function allow_reanswer(url) {
      // if (confirm("Are you sure you want this student to retake the assessment? This will reset his answers and timer on the latest attempt.")) {
      //    window.location.href = url;
      // }

      Swal.fire({
         title: 'Allow Retake',
         text: 'Are you sure you want this student to retake the assessment?',
         footer: 'This will reset his answers and timer on the latest attempt.',
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
                  title: 'Successful!',
                  text: parsed_data.message,
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
      var url = $("#url").val();
      var assessment_id = $("#assessment_id").val();
      $('.filter').select2();
      $(".filter").change(function() {

         var filter_redirect = url + 'lms/assessment/reports/' + assessment_id;
         var section = $(".section").val();
         var gender = $(".gender").val();
         var filter_url = filter_redirect + '/' + section + '/' + gender;
         window.location.href = filter_url;

      });
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
</script>
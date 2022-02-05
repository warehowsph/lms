<!DOCTYPE html>
<html>

<head>
   <title>Control</title>
   <!-- Latest compiled and minified CSS -->
   <link rel="stylesheet" href="<?php echo $resources . 'boostrap.min.css' ?>">
   <link rel="stylesheet" href="<?php echo $resources . 'bootstrap-theme.min.css' ?>">
   <link rel="stylesheet" href="<?php echo $resources . 'fileinput.css' ?>">
   <link rel="stylesheet" href="<?php echo $resources . 'fileinput.min.css' ?>">
   <link rel="stylesheet" href="<?php echo $resources . 'jquery-ui.css' ?>">
   <link rel="stylesheet" href="<?php echo $resources . 'font-awesome.min.css' ?>">
   <link rel="stylesheet" href="<?php echo $resources . 'assessment.css' ?>">
   <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" />
   <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

   <style type="text/css">
      .jstree-themeicon-custom {
         background-size: 100% !important;
      }

      .info-tab {
         /*border-top: 1px solid white;*/
         border-bottom: 1px solid white;
      }

      .save_status {
         color: white;
         text-align: center;
         background-color: black;
      }

      .score_class {
         width: 45px;
      }
   </style>
</head>

<body>

   <div class="container-fluid">
      <div class="row row-height">
         <div class="col-sm-7 ben_left">
            <!-- <form enctype="multipart/form-data" id="upload_form" method="POST" action="<?php //echo site_url('lms/assessment/upload/'.$assessment['id']); 
                                                                                             ?>" style="top: 0;position: absolute; width: 100%;"> -->
            <form enctype="multipart/form-data" id="upload_form" method="POST" style="top: 0;position: absolute; width: 100%;">
               <input type="file" required="" class="form-control file" accept="application/pdf" name="assessment_form">
               <input type="button" value="Upload" class="form-control btn btn-success upload">
            </form>
            <?php if ($assessment['assessment_file']) : ?>
               <!-- <iframe style="height: 99.3%;width: 100%;" id="optical_pdf" class="embed-responsive-item" src="<?php echo $resources . 'pdfjs/web/viewer.html?file=' . urlencode(site_url('uploads/lms_assessment/' . $assessment['id'] . '/' . $assessment['assessment_file']) . "&embedded=true"); ?>"></iframe> -->
               <iframe style="height: 99.3%;width: 100%;" id="optical_pdf" class="embed-responsive-item" src="<?php echo $resources . 'pdfjs/web/viewer.html?file=' . urlencode($_SESSION['S3_BaseUrl'] . 'uploads/lms_assessment/' . $assessment['id'] . '/' . $assessment['assessment_file']) . "&embedded=true"; ?>"></iframe>
               <!-- <iframe style="height: 99.3%;width: 100%;" id="optical_pdf" class="embed-responsive-item" src="https://docs.google.com/gview?url=<?php echo urlencode($_SESSION['S3_BaseUrl'] . 'uploads/lms_assessment/' . $assessment['id'] . '/' . $assessment['assessment_file']) . "&embedded=true"; ?>"></iframe> -->
            <?php else : ?>
               <h1 style="text-align: center;margin-top: 10;">Upload a PDF File Here</h1>
            <?php endif; ?>
         </div>

         <div class="col-sm-5 right">

            <div class="info col-sm-5">
               <div class="info-row">
                  <div class="info-tab info-title col-sm-2">Title :</div>
                  <div class="info-tab col-sm-8"><input type="text" id="assessment_name" value="<?php echo $assessment['assessment_name'] ?>" style="width: 100%;padding: 0px;margin: 0px;border: 0px;background: transparent;"></div>
               </div>
               <div class="info-row">
                  <a href="<?php echo site_url('lms/assessment/index/'); ?>">
                     <div class="info-tab info-title col-sm-2 the_close">Close</div>
                  </a>
               </div>

               <div class="info-row">
                  <div class="info-tab info-title col-sm-2">Date :</div>
                  <div class="info-tab col-sm-7"><?php echo date('F d, Y'); ?></div>
                  <div class="info-tab col-sm-3 save_status">No Changes</div>
               </div>

               <div class="info-row">
                  <div class="info-tab info-key col-sm-4 save add_section" option_type="section">
                     <center>Add Section</center>
                  </div>
                  <div class="info-tab col-sm-4 save assign">
                     <center>Assign</center>
                  </div>
                  <div class="info-tab col-sm-4 save true_save">
                     <center>Save</center>
                  </div>

               </div>

               <div class="info-row">
                  <div class="info-tab info-key col-sm-3" option_type="multiple_choice" title="True or False, Yes or No, Chronological Order, Matching Type">Multiple Choice</div>
                  <div class="tooltip">Hover over me
                     <span class="tooltiptext">Tooltip text</span>
                  </div>
                  <div class="info-tab info-key col-sm-3" title="Identification, Matching Type, Chronological Order, Fill in the Blanks" option_type="short_answer">Short Answer</div>

                  <div class="info-tab info-key col-sm-3" title="Multiple Answer" option_type="multiple_answer">Multiple Answer</div>

                  <div class="info-tab info-key col-sm-3" title="Essay" option_type="long_answer">
                     <center>Essay</center>
                  </div>

               </div>
            </div>

            <div class="clearfix"></div>

            <div class="assign_panel">
               <div class="col-sm-4">
                  Duration (Minutes)
                  <input type="number" min="1" value="<?php echo ($assessment['duration']) ? $assessment['duration'] : 30 ?>" class="form-control duration" name="duration">
               </div>
               <div class="col-sm-4">
                  Term
                  <select class="form-control" id="term">
                     <option value="1" <?php echo ($assessment['term'] == 1 ? "SELECTED" : "") ?>>1st</option>
                     <option value="2" <?php echo ($assessment['term'] == 2 ? "SELECTED" : "") ?>>2nd</option>
                     <option value="3" <?php echo ($assessment['term'] == 3 ? "SELECTED" : "") ?>>3rd</option>
                     <option value="4" <?php echo ($assessment['term'] == 4 ? "SELECTED" : "") ?>>4th</option>
                  </select>
               </div>
               <div class="col-sm-4">
                  Passing Percentage %
                  <input type="number" min="0" max="100" value="<?php echo ($assessment['percentage']) ? $assessment['percentage'] : 50 ?>" class="form-control percentage" name="">
               </div>
               <div class="col-sm-4">
                  Attempts
                  <input type="number" min="0" value="<?php echo ($assessment['attempts']) ? $assessment['attempts'] : 1 ?>" class="form-control attempts" name="">
               </div>
               <div class="col-sm-4">
                  <div class="pretty p-switch p-fill" style="margin-top: 30px;">

                     <input type="checkbox" id="allow_result_viewing" <?php echo ($assessment['allow_result_viewing'] == 1) ? "checked = ''" : ""; ?> />
                     <div class="state p-primary">
                        <label>Display Results after Student Submission</label>
                     </div>
                  </div>
               </div>
               <!-- <div class = "col-sm-12">
			        		<div class="pretty p-switch p-fill" style="margin-top: 30px;">
	                                
		                        <input type="checkbox" id="email_notification" <?php echo ($assessment['email_notification'] == 1) ? "checked = ''" : ""; ?> />
		                        <div class="state p-primary">
		                            <label>Email Notification</label>
		                        </div>
		                    </div>
		                </div> -->
               <div class="col-sm-12">
                  <div class="pretty p-switch p-fill" style="margin-top: 30px;">

                     <input type="checkbox" id="enable_timer" <?php echo ($assessment['enable_timer'] == 1) ? "checked = ''" : ""; ?> disabled />
                     <div class="state p-primary">
                        <label>Enable Timer</label>
                     </div>
                  </div>
               </div>

               <div class="col-sm-12" style="margin-top:10px">
                  Date Assigned

                  <input type="hidden" value="<?php echo $assessment['start_date'] ?>" class="start_date" name="">
                  <input type="hidden" value="<?php echo $assessment['end_date'] ?>" class="end_date" name="">
                  <input type="text" value="" class="form-control date_range" name="">
               </div>


               <div class="col-sm-12">
                  Assign Students
                  <div id="jstree_demo_div">

                     <ul>
                        <?php foreach ($classes as $classes_key => $classes_value) : ?>
                           <li data-jstree='{"icon":"https://img.icons8.com/bubbles/2x/classroom.png"}'><?php echo $classes_value['class'] ?>
                              <ul>
                                 <?php foreach ($class_sections as $class_sections_key => $class_sections_value) : ?>
                                    <?php if ($class_sections_value['class_id'] == $classes_value['id']) : ?>
                                       <li id="section_<?php echo $class_sections_value['class_id'] ?>_<?php echo $class_sections_value['section_id'] ?>" data-jstree='{"icon":"https://img.icons8.com/clouds/2x/child-safe-zone.png"}'><?php echo $class_sections_value['section'] ?>
                                          <ul>
                                             <?php foreach ($students as $students_key => $students_value) : ?>
                                                <?php if ($students_value['class_id'] == $class_sections_value['class_id'] && $students_value['section_id'] == $class_sections_value['section_id']) : ?>

                                                   <li data-jstree='{"icon":"https://cdn.clipart.email/08211c36d197d37bb0d0761bbfeb8efd_square-academic-cap-graduation-ceremony-clip-art-graduation-hat-_1008-690.png"}' class="student" id="student_<?php echo $students_value['id'] ?>"><?php echo $students_value['firstname'] ?> <?php echo $students_value['lastname'] ?></li>
                                                <?php endif; ?>
                                             <?php endforeach; ?>
                                          </ul>
                                       </li>
                                    <?php endif; ?>
                                 <?php endforeach; ?>
                              </ul>
                           </li>
                        <?php endforeach; ?>
                     </ul>

                  </div>
               </div>
            </div>
            <ul class="sortable ui-sortable">
               <li class="option-container option-container-clonable">
                  <div class="numbering_option"></div>
                  <label class="score_class">Score: </label> <input type="number" min="1" class="points score_class" value="1" />
                  <div class="copy_bottom" style="display: inline;">
                     <button class="btn btn-success" title="Copy" data-toggle="tooltip">Copy <i class="fa fa-files-o"></i><span></span></button>
                  </div>
                  <!-- <div class="copy_bottom" style="display: inline;">
		        					<button class="btn btn-warning">Copy To No. <span></span></button>
		        				</div> -->

                  <div class="remove_option float-right">X</div>
                  <div class="option">
                     <div class="option_type">
                        <input type="radio" name="" autocomplete="off" class="form-control">
                     </div>
                     <div class="option_label_container">
                        <div class="option_label"></div>
                        <div class="option_label_input">
                           <input type="text" name="" autocomplete="off" value="A" class="form-control">
                        </div>
                        <div class="remove_choice"><button>X</button></div>
                     </div>

                  </div>

                  <div class="add_option">
                     <div class="option_type">

                     </div>
                     <div class="option_label_container">

                        <div class="">
                           <center>
                              <input type="button" name="" class="form-control btn btn-success" style="margin-top: 10px;" value="Add Option">
                           </center>

                        </div>
                     </div>

                  </div>
               </li>

            </ul>

         </div>
      </div>
   </div>

   <input type="hidden" id="url" value="<?php echo site_url('lms/assessment/update'); ?>" name="" />
   <input type="hidden" id="base_url" value="<?php echo site_url('lms/assessment/'); ?>" name="" />
   <input type="hidden" id="stored_json" value='<?php echo $assessment['sheet']; ?>' name="" />
   <input type="hidden" id="assessment_id" value="<?php echo $assessment['id'] ?>" name="" />
   <input type="hidden" id="assigned" value="<?php echo $assessment['assigned'] ?>" name="" />

</body>

</html>

<script src="<?php echo $resources . 'jquery-1.12.4.js' ?>"></script>
<script src="<?php echo $resources . 'jquery-ui.js' ?>"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://nosir.github.io/cleave.js/dist/cleave.min.js"></script>
<script src="https://nosir.github.io/cleave.js/dist/cleave-phone.i18n.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script src="<?php echo $resources . 'assessment_10.js' ?>"></script>


<script type="text/javascript">
   $("#upload_form").on("submit", function(e) {
      e.preventDefault();

      var upload_url = '<?php echo site_url('lms/assessment/upload/' . $assessment['id']); ?>';

      Swal.fire({
         title: 'Uploading file please wait...',
         allowEscapeKey: false,
         allowOutsideClick: false,
         // timer: 2000,
         didOpen: () => {
            swal.showLoading();
         }
      });

      var data = new FormData(this);

      $.ajax({
         url: upload_url,
         type: "POST",
         data: data,
         contentType: false,
         cache: false,
         processData: false,

         success: function(data) {
            var resp = JSON.parse(data);
            Swal.close();

            if (resp.status == "success") {
               Swal.fire({
                  icon: 'success',
                  confirmButtonColor: '#3085d6',
                  // title: 'Hurray!',
                  title: resp.message,
                  // footer: '<a href="">Why do I have this issue?</a>'
               }).then(function() {
                  location.reload();
               });
            } else {
               Swal.fire({
                  icon: 'error',
                  confirmButtonColor: '#3085d6',
                  title: 'Ooops!',
                  text: resp.message,
                  // footer: '<a href="">Why do I have this issue?</a>'
               })
            }
         },
         error: function(e) {
            Swal.fire({
               icon: 'error',
               confirmButtonColor: '#3085d6',
               title: 'Ooops!',
               text: resp.message,
               // footer: '<a href="">Why do I have this issue?</a>'
            })
         }
      });
   });
</script>
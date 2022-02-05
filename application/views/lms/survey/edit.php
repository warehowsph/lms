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
   <link rel="stylesheet" href="<?php echo $resources . 'survey.css' ?>">
   <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
   <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</head>
<style type="text/css">
   .jstree-themeicon-custom {
      background-size: 100% !important;
   }

   .assign_panel {
      position: relative;
      padding: 0;
      top: 120px;
   }
</style>

<body>


   <div class="container-fluid">
      <div class="row row-height">
         <div class="col-sm-7 left">
            <form enctype="multipart/form-data" method="POST" action="<?php echo site_url('lms/survey/upload/' . $survey['id']); ?>">
               <a href="<?php echo site_url('lms/survey/index/'); ?>"><button type="button" class="form-control btn btn-danger">Back</button></a>
               <input type="file" required="" class="form-control" accept="application/pdf" name="survey_form">
               <input type="submit" class="form-control btn btn-success">
            </form>

            <!-- <iframe style="height: 100%;width: 100%;" id="optical_pdf" class="embed-responsive-item" src="<?php echo $resources . 'pdfjs/web/viewer.html?file=' . urlencode(site_url('uploads/lms_survey/' . $survey['id'] . '/' . $survey['survey_file']) . "&embedded=true"); ?>"></iframe> -->
            <iframe style="height: 100%;width: 100%;" id="optical_pdf" class="embed-responsive-item" src="<?php echo $resources . 'pdfjs/web/viewer.html?file=' . urlencode($_SESSION['S3_BaseUrl'] . 'uploads/lms_survey/' . $survey['id'] . '/' . $survey['survey_file']) . "&embedded=true"; ?>"></iframe>
            <!-- <iframe style="height: 99.3%;width: 100%;" id="optical_pdf" class="embed-responsive-item" src="https://docs.google.com/gview?url=<?php echo urlencode($_SESSION['S3_BaseUrl'] . 'uploads/lms_survey/' . $survey['id'] . '/' . $survey['survey_file']); ?>"></iframe> -->

         </div>

         <div class="col-sm-5 right">
            <div class="info col-sm-5">

               <div class="info-row">
                  <div class="info-tab info-title col-sm-3">Date :</div>
                  <div class="info-tab col-sm-9">February 21, 2020</div>
               </div>

               <div class="info-row">
                  <div class="info-tab info-title col-sm-3">Title :</div>
                  <div class="info-tab col-sm-9"><?php echo $survey['survey_name'] ?></div>
               </div>
               <div class="info-row">
                  <div class="info-tab info-key col-sm-3" option_type="multiple_choice">Multiple Choice</div>
                  <!-- <div class="info-tab info-key col-sm-3" option_type="short_answer">Answers</div> -->
                  <div class="info-tab info-key col-sm-6" option_type="long_answer">
                     <center>Comments/Suggestions</center>
                  </div>
                  <div class="info-tab info-key col-sm-3" option_type="multiple_answer">Multiple Answer</div>
               </div>
               <div class="info-row">
                  <div class="info-tab col-sm-6 save">
                     <center>Save</center>
                  </div>
                  <div class="info-tab col-sm-6 assign">
                     <center>Assign</center>
                  </div>
               </div>

            </div>
            <div class="clearfix"></div>
            <div class="assign_panel">

               <div class="col-sm-8">
                  Date Assigned
                  <input type="hidden" value="<?php echo $survey['start_date'] ?>" class="start_date" name="">
                  <input type="hidden" value="<?php echo $survey['end_date'] ?>" class="end_date" name="">
                  <input type="text" value="" class="form-control date_range" name="">
               </div>
               <div class="col-sm-4">
                  <div class="pretty p-switch p-fill" style="margin-top: 30px;">

                     <input type="checkbox" id="email_notification" />
                     <div class="state p-primary">
                        <label>Email Notification</label>
                     </div>
                  </div>
               </div>
               <div class="col-sm-12">
                  Assign Students
                  <div id="jstree_demo_div">
                     <ul>
                        <li class="jstree-open" data-jstree='{
	                                    "icon":"https://upload.wikimedia.org/wikipedia/commons/thumb/4/40/Round_Landmark_School_Icon_-_Transparent.svg/1200px-Round_Landmark_School_Icon_-_Transparent.svg.png"
	                                }'>All
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
                     </ul>
                     </li>
                  </div>
               </div>
            </div>
            <ul class="sortable ui-sortable">
               <li class="option-container option-container-clonable">
                  <div class="numbering_option">1.</div>
                  <div class="remove_option float-right">X</div>
                  <div class="option">
                     <div class="option_type">
                        <input type="radio" name="" class="form-control">
                     </div>
                     <div class="option_label_container">
                        <div class="option_label"></div>
                        <div class="option_label_input">
                           <input type="text" name="" value="A" class="form-control">
                        </div>
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
   <input type="hidden" id="assigned" value="<?php echo $survey['assigned'] ?>" name="" />
</body>

</html>

<script type="text/javascript" src="<?php echo $resources . 'jquery-1.12.4.js' ?>"></script>
<script type="text/javascript" src="<?php echo $resources . 'jquery-ui.js' ?>"></script>
<script type="text/javascript" src="https://nosir.github.io/cleave.js/dist/cleave.min.js"></script>
<script type="text/javascript" src="https://nosir.github.io/cleave.js/dist/cleave-phone.i18n.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<!-- <script type="text/javascript" src="<?php echo $resources . 'survey.js' ?>"></script> -->
<script type="text/javascript">
   var url = "<?php echo site_url('lms/survey/update'); ?>";
   var survey_id = "<?php echo $survey['id'] ?>";
   var survey_data = {
      id: survey_id
   };
















   var final_json = {};
   var assigned = $("#assigned").val();

   $(".sortable").sortable({
      stop: function(event, ui) {
         renumbering();
      }
   });
   var jstree = $('#jstree_demo_div').jstree({
      "checkbox": {
         "keep_selected_style": false
      },
      "plugins": ["checkbox"]
   });
   $(".option-container-clonable").hide();

   function populate_key(option_type) {
      var option_clone = $(".option-container-clonable").clone();
      switch (option_type) {
         case "multiple_choice":
            option_clone.removeClass("option-container-clonable");
            option_clone.addClass("option-container-actual");
            option_clone.addClass("multiple_choice");
            option_clone.attr("option_type", "multiple_choice");
            option_clone.show();
            $(".sortable").append(option_clone);
            break;
         case "multiple_answer":
            option_clone.removeClass("option-container-clonable");
            option_clone.addClass("option-container-actual");
            option_clone.addClass("multiple_choice");
            option_clone.attr("option_type", "multiple_answer");
            option_clone.show();
            option_clone.find(".option_type").find("input").attr("type", "checkbox");
            $(".sortable").append(option_clone);
            break;
         case "short_answer":
            option_clone.removeClass("option-container-clonable");
            option_clone.addClass("option-container-actual");
            option_clone.addClass("short_answer");
            option_clone.show();
            option_clone.attr("option_type", "short_answer");
            option_clone.find(".option_type").find("input").attr("type", "text");
            option_clone.find(".option_type").find("input").css("width", "100%");
            option_clone.find(".option_label_input").find("input").remove();
            option_clone.find(".add_option").remove();
            $(".sortable").append(option_clone);
            break;
         case "long_answer":
            option_clone.removeClass("option-container-clonable");
            option_clone.addClass("option-container-actual");
            option_clone.addClass("short_answer");
            option_clone.show();
            option_clone.attr("option_type", "long_answer");
            option_clone.find(".option_type").empty();
            option_clone.find(".option_type").html('<textarea class="form-control"></textarea>');
            option_clone.find(".option_type").find("textarea").css("width", "100%");
            option_clone.find(".option_label_input").find("input").remove();
            option_clone.find(".add_option").remove();
            $(".sortable").append(option_clone);
            break;
      }
   }

   function renumbering() {
      var total_number = $(".option-container-actual");
      $.each(total_number, function(key, value) {
         $(value).find(".numbering_option").text(key + 1);
         $(value).find(".option_type").find("input").attr("name", "option_" + key + 1);
      });
   }
   $(document).ready(function() {

      $.ajax({
         url: "<?php echo site_url('lms/survey/get_sheet'); ?>",
         type: "POST",
         data: survey_data,
         // contentType: "application/json",
         complete: function(response) {
            console.log(response.responseText);
            if (response.responseText) {
               stored_json = response.responseText;
               if (stored_json) {
                  $.each(JSON.parse(stored_json), function(key, value) {
                     populate_key(value.type);
                     $.each(value.option_labels.split(","), function(split_key, split_value) {
                        var last_option = $(".option-container-actual").eq(key).find(".option").length;
                        var option_clone = $(".option-container-actual").eq(key).find(".option").eq(last_option - 1).clone();
                        $(".option-container-actual").eq(key).find(".option").eq(last_option - 1).after(option_clone);
                     });
                     var the_last = $(".option-container-actual").eq(key).find(".option").length;
                     $.each(value.option_labels.split(","), function(value_key, value_value) {
                        $(".option-container-actual").eq(key).find(".option").eq(value_key).find(".option_label_input").find("input").val(value_value);

                     });
                     $(".option-container-actual").eq(key).find(".option").eq(the_last - 1).remove();



                  });
                  renumbering();
               }
            }
            // alert("Sucessfully Saved!");
         }
      });



   });
   $(document).on("click", ".remove_option", function() {
      $(this).parent().remove();
      renumbering();

   });
   $(".info-key").click(function() {
      var option_type = $(this).attr("option_type");
      populate_key(option_type);

      renumbering();
   });
   $(document).on("click", ".add_option", function() {
      var last_option = $(this).parent().find(".option").length;
      var option_clone = $(this).siblings(".option").eq(last_option - 1).clone();
      $(this).parent().find(".option").eq(last_option - 1).after(option_clone);

   });
   $(".save").click(function() {
      var json = [];
      var options = $(".option-container-actual");
      $.each(options, function(key, value) {
         var the_option_type = $(value).attr("option_type");

         if (the_option_type == "multiple_choice" || the_option_type == "multiple_answer") {
            var option_val = [];
            $.each($(value).find(".option"), function(option_key, option_value) {
               option_val.push($(option_value).find(".option_label_input").find("input").val());
            });
            option_json = {
               "type": the_option_type,
               "option_labels": option_val.join(","),
            };
         } else {
            option_json = {
               "type": the_option_type,
               "option_labels": "",
            };
         }
         json.push(option_json);



      });
      var student_ids = [];
      $.each(jstree.jstree("get_checked", null, true), function(key, value) {

         if (value.includes('student')) {
            student_id = value.replace('student_', '');

            student_ids.push(student_id);
         }
      });

      final_json = {
         id: "<?php echo $survey['id'] ?>",
         sheet: JSON.stringify(json),
         start_date: moment($(".date_range").data('daterangepicker').startDate.toDate()).format("YYYY-MM-DD HH:mm:ss"),
         end_date: moment($(".date_range").data('daterangepicker').endDate.toDate()).format("YYYY-MM-DD HH:mm:ss"),
         assigned: student_ids.join(','),
      };

      if (final_json) {
         $.ajax({
            url: url,
            type: "POST",
            data: final_json,
            // contentType: "application/json",
            complete: function(response) {
               console.log();
               if (response.responseText) {
                  alert("Sucessfully Saved!");
               }

            }
         });
      } else {
         alert("The survey can't be saved. Please use a compatible browser or device.");
      }

   });

   $(".assign_panel").hide();
   $(".assign").click(function() {
      $(".assign_panel").toggle();
      $(".sortable").toggle();
   });


   $('.date_range').daterangepicker({
      timePicker: true,
      startDate: moment().startOf('hour'),
      endDate: moment().startOf('hour').add(24, 'hour'),
      locale: {
         format: 'MMMM DD hh:mm A'
      }
   });

   if ($(".start_date").val() != '0000-00-00 00:00:00') {
      $('.date_range').daterangepicker({
         timePicker: true,
         startDate: moment($(".start_date").val()),
         endDate: moment($(".end_date").val()),
         locale: {
            format: 'MMMM DD hh:mm A'
         }
      });
   } else {

      $('.date_range').daterangepicker({
         timePicker: true,
         startDate: moment().startOf('hour'),
         endDate: moment().startOf('hour').add(24, 'hour'),
         locale: {
            format: 'MMMM DD hh:mm A'
         }
      });
   }

   var checked_ids = [];
   if (assigned) {

      $.each(assigned.split(","), function(key, value) {
         checked_ids.push("student_" + value);
      });
      $.jstree.reference('#jstree_demo_div').select_node(checked_ids);
   }
</script>
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
      .table_td {
         position: relative;
         width: 40px;
      }

      .table_input {
         position: absolute;
         display: block;
         top: 0;
         left: 0;
         margin: 0;
         height: 100%;
         width: 100%;
         border: none;
         padding: 10px;
         box-sizing: border-box;
         padding: 5px;
      }

      .column_highest_score {
         position: absolute;
         display: block;
         top: 0;
         left: 0;
         margin: 0;
         height: 100%;
         width: 100%;
         border: none;
         padding: 10px;
         box-sizing: border-box;
         padding: 5px;
      }

      .column_highest_score_td {
         position: relative;
         width: 40px;
      }

      .total {
         background-color: #ffff97;
      }

      .ps {
         background-color: aquamarine;
      }

      .ws {
         background-color: bisque;
      }

      .male {
         background-color: #b3b3f7;
      }

      .female {
         background-color: pink;
      }

      /* . {
         border: 2px solid black !important;
      } */

      .center {
         text-align: center;
         /* padding-top: 17px !important; */
      }

      .attendance {
         background-color: #cfe2f3;
      }

      .behavior {
         background-color: #ead1dc;
      }
   </style>

</head>

<body>

   <div class="container-fluid">
      <div class="row row-height">

         <div class="col-sm-12">
            <center>
               <h3>Conduct Grade</h3>
            </center>
         </div>
         <div class="col-sm-12">
            <table class="table" style="margin-bottom: 0px;">
               <tr>
                  <!-- <td>School Name</td>
                  <td><input type="" name="school_name" class="form-control school_name class_record" value="<?php echo $class_record['school_name'] ?>"></td> -->
                  <td>School ID</td>
                  <td><input type="" name="school_id" class="form-control school_id class_record" value="<?php echo $class_record['school_id'] ?>"></td>
                  <td>School Year</td>
                  <td><?php echo $school_year ?></td>
                  <form class="change_grade_section_form class_record" method="post" action="<?php echo base_url('lms/grading/update_grade_section/' . $class_record['id']) ?>">

                     <?php $disable = $real_role == 1 || $real_role == 7 ? "" : "disabled"; ?>

                     <td>Semester</td>
                     <td>
                        <select name="quarter" class="form-control grade_section quarter" <?php echo $disable; ?>>
                           <?php foreach ($quarters as $quarter_key => $quarter_value) : ?>
                              <option <?php echo ($quarter_value['id'] == $class_record['quarter']) ? "selected" : ""; ?> value="<?php echo $quarter_value['id'] ?>"><?php echo $quarter_value['description']; ?></option>
                           <?php endforeach ?>
                        </select>
                     </td>
               </tr>

               <tr>
                  <td>Grade</td>

                  <td width="173px">
                     <select class="form-control grade_section" name="grade" <?php echo $disable; ?>>
                        <?php foreach ($classes as $class_key => $class_value) : ?>
                           <option <?php echo ($class_value['id'] == $class_record['grade']) ? "selected" : ""; ?> value="<?php echo $class_value['id'] ?>"><?php echo $class_value['class']; ?></option>
                        <?php endforeach ?>
                     </select>
                  </td>
                  <td>Section</td>
                  <td>
                     <select class="form-control grade_section class_record" name="section" <?php echo $disable; ?>>
                        <?php foreach ($sections as $section_key => $section_value) : ?>
                           <option <?php echo ($section_value['id'] == $class_record['section_id']) ? "selected" : ""; ?> value="<?php echo $section_value['id'] ?>"><?php echo $section_value['section']; ?></option>
                        <?php endforeach ?>
                     </select>
                  </td>


                  <td>Teacher</td>
                  <!-- <td><?php echo $class_record['name'] ?> <?php echo $class_record['surname'] ?></td> -->
                  <td>
                     <select class="form-control grade_section class_record" name="teacher" <?php echo $disable; ?>>
                        <<?php foreach ($teachers as $teacher_key => $teacher_value) : ?> <option value="<?php echo $teacher_value['id'] ?>" <?php echo ($teacher_value['id'] == $class_record['teacher_id']) ? "selected" : ""; ?>><?php echo $teacher_value['name'] ?> <?php echo $teacher_value['surname'] ?></option>
                        <?php endforeach ?>
                     </select>
                  </td>
                  <td>Subject</td>
                  <td>
                     <select class="form-control grade_section class_record" name="subject" <?php echo $disable; ?>>
                        <<?php foreach ($subjects as $subject_key => $subject_value) : ?> <option value="<?php echo $subject_value['id'] ?>" <?php echo ($subject_value['id'] == $class_record['subject_id']) ? "selected" : ""; ?>><?php echo $subject_value['name']; ?></option>
                        <?php endforeach ?>
                     </select>
                  </td>

                  </form>
               </tr>
            </table>
         </div>

         <div class="col-sm-12 text-right" style="margin-bottom: 10px;">
            <a href="<?php echo base_url('lms/grading/index') ?>"><button class="btn btn-danger">Save & Close</button></a>
         </div>

         <table class="table table-bordered">
            <tr>
               <!-- <td colspan="2" class="">&nbsp;</td> -->
               <td class="text-center" colspan="2" rowspan="3" style="vertical-align: middle;"><b>Student's Name</b></td>

               <?php foreach ($criteria as $criteria_key => $criteria_value) :
                  if ($criteria_value['name'] == "Behavior") : ?>
                     <td class="criteria behavior text-center criteria-<?php echo $criteria_key ?> table_td " colspan="<?php echo ($criteria_value['name'] == "Behavior" ? $criteria_value['criteria_column'] + 1 : $criteria_value['criteria_column']); ?>" style="width: 15em; padding: 18px">
                        <b><input type="text" class="form-control table_input text-center behavior" name="" value="<?php echo $criteria_value['name'] ?>"></b>
                        <!-- <b><?php echo $criteria_value['name'] ?></b> -->
                     </td>
                  <?php else : ?>
                     <td class="criteria attendance text-center criteria-<?php echo $criteria_key ?> table_td " colspan="<?php echo ($criteria_value['name'] == "Behavior" ? $criteria_value['criteria_column'] + 1 : $criteria_value['criteria_column']); ?>" style="width: 15em; padding: 18px">
                        <b><input type="text" class="form-control table_input text-center attendance" name="" value="<?php echo $criteria_value['name'] ?>"></b>
                        <!-- <b><?php echo $criteria_value['name'] ?></b> -->
                     </td>
               <?php endif;
               endforeach; ?>

               <td class="text-center" rowspan="3" style="vertical-align: middle;"><b>Final Grade</b></td>
               <td class="text-center" rowspan="3" style="vertical-align: middle;"><b>Code</b></td>
            </tr>

            <tr>
               <!-- <td colspan="2" class=""></td> -->

               <?php foreach ($criteria as $criteria_key => $criteria_value) :
                  foreach ($criteria_value['column_section'] as $column_section_key => $column_section_value) :
                     foreach ($column_section_value['column'] as $column_key => $column_value) :
                        if ($criteria_value['name'] == "Behavior") : ?>
                           <td class="column column-1 text-center behavior" nowrap><?php echo $column_value["column_label"]; ?></td>
                        <?php else : ?>
                           <td class="column column-1 text-center attendance" nowrap><?php echo $column_value["column_label"]; ?></td>
                        <?php endif;
                     endforeach;

                     if ($criteria_value['name'] == "Behavior") : ?>
                        <td class="text-center behavior" nowrap>B Score</td>
                        <td class="text-center behavior" nowrap>B Grade</td>
                        <td class="text-center behavior">%</td>
                     <?php else : ?>
                        <td class="text-center attendance" nowrap>Att Ave</td>
                        <td class="text-center attendance">%</td>
               <?php endif;
                  endforeach;
               endforeach; ?>

               <!-- <td class=""></td>
               <td class="quarterly_grade"></td> -->
            </tr>

            <tr>
               <!-- <td colspan="2" class=""></td> -->

               <?php foreach ($criteria as $criteria_key => $criteria_value) :
                  foreach ($criteria_value['column_section'] as $column_section_key => $column_section_value) :
                     foreach ($column_section_value['column'] as $column_key => $column_value) :
                        if ($criteria_value['name'] == "Behavior") : ?>
                           <td class="column column-1 column_highest_score_td behavior" style="padding: 18px">
                              <input class="behavior text-center column_highest_score highest_score highest_score-<?php echo $criteria_key ?>_<?php echo $column_section_key ?>" column_id="<?php echo $column_value['id'] ?>" value="<?php echo $column_value['highest_score'] ?>" type="text" name="" criteria="<?php echo $criteria_key ?>" section="<?php echo $column_section_key ?>">
                           </td>
                        <?php else : ?>
                           <td class="column column-1 column_highest_score_td attendance" style="padding: 18px">
                              <input class="attendance text-center column_highest_score highest_score highest_score-<?php echo $criteria_key ?>_<?php echo $column_section_key ?>" column_id="<?php echo $column_value['id'] ?>" value="<?php echo $column_value['highest_score'] ?>" type="text" name="" criteria="<?php echo $criteria_key ?>" section="<?php echo $column_section_key ?>">
                           </td>
                        <?php endif;
                     endforeach;

                     if ($criteria_value['name'] != "Behavior") : ?>
                        <td class="center attendance"></td>
                        <td class="table_td attendance"><input type="text" class="attendance text-center ws_input table_input ws-<?php echo $criteria_key ?>_<?php echo $column_section_key ?>" name="" value="<?php echo $column_section_value['ws'] ?>" column_section="<?php echo $column_section_value['id'] ?>"></td>
                     <?php else : ?>
                        <td class="center behavior"></td>
                        <td class="center behavior"></td>
                        <td class="table_td behavior"><input type="text" class="behavior text-center ws_input table_input ws-<?php echo $criteria_key ?>_<?php echo $column_section_key ?>" name="" value="<?php echo $column_section_value['ws'] ?>" column_section="<?php echo $column_section_value['id'] ?>"></td>
               <?php endif;
                  endforeach;
               endforeach; ?>

               <!-- <td></td>
               <td class="quarterly_grade"></td> -->
            </tr>

            <tr>
               <td class="male " colspan="<?php echo $full_width + 4 ?>">Male</td>
               <td class="male  quarterly_grade"></td>
            </tr>

            <?php
            $male_count = 1;
            $all_count = 1;

            foreach ($students as $student_key => $student_value) :
               if ($student_value['gender'] == "male") : ?>
                  <tr row_count="<?php echo $all_count; ?>">
                     <td class="students" student_key="<?php echo $student_key ?>"><?php echo $male_count ?></td>
                     <td nowrap><?php echo $student_value['lastname'] ?>, <?php echo $student_value['firstname'] ?></td>
                     <?php $column_count = 1; ?>
                     <?php foreach ($criteria as $criteria_key => $criteria_value) :
                        foreach ($criteria_value['column_section'] as $column_section_key => $column_section_value) :
                           foreach ($column_section_value['column'] as $column_key => $column_value) :
                              if ($criteria_value['name'] == "Behavior") : ?>
                                 <td column_count="<?php echo $column_count; ?>" class="column column-1 table_td" section="1">
                                    <input class="text-center column_score_behavior table_input column-score-behavior" column_sequence_behavior="<?php echo $student_key ?>_<?php echo $criteria_key ?>_<?php echo $column_section_key ?>" column_id="<?php echo $column_value['id'] ?>" student_id="<?php echo $student_value['id'] ?>" type="text" name="" value="<?php echo $the_class->get_column_score($column_value['id'], $student_value['id']) ?>">
                                 </td>
                              <?php else : ?>
                                 <td column_count="<?php echo $column_count; ?>" class="column column-1 table_td" section="1">
                                    <input class="text-center column_score_attendance table_input column-score-attendance" column_sequence_attendance="<?php echo $student_key ?>_<?php echo $criteria_key ?>_<?php echo $column_section_key ?>" column_id="<?php echo $column_value['id'] ?>" student_id="<?php echo $student_value['id'] ?>" type="text" name="" value="<?php echo $the_class->get_column_score($column_value['id'], $student_value['id']) ?>">
                                 </td>
                              <?php endif;
                              $column_count++;
                           endforeach;

                           if ($criteria_value['name'] != "Behavior") : ?>
                              <td class="center att_ave-<?php echo $student_key ?>_<?php echo $criteria_key ?>_<?php echo $column_section_key ?>"></td>
                              <td class="center ws-<?php echo $student_key ?>_<?php echo $criteria_key ?>_<?php echo $column_section_key ?> ws-<?php echo $student_key ?>"></td>
                           <?php else : ?>
                              <td class="center bscore-<?php echo $student_key ?>_<?php echo $criteria_key ?>_<?php echo $column_section_key ?>" column_sequence="<?php echo $student_key ?>_<?php echo $criteria_key ?>_<?php echo $column_section_key ?>"></td>
                              <td class="center bgrade-<?php echo $student_key ?>_<?php echo $criteria_key ?>_<?php echo $column_section_key ?>"></td>
                              <td class="center ws-<?php echo $student_key ?>_<?php echo $criteria_key ?>_<?php echo $column_section_key ?> ws-<?php echo $student_key ?>"></td>
                     <?php endif;
                        endforeach;
                     endforeach; ?>

                     <td class="text-center final_grade-<?php echo $student_key ?> final_grade-<?php echo $student_key ?>_<?php echo $criteria_key ?>_<?php echo $column_section_key ?>"></td>
                     <td class="text-center coded_grade-<?php echo $student_key ?> coded_grade-<?php echo $student_key ?>_<?php echo $criteria_key ?>_<?php echo $column_section_key ?>"></td>
                  </tr>
            <?php
                  $male_count++;
                  $all_count++;
               endif;
            endforeach; ?>

            <tr>
               <td class="female " colspan="<?php echo $full_width + 5 ?>">Female</td>
            </tr>

            <?php
            $male_count = 1;

            foreach ($students as $student_key => $student_value) :
               if ($student_value['gender'] == "female") : ?>
                  <tr row_count="<?php echo $all_count; ?>">
                     <td class="students" student_key="<?php echo $student_key ?>"><?php echo $male_count ?></td>
                     <td nowrap><?php echo $student_value['lastname'] ?>, <?php echo $student_value['firstname'] ?></td>

                     <?php
                     $column_count = 1;

                     foreach ($criteria as $criteria_key => $criteria_value) :
                        foreach ($criteria_value['column_section'] as $column_section_key => $column_section_value) :
                           foreach ($column_section_value['column'] as $column_key => $column_value) :
                              if ($criteria_value['name'] == "Behavior") : ?>
                                 <td column_count="<?php echo $column_count; ?>" class="column column-1 table_td" section="1">
                                    <input class="text-center column_score_behavior table_input column-score-behavior" column_sequence_behavior="<?php echo $student_key ?>_<?php echo $criteria_key ?>_<?php echo $column_section_key ?>" column_id="<?php echo $column_value['id'] ?>" student_id="<?php echo $student_value['id'] ?>" type="text" name="" value="<?php echo $the_class->get_column_score($column_value['id'], $student_value['id']) ?>">
                                 </td>
                              <?php else : ?>
                                 <td column_count="<?php echo $column_count; ?>" class="column column-1 table_td" section="1">
                                    <input class="text-center column_score_attendance table_input column-score-attendance" column_sequence_attendance="<?php echo $student_key ?>_<?php echo $criteria_key ?>_<?php echo $column_section_key ?>" column_id="<?php echo $column_value['id'] ?>" student_id="<?php echo $student_value['id'] ?>" type="text" name="" value="<?php echo $the_class->get_column_score($column_value['id'], $student_value['id']) ?>">
                                 </td>
                              <?php endif;
                              $column_count++;
                           endforeach;

                           if ($criteria_value['name'] != "Behavior") : ?>
                              <td class="center att_ave-<?php echo $student_key ?>_<?php echo $criteria_key ?>_<?php echo $column_section_key ?>"></td>
                              <td class="center ws-<?php echo $student_key ?>_<?php echo $criteria_key ?>_<?php echo $column_section_key ?> ws-<?php echo $student_key ?>"></td>
                           <?php else : ?>
                              <td class="center bscore-<?php echo $student_key ?>_<?php echo $criteria_key ?>_<?php echo $column_section_key ?>"></td>
                              <td class="center bgrade-<?php echo $student_key ?>_<?php echo $criteria_key ?>_<?php echo $column_section_key ?>"></td>
                              <td class="center ws-<?php echo $student_key ?>_<?php echo $criteria_key ?>_<?php echo $column_section_key ?> ws-<?php echo $student_key ?>"></td>
                        <?php endif;
                        endforeach; ?>
                     <?php endforeach; ?>

                     <td class="text-center final_grade-<?php echo $student_key ?> final_grade-<?php echo $student_key ?>_<?php echo $criteria_key ?>_<?php echo $column_section_key ?>"></td>
                     <td class="text-center coded_grade-<?php echo $student_key ?> coded_grade-<?php echo $student_key ?>_<?php echo $criteria_key ?>_<?php echo $column_section_key ?>"></td>
                  </tr>

                  <?php
                  $male_count++;
                  $all_count++; ?>
               <?php endif; ?>
            <?php endforeach; ?>
         </table>

         <div class="col-sm-8">
         </div>
      </div>
   </div>

   <input type="hidden" id="url" value="<?php echo site_url('lms/assessment/update'); ?>" name="" />
   <input type="hidden" id="base_url" value="<?php echo base_url(); ?>" name="" />

</body>

</html>
<script type="text/javascript" src="<?php echo $resources . 'jquery-1.12.4.js' ?>"></script>
<script type="text/javascript" src="<?php echo $resources . 'jquery-ui.js' ?>"></script>

<script type="text/javascript" src="https://nosir.github.io/cleave.js/dist/cleave.min.js"></script>
<script type="text/javascript" src="https://nosir.github.io/cleave.js/dist/cleave-phone.i18n.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<!-- <script type="text/javascript" src="<?php //echo $resources . 'check_essays_4.js' 
                                          ?>"></script> -->

<script type="text/javascript">
   var url = "<?php echo base_url('lms/grading/') ?>";
   var grade_code = JSON.parse('<?php echo $transmutation ?>');
   var subject_transmuted = "<?php echo $subject['transmuted']; ?>";

   if (subject_transmuted == "0") {
      $(".quarterly_grade").hide();
   }

   // function onlyUnique(value, index, self) {
   //    return self.indexOf(value) === index;
   // }

   // $(".column_score").change(function() {
   //    var score = $(this).val();
   //    var column_id = $(this).attr("column_id");
   //    var student_id = $(this).attr("student_id");

   //    var update_column = {
   //       score: score,
   //       column_id: column_id,
   //       student_id: student_id,
   //    }

   //    $.ajax({
   //       url: url + "update_column",
   //       type: "POST",
   //       data: update_column,
   //       complete: function(response) {
   //          console.log(response.responseText);
   //       }
   //    });
   // });

   $(".column_score_behavior").change(function() {
      var score = $(this).val();
      var column_id = $(this).attr("column_id");
      var student_id = $(this).attr("student_id");

      var update_column = {
         score: score,
         column_id: column_id,
         student_id: student_id,
      }

      $.ajax({
         url: url + "update_column",
         type: "POST",
         data: update_column,
         complete: function(response) {
            console.log(response.responseText);
         }
      });

      update_highest_score();
      update_column_score_behavior();
      update_final_grade();
      update_coded_grade();
   });

   $(".column_score_attendance").change(function() {
      var score = $(this).val();
      var column_id = $(this).attr("column_id");
      var student_id = $(this).attr("student_id");

      var update_column = {
         score: score,
         column_id: column_id,
         student_id: student_id,
      }

      $.ajax({
         url: url + "update_column",
         type: "POST",
         data: update_column,
         complete: function(response) {
            console.log(response.responseText);
         }
      });

      update_highest_score();
      update_column_score_attendance();
      update_final_grade();
      update_coded_grade();
   });

   $(".highest_score").change(function() {
      var highest_score = $(this).val();
      var column_id = $(this).attr("column_id");
      var update_data = {
         highest_score: highest_score,
         column_id: column_id,
      }

      $.ajax({
         url: url + "update_highest_score",
         type: "POST",
         data: update_data,
         complete: function(response) {
            console.log(response.responseText);
         }
      });
   });

   $(".table_input").on('keypress', function(e) {
      if (e.which == 13) {
         var column_count = $(this).parent().attr("column_count");
         var row_count = $(this).parent().parent().attr("row_count");
         var row_count_1 = parseInt(row_count) + 1;
         $("[row_count=" + row_count_1 + "]").find("td[column_count=" + column_count + "]").find("input").focus();
      }
   });

   $(".grade_section").change(function() {
      $(".change_grade_section_form").submit();
   });

   function update_class_record() {
      var class_record_id = '<?php echo $class_record['id'] ?>';
      var school_name = $(".school_name").val();
      var school_id = $(".school_id").val();

      update_data = {
         id: class_record_id,
         school_name: school_name,
         school_id: school_id,
      }

      $.ajax({
         url: url + "update_class_record",
         type: "POST",
         data: update_data,
         complete: function(response) {
            console.log(response.responseText);
         }
      });
   }

   function update_highest_score() {
      var sequence = {};

      $.each($(".highest_score"), function(key, value) {
         var sequences = $(value).attr("criteria") + "_" + $(value).attr("section");
         sequence[sequences] = [];
      });

      $.each($(".highest_score"), function(key, value) {
         var the_sequence = $(value).attr("criteria") + "_" + $(value).attr("section");
         sequence[the_sequence].push($(value).val());
      });

      $.each(sequence, function(key, value) {
         var sum = sequence[key].reduce(function(a, b) {
            if (!b) {
               b = 0;
            }

            return parseInt(a) + parseInt(b);
         }, 0);

         $("." + key).text(sum);
      });
   }

   function update_column_score_behavior() {
      var score_total = {};
      var final_grade = 0;

      $.each($(".column-score-behavior"), function(key, value) {
         score_total[$(value).attr("column_sequence_behavior")] = [];
      });

      $.each($(".column-score-behavior"), function(column_score_key, column_score_value) {
         var tsequence = $(column_score_value).attr("column_sequence_behavior");

         if (score_total[tsequence]) {
            score_total[tsequence].push($(column_score_value).val());
         }
      });

      var initial_grade = {};
      $.each(score_total, function(key, value) {
         var sum = score_total[key].reduce(function(a, b) {
            if (!b) {
               b = 0;
            }
            return parseInt(a) + parseInt(b);
         }, 0);

         $(".bscore-" + key).text(sum);
         $(".bgrade-" + key).text((sum / 20) * 100);
         var splitted_key = key.split("_").slice(1).join("_");
         var ws = parseInt($(".ws-" + key.split("_").slice(1).join("_")).val());
         var bgrade = parseFloat($(".bgrade-" + key).text());
         var ws_values = roundNumberV2((bgrade * (ws / 100)), 2);
         $(".ws-" + key).text(ws_values);
      });
   }

   function update_column_score_attendance() {
      var score_total = {};
      var final_grade = 0;

      $.each($(".column-score-attendance"), function(key, value) {
         score_total[$(value).attr("column_sequence_attendance")] = [];
      });

      $.each($(".column-score-attendance"), function(column_score_key, column_score_value) {
         var tsequence = $(column_score_value).attr("column_sequence_attendance");

         if (score_total[tsequence]) {
            score_total[tsequence].push($(column_score_value).val());
         }
      });

      var initial_grade = {};
      $.each(score_total, function(key, value) {
         var sum = score_total[key].reduce(function(a, b) {
            if (!b) {
               b = 0;
            }
            return parseInt(a) + parseInt(b);
         }, 0);

         $(".att_ave-" + key).text(sum / 2);
         var splitted_key = key.split("_").slice(1).join("_");
         var ws = parseInt($(".ws-" + key.split("_").slice(1).join("_")).val());
         var att_ave = parseFloat($(".att_ave-" + key).text());
         var ws_values = roundNumberV2(att_ave * (ws / 100), 2);
         $(".ws-" + key).text(ws_values);
      });
   }

   function update_final_grade() {
      $.each($(".students"), function(key, value) {
         var student_key = $(value).attr("student_key");
         var all_ws = $(".ws-" + student_key);
         var final_grade = 0;

         $.each(all_ws, function(ws_key, ws_value) {
            final_grade += parseFloat($(ws_value).text());
         });

         if (final_grade > 0) {
            $(".final_grade-" + student_key).text(roundNumberV2(final_grade, 2));

            if (roundNumberV2(final_grade, 0) < 80) {
               $(".final_grade-" + student_key).css("background-color", "rgb(255 185 185)");
            } else if (roundNumberV2(final_grade, 0) >= 90) {
               $(".final_grade-" + student_key).css("background-color", "rgb(191 246 191)");
            } else {
               $(".final_grade-" + student_key).css("background-color", "rgb(255 255 255)");
            }
         }
      });
   }

   function update_coded_grade() {
      $.each($(".students"), function(key, value) {
         var student_key = $(value).attr("student_key");
         var final_grade = parseFloat($(".final_grade-" + student_key).text());

         console.log(final_grade);

         if (final_grade > 0) {
            $.each(grade_code, function(grade_code_key, grade_code_value) {
               if (final_grade <= parseFloat(grade_code_value.max_grade) && final_grade >= parseFloat(grade_code_value.min_grade)) {
                  $(".coded_grade-" + student_key).text(grade_code_value.grade_code);

                  if (parseFloat(final_grade) < 80) {
                     $(".coded_grade-" + student_key).css("background-color", "rgb(255 185 185)");
                  } else if (parseFloat(final_grade) >= 90) {
                     $(".coded_grade-" + student_key).css("background-color", "rgb(191 246 191)");
                  } else {
                     $(".coded_grade-" + student_key).css("background-color", "rgb(255 255 255)");
                  }
               }
            });
         }
      });
   }

   $(".class_record").change(function() {
      update_class_record();
   });

   $(document).ready(function() {
      update_highest_score();
      update_column_score_behavior();
      update_column_score_attendance();
      update_final_grade();
      update_coded_grade();
   });


   $(".highest_score").change(function() {
      update_highest_score();
      update_column_score_behavior();
      update_column_score_attendance();
      update_final_grade();
      update_coded_grade();
   });

   $(".ws_input").change(function() {
      var column_section = $(this).attr("column_section");
      var highest_score = $(this).val();
      var update_data = {
         highest_score: highest_score,
         column_section: column_section,
      }
      $.ajax({
         url: url + "update_ws",
         type: "POST",
         data: update_data,
         complete: function(response) {
            console.log(response.responseText);

         }
      });

      update_highest_score();
      update_column_score_behavior();
      update_column_score_attendance();
      update_final_grade();
      update_coded_grade();
   });

   // function set_local_data(name, value) {
   // 	try {
   // 		remove_local_data(name);
   // 		localStorage.setItem(name, value);
   // 	} catch (error) { console.log('Error writing to local storage') }   
   // }

   // function read_local_data(name) {
   // 	var result = null;
   // 	try {
   // 		result = localStorage.getItem(name);
   // 	} catch (error) { console.log('Error reading local storage') }    

   // 	return result;
   // }

   // function remove_local_data(name) {
   // 	try {
   // 		localStorage.removeItem(name);
   // 	} catch(error) {}    
   // }

   function roundNumberV1(num, scale) {
      if (!("" + num).includes("e"))
         return +(Math.round(num + "e+" + scale) + "e-" + scale);
      else {
         var arr = ("" + num).split("e");
         var sig = ""
         if (+arr[1] + scale > 0) {
            sig = "+";
         }
         var i = +arr[0] + "e" + sig + (+arr[1] + scale);
         var j = Math.round(i);
         var k = +(j + "e-" + scale);
         return k;
      }
   }

   function roundNumberV2(num, scale) {
      if (Math.round(num) != num) {
         if (Math.pow(0.1, scale) > num)
            return 0;

         var sign = Math.sign(num);
         var arr = ("" + Math.abs(num)).split(".");

         if (arr.length > 1) {
            if (arr[1].length > scale) {
               var integ = +arr[0] * Math.pow(10, scale);
               var dec = integ + (+arr[1].slice(0, scale) + Math.pow(10, scale));
               var proc = +arr[1].slice(scale, scale + 1)

               if (proc >= 5)
                  dec = dec + 1;

               dec = sign * (dec - Math.pow(10, scale)) / Math.pow(10, scale);
               return dec;
            }
         }
      }
      return num;
   }
</script>
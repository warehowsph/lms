<div class="content-wrapper" style="min-height: 946px;">
   <!-- Main content -->
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box removeboxmius">
               <div class="box-header ptbnull"></div>

               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-search"></i> <?php echo "Student Conduct"; ?></h3>
               </div>

               <div class="box-body">
                  <form role="form" action="<?php echo site_url('lms/conduct_ssapamp') ?>" method="post" class="">
                     <div class="row">
                        <?php echo $this->customlib->getCSRF(); ?>
                        <div class="col-sm-6 col-md-2">
                           <div class="form-group">
                              <label><?php echo $this->lang->line('current_session'); ?></label><small class="req"> *</small>
                              <select autofocus="" id="session_id" name="session_id" class="form-control">
                                 <option value=""><?php echo $this->lang->line('select'); ?></option>
                                 <?php
                                 foreach ($session_list as $session) {
                                 ?>
                                    <option value="<?php echo $session['id'] ?>" <?php if ($session['id'] == $sch_setting->session_id) echo "selected=selected" ?>><?php echo $session['session'] ?></option>
                                 <?php
                                 }
                                 ?>
                              </select>
                              <span class="text-danger"><?php echo form_error('session_id'); ?></span>
                           </div>
                        </div>

                        <div class="col-sm-6 col-md-2">
                           <div class="form-group">
                              <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                              <select autofocus="" id="class_id" name="class_id" class="form-control">
                                 <option value=""><?php echo $this->lang->line('select'); ?></option>
                                 <?php
                                 foreach ($classlist as $class) {
                                 ?>
                                    <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                 <?php
                                 }
                                 ?>
                              </select>
                              <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                           </div>
                        </div>

                        <div class="col-sm-6 col-md-2">
                           <div class="form-group">
                              <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                              <select id="section_id" name="section_id" class="form-control">
                                 <option value=""><?php echo $this->lang->line('select'); ?></option>
                              </select>
                              <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                           </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                           <div class="form-group">
                              <label><?php echo $this->lang->line('student'); ?></label><small class="req"> *</small>
                              <select autofocus="" id="student_id" name="student_id" class="form-control">
                                 <option value=""><?php echo $this->lang->line('select'); ?></option>
                              </select>
                              <span class="text-danger"><?php echo form_error('student_id'); ?></span>
                           </div>
                        </div>

                        <div class="col-sm-6 col-md-2">
                           <div class="form-group">
                              <label><?php echo "Term"; ?></label><small class="req"> *</small>
                              <select autofocus="" id="quarter_id" name="quarter_id" class="form-control">
                                 <option value=""><?php echo $this->lang->line('select'); ?></option>
                                 <?php
                                 foreach ($quarter_list as $quarter) {
                                 ?>
                                    <option value="<?php echo $quarter['id'] ?>" <?php if (set_value('quarter_id') == $quarter['id']) echo "selected=selected" ?>><?php echo $quarter['description'] ?></option>
                                 <?php
                                 }
                                 ?>
                              </select>
                              <span class="text-danger"><?php echo form_error('quarter_id'); ?></span>
                           </div>
                        </div>

                        <div class="form-group">
                           <div class="col-sm-12">
                              <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('show'); ?></button>
                           </div>
                        </div>
                     </div>
                     <!--./row-->
                  </form>
               </div>
               <!--./box-body-->

               <div class="">
                  <form id='frm_conduct_grades' action="<?php //echo site_url('lms/conduct/save_conduct_grades_numeric') 
                                                         ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                     <!-- submit hidden values -->
                     <input type="hidden" name="session_id" value="<?php echo $session_id ?>">
                     <input type="hidden" name="quarter_id" value="<?php echo $quarter_id ?>">
                     <input type="hidden" name="class_id" value="<?php echo $class_id ?>">
                     <input type="hidden" name="section_id" value="<?php echo $section_id ?>">
                     <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                     <input type="hidden" name="student_id" id="student_id" value="<?php echo $student_id ?>">
                     <div class="box-header ptbnull"></div>
                     <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo form_error('class_record_quarterly'); ?> Student Conducts</h3>
                     </div>
                     <div class="box-body table-responsive">
                        <?php if (isset($resultlist)) { ?>
                           <section class="content-header">
                              <h1><i class="fa fa-calendar-times-o"></i> <?php echo $this->lang->line('grades'); ?> </h1>
                           </section>
                           <!-- Main content -->
                           <section class="content">
                              <div class="row">
                                 <div class="col-md-12">
                                    <div class="box box-warning">
                                       <div class="box-header ptbnull">
                                          <h3 class="box-title titlefix"> <?php echo ""; ?></h3>
                                          <div class="box-tools pull-right"></div>
                                       </div>
                                       <div class="box-body">
                                          <div class="table-responsive">
                                             <?php if (!empty($resultlist)) { ?>
                                                <table class="table table-striped table-bordered table-hover example nowrap" cellspacing="0" width="100%" id="Tablesample">
                                                   <thead>
                                                      <?php
                                                      // if  ($class_id==$prekinder or $class_id==$kinder) {
                                                      if ($conductformat == "1") {
                                                      ?>
                                                         <tr>
                                                            <th class="text-center"></th>
                                                            <th class="text-center">NUMBER GRADE</th>
                                                            <th class="text-center">LG</th>
                                                            <!-- <th class="text-center">Conduct Grade</th> -->
                                                         </tr>
                                                      <?php
                                                      } else {
                                                      ?>
                                                         <tr>
                                                            <th><?php echo $this->lang->line('roll_no'); ?></th>
                                                            <th><?php echo $this->lang->line('student_name'); ?></th>
                                                            <th><?php echo $this->lang->line('gender'); ?></th>
                                                            <th class="text-center">Conduct Grade</th>
                                                         </tr>
                                                      <?php
                                                      }
                                                      ?>
                                                   </thead>
                                                   <tbody>
                                                      <?php if (!empty($resultlist)) {
                                                         // if ($class_id==$prekinder or $class_id==$kinder) {
                                                         if ($conductformat == "1") {
                                                            foreach ($Subjects as $row) {
                                                               echo "<tr>\r\n";
                                                               echo "<td class='text-left'>" . $row->alpha . ". " . $row->description . "</td>\r\n";
                                                               //
                                                               $ccid = $row->id;
                                                               $key = array_search($ccid, array_column($resultlist, 'clid'));

                                                               if ($key) {
                                                                  // var_dump($gradelist[$key]);
                                                                  // echo "<br>";
                                                                  $ssid =  $resultlist[$key]['ssid'];
                                                                  $p1 = $resultlist[$key]['grade'];
                                                                  if ($p1 == "0") {
                                                                     $p1 = "";
                                                                  }
                                                                  $fg = $resultlist[$key]['lg'];
                                                               } else {
                                                                  $ssid = 0;
                                                                  $p1 = "";
                                                                  $fg = "";
                                                               }
                                                      ?>
                                                               <input type="hidden" id="ccid<?php echo $row->id; ?>" name="clid[]" value="<?php echo $ccid ?>">
                                                               <input type="hidden" id="ssid<?php echo $row->id; ?>" name="ssid[]" value="<?php echo $ssid ?>">
                                                               <td class='numgrades text-center'>
                                                                  <center><input type="text" style="border: none; text-align:center" name="grade[]" value="<?php echo $p1; ?>" onchange="calculateSum2(<?php echo $row->id ?>)" class="grade<?php echo $row->id ?>" min="1" max="10"></center>
                                                               </td>
                                                               <td class='text-center'>
                                                                  <center><input type="text" style="border: none; text-align:center" name="final[]" value="<?php echo $fg; ?>" id="fin<?php echo $row->id ?>" readonly></center>
                                                               </td>
                                                               </tr>
                                                      <?php
                                                            } // foreach subjects
                                                            echo "<tr>\r\n";
                                                            echo "<td class='text-left'><b>Average</b></td>\r\n";
                                                            echo "<td class='text-center'><label id='totalgrade'></label></td>\r\n";
                                                            echo "<td class='text-center'><label id='lettergrade'></label></td>\r\n";
                                                            echo "</tr>\r\n";
                                                         } else { // if $classid

                                                            foreach ($resultlist as $row) {
                                                               echo "<tr>\r\n";
                                                               echo "<td class='text-left'>" . $row->roll_no . "</td>\r\n";
                                                               echo "<td class='text-left'><a href=\"" . base_url() . "student/view/" . $row->id . "\">" . strtoupper($row->student_name) . "</a></td>\r\n";
                                                               echo "<td class='text-left'>" . strtoupper($row->gender) . "</td>\r\n";
                                                               echo "<td class='text-center'><input type=\"hidden\" name=\"studentidhidden[]\" value=" . $row->id . "><input type=\"number\" name=\"conductgrades[]\" value=\"" . $row->conduct_num . "\" min=\"60\" max=\"100\"></td>\r\n";
                                                               echo "</tr>\r\n";
                                                            }
                                                         } // else 

                                                      } // if !empty 
                                                      ?>

                                                   </tbody>
                                                </table>

                                             <?php } ?>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <?php
                                 // if  ($class_id==$prekinder  || $class_id==$kinder) {
                                 if ($conductformat == "1") {
                                 ?>
                                    <div class="col-md-3">
                                       <div class="box box-primary">
                                          <div class="box-body box-profile">
                                             <h3 class="profile-username text-center">LEGEND</h3>
                                             <ul class="list-group list-group-unbordered">
                                                <?php foreach ($legend_list as $legendrow) { ?>
                                                   <li class="list-group-item">
                                                      <b><?php echo $legendrow->conduct_grade; ?></b> <span class="pull-right"><?php echo $legendrow->grade_description . '(' . $legendrow->description . ')'; ?></span>
                                                   </li>
                                                <?php } ?>
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                 <?php } ?>
                              </div>
                              <div class="row">
                                 <div class="box-footer">
                                    <button type="submit" name="action" value="save_views" class="btn btn-primary pull-right submitviews" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Updating"><i class="fa fa-save"></i> <?php echo "Save"; ?></button>
                                 </div>
                              </div>
                           </section>
                        <?php } ?>
                     </div>
                  </form>
               </div>
            </div>
         </div> <!-- ./col-md-12 -->
      </div>
   </section>
</div>

<script type="text/javascript">
   // var class_id;
   var base_url = '<?php echo base_url() ?>';

   function getSectionByClass(class_id, section_id = -1) {
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

   function getTermByGradeLevel(class_id, term_id = -1) {
      if (class_id != "") {
         var base_url = '<?php echo base_url() ?>';
         var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

         $('#quarter_id').html("");

         $.ajax({
            type: "GET",
            url: base_url + "classes/get_grade_level_terms",
            data: {
               'class_id': class_id
            },
            dataType: "json",
            beforeSend: function() {
               $('#quarter_id').addClass('dropdownloading');
            },
            success: function(data) {
               $.each(data, function(i, obj) {
                  var sel = "";
                  if (term_id == obj.id) {
                     sel = "selected";
                  }
                  div_data += "<option value=" + obj.id + " " + sel + ">" + obj.description + "</option>";
               });
               $('#quarter_id').append(div_data);
            },
            complete: function() {
               $('#quarter_id').removeClass('dropdownloading');
            }
         });
      }
   }


   function getStudentsByClassSection(class_id, section_id, school_year_id, student_id) {
      if (class_id != "") {

         $('#student_id').html("");
         //if (class_id == 1) 
         {
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
               type: "GET",
               url: base_url + "student/getStudentListPerClassSection",
               data: {
                  'class_id': class_id,
                  'section_id': section_id,
                  'school_year_id': school_year_id
               },
               dataType: "json",
               beforeSend: function() {
                  $('#student_id').addClass('dropdownloading');
               },
               success: function(data) {
                  $.each(data, function(i, obj) {
                     var sel = "";
                     if (student_id == obj.student_id) {
                        sel = "selected";
                     }
                     div_data += "<option value=" + obj.student_id + " " + sel + ">" + obj.lastname + ", " + obj.firstname + "</option>";
                  });
                  $('#student_id').append(div_data);
               },
               complete: function() {
                  $('#student_id').removeClass('dropdownloading');
               }
            });
         }
      }
   }

   $(document).ready(function() {
      var term_id = '<?php echo set_value('quarter_id') ?>';
      var class_id = $('#class_id').val();
      var section_id = '<?php echo set_value('section_id') ?>';
      var school_year_id = '<?php echo set_value('session_id') ?>';
      var student_id = '<?php echo set_value('student_id') ?>';

      getSectionByClass(class_id, section_id);
      getStudentsByClassSection(class_id, section_id, school_year_id, student_id);
      getTermByGradeLevel(class_id, term_id);

      var table = $('.conductTable').DataTable({
         "aaSorting": [],
         rowReorder: {
            selector: 'td:nth-child(2)'
         },
         // pageLength: 100,
         //responsive: 'false',
         paging: false,
         ordering: false,
         searching: false,
         dom: "Bfrtip",
         buttons: [{
               extend: 'copyHtml5',
               text: '<i class="fa fa-files-o"></i>',
               titleAttr: 'Copy',
               title: $('.download_label').html(),
               exportOptions: {
                  columns: ':visible'
               }
            },

            {
               extend: 'excelHtml5',
               text: '<i class="fa fa-file-excel-o"></i>',
               titleAttr: 'Excel',

               title: $('.download_label').html(),
               exportOptions: {
                  columns: ':visible'
               }
            },

            {
               extend: 'csvHtml5',
               text: '<i class="fa fa-file-text-o"></i>',
               titleAttr: 'CSV',
               title: $('.download_label').html(),
               exportOptions: {
                  columns: ':visible'
               }
            },

            {
               extend: 'pdfHtml5',
               text: '<i class="fa fa-file-pdf-o"></i>',
               titleAttr: 'PDF',
               title: $('.download_label').html(),
               exportOptions: {
                  columns: ':visible'

               }
            },

            {
               extend: 'print',
               text: '<i class="fa fa-print"></i>',
               titleAttr: 'Print',
               title: $('.download_label').html(),
               customize: function(win) {
                  $(win.document.body)
                     .css('font-size', '10pt');

                  $(win.document.body).find('table')
                     .addClass('compact')
                     .css('font-size', 'inherit');
               },
               exportOptions: {
                  columns: ':visible'
               }
            },

            {
               extend: 'colvis',
               text: '<i class="fa fa-columns"></i>',
               titleAttr: 'Columns',
               title: $('.download_label').html(),
               postfixButtons: ['colvisRestore']
            },
         ],
         "columnDefs": [{
            "targets": [0],
            "visible": false,
            "searchable": false
         }]
      });

      $(document).on('change', '#class_id', function(e) {
         $('#section_id').html("");
         $('#student_id').html("");
         var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
         class_id = $(this).val();

         // $.ajax({
         //    type: "GET",
         //    url: base_url + "sections/getByClass",
         //    data: {
         //       'class_id': class_id
         //    },
         //    dataType: "json",
         //    beforeSend: function() {
         //       $('#section_id').addClass('dropdownloading');
         //    },
         //    success: function(data) {
         //       $.each(data, function(i, obj) {
         //          div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
         //       });
         //       $('#section_id').append(div_data);
         //    },
         //    complete: function() {
         //       $('#section_id').removeClass('dropdownloading');
         //    }
         // });

         getSectionByClass(class_id);
         getTermByGradeLevel(class_id);
      });

      $(document).on('change', '#section_id', function(e) {
         $('#student_id').html("");
         var div_data2 = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

         var class_id = $('#class_id').val();
         var section_id = $('#section_id').val();
         var school_year_id = $('#session_id').val();

         // $.ajax({
         //    type: "GET",
         //    url: base_url + "student/getStudentListPerClassSection",
         //    data: {
         //       'class_id': class_id,
         //       'section_id': $('#section_id').val(),
         //       'school_year_id': $('#session_id').val()
         //    },
         //    dataType: "json",
         //    beforeSend: function() {
         //       $('#student_id').addClass('dropdownloading');
         //    },
         //    success: function(data) {
         //       $.each(data, function(i, obj) {
         //          div_data2 += "<option value=" + obj.student_id + ">" + obj.lastname + ", " + obj.firstname + "</option>";
         //       });
         //       $('#student_id').append(div_data2);
         //    },
         //    complete: function() {
         //       $('#student_id').removeClass('dropdownloading');
         //    }
         // });

         getStudentsByClassSection(class_id, section_id, school_year_id, student_id);
      });

      $("#frm_conduct_grades").on('submit', (function(e) {
         e.preventDefault();
         var $this = $('.submitviews');
         $this.button('loading');

         var frmdata = new FormData(this);

         $.ajax({
            url: "<?php echo site_url("lms/conduct/save_conduct_grades_numeric") ?>",
            type: "POST",
            data: frmdata,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
               $this.button('loading');
            },
            success: function(res) {
               if (res.status == "fail") {
                  var message = "";
                  $.each(res.error, function(index, value) {
                     message += value;
                  });
                  errorMsg(message);

               } else {
                  successMsg(res.message);
                  // window.location.reload(true);
               }
            },
            error: function(jqXHR, textStatus, errorThrown) {},
            complete: function(data) {
               $this.button('reset');
            }
         });
      }));
   });
</script>

<script type="text/javascript">
   var legend_array = [];
   var url = "<?php echo base_url('lms/grading_ssapamp/') ?>";
   $(document).ready(function() {

      var legendrecord = "<?php echo $legend_record ?>";
      var legendarray = legendrecord.split('*');

      var qid = "<?php echo $quarter_id ?>";

      legendarray.forEach(function(item, index) {
         console.log(item, index);
         itemarray = item.split('|');
         lettergrade = itemarray[0];
         range = itemarray[2];
         const legend_object = {
            lgrade: lettergrade,
            graderange: range
         };
         legend_array.push(legend_object)
      });

      calculateAverage();

   });

   function getLetterGrade(finalgrade) {
      lettergrade = "";
      //   rfinalgrade = Math.round(finalgrade);
      rfinalgrade = parseFloat(finalgrade);
      legend_array.forEach(function(object, index) {
         // console.log(object.lgrade);
         grade = object.graderange;
         if (grade.indexOf('-') > -1) {
            var gradearray = grade.split('-');
            temp = gradearray[0];
            temp = temp.trim();

            range1 = parseFloat(temp); //Math.round(temp);
            temp = gradearray[1];
            temp = temp.trim();
            range2 = parseFloat(temp); // Math.round(temp);
            range2 = range2 + 0.99;
            if (rfinalgrade >= range1 && rfinalgrade <= range2) {
               console.log('in range');
               lettergrade = object.lgrade;
               // break;
               return lettergrade;
            }
         } else {
            var gradearray = grade.split('and');
            temp = temp.trim();
            range2 = Math.round(temp);
            if (rfinalgrade <= range1) {
               console.log('in range');
               lettergrade = object.lgrade;
               return lettergrade;
            }
         }
      });
      // console.log(lettergrade);
      return lettergrade;
   }

   function calculateAverage() {
      var sum = 0,
         i = 0,
         average = 0;
      var defaultvalue = "";
      var lg = "";

      $('#Tablesample tr').each(function() {
         i = i + 1;
         var keval = $(this).find(".numgrades input").val();
         if (jQuery.type(keval) === "undefined") {
            //Some code goes here
         } else {
            sum += parseFloat(keval);
            // console.log(i);
            console.log(keval);
         }

      });
      $("#totalgrade").text(defaultvalue);
      $("#lettergrade").text(defaultvalue);
      if (isNaN(sum) || sum == 0) {

      } else {
         average = sum / 6;
         average = average.toFixed(2);
         $("#totalgrade").text(average);
         lg = getLetterGrade(average);
         $("#lettergrade").text(lg);
      }

   }

   function calculateSum2($i) {
      var sum = 0,
         gradeval = 0,
         error = 0;
      i = $i;
      //iterate through each textboxes and add the values
      $(".grade" + i).each(function() {
         //add only if the value is number
         if (!isNaN(this.value) && this.value.length != 0) {
            sum += parseFloat(this.value);;

            gradeval = parseInt(this.value);
            if (gradeval > 10) {
               error = 1;
            }
         } else if (this.value.length != 0) {
            $(this).css("background-color", "red");
         }
      });

      var ccidval = 0;
      ccidval = $("input#ccid" + i).val();
      console.log(ccidval);
      var ssidval = 0;
      ssidval = $("input#ssid" + i).val();
      console.log(ssidval);

      var studid = "";
      studid = $("input#student_id").val();
      console.log(studid);
      var lgval = "";
      lgval = $("input#fin" + i).val();
      lgval = getLetterGrade(sum);
      $("input#fin" + i).val(lgval);

      var update_data = {
         studentid: studid,
         clid: ccidval,
         ssid: ssidval,
         grade: gradeval,
         lg: lgval
      }
      $.ajax({
         url: url + "update",
         type: "POST",
         data: update_data,
         complete: function(response) {
            console.log(response.responseText);
         }
      });
      calculateAverage();
      // } else {
      //   alert('grade should not be greater than 10');
      // }

      //  }
      // $("input#fin"+i).val(sum);
   }
</script>
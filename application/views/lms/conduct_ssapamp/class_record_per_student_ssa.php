<style>
   /* tfoot {
      display: table;
   } */
</style>

<div class="content-wrapper" style="min-height: 946px;">
   <section class="content-header">
      <h1><i class="fa fa-line-chart"></i> <?php echo $this->lang->line('reports'); ?> <small> <?php echo $this->lang->line('filter_by_name1'); ?></small></h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <?php
      $data['school_code'] = $school_code;
      // $this->load->view('reports/_studentinformation', $data);
      ?>
      <div class="row">
         <div class="col-md-12">
            <div class="box removeboxmius">
               <div class="box-header ptbnull"></div>

               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
               </div>

               <div class="box-body">
                  <form role="form" action="<?php echo site_url('lms/grading_ssapamp/class_record_per_student') ?>" method="post" class="">
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
                                    //$count++;
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
                                    //$count++;
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
                              <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                           </div>
                        </div>
                     </div>
                     <!--./row-->
                  </form>
               </div>
               <!--./box-body-->

               <div class="">
                  <div class="box-header ptbnull"></div>
                  <div class="box-header ptbnull">
                     <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo form_error('class_record_quarterly'); ?> Student Grades<?php //echo $this->lang->line('class_record_summary') ; 
                                                                                                                                                   ?></h3>
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
                                       <?php $the_session_id = $this->input->post('session_id') ?>
                                       <?php $the_class_id = $this->input->post('class_id') ?>
                                       <?php $the_section_id = $this->input->post('section_id') ?>
                                       <?php $the_student_id = $this->input->post('student_id') ?>
                                       <h3 class="box-title titlefix"> <?php echo $this->lang->line('grades'); ?></h3>
                                       <div class="box-tools pull-right"><a href="<?php echo base_url('report/student_report_card') . '?session_id=' . $the_session_id . '&class_id=' . $the_class_id . '&section_id=' . $the_section_id . '&student_id=' . $the_student_id ?>" target="_blank"><button class="btn btn-success">Print Card</button></a></div>
                                    </div>
                                    <div class="box-body">
                                       <div class="table-responsive">
                                          <div class="download_label"><?php echo 'Quarterly Grades'; ?></div>
                                          <table id="class_record" class="table table-striped table-bordered table-hover classrecord nowrap">
                                             <thead>
                                                <tr>
                                                   <th class="text-left">Semester</th>

                                                   <th class="text-center">Number Grade</th>
                                                   <th class="text-center">LG</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                <?php
                                                // $q1Tot = 0;
                                                // $q2Tot = 0;
                                                // $q3Tot = 0;
                                                // $q4Tot = 0;
                                                // $aveTot = 0;
                                                // $finTot = 0;
                                                // $rowCtr = 0;

                                                // foreach ($resultlist as $row) {
                                                //    $average = ($row->Q1 == 0 || $row->Q2 == 0 || $row->Q3 == 0 || $row->Q4 == 0) ? '' : $row->average;
                                                //    $final = ($row->Q1 == 0 || $row->Q2 == 0 || $row->Q3 == 0 || $row->Q4 == 0) ? '' : $row->final_grade;
                                                //    echo "<tr>\r\n";
                                                //    echo "<td class='text-left'>" . $row->Subjects . "</td>\r\n";
                                                //    echo "<td class='text-center" . ($row->Q1 < 75 ? " text-danger" : ($row->Q1 >= 90 ? " text-success" : "")) . "'><b>" . ($row->Q1 == 0 ? '' : $row->Q1) . "</b></td>\r\n";
                                                //    echo "<td class='text-center" . ($row->Q2 < 75 ? " text-danger" : ($row->Q2 >= 90 ? " text-success" : "")) . "'><b>" . ($row->Q2 == 0 ? '' : $row->Q2) . "</b></td>\r\n";
                                                //    echo "<td class='text-center" . ($row->Q3 < 75 ? " text-danger" : ($row->Q3 >= 90 ? " text-success" : "")) . "'><b>" . ($row->Q3 == 0 ? '' : $row->Q3) . "</b></td>\r\n";
                                                //    echo "<td class='text-center" . ($row->Q4 < 75 ? " text-danger" : ($row->Q4 >= 90 ? " text-success" : "")) . "'><b>" . ($row->Q4 == 0 ? '' : $row->Q4) . "</b></td>\r\n";
                                                //    echo "<td class='text-center" . ($average < 75 ? " text-danger" : ($average >= 90 ? " text-success" : "")) . "'><b>" . ($average == 0 ? '' : $average) . "</b></td>\r\n";
                                                //    echo "<td class='text-center" . ($final < 75 ? " text-danger" : ($final >= 90 ? " text-success" : "")) . "'><b>" . ($final == 0 ? '' : $final) . "</b></td>\r\n";
                                                //    echo "</tr>\r\n";

                                                //    $q1Tot += ($row->Q1 !== null ? $row->Q1 : 0);
                                                //    $q2Tot += ($row->Q2 !== null ? $row->Q2 : 0);
                                                //    $q3Tot += ($row->Q3 !== null ? $row->Q3 : 0);
                                                //    $q4Tot += ($row->Q4 !== null ? $row->Q4 : 0);
                                                //    $aveTot += ($row->Q1 == 0 || $row->Q2 == 0 || $row->Q3 == 0 || $row->Q4 == 0) ? 0 : $row->average;
                                                //    $finTot += ($row->Q1 == 0 || $row->Q2 == 0 || $row->Q3 == 0 || $row->Q4 == 0) ? 0 : $row->final_grade;

                                                //    $rowCtr++;
                                                // }

                                                // $q1Ave = $q1Tot / $rowCtr;
                                                // $q2Ave = $q2Tot / $rowCtr;
                                                // $q3Ave = $q3Tot / $rowCtr;
                                                // $q4Ave = $q4Tot / $rowCtr;
                                                // $aveAve = $aveTot / $rowCtr;
                                                // $finAve = $finTot / $rowCtr;
                                                ?>
                                             </tbody>
                                             <tfoot>
                                                <tr>
                                                   <!-- <th class="text-right">General Average</th>
                                                   <th class="text-center <?php //echo ($q1Ave < 75 ? "text-danger" : ($q1Ave >= 90 ? "text-success" : "")); 
                                                                           ?>"><?php echo ($q1Ave == 0 ? "" : number_format($q1Ave, 2)); ?></th>
                                                   <th class="text-center <?php //echo ($q2Ave < 75 ? "text-danger" : ($q2Ave >= 90 ? "text-success" : ""));; 
                                                                           ?>"><?php echo ($q2Ave == 0 ? "" : number_format($q2Ave, 2)); ?></th>
                                                   <th class="text-center <?php //echo ($q3Ave < 75 ? "text-danger" : ($q3Ave >= 90 ? "text-success" : ""));; 
                                                                           ?>"><?php echo ($q3Ave == 0 ? "" : number_format($q3Ave, 2)); ?></th>
                                                   <th class="text-center <?php //echo ($q4Ave < 75 ? "text-danger" : ($q4Ave >= 90 ? "text-success" : ""));; 
                                                                           ?>"><?php echo ($q4Ave == 0 ? "" : number_format($q4Ave, 2)); ?></th>
                                                   <th class="text-center <?php //echo ($aveAve < 75 ? "text-danger" : ($aveAve >= 90 ? "text-success" : "")); 
                                                                           ?>"><?php echo ($aveAve == 0 ? "" : number_format($aveAve, 2)); ?></th>
                                                   <th class="text-center <?php //echo ($finAve < 75 ? "text-danger" : ($finAve >= 90 ? "text-success" : ""));; 
                                                                           ?>"><?php echo ($finAve == 0 ? "" : number_format($finAve, 2)); ?></th> -->
                                                </tr>
                                             </tfoot>
                                          </table>
                                          <?php //} 
                                          ?>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>

                        </section>
                     <?php } ?>
                  </div>
               </div>
               <!--./box box-primary -->
            </div><!-- ./col-md-12 -->
         </div>
      </div>
   </section>
</div>

<script type="text/javascript">
   var class_id;
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

   function getStudentsByClassSection(class_id, section_id, school_year_id, student_id = -1) {
      if (class_id != "") {
         $('#student_id').html("");

         //if (class_id == 1) {
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
         //}
      }
   }

   $(document).ready(function() {

      var class_id = $('#class_id').val();
      var section_id = '<?php echo set_value('section_id') ?>';
      var school_year_id = '<?php echo set_value('session_id') ?>';
      var student_id = '<?php echo set_value('student_id') ?>';
      var term_id = '<?php echo set_value('quarter_id') ?>';

      getSectionByClass(class_id, section_id);
      getStudentsByClassSection(class_id, section_id, school_year_id, student_id);
      getTermByGradeLevel(class_id, term_id);

      var table = $('.classrecord').DataTable({
         paging: false,
         ordering: false,
         searching: false,
         dom: "Bfrtip",
         fixedHeader: {
            header: true,
            footer: true
         },
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
         "footerCallback": function(row, data, start, end, display) {
            var api = this.api(),
               data;

            //  // Remove the formatting to get integer data for summation
            //  var intVal = function (i) {
            //      return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i.toFixed(2) : (0).toFixed(2);
            //  };

            //  // computing column Total the complete result 
            //  var total_expected = 0;
            //  var total_actual = 0;
            //  var total_arrears = 0;

            //  total_expected = api
            //  .column(5)
            //  .data()
            //  .reduce( function (a, b) {
            //      return (intVal(a) + intVal(b)).toFixed(0);
            //  }, (0).toFixed(0));

            //  total_actual = api
            //  .column(6)
            //  .data()
            //  .reduce( function (a, b) {
            //      return (intVal(a) + intVal(b)).toFixed(0);
            //  }, (0).toFixed(0));

            //  total_arrears = api
            //  .column(7)
            //  .data()
            //  .reduce( function (a, b) {
            //      return (intVal(a) + intVal(b)).toFixed(0);
            //  }, (0).toFixed(0));

            //  $(api.column(4).footer()).html('<table style="width:100%"><tr><td class="text-right">Total</td></tr></table>');
            //  $(api.column(5).footer()).html('<table style="width:100%"><tr><td class="text-right">'+formatNumber(total_expected)+'</td></tr></table>');
            //  $(api.column(6).footer()).html('<table style="width:100%"><tr><td class="text-right">'+formatNumber(total_actual)+'</td></tr></table>');
            //  $(api.column(7).footer()).html('<table style="width:100%"><tr><td class="text-right">'+formatNumber(total_arrears)+'</td></tr></table>');
         }
      });

      $(document).on('change', '#class_id', function(e) {
         $('#section_id').html("");
         var class_id = $(this).val();

         // class_id = $(this).val();
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

         // var div_data2 = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
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

         var class_id = $('#class_id').val();
         var section_id = $('#section_id').val();
         var school_year_id = $('#session_id').val();
         var student_id = $('#student_id').val();

         getStudentsByClassSection(class_id, section_id, school_year_id, student_id);
      });
   });
</script>
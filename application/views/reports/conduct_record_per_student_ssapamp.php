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
      $this->load->view('reports/_studentinformation', $data);
      ?>
      <div class="row">
         <div class="col-md-12">
            <div class="box removeboxmius">
               <div class="box-header ptbnull"></div>

               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
               </div>

               <div class="box-body">
                  <form role="form" action="<?php echo site_url('report/conduct_record_per_student') ?>" method="post" class="">
                     <div class="row">
                        <?php echo $this->customlib->getCSRF(); ?>
                        <div class="col-sm-6 col-md-3">
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

                        <div class="col-sm-6 col-md-3">
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

                        <div class="col-sm-6 col-md-3">
                           <div class="form-group">
                              <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                              <select id="section_id" name="section_id" class="form-control">
                                 <option value=""><?php echo $this->lang->line('select'); ?></option>
                              </select>
                              <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                           </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                           <div class="form-group">
                              <label><?php echo $this->lang->line('student'); ?></label><small class="req"> *</small>
                              <select autofocus="" id="student_id" name="student_id" class="form-control">
                                 <option value=""><?php echo $this->lang->line('select'); ?></option>
                              </select>
                              <span class="text-danger"><?php echo form_error('student_id'); ?></span>
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
                     <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo form_error('class_record_quarterly'); ?> Student Conduct<?php //echo $this->lang->line('class_record_summary') ; 
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
                              <div class="col-md-3">
                                 <div class="box box-primary">
                                    <div class="box-body box-profile">
                                       <img class="profile-user-img img-responsive img-circle" src="<?php echo $_SESSION['S3_BaseUrl'] . $student['image'] ?>" alt="User profile picture">
                                       <h3 class="profile-username text-center"><?php echo $student['firstname'] . " " . $student['lastname']; ?></h3>
                                       <ul class="list-group list-group-unbordered">
                                          <li class="list-group-item">
                                             <b><?php echo $this->lang->line('admission_no'); ?></b> <a class="pull-right"><?php echo $student['admission_no']; ?></a>
                                          </li>
                                          <li class="list-group-item">
                                             <b><?php echo $this->lang->line('roll_no'); ?></b> <a class="pull-right"><?php echo $student['roll_no']; ?></a>
                                          </li>
                                          <li class="list-group-item">
                                             <b><?php echo $this->lang->line('class'); ?></b> <a class="pull-right"><?php echo $student['class']; ?></a>
                                          </li>
                                          <li class="list-group-item">
                                             <b><?php echo $this->lang->line('section'); ?></b> <a class="pull-right"><?php echo $student['section']; ?></a>
                                          </li>
                                          <li class="list-group-item">
                                             <b><?php echo $this->lang->line('lrn'); ?></b> <a class="pull-right"><?php echo $student['lrn_no']; ?></a>
                                          </li>
                                       </ul>
                                    </div>
                                 </div>
                              </div>
                              <!-- check if what grade starts -->
                              <?php 
                                 if ( $class_id==$prekinderid && strtolower($school_code) == 'ssapamp') {
                              ?>
                              <div class="col-md-9">
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
                                          <div class="download_label"><?php echo 'Semestral Grades'; ?></div>
                                          <?php
                                                // echo "session_id: " . $session_id . "<br>";
                                                // echo "class_id: " . $class_id . "<br>";
                                                // echo "section_id: " . $section_id . "<br>";
                                                // echo "student_id: " . $student_id . "<br>";
                                                // echo "grade: " . $lettergradearray . "<br>";
                                                // var_dump($lettergradearray);
                                                // echo "<br>";
                                                // var_dump($pergradearray);
                                                // echo "<br>";
                                                // var_dump($semave);
                                                // echo "<br>";
                                                // foreach($legend_list as $rows) {
                                                //    $letter=$rows->conduct_grade;
                                                //    $mingrade=$rows->mingrade;
                                                //    $maxgrade=$rows->maxgrade;
                                                //    echo "letter: " . $letter . "<br>";
                                                //    echo "mingrade: " . $mingrade . "<br>";
                                                //    echo "maxgrade: " . $maxgrade . "<br>";
                                                
                                                // }
                                                $term=0;
                                                // foreach($semesters as $srow) {
                                                //    echo $terms . "<br>";
                                                //    var_dump($srow);
                                                //    echo "<br>";
                                                //    $term++;                                             
                                                // }
                                                // echo "<br>";                                          
                                          ?>
                                          <table id="class_record" class="table table-striped table-bordered table-hover classrecord nowrap">
                                             <?php
                                             foreach($semesters as $srow) {
                                                $term++;
                                             ?>
                                             <thead>
                                                <tr>
                                                   <th colspan="3" class="text-center">SEMESTER <?php echo $term; ?></th>	       
                                                </tr>
                                                <tr>
                                                   <th class="text-center"></th>									
                                                   <th class="text-center">NUMBER GRADE</th>
                                                   <th class="text-center">LG</th>                                                   
                                                </tr>
                                             </thead>
                                             <tbody>
                                                <?php
                                                
                                                   $q1Tot=0;
                                                   $rowCtr=0;
                                                   foreach($srow as $recrow) {
                                                      echo "<tr>\r\n";
                                                      echo "<td class='text-left'>" . $recrow->description . "</td>\r\n";   
                                                      echo "<td class='text-center'><b>" . ($recrow->grade == 0 ? '' : round($recrow->grade)) . "</b></td>\r\n";
                                                      echo "<td class='text-center'><b>" . ($recrow->grade == 0 ? '' : $recrow->LG) . "</b></td>\r\n";    
                                                      echo "</tr>\r\n";
                                                      $q1Tot += ($recrow->grade !== null ? $recrow->grade : 0);
                                                      $rowCtr++;
                                                   }                                            
                                                   $q1Ave = $q1Tot / $rowCtr;

                                                ?>
                                                <tr style="outline: thin solid">
                                                   <th class="text-right">Average</th>
                                                   <th class="text-center"><?php echo ($q1Ave == 0 ? "" : number_format($q1Ave, 2)); ?></th>
                                                   <th class="text-center">
                                                   <?php
                                                      foreach($semave as $semrow) {
                                                         foreach($semrow as $vrow) {
                                                            if ($vrow->semester==$term) 
                                                               {
                                                                  echo $vrow->LG;
                                                               }
                                                         }
                                                      }
                                                   ?></th>
                                                </tr>
                                             </tbody>
                                             <?php
                                                echo "<tr>";
                                                echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
                                                echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
                                                echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
                                                echo "</tr>";
                                             }
                                             ?>
                                             <tfoot>
                                                <tr>
                                                   <th class="text-right">General Average</th>
                                                   <th class="text-center"><?php echo number_format($totalgrade,2); ?></th>  
                                                   <th class="text-center"><?php echo $finallettergrade; ?></th>                                                   
                                                </tr>
                                             </tfoot>
                                             
                                          </table>
                                          <?php //} 
                                          ?>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <?php
                              } else {
                              
                              }
                              ?>
                           </div>
                           <div class="row">
                              <div class="table-responsive">
                                 <!-- <?php if (isset($student_conduct)) { ?> -->
                                    <!-- <section class="content-header">
                                       <h1><i class="fa fa-calendar-times-o"></i> <?php echo $this->lang->line('grades'); ?> </h1>
                                    </section> -->
                                    <!-- Main content -->
                                    <!-- <section class="content"> -->
                                       <!-- <div class="row">
                                          <div class="col-md-3">
                                             <div class="box box-primary">
                                                <div class="box-body box-profile">
                                                   <h3 class="profile-username text-center">Legend</h3>
                                                   <ul class="list-group list-group-unbordered">
                                                      <?php foreach ($legend_list as $legendrow) { ?>
                                                         <li class="list-group-item">
                                                            <b><?php echo $legendrow->conduct_grade; ?></b> <span class="pull-right"><?php echo $legendrow->description; ?></span>
                                                         </li>
                                                      <?php } ?>
                                                   </ul>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="col-md-9">
                                             <div class="box box-warning">
                                                <div class="box-header ptbnull">
                                                   <h3 class="box-title titlefix"> Conduct Grades</h3>
                                                   <div class="box-tools pull-right"></div>
                                                </div>
                                                <div class="box-body">
                                                   <div class="table-responsive">
                                                      <?php if (!empty($student_conduct)) {
                                                         if ($conduct_grading_type == "letter") { ?>
                                                            <table id="class_record" class="table table-striped table-bordered table-hover conductTable nowrap">
                                                               <thead>
                                                                  <tr>
                                                                     <th class="text-left">Indicator ID</th>
                                                                     <th class="text-left">DepEd Indicator</th>
                                                                     <th class="text-left">Indicator</th>
                                                                     <th class="text-left">1st Qtr</th>
                                                                     <th class="text-left">2nd Qtr</th>
                                                                     <th class="text-left">3rd Qtr</th>
                                                                     <th class="text-left">4rth Qtr</th>
                                                                  </tr>
                                                               </thead>
                                                               <tbody>
                                                                  
                                                               </tbody>
                                                               <tfoot>
                                                               </tfoot>
                                                            </table>
                                                         <?php } else if ($conduct_grading_type == "number") { ?>
                                                            <table id="class_record" class="table table-striped table-bordered table-hover conductTable nowrap">
                                                               <thead>
                                                                  <tr>
                                                                     <th class="text-center">1st Qtr</th>
                                                                     <th class="text-center">2nd Qtr</th>
                                                                     <th class="text-center">3rd Qtr</th>
                                                                     <th class="text-center">4rth Qtr</th>
                                                                  </tr>
                                                               </thead>
                                                               <tbody>
                                                                  
                                                               </tbody>
                                                               <tfoot>
                                                               </tfoot>
                                                            </table>
                                                      <?php }
                                                      } ?>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div> -->
                                       <!-- <div class="form-group">
                                                                <div class="col-sm-12">
                                                                    <button type="submit" name="save_conduct" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?></button>
                                                                </div>
                                                            </div>    -->
                                    <!-- </section> -->
                                 <!-- <?php } ?> -->
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

   function getSectionByClass(class_id, section_id) {
      if (class_id != "" && section_id != "") {
         $('#section_id').html("");
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

   function getStudentsByClassSection(class_id, section_id, school_year_id, student_id) {
      if (class_id != "") {
         $('#student_id').html("");
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

   $(document).ready(function() {
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

      var class_id = $('#class_id').val();
      var section_id = '<?php echo set_value('section_id') ?>';
      var school_year_id = '<?php echo set_value('session_id') ?>';
      var student_id = '<?php echo set_value('student_id') ?>';
      getSectionByClass(class_id, section_id);
      getStudentsByClassSection(class_id, section_id, school_year_id, student_id);

      $(document).on('change', '#class_id', function(e) {
         $('#section_id').html("");
         var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
         class_id = $(this).val();
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
                  div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
               });
               $('#section_id').append(div_data);
            },
            complete: function() {
               $('#section_id').removeClass('dropdownloading');
            }
         });
      });

      $(document).on('change', '#section_id', function(e) {
         $('#student_id').html("");
         var div_data2 = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
         $.ajax({
            type: "GET",
            url: base_url + "student/getStudentListPerClassSection",
            data: {
               'class_id': class_id,
               'section_id': $('#section_id').val(),
               'school_year_id': $('#session_id').val()
            },
            dataType: "json",
            beforeSend: function() {
               $('#student_id').addClass('dropdownloading');
            },
            success: function(data) {
               $.each(data, function(i, obj) {
                  div_data2 += "<option value=" + obj.student_id + ">" + obj.lastname + ", " + obj.firstname + "</option>";
               });
               $('#student_id').append(div_data2);
            },
            complete: function() {
               $('#student_id').removeClass('dropdownloading');
            }
         });
      });
   });
</script>
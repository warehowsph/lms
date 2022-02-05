<div class="content-wrapper" style="min-height: 946px;">
   <section class="content-header">
      <h1><i class="fa fa-line-chart"></i> <?php echo $this->lang->line('reports'); ?> <small> <?php echo $this->lang->line('filter_by_name1'); ?></small></h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <?php $this->load->view('reports/_studentinformation'); ?>
      <div class="row">
         <div class="col-md-12">
            <div class="box removeboxmius">
               <div class="box-header ptbnull"></div>

               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
               </div>

               <div class="box-body">
                  <form role="form" action="<?php echo site_url('report/class_record_per_student') ?>" method="post" class="">
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
                              <div class="col-md-3">
                                 <div class="box box-primary">
                                    <div class="box-body box-profile">
                                       <img class="profile-user-img img-responsive img-circle" src="<?php echo $_SESSION['S3_BaseUrl'] . $student['image'] ?>" alt="User profile picture">
                                       <h3 class="profile-username text-center"><?php echo ucfirst($student['lastname']) . ", " . ucfirst($student['firstname']) . " " . ucfirst($student['middlename']) . "."; ?></h3>
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
                                          <?php //if (!empty($resultlist)) { 
                                          ?>
                                          <table id="class_record" class="table table-striped table-bordered table-hover example nowrap">
                                             <thead>
                                                <tr>
                                                   <th class="text-left">Subjects</th>
                                                   <?php
                                                   foreach ($quarter_list as $row) {
                                                      echo "<th class=\"text-center\">" . $row->description . "</th>\r\n";
                                                   }
                                                   ?>
                                                   <th class="text-center">Average</th>
                                                   <th class="text-center">Final Grade</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                <?php
                                                foreach ($resultlist as $row) {
                                                   $average = ($row->Q1 == 0 || $row->Q2 == 0 || $row->Q3 == 0 || $row->Q4 == 0) ? '' : $row->average;
                                                   $final = ($row->Q1 == 0 || $row->Q2 == 0 || $row->Q3 == 0 || $row->Q4 == 0) ? '' : $row->final_grade;
                                                   echo "<tr>\r\n";
                                                   echo "<td class='text-left'>" . $row->Subjects . "</td>\r\n";
                                                   echo "<td class='text-center" . ($row->Q1 < 75 ? " text-danger" : ($row->Q1 >= 90 ? " text-success" : "")) . "'><b>" . ($row->Q1 == 0 ? '' : $row->Q1) . "</b></td>\r\n";
                                                   echo "<td class='text-center" . ($row->Q2 < 75 ? " text-danger" : ($row->Q2 >= 90 ? " text-success" : "")) . "'><b>" . ($row->Q2 == 0 ? '' : $row->Q2) . "</b></td>\r\n";
                                                   echo "<td class='text-center" . ($row->Q3 < 75 ? " text-danger" : ($row->Q3 >= 90 ? " text-success" : "")) . "'><b>" . ($row->Q3 == 0 ? '' : $row->Q3) . "</b></td>\r\n";
                                                   echo "<td class='text-center" . ($row->Q4 < 75 ? " text-danger" : ($row->Q4 >= 90 ? " text-success" : "")) . "'><b>" . ($row->Q4 == 0 ? '' : $row->Q4) . "</b></td>\r\n";
                                                   echo "<td class='text-center" . ($average < 75 ? " text-danger" : ($average >= 90 ? " text-success" : "")) . "'><b>" . ($average == 0 ? '' : $average) . "</b></td>\r\n";
                                                   echo "<td class='text-center" . ($final < 75 ? " text-danger" : ($final >= 90 ? " text-success" : "")) . "'><b>" . ($final == 0 ? '' : $final) . "</b></td>\r\n";
                                                   echo "</tr>\r\n";
                                                }
                                                ?>
                                             </tbody>
                                             <tfoot>
                                                <!-- <tr>
                                                                            <th>Average</th>
                                                                            <th></th>
                                                                            <th></th>
                                                                            <th></th>
                                                                            <th></th>
                                                                        </tr> -->
                                             </tfoot>
                                          </table>
                                          <?php //} 
                                          ?>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="table-responsive">
                                 <?php if (isset($student_conduct)) { ?>
                                    <section class="content-header">
                                       <h1><i class="fa fa-calendar-times-o"></i> <?php echo $this->lang->line('grades'); ?> </h1>
                                    </section>
                                    <!-- Main content -->
                                    <section class="content">
                                       <div class="row">
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
                                                                  <?php

                                                                  foreach ($student_conduct as $row) {
                                                                     echo "<tr>\r\n";
                                                                     echo "<td class='text-center'>$row->id</td>\r\n";
                                                                     echo "<td class='text-left'>$row->deped_indicators</td>\r\n";
                                                                     echo "<td class='text-left'>$row->indicators</td>\r\n";
                                                                     echo "<td class='text-center'>$row->first_quarter</td>\r\n";
                                                                     echo "<td class='text-center'>$row->second_quarter</td>\r\n";
                                                                     echo "<td class='text-center'>$row->third_quarter</td>\r\n";
                                                                     echo "<td class='text-center'>$row->fourth_quarter</td>\r\n";
                                                                     echo "</tr>\r\n";
                                                                  }
                                                                  ?>
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
                                                                  <?php
                                                                  foreach ($student_conduct as $row) {
                                                                     echo "<tr>\r\n";
                                                                     echo "<td class='text-center'>$row->first_quarter</td>\r\n";
                                                                     echo "<td class='text-center'>$row->second_quarter</td>\r\n";
                                                                     echo "<td class='text-center'>$row->third_quarter</td>\r\n";
                                                                     echo "<td class='text-center'>$row->fourth_quarter</td>\r\n";
                                                                     echo "</tr>\r\n";
                                                                  }
                                                                  ?>
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
                                       </div>
                                       <!-- <div class="form-group">
                                                                <div class="col-sm-12">
                                                                    <button type="submit" name="save_conduct" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?></button>
                                                                </div>
                                                            </div>    -->
                                    </section>
                                 <?php } ?>
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
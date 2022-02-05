<style>
   /* tfoot {
      display: table;
   } */
</style>

<?php

function gradeCode($codes, $grade, $show)
{
   $retVal = '';

   if ($show) {
      $retVal = '';

      foreach ($codes as $rows) {
         if ($grade >= $rows->min_grade && $grade <= $rows->max_grade) {
            $retVal = $rows->grade_code;
            break;
         }
      }
   } else {
      $retVal = $grade;
   }

   return $retVal;
}

function isTermAllowed($terms_allowed, $term)
{
   $retVal = false;

   foreach ($terms_allowed as $rows) {
      if ($rows->quarter_id == $term) {
         $retVal = true;
         break;
      }
   }

   return $retVal;
}
?>

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
                           <!-- Attendance -->
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="row">

                                    <?php if (strtolower($school_code) != 'ssapamp') : ?>
                                       <div class="col-md-12">
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
                                    <?php endif; ?>

                                    <?php if (!empty($codes_table)) : ?>
                                       <div class="col-md-12">
                                          <div class="box box-primary">
                                             <div class="box-body box-profile">
                                                <h3 class="profile-username text-center">Legend</h3>
                                                <ul class="list-group list-group-unbordered">
                                                   <?php foreach ($codes_table as $code) { ?>
                                                      <li class="list-group-item">
                                                         <b><?php echo $code->grade_code; ?></b> <span class="pull-right"><?php echo ($code->min_grade . "-" . intval($code->max_grade)); ?></span>
                                                      </li>
                                                   <?php } ?>
                                                </ul>
                                             </div>
                                          </div>
                                       </div>
                                    <?php endif; ?>
                                 </div>
                              </div>
                              <div class="col-md-9">
                                 <div class="row">
                                    <div class="col-md-12">
                                       <div class="box box-warning">
                                          <div class="box-header ptbnull">
                                             <?php //print_r(gradeCode($codes_table, 95, true)); 
                                             // $month_number = 3;
                                             // $month_name = date("F", mktime(0, 0, 0, $month_number, 10));
                                             // echo $month_name;
                                             ?>
                                             <?php $the_session_id = $this->input->post('session_id') ?>
                                             <?php $the_class_id = $this->input->post('class_id') ?>
                                             <?php $the_section_id = $this->input->post('section_id') ?>
                                             <?php $the_student_id = $this->input->post('student_id') ?>
                                             <h3 class="box-title titlefix"> <?php echo $this->lang->line('grades'); ?></h3>
                                             <?php if (strtolower($school_code) == 'scholaangelicus') : ?>
                                                <div class="box-tools pull-right">
                                                   <a href="<?php echo base_url('report/student_report_card') . '?session_id=' . $the_session_id . '&class_id=' . $the_class_id . '&section_id=' . $the_section_id . '&student_id=' . $the_student_id ?>" target="_blank">
                                                      <button class="btn btn-primary btn-sm"><i class="fa fa-print"></i> Print Report Card</button>
                                                   </a>
                                                </div>
                                             <?php endif; ?>
                                          </div>
                                          <div class="box-body">

                                             <?php if (strtolower($school_code) == 'ssapamp') : ?>
                                                <div class="box-body box-profile">
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
                                             <?php endif; ?>
                                             <div class="table-responsive">
                                                <div class="download_label"><?php echo 'Quarterly Grades'; ?></div>
                                                <table id="class_record" class="table table-striped table-bordered table-hover classrecord nowrap">
                                                   <thead>
                                                      <tr>
                                                         <th class="text-left">Subjects</th>
                                                         <?php
                                                         foreach ($quarter_list as $row) {
                                                            echo "<th class=\"text-center\">" . $row->description . "</th>\r\n";
                                                         }
                                                         ?>
                                                         <?php if ($show_average_column) { ?>
                                                            <th class="text-center">Average</th>
                                                         <?php } ?>
                                                         <th class="text-center">Final Grade</th>
                                                      </tr>
                                                   </thead>
                                                   <tbody>
                                                      <?php
                                                      $q1Tot = 0;
                                                      $q2Tot = 0;
                                                      $q3Tot = 0;
                                                      $q4Tot = 0;
                                                      $aveTot = 0;
                                                      $finTot = 0;
                                                      $rowCtr = 0;
                                                      $q1Ave = 0;
                                                      $q2Ave = 0;
                                                      $q3Ave = 0;
                                                      $q4Ave = 0;

                                                      foreach ($resultlist as $row) {
                                                         $average = ($row->Q1 == 0 || $row->Q2 == 0 || $row->Q3 == 0 || $row->Q4 == 0) ? '' : $row->average;
                                                         $final = ($row->Q1 == 0 || $row->Q2 == 0 || $row->Q3 == 0 || $row->Q4 == 0) ? '' : $row->final_grade;
                                                         echo "<tr>\r\n";
                                                         echo "<td class='text-left'>" . $row->Subjects . "</td>\r\n";
                                                         echo "<td class='text-center" . ($row->Q1 < 75 ? " text-danger" : ($row->Q1 >= 90 ? " text-success" : "")) . "'><b>" . ($row->Q1 == 0 ? '' : gradeCode($codes_table, $row->Q1, $show_letter_grade)) . "</b></td>\r\n";
                                                         echo "<td class='text-center" . ($row->Q2 < 75 ? " text-danger" : ($row->Q2 >= 90 ? " text-success" : "")) . "'><b>" . ($row->Q2 == 0 ? '' : gradeCode($codes_table, $row->Q2, $show_letter_grade)) . "</b></td>\r\n";
                                                         if (isset($row->Q3))
                                                            echo "<td class='text-center" . ($row->Q3 < 75 ? " text-danger" : ($row->Q3 >= 90 ? " text-success" : "")) . "'><b>" . ($row->Q3 == 0 ? '' : gradeCode($codes_table, $row->Q3, $show_letter_grade)) . "</b></td>\r\n";
                                                         if (isset($row->Q4))
                                                            echo "<td class='text-center" . ($row->Q4 < 75 ? " text-danger" : ($row->Q4 >= 90 ? " text-success" : "")) . "'><b>" . ($row->Q4 == 0 ? '' : gradeCode($codes_table, $row->Q4, $show_letter_grade)) . "</b></td>\r\n";

                                                         if ($show_average_column)
                                                            echo "<td class='text-center" . ($average < 75 ? " text-danger" : ($average >= 90 ? " text-success" : "")) . "'><b>" . ($average == 0 ? '' : $average) . "</b></td>\r\n";
                                                         echo "<td class='text-center" . ($final < 75 ? " text-danger" : ($final >= 90 ? " text-success" : "")) . "'><b>" . ($final == 0 ? '' : $final) . "</b></td>\r\n";
                                                         echo "</tr>\r\n";

                                                         $q1Tot += ($row->Q1 !== null ? $row->Q1 : 0);
                                                         $q2Tot += ($row->Q2 !== null ? $row->Q2 : 0);
                                                         $q3Tot += ($row->Q3 !== null ? $row->Q3 : null);
                                                         $q4Tot += ($row->Q4 !== null ? $row->Q4 : null);
                                                         $aveTot += ($row->Q1 == 0 || $row->Q2 == 0 || $row->Q3 == 0 || $row->Q4 == 0) ? 0 : $row->average;
                                                         $finTot += ($row->Q1 == 0 || $row->Q2 == 0 || $row->Q3 == 0 || $row->Q4 == 0) ? 0 : $row->final_grade;

                                                         $rowCtr++;
                                                      }

                                                      $q1Ave = $q1Tot / $rowCtr;
                                                      $q2Ave = $q2Tot / $rowCtr;
                                                      if ($q3Tot !== null)
                                                         $q3Ave = $q3Tot / $rowCtr;

                                                      if ($q4Tot !== null)
                                                         $q4Ave = $q4Tot / $rowCtr;
                                                      $aveAve = $aveTot / $rowCtr;
                                                      $finAve = $finTot / $rowCtr;

                                                      if (strtolower($school_code) == "ssapamp") {
                                                         echo "<tr>\r\n";
                                                         echo "<td class='text-left'>CONDUCT</td>\r\n";

                                                         if (isset($ssap_conduct)) {
                                                            // echo "<td class='text-center" . ($ssap_conduct->s1 < 75 ? " text-danger" : ($ssap_conduct->s1 >= 90 ? " text-success" : "")) . "'><b>" . ($ssap_conduct->s1 == 0 ? '' : gradeCode($codes_table, $ssap_conduct->s1, $show_letter_grade)) . "</b></td>\r\n";
                                                            // echo "<td class='text-center" . ($ssap_conduct->s2 < 75 ? " text-danger" : ($ssap_conduct->s2 >= 90 ? " text-success" : "")) . "'><b>" . ($ssap_conduct->s2 == 0 ? '' : gradeCode($codes_table, $ssap_conduct->s2, $show_letter_grade)) . "</b></td>\r\n";
                                                            echo "<td class='text-center'><b>" . $ssap_conduct->a1 . "</b></td>\r\n";
                                                            echo "<td class='text-center'><b>" . $ssap_conduct->a2 . "</b></td>\r\n";

                                                            // if ($show_average_column) {
                                                            //    echo "<td class='text-center" . (($ssap_conduct['s1'] / $ssap_conduct['s2']) < 75 ? " text-danger" : (($ssap_conduct['s1'] / $ssap_conduct['s2']) >= 90 ? " text-success" : "")) . "'><b>" . (($ssap_conduct['s1'] / $ssap_conduct['s2']) == 0 ? '--' : gradeCode($codes_table, ($ssap_conduct['s1'] / $ssap_conduct['s2']), $show_letter_grade)) . "</b></td>\r\n";
                                                            // }

                                                            if ($ssap_conduct->a1 != null && $ssap_conduct->a2 != null) {
                                                               // echo "<td class='text-center" . (($ssap_conduct->s1 / $ssap_conduct->s2) < 75 ? " text-danger" : (($ssap_conduct->s1 / $ssap_conduct->s2) >= 90 ? " text-success" : "")) . "'><b>" . (($ssap_conduct->s1 / $ssap_conduct->s2) == 0 ? '' : gradeCode($codes_table, ($ssap_conduct->s1 / $ssap_conduct->s2), $show_letter_grade)) . "</b></td>\r\n";
                                                               echo "<td class='text-center'>><b>" . $ssap_conduct->finalgrade . "</b></td>\r\n";
                                                            } else {
                                                               echo "<td class='text-left'>&nbsp</td>\r\n";
                                                            }
                                                         } else {
                                                            echo "<td class='text-left'>&nbsp</td>\r\n";
                                                            echo "<td class='text-left'>&nbsp</td>\r\n";
                                                            echo "<td class='text-left'>&nbsp</td>\r\n";
                                                         }

                                                         echo "</tr>\r\n";
                                                      }
                                                      ?>
                                                   </tbody>
                                                   <tfoot>
                                                      <?php if ($show_general_average) { ?>
                                                         <tr>
                                                            <th class="text-right">General Average</th>
                                                            <th class="text-center <?php echo ($q1Ave < 75 ? "text-danger" : ($q1Ave >= 90 ? "text-success" : "")); ?>"><?php echo ($q1Ave == 0 ? "" : number_format($q1Ave, 2)); ?></th>
                                                            <th class="text-center <?php echo ($q2Ave < 75 ? "text-danger" : ($q2Ave >= 90 ? "text-success" : ""));; ?>"><?php echo ($q2Ave == 0 ? "" : number_format($q2Ave, 2)); ?></th>
                                                            <?php if ($q3Tot !== null) { ?>
                                                               <th class="text-center <?php echo ($q3Ave < 75 ? "text-danger" : ($q3Ave >= 90 ? "text-success" : ""));; ?>"><?php echo ($q3Ave == 0 ? "" : number_format($q3Ave, 2)); ?></th>
                                                            <?php } ?>
                                                            <?php if ($q4Tot !== null) { ?>
                                                               <th class="text-center <?php echo ($q4Ave < 75 ? "text-danger" : ($q4Ave >= 90 ? "text-success" : ""));; ?>"><?php echo ($q4Ave == 0 ? "" : number_format($q4Ave, 2)); ?></th>
                                                            <?php }

                                                            if ($show_average_column) : ?>
                                                               <th class="text-center <?php echo ($aveAve < 75 ? "text-danger" : ($aveAve >= 90 ? "text-success" : "")); ?>"><?php echo ($aveAve == 0 ? "" : number_format($aveAve, 2)); ?></th>
                                                            <?php endif; ?>
                                                            <th class="text-center <?php echo ($finAve < 75 ? "text-danger" : ($finAve >= 90 ? "text-success" : ""));; ?>"><?php echo ($finAve == 0 ? "" : number_format($finAve, 2)); ?></th>
                                                         </tr>
                                                      <?php } ?>
                                                   </tfoot>
                                                </table>
                                                <?php //} 
                                                ?>
                                             </div>
                                          </div>
                                       </div>
                                    </div>

                                    <div class="col-md-12">
                                       <div class="box box-warning">
                                          <div class="box-header ptbnull">
                                             <h3 class="box-title titlefix"> <?php echo "Attendance"; ?></h3>
                                          </div>
                                          <div class="box-body">
                                             <div class="table-responsive">
                                                <div class="download_label"><?php echo 'Attendance'; ?></div>
                                                <?php $attendance_categories = array(
                                                   'Days Present' => 'attendance',
                                                   'Days Absent' => 'absent',
                                                   'Days Tardy' => 'tardy',
                                                );

                                                $totDOS = 0;
                                                $totRow = 0;
                                                ?>
                                                <table id="class_record" class="table table-striped table-bordered table-hover classrecord nowrap">
                                                   <thead>
                                                      <tr>
                                                         <th class="text-left"></th>
                                                         <?php
                                                         foreach ($month_days_list as $row) {
                                                            echo "<th class=\"text-center\">" . $row->month . "</th>\r\n";
                                                         }
                                                         ?>
                                                         <th class="text-center">Total</th>
                                                      </tr>
                                                   </thead>
                                                   <tbody>
                                                      <tr>
                                                         <td>Days of School</td>
                                                         <?php
                                                         foreach ($month_days_list as $row) {
                                                            // if (isTermAllowed($terms_allowed, $row->term)) {
                                                            //    echo "<td class=\"text-center\">" . $row->no_of_days . "</td>";
                                                            //    $totDOS += $row->no_of_days;
                                                            // } else {
                                                            //    echo "<td class=\"text-center\">&nbsp;</td>";
                                                            // }

                                                            echo "<td class=\"text-center\">" . $row->no_of_days . "</td>";
                                                            $totDOS += $row->no_of_days;
                                                         }
                                                         ?>
                                                         <td class="text-center"><b><?php echo $totDOS; ?></b></td>
                                                      </tr>

                                                      <?php
                                                      foreach ($attendance_categories as $key => $value) :
                                                         $totRow = 0;
                                                      ?>
                                                         <tr>
                                                            <td><?php echo $key ?></td>
                                                            <?php
                                                            foreach ($month_days_list as $row) {
                                                               // if (isTermAllowed($terms_allowed, $row->term)) {
                                                               //    $month = $row->month;
                                                               //    echo "<td class=\"text-center\">" . json_decode($student_attendance[$value])->$month . "</td>";
                                                               //    $totRow += intval(json_decode($student_attendance[$value])->$month);
                                                               // } else {
                                                               //    echo "<td class=\"text-center\">&nbsp;</td>";
                                                               // }
                                                               $month = $row->month;
                                                               echo "<td class=\"text-center\">" . json_decode($student_attendance[$value])->$month . "</td>";
                                                               $totRow += intval(json_decode($student_attendance[$value])->$month);
                                                            }
                                                            ?>

                                                            <td class="text-center"><b><?php echo $totRow; ?></b></td>
                                                         </tr>
                                                      <?php endforeach; ?>
                                                   </tbody>
                                                   <tfoot>
                                                   </tfoot>
                                                </table>
                                             </div>
                                          </div>
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
                                                                     <th class="text-left">ID</th>
                                                                     <th class="text-left">Core Indicator</th>
                                                                     <th class="text-left">Behavior Statements</th>
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
   var class_id = $('#class_id').val();
   var section_id = '<?php echo set_value('section_id') ?>';
   var school_year_id = '<?php echo set_value('session_id') ?>';
   var student_id = '<?php echo set_value('student_id') ?>';

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
      getSectionByClass(class_id, section_id);
      getStudentsByClassSection(class_id, section_id, school_year_id, student_id);

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
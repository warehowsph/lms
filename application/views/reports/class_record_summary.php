<?php

function gradeCode($codes, $grade, $show)
{
   $retVal = '';

   if ($show) {
      $retVal = '';

      $grade = number_format($grade, 2);

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
                  <form role="form" action="<?php echo site_url('report/class_record_summary') ?>" method="post" class="">
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
                              <label><?php echo "Term"; ?></label><small class="req"> *</small>
                              <select autofocus="" id="quarter_id" name="quarter_id" class="form-control">
                                 <option value=""><?php echo $this->lang->line('select'); ?></option>
                                 <?php
                                 foreach ($quarter_list as $quarter) {
                                 ?>
                                    <option value="<?php echo $quarter['id'] ?>" <?php if (set_value('quarter_id') == $quarter['id']) echo "selected=selected" ?>><?php echo $quarter['description'] ?></option>
                                 <?php
                                    //$count++;
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

               <div class="box box-warning">
                  <div class="box-header ptbnull">
                     <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo form_error('class_record_summary'); ?> Summary of Consolidated Grades<?php //echo $this->lang->line('class_record_summary') ; 
                                                                                                                                                                  ?></h3>
                  </div>
                  <div class="box-body table-responsive">
                     <?php if (isset($resultlist)) { ?>
                        <div class="download_label"><?php echo $this->lang->line('class_record_summary') ?></div>
                        <table class="table table-striped table-bordered table-hover classrecord nowrap" cellspacing="0" width="100%">
                           <thead>
                              <tr>
                                 <th>Learner Names</th>
                                 <th>Gender</th>
                                 <?php
                                 foreach ($subject_list as $row) {
                                    // if ($show_letter_grade)
                                    //    echo "<th class=\"text-center\" colspan=\"2\">" . $row->subject . "</th>\r\n";
                                    // else
                                    echo "<th class=\"text-center\">" . $row->subject . "</th>\r\n";
                                 }
                                 ?>
                                 <th>Average</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              foreach ($resultlist as $row) {

                                 $ctr = 0;
                                 echo "<tr>\r\n";

                                 foreach ($row as $val) {
                                    if ($show_letter_grade) {
                                       if ($ctr <= 1) {
                                          echo "<td class='text-left'>" . $val . "</td>\r\n";
                                       } else {
                                          echo "<td class='text-center" . ($val < 75 ? " text-danger" : ($val >= 90 ? " text-success" : "")) . "'><b>" . ($val == 0 ? '' : $val . " - " . gradeCode($codes_table, $val, $show_letter_grade)) . "</b></td>\r\n";
                                       }
                                    } else {
                                       if ($ctr <= 1)
                                          echo "<td class='text-left'>" . $val . "</td>\r\n";
                                       else
                                          echo "<td class='text-center" . ($val < 75 ? " text-danger" : ($val >= 90 ? " text-success" : "")) . "'><b>" . ($val == 0 ? '' : $val) . "</b></td>\r\n";
                                    }

                                    $ctr++;
                                 }
                                 echo "</tr>\r\n";
                              }
                              ?>
                           </tbody>
                        </table>
                  </div>
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

   $(document).ready(function() {
      var class_id = $('#class_id').val();
      var section_id = '<?php echo set_value('section_id') ?>';
      var term_id = '<?php echo set_value('quarter_id') ?>';

      getSectionByClass(class_id, section_id);
      getTermByGradeLevel(class_id, term_id);

      var table = $('.classrecord').DataTable({
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
         ]
      });

      $(document).on('change', '#class_id', function(e) {
         $('#section_id').html("");
         var class_id = $(this).val();
         // var base_url = '<?php echo base_url() ?>';
         // var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

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
   });
</script>
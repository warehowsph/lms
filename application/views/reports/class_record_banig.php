<style>
   thead {
      border-top: 1px solid #dddddd;
   }

   th {
      border-top: 1px solid #dddddd;
      border-bottom: 1px solid #dddddd;
      border-right: 1px solid #dddddd;
   }

   th:first-child {
      border-left: 1px solid #dddddd;
   }
</style>

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
                  <form role="form" action="<?php echo site_url('report/class_record_banig') ?>" method="post" class="">
                     <div class="row">
                        <?php echo $this->customlib->getCSRF(); ?>
                        <div class="col-sm-6 col-md-4">
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

                        <div class="col-sm-6 col-md-4">
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

                        <div class="col-sm-6 col-md-4">
                           <div class="form-group">
                              <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                              <select id="section_id" name="section_id" class="form-control">
                                 <option value=""><?php echo $this->lang->line('select'); ?></option>
                              </select>
                              <span class="text-danger"><?php echo form_error('section_id'); ?></span>
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
                     <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo form_error('class_record_quarterly'); ?> DepEd Banig<?php ?></h3>
                  </div>
                  <div class="box-body table-responsive">
                     <?php if (isset($resultlist)) { ?>
                        <div class="download_label"><?php echo "DEPED BANIG - (" . $this->sch_setting_detail->name . ")";
                                                      $this->customlib->get_postmessage(); ?></div>
                        <table class="table cell-border nowrap banig" cellspacing="0" width="100%">
                           <thead>
                              <tr>
                                 <th rowspan="2" class="text-center">Learner Names</th>
                                 <th rowspan="2" class="text-center">Gender</th>
                                 <?php

                                 $subjectColSpan = count($quarter_list) + 1;

                                 foreach ($subject_list as $row) {
                                    echo ('<th colspan="' . $subjectColSpan . '" class="text-center">' . $row->subject . '</th>');
                                 }
                                 ?>
                                 <th colspan="<?php echo count($quarter_list) ?>" class="text-center">Averages</th>
                                 <th rowspan="2" class="text-center">COMPUTED AVE.</th>
                                 <th rowspan="2" class="text-center"></th>
                              </tr>
                              <tr>
                                 <?php
                                 foreach ($subject_list as $row) {
                                    foreach ($quarter_list as $row) {
                                       echo "<th class=\"text-center\">" . $row['description'] . "</th>\r\n";
                                    }

                                    echo ('<th class="text-center">Ave.</th>');
                                 }

                                 foreach ($quarter_list as $row) {
                                    echo "<th class=\"text-center\">" . $row['description'] . "</th>\r\n";
                                 }
                                 ?>
                                 <!-- <th class="text-center">Q1</th>
                                 <th class="text-center">Q2</th>
                                 <th class="text-center">Q3</th>
                                 <th class="text-center">Q4</th> -->

                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              foreach ($resultlist as $row) {
                                 $ctr = 0;
                                 $is_zero = false;
                                 echo "<tr>\r\n";

                                 foreach ($row as $value) {
                                    if (!is_numeric($value))
                                       echo "<td class='text-left'>" . $value . "</td>\r\n";
                                    else
                                                    if ($value <= 0)
                                       echo "<td class='text-center'></td>\r\n";
                                    else
                                       echo "<td class='text-center'>" . $value . "</td>\r\n";

                                    $ctr++;
                                 }

                                 echo "</tr>\r\n";
                              }
                              // foreach($resultlist as $row):
                              // endforeach
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

   function getSubjectsByClass(class_id, subject_id, school_year_id) {
      if (class_id != "") {
         $('#subject_id').html("");
         var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
         $.ajax({
            type: "GET",
            url: base_url + "admin/subject/get_subject_list",
            data: {
               'class_id': class_id,
               'school_year_id': school_year_id
            },
            dataType: "json",
            beforeSend: function() {
               $('#subject_id').addClass('dropdownloading');
            },
            success: function(data) {
               $.each(data, function(i, obj) {
                  var sel = "";
                  if (subject_id == obj.subject_id) {
                     sel = "selected";
                  }
                  div_data += "<option value=" + obj.subject_id + " " + sel + ">" + obj.subject + "</option>";
               });
               $('#subject_id').append(div_data);
            },
            complete: function() {
               $('#subject_id').removeClass('dropdownloading');
            }
         });
      }
   }

   $(document).ready(function() {
      var table = $('.banig').DataTable({
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

      class_id = $('#class_id').val();
      var section_id = '<?php echo set_value('section_id') ?>';
      var subject_id = '<?php echo set_value('subject_id') ?>';
      var school_year_id = '<?php echo set_value('session_id') ?>';
      getSectionByClass(class_id, section_id);
      getSubjectsByClass(class_id, subject_id, $('#session_id').val());

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

         $('#subject_id').html("");
         var div_data2 = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
         $.ajax({
            type: "GET",
            url: base_url + "admin/subject/get_subject_list",
            data: {
               'class_id': class_id,
               'school_year_id': $('#session_id').val()
            },
            dataType: "json",
            beforeSend: function() {
               $('#subject_id').addClass('dropdownloading');
            },
            success: function(data) {
               $.each(data, function(i, obj) {
                  div_data2 += "<option value=" + obj.subject_id + ">" + obj.subject + "</option>";
               });
               $('#subject_id').append(div_data2);
            },
            complete: function() {
               $('#subject_id').removeClass('dropdownloading');
            }
         });
      });
   });
</script>
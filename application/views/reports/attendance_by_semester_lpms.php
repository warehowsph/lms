<div class="content-wrapper" style="min-height: 946px;">
   <section class="content-header">
      <h1><i class="fa fa-line-chart"></i> <?php echo $this->lang->line('reports'); ?> <small> <?php echo $this->lang->line('filter_by_name1'); ?></small></h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box removeboxmius">
               <div class="box-header ptbnull"></div>

               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-search"></i> Student Attendance By Semester</h3>
               </div>

               <div class="box-body">
                  <form role="form" action="<?php echo site_url('report/attendance_by_semester_lpms') ?>" method="post" class="">
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
                     <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo form_error('class_record_quarterly'); ?> Attendance for <?php echo $student['firstname'] . ' ' . $student['lastname'] ?></h3>
                  </div>
                  <div class="box-body table-responsive">
                     <?php if (isset($resultlist)) { ?>
                        <form id="attendance" action="<?php echo site_url('report/save_attendance_lpms') ?>" method="POST">
                           <input type="hidden" name="session_id" value="<?php echo $session_id ?>">
                           <input type="hidden" name="class_id" value="<?php echo $class_id ?>">
                           <input type="hidden" name="section_id" value="<?php echo $section_id ?>">
                           <input type="hidden" name="student_id" value="<?php echo $student_id ?>">
                           <section class="content-header">
                              <h1><i class="fa fa-calendar-times-o"></i> <?php echo $this->lang->line('grades'); ?> </h1>
                           </section>
                           <!-- Main content -->
                           <section class="content">
                              <div class="col-md-12">
                                 <div class="box box-warning">

                                    <div class="box-body">


                                       <div class="table-responsive">
                                          <?php $attendance_categories = array(
                                             'Days of School' => 'days_of_school',
                                             'Days Present' => 'days_present',
                                             'Tardiness' => 'tardiness',
                                             'Improper Uniform' => 'improper_uniform',
                                          ); ?>
                                          <table id="attendance" class="table table-striped table-bordered table-hover nowrap">
                                             <thead>
                                                <tr>
                                                   <th></th>
                                                   <th class="text-center">1st Trim</th>
                                                   <th class="text-center">2nd Trim</th>
                                                   <th class="text-center">3rd Trim</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                <?php
                                                foreach ($attendance_categories as $key => $value) : ?>
                                                   <tr>
                                                      <td><?php echo $key ?></td>

                                                      <?php if ($student_attendance) : ?>
                                                         <td class="text-center"><input class="semester_edit" type="number" edittype="first" size="6" name="first[<?php echo $value ?>]" category="<?php echo $value ?>" value="<?php print_r(json_decode($student_attendance['first_trim'])->$value) ?>" min="0" /></td>
                                                      <?php else : ?>
                                                         <td class="text-center"><input class="semester_edit" type="number" edittype="first" size="6" name="first[<?php echo $value ?>]" category="<?php echo $value ?>" value="" min="0" /></td>
                                                      <?php endif; ?>

                                                      <?php if ($student_attendance) : ?>
                                                         <td class="text-center"><input class="semester_edit" type="number" edittype="second" size="6" name="second[<?php echo $value ?>]" category="<?php echo $value ?>" value="<?php print_r(json_decode($student_attendance['second_trim'])->$value) ?>" min="0" /></td>
                                                      <?php else : ?>
                                                         <td class="text-center"><input class="semester_edit" type="number" edittype="second" size="6" name="second[<?php echo $value ?>]" category="<?php echo $value ?>" value="" min="0" /></td>
                                                      <?php endif; ?>

                                                      <?php if ($student_attendance) : ?>
                                                         <td class="text-center"><input class="semester_edit" type="number" edittype="third" size="6" name="third[<?php echo $value ?>]" category="<?php echo $value ?>" value="<?php print_r(json_decode($student_attendance['third_trim'])->$value) ?>" min="0" /></td>
                                                      <?php else : ?>
                                                         <td class="text-center"><input class="semester_edit" type="number" edittype="third" size="6" name="third[<?php echo $value ?>]" category="<?php echo $value ?>" value="" min="0" /></td>
                                                      <?php endif; ?>
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
                              <div class="form-group">
                                 <div class="col-sm-12">
                                    <!-- <button type="submit" name="search" value="search_filter" class="btn btn-primary checkbox-toggle pull-right"> <i class="fa fa-save"></i> Save</button> -->
                                    <button type="submit" name="action" class="btn btn-primary pull-right save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving..."><i class="fa fa-save"></i><?php echo " Save"; ?></button>
                                 </div>
                              </div>
                  </div>
   </section>
   </form>
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
   $(".month_edit").change(function() {

      var month_values = $(".month_edit");
      var total_month_values = 0;
      var months_object = {};
      $.each(month_values, function(key, value) {
         // console.log($(value).val());
         if ($(value).val()) {
            total_month_values = parseInt(total_month_values) + parseInt($(value).val());
            months_object[$(value).attr("month")] = parseInt($(value).val());
         } else {
            months_object[$(value).attr("month")] = 0;
         }
      });
      var months_json = JSON.stringify(months_object);

      $("#total_present").text(total_month_values);
   });

   $(".absent_edit").change(function() {

      var month_values = $(".absent_edit");
      var total_month_values = 0;
      var months_object = {};
      $.each(month_values, function(key, value) {
         // console.log($(value).val());
         if ($(value).val()) {
            total_month_values = parseInt(total_month_values) + parseInt($(value).val());
         }
      });

      $("#total_absent").text(total_month_values);
   });

   $(".tardy_edit").change(function() {

      var month_values = $(".tardy_edit");
      var total_month_values = 0;
      var months_object = {};
      $.each(month_values, function(key, value) {
         // console.log($(value).val());
         if ($(value).val()) {
            total_month_values = parseInt(total_month_values) + parseInt($(value).val());
         }
      });

      $("#total_tardy").text(total_month_values);
   });
   var input_status;
   var input_index;
   $("input").click(function() {
      input_status = $(this).attr("class");

      input_index = $("." + input_status).index(this);

   });

   $(window).keydown(function(event) {
      if (event.keyCode == 13) {
         event.preventDefault();

         console.log(input_status);
         console.log(input_index);
         $("." + input_status).eq(input_index + 1).focus();
         input_index = input_index + 1;

      }
   });

   $("#attendance").submit(function(event) {
      event.preventDefault();
      var url = '<?php echo base_url('report/save_attendance_lpms') ?>';

      var $this = $('.save');
      $this.button('loading');
      var frmdata = new FormData(this);

      $.ajax({
         url: '<?php echo site_url('report/save_attendance_lpms') ?>',
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
               errorMsg(res.message);
            } else {
               successMsg(res.message);
            }
         },
         error: function(jqXHR, textStatus, errorThrown) {
            // alert(errorThrown);
         },
         complete: function(data) {
            $this.button('reset');
         }
      });
   });
</script>
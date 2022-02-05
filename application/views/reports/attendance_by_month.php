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
                  <h3 class="box-title"><i class="fa fa-search"></i> Student Attendance By Month</h3>
               </div>

               <div class="box-body">
                  <form role="form" action="<?php echo site_url('report/attendance_by_month') ?>" method="post" class="">
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
                     <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo form_error('class_record_quarterly'); ?> Student Attendance </h3>
                  </div>
                  <div class="box-body table-responsive">
                     <?php if (isset($resultlist)) { ?>
                        <form id="attendance" action="<?php echo site_url('report/save_attendance') ?>" method="POST">
                           <input type="hidden" name="session_id" value="<?php echo $session_id ?>">
                           <input type="hidden" name="class_id" value="<?php echo $class_id ?>">
                           <input type="hidden" name="section_id" value="<?php echo $section_id ?>">
                           <input type="hidden" name="student_id" value="<?php echo $student_id ?>">

                           <section class="content-header">
                              <h1><i class="fa fa-calendar-times-o"></i> <?php echo $this->lang->line('grades'); ?> </h1>
                           </section>
                           <!-- Main content -->
                           <section class="content">
                              <div class="form-group mb-1">
                                 <div class="col-sm-12 p-3">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary checkbox-toggle pull-right"> <i class="fa fa-save"></i> Save</button>
                                 </div>
                              </div>
                              <div class="col-md-12">
                                 <div class="box box-warning">
                                    <div class="box-body">
                                       <div class="table-responsive">
                                          <table id="attendance" class="table table-striped table-bordered table-hover nowrap">
                                             <thead>
                                                <tr>
                                                   <th>Month</th>
                                                   <th class="text-center">Days of School</th>
                                                   <th class="text-center">Days Present</th>
                                                   <th class="text-center">Days Absent</th>
                                                   <th class="text-center">Days Tardy</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                <?php $total_days = 0; ?>
                                                <?php $total_present = 0; ?>
                                                <?php $total_absent = 0; ?>
                                                <?php $total_tardy = 0; ?>
                                                <?php foreach ($month_days_list as $value) : ?>
                                                   <?php
                                                   if ($value->term <= $term_id)
                                                      $total_days += $value->no_of_days;
                                                   ?>
                                                   <?php if ($student_attendance) :
                                                      $month = $value->month; ?>
                                                      <?php $total_present += $value->term == $term_id ? json_decode($student_attendance['attendance'])->$month : $value->no_of_days ?>
                                                      <?php $total_absent += json_decode($student_attendance['absent'])->$month ?>
                                                      <?php $total_tardy += json_decode($student_attendance['tardy'])->$month ?>
                                                   <?php endif; ?>
                                                   <?php //if ($value->term <= $term_id) : 
                                                   ?>
                                                   <tr>
                                                      <td><?php echo $value->month ?></td>
                                                      <td class="text-center">
                                                         <input class="nod text-center" type="text" readonly size="6" name="nod[<?php echo $value->month ?>]" id="nod[<?php echo $value->month ?>]" month="<?php echo $value->month ?>" value="<?php echo ($value->term <= $term_id ? $value->no_of_days : 0) ?>" min="0" max="<?php echo $value->no_of_days ?>" />
                                                      </td>

                                                      <td class="text-center">
                                                         <?php if ($student_attendance['attendance']) : ?>
                                                            <input class="present_edit text-center" <?php echo ($value->term == $term_id ? '' : 'readonly'); ?> type="number" edittype="present" size="6" name="present[<?php echo $value->month ?>]" id="present[<?php echo $value->month ?>]" month="<?php echo $value->month ?>" value="<?php print_r(json_decode($student_attendance['attendance'])->$month) ?>" min="0" max="<?php echo $value->no_of_days ?>" />
                                                         <?php else : ?>
                                                            <input class="present_edit text-center" <?php echo ($value->term == $term_id ? '' : 'readonly'); ?> type="number" edittype="present" size="6" name="present[<?php echo $value->month ?>]" id="present[<?php echo $value->month ?>]" month="<?php echo $value->month ?>" value="<?php echo $value->term <= $term_id ? $value->no_of_days : 0 ?>" min="0" max="<?php echo $value->no_of_days ?>" />
                                                         <?php endif; ?>
                                                      </td>
                                                      <td class="text-center">
                                                         <?php if ($student_attendance['absent']) : ?>
                                                            <input class="absent_edit text-center" <?php echo ($value->term == $term_id ? '' : 'readonly'); ?> type="text" edittype="absent" size="6" readonly name="absent[<?php echo $value->month ?>]" id="absent[<?php echo $value->month ?>]" month="<?php echo $value->month ?>" value="<?php print_r(json_decode($student_attendance['absent'])->$month) ?>" min="0" max="<?php echo $value->no_of_days ?>" />
                                                         <?php else : ?>
                                                            <input class="absent_edit text-center" <?php echo ($value->term == $term_id ? '' : 'readonly'); ?> type="text" edittype="absent" size="6" readonly name="absent[<?php echo $value->month ?>]" id="absent[<?php echo $value->month ?>]" month="<?php echo $value->month ?>" value="" min="0" max="<?php echo $value->no_of_days ?>" />
                                                         <?php endif; ?>
                                                      </td>
                                                      <td class="text-center">
                                                         <?php if ($student_attendance['tardy']) : ?>
                                                            <input class="tardy_edit text-center" <?php echo ($value->term == $term_id ? '' : 'readonly'); ?> type="number" edittype="tardy" size="6" name="tardy[<?php echo $value->month ?>]" id="tardy[<?php echo $value->month ?>]" month="<?php echo $value->month ?>" value="<?php print_r(json_decode($student_attendance['tardy'])->$month) ?>" min="0" max="<?php echo $value->no_of_days ?>" />
                                                         <?php else : ?>
                                                            <input class="tardy_edit text-center" <?php echo ($value->term == $term_id ? '' : 'readonly'); ?> type="number" edittype="tardy" size="6" name="tardy[<?php echo $value->month ?>]" id="tardy[<?php echo $value->month ?>]" month="<?php echo $value->month ?>" value="" min="0" max="<?php echo $value->no_of_days ?>" />
                                                         <?php endif; ?>

                                                      </td>
                                                   </tr>
                                                   <?php //endif 
                                                   ?>
                                                <?php endforeach; ?>

                                                <tr>
                                                   <td class="text-right" id="total">Total</td>
                                                   <td class="text-center" id="total_days"><?php echo $total_days ?></td>
                                                   <td class="text-center" id="total_present"><?php echo $total_present ?></td>
                                                   <td class="text-center" id="total_absent"><?php echo $total_absent ?></td>
                                                   <td class="text-center" id="total_tardy"><?php echo $total_tardy ?></td>
                                                </tr>
                                                <?php //if($hasACP == 'yes'):  
                                                ?>
                                                <?php //if ($class_id == '14' || $class_id == '15') : 
                                                ?>
                                                <!-- <tr>
                                                      <td id="acp">ACP</td>
                                                      <td id="acp_input_td" colspan="3">
                                                         <select name="acp" class="form-control">
                                                            <option value="P" <?php echo ($student_attendance['acp'] == 'P') ? "selected" : ""; ?>>P</option>
                                                            <option value="F" <?php echo ($student_attendance['acp'] == 'F') ? "selected" : ""; ?>>F</option>
                                                         </select>
                                                      </td>
                                                   </tr> -->
                                                <?php //endif; 
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
   });

   $(".present_edit").change(function() {
      var month_values = $(".present_edit");
      var absent_values = $(".absent_edit");
      var nod_values = $(".nod");
      var total_month_values = 0;
      var months_object = {};

      $.each(month_values, function(key, value) {
         // console.log($(value).val());
         if ($(value).val()) {
            var absent = parseInt(nod_values[key].value) - parseInt($(value).val());

            if (absent >= 0) {
               absent_values[key].value = parseInt(nod_values[key].value) - parseInt($(value).val());
            } else {
               month_values[key].value = nod_values[key].value;
            }

            total_month_values = parseInt(total_month_values) + parseInt($(value).val());
            months_object[$(value).attr("present")] = parseInt($(value).val());
         } else {
            months_object[$(value).attr("present")] = 0;
         }
      });

      $(".absent_edit").trigger('change');

      var months_json = JSON.stringify(months_object);
      $("#total_present").text(total_month_values);
   });

   $(".absent_edit").change(function() {
      var month_values = $(".absent_edit");
      // var present_values = $(".present_edit");
      // var nod_values = $(".nod");
      var total_month_values = 0;
      var months_object = {};

      //-- adjust present value
      // var presentVal = $("#present[" + month + "]").val();
      // var absentVal = $("#absent[" + month + "]").val();
      // $("#present[" + month + "]").val(presentVal - absentVal);

      $.each(month_values, function(key, value) {
         // console.log($(value).val());
         if ($(value).val()) {
            // var present = parseInt(nod_values[key].value) - parseInt($(value).val());

            // if (present >= 0) {
            //    present_values[key].value = parseInt(nod_values[key].value) - parseInt($(value).val());
            // } else {
            //    month_values[key].value = nod_values[key].value;
            // }

            total_month_values = parseInt(total_month_values) + parseInt($(value).val());
         }
      });

      // $(".present_edit").trigger('change');
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

      var $this = $('.save');
      $this.button('loading');
      var frmdata = new FormData(this);

      $.ajax({
         url: '<?php echo site_url('report/save_attendance') ?>',
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
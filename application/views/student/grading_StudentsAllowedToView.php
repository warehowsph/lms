<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style type="text/css">
   @media print {

      .no-print,
      .no-print * {
         display: none !important;
      }
   }
</style>

<div class="content-wrapper" style="min-height: 946px;">
   <section class="content-header">
      <h1>
         <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('student_information'); ?> <small><?php echo $this->lang->line('student1'); ?></small>
      </h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
               </div>
               <div class="box-body">
                  <?php if ($this->session->flashdata('msg')) { ?> <div class="alert alert-success"> <?php echo $this->session->flashdata('msg') ?> </div> <?php } ?>

                  <div class="row">
                     <div class="col-md-12">
                        <div class="row">
                           <form role="form" action="<?php echo site_url('student/grading_StudentsAllowedToView') ?>" method="post" class="">
                              <?php echo $this->customlib->getCSRF(); ?>
                              <div class="col-sm-6">
                                 <div class="form-group">
                                    <label><?php echo $this->lang->line('class'); ?></label> <small class="req"> *</small>
                                    <select autofocus="" id="class_id" name="class_id" class="form-control">
                                       <option value=""><?php echo $this->lang->line('select'); ?></option>
                                       <?php
                                       foreach ($classlist as $class) {
                                       ?>
                                          <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                       <?php
                                          $count++;
                                       } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                 </div>
                              </div>
                              <div class="col-sm-6">
                                 <div class="form-group">
                                    <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                    <select id="section_id" name="section_id" class="form-control">
                                       <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                 </div>
                              </div>

                              <div class="col-sm-12">
                                 <div class="form-group">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                 </div>
                              </div>
                           </form>
                        </div>
                     </div>
                     <!--./col-md-6-->
                  </div>
                  <!--./row-->
               </div>

               <?php $resultsize = sizeof($resultlist); ?>

               <form id='frm_allow_viewing' action="<?php echo site_url('student/grading_AllowStudentsToView') ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                  <div class="nav-tabs-custom border0 navnoshadow">
                     <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo form_error('grading_AllowStudentsToView'); ?> <?php echo $this->lang->line('grade_view_permission'); ?></h3>
                     </div>
                     <?php if (isset($resultlist)) { ?>
                        <div class="tab-content">
                           <div class="download_label"><?php echo $title; ?></div>
                           <div class="tab-pane active table-responsive no-padding" id="tab_1">

                              <table class="table table-striped table-bordered table-hover example nowrap" cellspacing="0" width="100%">
                                 <thead>
                                    <tr>
                                       <th><?php echo $this->lang->line('roll_no'); ?></th>
                                       <th><?php echo $this->lang->line('student_name'); ?></th>
                                       <th><?php echo $this->lang->line('gender'); ?></th>
                                       <?php
                                       $qtr = 1;
                                       foreach ($quarter_list as $row) {
                                          echo "<th class=\"text-center\"><input type=\"checkbox\" id=\"chkqtr" . $qtr . "\">&nbsp;&nbsp;" . $row->description . "</th>\r\n";
                                          $qtr++;
                                       }
                                       ?>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php if (!empty($resultlist)) {
                                       echo "<input type=\"hidden\" name=\"class_id\" value=" . $row->id . ">\r\n";
                                       echo "<input type=\"hidden\" name=\"section_id\" value=" . $row->id . ">\r\n";
                                       foreach ($resultlist as $row) {
                                          echo "<tr>\r\n";
                                          // echo "<input type=\"hidden\" name=\"student_id[]\" value=".$row->id.">\r\n";
                                          echo "<td class='text-left'>" . $row->roll_no . "</td>\r\n";
                                          echo "<td class='text-left'>" . strtoupper($row->student_name) . "</td>\r\n";
                                          echo "<td class='text-left'>" . strtoupper($row->gender) . "</td>\r\n";

                                          echo "<td class='text-center'><input type=\"hidden\" name=\"q1hidden[]\" value=" . $row->id . "_" . $row->session_id . "_" . $row->q1 . "><input type=\"checkbox\" name=\"q1[]\" " . ($row->Q1 == true ? 'CHECKED' : '') . " value=" . $row->id . "_" . $row->session_id . "_" . $row->q1 . "></td>\r\n";
                                          echo "<td class='text-center'><input type=\"hidden\" name=\"q2hidden[]\" value=" . $row->id . "_" . $row->session_id . "_" . $row->q2 . "><input type=\"checkbox\" name=\"q2[]\" " . ($row->Q2 == true ? 'CHECKED' : '') . " value=" . $row->id . "_" . $row->session_id . "_" . $row->q2 . "></td>\r\n";
                                          if ($row->q3)
                                             echo "<td class='text-center'><input type=\"hidden\" name=\"q3hidden[]\" value=" . $row->id . "_" . $row->session_id . "_" . $row->q3 . "><input type=\"checkbox\" name=\"q3[]\" " . ($row->Q3 == true ? 'CHECKED' : '') . " value=" . $row->id . "_" . $row->session_id . "_" . $row->q3 . "></td>\r\n";
                                          if ($row->q4)
                                             echo "<td class='text-center'><input type=\"hidden\" name=\"q4hidden[]\" value=" . $row->id . "_" . $row->session_id . "_" . $row->q4 . "><input type=\"checkbox\" name=\"q4[]\" " . ($row->Q4 == true ? 'CHECKED' : '') . " value=" . $row->id . "_" . $row->session_id . "_" . $row->q4 . "></td>\r\n";
                                          echo "</tr>\r\n";
                                       }
                                    } ?>
                                 </tbody>
                              </table>
                           </div>

                           <div class="row">
                              <div class="box-footer">
                                 <button type="submit" name="action" <?php echo ($resultsize <= 0 ? 'disabled' : ''); ?> value="save_views" class="btn btn-primary pull-right submitviews" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Updating"><?php echo "Save"; ?></button>
                              </div>
                           </div>
                        </div>
                     <?php } ?>
                  </div>
               </form>
            </div>
         </div>
         <!--./box box-primary -->
      </div>
   </section>
</div>

<script type="text/javascript">
   // // call onload or in script segment below form
   // function attachCheckboxHandlers() {
   //     var chk_arr =  document.getElementsByName("q1[]");
   //     var chklength = chk_arr.length; 

   //     for(k=0;k< chklength;k++) {
   //         chk_arr[k].onclick = updateQ1(chk_arr, chklength);
   //     }
   // }

   // function updateQ1(chkarr, chklen) {
   //     var mainchk = document.getElementById("chkqtr1");
   //     var allChecked = true;

   //     for(k=0;k<chklen;k++) {
   //         if (chkarr[k].checked == false) {
   //             allChecked = false;
   //             break;
   //         }
   //     }

   //     mainchk.checked = allChecked;
   // }

   function getSectionByClass(class_id, section_id) {
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
            success: function(data) {
               $.each(data, function(i, obj) {
                  var sel = "";
                  if (section_id == obj.section_id) {
                     sel = "selected";
                  }
                  div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
               });
               $('#section_id').append(div_data);
            }
         });
      }
   }

   $(document).ready(function() {
      var class_id = $('#class_id').val();
      var section_id = '<?php echo set_value('section_id') ?>';
      getSectionByClass(class_id, section_id);
      // attachCheckboxHandlers();     

      $(document).on('change', '#class_id', function(e) {
         $('#section_id').html("");
         var class_id = $(this).val();
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
                  div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
               });
               $('#section_id').append(div_data);
            },
            complete: function() {
               $('#section_id').removeClass('dropdownloading');
            }
         });
      });

      $("#frm_allow_viewing").on('submit', (function(e) {
         e.preventDefault();
         var $this = $('.submitviews');
         $this.button('loading');

         var frmdata = new FormData(this);

         $.ajax({
            url: "<?php echo site_url("student/grading_AllowStudentsToView") ?>",
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
                  window.location.reload(true);
               }
            },
            error: function(jqXHR, textStatus, errorThrown) {},
            complete: function(data) {
               $this.button('reset');
            }
         });
      }));
   });

   $("#chkqtr1").click(function() {
      var chk_arr = document.getElementsByName("q1[]");
      var chklength = chk_arr.length;
      var mainchk = document.getElementById("chkqtr1").checked;

      for (k = 0; k < chklength; k++) {
         if (mainchk)
            chk_arr[k].checked = true;
         else
            chk_arr[k].checked = false;
      }
   });

   $("#chkqtr2").click(function() {
      var chk_arr = document.getElementsByName("q2[]");
      var chklength = chk_arr.length;
      var mainchk = document.getElementById("chkqtr2").checked;

      for (k = 0; k < chklength; k++) {
         if (mainchk)
            chk_arr[k].checked = true;
         else
            chk_arr[k].checked = false;
      }
   });

   $("#chkqtr3").click(function() {
      var chk_arr = document.getElementsByName("q3[]");
      var chklength = chk_arr.length;
      var mainchk = document.getElementById("chkqtr3").checked;

      for (k = 0; k < chklength; k++) {
         if (mainchk)
            chk_arr[k].checked = true;
         else
            chk_arr[k].checked = false;
      }
   });

   $("#chkqtr4").click(function() {
      var chk_arr = document.getElementsByName("q4[]");
      var chklength = chk_arr.length;
      var mainchk = document.getElementById("chkqtr4").checked;

      for (k = 0; k < chklength; k++) {
         if (mainchk)
            chk_arr[k].checked = true;
         else
            chk_arr[k].checked = false;
      }
   });
</script>
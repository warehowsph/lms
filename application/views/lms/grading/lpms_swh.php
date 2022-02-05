<!-- <script type="text/javascript" src="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-info" style="padding:5px;">
               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
               </div>
               <form id="import" action="<?php echo site_url('lms/grading/import_swh') ?>" method="post" enctype="multipart/form-data">
                  <div class="box-body">
                     <?php echo $this->customlib->getCSRF(); ?>
                     <div class="row">
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="class_id"><?php echo $this->lang->line('class'); ?></label>
                              <select autofocus="" id="class_id" name="class_id" class="form-control">
                                 <option value=""><?php echo $this->lang->line('select'); ?></option>
                                 <?php foreach ($classlist as $class) { ?>
                                    <option value="<?php echo $class['id'] ?>"><?php echo $class['class'] ?></option>
                                 <?php } ?>
                              </select>
                              <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="section_id"><?php echo $this->lang->line('section'); ?></label>
                              <select id="section_id" name="section_id" class="form-control">
                                 <option value=""><?php echo $this->lang->line('select'); ?></option>
                              </select>
                              <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="quarter_id"><?php echo "Term" ?></label>
                              <select autofocus="" id="quarter_id" name="quarter_id" class="form-control">
                                 <option value=""><?php echo $this->lang->line('select'); ?></option>
                                 <?php foreach ($quarters as $quarter) { ?>
                                    <option value="<?php echo $quarter['id'] ?>"><?php echo $quarter['description'] ?></option>
                                 <?php  } ?>
                              </select>
                              <span class="text-danger"><?php echo form_error('quarter_id'); ?></span>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="file"><?php echo $this->lang->line('select_csv_file'); ?></label>
                              <div><input class="filestyle form-control" type='file' name='file' id="file" size='20' />
                                 <span class="text-danger"><?php echo form_error('file'); ?></span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6 pt20">
                           <!-- <button type="submit" class="btn btn-info pull-right"><?php echo "Import SWH Data"; ?></button> -->
                           <button type="submit" name="action" class="btn btn-primary pull-right import" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Importing"><?php echo "Import SWH Data"; ?></button>
                        </div>
                     </div>
                  </div>
               </form>
               <div>
               </div>
            </div>
            <div class="row">
               <!-- general form elements -->
               <div class="box box-primary">
                  <div class="box-header ptbnull">
                     <h3 class="box-title titlefix">Study and Work Habbits</h3>
                  </div><!-- /.box-header -->
                  <div class="box-body">
                     <div class="mailbox-messages table-responsive">
                        <div class="download_label"><?php echo "SWH"; ?></div>

                        <table class="table table-striped table-bordered table-hover lpmstable">
                           <thead>
                              <tr>
                                 <th rowspan="2">ID</th>
                                 <th rowspan="2" nowrap>Student's Name</th>
                                 <th colspan="10" nowrap class="text-center">STUDY AND WORK HABITS</th>
                                 <th colspan="7" nowrap class="text-center">SOCIAL ATTITUDE</th>
                                 <th colspan="3" nowrap class="text-center">HEALTH HABITS</th>
                              </tr>
                              <tr>
                                 <?php foreach ($swh_item_list as $key => $value) : ?>
                                    <th><?php echo $value['sub']; ?></th>
                                 <?php endforeach ?>
                              </tr>
                           </thead>
                           <tbody>
                           </tbody>
                        </table><!-- /.table -->
                     </div><!-- /.mail-box-messages -->

                  </div><!-- /.box-body -->

               </div>
            </div>
            <!--/.col (left) -->
            <!-- right column -->
         </div>
   </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
   var table;

   $(document).ready(function() {
      table = $('.lpmstable').DataTable({
         "aaSorting": [],
         rowReorder: {
            selector: 'td:nth-child(2)'
         },
         pageLength: 20,
         // responsive: 'false',
         ordering: false,
         dom: "Bfrtip",
         "columnDefs": [{
            "targets": [1],
            "className": "text-nowrap"
         }],
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

      var class_id = $('#class_id').val();
      var section_id = '<?php echo set_value('section_id') ?>';
      getSectionByClass(class_id, section_id);
   });

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

   $(document).on('change', '#class_id', function(e) {
      table.clear().draw();
      $('#section_id').html("");
      var class_id = $(this).val();

      // $('#section_id').html("");
      // var class_id = $(this).val();
      // var base_url = '<?php echo base_url() ?>';
      // var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
      // $.ajax({
      //    type: "GET",
      //    url: base_url + "sections/getByClass",
      //    data: {
      //       'class_id': class_id
      //    },
      //    dataType: "json",
      //    success: function(data) {
      //       $.each(data, function(i, obj) {
      //          div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
      //       });
      //       $('#section_id').append(div_data);
      //    }
      // });

      getSectionByClass(class_id);
      getTermByGradeLevel(class_id);
   });

   $(document).on('change', '#quarter_id', function(e) {
      table.clear().draw();
      var section_id = $("#section_id").val();
      var class_id = $("#class_id").val();
      var quarter_id = $(this).val();

      $('.download_label').html($("#quarter_id").find("option:selected").text() + '-' + $("#class_id").find("option:selected").text() + '-' + $(this).find("option:selected").text());

      var url = '<?php echo base_url('lms/grading/fetch_lpms_swh_data') ?>' + '?quarter=' + quarter_id + '&grade_level=' + class_id + '&section=' + section_id;
      // table.ajax.data = {
      //    grade_level: class_id,
      //    section: section_id
      // };
      table.ajax.url(url);
      table.ajax.reload();
   });

   $(document).on('change', '#section_id', function(e) {
      table.clear().draw();
      // var quarter_id = $("#quarter_id").val();
      // var class_id = $("#class_id").val();
      // var section_id = $(this).val();

      // $('.download_label').html($("#quarter_id").find("option:selected").text() + '-' + $("#class_id").find("option:selected").text() + '-' + $(this).find("option:selected").text());

      // var url = '<?php //echo base_url('lms/grading/fetch_lpms_swh_data') 
                     ?>' + '?quarter=' + quarter_id + '&grade_level=' + class_id + '&section=' + section_id;
      // // table.ajax.data = {
      // //    grade_level: class_id,
      // //    section: section_id
      // // };
      // table.ajax.url(url);
      // table.ajax.reload();
   });

   $("#import").submit(function(event) {
      event.preventDefault();
      var quarter_id = $("#quarter_id").val();
      var class_id = $("#class_id").val();
      var section_id = $("#section_id").val();
      var url = '<?php echo base_url('lms/grading/fetch_lpms_swh_data') ?>' + '?quarter=' + quarter_id + '&grade_level=' + class_id + '&section=' + section_id;

      var $this = $('.import');
      $this.button('loading');

      var frmdata = new FormData(this);

      $.ajax({
         url: '<?php echo site_url('lms/grading/import_swh') ?>',
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
               // var message = "";
               // $.each(res.error, function(index, value) {
               //    message += value;
               // });

               errorMsg(res.message);
            } else {
               // $('#file').click();
               var drEvent = $('#file').dropify();
               drEvent = drEvent.data('dropify');
               drEvent.resetPreview();
               drEvent.clearElement();
               successMsg(res.message);
               table.ajax.url(url);
               table.ajax.reload();
            }
         },
         error: function(jqXHR, textStatus, errorThrown) {
            // alert('Error!');
            table.ajax.url(url);
            table.ajax.reload();
         },
         complete: function(data) {
            $this.button('reset');
         }
      });
   });
</script>
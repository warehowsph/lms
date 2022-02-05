<div class="content-wrapper" style="min-height: 946px;">
    <!-- Main content -->
    <section class="content" >
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>

                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>

                    <div class="box-body">    
                        <form role="form" action="<?php echo site_url('lms/conduct') ?>" method="post" class="">
                            <div class="row">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="col-sm-6 col-md-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('current_session'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="session_id" name="session_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($session_list as $session) {
                                                ?>
                                                <option value="<?php echo $session['id'] ?>" <?php if ($session['id'] == $sch_setting->session_id) echo "selected=selected" ?>><?php echo $session['session'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('session_id'); ?></span>
                                    </div>
                                </div>      

                                <div class="col-sm-6 col-md-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('quarter'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="quarter_id" name="quarter_id" class="form-control" >
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

                                <div class="col-sm-6 col-md-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $class) {
                                                ?>
                                                <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div> 

                                <div class="col-sm-6 col-md-2">
                                    <div class="form-group">  
                                        <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                        <select  id="section_id" name="section_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>  
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('student'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="student_id" name="student_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('student_id'); ?></span>
                                    </div>
                                </div>                                 
                                
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('show'); ?></button>
                                    </div>
                                </div>
                            </div><!--./row-->     
                        </form>
                    </div><!--./box-body-->    
            
                    <div class="">
                        <form id='frm_conduct_grades' action="<?php echo site_url('lms/conduct/save_conduct_grades') ?>"  method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <!-- submit hidden values -->
                            <input type="hidden" name="session_id" value="<?php echo $session_id ?>">
                            <input type="hidden" name="quarter_id" value="<?php echo $quarter_id ?>">
                            <input type="hidden" name="class_id" value="<?php echo $class_id ?>">
                            <input type="hidden" name="section_id" value="<?php echo $section_id ?>">
                            <input type="hidden" name="student_id" value="<?php echo $student_id ?>">
                            <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                            <div class="box-header ptbnull"></div> 
                            <div class="box-header ptbnull">
                                <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo form_error('class_record_quarterly'); ?> Student Conducts</h3>
                            </div>
                            <div class="box-body table-responsive">
                                <?php if (isset($resultlist)) {?>
                                    <section class="content-header">
                                            <h1><i class="fa fa-calendar-times-o"></i> <?php echo $this->lang->line('grades'); ?> </h1>
                                        </section>
                                        <!-- Main content -->
                                        <section class="content">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="box box-primary">
                                                        <div class="box-body box-profile">
                                                            <h3 class="profile-username text-center">LEGEND</h3>
                                                            <ul class="list-group list-group-unbordered">
                                                                <?php foreach($legend_list as $legendrow) { ?>
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
                                                            <h3 class="box-title titlefix"> <?php echo $student['firstname'] . " " . $student['lastname']; ?></h3>
                                                            <div class="box-tools pull-right"></div>
                                                        </div>
                                                        <div class="box-body">
                                                            <div class="table-responsive">
                                                                <?php if (!empty($resultlist)) { ?>
                                                                    <table id="class_record" class="table table-striped table-bordered table-hover conductTable nowrap">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="text-left">Indicator ID</th>
                                                                                <th class="text-left">DepEd Indicator</th>
                                                                                <th class="text-left">Indicator</th>
                                                                                <th class="text-left">Conduct Grade</th>
                                                                                <!-- <th class="text-left">Core Indicator</th>
                                                                                <th class="text-left">Indicator</th> -->
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php

                                                                            foreach($resultlist as $row) {
                                                                                if ($coreindicator == $row->deped_indicators)
                                                                                    $depedrowspan++;

                                                                                $coreindicator = $row->deped_indicators;
                                                                                $letterVisible = "";
                                                                                $numericVisible = "";
                                                                                if (strtolower($sch_setting->conduct_grading_type) == 'numeric') 
                                                                                    $letterVisible = "HIDDEN";
                                                                                else 
                                                                                    $numericVisible = "HIDDEN";

                                                                                echo "<tr>\r\n";
                                                                                echo "<td class='text-center'>$row->id</td>\r\n";
                                                                                echo "<td class='text-left'>$coreindicator</td>\r\n";
                                                                                echo "<td class='text-left'>$row->indicators</td>\r\n";
                                                                                echo "<td class='text-center'>";
                                                                                echo "<select name='conduct[]' class='form-control'>";
                                                                                echo "<option value=''>".$this->lang->line('select')."</option>";                                                                                

                                                                                foreach($legend_list as $legendrow) {
                                                                                    $selected = "";
                                                                                    if ($legendrow->conduct_grade == $row->conduct)
                                                                                        $selected = "selected";
                                                                                    echo "<option $letterVisible value='".$row->id."-".$legendrow->conduct_grade."' ".$selected.">".$legendrow->conduct_grade."</option>";                                                                                    
                                                                                }
                                                                                
                                                                                echo "</select>";
                                                                                echo "</td>\r\n";
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

                                                                <?php } ?>                            
                                                            </div>                                                                          
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="box-footer">
                                                    <button type="submit" name="action" value="save_views" class="btn btn-primary pull-right submitviews" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Updating"><i class="fa fa-save"></i> <?php echo "Save"; ?></button>
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
                        </form>
                    </div>  
                </div>
            </div> <!-- ./col-md-12 -->        
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
                data: {'class_id': class_id},
                dataType: "json",
                beforeSend: function () {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                },
                complete: function () {
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
                data: {'class_id': class_id, 'section_id': section_id, 'school_year_id': school_year_id },
                dataType: "json",
                beforeSend: function () {
                    $('#student_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (student_id == obj.student_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.student_id + " " + sel + ">" + obj.lastname + ", " + obj.firstname + "</option>";
                    });
                    $('#student_id').append(div_data);
                },
                complete: function () {
                    $('#student_id').removeClass('dropdownloading');
                }
            });
        }
    }
    
    $(document).ready(function () {
        var table = $('.conductTable').DataTable({
            "aaSorting": [],           
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            // pageLength: 100,
            //responsive: 'false',
            paging: false,
            ordering: false,
            searching: false,
            dom: "Bfrtip",
            buttons: [
                {
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
                        customize: function ( win ) {
                    $(win.document.body)
                        .css( 'font-size', '10pt' );
 
                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
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
            "columnDefs": [
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                }
            ]
        });

        var class_id = $('#class_id').val();
        var section_id =  '<?php echo set_value('section_id') ?>';
        var school_year_id = '<?php echo set_value('session_id') ?>';
        var student_id = '<?php echo set_value('student_id') ?>';
        getSectionByClass(class_id, section_id);
        getStudentsByClassSection(class_id, section_id, school_year_id, student_id);

        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            class_id = $(this).val();
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: { 'class_id': class_id },
                dataType: "json",
                beforeSend: function () {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                },
                complete: function () {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        });

        $(document).on('change', '#section_id', function (e) {
            $('#student_id').html("");
            var div_data2 = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "student/getStudentListPerClassSection",
                data: {'class_id': class_id, 'section_id': $('#section_id').val(), 'school_year_id': $('#session_id').val() },
                dataType: "json",
                beforeSend: function () {
                    $('#student_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {                        
                        div_data2 += "<option value=" + obj.student_id + ">" + obj.lastname + ", " + obj.firstname + "</option>";
                    });
                    $('#student_id').append(div_data2);
                },
                complete: function () {
                    $('#student_id').removeClass('dropdownloading');
                }
            });
        });

        $("#frm_conduct_grades").on('submit', (function (e) {
            e.preventDefault();
            var $this = $('.submitviews');
            $this.button('loading');

            var frmdata = new FormData(this);

            $.ajax({
                url: "<?php echo site_url("lms/conduct/save_conduct_grades") ?>",
                type: "POST",
                data: frmdata,
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    $this.button('loading');
                },
                success: function (res) {
                    if (res.status == "fail") {
                        var message = "";
                        $.each(res.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);

                    } else {
                        successMsg(res.message);
                        // window.location.reload(true);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                }, 
                complete: function (data) {
                    $this.button('reset');
                }
            });
        }));
    });
</script>
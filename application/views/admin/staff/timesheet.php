<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php echo $this->lang->line('human_resource'); ?>
        </h1>


    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('timesheet'); ?> </h3>
                        <div class="box-tools">
                            <?php if ($this->rbac->hasPrivilege('timesheet', 'can_add')) {
                                $disableIn = $timeout ? '' : 'disabled';
                                $disableOut = $timeout ? 'disabled' : '';
                            ?>
                                <small><button <?php echo $disableIn; ?> id="timein" class="btn btn-success btn-sm" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><?php echo $this->lang->line('timein'); ?></button></small>
                                <small><button <?php echo $disableOut; ?> id="timeout" class="btn btn-danger btn-sm" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><?php echo $this->lang->line('timeout'); ?></button></small>&nbsp;&nbsp;
                            <?php } ?>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="flex-row">
                            <div>
                                <h4>Today</h4>
                            </div>
                        </div>
                        <?php //if (!empty($timesheet_today)) { 
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="tab-pane active table-responsive no-padding">
                                    <table class="table table-striped table-bordered table-hover nowrap timesheet-today">
                                        <thead>
                                            <th>Name</th>
                                            <th>Date</th>
                                            <th>Time In</th>
                                            <th>Time Out</th>
                                            <th>Hours</th>
                                            <th>Update At</th>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($timesheet_today as $key => $value) { ?>
                                                <tr>
                                                    <td><?php echo $value["name"]; ?></td>
                                                    <td><?php echo $value["date"]; ?></td>
                                                    <td><?php echo $value["timein"]; ?></td>
                                                    <td><?php echo $value["timeout"]; ?></td>
                                                    <td><?php echo $value["hours"]; ?></td>
                                                    <td><?php echo $value["updated_at"]; ?></td>
                                                </tr>
                                            <?php
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php //} else { 
                        ?>
                        <!-- <div class="alert alert-danger">
                                There are no records found.
                            </div> -->
                        <?php //} 
                        ?>

                        <div class="flex-row">
                            <div>
                                <h4>Search Timesheets <?php //echo strpos(strtolower($staffrole), 'admin') . 'E' 
                                                        ?></h4>
                            </div>
                        </div>
                        <div class="flex-row">
                            <?php
                            $$display = "";

                            if (strpos(strtolower($staffrole), 'admin') < 0 || strpos(strtolower($staffrole), 'admin') == null) {
                                $display = 'style="display: none"';
                            }
                            ?>
                            <div class="col-sm-6 col-md-3" <?php echo $display; ?>>
                                <div class="form-group">
                                    <select autofocus="" id="staff_id" name="staff_id" class="form-control">
                                        <option value=""><?php echo "Select Staff" ?></option>
                                        <!-- <option value="all"><?php //echo "All Staff" 
                                                                    ?></option> -->
                                        <?php
                                        foreach ($employee_list as $staff) {
                                        ?>
                                            <option value="<?php echo $staff['id'] ?>" <?php if (set_value('staff_id') == $staff['id']) echo "selected=selected" ?>><?php echo $staff['fullname'] ?></option>
                                        <?php
                                            //$count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <input type="text" id="datefilter" name="datefilter" class="form-control input-md col-sm-6" value="" placeholder="Select Date Range" readonly />
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <small><button id="btn-show" onclick="ShowResult()" type="button" class="btn btn-default btn-sm">Show</button></small>
                                </div>
                            </div>

                            <?php //if (empty($timesheet_search)) { 
                            ?>
                            <div class="col-md-12">
                                <div class="tab-pane active table-responsive no-padding">
                                    <table class="table table-striped table-bordered table-hover nowrap timesheet_search">
                                        <thead>
                                            <th>Name</th>
                                            <th>Date</th>
                                            <th>Time In</th>
                                            <th>Time Out</th>
                                            <th>Hours</th>
                                            <th>Update At</th>
                                            <?php if ($display == "") { ?>
                                                <th>Action</th>
                                            <?php } ?>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <?php //} 
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

<!-- edit account modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="editModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-pencil" style="color:green"></i> Edit Timesheet</h4>
            </div>

            <form role="form" action="<?php echo site_url("admin/staff/c_UpdateTimesheet/") ?>" method="post" id="updateForm" s>
                <div class="modal-body">

                    <?php echo validation_errors(); ?>

                    <div class="form-group">
                        <label for="u_name">Name</label>
                        <input type="text" class="form-control" id="u_name" name="u_name" placeholder="Name" autocomplete="off" disabled>
                    </div>

                    <div class="form-group">
                        <label for="u_date">Date</label>
                        <input type="text" class="form-control" name="u_date" id="u_date" class="form-control input-md col-sm-6" value="" placeholder="Select Date" />
                    </div>

                    <div class="form-group">
                        <label for="timein_picker">Time In</label>
                        <div class="input-group bootstrap-timepicker timepicker">
                            <input name="timein_picker" id="timein_picker" type="text" class="form-control input-small">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="timeout_picker">Time Out</label>
                        <div class="input-group bootstrap-timepicker timepicker">
                            <input name="timeout_picker" id="timeout_picker" type="text" class="form-control input-small">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> -->
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script> -->

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<link rel="stylesheet" href="<?php echo base_url(); ?>backend/timepicker/css/bootstrap-timepicker.min.css">
<script type="text/javascript" src="<?php echo base_url(); ?>backend/timepicker/js/bootstrap-timepicker.min.js"></script>



<script type="text/javascript">
    var staffid = <?php echo $staff_id; ?>;
    var staffrole = '<?php echo $staffrole; ?>';
    var start_date = "";
    var end_date = "";
    var timesheet_search;
    var timesheet_today;
    var isadmin = <?php echo strpos(strtolower($staffrole), 'admin') == null ? -1 : strpos(strtolower($staffrole), 'admin'); ?>;

    $("#datefilter").daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        },
        function(start, end, label) {
            // var years = moment().diff(start, 'years');
            // alert("You are " + years + " years old!");
            // start_date = start.format('YYYY-MM-DD');
            // end_date = end.format('YYYY-MM-DD');
        }
    });

    $('#datefilter').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));

        start_date = picker.startDate.format('YYYY-MM-DD');
        end_date = picker.endDate.format('YYYY-MM-DD');
    });

    $('#datefilter').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        start_date = '';
        end_date = '';
    });

    $(document).ready(function() {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';

        $('#timein_picker').timepicker({
            minuteStep: 1,
            showSeconds: true,
            secondStep: 1
        });
        $('#timeout_picker').timepicker({
            minuteStep: 1,
            showSeconds: true,
            secondStep: 1
        });

        $('#u_date').datepicker({
            format: date_format,
            autoclose: true
        });

        timesheet_today = $('.timesheet-today').DataTable({
            prerender: true,
            processing: true,
            language: {
                sEmptyTable: "No data available",
                processing: "Loading records..."
            },
            pageLength: 15,
            searching: false,
            paging: true,
            info: false,
            // columnDefs: [{
            //     targets: [4],
            //     visible: false
            // }]
        });

        timesheet_search = $('.timesheet_search').DataTable({
            prerender: true,
            processing: true,
            language: {
                sEmptyTable: "No data available",
                processing: "Loading records..."
            },
            pageLength: 15,
            // columnDefs: [{
            //     targets: [4],
            //     visible: false
            // }],
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
    });

    function timeIn() {
        $("#timein").attr("disabled", true);
        var url = '<?php echo site_url("admin/staff/c_TimeIn/") ?>';

        $.ajax({
            url: url + staffid,
            type: 'post',
            dataType: 'json',
            success: function(res) {
                if (res.status == "fail") {
                    var message = "";
                    $.each(res.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);

                    $("#timein").attr("disabled", false);
                    $("#timeout").attr("disabled", true);

                } else {
                    successMsg(res.message);
                    // window.location.reload(true);
                    ShowToday();

                    $("#timein").attr("disabled", true);
                    $("#timeout").attr("disabled", false);
                }
            }
        });
    }

    function timeOut() {
        $("#timeout").attr("disabled", true);
        var url = '<?php echo site_url("admin/staff/c_TimeOut/") ?>';

        $.ajax({
            url: url + staffid,
            type: 'post',
            dataType: 'json',
            success: function(res) {
                if (res.status == "fail") {
                    var message = "";
                    $.each(res.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);

                    $("#timein").attr("disabled", true);
                    $("#timeout").attr("disabled", false);

                } else {
                    successMsg(res.message);
                    // window.location.reload(true);
                    ShowToday();

                    $("#timein").attr("disabled", false);
                    $("#timeout").attr("disabled", true);
                }
            }
        });
    }

    function ShowToday() {
        // var _staffid = isadmin > 0 ? $('#staff_id').val() : staffid;
        var url = '<?php echo site_url("admin/staff/c_GetTimesheetToday/") ?>' + staffid;
        timesheet_today.ajax.url(url);
        timesheet_today.ajax.reload();
    }

    function ShowResult() {
        var _staffid = isadmin > 0 ? $('#staff_id').val() : staffid;
        var url = '<?php echo site_url("admin/staff/c_GetTimesheetByDateRangeByID/") ?>' + _staffid + '/' + (start_date == "" ? "0" : start_date) + "/" + (end_date == "" ? "0" : end_date) + "/" + staffrole;

        // timesheet_search.clear().draw();

        if (_staffid != '') {
            // timesheet-search.ajax.data = { "date_from": start_date, "date_to": end_date };
            timesheet_search.ajax.url(url);
            timesheet_search.ajax.reload();
        }
    }

    $('#timein').on("click", function() {
        // if (isDoubleClicked($(this))) return;

        timeIn();
    });

    $('#timeout').on("click", function() {
        // if (isDoubleClicked($(this))) return;

        timeOut();
    });

    function editFunc(id, name, date, timein, timeout) {
        // alert('EDIT');
        $("#u_name").val(name);
        $("#u_date").val(date);
        $("#timein_picker").val(timein);
        $("#timeout_picker").val(timeout);

        // submit the edit from 
        $("#updateForm").unbind('submit').bind('submit', function() {
            var form = $(this);

            $.ajax({
                url: form.attr('action') + '/' + id,
                type: form.attr('method'),
                data: form.serialize(), // /converting the form data into array and sending it to server
                dataType: 'json',
                success: function(response) {
                    if (response.status == "fail") {
                        var message = "";
                        $.each(response.error, function(index, value) {
                            message += value;
                        });
                        errorMsg(message);

                    } else {
                        successMsg(response.message);
                        // window.location.reload(true);
                        $('#editModal').modal('hide');
                        ShowResult();
                    }
                }
            });

            return false;
        });
    }

    // function isDoubleClicked(element) {
    //     //if already clicked return TRUE to indicate this click is not allowed
    //     if (element.data("isclicked")) return true;

    //     //mark as clicked for 1 second
    //     element.data("isclicked", true);
    //     setTimeout(function() {
    //         element.removeData("isclicked");
    //     }, 1000);

    //     //return FALSE to indicate this click was allowed
    //     return false;
    // }
</script>
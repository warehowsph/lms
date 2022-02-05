<div class="content-wrapper">
    <section class="content-header">
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo "KampusPay Collections"; ?> </h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="flex-row">
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
                                    <table class="table table-striped table-bordered table-hover nowrap kampuspay_collections">
                                        <thead>
                                            <th class="text-left">Transaction #</th>
                                            <th class="text-left">Reference #</th>
                                            <th class="text-left">Fee Type</th>
                                            <th class="text-left">Amount</th>
                                            <th class="text-left">Rebates</th>
                                            <th class="text-left">Date</th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right"></td>
                                            <td class="text-right"></td>
                                            <td class="text-right"></td>
                                            <td></td>
                                        </tfoot>
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

<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> -->
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script> -->

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<link rel="stylesheet" href="<?php echo base_url(); ?>backend/timepicker/css/bootstrap-timepicker.min.css">
<script type="text/javascript" src="<?php echo base_url(); ?>backend/timepicker/js/bootstrap-timepicker.min.js"></script>



<script type="text/javascript">
    var start_date = "";
    var end_date = "";
    var kampuspay_collections;
    var isadmin = <?php echo strpos(strtolower($staffrole), 'admin') == null ? -1 : strpos(strtolower($staffrole), 'admin'); ?>;
    var Today = new Date();

    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    }

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;

        return [year, month, day].join('-');
    }

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
            // $('#datefilter').val(start.format('YYYY-MM-DD'));
        }
    }).val(formatDate(Today) + " - " + formatDate(Today));

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

        // var Today = new Date();
        // Today.setDate(Today.getDate());
        // $('#datefilter').daterangepicker('setDate', Today);
        // $("#datefilter").data().daterangepicker.startDate = moment();
        // $("#datefilter").data().daterangepicker.endDate = moment();
        // $("#datefilter").data().daterangepicker.updateCalendars();

        kampuspay_collections = $('.kampuspay_collections').DataTable({
            prerender: true,
            processing: true,
            language: {
                sEmptyTable: "No data available",
                processing: "Loading records..."
            },
            // "responsive": true,
            // "searching": true,
            // "paging": false,
            // "ordering": true,
            // "scrollX": true,
            pageLength: 15,
            columnDefs: [{
                targets: [3, 4],
                className: "text-right"
            }],
            aaSorting: [
                [5, 'desc']
            ],
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
            ],
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(),
                    data;

                // Remove the formatting to get integer data for summation
                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };

                var totalCollections = api
                    .column(3)
                    .data()
                    .reduce(function(a, b) {
                        return (intVal(a) + intVal(b)).toFixed(2);
                    }, 0);

                console.log(totalCollections);

                var totalRebates = api
                    .column(4)
                    .data()
                    .reduce(function(a, b) {
                        return (intVal(a) + intVal(b)).toFixed(2);
                    }, 0);

                console.log(totalRebates);

                // Update footer
                $(api.column(2).footer()).html('Total');
                $(api.column(3).footer()).html('PHP ' + formatNumber(totalCollections));
                $(api.column(4).footer()).html('PHP ' + formatNumber(totalRebates));
            }
        });

        ShowResult();
    });

    function ShowResult() {
        kampuspay_collections.clear().draw();

        var url = '<?php echo site_url("admin/kampuspay/getKampusPayCollections/") ?>' + (start_date == "" ? formatDate(Today) : start_date) + "/" + (end_date == "" ? formatDate(Today) : end_date);
        kampuspay_collections.ajax.url(url);
        kampuspay_collections.ajax.reload();
    }
</script>
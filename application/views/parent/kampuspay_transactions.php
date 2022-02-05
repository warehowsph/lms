<style type="text/css">
    /* tfoot {
        display: block;
    } */
</style>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo "KampusPay Transactions"; ?> </h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="flex-row">
                            <!-- <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <input type="text" id="datefilter" name="datefilter" class="form-control input-md col-sm-6" value="" placeholder="Select Date Range" readonly />
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <small><button id="btn-show" onclick="ShowResult()" type="button" class="btn btn-default btn-sm">Show</button></small>
                                </div>
                            </div> -->

                            <?php //if (empty($timesheet_search)) { 
                            ?>
                            <div class="col-md-12">
                                <div class="tab-pane active table-responsive no-padding">
                                    <table class="table table-striped table-bordered table-hover nowrap kampuspay_transactions">
                                        <thead>
                                            <th class="text-left">Transaction #</th>
                                            <th class="text-left">Reference #</th>
                                            <th class="text-left">Fee Type</th>
                                            <th class="text-left">Amount</th>
                                            <th class="text-left">Date</th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
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
    var kampuspay_transactions;
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
            // $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
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

        // $('#u_date').datepicker({
        //     format: date_format,
        //     autoclose: true
        // });

        kampuspay_transactions = $('.kampuspay_transactions').DataTable({
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
            // columnDefs: [{
            //     // targets: [3],
            //     // className: "text-right",
            //     data: null,
            //     defaultContent: "No data available",
            //     targets: ['_all']
            // }],
            aaSorting: [
                [4, 'desc']
            ],
            dom: "Bfrtip",
            buttons: [{
                    extend: 'copyHtml5',
                    text: '<i class="fa fa-files-o"></i>',
                    titleAttr: 'Copy',
                    title: 'kampuspay_transactions',
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel',

                    title: 'kampuspay_transactions',
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'csvHtml5',
                    text: '<i class="fa fa-file-text-o"></i>',
                    titleAttr: 'CSV',
                    title: 'kampuspay_transactions',
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'PDF',
                    title: 'kampuspay_transactions',
                    exportOptions: {
                        columns: ':visible'

                    }
                },

                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i>',
                    titleAttr: 'Print',
                    title: 'kampuspay_transactions',
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
                    title: 'kampuspay_transactions',
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

                var totalTransactions = api
                    .column(3)
                    .data()
                    .reduce(function(a, b) {
                        return (intVal(a) + intVal(b)).toFixed(2);
                    }, 0);

                console.log(totalTransactions);

                // Update footer
                $(api.column(2).footer()).html('Total');
                $(api.column(3).footer()).html('PHP ' + formatNumber(totalTransactions));
            }
        });

        ShowResult();
    });

    function ShowResult() {
        var url = '<?php echo site_url("parent/parents/getKampusPayTransactions/") ?>' + (start_date == "" ? formatDate(Today) : start_date) + "/" + (end_date == "" ? formatDate(Today) : end_date);
        // kampuspay_transactions.ajax.data({
        //     startdate: start_date,
        //     enddate: end_date
        // });
        kampuspay_transactions.ajax.url(url);
        kampuspay_transactions.ajax.reload();
    }
</script>
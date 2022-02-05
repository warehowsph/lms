<?php  //echo date('F Y');die;  
?>
<style type="text/css">
   .borderwhite {
      border-top-color: #fff !important;
   }

   .box-header>.box-tools {
      display: none;
   }

   .sidebar-collapse #barChart {
      height: 100% !important;
   }

   .sidebar-collapse #lineChart {
      height: 100% !important;
   }
</style>
<div class="content-wrapper" style="min-height: 946px;">
   <section class="content">
      <div class="col-md-12">
         <?php if ($mysqlVersion && $sqlMode && strpos($sqlMode->mode, 'ONLY_FULL_GROUP_BY') !== FALSE) { ?>
            <div class="alert alert-danger">
               Campus CloudPH may not work properly because ONLY_FULL_GROUP_BY is enabled, consult with your hosting provider to disable ONLY_FULL_GROUP_BY in sql_mode configuration.
            </div>
         <?php } ?>

         <?php
         foreach ($notifications as $notice_key => $notice_value) {
         ?>

            <div class="dashalert alert alert-success alert-dismissible" role="alert">
               <button type="button" class="alertclose close close_notice" data-dismiss="alert" aria-label="Close" data-noticeid="<?php echo $notice_value->id; ?>"><span aria-hidden="true">&times;</span></button>

               <a href="<?php echo site_url('admin/notification') ?>"><?php echo $notice_value->title; ?></a>
            </div>

         <?php } ?>
      </div>
   </section>
</div>
<style>
   canvas {
      -moz-user-select: none;
      -webkit-user-select: none;
      -ms-user-select: none;
   }
</style>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<!-- <script src="<?php echo base_url() ?>backend/js/Chart.min.js"></script>
<script src="<?php echo base_url() ?>backend/js/utils.js"></script> -->
<script type="text/javascript">
   new Chart(document.getElementById("doughnut-chart"), {
      type: 'doughnut',
      data: {
         labels: [<?php foreach ($incomegraph as $value) { ?> "<?php echo $value['income_category']; ?>", <?php } ?>],
         datasets: [{
            label: "Income",
            backgroundColor: [<?php $s = 1;
                              foreach ($incomegraph as $value) { ?> "<?php echo incomegraphColors($s++); ?>", <?php if ($s == 8) {
                                                                                                                  $s = 1;
                                                                                                               }
                                                                                                            } ?>],
            data: [<?php $s = 1;
                     foreach ($incomegraph as $value) { ?><?php echo $value['total']; ?>, <?php } ?>]
         }]
      },
      options: {
         responsive: true,
         circumference: Math.PI,
         rotation: -Math.PI,
         legend: {
            position: 'top',
         },
         title: {
            display: true,

         },
         animation: {
            animateScale: true,
            animateRotate: true
         }
      }
   });

   new Chart(document.getElementById("doughnut-chart1"), {
      type: 'doughnut',
      data: {
         labels: [<?php foreach ($expensegraph as $value) { ?> "<?php echo $value['exp_category']; ?>", <?php } ?>],
         datasets: [{
            label: "Population (millions)",
            backgroundColor: [<?php $ss = 1;
                              foreach ($expensegraph as $value) { ?> "<?php echo expensegraphColors($ss++); ?>", <?php if ($ss == 8) {
                                                                                                                     $ss = 1;
                                                                                                                  }
                                                                                                               } ?>],
            data: [<?php foreach ($expensegraph as $value) { ?><?php echo $value['total']; ?>, <?php } ?>]
         }]
      },
      options: {
         responsive: true,
         circumference: Math.PI,
         rotation: -Math.PI,
         legend: {
            position: 'top',
         },
         title: {
            display: true,

         },
         animation: {
            animateScale: true,
            animateRotate: true
         }
      }
   });

   <?php
   if (($this->module_lib->hasActive('fees_collection')) || ($this->module_lib->hasActive('expense'))) {
   ?>
      $(function() {
         var areaChartOptions = {
            showScale: true,
            scaleShowGridLines: false,
            scaleGridLineColor: "rgba(0,0,0,.05)",
            scaleGridLineWidth: 1,
            scaleShowHorizontalLines: true,
            scaleShowVerticalLines: true,
            bezierCurve: true,
            bezierCurveTension: 0.3,
            pointDot: false,
            pointDotRadius: 4,
            pointDotStrokeWidth: 1,
            pointHitDetectionRadius: 20,
            datasetStroke: true,
            datasetStrokeWidth: 2,
            datasetFill: true,

            maintainAspectRatio: true,
            responsive: true
         };
         var bar_chart = "<?php echo $bar_chart ?>";
         var line_chart = "<?php echo $line_chart ?>";
         if (line_chart) {


            var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
            var lineChart = new Chart(lineChartCanvas);
            var lineChartOptions = areaChartOptions;
            lineChartOptions.datasetFill = false;
            var yearly_collection_array = <?php echo json_encode($yearly_collection) ?>;
            var yearly_expense_array = <?php echo json_encode($yearly_expense) ?>;
            var total_month = <?php echo json_encode($total_month) ?>;
            var areaChartData_expense_Income = {
               labels: total_month,
               datasets: [{
                     label: "Expense",
                     fillColor: "rgba(215, 44, 44, 0.7)",
                     strokeColor: "rgba(215, 44, 44, 0.7)",
                     pointColor: "rgba(233, 30, 99, 0.9)",
                     pointStrokeColor: "#c1c7d1",
                     pointHighlightFill: "#fff",
                     pointHighlightStroke: "rgba(220,220,220,1)",
                     data: yearly_expense_array
                  },
                  {
                     label: "Collection",
                     fillColor: "rgba(102, 170, 24, 0.6)",
                     strokeColor: "rgba(102, 170, 24, 0.6)",
                     pointColor: "rgba(102, 170, 24, 0.9)",
                     pointStrokeColor: "rgba(102, 170, 24, 0.6)",
                     pointHighlightFill: "#fff",
                     pointHighlightStroke: "rgba(60,141,188,1)",
                     data: yearly_collection_array
                  }
               ]
            };


            lineChart.Line(areaChartData_expense_Income, lineChartOptions);
         }

         var current_month_days = <?php echo json_encode($current_month_days) ?>;
         var days_collection = <?php echo json_encode($days_collection) ?>;
         var days_expense = <?php echo json_encode($days_expense) ?>;

         var areaChartData_classAttendence = {
            labels: current_month_days,
            datasets: [{
                  label: "Electronics",
                  fillColor: "rgba(102, 170, 24, 0.6)",
                  strokeColor: "rgba(102, 170, 24, 0.6)",
                  pointColor: "rgba(102, 170, 24, 0.6)",
                  pointStrokeColor: "#c1c7d1",
                  pointHighlightFill: "#fff",
                  pointHighlightStroke: "rgba(220,220,220,1)",
                  data: days_collection
               },
               {
                  label: "Digital Goods",
                  fillColor: "rgba(233, 30, 99, 0.9)",
                  strokeColor: "rgba(233, 30, 99, 0.9)",
                  pointColor: "rgba(233, 30, 99, 0.9)",
                  pointStrokeColor: "rgba(233, 30, 99, 0.9)",
                  pointHighlightFill: "rgba(233, 30, 99, 0.9)",
                  pointHighlightStroke: "rgba(60,141,188,1)",
                  data: days_expense
               }
            ]
         };
         if (bar_chart) {
            var barChartCanvas = $("#barChart").get(0).getContext("2d");
            var barChart = new Chart(barChartCanvas);

            var barChartData = areaChartData_classAttendence;
            barChartData.datasets[1].fillColor = "rgba(233, 30, 99, 0.9)";
            barChartData.datasets[1].strokeColor = "rgba(233, 30, 99, 0.9)";
            barChartData.datasets[1].pointColor = "rgba(233, 30, 99, 0.9)";
            var barChartOptions = {
               scaleBeginAtZero: true,
               scaleShowGridLines: true,
               scaleGridLineColor: "rgba(0,0,0,.05)",
               scaleGridLineWidth: 1,
               scaleShowHorizontalLines: false,
               scaleShowVerticalLines: false,
               barShowStroke: true,
               barStrokeWidth: 2,
               barValueSpacing: 5,
               barDatasetSpacing: 1,
               responsive: true,
               maintainAspectRatio: true
            };
            barChartOptions.datasetFill = false;
            barChart.Bar(barChartData, barChartOptions);
         }
      });

   <?php
   }
   ?>


   $(document).ready(function() {

      $(document).on('click', '.close_notice', function() {
         var data = $(this).data();


         $.ajax({
            type: "POST",
            url: base_url + "admin/notification/read",
            data: {
               'notice': data.noticeid
            },
            dataType: "json",
            success: function(data) {
               if (data.status == "fail") {

                  errorMsg(data.msg);
               } else {
                  successMsg(data.msg);
               }

            }
         });


      });
   });
</script>
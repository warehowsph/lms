<!DOCTYPE html>
<html>

<head>
   <title>Control</title>
   <!-- Latest compiled and minified CSS -->
   <link rel="stylesheet" href="<?php echo $resources . 'boostrap.min.css' ?>">
   <link rel="stylesheet" href="<?php echo $resources . 'bootstrap-theme.min.css' ?>">
   <link rel="stylesheet" href="<?php echo $resources . 'fileinput.css' ?>">
   <link rel="stylesheet" href="<?php echo $resources . 'fileinput.min.css' ?>">
   <link rel="stylesheet" href="<?php echo $resources . 'jquery-ui.css' ?>">
   <link rel="stylesheet" href="<?php echo $resources . 'font-awesome.min.css' ?>">
   <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
   <link rel="stylesheet" href="<?php echo $resources . 'w3.css' ?>">

   <script src="<?php echo $resources . 'jquery.min.js' ?>"></script>
   <script src="<?php echo $resources . 'Chart.min.js' ?>"></script>
   <script src="<?php echo $resources . 'utils.js' ?>"></script>
   <script src="<?php echo $resources . 'chartjs-plugin-datalabels.min.js' ?>"></script>

   <style type="text/css">
      li {
         list-style-type: none;
      }

      .ui-state-default,
      .ui-widget-content .ui-state-default,
      .ui-widget-header .ui-state-default,
      .ui-button,
      html .ui-button.ui-state-disabled:hover,
      html .ui-button.ui-state-disabled:active {
         border: 1px solid #c5c5c5;
         background: white;
         font-weight: normal;
         color: #454545;
      }

      .kv-upload-progress .kv-hidden {
         display: none;
      }

      .sortable {
         background-color: none;
      }

      /*Set the row height to the viewport*/
      .row-height {
         height: 100vh;
      }

      /*Set up the columns with a 100% height, body color and overflow scroll*/

      .left {
         height: 100%;
         overflow-y: scroll;
         padding: 0;
      }

      .right {
         height: 100%;
         overflow-y: scroll;
         padding: 0;
      }

      .mid {
         background-color: green;
         height: 100%;
         overflow-y: scroll;
      }

      /*Remove the scrollbar from Chrome, Safari, Edge and IE*/
      ::-webkit-scrollbar {
         width: 0px;
         background: transparent;
      }

      * {
         -ms-overflow-style: none !important;
      }

      .radio-inline {
         width: 100%;
      }



      /*checkbox*/
      .checkbox label:after,
      .radio label:after {
         content: '';
         display: table;
         clear: both;
      }

      .checkbox .cr,
      .radio .cr {
         position: relative;
         display: inline-block;
         border: 1px solid #a9a9a9;
         border-radius: .25em;
         width: 1.3em;
         height: 1.3em;
         float: left;
         margin-right: .5em;
      }

      .radio .cr {
         border-radius: 50%;
      }

      .checkbox .cr .cr-icon,
      .radio .cr .cr-icon {
         position: absolute;
         font-size: .8em;
         line-height: 0;
         top: 50%;
         left: 20%;
      }

      .radio .cr .cr-icon {
         margin-left: 0.04em;
      }

      .checkbox label input[type="checkbox"],
      .radio label input[type="radio"] {
         display: none;
      }

      .checkbox label input[type="checkbox"]+.cr>.cr-icon,
      .radio label input[type="radio"]+.cr>.cr-icon {
         transform: scale(3) rotateZ(-20deg);
         opacity: 0;
         transition: all .3s ease-in;
      }

      .checkbox label input[type="checkbox"]:checked+.cr>.cr-icon,
      .radio label input[type="radio"]:checked+.cr>.cr-icon {
         transform: scale(1) rotateZ(0deg);
         opacity: 1;
      }

      .checkbox label input[type="checkbox"]:disabled+.cr,
      .radio label input[type="radio"]:disabled+.cr {
         opacity: .5;
      }

      /*checkbox*/

      .sortable {
         padding: 0;
      }

      ::-webkit-scrollbar {
         width: 15px;
      }

      /* Track */
      ::-webkit-scrollbar-track {
         background: #f1f1f1;
      }

      /* Handle */
      ::-webkit-scrollbar-thumb {
         background: #888;
      }

      /* Handle on hover */
      ::-webkit-scrollbar-thumb:hover {
         background: #555;
      }

      canvas {
         margin: 0 auto;
      }

      .question_container {
         border: 2px solid black;
         margin-bottom: 20px;
         page-break-inside: avoid;
      }

      .question_container .radio {
         margin: 0;
         background-color: rgb(100, 100, 100);
         color: white;
      }

      .w3-center {
         color: white;

         background-color: rgb(72, 159, 72);
      }

      .w3-center h4 {
         margin: 0;
         padding: 10px;
      }

      .for_print {
         display: none;
      }

      @media print {
         .left {
            display: none;
         }

         .right,
         .right * {
            visibility: visible;
            display: block;
            width: auto;
            height: auto;
            overflow: visible;
         }

         .for_print {
            display: block;
         }

         .for_display {
            display: none;
         }

         body {
            margin: 10mm 10mm 10mm 10mm;
         }

      }

      .close {
         background-color: red;
         width: 100%;
         color: white;
      }

      .close a {
         text-decoration: none;
      }
   </style>
</head>

<body style="margin: 0">
   <div class="container-fluid">
      <div class="row row-height">
         <div class="col-sm-7 left">
            <!-- <iframe style="height: 100%;width: 100%;" id="optical_pdf" class="embed-responsive-item" src="<?php //echo $resources . 'pdfjs/web/viewer.html?file=' . urlencode(site_url('uploads/lms_assessment/' . $assessment['id'] . '/' . $assessment['assessment_file']) . "&embedded=true"); 
                                                                                                               ?>"></iframe> -->
            <iframe style="height: 100%;width: 100%;" id="optical_pdf" class="embed-responsive-item" src="<?php echo $resources . 'pdfjs/web/viewer.html?file=' . urlencode($_SESSION['S3_BaseUrl'] . 'uploads/lms_assessment/' . $assessment['id'] . '/' . $assessment['assessment_file']) . "&embedded=true"; ?>"></iframe>
            <!-- <iframe style="height: 99.3%;width: 100%;" id="optical_pdf" class="embed-responsive-item" src="https://docs.google.com/gview?url=<?php //echo urlencode($_SESSION['S3_BaseUrl'] . 'uploads/lms_assessment/' . $assessment['id'] . '/' . $assessment['assessment_file']); 
                                                                                                                                                   ?>"></iframe> -->

         </div>

         <div class="col-sm-5 right">
            <table class="table table-bordered table-striped for_display" style="margin:0">
               <tr>
                  <td class="close"><a href="<?php echo site_url('lms/assessment/reports/' . $assessment['id']) ?>">
                        <div class="">Close</div>
                     </a></td>
                  <td><b>Date Created: </b></td>
                  <td><?php echo date("F d, Y", strtotime($data[0]['date_created'])) ?></td>
               </tr>
               <tr>
                  <td style="width: 24%"><b>Title: </b></td>
                  <td><?php echo $assessment['assessment_name'] ?></td>
                  <td><b>Date Created: </b></td>
                  <td><?php echo date("F d, Y", strtotime($data[0]['date_created'])) ?></td>
               </tr>
            </table>


            <div class="w3-container" id="resp-container">
               <?php

               if ($data[0]['answer'] != null) {
                  $respond = json_decode($data[0]['answer']);
                  $i = 0;
                  $section_counter = 0;

                  foreach ($respond as $resp) {
                     $i++;
                     if ($resp->type != "long_answer" && $resp->type != "short_answer" && $resp->type != "section") {

                        print('<div class="w3-panel w3-card-2 question_container">');
                        print('<div class="radio">');
                        printf('<label class="sort_number" style="font-size: 1.5em">%s</label>', $i, $section_counter);
                        print('</div>');
                        printf('<canvas id="myChart_%s_%s" width="600" height="250"></canvas>', $i, $section_counter);
                        printf('<div id="resp_%s_%s" class="w3-center"></div>', $i, $section_counter);
                        print('</div>');
                     } elseif ($resp->type == "section") {
                        print('<div><h1>');
                        print_r($resp->answer);
                        print('</h1></div>');
                        $i = 0;
                        $section_counter++;
                     }
                  }
               } else {
               }
               ?>
            </div>

         </div>
      </div>
   </div>
</body>

</html>

<script type="text/javascript">
   var resp_data;

   async function showResponses() {
      await getResponses();
   }

   $(document).ready(function() {
      showResponses();
   });

   function getResponses() {
      fetch('<?php echo site_url('lms/assessment/get_sheets/' . $assessment['id']) ?>')
         .then((resp) => resp.json())
         .then(function(data) {
            resp_data = data;
            console.log(resp_data);

            var display = "";
            var chart_ctr = 0;
            var section = 0;

            //-- Show charts
            $.each(data, function() {
               chart_ctr++;
               console.log(data[chart_ctr - 1].type);
               if (data[chart_ctr - 1].type != "section") {
                  // console.log("beri gud");
                  if (data[chart_ctr - 1].answer_choices != '') {
                     $('#resp_' + chart_ctr).html('<h4>RESPONDENTS: ' + data[chart_ctr - 1].respondents + '</h4>')

                     var config = {
                        type: 'bar',
                        data: {
                           datasets: [{
                              label: 'Question ' + chart_ctr,
                              data: data[chart_ctr - 1].answers_count.map(Number),
                              fill: false,
                              backgroundColor: [window.chartColors.green,
                                 window.chartColors.blue,
                                 window.chartColors.red,
                                 window.chartColors.orange,
                                 window.chartColors.yellow,
                                 window.chartColors.purple,
                                 window.chartColors.grey
                              ]
                           }],
                           labels: data[chart_ctr - 1].answer_choices
                        },
                        options: {
                           scales: {
                              yAxes: [{
                                 ticks: {
                                    precision: 0
                                 }
                              }]
                           },
                           responsive: true,
                           maintainAspectRatio: true,
                           layout: {
                              padding: {
                                 left: 0,
                                 right: 0,
                                 top: 0,
                                 bottom: 15
                              }
                           },
                           plugins: {
                              datalabels: {
                                 anchor: 'end',
                                 backgroundColor: function(context) {
                                    return context.dataset.backgroundColor;
                                 },
                                 borderColor: 'white',
                                 borderRadius: 25,
                                 borderWidth: 2,
                                 color: 'white',
                                 font: {
                                    weight: 'bold'
                                 },
                                 formatter: (value, ctx) => {
                                    let sum = 0;
                                    let dataArr = ctx.chart.data.datasets[0].data;
                                    dataArr.map(data => {
                                       sum += data;
                                    });
                                    let percentage = (value * 100 / sum).toFixed(1) + "%";
                                    return percentage;
                                 },
                              }
                           }
                        }
                     };
                     var the_id = (chart_ctr) + "_" + (section);
                     console.log(the_id);
                     var can_id = "canvas" + the_id;
                     var ctx = $('#myChart_' + the_id);
                     window.can_id = new Chart(ctx, config);
                  }
               } else {
                  chart_ctr = 1;
                  section++;
               }




            });
         })
         .catch(function(error) {
            // This is where you run code if the server returns any errors
         });

      $("#resp-container").clone();
   }
</script>
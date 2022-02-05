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
   <link rel="stylesheet" href="<?php echo $resources . 'answer.css' ?>">
   <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<style type="text/css">
   .sortable {
      top: 0;
   }
</style>

<body>

   <div class="container-fluid">
      <div class="row row-height">
         <div class="col-sm-7 left">

            <form enctype="multipart/form-data" id="upload_form" method="POST" action="<?php echo site_url('lms/assessment/upload/' . $assessment['id']); ?>" style="top: 0;position: absolute; width: 100%;">
               <!-- <input type="file" required="" class="form-control file" accept="application/pdf" name="assessment_form"> -->
               <!-- <input type="button" value="Upload" class="form-control btn btn-success upload"> -->
               <input type="button" value="" class="form-control btn">
            </form>
            <?php if ($assessment['assessment_file']) : ?>
               <?php if ($mode == "offline") :
               ?>
                  <iframe style="height: 100%;width: 100%;" id="optical_pdf" class="embed-responsive-item" src="<?php echo $old_resources . 'pdfjs/web/viewer.html?file=' . urlencode(old_url('uploads/lms_assessment/' . $assessment['id'] . '/' . $assessment['assessment_file'])); ?>"></iframe>
               <?php else :
               ?>
                  <?php if ($_SERVER['HTTP_HOST'] == "www.stepsmandaluyong.com") : ?>
                     <iframe style="height: 100%;width: 100%;" id="optical_pdf" class="embed-responsive-item" src="<?php echo $old_resources . 'pdfjs/web/viewer.html?file=' . urlencode($s3bucketurl . 'uploads/lms_assessment/' . $assessment['id'] . '/' . $assessment['assessment_file']) . "&embedded=true"; ?>"></iframe>
                  <?php else : ?>
                     <iframe style="height: 100%;width: 100%;" id="optical_pdf" class="embed-responsive-item" src="<?php echo $old_resources . 'pdfjs/web/viewer.html?file=' . urlencode($s3bucketurl . 'uploads/lms_assessment/' . $assessment['id'] . '/' . $assessment['assessment_file']) . "&embedded=true"; ?>"></iframe>
                  <?php endif; ?>
               <?php endif ?>
            <?php else : ?>
               <h1 style="text-align: center;">Upload a PDF File Here</h1>
            <?php endif; ?>
         </div>

         <div class="col-sm-5 right">
            <table class="table table-bordered">
               <tr>

                  <td colspan="4" style="padding: 0;cursor: pointer;">
                     <a href="<?php echo old_url('lms/assessment/index/'); ?>">
                        <div class="info-tab info-title the_close">Close</div>
                     </a>
                  </td>

               </tr>
               <tr>
                  <td>Name :</td>
                  <td><?php echo $student_name; ?></td>
                  <td>Date</td>
                  <td><?php echo date("F d, Y"); ?></td>
               </tr>
               <tr>
                  <td>Title :</td>
                  <td><?php echo $assessment['assessment_name'] ?></td>
                  <td>Timer :</td>
                  <td><span id="time" class="time"></span> </td>
               </tr>
               <!-- <tr>
		        			<td>Score :</td>
		        			<td class="score"></td>
		        			<td>Timer</td>
		        			<td class="timer"></td>
		        		</tr> -->
            </table>
            <div class="info-row">
               <div class="info-tab col-sm-12 save submit">
                  <center>Submit</center>
               </div>
            </div>
            <!-- <div class="info col-sm-5">



		        	</div> -->
            <div class="clearfix"></div>
            <ul class="sortable ui-sortable">
               <li class="option-container option-container-clonable">
                  <div class="numbering_option"></div>
                  <!-- <div class="copy_last" style="display: inline;">
		        					<button class="btn btn-success">Duplicate</button>
		        				</div> -->
                  <!-- <div class="copy_bottom" style="display: inline;">
		        					<button class="btn btn-warning">Duplicate To No. 2</button>
		        				</div> -->

                  <!-- <div class="remove_option float-right">X</div> -->
                  <div class="option">
                     <div class="option_type">
                        <input type="radio" name="" class="form-control">
                     </div>
                     <div class="option_label_container">
                        <div class="option_label"></div>
                        <div class="option_label_input">
                           <input type="text" name="" value="A" class="form-control">
                        </div>
                        <!-- <div class="remove_choice"><button>X</button></div> -->
                     </div>

                  </div>

                  <div class="add_option">
                     <div class="option_type">

                     </div>
                     <div class="option_label_container">

                        <div class="">
                           <center>
                              <!-- <input type="button" name="" class="form-control btn btn-success" style="margin-top: 10px;" value="Add Option"> -->
                           </center>

                        </div>
                     </div>

                  </div>
               </li>

            </ul>

         </div>
      </div>
   </div>

   <input type="hidden" id="url" value="<?php echo site_url('lms/assessment/update'); ?>" name="" />
   <input type="hidden" id="site_url" value="<?php echo site_url('lms/assessment/'); ?>" name="" />
   <input type="hidden" id="base_url" value="<?php echo site_url('lms/assessment/'); ?>" name="" />
   <input type="hidden" id="old_url" value="<?php echo old_url('lms/assessment/'); ?>" name="" />
   <input type="hidden" id="assessment_id" value="<?php echo $assessment['id'] ?>" name="" />
   <input type="hidden" id="assessment_sheet_id" value="<?php echo $assessment_sheet['id'] ?>" name="" />
   <input type="hidden" id="enable_timer" value="<?php echo $assessment['enable_timer'] ?>" name="" />
   <input type="hidden" id="account_id" value="<?php echo $account_id ?>" name="" />
   <input type="hidden" id="expiration_value" value="<?php echo $assessment_sheet['expiration'] ?>" name="" />
   <input type="hidden" id="time_now" value="<?php echo time(); ?>" name="" />
</body>

</html>
<script type="text/javascript" src="<?php echo $resources . 'jquery-1.12.4.js' ?>"></script>
<script type="text/javascript" src="<?php echo $resources . 'jquery-ui.js' ?>"></script>
<script type="text/javascript" src="https://nosir.github.io/cleave.js/dist/cleave.min.js"></script>
<script type="text/javascript" src="https://nosir.github.io/cleave.js/dist/cleave-phone.i18n.js"></script>
<script type="text/javascript" src="<?php echo $resources . 'answer_12.js' ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
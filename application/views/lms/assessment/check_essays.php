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
   <link rel="stylesheet" href="<?php echo $resources . 'assessment.css' ?>">
   <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" />



   <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

   <style type="text/css">
      .jstree-themeicon-custom {
         background-size: 100% !important;
      }

      .info-tab {
         /*border-top: 1px solid white;*/
         border-bottom: 1px solid white;
      }

      .save_status {
         color: white;
         text-align: center;
         background-color: black;
      }

      .sortable {
         position: relative;
         padding: 0;
         top: 0px;
         background-color: none;
         z-index: 1;
      }

      .student_name_container {
         padding: 12px;
         text-align: center;
         cursor: pointer;
         border-top: 1px solid gray;
      }

      .student_name_container:hover {
         background-color: rgb(103 255 122);
         color: #575757;
      }

      .has_answer {
         background-color: #cee6ff;
      }

      .has_answer:hover {
         background-color: rgb(103 255 122);
      }

      .active_students {
         background-color: green !important;
      }

      .display_name {
         background-color: rgb(103 255 122);
         padding: 15px;
      }
   </style>
</head>

<body>

   <div class="container-fluid">
      <div class="row row-height">
         <!-- <div class = "col-sm-5 ben_left">
		        	<form enctype="multipart/form-data" id="upload_form" method="POST" action="<?php echo site_url('lms/assessment/upload/' . $assessment['id']); ?>" style="top: 0;position: absolute; width: 100%;">
		        		<input type="file" required="" class="form-control file" accept="application/pdf" name="assessment_form">
		        		<input type="button" value="Upload" class="form-control btn btn-success upload">
		        	</form>
		        	<?php if ($assessment['assessment_file']) : ?>
	            		<iframe style="height: 100%;width: 100%;" id="optical_pdf" class="embed-responsive-item" src="<?php echo $resources . 'pdfjs/web/viewer.html?file=' . urlencode(site_url('uploads/lms_assessment/' . $assessment['id'] . '/' . $assessment['assessment_file'])); ?>"></iframe>
	            	<?php else : ?>
	            		<h1 style="text-align: center;">Upload a PDF File Here</h1>
		            <?php endif; ?>
		        	
		        </div> -->
         <div class="col-sm-4 ben_left">
            <?php foreach ($students as $students_key => $students_value) : ?>
               <!-- <pre> -->
               <?php //print_r($students_value) 
               ?>
               <div class="student_name_container <?php if ($students_value['has_answered']) {
                                                      echo 'has_answer';
                                                   } ?>" student_name="<?php echo ucfirst((strtolower($students_value['lastname']))) ?>, <?php echo ucfirst((strtolower($students_value['firstname']))) ?>" account_id="<?php echo $students_value['id'] ?>">
                  <p><?php echo ucfirst((strtolower($students_value['lastname']))) ?>, <?php echo ucfirst((strtolower($students_value['firstname']))) ?></p>
               </div>

            <?php endforeach; ?>

            <div class=""><a href="<?php echo base_url('lms/assessment/recheck_answers/' . $id) ?>"><button class="btn btn-danger form-control">Save & Close</button></a></div>
         </div>

         <div class="col-sm-8 right">
            <div class="display_name">Student Name: <span class="student_name"></span></div>
            <ul class="sortable ui-sortable">
               <li class="option-container option-container-clonable">
                  <div class="numbering_option"></div>
                  <label class="score_class">Score: </label> <input type="number" min="1" class="points score_class" value="1" />
                  <!-- <div class="copy_last" style="display: inline;">
		        					<button class="btn btn-success">Duplicate</button>
		        				</div> -->
                  <!-- <div class="copy_bottom" style="display: inline;">
		        					<button class="btn btn-warning">Duplicate To No. 2</button>
		        				</div> -->

                  <div class="remove_option float-right">X</div>
                  <div class="option">
                     <div class="option_type">
                        <input type="radio" name="" class="form-control">
                     </div>
                     <div class="option_label_container">
                        <div class="option_label"></div>
                        <div class="option_label_input">
                           <input type="text" name="" value="A" class="form-control">
                        </div>
                        <div class="remove_choice"><button>X</button></div>
                     </div>

                  </div>

                  <div class="add_option">
                     <div class="option_type">

                     </div>
                     <div class="option_label_container">

                        <div class="">
                           <center>
                              <input type="button" name="" class="form-control btn btn-success" style="margin-top: 10px;" value="Add Option">
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
   <input type="hidden" id="base_url" value="<?php echo base_url(); ?>" name="" />
   <input type="hidden" id="stored_json" value='<?php echo $assessment['sheet']; ?>' name="" />
   <input type="hidden" id="assessment_id" value="<?php echo $assessment['id'] ?>" name="" />
   <input type="hidden" id="assigned" value="<?php echo $assessment['assigned'] ?>" name="" />
   <input type="hidden" id="answers" value='<?php echo $answers ?>' name="" />

</body>

</html>
<script type="text/javascript" src="<?php echo $resources . 'jquery-1.12.4.js' ?>"></script>
<script type="text/javascript" src="<?php echo $resources . 'jquery-ui.js' ?>"></script>

<script type="text/javascript" src="https://nosir.github.io/cleave.js/dist/cleave.min.js"></script>
<script type="text/javascript" src="https://nosir.github.io/cleave.js/dist/cleave-phone.i18n.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="<?php echo $resources . 'check_essays_5.js' ?>"></script>
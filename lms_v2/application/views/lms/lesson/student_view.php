<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">

   <title>LMS - <?php echo $lesson['lesson_name'] ?></title>
   <link rel="stylesheet" href="<?php echo $resources . 'lesson_3.css' ?>">
   <link rel="stylesheet" href="<?php echo $resources . 'jquery-ui.css' ?>">
   <link rel="stylesheet" href="<?php echo $resources . 'jquery.magnify.css' ?>">
   <link rel="stylesheet" href="<?php echo $resources . 'font-awesome.min.css' ?>">
   <link rel="stylesheet" href="<?php echo $resources . 'fontawesome/css/all.css' ?>">
   <!-- Latest compiled and minified CSS -->
   <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->

   <!-- Optional theme -->
   <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous"> -->

   <!-- Latest compiled and minified JavaScript -->
   <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script> -->
   <!-- <link href="https://vjs.zencdn.net/7.7.5/video-js.css" rel="stylesheet" /> -->
   <!-- <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script> -->

</head>

<body>


   <div class="student_view">
      <style type="text/css">
         .student_view_container {
            width: 100%;
         }

         .button_navigation {
            width: 10%;
         }

         .student_view_title {
            width: 30%;
         }

         .green {
            background-color: rgb(0 175 30);
         }

         .orange {
            background-color: rgb(195 128 5);
         }

         .red {
            background-color: rgb(220 0 0);
         }

         .navigation_tools {
            width: 10%;
            max-height: 20px;
         }

         .student_view_slides {
            width: 100%;
            top: 28px;
         }

         .slide {
            width: 10%;
         }

         .slide_active {
            height: 100px;
         }

         .blue {
            background-color: rgb(46, 117, 182);
         }

         .student_view_title {
            width: 70%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-indent: 10px;
         }

         .close_student_view {
            width: 10%;
         }
      </style>
      <div class="student_view_container">

         <div class="student_view_navigation">
            <div class="student_view_buttons button_navigation blue previous"><i class="fas fa-chevron-left"></i> Back</div>
            <div class="student_view_buttons student_view_title">Title</div>
            <div class="student_view_buttons button_navigation blue next">Next <i class="fas fa-chevron-right"></i></div>

            <?php if ($role == "admin") : ?>
               <div class="student_view_buttons navigation_tools green teacher_tools_button"><i class="fas fa-tools"></i> Teacher Tools</div>
               <div class="student_view_buttons navigation_tools orange formula_board_button"><i class="fas fa-square-root-alt"></i> Formula Board</div>
               <div class="student_view_buttons navigation_tools white annotate_button"><i class="fas fa-pen"></i> Annotate</div>
               <!-- <div class="student_view_buttons navigation_tools blue teacher_tools_button"><i class="fas fa-wrench"></i> Discussion</div> -->
            <?php endif; ?>
            <!-- <div class="student_view_buttons navigation_tools orange discussion_board_button"><i class="fas fa-comments"></i> Discussion</div> -->
            <div class="student_view_buttons button_navigation red close_student_view"><i class="fas fa-times-circle"></i> Close</div>


         </div>
         <div class="student_view_slides" id="student_view_slides">
            <div class="slide slide_clone" style="display: none;">
               <div class="dimmer"></div>
               <img src="https://upload.wikimedia.org/wikipedia/commons/d/da/Panthera_tigris_tigris_Tidoba_20150306.jpg">
            </div>

         </div>
         <style type="text/css">
            .html_content {
               background-color: rgb(210, 206, 206);
               display: block;
               height: 100%;
               width: 100%;
               padding: 50px;
            }
         </style>
         <div class="student_view_content">

            <iframe class="content_type student_view_content_iframe" src="https://www.youtube.com/embed/" frameborder="0"></iframe>
            <img class="content_type image_content" src="" data-magnify="gallery" data-caption="Image Caption 1" data-src="1.jpg" />
            <div class="content_type html_content" style="background-color: white;"></div>
            <video src="" class="video_content" width="100%" controls controlsList="nodownload"></video>
         </div>


      </div>


   </div>



   <input type="hidden" id="url" value="<?php echo site_url('lms/lesson/'); ?>" name="">
   <input type="hidden" id="lesson_id" value="<?php echo $id; ?>" name="">






   <script src="<?php echo $resources . 'jquery-1.12.4.js' ?>"></script>
   <script src="<?php echo $resources . 'jquery.magnify.js' ?>"></script>
   <script src="<?php echo $resources . 'jquery-ui.js' ?>"></script>
   <script src="<?php echo $resources . 'jquery.mousewheel.min.js' ?>"></script>

   <!-- <script src="https://vjs.zencdn.net/7.7.5/video.js"></script> -->
   <script src="<?php echo $resources . 'lesson_student_view.js' ?>"></script>

</body>

</html>
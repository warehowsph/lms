<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>LMS Lesson Creator</title>
   <link rel="stylesheet" href="<?php echo $resources . 'jquery-ui.css' ?>">
   <link rel="stylesheet" href="<?php echo $resources . 'lesson.css' ?>">
   <link rel="stylesheet" href="<?php echo $resources . 'jquery.magnify.css' ?>">
   <link rel="stylesheet" href="<?php echo $resources . 'font-awesome.min.css' ?>">
   <link rel="stylesheet" href="<?php echo $resources . 'fontawesome/css/all.css' ?>">
   <link href="https://vjs.zencdn.net/7.7.5/video-js.css" rel="stylesheet" />

   <!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
   <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
   <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
   <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" />

   <style type="text/css">
      .ql-snow {
         background-color: white;
      }

      #learing_plan_text {
         color: black;
      }

      #objective_text {
         color: black;
      }

      .jstree-themeicon-custom {
         background-size: 100% !important;
      }

      .select-box {
         cursor: pointer;
         position: relative;
         max-width: 20em;
         width: 100%;
      }

      .select,
      .label {
         color: #414141;
         display: block;
         font: 400 17px/2em 'Source Sans Pro', sans-serif;
      }

      .select {
         width: 100%;
         position: absolute;
         top: 0;
         padding: 5px 0;
         height: 40px;
         opacity: 0;
         -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
         background: none transparent;
         border: 0 none;
      }

      .select-box1 {
         background: #ececec;
      }

      .label {
         position: relative;
         padding: 5px 10px;
         cursor: pointer;
      }

      .open .label::after {
         content: "▲";
      }

      .label::after {
         content: "▼";
         font-size: 12px;
         position: absolute;
         right: 0;
         top: 0;
         padding: 5px 15px;
         border-left: 5px solid #fff;
      }

      .no_background {
         background-color: none;
      }
   </style>
</head>

<body>
   <div style="position: relative;height: 100%;width: 100%;background-color: white;z-index: 9999" class="loader">
      <img style="position: absolute;top: 0%;left: 50%;margin: 13% 0px 0px -10%;" src="<?php echo $resources . 'images/loader.gif' ?>">
   </div>
   <input type="hidden" id="site_url" value="<?php echo site_url('lms/lesson/update'); ?>" name="">
   <input type="hidden" id="url" value="<?php echo site_url('lms/lesson/'); ?>" name="">
   <input type="hidden" id="lesson_id" value="<?php echo $id; ?>" name="">
   <input type="hidden" id="main_url" value="<?php echo site_url(); ?>" name="">
   <input type="hidden" id="assigned" value="<?php echo $lesson['assigned']; ?>" name="">
   <input type="hidden" id="role" value="<?php echo $role ?>" name="" />

   <div id="myModal" class="modal">

      <!-- Modal content -->
      <div class="modal-content">

         <h3 style="color: white">Add Text</h3>
         <input type="text" id="text_title" name="" style="padding: 10px; width: 100%;" placeholder="Text Title">
         <div id="view_text">

         </div>
         <div id="add_text">

         </div>
         <button class="add_text_done add_text_close">Done</button>
         <button class="add_text_close">Close</button>
      </div>

   </div>

   <div class="edit_area">

      <div class="part ben_left" style="width: 100%">
         <div class="navigation">

            <div class="title_container">
               <input type="text" class="title" placeholder="Lesson Name Here..." value="<?php echo $lesson['lesson_name'] ?>" name="">

            </div>
            <div class="folders_container">

               <div class="folder folder_active" style="width: 33.33%">
                  <!-- <input type="text" placeholder="Engage" value="Engage" name=""> -->
                  <span>Introduction</span>
               </div>
               <div class="folder" style="width: 33.33%">
                  <!-- <input type="text" placeholder="Explore" value="Explore" name=""> -->
                  <span>Lesson Proper</span>
               </div>
               <div class="folder" style="width: 33.33%">
                  <!-- <input type="text" placeholder="Explain" value="Explain" name=""> -->
                  <span>Formative Assessment</span>
               </div>

               <!-- <?php //if($lesson['education_level']=='tertiary'): 
                     ?>
                           <div class="folder folder_active" style="width: 33.33%">
                                 <span>Introduction</span>
                           </div>
                           <div class="folder" style="width: 33.33%">
                                 <span>Lesson Proper</span>
                           </div>
                           <div class="folder" style="width: 33.33%">
                                 <span>Examination</span>
                           </div>
                           
                           <?php //else: 
                           ?>
                           <div class="folder folder_active">
                              <span>Engage</span>
                           </div>
                           <div class="folder">
                                 <span>Explore</span>
                           </div>
                           <div class="folder">
                                 <span>Explain</span>
                           </div>
                           <div class="folder">
                                 <span>Extend</span>
                           </div>
                           <div class="folder">
                                 <span>Evaluation</span>
                           </div>
                        <?php //endif; 
                        ?> -->

            </div>

         </div>

         <div class="folder_container">

            <ul id="folder_1" class="folder_contents connectedSortable">

            </ul>
         </div>
         <div class="folder_container">
            <ul id="folder_2" class="folder_contents connectedSortable">

            </ul>
         </div>
         <div class="folder_container">
            <ul id="folder_3" class="folder_contents connectedSortable">

            </ul>
         </div>
         <div class="folder_container">
            <ul id="folder_4" class="folder_contents connectedSortable">

            </ul>
         </div>
         <div class="folder_container">
            <ul id="folder_5" class="folder_contents connectedSortable">

            </ul>
         </div>
         <div id="" class="slider close learning_plan_slider">
            <h2>Learning Plan</h2>
            <div class="slider_container">
               <div id="learning_plan_text">
                  <table border="1">
                     <tr>
                        <th>Topic: Learning Competencies</th>
                        <th>Virtual Session Schedule</th>
                        <th>Campus LMS Resources</th>
                        <th>Learning Experiences</th>
                     </tr>
                  </table>
               </div>
            </div>
         </div>
         <!-- <div id="" class="slider close objective_slider">
                  <div class="slider_container">
                        <h2>Objective</h2>
                        <div id="objective_text">
                        
                        
                        </div>
                  </div>
               </div> -->
         <div id="" class="slider close assign_slider" style="background-color: rgb(84, 130, 53);">
            <div class="slider_container">
               <div class="col-lg-6">
                  <h2>Assign to Students</h2>
                  <div id="jstree_demo_div">
                     <ul>
                        <li class="jstree-open" data-jstree='{
                                    "icon":"https://upload.wikimedia.org/wikipedia/commons/thumb/4/40/Round_Landmark_School_Icon_-_Transparent.svg/1200px-Round_Landmark_School_Icon_-_Transparent.svg.png"
                                 }'>All
                           <ul>
                              <?php foreach ($classes as $classes_key => $classes_value) : ?>
                                 <li data-jstree='{"icon":"https://img.icons8.com/bubbles/2x/classroom.png"}'><?php echo $classes_value['class'] ?>
                                    <ul>
                                       <?php foreach ($class_sections as $class_sections_key => $class_sections_value) : ?>
                                          <?php if ($class_sections_value['class_id'] == $classes_value['id']) : ?>
                                             <li id="section_<?php echo $class_sections_value['class_id'] ?>_<?php echo $class_sections_value['section_id'] ?>" data-jstree='{"icon":"https://img.icons8.com/clouds/2x/child-safe-zone.png"}'><?php echo $class_sections_value['section'] ?>
                                                <ul>
                                                   <?php foreach ($students as $students_key => $students_value) : ?>
                                                      <?php if ($students_value['class_id'] == $class_sections_value['class_id'] && $students_value['section_id'] == $class_sections_value['section_id']) : ?>

                                                         <li data-jstree='{"icon":"https://cdn.clipart.email/08211c36d197d37bb0d0761bbfeb8efd_square-academic-cap-graduation-ceremony-clip-art-graduation-hat-_1008-690.png"}' class="student" id="student_<?php echo $students_value['id'] ?>"><?php echo $students_value['firstname'] ?> <?php echo $students_value['lastname'] ?></li>
                                                      <?php endif; ?>
                                                   <?php endforeach; ?>
                                                </ul>
                                             </li>
                                          <?php endif; ?>
                                       <?php endforeach; ?>
                                    </ul>
                                 </li>
                              <?php endforeach; ?>
                           </ul>
                     </ul>
                     </li>
                  </div>
               </div>
               <div class="col-lg-6">

                  <h3>Assign Date</h3>
                  <?php if ($lesson['start_date'] == '0000-00-00 00:00:00') : ?>
                     <?php $lesson['start_date'] = ""; ?>
                     <?php $lesson['end_date'] = ""; ?>
                  <?php endif ?>
                  <input type="hidden" name="" value="<?php echo $lesson['start_date'] ?>" class="start_date">
                  <input type="hidden" name="" value="<?php echo $lesson['end_date'] ?>" class="end_date">
                  <input type="text" value="" class="form-control date_range" name="" style="width: 80%;padding: 10px;">
                  <h3>Lesson Type</h3>
                  <div class="select-box">

                     <label for="lesson_type" class="label select-box1"><span class="label-desc">Lesson Type</span> </label>
                     <select id="lesson_type" class="select">
                        <option <?php if ($lesson['lesson_type'] == "classroom") {
                                    echo "selected=''";
                                 } ?> value="classroom">Classroom Use</option>
                        <option <?php if ($lesson['lesson_type'] == "reviewer") {
                                    echo "selected=''";
                                 } ?> value="reviewer">Reviewer</option>
                        <option <?php if ($lesson['lesson_type'] == "assignment") {
                                    echo "selected=''";
                                 } ?> value="assignment">Assignment</option>
                        <option <?php if ($lesson['lesson_type'] == "virtual") {
                                    echo "selected=''";
                                 } ?> value="virtual">Virtual Class</option>
                     </select>

                  </div>

                  <div class="notification_control">
                     <h3>Notification</h3>

                     <!-- <div class="pretty p-switch p-fill">
                                 <input type="checkbox" />
                                 <div class="state p-primary">
                                    <label>SMS Notification</label>
                                 </div>
                              </div> -->

                     <div class="pretty p-switch p-fill">

                        <input type="checkbox" id="email_notification" <?php if ($lesson['email_notification'] == "1") {
                                                                           echo "checked";
                                                                        } ?> />
                        <div class="state p-primary">
                           <label>Email Notification</label>
                        </div>
                     </div>

                  </div>



                  <h3>Save</h3>

                  <button class="assign_save" style="padding: 10px;width: 50%;border-radius: 10px;border: 0px;cursor: pointer;">Assign</button>
               </div>




            </div>
         </div>

         <div id="" class="slider close discussion_slider">

            <div class="slider_container">

               <h2>Discussion Board</h2>
               <div class="dicussion_container teacher_chat_container">



               </div>
               <div class="chat_discussion" style="position: relative;">
                  <textarea class="chat_text" style="bottom: 0;"></textarea>
                  <button class="chat_submit" onclick="send_chat()">Send</button>
               </div>
            </div>
         </div>
         <div id="" class="slider close settings_slider">
            <div class="slider_container">
               <h2>Settings</h2>
               <div class="col-lg-6">
                  <h3>Subject</h3>
                  <div class="select-box">
                     <label for="subject" class="label select-box1"><span class="label-desc">Subject</span> </label>
                     <select id="subject" class="select">
                        <?php foreach ($subjects as $key => $value) : ?>
                           <option <?php if ($value['id'] == $lesson['subject_id']) {
                                       echo "selected=''";
                                    } ?> value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                        <?php endforeach; ?>
                     </select>

                  </div>

                  <h3>Grade</h3>
                  <div class="select-box">

                     <label for="grade" class="label select-box1"><span class="label-desc">Grade</span> </label>
                     <select id="grade" class="select">
                        <?php foreach ($classes as $key => $value) : ?>
                           <option <?php if ($value['id'] == $lesson['grade_id']) {
                                       echo "selected=''";
                                    } ?> value="<?php echo $value['id'] ?>"><?php echo $value['class'] ?></option>
                        <?php endforeach; ?>
                     </select>

                  </div>

                  <h3>Education Level</h3>
                  <div class="select-box">

                     <label for="education_level" class="label select-box1"><span class="label-desc">Education Level</span> </label>
                     <select id="education_level" class="select">
                        <option <?php if ($lesson['education_level'] == "grade_school") {
                                    echo "selected=''";
                                 } ?> value="grade_school">Grade School</option>
                        <option <?php if ($lesson['education_level'] == "junior") {
                                    echo "selected=''";
                                 } ?> value="junior">Junior Highschool</option>
                        <option <?php if ($lesson['education_level'] == "senior") {
                                    echo "selected=''";
                                 } ?> value="senior">Senior Highschool</option>
                        <option <?php if ($lesson['education_level'] == "tertiary") {
                                    echo "selected=''";
                                 } ?> value="tertiary">Tertiary</option>
                     </select>

                  </div>

               </div>
               <div class="col-lg-6">
                  <h3>Term</h3>
                  <div class="select-box">

                     <label for="term" class="label select-box1"><span class="label-desc">Term</span> </label>
                     <select id="term" class="select">
                        <option <?php if ($lesson['term'] == "1") {
                                    echo "selected=''";
                                 } ?> value="1">1st Term</option>
                        <option <?php if ($lesson['term'] == "2") {
                                    echo "selected=''";
                                 } ?> value="2">2nd Term</option>
                        <option <?php if ($lesson['term'] == "3") {
                                    echo "selected=''";
                                 } ?> value="3">3rd Term</option>
                        <option <?php if ($lesson['term'] == "4") {
                                    echo "selected=''";
                                 } ?> value="4">4th Term</option>
                     </select>

                  </div>
                  <h3>Shared</h3>
                  <div class="select-box">

                     <label for="shared" class="label select-box1"><span class="label-desc">Share</span> </label>
                     <select id="shared" class="select">
                        <option <?php if ($lesson['shared'] == "1") {
                                    echo "selected=''";
                                 } ?> value="1">Yes</option>
                        <option <?php if ($lesson['shared'] == "0") {
                                    echo "selected=''";
                                 } ?> value="0">No</option>
                     </select>

                  </div>
               </div>

            </div>
         </div>


         <ul id="result_container" class="connectedSortable">

            <li class="ui-state-default content search_content content_hidden" result_id="">
               <!-- <div class="flip-card">
                  <div class="flip-card-inner">
                  <div class="flip-card-front">

                     <div class="content_header theme">
                        <span>Default</span>
                        <img class="content_close" src="<?php //echo $resources . 'images/close.png' 
                                                         ?>">
                     </div>
                     <div class="content_body">
                        <div class="download_status_container">
                        <span>Ready</span>
                        </div>
                        <img src="<?php //echo $resources . 'images/website.png' 
                                    ?>">

                     </div>

                     <div class="content_footer theme">
                        <textarea>Default Description</textarea>
                     </div>

                  </div>
                  <div class="flip-card-back">
                     <h1>John Doe</h1>
                     <p>Architect & Engineer</p>
                     <p>We love that guy</p>
                  </div>
                  </div>
               </div> -->

               <div class="content_header theme">
                  <span>Default</span>
                  <img class="content_close" src="<?php echo $resources . 'images/close.png' ?>">
               </div>
               <div class="content_body">
                  <div class="download_status_container">
                     <span>Ready</span>
                  </div>
                  <img src="<?php echo $resources . 'images/website.png' ?>">

               </div>

               <div class="content_footer theme">
                  <textarea>Default Description</textarea>
               </div>
            </li>
         </ul>


         <div class="footer" style="width: 100%">

            <div class="actions_container">
               <div class="actions">
                  <a href="<?php echo site_url('lms/lesson/shared'); ?>">
                     <button class="action_button close_action"><i class="fas fa-times-circle"></i>Close</button>
                  </a>
               </div>
               <div class="actions" style="display: none">
                  <button id="learning_plan" class="trigger action_button"><i class="fab fa-leanpub"></i>Learning Plan</button>
               </div>
               <!-- <div class="actions">
                           <button id="objective" class="trigger action_button"><i class="fas fa-bullseye"></i>Objective</button>
                        </div> -->
               <div class="actions">
                  <button id="slideshow" class="action_button slideshow_action"><i class="fas fa-video"></i>Slideshow</button>
               </div>
               <div class="actions" style="display: none">
                  <button id="assign" class="trigger action_button assign_action"><i class="fas fa-chalkboard-teacher"></i>Assign</button>
               </div>
               <div class="actions" style="display: none">
                  <button id="discussion" class="trigger action_button"><i class="fas fa-school"></i>Discussion</button>
               </div>
               <div class="actions" style="display: none">
                  <button id="settings" class="trigger action_button"><i class="fas fa-cogs"></i>Settings</button>
               </div>
            </div>

         </div>
         <div class="result_actions">
            <form class="upload_form" method="post" enctype="multipart/form-data">
               <input type="file" class="upload_input hidden" name="upload_file[]" multiple="">
            </form>
            <div class="upload_actions actions_container">

               <div class="actions">
                  <button class="action_button my_upload_button upload_color"><i class="fas fa-upload"></i>Upload</button>
               </div>
            </div>
            <div class="upload_actions actions_container">
               <div class="actions">
                  <button class="action_button text_color" id="myBtn"><i class="fas fa-file-alt"></i>Add Text</button>
               </div>
            </div>
         </div>
      </div>


      <div class="part extremeright" style="width: 0%">
         <div class="extremeright_filler">

         </div>
         <div class="search_container">

         </div>
         <div class="extremeright_icon icon_active" portal="youtube">
            <center>
               <img src="<?php echo $resources . 'images/youtube.png' ?>">
            </center>
         </div>

         <div class="extremeright_icon" portal="google_image">
            <center>
               <img src="<?php echo $resources . 'images/google-photos.svg' ?>">
            </center>
         </div>

         <div class="extremeright_icon" portal="google">
            <center>
               <img src="<?php echo $resources . 'images/google.svg' ?>">
            </center>
         </div>

         <div class="extremeright_icon" portal="my_resources">
            <center>
               <img src="<?php echo $resources . 'images/mycms.png' ?>">
            </center>
         </div>
         <div class="extremeright_icon" portal="cms_resources">
            <center>
               <img src="<?php echo $resources . 'images/cms.png' ?>">
            </center>
         </div>
      </div>

   </div>


   <div class="student_view student_view_close">
      <div class="student_view_container">
         <div class="student_view_navigation">
            <div class="student_view_buttons button_navigation blue previous"><i class="fas fa-chevron-left"></i> Previous</div>
            <div class="student_view_buttons student_view_title">Title</div>
            <div class="student_view_buttons button_navigation blue next">Next <i class="fas fa-chevron-right"></i></div>


         </div>
         <div class="student_view_slides" id="student_view_slides">
            <div class="slide slide_clone" style="display: none;">
               <div class="dimmer"></div>
               <img src="https://upload.wikimedia.org/wikipedia/commons/d/da/Panthera_tigris_tigris_Tidoba_20150306.jpg">
            </div>

         </div>
         <div class="student_view_content">

            <iframe class="content_type student_view_content_iframe" src="https://www.youtube.com/embed/" frameborder="0"></iframe>
            <img class="content_type image_content" src="" data-magnify="gallery" data-caption="Image Caption 1" data-src="1.jpg" />
            <div class="content_type html_content" src="" style="background-color: white;"></div>
            <video src="" class="video_content" width="100%" controls controlsList="nodownload"></video>
         </div>

      </div>
      <div class="student_view_right">

         <div class="student_view_navigation">
            <div class="student_view_buttons close_action close_student_view"><i class="fas fa-times-circle"></i> Close Slideshow</div>
            <div style="display: none">
               <div class="dicussion_container student_chat_container">

                  adasdas

               </div>
               <div class="chat_discussion" style="position: relative;">
                  <textarea class="chat_text student_chat" name="" style="bottom: 0;"></textarea>
                  <button class="chat_submit" onclick="send_chat($('.student_chat').val())">Send</button>
               </div>
            </div>


         </div>
      </div>
   </div>

   <script src="<?php echo $resources . 'jquery-1.12.4.js' ?>"></script>
   <script src="<?php echo $resources . 'jquery.magnify.js' ?>"></script>
   <script src="<?php echo $resources . 'jquery-ui.js' ?>"></script>
   <script src="<?php echo $resources . 'jquery.mousewheel.min.js' ?>"></script>

   <script src="https://vjs.zencdn.net/7.7.5/video.js"></script>
   <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
   <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
   <script src="<?php echo $resources . 'lesson_show.js' ?>"></script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">

   <title>LMS - <?php echo $lesson['lesson_name'] ?></title>
   <link rel="stylesheet" href="<?php echo $resources . 'jquery-ui.css' ?>">
   <link rel="stylesheet" href="<?php echo $resources . 'lesson_3.css' ?>">
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
   <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
   <script src="https://cdn.tiny.cloud/1/iukfz8wu0g81q52ws27bltas7y7taocjqdq30eoi202b3nls/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
   <!-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> -->

   <style type="text/css">
      .ql-snow {
         background-color: white;
      }

      #learning_plan_text {
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

      .learning_plan_slider {
         width: 100%;
         top: 0px;
         z-index: 4;
      }

      .tinymce td {
         font-size: 10px;
      }

      #learning_plan_save {
         width: 200px;
         padding: 10px;
         background-color: green;
         color: white;
      }

      .joe_text {
         padding: 10px;
         width: 100%;
         margin-top: 10px;
         margin-bottom: 10px;
      }

      .instruction h2,
      h3 {
         border-radius: 10px;
      }

      .actions {
         <?php if ($lesson['lesson_type'] == "zoom" || $lesson['lesson_type'] == "virtual") : ?>width: 14%;
         <?php else : ?>width: 16.5%;
         <?php endif; ?>
      }

      #start_class img {
         height: 35px;
         width: 35px;
         top: 10px;
         position: absolute;
      }

      #start_class span {
         margin-left: 35px;
      }

      .tooltip {
         position: relative;
         display: inline-block;
         border-bottom: 1px dotted black;
      }

      .tooltip .tooltiptext {
         visibility: hidden;
         width: 120px;
         background-color: white;
         color: black;
         text-align: center;
         border-radius: 6px;
         padding: 7px 7px;
         position: absolute;
         z-index: 1;
         top: 150%;
         left: 50%;
         margin-left: -60px;
         font-size: 15px;
         font-weight: normal;
         border-color: black;
         border: 1px solid darkgray;
         /* box-shadow: 1px 1px 5px 1px rgba(0, 0, 0, 0.91); */
      }

      .tooltip .tooltiptext::after {
         content: "";
         position: absolute;
         bottom: 100%;
         left: 50%;
         margin-left: -7px;
         border-width: 7px;
         border-style: solid;
         border-color: transparent transparent white transparent;
         /* box-shadow: 1px 0px 18px -3px rgba(0, 0, 0, 0.91); */
      }

      .tooltip:hover .tooltiptext {
         visibility: visible;
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
   <input type="hidden" id="google_meet_id" value="<?php echo $role ?>" name="" />
   <input type="hidden" id="pdfjs" value="<?php echo site_url('backend/lms/pdfjs/web/viewer.html?file='); ?>" name="" />
   <input type="hidden" id="image_resources" value="<?php echo $resources . 'images/' ?>" name="" />
   <input type="hidden" id="start_url" value="<?php echo $start_url ?>" name="" />
   <input type="hidden" id="google_meet" value="<?php echo $google_meet ?>" name="" />
   <input type="hidden" id="account_id" value="<?php echo $account_id ?>" name="" />

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

   <div id="vimeo_modal" class="modal">
      <!-- Modal content -->
      <div class="modal-content">
         <h3 style="color: white">External Link Url</h3>
         <input type="text" class="joe_text" id="vimeo_title" name="" style="padding: 10px; width: 100%;" placeholder="Title">
         <input type="text" class="joe_text" id="vimeo_url" name="" style="padding: 10px; width: 100%;" placeholder="https://">
         <textarea type="text" class="joe_text" id="vimeo_description" name="" style="padding: 10px; width: 100%;" placeholder="Description"></textarea>
         <button class="vimeo_modal_done vimeo_modal_close">Done</button>
         <button class="vimeo_modal_close">Close</button>
      </div>
   </div>

   <div class="edit_area">
      <div class="part ben_left">
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
            <h2 style="margin: 5px;">Learning Outcomes <button id="learning_plan_save"> Save </button></h2>
            <div class="slider_container">
               <!-- <div id="learning_plan_text" style="color: black(); ">                        
                        </div> -->
               <textarea class="tinymce" id="the_learning_plan">
                  <?php if ($lesson['learning_plan'] != "") : ?>
                     <?php echo $lesson['learning_plan'] ?>
                  <?php else : ?>
                     <table style="height: 600px;" border="1">
                        <tr>
                        <th rowspan="2">Learning Competencies (MELCS)</th>
                        <th rowspan="2">Objectives</th>
                        <th rowspan="2">Virtual Session Schedule <span style="font-size: 10px;">(10,20,30,40,50,60,70,80,90)</span></th>
                        <th rowspan="2">Campus LMS Resources</th>
                        <th rowspan="2" colspan="2">Learning Experiences</th>
                        <th colspan="2">Hybrid Learning Modes</th>  
                        </tr>
                        <tr>
                        <th>Synchronous</th>
                        <th>Asynchronous</th>
                        </tr>
                        <tr style="font-size: 10px">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="color: red;font-size: 14px;"><h3>Engage</h3></td>
                        <td>How will you capture the student's interest? What questions should students ask themselves?</td>
                        <td></td>
                        <td></td>
                        </tr>
                        <tr style="font-size: 10px;">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="color: blue;font-size: 14px;"><h3>Explore</h3></td>
                        <td>Describe what kinds of hands-on/minds-on activities students will be doing?</td>
                        <td></td>
                        <td></td>
                        </tr>
                        <tr style="font-size: 10px;">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="color: violet;font-size: 14px;"><h3>Explain</h3></td>
                        <td>List higher order thinking questions which teachers will use to solicit student explanations and help them to justify their explanations.</td>
                        <td></td>
                        <td></td>
                        </tr>
                        <tr style="font-size: 10px">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="color: green;font-size: 14px;"><h3>Extend</h3></td>
                        <td>Describe how students will develop a more sophisticated understanding of the concept?</td>
                        <td></td>
                        <td></td>
                        </tr>
                        <tr style="font-size: 10px">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="color: brown;font-size: 14px;"><h3>Evaluate</h3></td>
                        <td>How will students demonstrate that they have achieved the lesson objective?</td>
                        <td></td>
                        <td></td>
                        </tr>
                        <tr style="font-size: 10px">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="color: orange;font-size: 14px;"><h3>Life Long Learning</h3></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        </tr>
                     </table>
                  <?php endif; ?>                  
               </textarea>
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
               <div class="col-lg-6" style="margin-bottom: 1000px;">
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

                                                         <li data-jstree='{"icon":"https://cdn.clipart.email/08211c36d197d37bb0d0761bbfeb8efd_square-academic-cap-graduation-ceremony-clip-art-graduation-hat-_1008-690.png"}' class="student" id="student_<?php echo $students_value['id'] ?>"><?php echo ucfirst(strtolower($students_value['lastname'])) ?>, <?php echo ucfirst(strtolower($students_value['firstname'])) ?></li>
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

               <div class="col-lg-6" style="margin-bottom: 400px">
                  <h3>Assign Date</h3>
                  <?php if ($lesson['start_date'] == '0000-00-00 00:00:00') : ?>
                     <?php $lesson['start_date'] = ""; ?>
                     <?php $lesson['end_date'] = ""; ?>
                  <?php endif ?>
                  <input type="hidden" name="" value="<?php echo $lesson['start_date'] ?>" class="start_date">
                  <input type="hidden" name="" value="<?php echo $lesson['end_date'] ?>" class="end_date">
                  <input type="text" value="" class="form-control date_range" name="" style="width: 80%;padding: 10px;">
                  <h3>Mode of Delivery <span class="zoom_email_used"></span></h3>
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
                                 } ?> value="virtual"><img src="">Google Meet Live Class</option>
                        <option <?php if ($lesson['lesson_type'] == "zoom") {
                                    echo "selected=''";
                                 } ?> value="zoom"><img src="">Zoom Live Class</option>
                     </select>
                  </div>

                  <div class="student_view_control">
                     <h3>Student Viewing</h3>
                     <div class="pretty p-switch p-fill">
                        <input type="checkbox" id="allow_view" <?php if ($lesson['allow_view'] == "") {
                                                                  echo "checked";
                                                               } ?> <?php if ($lesson['allow_view'] == "1") {
                                                                        echo "checked";
                                                                     } ?> />
                        <div class="state p-primary">
                           <label>Allow</label>
                        </div>
                     </div>
                  </div>

                  <div class="notification_control">
                     <h3>Notification</h3>
                     <div class="pretty p-switch p-fill" style="display: none">
                        <input type="checkbox" id="email_notification" <?php if ($lesson['email_notification'] == "1") {
                                                                           echo "checked";
                                                                        } ?> checked />
                        <!-- <input type="checkbox" id="email_notification" /> -->
                        <div class="state p-primary">
                           <label>Email Notification</label>
                        </div>
                     </div>
                     <button id="send_emails_now" style="padding: 10px;border-radius: 10px;border: 0px;cursor: pointer;width: 30%;background-color: #428bca;color: white;">Send Now</button>
                  </div>
                  <h3>Save</h3>
                  <button class="assign_save" style="    padding: 10px;width: 30%;border-radius: 10px;border: 0px;cursor: pointer;background-color: #428bca;color: white;">Save/Assign</button>
               </div>
            </div>
         </div>

         <div id="" class="slider close discussion_slider">
            <div class="slider_container">
               <style type="text/css">
                  .teacher_chat_container {
                     height: 100%;
                  }
               </style>
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
                        <option <?php if ($lesson['education_level'] == "all_levels") {
                                    echo "selected=''";
                                 } ?> value="all_levels">All Levels</option>
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

         <div class="footer">
            <div class="actions_container">
               <div class="actions">
                  <a href="<?php echo site_url('lms/lesson/index'); ?>">
                     <button class="action_button close_action"><i class="fas fa-times-circle"></i>Close</button>
                  </a>
               </div>
               <div class="actions">
                  <button id="learning_plan" class="trigger action_button"><i class="fab fa-leanpub"></i>Learning Plan</button>
               </div>
               <!-- <div class="actions">
                           <button id="objective" class="trigger action_button"><i class="fas fa-bullseye"></i>Objective</button>
                        </div> -->
               <div class="actions">
                  <button id="slideshow" class="action_button slideshow_action"><i class="fas fa-video"></i>Slideshow</button>
               </div>
               <div class="actions">
                  <button id="assign" class="trigger action_button assign_action"><i class="fas fa-chalkboard-teacher"></i>Assign</button>
               </div>
               <div class="actions">
                  <button id="discussion" class="trigger action_button"><i class="fas fa-school"></i>Discussion</button>
               </div>
               <div class="actions">
                  <button id="settings" class="trigger action_button"><i class="fas fa-cogs"></i>Settings</button>
               </div>
               <a class="virtual_link" target="_blank" href="">
                  <div class="actions start_class" <?php if ($lesson['lesson_type'] == "zoom" || $lesson['lesson_type'] == "virtual") : ?> style="display: block;" <?php else : ?> style="display: none;" <?php endif; ?>>
                     <button id="start_class" class="trigger action_button"><img src=""><span>Start Class</span></button>
                  </div>
               </a>

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

            <div class="upload_actions actions_container">
               <div class="actions">
                  <button class="action_button vimeo vimeo_color" id="vimeo_btn"><i class="fas fa-video"></i>External Link</button>
               </div>
            </div>
         </div>
      </div>

      <div class="part ben_right">
         <div class="navigation">
            <div class="title_container">
               <input type="text" disabled="" value="Campus LMS Resources Search" name="">

            </div>
            <div class="search_container">

               <input type="text" id="search_portal" placeholder="Content Search" name="search_portal">
               <button class="submit_button"><img src="<?php echo $resources . 'images/search.png' ?>"></button>
            </div>
         </div>

         <ul id="result_container" class="connectedSortable">

            <li class="ui-state-default content search_content content_hidden" result_id="">
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


            <div class="instruction instructions">
               <h2>How It Works: </h2>
               <h3>1. Find Resources.</h3>
               <h3>2. Open Results.</h3>
               <h3>3. Drag and Drop.</h3>
            </div>
            <div class="instruction my_resources_instructions">
               <h2>My Resources</h2>
               <h3>1. Find/Upload Resources.</h3>
               <h3>2. Drag and Drop. </h3>
            </div>
            <div class="instruction cms_resources_instructions">
               <h2>How It Works: </h2>
               <h3>1. Find Resources.</h3>
               <h3>2. Open Results.</h3>
               <h3>3. Drag and Drop.</h3>
            </div>
         </ul>


      </div>

      <div class="part extremeright">
         <div class="extremeright_filler">

         </div>
         <div class="search_container">

         </div>
         <div class="extremeright_icon icon_active" title="Youtube" portal="youtube">
            <center>
               <img src="<?php echo $resources . 'images/youtube.png' ?>">
            </center>
         </div>

         <div class="extremeright_icon" title="Google Image" portal="google_image">
            <center>
               <img src="<?php echo $resources . 'images/google-photos.svg' ?>">
            </center>
         </div>

         <div class="extremeright_icon" title="Google Web Search" portal="google">
            <center>
               <img src="<?php echo $resources . 'images/google.svg' ?>">
            </center>
         </div>

         <div class="extremeright_icon" title="My Resources" portal="my_resources">
            <center>
               <img src="<?php echo $resources . 'images/mycms.png' ?>">
            </center>
         </div>
         <div class="extremeright_icon" title="CMS Resources" portal="cms_resources">
            <center>
               <img src="<?php echo $resources . 'images/cms.png' ?>">
            </center>
         </div>
      </div>

   </div>


   <div class="student_view student_view_close">
      <style type="text/css">
         .student_view_container {
            width: 100%;
         }

         .button_navigation {
            width: 10%;
         }

         .student_view_title {
            width: 40%;
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
            width: 40%;
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
            <div class="student_view_buttons button_navigation blue previous tooltip"><i class="fas fa-chevron-left"></i>
               <span class="tooltiptext">Back</span>
            </div>
            <div class="student_view_buttons student_view_title">Title</div>
            <div class="student_view_buttons button_navigation blue next tooltip"><i class="fas fa-chevron-right"></i>
               <span class="tooltiptext">Next</span>
            </div>

            <?php if ($role == "admin") : ?>
               <div class="student_view_buttons navigation_tools green teacher_tools_button tooltip"><i class="fas fa-tools"></i>
                  <span class="tooltiptext">Teacher Tools</span>
               </div>
               <div class="student_view_buttons navigation_tools orange formula_board_button tooltip"><i class="fas fa-square-root-alt"></i>
                  <span class="tooltiptext">Formula Board</span>
               </div>
               <div class="student_view_buttons navigation_tools white annotate_button tooltip"><i class="fas fa-pen"></i>
                  <span class="tooltiptext">Annotate</span>
               </div>
               <!-- <div class="student_view_buttons navigation_tools blue teacher_tools_button"><i class="fas fa-wrench"></i> Discussion</div> -->
            <?php endif; ?>
            <!-- <div class="student_view_buttons navigation_tools orange discussion_board_button"><i class="fas fa-comments"></i> Discussion</div> -->
            <div class="student_view_buttons button_navigation red close_student_view tooltip"><i class="fas fa-times-circle"></i>
               <span class="tooltiptext">Close</span>
            </div>
         </div>

         <div class="student_view_slides" id="student_view_slides">
            <div class="slide slide_clone" style="display: none;">
               <div class="dimmer"></div>
               <img src="" style="object-fit: contain">
            </div>
         </div>

         <style type="text/css">
            .html_content {
               background-color: rgb(210, 206, 206);
               display: block;
               height: 100%;
               /* width: 100%; */
               padding: 50px;
               overflow: auto;
            }
         </style>

         <div class="student_view_content">
            <iframe class="content_type student_view_content_iframe" src="https://www.youtube.com/embed/" frameborder="0"></iframe>
            <img class="content_type image_content" src="" data-magnify="gallery" data-caption="Image Caption 1" data-src="1.jpg" />
            <div class="content_type html_content" style="background-color: white;"></div>
            <video src="" class="video_content" width="100%" controls controlsList="nodownload"></video>
         </div>

         <style type="text/css">
            .teacher_tools {
               width: 100%;
            }
         </style>

         <!-- <div id="teacher_tools" class="teacher_tools">
            <div class="student_view_buttons close_action teacher_tools_button" style="
                           width: 12%;
                           background-color: green;
                           border-radius: 20px;
                           margin: 5px;
                           z-index: 99999;
                           float: right;
                           padding: 5px;
                        "><i class="fas fa-times-circle"></i> Close</div>

            <h2 style="padding: 5px 30px;
                              margin: 0px;
                              left: 15px;
                              position: relative;
                              color: white;
                              width: 200px;
                              background-color: rgb(22, 187, 238);
                              font-weight: bolder;
                              margin-top: 0px;">CMS Teacher Tools</h2>
            <iframe id="classroomscreen" src="https://www.classroomscreen.com/classic/" style="width: 100%;height: 90%;position: relative;"></iframe>
         </div> -->

         <!-- <div id="" class="formula_board">
            <iframe id="" src="<?php echo base_url() ?>backend/lms/mathquill/" style="width: 100%;height: 100%;position: relative;"></iframe>
            <iframe id="" src="https://equatio.texthelp.com/space" style="width: 100%;height: 100%;position: relative;"></iframe>
         </div> -->

         <!-- <div id="" class="discussion_board">
            <h2>Discussion: </h2>
            <div class="dicussion_container student_chat_container" style="height:90%;width: 100%">

            </div>

            <div class="chat_discussion" style="">
               <textarea class="chat_text student_chat" name="" style="width: 30%"></textarea>
               <button class="chat_submit" onclick="send_chat($('.student_chat').val())" style="position: absolute;height: 37px;width: 15%;">Send</button>
            </div>
         </div> -->

         <canvas id="canvas" class="annotate" width="1000" height="1000">

         </canvas>

      </div>
      <!-- <div class="student_view_right"> -->

      <!-- <div class="student_view_navigation">
                  <?php if ($role == "admin") : ?>
                  <div class="student_view_buttons close_action teacher_tools_button" style="
                  width: 47%;
                  background-color: green;
                  border-radius: 20px;
                  margin: 5px;
                  z-index: 99999;
                  "><i class="fas fa-wrench"></i> CMS Teacher Tools</div>
                  <?php endif; ?>
                  <div class="student_view_buttons close_action close_student_view" style="
                  width: 47%;
                  border-radius: 20px;
                  margin: 5px;
                  "><i class="fas fa-times-circle"></i> Close Slideshow</div>
                  <div class="dicussion_container student_chat_container" style="height:450px;width: 100%">

                  </div>
                  <div class="chat_discussion" style="position: relative;width: 100%;">
                     <textarea class="chat_text student_chat" name="" style="bottom: 0;width: 96%;"></textarea>
                     <button class="chat_submit" onclick="send_chat($('.student_chat').val())" style="width: 98%;">Send</button>
                  </div>
                  <button>Full Screen</button>
               </div> -->
      <!-- </div> -->

   </div>
   <style type="text/css">
      .zoom_modal_container {
         position: absolute;
         height: 100%;
         width: 100%;
         background-color: rgba(255, 255, 255, .8);
         top: 0;
         z-index: 4;
         transition: all 1s;
      }

      .zoom_modal {
         width: 50%;
         height: 400px;
         /*background-color: white;*/
         margin: 0 auto;
         margin-top: 40px;
         border-radius: 10px;
      }

      .modal_title {
         padding: 10px;
         text-align: center;
      }

      .modal_close {
         display: none;
         transition: all 1s;
      }

      .modal_open {
         display: block;
         transition: all 1s;
      }

      .zoom_modal_container h1 {
         color: black;
         margin-top: 100px;
         text-align: center;

      }

      .zoom_done {
         background-color: green;
      }
   </style>
   <div class="zoom_modal_container" style="display: none">

      <div class="zoom_modal">
         <h1 class="zoom_label">Checking For Available Zoom Accounts</h1>
         <img class="zoom_checking" style="width: 100%" src="<?php echo $resources . 'images/zoom_loader.gif' ?>">
         <button class="zoom_done">Done</button>
      </div>
   </div>

   <script src="<?php echo $resources . 'jquery-1.12.4.js' ?>"></script>
   <script src="<?php echo $resources . 'jquery.magnify.js' ?>"></script>
   <script src="<?php echo $resources . 'jquery-ui.js' ?>"></script>
   <script src="<?php echo $resources . 'jquery.mousewheel.min.js' ?>"></script>

   <script type="text/javascript" src="https://vjs.zencdn.net/7.7.5/video.js"></script>
   <script type="text/javascript" src="https://cdn.quilljs.com/2.0.0-dev.2/quill.js"></script>
   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
   <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
   <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
   <script src="<?php echo $resources . 'drawing-table.js' ?>" type="text/javascript"></script>
   <script src="<?php echo $resources . 'lesson_student_view.js' ?>"></script>

   <script type="text/javascript">
      $(function() {
         $('[data-toggle="tooltip"]').tooltip()
      })
   </script>
</body>

</html>
<!DOCTYPE html>
<html>

<head>
   <title>Checking Zoom Accounts</title>
   <!-- Latest compiled and minified CSS -->
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

   <!-- Optional theme -->
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

   <!-- Latest compiled and minified JavaScript -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>

   <div class="container">
      <div class="header clearfix">
         <nav>
            <ul class="nav nav-pills pull-right">
               <li role="presentation" class="active"><a href="#">Zoom</a></li>
            </ul>
         </nav>
         <h3 class="text-muted">Zoom Checker</h3>
      </div>

      <div class="jumbotron">

         <h1>Pick Zoom Meeting</h1>
         <p class="lead">Please select a zoom to start the class. If you encounter any disconnection. You can reconnect here.</p>
         <div class="alert alert-info">
            <strong>Update!</strong> After you have finished your lesson or video conference. Please click the <button class="btn btn-danger">End Class. </button>. To update the zoom picker list and to let other teachers use the zoom account. Thank you.
         </div>

         <table class="table">
            <tr>
               <th>Level/Section</th>
               <th>Start Class / Reconnect</th>
               <th>Teacher</th>
               <th>Lesson</th>
               <th>Observe Class / Join Meeting</th>
            </tr>
            <?php foreach ($zoom_accounts as $zoom_lister_key => $zoom_lister_value) : ?>
               <tr>
                  <td><?php echo $zoom_lister_value['name'] ?></td>
                  <?php if ($zoom_lister_value['account_id'] == "") : ?>

                     <?php if ($lesson_id == "") : ?>

                     <?php else : ?>
                        <td><a href="<?php echo base_url('lms/lesson/start_zoom/' . $lesson_id . '/' . $zoom_lister_value['email']); ?>" onclick="page_refresh()" target="_blank"><button class="btn btn-success">Start Class</button></a></td>
                     <?php endif; ?>
                  <?php else : ?>
                     <?php if ($zoom_lister_value['account_id'] == $account_id) : ?>
                        <td>
                           <a href="#" onclick="end_class('<?php echo base_url('lms/lesson/end_zoom/' . $zoom_lister_value['email']); ?>')"><button class="btn btn-danger end_class">End Class</button></a>
                           <a href="<?php echo base_url('lms/lesson/start_zoom/' . $lesson_id . '/' . $zoom_lister_value['email']); ?>" target="_blank"><button class="btn btn-primary">Reconnect</button></a>
                        </td>
                     <?php else : ?>
                        <?php if ($real_role == 1 || $real_role == 7) : ?>
                           <td>
                              <a href="#" onclick="end_class('<?php echo base_url('lms/lesson/end_zoom/' . $zoom_lister_value['email']); ?>')"><button class="btn btn-danger end_class">End Class</button></a>

                              <a href="#" target="_blank"><button disabled="" class="btn btn-warning">In Progress</button></a>
                           </td>
                        <?php else : ?>
                           <td>
                              <a href="#" target="_blank"><button disabled="" class="btn btn-warning">In Progress</button></a>
                           </td>
                        <?php endif; ?>

                     <?php endif; ?>
                  <?php endif; ?>
                  <td><?php echo $zoom_lister_value['teacher_name'] ?></td>
                  <td><?php echo $zoom_lister_value['lesson_name'] ?></td>

                  <td>
                     <?php if ($zoom_lister_value['conference_id']) : ?>
                        <a href="<?php echo $zoom_lister_value['join_url'] ?>" target="_blank"><button class="btn btn-default">Join/Observe Class</button></a>
                     <?php endif; ?>
                  </td>
               </tr>
            <?php endforeach; ?>
         </table>



         <p class="lead">This is the new update for LMS Zoom Implementation. We upgraded the algorithm and User Friendliness of the interface for the teachers. There are no changes as of the method of using Zoom in the LMS please feel free to use it as the same as before.</p>
      </div>

      <div class="row marketing">
         <div class="col-lg-12">
            <h1>FAQ's</h1>
         </div>
         <div class="col-lg-6">
            <h4>Q: Encountered <b>Sign In to Start</b> instead going inside the zoom meeting.</h4>
            <p>A: If you encounter this issue please click this button
            <p><a class="btn btn-lg btn-warning" href="" role="button">Refresh this page.</a></p>
            </p>
            <p>The system will reassign you to a new zoom account which is available right now.</p>
            <hr>
            <h4>Q: When starting zoom there is a warning that looks like this. Saying that there is a current meeting in progress</h4>
            <img class="img-responsive" src="<?php echo base_url('backend/lms/images/zoom_conflict.png'); ?>">
            <p>A: This means that there is a person or teacher started class before you. This mostly happens if you both clicked the start meeting at the same time. To fix this issue please click this button. <a class="btn btn-lg btn-warning" href="" role="button">Refresh this page.</a></p>
         </div>

         <div class="col-lg-6">
            <h4>Q: I can't share screen there is an error everytime I try to start</h4>
            <p>A: This issue may be caused by either the hardware or the software. Zoom recommends to update to the latest zoom application and having Windows 10 or Windows 8 (OS)Operating System. If running on Windows 7 OS please make sure it is running on the latest software version. If you are running on Macintosh Machine aka. (Apple Laptop/Desktop) Please update the firmware to the lastest version, same case with other operating systems.</p>

         </div>
      </div>

      <footer class="footer">
         <p>© 2020 Cloud PH</p>
      </footer>

   </div>

   <!-- Modal -->
   <div id="myModal" class="modal fade " role="dialog">
      <div class="modal-dialog">

         <!-- Modal content-->
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title">Zoom Picker Update</h4>
            </div>
            <div class="modal-body">
               <div id="myCarousel" class="carousel slide" data-ride="carousel">
                  <!-- Indicators -->
                  <ol class="carousel-indicators">
                     <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                     <li data-target="#myCarousel" data-slide-to="1"></li>
                     <li data-target="#myCarousel" data-slide-to="2"></li>
                  </ol>

                  <!-- Wrapper for slides -->
                  <div class="carousel-inner">
                     <div class="item active">
                        <img src="la.jpg" alt="Los Angeles">
                     </div>

                     <div class="item">
                        <img src="chicago.jpg" alt="Chicago">
                     </div>

                     <div class="item">
                        <img src="ny.jpg" alt="New York">
                     </div>
                  </div>

                  <!-- Left and right controls -->
                  <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                     <span class="glyphicon glyphicon-chevron-left"></span>
                     <span class="sr-only">Previous</span>
                  </a>
                  <a class="right carousel-control" href="#myCarousel" data-slide="next">
                     <span class="glyphicon glyphicon-chevron-right"></span>
                     <span class="sr-only">Next</span>
                  </a>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
         </div>

      </div>
   </div>
   <script type="text/javascript">
      function end_class(url) {
         if (confirm("Are you sure you want to end class?")) {
            window.location.replace(url);
         }

      }

      function page_refresh() {
         setTimeout(function() {
            location.reload();
         }, 5000);

      }
   </script>

</body>

</html>
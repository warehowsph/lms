<?php
function displayTextWithLinks($s)
{
   return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a target=_blank href="$1">Click here to view the document(s)</a>', $s);
}

foreach ($docs as $value) {
   // print_r($value['docs']);
?>
   <tr>
      <td><?php echo $value["lastname"] . ", " . $value['firstname']; ?></td>
      <td><?php echo displayTextWithLinks(strip_tags($value["message"])); ?></td>
      <td><?php echo displayTextWithLinks(strip_tags($value["url_link"])); ?></td>
      <td><?php echo $value["created_at"]; ?></td>
      <td><?php echo $value["score"]; ?></td>
      <td><?php echo $value["remarks"]; ?></td>
      <td class="text-right nowrap">
         <?php if ($value["url_link"] != '' || $value['docs'] != '') {
            if ($value['docs'] != '') {
               if (
                  strpos(strtoupper($value['docs']), ".DOC") !== false || strpos(strtoupper($value['docs']), ".XLS") !== false ||
                  strpos(strtoupper($value['docs']), ".PPT") !== false || strpos(strtoupper($value['docs']), ".DOCX") !== false ||
                  strpos(strtoupper($value['docs']), ".XLSX") !== false || strpos(strtoupper($value['docs']), ".PPTX") !== false
               ) { ?>

                  <a data-placement="left" class="btn btn-default btn-xs document_view_btn" homework-id="<?php echo $value["homework_id"] ?>" student-session-id="<?php echo $value["session_id"] ?>" student-name="<?php echo $value["firstname"] . " " . $value['lastname']; ?>" file_location="<?php echo $_SESSION['S3_BaseUrl']; ?>uploads/homework/assignment/<?php echo $value['docs']; ?>&embedded=true" data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>"><i class="fa fa-eye"></i></a>
               <?php } else { ?>
                  <!-- <a data-placement="left" class="btn btn-default btn-xs document_view_btn" file_location="<?php echo base_url(); ?>homework/assigmnetDownload/<?php echo $value['docs']; ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>"><i class="fa fa-eye"></i></a> -->
                  <a data-placement="left" class="btn btn-default btn-xs document_view_btn" homework-id="<?php echo $value["homework_id"] ?>" student-session-id="<?php echo $value["session_id"] ?>" student-name="<?php echo $value["firstname"] . " " . $value['lastname']; ?>" file_location="<?php echo $_SESSION['S3_BaseUrl']; ?>uploads/homework/assignment/<?php echo $value['docs']; ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>"><i class="fa fa-eye"></i></a>
               <?php } ?>

               <a data-placement="left" class="btn btn-default btn-xs" homework-id="<?php echo $value["homework_id"] ?>" student-session-id="<?php echo $value["session_id"] ?>" student-name="<?php echo $value["firstname"] . " " . $value['lastname']; ?>" href="<?php echo $_SESSION['S3_BaseUrl']; ?>uploads/homework/assignment/<?php echo $value['docs']; ?>" data-toggle="tooltip" title="Download"><i class="fa fa-download"></i></a>
               <!-- <a data-placement="left" class="btn btn-default btn-xs" href="<?php echo base_url(); ?>homework/assigmnetDownload/<?php echo $value['docs']; ?>" data-toggle="tooltip" title="Download"><i class="fa fa-download"></i></a> -->
            <?php } ?>

            <a data-placement="left" class="btn btn-default btn-xs evaluatebtn" homework-id="<?php echo $value["homework_id"] ?>" student-session-id="<?php echo $value["session_id"] ?>" student-name="<?php echo $value["firstname"] . " " . $value['lastname']; ?>" data-toggle="tooltip" title="Evaluate"><i class="fa fa-reorder"></i></a>
         <?php } ?>
      </td>
   </tr>
<?php } ?>

<script type="text/javascript">
   $(".evaluatebtn").click(function() {
      $("#eval_student").html($(this).attr("student-name"));
      $("#student_session_id").attr("value", $(this).attr("student-session-id"));
      $("#homework_id").attr("value", $(this).attr("homework-id"));
      $('#evaluate_student').modal({
         backdrop: 'static',
         keyboard: false,
         show: true
      });
   });

   $(".document_view_btn").click(function() {
      var file_location = $(this).attr("file_location");

      //'JPG', 'JPEG', 'PNG', 'GIF', 'BMP', 'SVG'
      //'DOC', 'XLS', 'PPT', 'DOCX', 'XLSX', 'PPTX'

      if (file_location.toLocaleUpperCase().includes(".PDF")) {
         var pdfjs = "<?php echo site_url('backend/lms/pdfjs/web/viewer.html?file='); ?>";
         var file_location = $(this).attr("file_location");

         $(".document_iframe_pdf").attr("src", "");

         $("#eval_student_pdf").html($(this).attr("student-name"));
         $("#student_session_id_pdf").attr("value", $(this).attr("student-session-id"));
         $("#homework_id_pdf").attr("value", $(this).attr("homework-id"));

         $(".document_iframe_pdf").attr("src", pdfjs + file_location);

         // $(".document_iframe").attr("src", 'https://docs.google.com/gview?url=' + decodeURIComponent(file_location));
         $('#document_view_modal_pdf').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
         });
      } else if (file_location.toLocaleUpperCase().includes(".JPG") || file_location.toLocaleUpperCase().includes(".JPEG") ||
         file_location.toLocaleUpperCase().includes(".PNG") || file_location.toLocaleUpperCase().includes(".GIF") ||
         file_location.toLocaleUpperCase().includes(".SVG")) {

         $(".document_img").attr("src", "");

         $("#eval_student_img").html($(this).attr("student-name"));
         $("#student_session_id_img").attr("value", $(this).attr("student-session-id"));
         $("#homework_id_img").attr("value", $(this).attr("homework-id"));

         $(".document_img").attr("src", file_location);

         $('#document_view_modal_img').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
         });
      } else if (file_location.toLocaleUpperCase().includes(".MP4") || file_location.toLocaleUpperCase().includes(".AVI") ||
         file_location.toLocaleUpperCase().includes(".MOV") || file_location.toLocaleUpperCase().includes(".WMV")) {
         var type = file_location.toLocaleLowerCase().slice(3);

         $(".document_vid").attr("src", "");

         $("#eval_student_vid").html($(this).attr("student-name"));
         $("#student_session_id_vid").attr("value", $(this).attr("student-session-id"));
         $("#homework_id_vid").attr("value", $(this).attr("homework-id"));

         $(".document_vid").attr("src", file_location);
         $(".document_vid").attr("type", "video/" + type);

         $('#document_view_modal_vid').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
         });
      } else if (file_location.toLocaleUpperCase().includes(".DOC") || file_location.toLocaleUpperCase().includes(".XLS") ||
         file_location.toLocaleUpperCase().includes(".PPT") || file_location.toLocaleUpperCase().includes(".DOCX") ||
         file_location.toLocaleUpperCase().includes(".XLSX") || file_location.toLocaleUpperCase().includes(".PPTX")) {
         var officedoc = "https://view.officeapps.live.com/op/view.aspx?src=";
         // var officedoc = "https://docs.google.com/gview?url=";
         var file_location = $(this).attr("file_location");

         $(".document_iframe_office").attr("src", "");

         $("#eval_student_office").html($(this).attr("student-name"));
         $("#student_session_id_office").attr("value", $(this).attr("student-session-id"));
         $("#homework_id_office").attr("value", $(this).attr("homework-id"));

         $(".document_iframe_office").attr("src", officedoc + decodeURIComponent(file_location) + "&embedded=true");
         // $(".document_iframe").attr("src", officedoc + decodeURIComponent(file_location));
         $('#document_view_modal_office').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
         });
      } else {
         alert("No preview available. You may download the document to view.");
      }

      $("#document_view_modal_vid").on('hidden.bs.modal', function() {
         var media = $(".document_vid").get(0);
         media.pause();
         media.currentTime = 0;
      });

      // $("#full-image").attr("src", file_location);
      // $('#image-viewer').show();
   });
</script>
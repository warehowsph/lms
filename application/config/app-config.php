<?php
// https://filext.com/faq/office_mime_types.html
// https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Complete_list_of_MIME_types

if (!defined('BASEPATH')) {
   exit('No direct script access allowed');
}

$config['adm_digit_length'] = 6;
$config['exam_type'] = array(
   'basic_system'        => lang('basic_system'),
   'school_grade_system' => lang('school_grade_system'),
   'coll_grade_system'   => lang('coll_grade_system'),
   'gpa'                 => lang('gpa'),
);

$config['image_validate'] = array(
   'allowed_mime_type' => array('image/jpeg', 'image/jpg', 'image/png'), //mime_type
   'allowed_extension' => array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'JPG', 'JPEG', 'PNG', 'GIF', 'BMP', 'SVG', 'Jpg', 'Jpeg', 'Png', 'Gif', 'Bmp', 'Svg'), // image extensions
   'upload_size'       => 31457280 //1048576, // bytes
);

$config['csv_validate'] = array(
   'allowed_mime_type' => array('application/vnd.ms-excel', 'text/plain', 'text/csv', 'text/tsv'), //mime_type
   'allowed_extension' => array('csv'), // image extensions
   'upload_size'       => 31457280, //1048576, // bytes
);

$config['file_validate'] = array(
   'allowed_mime_type' => array(
      'application/pdf',
      'application/msword',
      'application/vnd.ms-excel',
      'application/vnd.ms-powerpoint',
      'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
      'application/vnd.openxmlformats-officedocument.presentationml.presentation',
      'application/x-zip-compressed',
      'application/zip',
      'application/octet-stream',
      'image/jpeg',
      'image/jpg',
      'image/png',
      'video/mp4',
      'video/quicktime',
      'video/x-ms-wmv',
      'video/x-msvideo'
   ), //mime_type
   'allowed_extension' => array(
      'zip', 'pdf', 'doc', 'xls', 'ppt', 'docx', 'xlsx', 'pptx', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg',
      'ZIP', 'PDF', 'DOC', 'XLS', 'PPT', 'DOCX', 'XLSX', 'PPTX', 'JPG', 'JPEG', 'PNG', 'GIF', 'BMP', 'SVG',
      'Pdf', 'Zip', 'Doc', 'Ppt', 'Xls', 'Docx', 'Xlsx', 'Pptx', 'Jpg', 'Jpeg', 'Png', 'Gif', 'Bmp', 'Svg',
      'MP4', 'Mp4', 'mp4',
      'MOV', 'Mov', 'mov',
      'WMV', 'Wmv', 'wmv',
      'AVI', 'Avi', 'avi'
   ),
   // 'upload_size' => 26214400, // 25MB
   'upload_size' => 31457280, //1048576, // bytes 35MB
);

// $config['S3_Bucket_Url'] = "https://s3.us-east-2.amazonaws.com/media.campuscloudph.com/";
// $config['S3_Bucket'] = "media.campuscloudph.com";
// $config['AWSAccessKeyId'] = "AKIAXG65GN66XVJDHDDE";
// $config['AWSSecretKey'] = "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";

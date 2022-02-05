<?php

  function old_url($url){
    return str_replace("/lms_v2/","",base_url())."/".$url;
  }

?>

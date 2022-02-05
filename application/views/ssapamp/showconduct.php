<center>
<h2>ST. SCHOLASTICA’S ACADEMY</h1>
City of San Fernando, Pampanga
<br>
PRESCHOOL
<br>
A.Y. 2021-2022
<br>
<p>PRE – KINDERGARTEN CHECKLIST</p>
</center>
<!-- <form action="<?php //echo base_url('studentconduct/submit_record') ?>" method="post" class="form-horizontal">      -->
<form action="<?php echo base_url('lms/conduct_ssapamp') ?>" method="post" class="form-horizontal">  
<div class="container">
<input type="hidden" name="studentid" value="<?php echo $studentid ?>" >
<input type="hidden" name="levelid" value="<?php echo $levelid ?>" >
<input type="hidden" name="sectionid" value="<?php echo $sectionid ?>" >
<input type="hidden" name="schoolyear" value="<?php echo $schoolyear ?>" >
<div class="row" id="t0_row" >
    <div class="col-1" id="div3">Name</div>
    <div class="col-4" id="div4"><?php echo $studentname ?></div>
    <div class="col-1" id="div3">Section</div>
    <div class="col-2" id="div4"><?php echo $section ?></div>
</div>
<br>
<div class="row" id="t_row" >
    <div class="col-4" style="border: 1px solid black"><b>FIRST SEMESTER</b></div>
    <div class="col-2" style="border: 1px solid black"><b><center>NUMBER GRADE</center></b></div>
    <div class="col-2" style="border: 1px solid black"><b><center>L.G.</center></b></div>
</div>
<?php
 foreach($Subjects as $row)
 {
?>

  <?php
  // if ($row->id == 1) {
  //   $list=$cle;
  //   $gradelist = array();
  //   $gradelist = $db_cle;
  // } else if ($row->id == 2) {
  //   $list=$reading;
  //   $gradelist = array();
  //   $gradelist = $db_reading;
  // } else if ($row->id == 3) {
  //   $list=$math;
  //   $gradelist = array();
  //   $gradelist = $db_math;
  // } else {
  //   $list=$mape;
  //   $gradelist = array();
  //   $gradelist = $db_mape;
  // }
  // var_dump($gradelist);
  // foreach ($list as $row1) {

  ?>
  <div class="row" id="t1_row" >
    <div class="col-4" style="border: 1px solid black"><b><?php echo $row->alpha . '. ' . $row->description;?></b></div>
    <input type="hidden" name="tg_id[]" value="<?php echo $row->id ?>" >
    <?php

      $ccid = $row->id;
      $key = array_search($ccid, array_column($db_grades, 'clid'));
      if ($key) {
        // var_dump($gradelist[$key]);
        // echo "<br>";
        $ssid =  $db_grades[$key]['ssid'];
        $p1 = $db_grades[$key]['grade'];
        $fg =$db_grades[$key]['lg'];
      }
      else {
        $ssid=0;
        $p1=1;
        $fg="";
      }

      // }

    ?>  
    <input type="hidden" name="clid[]" value="<?php echo $ccid ?>" >  
    <input type="hidden" name="ssid[]" value="<?php echo $ssid ?>" >  
    <div class="col-2" style="border: 1px solid black"><center><input type="text" style="border-bottom:0px " name="grade[]" value="<?php echo $p1;?>" onkeyup="calculateSum2(<?php echo $row->id ?>)" onkeydown="calculateSum2(<?php echo $row->id ?>)" class="grade<?php echo $row->id ?>"></center></div>
    
    <div class="col-2" style="border: 1px solid black"><center><input type="text" style="border-bottom:0px " name="final[]" value="<?php echo $fg;?>" id="fin<?php echo $row->id ?>" readonly></center></div>

  </div>
<?php

  }
?>  
<br>

</div>
            <div class="form-group">
                <label class="control-label col-sm-2"></label>
                <div class="col-sm-4">
                    <input id="btnSave" type="submit" name="btnSave" class="btn btn-primary" value="Save">   
                </div>
            </div>
</form>
<br><br>

<script>

$(document).ready(function() {
    //this calculates values automatically 
    // calculateSum();
     calculateSum2();
    //  calculateAVE();
    //  acts();


    // $(".dc").on("keydown keyup", function() {
    //     calculateSum();
    // });
    // $(".p").on("keydown keyup", function() {
    //     calculateAVE();
    // });
    // $("#action").on("keydown keyup", function() {
    //     acts();
    // });
});

function calculateSum2($i) {
    var sum = 0,
    i = $i;
    //iterate through each textboxes and add the values
    $(".grade"+ i).each(function() {
        //add only if the value is number
        if (!isNaN(this.value) && this.value.length != 0) {
            sum += parseFloat(this.value);
            ;
        }
        else if (this.value.length != 0){
            $(this).css("background-color", "red");
        }
    });

    if ((9 <= sum) && (sum <= 10 )) {
      $("input#fin"+ i).val("O");
    }
    else if ((7 <= sum) && (sum <= 8 )) {
      $("input#fin"+ i).val("VS");
    }
    else if ((5 <= sum) && (sum <= 6 )) {
      $("input#fin"+ i).val("S");
    }
    else if ((3 <= sum) && (sum <= 4 )) {
      $("input#fin"+ i).val("NI");
    }
    else if ((1 <= sum) && (sum <= 2 )) {
      $("input#fin"+ i).val("U");
    }
//  }
  // $("input#fin"+i).val(sum);
}
</script>

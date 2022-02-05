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
<form action="<?php echo base_url('lms/checklist_ssapamp') ?>" method="post" class="form-horizontal">     
<div class="container">
<input type="hidden" id="studentid" name="studentid" value="<?php echo $studentid ?>" >
<input type="hidden" id="levelid" name="levelid" value="<?php echo $levelid ?>" >
<input type="hidden" id="sectionid" name="sectionid" value="<?php echo $sectionid ?>" >
<input type="hidden" id="schoolyear" name="schoolyear" value="<?php echo $schoolyear ?>" >
<input type="hidden" id="quarter" name="quarter" value="<?php echo $quarter ?>" >
  <div class="row" id="q_row">
    <div class="col-1" id="div3">Name</div>
    <div class="col-4" id="div4"><?php echo $studentname ?></div>
    <div class="col-1" id="div3"> </div>
    <div class="col-1" id="div3">Section</div>
    <div class="col-3" id="div4"><?php echo $section ?></div>
  </div>
  <div class="row" id="q_row">
    <div class="col-1" id="div3">Age</div>
    <div class="col-1" id="div4"><?php echo $age ?></div>
    <div class="col-4" id="div3"></div>
    <div class="col-1" id="div3"></div>
    <div class="col-3" id="div3"></div>
  </div>
  <br>
  <br>
  <div class="row" id="r_row">
    <div class="col-4" id="div1"></div>
    <div class="col-6" id="div-cell-no-bottom"><center>PERIODIC RATINGS</center></div>
  </div>
  <div class="row" id="s_row">
    <div class="col-4" id="div2"></div>
    <div class="col-2" style="border: 1px solid black"><center>1st</center></div>
    <div class="col-2" style="border: 1px solid black"><center>2nd</center></div>
    <div class="col-2" style="border: 1px solid black"><center>FINAL</center></div>
  </div>
  <br>
<?php
 foreach($Subjects as $row)
 {
?>

  <div class="row" id="t_row">
    <div class="col-4" id="div-cell-no-bottom"><?php echo $row->alpha . '.' . $row->checklistname;?></div>
    <div class="col-2" id="div-cell-no-bottom"> </div>
    <div class="col-2" id="div-cell-no-bottom"> </div>
    <div class="col-2" id="div-cell-no-bottom"> </div>
  </div>
  <?php
  if ($row->id == 1) {
    $list=$cle;
    $gradelist = array();
    $gradelist = $db_cle;
  } else if ($row->id == 2) {
    $list=$reading;
    $gradelist = array();
    $gradelist = $db_reading;
  } else if ($row->id == 3) {
    $list=$math;
    $gradelist = array();
    $gradelist = $db_math;
  } else {
    $list=$mape;
    $gradelist = array();
    $gradelist = $db_mape;
  }
  // var_dump($gradelist);
  foreach ($list as $row1) {

  ?>
  <div class="row" id="t1_row" >
    <?php
      // var_dump($row1->mainsub);
      if ($row1->mainsub=="S1")
      {
        $lbl =$row1->itemseq . '.' . $row1->detail;
        $label=str_pad($lbl,(20*strlen("&nbsp;")),"&nbsp;",  STR_PAD_LEFT);  
        $label=$lbl;      
      }
      elseif ($row1->mainsub=="S")
      {
        $lbl =$row1->itemseq . '.' . $row1->detail;
        $label=$lbl;
        // $label=str_pad($lbl,20,"~",  STR_PAD_LEFT);  
        // $label=str_replace("~",chr(32),$t);  
      }
      elseif ($row1->mainsub=="M")
      {
        $label=$row1->itemseq . '.' . $row1->detail;
      }
    ?>
    <div class="col-4" id="div-cell-no-bottom"><?php echo $label;?></div>
    <input type="hidden" name="tg_id[]" value="<?php echo $row1->id ?>" >
    <?php
    if ($row1->graded == "1")
    {
      $ccid = $row1->id;
      // var_dump($ccid);
      // echo "<br>";
      $key = array_search($ccid, array_column($gradelist, 'clid'));
      if ($key) {
        // var_dump($gradelist[$key]);
        // echo "<br>";
        $ssid =  $gradelist[$key]['ssid'];
        $p1 = $gradelist[$key]['period1'];
        $p2 =$gradelist[$key]['period2'];
        $fg =$gradelist[$key]['finalgrade'];
      }
      else {
        $ssid=0;
        $p1=70;
        $p2=70;
        $fg=70;
      }

      ?>  
    <input type="hidden" id="ccid<?php echo $row1->id;?>" name="clid[]" value="<?php echo $ccid ?>" >  
    <input type="hidden" id="ssid<?php echo $row1->id;?>" name="ssid[]" value="<?php echo $ssid ?>" >  
    <div class="col-2" id="div-cell-no-bottom"><center><input type="text" style="border-bottom:0px" name="1st[]" value="<?php echo $p1;?>" onkeyup="calculateSum2(<?php echo $row1->id ?>)" onkeydown="calculateSum2(<?php echo $row1->id ?>)" class="grade<?php echo $row1->id;?>" <?php if ($quarter=="2") echo "readonly";?>></center></div>
    <div class="col-2" id="div-cell-no-bottom"><center><input type="text" style="border-bottom:0px" name="2nd[]" value="<?php echo $p2;?>" onkeyup="calculateSum2(<?php echo $row1->id ?>)" onkeydown="calculateSum2(<?php echo $row1->id ?>)" class="grade<?php echo $row1->id;?>" <?php if ($quarter=="1") echo "readonly";?>></center></div>
    <div class="col-2" id="div-cell-no-bottom"><center><input type="text" style="border-bottom:0px" name="final[]" value="<?php echo $fg;?>" id="fin<?php echo $row1->id ?>" readonly></center></div>
    <?php
  } else {

  ?>
    <div class="col-2" id="div-cell-no-bottom"> </div>
    <div class="col-2" id="div-cell-no-bottom"> </div>
    <div class="col-2" id="div-cell-no-bottom"> </div>
    <?php
  }
  ?>
  </div>
<?php

  }
?>  
<?php
}
?>
  <div class="row" id="u_row">
    <div class="col-4" id="div-cell-only-top"></div>
    <div class="col-2" id="div-cell-only-top"> </div>
    <div class="col-2" id="div-cell-only-top"> </div>
    <div class="col-2" id="div-cell-only-top"> </div>
  </div>
</div>
<br>
    <div class="form-group">
        <label class="control-label col-sm-2"></label>
        <div class="col-sm-4">
            <input id="btnSave" type="submit" name="btnSave" class="btn btn-primary" value="Save">   
        </div>
    </div>
</form>
<br><br>

<script>
var url = "<?php echo base_url('lms/grading_checklist_ssapamp/') ?>";

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
    var grades = [];
    period1_val=0;
    period2_val=0;
    //iterate through each textboxes and add the values
    $(".grade"+ i).each(function() {
        //add only if the value is number
        grades.push(this.value);
        if (!isNaN(this.value) && this.value.length != 0) {
            sum += parseFloat(this.value)/ "2";
            ;
        }
        else if (this.value.length != 0){
            $(this).css("background-color", "red");
        }
    });

    grades.forEach(function (item, index) {
      console.log(item, index);
      switch (index) {
        case 0:
        period1_val=item;
        break;
        case 1:
        period2_val=item;
        break;
      }

    });

    quarter = $("input#quarter").val();

    var ccidval = 0;
    ccidval = $("input#ccid" + i).val();
    console.log("ccidval");
    console.log(ccidval);

    var ssidval = 0;
    ssidval = $("input#ssid" + i).val();
    console.log("ssidval");
    console.log(ssidval);

    var studid = "";
    studid = $("input#studentid").val();
    console.log("studid");
    console.log(studid);

    console.log(period1_val);
    console.log(period2_val);
    var sumval = 0;
    console.log("quarter");
    console.log(quarter);
    if (quarter=="1") {
      // sum = $("input#fin"+i).val();
      sumval = $("input#fin"+i).val();
    } else {
      sumval = sum;
    }

    var update_data = {
         studentid: studid,
         clid: ccidval,
         id: ssidval,
         period1: period1_val,
         period2: period2_val,
         finalgrade: sumval
    }
      
    data = JSON.stringify(update_data);
    console.log(data);

    $.ajax({
        url: url + "update",
        type: "POST",
        data: update_data,
        complete: function(response) {
          console.log(response.responseText);
        }
    });    
//  if(sum < 75){
//   $("input#action"+ i).val("FAILED");
//  }else{
//   $("input#action"+ i).val("PASSED");

//  }
    if (quarter=="2") {
      $("input#fin"+i).val(sum);
    }
}
</script>

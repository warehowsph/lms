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
<!-- <div class="container"> -->
<input type="hidden" name="studentid" value="<?php echo $studentid ?>" >
<input type="hidden" name="levelid" value="<?php echo $levelid ?>" >
<input type="hidden" name="sectionid" value="<?php echo $sectionid ?>" >
<input type="hidden" name="schoolyear" value="<?php echo $schoolyear ?>" >
<table style="width:100%" id="Tablesample">
  <tr>
    <td>Name</td>
    <td><?php echo $studentname ?></td>
    <td> </td>
    <td>Section</td>
    <td><?php echo $section ?></td>
  </tr>
  <tr>
    <td>Age</td>
    <td><?php echo $age ?></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td colspan="4"><center>PERIODIC RATINGS</center></td>
  </tr>
  <tr>
    <td></td>
    <td><center>1st</center></td>
    <td><center>2nd</center></td>
    <td><center>FINAL</center></td>
    <td><center>LG</center></td>
  </tr>
<?php
$subjectctr=0;
 foreach($Subjects as $row)
 {
   $subjectctr+=1;
   if ($subjectctr>1) {
     echo "<tr>";
     echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
     echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
     echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
     echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
     echo "</tr>";
   }
?>

  <tr>
    <td><b><?php echo $row->alpha . '.' . $row->checklistname;?></b></td>
    <td><center><input type="text" style="border-bottom:0px" name="<?php echo $row->alpha ;?>1" id="subject_<?php echo $row->alpha ?>1" readonly class="subject_<?php echo $row->alpha ?>"></center></td>
    <td><center><input type="text" style="border-bottom:0px" name="<?php echo $row->alpha ;?>2" id="subject_<?php echo $row->alpha ?>2" readonly class="subject_<?php echo $row->alpha ?>"></center></td>
    <td><center><input type="text" style="border-bottom:0px" name="<?php echo $row->alpha ;?>final" id="final_<?php echo $row->alpha ?>" readonly></center></td>
    <td><center><input type="text" style="border-bottom:0px" name="<?php echo $row->alpha ;?>lg" id="lg<?php echo $row->alpha ?>" readonly></center></td>
  </tr>
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
    if ($row1->mainsub=="M" and $row1->graded == "0")
    {
      echo "<tr>";
      echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
      echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
      echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
      echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
      echo "</tr>";
    }
  ?>

  <tr>
    <?php
      $prefix = "";
      // var_dump($row1->mainsub);
      if ($row1->mainsub=="S1")
      {
        $lbl =$row1->itemseq . "." . $row1->detail;
        // $label=str_pad($lbl,(20*strlen("&nbsp;")),"&nbsp;",  STR_PAD_LEFT);  
        $label="&nbsp&nbsp&nbsp&nbsp&nbsp" .$lbl;  
        $prefix = $row->alpha . "_";    
      }
      elseif ($row1->mainsub=="S")
      {
        $lbl =$row1->itemseq . "." .  $row1->detail;
        $label="&nbsp&nbsp&nbsp&nbsp&nbsp" . $lbl;
        $prefix = $row->alpha . "_";
        // $label=str_pad($lbl,20,"~",  STR_PAD_LEFT);  
        // $label=str_replace("~",chr(32),$t);  
      }
      elseif ($row1->mainsub=="M")
      {
        $label=$row1->itemseq . "." .  $row1->detail;
        $prefix = $row->alpha;
      }
    ?>
    <td><?php echo $label;?></td>
    <input type="hidden" name="tg_id[]" value="<?php echo $row1->id ?>" >
    <?php
    if ($row1->graded == "1")
    {
      $ccid = $row1->id;
      // var_dump($ccid);
      // echo "<br>";
      if ($row1->mainsub=="M") {         
        $tdclass = $row->alpha;
      } else {
        $tdclass = $prefix . $row1->id;
      }      
      $key = array_search($ccid, array_column($gradelist, 'clid'));
      if ($key) {
        // var_dump($gradelist[$key]);
        // echo "<br>";
        $ssid =  $gradelist[$key]['ssid'];
        $p1 = $gradelist[$key]['period1'];
        $p2 =$gradelist[$key]['period2'];
        $fg =$gradelist[$key]['finalgrade'];
        $tclass =$gradelist[$key]['class'];
        $class = "grade" . $tclass . $row1->id;
        // $x = $prefix[strlen($prefix)-1];
        // if ($x == "_")
 
        
      }
      else {
        $ssid=0;
        $p1=50;
        $p2=50;
        $fg=50;
        $class="";
        $tdclass = "A";
      }

      ?>  
    <input type="hidden" name="clid[]" value="<?php echo $ccid ?>" >  
    <input type="hidden" name="ssid[]" value="<?php echo $ssid ?>" >  
    <input type="hidden" name="ff" value="<?php echo $tdclass ?>" >  
    <input type="hidden" name="ff" value="<?php echo $row1->groupclass ?>" >
    <td class="1<?php echo $tclass;?>"><center><input type="text" style="border-bottom:0px" name="1st[]" value="<?php echo $p1;?>" onchange="calculateSum3(this.className,<?php echo $row1->id ?>,'<?php echo $tclass;?>')" class="<?php echo $class ?>"></center></td>
    <td class="2<?php echo $tclass;?>"><center><input type="text" style="border-bottom:0px" name="2nd[]" value="<?php echo $p2;?>" onchange="calculateSum3(this.className,<?php echo $row1->id ?>,'<?php echo $tclass;?>')" class="<?php echo $class ?>"></center></td>
    <td><center><input type="text" style="border-bottom:0px" name="final[]" value="<?php echo $fg;?>" id="fin<?php echo $row1->id ?>" readonly></center></td>
    <td><center><input type="text" style="border-bottom:0px" name="lg[]" value="<?php echo "";?>" id="lg<?php echo $row1->id ?>" readonly></center></td>
    <!-- <td></td> -->
    <?php
  } else {
      if (($row1->mainsub=="M") or ($row1->mainsub=="S")) {
        if ($row1->mainsub=="M") {
          $t_d_class = "_" . $row1->id;
        } elseif ($row1->mainsub=="S") {
          $t_d_class = $row1->groupclass;
        }

        ?>
    <input type="hidden" name="ff" value="<?php echo $row->groupclass ?>" >
    <td class="1<?php echo $t_d_class ?>"><center><input type="text" style="border-bottom:0px" name="1st1[]" id="1_<?php echo $row1->id ?>" readonly></center></td>
    <td class="2<?php echo $t_d_class ?>"><center><input type="text" style="border-bottom:0px" name="2nd1[]" id="2_<?php echo $row1->id ?>" readonly></center></td>
    <td><center><input type="text" style="border-bottom:0px" name="final1[]" readonly></center></td>
    <td><center><input type="text" style="border-bottom:0px" name="lg1[]"  readonly></center></td>        
<?php        
      }
  ?>
    <!-- <td></td>
    <td></td>
    <td></td>
    <td></td> -->
    <?php
  }
  ?>
  </tr>
<?php

  }
?>  
<?php
}
?>
  <!-- <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr> -->
  </table>  
<!-- </div> -->

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
var legend_array = [];
$(document).ready(function() {
    //this calculates values automatically 
    // calculateSum();
    //  calculateSum2();
    //  calculateAVE();
    //  acts();
    
     var legendrecord = "<?php echo $legend_record ?>";
     var legendarray = legendrecord.split('*');
     
     legendarray.forEach(function (item, index) {
      console.log(item, index);
      itemarray = item.split('|');
      lettergrade = itemarray[0];
      range = itemarray[2];
      const legend_object = {
        lgrade : lettergrade,
        graderange : range 
      };
      legend_array.push(legend_object)

      
    });

    var lg = getLetterGrade(99);
    console.log(lg);
    calculateCLE();
    calculateReading();
    calculateMath();
    calculateMape();


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

function getLetterGrade(finalgrade) {
      lettergrade = "";
      legend_array.forEach(function (object,index) {
        // console.log(object.lgrade);
        grade = object.graderange;
        if (grade.indexOf('-') > -1)
        {
          var gradearray = grade.split('-');
          temp = gradearray[0];
          range1 = temp.trim();
          temp = gradearray[1];
          range2 = temp.trim();
          if (finalgrade >= range1 && finalgrade <= range2) {
            console.log('in range');
            lettergrade = object.lgrade;
            // break;
            return lettergrade;
          }
        } else {
          var gradearray = grade.split('and');
          temp = gradearray[0];
          range1 = temp.trim();
          if (finalgrade <= range1) {
            console.log('in range');
            lettergrade = object.lgrade;
            return lettergrade;
          }
        }
      });
      // console.log(lettergrade);
      return lettergrade;
}

function calculateSum2($i) {
    var sum = 0,
    i = $i;
    //iterate through each textboxes and add the values
    $(".grade"+ i).each(function() {
        //add only if the value is number
        if (!isNaN(this.value) && this.value.length != 0) {
            sum += parseFloat(this.value)/ "2";
            ;
        }
        else if (this.value.length != 0){
            $(this).css("background-color", "red");
        }
    });
//  if(sum < 75){
//   $("input#action"+ i).val("FAILED");
//  }else{
//   $("input#action"+ i).val("PASSED");

//  }
  $("input#fin"+i).val(sum);
}

function calculateCLE() {
  var sum = 0,
  i=0,
  ave_cle1=0,
  ave_cle2=0,
  average=0;
  $('#Tablesample tr').each(function() {
    i=i+1;
    var keval = $(this).find(".1cle input").val();
    if(jQuery.type(keval) === "undefined"){
    //Some code goes here
    } else {
      sum += parseFloat(keval);
    // console.log(i);
    console.log(keval);
    }

  });
  average = sum / 4;
  ave_cle1 = average;
  $("input#subject_A1").val(average);

  sum=0;
  average=0;
  $('#Tablesample tr').each(function() {
    i=i+1;
    var keval = $(this).find(".2cle input").val();
    if(jQuery.type(keval) === "undefined"){
    //Some code goes here
    } else {
      sum += parseFloat(keval);
    // console.log(i);
    console.log(keval);
    }

  });
  average = sum / 4;
  ave_cle2 = average
  $("input#subject_A2").val(average);
  // console.log(sum);
  var final_average=0;

  final_average = (ave_cle1 + ave_cle2) / 2;
  $("input#final_A").val(final_average);

  var lettergrade = getLetterGrade(final_average);
  $("input#lgA").val(lettergrade);

}

function calculateReading() {
  var sum = 0,
  i=0,
  decoding1=0,
  decoding2=0,
  comp1=0,
  comp2=0,
  oral1=0,
  oral2=0,
  average=0;
  // Decoding Skills
  $('#Tablesample tr').each(function() {
    i=i+1;
    var keval = $(this).find(".1reading1 input").val();
    if(jQuery.type(keval) === "undefined"){
    //Some code goes here
    } else {
      sum += parseFloat(keval);
    // console.log(i);
    console.log(keval);
    }

  });
  average = sum / 6;
  decoding1=average;
  $("input#1_5").val(average);

  sum=0;
  average=0;
  $('#Tablesample tr').each(function() {
    i=i+1;
    var keval = $(this).find(".2reading1 input").val();
    if(jQuery.type(keval) === "undefined"){
    //Some code goes here
    } else {
      sum += parseFloat(keval);
    // console.log(i);
    console.log(keval);
    }

  });
  average = sum / 6;
  decoding2=average;
  $("input#2_5").val(average);
  // console.log(sum);



  // Comprehension Skills
  // sub level

  sum=0;
  average=0;
  $('#Tablesample tr').each(function() {
    i=i+1;
    var keval = $(this).find(".1reading2g input").val();
    if(jQuery.type(keval) === "undefined"){
    //Some code goes here
    } else {
      sum += parseFloat(keval);
    // console.log(i);
    console.log(keval);
    }

  });
  average = sum / 2;
  $("input#1_19").val(average);

  sum=0;
  average=0;
  $('#Tablesample tr').each(function() {
    i=i+1;
    var keval = $(this).find(".2reading2g input").val();
    if(jQuery.type(keval) === "undefined"){
    //Some code goes here
    } else {
      sum += parseFloat(keval);
    // console.log(i);
    console.log(keval);
    }

  });
  average = sum / 2;
  $("input#2_19").val(average);

  // main

  sum=0;
  average=0;
  $('#Tablesample tr').each(function() {
    i=i+1;
    var keval = $(this).find(".1reading2 input").val();
    if(jQuery.type(keval) === "undefined"){
    //Some code goes here
    } else {
      sum += parseFloat(keval);
    // console.log(i);
    console.log(keval);
    }

  });
  average = sum / 6;
  comp1=average;
  $("input#1_12").val(average);

  sum=0;
  average=0;
  $('#Tablesample tr').each(function() {
    i=i+1;
    var keval = $(this).find(".2reading2 input").val();
    if(jQuery.type(keval) === "undefined"){
    //Some code goes here
    } else {
      sum += parseFloat(keval);
    // console.log(i);
    console.log(keval);
    }

  });
  average = sum / 6;
  comp2=average;
  $("input#2_12").val(average);

  // Oral Communication Skills
  sum=0;
  average=0;
  $('#Tablesample tr').each(function() {
    i=i+1;
    var keval = $(this).find(".1reading3 input").val();
    if(jQuery.type(keval) === "undefined"){
    //Some code goes here
    } else {
      sum += parseFloat(keval);
    // console.log(i);
    console.log(keval);
    }

  });
  average = sum / 8;
  oral1=average;
  $("input#1_22").val(average);

  sum=0;
  average=0;
  $('#Tablesample tr').each(function() {
    i=i+1;
    var keval = $(this).find(".2reading3 input").val();
    if(jQuery.type(keval) === "undefined"){
    //Some code goes here
    } else {
      sum += parseFloat(keval);
    // console.log(i);
    console.log(keval);
    }

  });
  average = sum / 8;
  oral2=average;
  $("input#2_22").val(average);

  var ave_reading1=0,
  ave_reading2=0,
  final_average =0;

  ave_reading1=(decoding1 + comp1 + oral1) / 3;
  $("input#subject_B1").val(ave_reading1);
  ave_reading2=(decoding2 + comp2 + oral2) / 3;
  $("input#subject_B2").val(ave_reading2);
  final_average = (ave_reading1 + ave_reading2) / 2;
  $("input#final_B").val(final_average);

  var lettergrade = getLetterGrade(final_average);
  $("input#lgB").val(lettergrade);
}

function calculateMath() {
  var sum = 0,
  i=0,
  ave_math1=0,
  ave_math2=0
  average=0;

  // sub level
  sum=0;
  average=0;
  $('#Tablesample tr').each(function() {
    i=i+1;
    var keval = $(this).find(".1math_e input").val();
    if(jQuery.type(keval) === "undefined"){
    //Some code goes here
    } else {
      if (keval.trim() === '') {
        keval="0";
      }
      sum += parseFloat(keval);
    // console.log(i);
    console.log(keval);
    }

  });
  average = sum / 2;
  $("input#1_36").val(average);

  sum=0;
  average=0;
  $('#Tablesample tr').each(function() {
    i=i+1;
    var keval = $(this).find(".2math_e input").val();
    if(jQuery.type(keval) === "undefined"){
    //Some code goes here
    } else {
      if (keval.trim() === '') {
        keval="0";
      }
      sum += parseFloat(keval);
    // console.log(i);
    console.log(keval);
    }

  });
  average = sum / 2;
  $("input#2_36").val(average);


  var indexval=0;
  // main
  sum=0;
  average=0;
  $('#Tablesample tr').each(function() {
    i=i+1;
    
    console.log(indexval);
    var keval = $(this).find(".1math input").val();
    if(jQuery.type(keval) === "undefined"){
    //Some code goes here
    } else {
        indexval+=1;
      if (keval.trim() === '') {
        keval="0";
      }
      sum += parseFloat(keval);
    // console.log(i);
    console.log(keval);
    }

  });
  average = sum / 8;
  ave_math1 = average;
  $("input#1_31").val(average);
  $("input#subject_C1").val(average);

  sum=0;
  average=0;
  indexval=0;
  $('#Tablesample tr').each(function() {
    i=i+1;
    
    console.log(indexval);
    var keval = $(this).find(".2math input").val();
    if(jQuery.type(keval) === "undefined"){
    //Some code goes here
    } else {
        indexval+=1;
      if (keval.trim() === '') {
        keval="0";
      }      
      sum += parseFloat(keval);
    // console.log(i);
    console.log(keval);
    }

  });
  average = sum / 8;
  ave_math2 = average;
  $("input#2_31").val(average);
  $("input#subject_C2").val(average);
  var final_average = (ave_math1 + ave_math2) / 2;
  $("input#final_C").val(final_average);

  var lettergrade = getLetterGrade(final_average);
  $("input#lgC").val(lettergrade);
  // console.log(sum);  
}

function calculateMape() {
  var sum = 0,
  i=0,
  ave_music1=0,
  ave_music2=0,
  ave_arts1=0,
  ave_arts2=0,
  ave_fms1=0,
  ave_fms2=0,
  ave_gms1=0,
  ave_gms2=0,

  average=0;
    // music
  $('#Tablesample tr').each(function() {
    i=i+1;
    var keval = $(this).find(".1mape1 input").val();
    if(jQuery.type(keval) === "undefined"){
    //Some code goes here
    } else {
      sum += parseFloat(keval);
    // console.log(i);
    console.log(keval);
    }

  });

  average = sum / 3;
  ave_music1 = average;
  $("input#1_42").val(average);

  sum=0;
  average=0;
  $('#Tablesample tr').each(function() {
    i=i+1;
    var keval = $(this).find(".2mape1 input").val();
    if(jQuery.type(keval) === "undefined"){
    //Some code goes here
    } else {
      sum += parseFloat(keval);
    // console.log(i);
    console.log(keval);
    }

  });
  average = sum / 3;
  ave_music2 = average;
  $("input#2_42").val(average);
  // // console.log(sum);

  // arts
  sum=0;
  average=0;
  $('#Tablesample tr').each(function() {
    i=i+1;
    var keval = $(this).find(".1mape2 input").val();
    if(jQuery.type(keval) === "undefined"){
    //Some code goes here
    } else {
      sum += parseFloat(keval);
    // console.log(i);
    console.log(keval);
    }

  });
  average = sum / 3;
  ave_arts1 = average;
  $("input#1_46").val(average);

  sum=0;
  average=0;
  $('#Tablesample tr').each(function() {
    i=i+1;
    var keval = $(this).find(".2mape2 input").val();
    if(jQuery.type(keval) === "undefined"){
    //Some code goes here
    } else {
      sum += parseFloat(keval);
    // console.log(i);
    console.log(keval);
    }

  });
  average = sum / 3
  ave_arts2 = average;
  $("input#2_46").val(average);

  sum=0;
  average=0;
  $('#Tablesample tr').each(function() {
    i=i+1;
    var keval = $(this).find(".1mape3a input").val();
    if(jQuery.type(keval) === "undefined"){
    //Some code goes here
    } else {
      sum += parseFloat(keval);
    // console.log(i);
    console.log(keval);
    }

  });
  average = sum / 6;
  ave_fms1 = average;
  $("input#1_51").val(average);

  sum=0;
  average=0;
  $('#Tablesample tr').each(function() {
    i=i+1;
    var keval = $(this).find(".2mape3a input").val();
    if(jQuery.type(keval) === "undefined"){
    //Some code goes here
    } else {
      sum += parseFloat(keval);
    // console.log(i);
    console.log(keval);
    }

  });
  average = sum / 6;
  ave_fms2 = average;
  $("input#2_51").val(average);

  sum=0;
  average=0;
  $('#Tablesample tr').each(function() {
    i=i+1;
    var keval = $(this).find(".1mape3b input").val();
    if(jQuery.type(keval) === "undefined"){
    //Some code goes here
    } else {
      sum += parseFloat(keval);
    // console.log(i);
    console.log(keval);
    }

  });
  average = sum / 8;
  ave_gms1 = average;
  $("input#1_58").val(average);

  sum=0;
  average=0;
  $('#Tablesample tr').each(function() {
    i=i+1;
    var keval = $(this).find(".2mape3b input").val();
    if(jQuery.type(keval) === "undefined"){
    //Some code goes here
    } else {
      sum += parseFloat(keval);
    // console.log(i);
    console.log(keval);
    }

  });
  average = sum / 8;
  ave_gms2 = average;
  $("input#2_58").val(average);

  var ave_psydev1 =0,
  ave_psydev2=0,
  ave_mape1=0,
  ave_mape2=0,
  final_average=0;

  ave_psydev1 = (ave_fms1 + ave_gms1) / 2;
  $("input#1_50").val(ave_psydev1);
  ave_psydev2 = (ave_fms2 + ave_gms2) / 2;
  $("input#2_50").val(ave_psydev2);

  ave_mape1 =(ave_music1 + ave_arts1 + ave_psydev1) / 3;
 
  $("input#subject_D1").val(ave_mape1);
  ave_mape2 =(ave_music2 + ave_arts2 + ave_psydev2) / 3;
  $("input#subject_D2").val(ave_mape2);

  final_average = (ave_mape1 + ave_mape2) / 2;
  $("input#final_D").val(final_average);

  var lettergrade = getLetterGrade(final_average);
  $("input#lgD").val(lettergrade);
}

function calculateColumn2() {
  // var result =  filter([".philips", ".h4"]);
  //       alert(result);
        var result_2 = filter(".1A");
        // alert(result_2);
        console(result2);
}

function filter(params) {
        var select = "tr";

        // for (var i = 0; i < params.length; i++) {
        //     select += ":has(" + params[i] + ")";
        // }
       return $("tr").has("td").has(filter);

        // return $(select).map(
        //       function () { 
        //           return $(this).attr('id');
        //       }
        // ).get();
    }

function calculateSum3($classname,$i,$subject) {
    var sum = 0,
    i = $i,
    subject_class = $subject;
    var cname = $classname;

    //iterate through each textboxes and add the values
    $("."+cname).each(function() {
        //add only if the value is number
        if (!isNaN(this.value) && this.value.length != 0) {
            sum += parseFloat(this.value)/ "2";
            ;
        }
        else if (this.value.length != 0){
            $(this).css("background-color", "red");
        }
    });
//  if(sum < 75){
//   $("input#action"+ i).val("FAILED");
//  }else{
//   $("input#action"+ i).val("PASSED");

//  }
// alert(subject_class);


  $("input#fin"+i).val(sum);
  if (subject_class == "cle") {
        calculateCLE();
    } else if (subject_class.indexOf("reading") > -1) {
        calculateReading();
    } else if (subject_class.indexOf("math") > -1) {
        calculateMath();
    } else if (subject_class.indexOf("mape") > -1) {
        calculateMape();
    }
}

function calculateBySubject($classname,$i) {
    var sum = 0,
    i = $i;
    var cname = $classname;

    //iterate through each textboxes and add the values
    $("."+cname).each(function() {
        //add only if the value is number
        if (!isNaN(this.value) && this.value.length != 0) {
            sum += parseFloat(this.value)/ "2";
            ;
        }
        else if (this.value.length != 0){
            $(this).css("background-color", "red");
        }
    });
//  if(sum < 75){
//   $("input#action"+ i).val("FAILED");
//  }else{
//   $("input#action"+ i).val("PASSED");

//  }
  $("input#final_" + i).val(sum);
}
</script>

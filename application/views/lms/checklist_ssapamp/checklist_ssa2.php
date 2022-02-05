<div class="content-wrapper" style="min-height: 946px;">
    <!-- Main content -->
    <section class="content" >
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>

                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>

                    <div class="box-body">    
                        <form role="form" action="<?php echo site_url('lms/checklist_ssapamp') ?>" method="post" class="">
                            <div class="row">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <input type="hidden" name="level_classid" id="level_classid" value="<?php echo $level_classid ?>">
                                <div class="col-sm-6 col-md-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('current_session'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="session_id" name="session_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($session_list as $session) {
                                                ?>
                                                <option value="<?php echo $session['id'] ?>" <?php if ($session['id'] == $sch_setting->session_id) echo "selected=selected" ?>><?php echo $session['session'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('session_id'); ?></span>
                                    </div>
                                </div>      

                                <div class="col-sm-6 col-md-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('quarter'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="quarter_id" name="quarter_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($quarter_list as $quarter) {
                                                ?>
                                                <option value="<?php echo $quarter['id'] ?>" <?php if (set_value('quarter_id') == $quarter['id']) echo "selected=selected" ?>><?php echo $quarter['description'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('quarter_id'); ?></span>
                                    </div>
                                </div>                            

                                <div class="col-sm-6 col-md-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $class) {
                                                ?>
                                                <option value="<?php echo $class['id'] ?>" <?php echo ($level_classid == $class['id']) ?  "selected=selected" :  "disabled"?>><?php echo $class['class'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div> 

                                <!-- <div class="col-sm-6 col-md-2">
                                    <div class="form-group">  
                                        <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                        <select  id="section_id" name="section_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>  
                                </div>   -->

                                <div class="col-sm-6 col-md-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="section_id" name="section_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($section_list as $section) {
                                                ?>
                                                <option value="<?php echo $section['section_id'] ?>" <?php if (set_value('section_id') == $section['section_id']) echo "selected=selected" ?>><?php echo $section['section'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>                                
                                
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('student'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="student_id" name="student_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('student_id'); ?></span>
                                    </div>
                                </div>                                  
                                
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('show'); ?></button>
                                    </div>
                                </div>
                            </div><!--./row-->     
                        </form>
                    </div><!--./box-body-->    
            
                    <div class="">
                        <form id='frm_conduct_grades' action="<?php echo site_url('') ?>"  method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <!-- submit hidden values -->
                            <input type="hidden" name="session_id" value="<?php echo $session_id ?>">
                            <input type="hidden" name="quarter_id" value="<?php echo $quarter_id ?>">
                            <input type="hidden" name="quarterid" id="quarterid" value="<?php echo $quarter_id ?>">
                            <input type="hidden" name="class_id" value="<?php echo $class_id ?>">
                            <input type="hidden" name="section_id" value="<?php echo $section_id ?>">
                            <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                            <div class="box-header ptbnull"></div> 
                            <div class="box-header ptbnull">
                                <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo form_error('class_record_quarterly'); ?> Student Checklist</h3>
                            </div>
                            <div class="box-body table-responsive">
                                <?php if (isset($resultlist)) {?>
                                    <section class="content-header">
                                            <h1><i class="fa fa-calendar-times-o"></i> <?php echo $this->lang->line('grades'); ?> </h1>
                                        </section>
                                        <!-- Main content -->
                                        <section class="content">
                                            <div class="row">                                                
                                                <div class="col-md-12">
                                                    <div class="box box-warning">
                                                        <div class="box-header ptbnull">
                                                            <h3 class="box-title titlefix"> <?php echo ""; ?></h3>
                                                            <div class="box-tools pull-right"></div>
                                                        </div>
                                                        <div class="box-body">
                                                            <div class="table-responsive">
                                                                <?php if (!empty($resultlist)) { ?>
                                                                    <table class="table table-striped table-bordered table-hover example nowrap" cellspacing="0" width="100%" id="Tablesample">
                                                                        <thead>
                                                                            <tr>						
                                                                                <th></th>		
                                                                                <th colspan="4"><center>PERIODIC RATINGS</center></th>
                                                                            </tr>                                                                            
                                                                            <tr>						
                                                                                <th></th>		
                                                                                <th><center>1st</center></th>									
                                                                                <th><center>LG</center></th>		
                                                                                <th><center>2nd</center></th>
                                                                                <th><center>LG</center></th>		
                                                                                <th><center>FINAL</center></th>
                                                                                <th><center>LG</center></th>
                                                                                <!-- <th><center>VARS</center></th> -->
                                                                            </tr>
                                                                        </thead>
                                                                        <!-- <tbody> -->
                                                                                <?php
                                                                                $subjectctr=0;
                                                                                foreach($Subjects as $row) {
                                                                                    $subjectctr+=1;
                                                                                    if ($subjectctr>1) {
                                                                                        echo "<tr>";
                                                                                        echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
                                                                                        echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
                                                                                        echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
                                                                                        echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
                                                                                        echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
                                                                                        echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
                                                                                        // echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
                                                                                        echo "</tr>";
                                                                                      }
                                                                                ?>
                                                                                    <tr>
                                                                                    <td><b><?php echo $row->alpha . '.' . $row->checklistname;?></b></td>
                                                                                    <?php                                                                                       
                                                                                        $no_of_levels=$row->totallevels;
                                                                                        if ($row->id == 5)
                                                                                        {
                                                                                          foreach ($writting as $subjectrec) {
                                                                                            $cdid=$subjectrec->id;
                                                                                          }

                                                                                          $ptr=0;

                                                                                          foreach ($db_writting as $datarecord) {
                                                                                            if ($ptr>0) {
                                                                                              $p1 = $datarecord['period1'];
                                                                                              $p2 = $datarecord['period2'];
                                                                                              $fg = $datarecord['finalgrade'];
                                                                                              $tclass =$datarecord['class'];
                                                                                              $ssid =  $datarecord['ssid'];
                                                                                              
                                                                                            }
                                                                                            $ptr++;
                                                                                          }

                                                                                          // if ($quarterid==1) {
                                                                                            $fg="";
                                                                                          // }

                                                                                          $tdclass = $row->alpha;
                                                                                          $class = "grade" . $tclass . $cdid;
                                                                                    ?>
                                                                                    <input type="hidden" id="ccid<?php echo $cdid;?>" name="clid[]" value="<?php echo $cdid ?>" >  
                                                                                    <input type="hidden" id="ssid<?php echo $cdid;?>" name="ssid[]" value="<?php echo $ssid ?>" >  
                                                                                    <td class="1<?php echo $tclass;?>"><center><input type="text" style="border: none; text-align:center" name="1st[]" id="1_<?php echo $class ?>" value="<?php echo $p1;?>" onchange="calculateSum3('<?php echo $class ?>',<?php echo $cdid ?>,'<?php echo $row->groupclass;?>')" class="<?php echo $class ?>" <?php if ($quarter_id=="2") echo "readonly";?>></center></td>
                                                                                    <td class="text-center"><label id="lgrade1_<?php echo $cdid ?>"></label></td>
                                                                                    <td class="2<?php echo $tclass;?>"><center><input type="text" style="border: none; text-align:center" name="2nd[]" id="2_<?php echo $class ?>" value="<?php echo $p2;?>" onchange="calculateSum3('<?php echo $class ?>',<?php echo $cdid ?>,'<?php echo $row->groupclass;?>')" class="<?php echo $class ?>" <?php if ($quarter_id=="1") echo "readonly";?>></center></td>
                                                                                    <td class="text-center"><label id="lgrade2_<?php echo $cdid ?>"></label></td>
                                                                                    <td class="text-center"><center><input type="text" style="border: none; text-align:center" name="final[]" value="<?php echo $fg;?>" id="fin<?php echo $cdid ?>" readonly disabled="disabled"></center></td>
                                                                                    <td class="text-center"><center><input type="text" style="border: none; text-align:center" name="lg[]" value="<?php echo "";?>" id="lg<?php echo $cdid ?>" readonly disabled="disabled"></center></td>  
                                                                                    <?php
                                                                                        } else {
                                                                                    ?>
                                                                                    <td><center><input type="text" style="border: none; text-align:center" name="<?php echo $row->alpha ;?>1" id="subject_<?php echo $row->alpha ?>1" readonly disabled="disabled" class="subject_<?php echo $row->alpha ?>"></center></td>
                                                                                    <td class="text-center"><label id="lgrade1_<?php echo $row->alpha ?>"></label></td>
                                                                                    <td><center><input type="text" style="border: none; text-align:center" name="<?php echo $row->alpha ;?>2" id="subject_<?php echo $row->alpha ?>2" readonly disabled="disabled" class="subject_<?php echo $row->alpha ?>"></center></td>
                                                                                    <td class="text-center"><label id="lgrade2_<?php echo $row->alpha ?>"></label></td>
                                                                                    <td class="text-center"><center><input type="text" style="border: none; text-align:center" name="<?php echo $row->alpha ;?>final" id="final_<?php echo $row->alpha ?>" readonly disabled="disabled"></center></td>
                                                                                    <td class="text-center"><center><input type="text" style="border: none; text-align:center" name="<?php echo $row->alpha ;?>lg" id="lg<?php echo $row->alpha ?>" readonly disabled="disabled"></center></td>
                                                                                    
                                                                                    <?php    } ?>
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
                                                                                            } else if ($row->id == 4) {
                                                                                              $list=$mape;
                                                                                              $gradelist = array();
                                                                                              $gradelist = $db_mape;
                                                                                            } else {
                                                                                              $list=$writting;
                                                                                              $gradelist = array();
                                                                                              $gradelist = $db_writting;
                                                                                              $no_of_levelrecs=count($writting);
                                                                                            }
                                                                                            
                                                                                            foreach ($list as $row1) {
                                                                                                if ($row1->mainsub=="M" and $row1->graded == "0")
                                                                                                {
                                                                                                echo "<tr>";
                                                                                                echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
                                                                                                echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
                                                                                                echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
                                                                                                echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
                                                                                                echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
                                                                                                echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
                                                                                                // echo "<td>" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "</td>";
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
                                                                                                }
                                                                                                elseif ($row1->mainsub=="M")
                                                                                                {
                                                                                                    $label=$row1->itemseq . "." .  $row1->detail;
                                                                                                    $prefix = $row->alpha;
                                                                                                }
                                                                                            ?> 
                                                                                            <?php 
                                                                                            // if ($no_of_levelrecs==1) {
                                                                                            // } else {
                                                                                            ?> 
                                                                                            <?php if ($no_of_levelrecs==1) { 
                                                                                            } else {
                                                                                            ?>                                                                                          
                                                                                            <td><?php echo $label;?></td>
                                                                                            <input type="hidden" name="tg_id[]" value="<?php echo $row1->id ?>" >    
                                                                                            <?php } ?> 
                                                                                            <!-- testing 123                                                                                                                                                                                                                                                           -->
                                                                                        <?php
                                                                                            if ($row1->graded == "1")
                                                                                            {
                                                                                            $ccid = $row1->id;
                                                                                            if ($row1->mainsub=="M") {         
                                                                                                $tdclass = $row->alpha;
                                                                                            } else {
                                                                                                $tdclass = $prefix . $row1->id;
                                                                                            }      
                                                                                            $key = array_search($ccid, array_column($gradelist, 'clid'));
                                                                                            if ($key) {
                                                                                                $ssid =  $gradelist[$key]['ssid'];
                                                                                                $p1 = $gradelist[$key]['period1'];
                                                                                                if ($p1==0)
                                                                                                {
                                                                                                  $p1="";
                                                                                                }
                                                                                                $p2 =$gradelist[$key]['period2'];
                                                                                                if ($p2==0)
                                                                                                {
                                                                                                  $p2="";
                                                                                                }
                                                                                                $fg =$gradelist[$key]['finalgrade'];
                                                                                                if ($fg==0)
                                                                                                {
                                                                                                  $fg="";
                                                                                                }
                                                                                                $tclass =$gradelist[$key]['class'];
                                                                                                $class = "grade" . $tclass . $row1->id;
                                                                                                // $x = $prefix[strlen($prefix)-1];
                                                                                                // if ($x == "_")

                                                                                                
                                                                                            }
                                                                                            else {
                                                                                                $ssid=0;
                                                                                                $p1="";
                                                                                                $p2="";
                                                                                                $fg="";
                                                                                                $class="";
                                                                                                $tdclass = "";
                                                                                            }                                                                                                
                                                                                        ?>  
                                                                                        <?php
                                                                                          if ($no_of_levelrecs==1) {

                                                                                          } else {
                                                                                        ?>
                                                                                            <!-- zone 1 -->
                                                                                              <input type="hidden" id="ccid<?php echo $row1->id;?>" name="clid[]" value="<?php echo $ccid ?>" >  
                                                                                              <input type="hidden" id="ssid<?php echo $row1->id;?>" name="ssid[]" value="<?php echo $ssid ?>" >  
                                                                                              <input type="hidden" name="ff" value="<?php echo $tdclass ?>" >  
                                                                                              <input type="hidden" name="ff" value="<?php echo $row1->groupclass ?>" >
                                                                                              <td class="1<?php echo $tclass;?>"><center><input type="text" style="border: none; text-align:center" name="1st[]" value="<?php echo $p1;?>" onchange="calculateSum3('<?php echo $class ?>',<?php echo $row1->id ?>,'<?php echo $row->groupclass;?>')" class="<?php echo $class ?>" <?php if ($quarter_id=="2") echo "readonly";?>></center></td>
                                                                                              <td class="text-center"><label id="lgrade1_<?php echo $row1->id ?>"></label></td>
                                                                                              <td class="2<?php echo $tclass;?>"><center><input type="text" style="border: none; text-align:center" name="2nd[]" value="<?php echo $p2;?>" onchange="calculateSum3('<?php echo $class ?>',<?php echo $row1->id ?>,'<?php echo $row->groupclass;?>')" class="<?php echo $class ?>" <?php if ($quarter_id=="1") echo "readonly";?>></center></td>
                                                                                              <td class="text-center"><label id="lgrade2_<?php echo $row1->id ?>"></label></td>
                                                                                              <td class="text-center"><center><input type="text" style="border: none; text-align:center" name="final[]" value="<?php echo $fg;?>" id="fin<?php echo $row1->id ?>" readonly disabled="disabled"></center></td>
                                                                                              <td class="text-center"><center><input type="text" style="border: none; text-align:center" name="lg[]" value="<?php echo "";?>" id="lg<?php echo $row1->id ?>" readonly disabled="disabled"></center></td>  
                                                                                              <!-- <td><b><?php echo $row1->id . '-' . $tclass . ' - ' . $row->groupclass . ' - ' . $class;?></b></td> -->
                                                                                              <?php 
                                                                                                }
                                                                                              ?>
                                                                                              <?php
                                                                                              } else {
                                                                                                  // not graded
                                                                                                  if (($row1->mainsub=="M") or ($row1->mainsub=="S")) {
                                                                                                      if ($row1->mainsub=="M") {
                                                                                                        // $t_d_class = "_" . $row1->id;
                                                                                                        $t_d_class = "_" . $row1->groupclass;
                                                                                                        $id_class = $row1->groupclass;
                                                                                                      } elseif ($row1->mainsub=="S") {
                                                                                                        // $t_d_class = $row1->groupclass;
                                                                                                        $t_d_class = $row->groupclass;
                                                                                                        $id_class = $row->groupclass;
                                                                                                      }
                                                                                              ?>
                                                                                                  <td class="1<?php echo $t_d_class ?>"><center><input type="text" style="border: none; text-align:center" name="1st1[]" id="1_<?php echo $id_class ?>" readonly disabled="disabled"></center></td>
                                                                                                  <td class="text-center"><label id="lgrade1_<?php echo $id_class ?>"></label></td>
                                                                                                  <td class="2<?php echo $t_d_class ?>"><center><input type="text" style="border: none; text-align:center" name="2nd1[]" id="2_<?php echo $id_class ?>" readonly disabled="disabled"></center></td>
                                                                                                  <td class="text-center"><label id="lgrade2_<?php echo $id_class ?>"></label></td>
                                                                                                  <td class="text-center"><center><input type="text" style="border: none; text-align:center" name="final1[]" readonly disabled="disabled"></center></td>
                                                                                                  <td class="text-center"><center><input type="text" style="border: none; text-align:center" name="lg1[]"  readonly disabled="disabled"></center></td>   
                                                                                                  <!-- <td><b><?php echo $t_d_class . ' - ' . $id_class;?></b></td> -->
                                                                                              <?php
                                                                                                  }
                                                                                              }
                                                                                              ?>                                                                                             


                                                                                        <?php
                                                                                            } //foreach ($list as $row1) 
                                                                                        ?>                         
                                                                                <?php
                                                                                }
                                                                                ?>   
                                                                                <!-- foreach($Subjects as $row)                                                                                          -->
                                                                        <!-- </tbody> -->
                                                                    </table>

                                                                                                                 
                                                                <?php
                                                                } ?>   
                                                                <!-- if (!empty($resultlist))                          -->
                                                            </div>                                                                          
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if (!empty($resultlist)) { ?>
                                                <div class="col-md-3">
                                                    <div class="box box-primary">
                                                        <div class="box-body box-profile">
                                                            <h3 class="profile-username text-center">LEGEND</h3>
                                                            <ul class="list-group list-group-unbordered">
                                                                <?php foreach($legend_list as $content) { ?>
                                                                        <li class="list-group-item">
                                                                            <b><?php echo $content['letter_grade']; ?></b> <span class="pull-right"><?php echo $content['grade_description'] . ' ' . $content['description']; ?></span>                                                                            
                                                                        </li>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php } ?>

                                            </div>
                                            <div class="row">
                                                <div class="box-footer">
                                                    <button type="submit" name="action" value="save_views" class="btn btn-primary pull-right submitviews" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Updating"><i class="fa fa-save"></i> <?php echo "Save"; ?></button>
                                                </div>
                                            </div>
                                        </section>
                                <?php } ?>
                            </div>
                        </form>
                    </div>  
                </div>
            </div> <!-- ./col-md-12 -->        
        </div>  
    </section>
</div>

<script type="text/javascript">
var class_id;
var base_url = '<?php echo base_url() ?>';

    function getSectionByClass(class_id, section_id) {
        if (class_id != "" && section_id != "") {
            $('#section_id').html("");
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                beforeSend: function () {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                },
                complete: function () {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        }
    }   


   function getStudentsByClassSection(class_id, section_id, school_year_id, student_id) {
      if (class_id != "") {
        
         $('#student_id').html("");
         if (class_id == 1) {
         var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
         $.ajax({
            type: "GET",
            url: base_url + "student/getStudentListPerClassSection",
            data: {
               'class_id': class_id,
               'section_id': section_id,
               'school_year_id': school_year_id
            },
            dataType: "json",
            beforeSend: function() {
               $('#student_id').addClass('dropdownloading');
            },
            success: function(data) {
               $.each(data, function(i, obj) {
                  var sel = "";
                  if (student_id == obj.student_id) {
                     sel = "selected";
                  }
                  div_data += "<option value=" + obj.student_id + " " + sel + ">" + obj.lastname + ", " + obj.firstname + "</option>";
               });
               $('#student_id').append(div_data);
            },
            complete: function() {
               $('#student_id').removeClass('dropdownloading');
            }
         });
        }
      }
   }        
    
    $(document).ready(function () {
        var table = $('.conductTable').DataTable({
            "aaSorting": [],           
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            // pageLength: 100,
            //responsive: 'false',
            paging: false,
            ordering: false,
            searching: false,
            dom: "Bfrtip",
            buttons: [
                {
                    extend: 'copyHtml5',
                    text: '<i class="fa fa-files-o"></i>',
                    titleAttr: 'Copy',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel',
                   
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'csvHtml5',
                    text: '<i class="fa fa-file-text-o"></i>',
                    titleAttr: 'CSV',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'PDF',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'
                        
                    }
                },

                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i>',
                    titleAttr: 'Print',
                    title: $('.download_label').html(),
                        customize: function ( win ) {
                    $(win.document.body)
                        .css( 'font-size', '10pt' );
 
                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
                },
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'colvis',
                    text: '<i class="fa fa-columns"></i>',
                    titleAttr: 'Columns',
                    title: $('.download_label').html(),
                    postfixButtons: ['colvisRestore']
                },
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                }
            ]
        });

        var class_id = $('#class_id').val();
        class_id =  $('#level_classid').val();
        var section_id =  '<?php echo set_value('section_id') ?>';
        var school_year_id = '<?php echo set_value('session_id') ?>';
        var student_id = '<?php echo set_value('student_id') ?>';
        getSectionByClass(class_id, section_id);
        getStudentsByClassSection(class_id, section_id, school_year_id, student_id);

        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            class_id = $(this).val();
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: { 'class_id': class_id },
                dataType: "json",
                beforeSend: function () {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                },
                complete: function () {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        });

        $(document).on('change', '#section_id', function(e) {
         $('#student_id').html("");
         var div_data2 = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        //  alert($('#level_classid').val());
        //  alert($('#section_id').val());
        //  alert($('#session_id').val());
         $.ajax({
            type: "GET",
            url: base_url + "student/getStudentListPerClassSection",
            data: {
               'class_id': $('#level_classid').val(),
               'section_id': $('#section_id').val(),
               'school_year_id': $('#session_id').val()
            },
            dataType: "json",
            beforeSend: function() {
               $('#student_id').addClass('dropdownloading');
            },
            success: function(data) {
               $.each(data, function(i, obj) {
                  div_data2 += "<option value=" + obj.student_id + ">" + obj.lastname + ", " + obj.firstname + "</option>";
               });
               $('#student_id').append(div_data2);
            },
            complete: function() {
               $('#student_id').removeClass('dropdownloading');
            }
         });
      });        

        $("#frm_conduct_grades").on('submit', (function (e) {
            e.preventDefault();
            var $this = $('.submitviews');
            $this.button('loading');

            var frmdata = new FormData(this);

            $.ajax({
                url: "<?php echo site_url("lms/conduct/save_conduct_grades_numeric") ?>",
                type: "POST",
                data: frmdata,
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    $this.button('loading');
                },
                success: function (res) {
                    if (res.status == "fail") {
                        var message = "";
                        $.each(res.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);

                    } else {
                        successMsg(res.message);
                        // window.location.reload(true);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                }, 
                complete: function (data) {
                    $this.button('reset');
                }
            });
        }));
    });
</script>
<script type="text/javascript">
    var legend_array = [];
    var url = "<?php echo base_url('lms/grading_checklist_ssapamp/') ?>";
    $(document).ready(function() {
        
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
        calculateWritting();
    });

    function getLetterGrade(finalgrade) {
          lettergrade = "";
          // rfinalgrade = Math.round(finalgrade);
          rfinalgrade = parseFloat(finalgrade);
          legend_array.forEach(function (object,index) {
            // console.log(object.lgrade);
            grade = object.graderange;
            if (grade.indexOf('-') > -1)
            {
              var gradearray = grade.split('-');
              temp = gradearray[0];
              temp = temp.trim();
              range1 = parseFloat(temp);//Math.round(temp);
              temp = gradearray[1];
              temp = temp.trim();
              range2 =parseFloat(temp);// Math.round(temp);
              range2 = range2 + 0.99;
              if (rfinalgrade >= range1 && rfinalgrade <= range2) {
                console.log('in range');
                lettergrade = object.lgrade;
                // break;
                return lettergrade;
              }
            } else {
              var gradearray = grade.split('and');
              temp = temp.trim();
              range2 = Math.round(temp);
              if (rfinalgrade <= range1) {
                console.log('in range');
                lettergrade = object.lgrade;
                return lettergrade;
              }
            }
          });
          // console.log(lettergrade);
          return lettergrade;
    }

    function calculateCLE() {
      var sum = 0,
      i=0,
      ave_cle1=0,
      ave_cle2=0,
      average=0;
      var defaultvalue="";
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

      var sub_lettergrade = "";
      if (isNaN(sum) || sum==0) {
        $("input#subject_A1").val(defaultvalue);
      } else {      
        average = sum / 4;
        ave_cle1 = average;
        $("input#subject_A1").val(average.toFixed(2));
        sub_lettergrade =  getLetterGrade(average);
        $("#lgrade1_A").text(sub_lettergrade);
      }

      sum=0;
      average=0;
      sub_lettergrade = "";
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
      if (isNaN(sum) || sum==0) {
        $("input#subject_A2").val(defaultvalue);
      } else {      
        average = sum / 4;
        ave_cle2 = average;
        $("input#subject_A2").val(average.toFixed(2));
        sub_lettergrade =  getLetterGrade(average);
        $("#lgrade2_A").text(sub_lettergrade);
      }
      
      // console.log(sum);
      var final_average=0;
      if (ave_cle1>0 && ave_cle2>0) {
        final_average = (ave_cle1 + ave_cle2) / 2;
        $("input#final_A").val(final_average.toFixed(2));

        var lettergrade = getLetterGrade(final_average);
        $("input#lgA").val(lettergrade);
      }
      

    }
    
    function calculateReading() {
      var sum = 0,
      i=0,
      decoding1=0,
      decoding2=0,
      comp1=0,
      comp2=0,
      subcomp1=0,
      subcomp2=0,
      totalcomp1=0,
      totalcomp2=0,
      oral1=0,
      oral2=0,
      average=0;
      var defaultvalue="";
      var sub_lettergrade = "";
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
      if (isNaN(sum)) {
        $("input#1_ds").val(defaultvalue);
      } else {  
        average = sum / 6;
        decoding1=average;
        $("input#1_ds").val(average.toFixed(2));
        sub_lettergrade =  getLetterGrade(average);
        $("#lgrade1_ds").text(sub_lettergrade);
      }
      
      sub_lettergrade = "";
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
      
      if (isNaN(sum)) {
        $("input#2_ds").val(defaultvalue);
      } else {  
        average = sum / 6;
        decoding2=average;
        $("input#2_ds").val(average.toFixed(2));
        sub_lettergrade =  getLetterGrade(average);
        $("#lgrade2_ds").text(sub_lettergrade);
      // console.log(sum);
      }

      // Comprehension Skills
      // sub level
      sub_lettergrade = "";
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
      if (isNaN(sum)) {
        $("input#1_reading").val(defaultvalue);
      } else {    
        average = sum / 2;
        $("input#1_reading").val(average.toFixed(2));
        subcomp1 = average;
      }

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
      if (isNaN(sum)) {
        $("input#2_reading").val(defaultvalue);
      } else {    
        average = sum / 2;
        $("input#2_reading").val(average.toFixed(2));
        subcomp2=average;
      }

      // main
      // Comprehension Skills

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
      sub_lettergrade="";
      var ave=0;
      if (isNaN(sum)) {
        $("input#1_cs").val(defaultvalue);
      } else {  
        totalcomp1 = sum + subcomp1;
        average = totalcomp1 / 7;
        comp1=average;
        // ave = roundOfGrade(average);
        $("input#1_cs").val(average.toFixed(2));
        sub_lettergrade =  getLetterGrade(average);
        $("#lgrade1_cs").text(sub_lettergrade);
      }

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
      
      sub_lettergrade="";
      if (isNaN(sum)) {
        $("input#2_cs").val(defaultvalue);
      } else {
        totalcomp2 = sum + subcomp2;
        average = totalcomp2 / 7;
        comp2=average;
        $("input#2_cs").val(average.toFixed(2));
        sub_lettergrade =  getLetterGrade(average);
        $("#lgrade2_cs").text(sub_lettergrade);
      }

      // Oral Communication Skills
      sub_lettergrade="";
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
      
      sub_lettergrade="";
      if (isNaN(sum)) {
        $("input#1_ocs").val(defaultvalue);
      } else {    
        average = sum / 8;
        oral1=average;
        $("input#1_ocs").val(average.toFixed(2));
        sub_lettergrade =  getLetterGrade(average);
        $("#lgrade1_ocs").text(sub_lettergrade);
      }

      sub_lettergrade="";
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
      if (isNaN(sum)) {
        $("input#2_ocs").val(defaultvalue);
      } else {    
        average = sum / 8;
        oral2=average;
        $("input#2_ocs").val(average.toFixed(2));
        sub_lettergrade =  getLetterGrade(average);
        $("#lgrade2_ocs").text(sub_lettergrade);
      }
      
      sub_lettergrade="";
      var ave_reading1=0,
      ave_reading2=0,
      final_average =0;
      $("input#subject_B1").val(defaultvalue);
      if (decoding1>1 && comp1>1 & oral1>1) {
        ave_reading1=(decoding1 + comp1 + oral1) / 3;
        $("input#subject_B1").val(ave_reading1.toFixed(2));
        sub_lettergrade =  getLetterGrade(ave_reading1);
        $("#lgrade1_B").text(sub_lettergrade);
      }

      // alert('1');
      sub_lettergrade="";
      $("input#subject_B2").val(defaultvalue);
      if (decoding2>1 && comp2>1 & oral2>1) {
        ave_reading2=(decoding2 + comp2 + oral2) / 3;
        $("input#subject_B2").val(ave_reading2.toFixed(2));
        sub_lettergrade =  getLetterGrade(ave_reading2);
        $("#lgrade2_B").text(sub_lettergrade);
      }
      // alert('2');
      $("input#final_B").val(defaultvalue);
      $("input#lgB").val(defaultvalue);
      if (ave_reading1>1 && ave_reading2>1) {
        final_average = (ave_reading1 + ave_reading2) / 2;
        $("input#final_B").val(final_average.toFixed(2));

        var lettergrade = getLetterGrade(final_average);
        $("input#lgB").val(lettergrade);
        // alert('3');
      }  

    }    

    function calculateMath() {
      var sum = 0,
      i=0,
      ave_math1=0,
      ave_math2=0
      average=0;
      var defaultvalue="";
      var sub_lettergrade="";
      // sub level
      sum=0;
      average=0;
      
      $('#Tablesample tr').each(function() {
        i=i+1;
        var keval = $(this).find(".1math_e input").val();
        if(jQuery.type(keval) === "undefined"){
        //Some code goes here
        } else {
          // if (keval.trim() === '') {
          //   keval="0";
          // }
          sum += parseFloat(keval);
        // console.log(i);
        console.log(keval);
        }

      });
      if (isNaN(sum) || sum==0) {
        $("input#1_math").val(defaultvalue);
      } else {
        average = sum / 2;
        $("input#1_math").val(average.toFixed(2));
      }

      sum=0;
      average=0;
      $('#Tablesample tr').each(function() {
        i=i+1;
        var keval = $(this).find(".2math_e input").val();
        if(jQuery.type(keval) === "undefined"){
        //Some code goes here
        } else {
          // if (keval.trim() === '') {
          //   keval="0";
          // }
          sum += parseFloat(keval);
        // console.log(i);
        console.log(keval);
        }

      });
      if (isNaN(sum) || sum==0) {
        $("input#2_math").val(defaultvalue);
      } else {
        average = sum / 2;
        $("input#2_math").val(average.toFixed(2));
      }

      // main
      sum=0;
      average=0;
      $('#Tablesample tr').each(function() {
        i=i+1;
        var keval = $(this).find(".1math input").val();
        if(jQuery.type(keval) === "undefined"){
        //Some code goes here
        } else {
          // if (keval.trim() === '') {
          //   keval="0";
          // }
          sum += parseFloat(keval);
        // console.log(i);
        console.log(keval);
        }

      });
      sub_lettergrade="";
      if (isNaN(sum) || sum==0) {
        $("input#1_cp").val(defaultvalue);
        $("input#subject_C1").val(defaultvalue);
      } else {
        average = sum / 8;
        ave_math1 = average;
        $("input#1_cp").val(average);
        $("input#subject_C1").val(average.toFixed(2));
        sub_lettergrade =  getLetterGrade(average);
        $("#lgrade1_C").text(sub_lettergrade);
        $("#lgrade1_cp").text(sub_lettergrade);
      }
      
      sub_lettergrade="";
      sum=0;
      average=0;
      $('#Tablesample tr').each(function() {
        i=i+1;
        var keval = $(this).find(".2math input").val();
        if(jQuery.type(keval) === "undefined"){
        //Some code goes here
        } else {
          // if (keval.trim() === '') {
          //   keval="0";
          // }      
          sum += parseFloat(keval);
        // console.log(i);
        console.log(keval);
        }

      });
      if (isNaN(sum) || sum==0) {
        $("input#2_cp").val(defaultvalue);
        $("input#subject_C2").val(defaultvalue);
      } else {      
        average = sum / 8;
        ave_math2 = average;
      
        $("input#2_cp").val(average.toFixed(2));
        $("input#subject_C2").val(average.toFixed(2));
        sub_lettergrade =  getLetterGrade(average);
        $("#lgrade2_C").text(sub_lettergrade);
        $("#lgrade2_cp").text(sub_lettergrade);
      }
      $("input#final_C").val(defaultvalue);
      $("input#lgC").val(defaultvalue);
      if (ave_math1>1 && ave_math2>1) {
        var final_average = (ave_math1 + ave_math2) / 2;
        $("input#final_C").val(final_average.toFixed(2));

        var lettergrade = getLetterGrade(final_average);
        $("input#lgC").val(lettergrade);
      }
      
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
      var defaultvalue="";
      var sub_lettergrade="";

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

      if (isNaN(sum)) {
        $("input#1_mu").val(defaultvalue);
      } else {
        average = sum / 3;
        ave_music1 = average;
        $("input#1_mu").val(average.toFixed(2));
        sub_lettergrade =  getLetterGrade(average);
        $("#lgrade1_mu").text(sub_lettergrade);
      }

      sub_lettergrade = "";
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
      if (isNaN(sum)) {
        $("input#2_mu").val(defaultvalue);
      } else {
        average = sum / 3;
        ave_music2 = average;
        $("input#2_mu").val(average.toFixed(2));
        sub_lettergrade =  getLetterGrade(average);
        $("#lgrade2_mu").text(sub_lettergrade);
      }
      // // console.log(sum);

      // arts
      sub_lettergrade = "";
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
      if (isNaN(sum)) {
        $("input#1_ar").val(defaultvalue);
      } else {
        average = sum / 3;
        ave_arts1 = average;
        $("input#1_ar").val(average.toFixed(2));
        sub_lettergrade =  getLetterGrade(average);
        $("#lgrade1_ar").text(sub_lettergrade);
      }
      
      sub_lettergrade = "";
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
      if (isNaN(sum)) {
        $("input#2_ar").val(defaultvalue);
      } else {      
        average = sum / 3
        ave_arts2 = average;
        $("input#2_ar").val(average.toFixed(2));
        sub_lettergrade =  getLetterGrade(average);
        $("#lgrade2_ar").text(sub_lettergrade);
      }

      // Fine Motor Skills
      sub_lettergrade = "";
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
      if (isNaN(sum)) {
        $("input#1_fms").val(defaultvalue);
      } else {
        average = sum / 6;
        ave_fms1 = average;
        $("input#1_fms").val(average.toFixed(2));
        sub_lettergrade =  getLetterGrade(average);
        $("#lgrade1_fms").text(sub_lettergrade);
      }
      
      sub_lettergrade = "";
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
      if (isNaN(sum)) {
        $("input#2_fms").val(defaultvalue);
      } else {
        average = sum / 6;
        ave_fms2 = average;
        $("input#2_fms").val(average.toFixed(2));
        sub_lettergrade =  getLetterGrade(average);
        $("#lgrade2_fms").text(sub_lettergrade);
      }

      // Gross Motor Skills
      sub_lettergrade = "";
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
      if (isNaN(sum)) {
        $("input#1_gms").val(defaultvalue);
      } else {
        average = sum / 8;
        ave_gms1 = average;
        $("input#1_gms").val(average.toFixed(2));
        sub_lettergrade =  getLetterGrade(average);
        $("#lgrade1_gms").text(sub_lettergrade);
      }
      
      sub_lettergrade = "";
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
      if (isNaN(sum)) {
        $("input#2_gms").val(defaultvalue);
      } else {
        average = sum / 8;
        ave_gms2 = average;
        $("input#2_gms").val(average.toFixed(2));
        sub_lettergrade =  getLetterGrade(average);
        $("#lgrade2_gms").text(sub_lettergrade);
      }

      var ave_psydev1 =0,
      ave_psydev2=0,
      ave_mape1=0,
      ave_mape2=0,
      final_average=0;

      
      // Pscychomotr Development
      $("input#1_pd").val(defaultvalue);
      $("input#2_pd").val(defaultvalue);
      $("input#subject_D1").val(defaultvalue);
      $("input#subject_D2").val(defaultvalue);
      $("input#final_D").val(defaultvalue);
      $("input#lgD").val(defaultvalue);

      if (ave_fms1>1 && ave_gms1>1) {
        ave_psydev1 = (ave_fms1 + ave_gms1) / 2;
        $("input#1_pd").val(ave_psydev1.toFixed(2));
      }

      if (ave_fms2>1 && ave_gms2>1) {
        ave_psydev2 = (ave_fms2 + ave_gms2) / 2;
        $("input#2_pd").val(ave_psydev2.toFixed(2));
      }

      if (ave_music1>1 && ave_arts1>1 && ave_psydev1) {
        ave_mape1 =(ave_music1 + ave_arts1 + ave_psydev1) / 3;            
        $("input#subject_D1").val(ave_mape1.toFixed(2));

        sub_lettergrade =  getLetterGrade(ave_mape1);
        $("#lgrade1_D").text(sub_lettergrade);
      }
      if (ave_music2>1 && ave_arts2>1 && ave_psydev2) {
        ave_mape2 =(ave_music2 + ave_arts2 + ave_psydev2) / 3;
        $("input#subject_D2").val(ave_mape2.toFixed(2));

        sub_lettergrade =  getLetterGrade(ave_mape2);
        $("#lgrade2_D").text(sub_lettergrade);       

        final_average = (ave_mape1 + ave_mape2) / 2;
        $("input#final_D").val(final_average.toFixed(2));

        var lettergrade = getLetterGrade(final_average);
        $("input#lgD").val(lettergrade);        
      }
      
    }

    function calculateWritting() {
      var sum = 0,
      i=0,
      average=0;
      var sub_lettergrade="";

      var subjectid = "<?php echo $writting_id ?>";
      var x=0;
      var temp="";
      var period1 = 0;
      temp = $("input#1_gradewritting" + subjectid).val();
      period1 = parseFloat(temp);

      sub_lettergrade =  getLetterGrade(temp);
      $("#lgrade1_" + subjectid).text(sub_lettergrade);

      var period2 = 0;
      temp = $("input#2_gradewritting" + subjectid).val();
      period2 = parseFloat(temp);
      sum = parseFloat(period1) + parseFloat(period2);

      sub_lettergrade =  getLetterGrade(temp);
      $("#lgrade2_" + subjectid).text(sub_lettergrade);
     
      var ave=0;
      ave = sum /2;
      average =  $("input#fin"+subjectid).val();
     
      var lettergrade = getLetterGrade(ave);
      $("input#lg"+subjectid).val(lettergrade);      
    }    

    function calculateSum3($classname,$i,$subject) {
      var sum = 0,
        i = $i,
        average=0,
        subject_class = $subject;
        var cname = $classname;
        var grades = [];
        period1_val=0;
        period2_val=0;

        // alert(subject_class);
        //iterate through each textboxes and add the values
        $("."+cname).each(function() {
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
      var quarter_class = "";
      quarter_class =  $('input#quarterid').attr("value"); 
      average = sum;
      $("input#fin"+i).val('');
      if (quarter_class==2) {
        $("input#fin"+i).val(sum);
      }
      

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

      

      var studentid="";
      studentid = $("input#studentid").val();

      var ccidval = 0;
      ccidval = $("input#ccid" + i).val();

      var ssidval = 0;
      ssidval = $("input#ssid" + i).val();
      
      var update_data = {
            studentid: studentid,
            clid: ccidval,
            id: ssidval,
            period1: period1_val,
            period2: period2_val,
            finalgrade: sum,
            quarter: quarter_class
          }

      $.ajax({
          url: url + "update",
          type: "POST",
          data: update_data,
          complete: function(response) {
            console.log(response.responseText);
          }
      });      
    
      if (subject_class=="cle") {
        calculateCLE();
      }
      else if (subject_class=="reading") {
        calculateReading();
      }
      else if (subject_class=="math") {
        calculateMath();
      }
      else if (subject_class=="mape") {
        calculateMape();
      }
      else if (subject_class=="writting") {
        // alert(average);
        $("input#lg"+i).val('');
        var lettergrade = getLetterGrade(average);
        if (quarter_class==2) {
          $("input#lg"+i).val(lettergrade);
        // alert(lettergrade);
        }
      }

      

    }

    function roundOfGrade(grade) {     
      var gradeval = grade.toFixed(2);
      return $gradeval;
    }    

</script>
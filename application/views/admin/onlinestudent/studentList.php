<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('student')." ".$this->lang->line('list')?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <div class="mailbox-messages">
                           <table class="table table-striped table-bordered table-hover example nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>                                          
                                        <th><?php echo $this->lang->line('student_name'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <!-- <th><?php //echo $this->lang->line('date_of_birth'); ?></th> -->
                                        <th><?php echo $this->lang->line('gender'); ?></th>
                                        <!-- <th><?php //echo $this->lang->line('mobile_no'); ?></th> -->
                                        <th><?php echo $this->lang->line('enrollment_payment_status'); ?></th>
                                        <th>Status</th>
                                        <th><?php echo $this->lang->line('mode_of_payment'); ?></th>
                                        <th><?php echo $this->lang->line('payment_scheme'); ?></th>
                                        <th><?php echo $this->lang->line('enrollment_type'); ?></th>
                                        <th><?php echo $this->lang->line('date').' Applied'; ?></th>

                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($studentlist as $student) { ?>
                                    <tr>                                           
                                        <td>                      
                                            <?php echo strtoupper($student['lastname']).", ".strtoupper($student['firstname']);  ?>
                                        </td>
                                        <td><?php if ($student['class'] != '') { echo $student['class']; } ?></td>
                                        <!-- <td><?php //if ($student["dob"] != null) { echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob'])); }?></td> -->
                                        <td><?php echo ucfirst($student['gender']); ?></td>
                                        <!-- <td><?php //echo $student['mobileno']; ?></td> -->
                                        <td><?php echo ucfirst($student['enrollment_payment_status']); ?></td>
                                        <td><?php echo ($student['is_enroll'])? "<i class='fa fa-check'></i><span style='display:none'>Yes</span> Enrolled":"<i class='fa fa-minus-circle'></i><span style='display:none'>No</span> Not Enrolled"; ?></td>
                                        <td><?php echo ucfirst($student['mode_of_payment']); ?></td>
                                        <td><?php echo ucfirst($student['payment_scheme']); ?></td>
                                        <td><?php echo ucfirst($student['enrollment_type'] == 'old_new' ? 'old' : $student['enrollment_type']); ?></td>
                                        <td><?php echo $student['created_at']; ?></td>
                                        <td class="mailbox-date pull-right">
                                            <?php $documents = explode("|", $student['document']);
                                            foreach ($documents as $document) 
                                            { 
                                                if (!empty($document)) 
                                                { ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>admin/onlinestudent/download/<?php echo $document ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                <?php }
                                            } 
                                                      
                                            if($this->rbac->hasprivilege('online_admission','can_edit')) {
                                                if(!$student['is_enroll']) { ?>
                                                    <a data-placement="left" href="<?php echo site_url('admin/onlinestudent/edit/'.$student['id']); ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                <?php } 
                                            }

                                            if($this->rbac->hasprivilege('online_admission','can_delete')) { ?>                                                    
                                                <a data-placement="left" href="<?php echo site_url('admin/onlinestudent/delete/'.$student['id']); ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->                  
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

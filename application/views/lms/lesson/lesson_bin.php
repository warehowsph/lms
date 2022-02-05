<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-download"></i> <?php echo $this->lang->line('download_center'); ?></h1>

    </section>
 
    <!-- Main content -->
    <section class="content">
        <div class="row">
            
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('upload_content', 'can_add')) {
                echo "12";
            } else {
                echo "12";
            }
            ?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Lesson Bin</h3>
                        <div class="box-tools pull-right">

                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <div class="pull-right">
                                
                            </div><!-- /.pull-right -->
                        </div>
                        <div class="mailbox-messages table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('content_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example nowrap">
                                <thead>
                                    <tr>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                        <th>Title</th>
                                        <th>Teacher</th>
                                        <th><?php echo $this->lang->line('type'); ?></th>
                                        <th>Date</th>
                                        <th>Term</th>
                                        <th>Subject</th>
                                        <th>Grade</th>
                                        <th>Shared</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($list as $list_key => $list_data): ?>

                                        <tr>
                                            <td class="mailbox-date pull-right">
                                                <a data-placement="left" href="<?php echo site_url('lms/lesson/retrieve/'.$list_data['id']);?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="Start Class" >
                                                                <i class="fa fa-sign-out"></i> Retrieve Lesson
                                                        </a>
                                                
                                            </td>
                                            <td class="mailbox-name">
                                                <?php echo $list_data['lesson_name']?>
                                            </td>
                                            <td class="mailbox-name">
                                                <?php echo $list_data['name'] ?> <?php echo $list_data['surname'] ?>
                                            </td>
                                            <td class="mailbox-name">
                                                <?php echo ($list_data['lesson_type']=="virtual")?"Google Meet":$list_data['lesson_type']; ?>
                                            </td>
                                            <td class="mailbox-name">
                                               <?php echo date("M d, h:i A", strtotime($list_data['start_date'])); ?> - <?php echo date("M d, h:i A", strtotime($list_data['end_date'])); ?>
                                            </td>
                                            <td class="mailbox-name">
                                                <center><?php echo $list_data['term']; ?></center>
                                            </td>
                                            <td class="mailbox-name">
                                                <?php echo $list_data['subject_name']; ?>
                                            </td>
                                            <td class="mailbox-name">
                                                <?php echo $list_data['class']; ?>
                                            </td>
                                            
                                            <!-- <td class="mailbox-name">
                                                <?php echo str_replace("_", " ", strtoupper($list_data['education_level'])); ?>
                                            </td> -->
                                            <td>
                                                <?php echo ($list_data['shared'] == 1)?"Yes":"No" ; ?>
                                            </td>
                                            <td>
                                                <select class="lesson_status" lesson_id="<?php echo $list_data['id'] ?>" <?php if($role=="student"): ?> readonly="" <?php endif; ?> >
                                                    <option value="awaiting">Awaiting</option>
                                                    <option value="cancelled">Cancelled</option>
                                                    <option value="completed">Completed</option>
                                                </select>
                                            </td>
                                            
                                        </tr>
                                        <?php endforeach; ?>

                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->

                    </div><!-- /.box-body -->

                </div>
            </div><!--/.col (left) -->


            <!-- right column -->

        </div>
        <div class="row">
            <!-- left column -->

            <!-- right column -->
            <div class="col-md-12">

                <!-- Horizontal Form -->

                <!-- general form elements disabled -->

            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->


<script>
    function google_meet_open(url){
        window.open(url);
    }
    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });

        $(".lesson_status").change(function(){
            var lesson_status_val = $(this).val();
            
            alert($(this).val());
        });
        
        
    });
</script>
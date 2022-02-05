<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-download"></i> Lesson Attendance</h1>

    </section>
 
    <!-- Main content -->
    <section class="content">
        <div class="row">
            
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        
                        <h3 class="box-title titlefix">Lesson Attendance (Lesson Title Here)</h3>

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
                                        <th>Student Name</th>
                                        <th>Time Stamp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($list as $list_key => $list_data): ?>

                                        <tr>
                                            <td class="mailbox-name">
                                                <?php echo $list_data['lesson_name']?>
                                            </td>
                                            <td class="mailbox-name">
                                                <?php echo $list_data['subject_name']; ?>
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
    function check_class(lesson_id){
        var url = "<?php echo base_url('lms/lesson/check_class/');?>"+lesson_id;

        $.ajax({
            url: url,
            method:"POST",
        }).done(function(data) {
            var parsed_data = JSON.parse(data);
            if(parsed_data.video!=""){
                window.open(parsed_data.video,"_blank");
            }
            if(parsed_data.lms!=""){
                window.location.href = parsed_data.lms;
            }
        });
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
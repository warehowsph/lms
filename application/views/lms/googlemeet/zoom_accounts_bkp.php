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
          
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">School Zoom Accounts API</h3>
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
                                        <th>Zoom Name</th>
                                        <th></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($list as $list_key => $list_data): ?>
                                        <tr>
                                            <td class="mailbox-name">
                                                <?php echo $list_data['name']?> <?php echo $list_data['surname']?>
                                            </td>
                                            <td class="mailbox-name">
                                                <input style="width: 100%" type="text" class="google_meet" staff_id="<?php echo $list_data['id']?>" value="<?php echo $list_data['zoom']?>" name="">
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

        $('.google_meet').change(function(){
            var account_id = $(this).attr("staff_id");
            var zoom = $(this).val();
            var url = '<?php echo base_url()."lms/googlemeet/zoom_updated"; ?>';
            $.ajax({
                url: url,
                type: 'POST',
                data: { 
                    account_id: account_id, 
                    zoom:zoom
                },
                success: function(res) {
                    console.log(res);
                    res = JSON.parse(res);
                    if (res.status == "failed") {
                        errorMsg(res.message);
                    } else {
                        successMsg(res.message);
                    }
                },
                error:function(response){
                    console.log(response.responseText);
                    // alert("error");
                }
            });
        });
        
        
    });
</script>
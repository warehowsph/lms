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
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-online-timetable"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?> </button>
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
                                        <th>Email *</th>
                                        <th>API KEY *</th>
                                        <th>API SECRET *</th>
                                        <th>Type</th>
                                        <th>Owner</th>
                                        <th>Times Used</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($list as $list_key => $list_data): ?>
                                        <tr>
                                            <td class="mailbox-name">
                                                <?php echo $list_data['name']?>
                                            </td>
                                            <td class="mailbox-name">
                                                <?php echo $list_data['email']?>
                                            </td>
                                            <td class="mailbox-name">
                                                <?php if($list_data['editable']==0): ?>
                                                    *********
                                                <?php else: ?>
                                                    <?php echo $list_data['api_key']?>
                                                <?php endif; ?>
                                            </td>
                                            <td class="mailbox-name">
                                                <?php if($list_data['editable']==0): ?>
                                                    *********
                                                <?php else: ?>
                                                    <?php echo $list_data['api_secret']?>
                                                <?php endif; ?>
                                            </td>
                                            <td class="mailbox-name">
                                                <?php echo ucfirst($list_data['zoom_type'])?>
                                            </td>
                                            <td class="mailbox-name">
                                                <?php echo ucfirst($list_data['owner']) ?>
                                            </td>
                                            <td class="mailbox-name">
                                                <?php echo $list_data['usage']?>
                                            </td>
                                            <td class="mailbox-name">
                                                <?php if($list_data['editable']==1): ?>
                                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-online-timetable">Delete <i class="fa fa-trash"></i></button>
                                                <?php endif; ?>
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


<div class="modal fade" id="modal-online-timetable">
    <div class="modal-dialog">
        <form id="form-addconference" action="<?php echo site_url('admin/conference/addByOther'); ?>" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Zoom Account</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" id="password" name="password">
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <label for="title">Zoom Name<small class="req">*</small></label>
                            <input type="text" class="form-control" id="title" name="title">
                            <span class="text text-danger" id="title_error"></span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <label for="zoom_email">Zoom Email <small class="req"> *</small></label>
                            <input type="email" class="form-control" id="zoom_email" name="title">
                            <span class="text text-danger" id="title_error"></span>
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <label for="zoom_email">API Key <small class="req"> *</small></label>
                            <input type="email" class="form-control" id="zoom_email" name="title">
                            <span class="text text-danger" id="title_error"></span>
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <label for="zoom_email">API Secret <small class="req"> *</small></label>
                            <input type="email" class="form-control" id="zoom_email" name="title">
                            <span class="text text-danger" id="title_error"></span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <label for="class">Zoom Type <small class="req"> *</small></label>
                            <select  id="class_id" name="class_id" class="form-control" >
                                <option value="paid">Paid</option>
                                <option value="free">Free (Meetings are 40 Minutes only)</option>
                   
                            </select>
                            <span class="text text-danger" id="class_error"></span>
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <label for="class"><?php echo $this->lang->line('staff'); ?><small class="req"> *</small></label>
                            <select  id="staff_id" name="staff_id" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                            </select>
                            <span class="text text-danger" id="class_error"></span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <label for="class"><?php echo $this->lang->line('class'); ?> <small class="req"> *</small></label>
                            <select  id="class_id" name="class_id" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php
                                foreach ($classlist as $class) {
                                    ?>
                                    <option value="<?php echo $class['id'] ?>"><?php echo $class['class'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <span class="text text-danger" id="class_error"></span>
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <label for="section"><?php echo $this->lang->line('section'); ?><small class="req"> *</small></label>
                            <select  id="section_id" name="section_id" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                            </select>
                            <span class="text text-danger" id="section_error"></span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-sm-6 col-md-6 col-lg-6">
                            <label for="class"><?php echo $this->lang->line('host_video'); ?><small class="req"> *</small></label>
                            <label class="radio-inline"><input type="radio" name="host_video"  value="1" checked><?php echo $this->lang->line('enable'); ?></label>
                            <label class="radio-inline"><input type="radio" name="host_video" value="0" ><?php echo $this->lang->line('disabled'); ?> </label>
                            <span class="text text-danger" id="class_error"></span>
                        </div>
                        <div class="form-group col-sm-6 col-md-6 col-lg-6">
                            <label for="class"><?php echo $this->lang->line('client_video'); ?><small class="req"> *</small></label>
                            <label class="radio-inline"><input type="radio" name="client_video"  value="1" checked><?php echo $this->lang->line('enable'); ?></label>
                            <label class="radio-inline"><input type="radio" name="client_video" value="0" ><?php echo $this->lang->line('disabled'); ?></label>
                            <span class="text text-danger" id="class_error"></span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <label for="description"><?php echo $this->lang->line('description') ?></label>
                            <textarea class="form-control" name="description" id="description"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving..."><?php echo $this->lang->line('save') ?></button>
                </div>
            </div>
        </form>
    </div>
</div>

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
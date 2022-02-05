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
            <?php if ($this->rbac->hasPrivilege('upload_content', 'can_add')) : ?>
                
            <?php endif; ?>
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('upload_content', 'can_add')) {
                echo "12";
            } else {
                echo "12";
            }
            ?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">Google Live Classes</h3>
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
                                        <th>Title</th>
                                        <th><?php echo $this->lang->line('type'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th>Subject</th>
                                        <th>Grade</th>
                                        <th>Term</th>
                                        <th>Education Level</th>
                                        <th>Shared</th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($list as $list_key => $list_data): ?>

                                        <tr>
                                            <td class="mailbox-name">
                                                <?php echo $list_data['lesson_name']?>
                                            </td>
                                            <td class="mailbox-name">
                                                <?php echo ($list_data['lesson_type']=="virtual")?"Google Meet":$list_data['lesson_type']; ?>
                                            </td>
                                            <td class="mailbox-name">
                                               <?php echo date("F d Y", strtotime($list_data['date_created'])); ?>
                                            </td>
                                            <td class="mailbox-name">
                                                <?php echo $list_data['name']; ?>
                                            </td>
                                            <td class="mailbox-name">
                                                <?php echo $list_data['class']; ?>
                                            </td>
                                            <td class="mailbox-name">
                                                <?php echo $list_data['term']; ?>
                                            </td>
                                            <td class="mailbox-name">
                                                <?php echo str_replace("_", " ", ucfirst($list_data['education_level'])); ?>
                                            </td>
                                            <td>
                                                <?php echo ($list_data['shared'] == 1)?"Yes":"No" ; ?>
                                            </td>
                                            <td class="mailbox-date pull-right">
                                                <?php if($role=="admin"): ?>
                                                    <a data-placement="left" href="<?php echo site_url('lms/lesson/create/'.$list_data['id']);?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>" >
                                                            <i class="fa fa-edit"></i> Edit
                                                    </a>
                                                    <a data-placement="left" href="<?php echo site_url('lms/lesson/create/'.$list_data['id']);?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="Start Class" >
                                                            <i class="fa fa-sign-in"></i> Start Class
                                                    </a>
                                                    <a data-placement="left" href="<?php echo site_url('lms/lesson/delete/'.$list_data['id']);?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>

                                                <?php elseif($role=="student"): ?>
                                                    <a data-placement="left" href="<?php echo site_url('lms/lesson/create/'.$list_data['id']);?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="Enter Class" >
                                                            <i class="fa fa-eye"></i>
                                                    </a>
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


<script>
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
    });
</script>
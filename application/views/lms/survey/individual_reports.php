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
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Students List</h3>
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
                                        <th>Name</th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $list_key => $list_data): ?>

                                        <tr>
                                            <td class="mailbox-name">
                                                <?php echo $list_data['firstname']?>
                                            </td>

                                            <td class="mailbox-date pull-right">
                                                <a data-placement="left" href="<?php echo site_url('lms/survey/individual/'.$survey_id.'/'.$list_data['id']);?>" target="_blank" class="btn btn-default btn-xs"  data-toggle="tooltip" title="View Survey Sheet" >
                                                            <i class="fa fa-eye"></i>
                                                    </a>
                                                <?php if($role=="admin"): ?>


                                                    

                                                <?php elseif($role=="student"): ?>
                                                    <a data-placement="left" href="<?php echo site_url('lms/assessment/review/'.$list_data['id']);?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="View Answer" >
                                                            <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a data-placement="left" href="<?php echo site_url('lms/assessment/answer/'.$list_data['id']);?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="Answer" >
                                                            <i class="fa fa-edit"></i>
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
<script type="text/javascript">
    $(document).ready(function () {
      
        
        $("#btnreset").click(function () {

            $("#form1")[0].reset();
        });
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        getSectionByClass(class_id, section_id);
        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        });
        function getSectionByClass(class_id, section_id) {
            if (class_id != "" && section_id != "") {
                $('#section_id').html("");
                var base_url = '<?php echo base_url() ?>';
                var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                $.ajax({
                    type: "GET",
                    url: base_url + "sections/getByClass",
                    data: {'class_id': class_id},
                    dataType: "json",
                    success: function (data) {
                        $.each(data, function (i, obj)
                        {
                            var sel = "";
                            if (section_id == obj.id) {
                                sel = "selected";
                            }
                            div_data += "<option value=" + obj.id + " " + sel + ">" + obj.section + "</option>";
                        });
                        $('#section_id').append(div_data);
                    }
                });
            }
        }



    });
    $(document).ready(function () {

        $(document).on("click", '.content_available', function (e) {
            var avai_value = $(this).val();
            if (avai_value === "student") {
                console.log(avai_value);
                if ($(this).is(":checked")) {

                    $(this).closest("div").parents().find('.upload_content').removeClass("content_disable");

                } else {
                    $(this).closest("div").parents().find('.upload_content').addClass("content_disable");

                }
            }
        });
        $("#chk").click(function () {
            if ($(this).is(":checked")) {
                $("#class_id").prop("disabled", true);
            } else {
                $("#class_id").prop("disabled", false);
            }
        });
        if ($("#chk").is(":checked")) {
            $("#class_id").prop("disabled", true);
        } else {
            $("#class_id").prop("disabled", false);
        }

    });</script>

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
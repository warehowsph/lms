<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style type="text/css">
    @media print
    {
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
</style>

<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('student_information'); ?> <small><?php echo $this->lang->line('student1'); ?></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?> <div class="alert alert-success">  <?php echo $this->session->flashdata('msg') ?> </div> <?php } ?>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('student/SendDocs') ?>" method="post" class="">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('class'); ?></label> <small class="req"> *</small>
                                                <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($classlist as $class) {
                                                        ?>
                                                        <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                                        <?php
                                                        $count++;
                                                    } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                                <select  id="section_id" name="section_id" class="form-control" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div><!--./col-md-6-->
                        </div><!--./row-->
                    </div>

                    <?php

                    $resultsize = sizeof($resultlist);
                    if (isset($resultlist)) { ?>

                    <form id='frm_senddocs' action="<?php echo site_url('student/Upload_Documents') ?>"  method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <div class="nav-tabs-custom border0 navnoshadow">
                        <div class="box-header ptbnull"></div>
                        <ul class="nav nav-tabs">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('doctitle'); ?></label><small class="req"> *</small>
                                    <input type="text" class="form-control" name="doctitle" placeholder="Enter the document title">
                                    <span class="text-danger"><?php echo form_error('doctitle'); ?></span>
                                </div>
                            </div>
                            <!-- <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><i class="fa fa-list"></i> <?php echo $this->lang->line('list'); ?>  <?php echo $this->lang->line('view'); ?></a></li>
                            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><i class="fa fa-newspaper-o"></i> <?php echo $this->lang->line('details'); ?> <?php echo $this->lang->line('view'); ?></a></li> -->
                        </ul>

                        <div class="tab-content">
                            <div class="download_label"><?php echo $title; ?></div>
                            <div class="tab-pane active table-responsive no-padding" id="tab_1">
                                <table class="table table-striped table-bordered table-hover nowrap senddocs" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('roll_no'); ?></th>
                                            <th><?php echo $this->lang->line('student_name'); ?></th>
                                            <th><?php echo $this->lang->line('class'); ?></th>
                                            <th><?php echo $this->lang->line('gender'); ?></th>
                                            <th class="text-center"><?php echo $this->lang->line('documents'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($resultlist)) { ?>
                                        <?php }
                                        else {
                                            $count = 1;

                                            foreach ($resultlist as $student) { ?>
                                                <tr>
                                                    <input type="hidden" name="id_num[]" value="<?php echo $student['id']; ?>">
                                                    <td><?php echo $student['roll_no']; ?></td>
                                                    <td><a href="<?php echo base_url(); ?>student/view/<?php echo $student['id']; ?>"><?php echo $student['lastname'] . ", " . $student['firstname']; ?></a></td>
                                                    <td><?php echo $student['class'] . "(" . $student['section'] . ")" ?></td>
                                                    <td><?php echo $student['gender']; ?></td>
                                                    <td>
                                                        <input name="docs<?php echo $student['id'] ?>[]" placeholder="" type="file" multiple class="form-control filestyle" data-height="25"  value="<?php echo set_value('first_doc'); ?>" />
                                                    </td>
                                                </tr>
                                                <?php $count++;
                                            }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <div class="box-footer">
                                    <button type="submit" name="action" <?php echo($resultsize <= 0 ? 'disabled' : ''); ?> value="upload_docs" class="btn btn-primary pull-right submitdocs" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Sending"><?php echo $this->lang->line('senddocs'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                    <?php } ?>
                </div>
            </div><!--./box box-primary -->
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var table = $('.senddocs').DataTable({
            "aaSorting": [],           
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            paging: false,
            // pageLength: 50,
            //responsive: 'false',
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
            ]
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
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }
    }

    $(document).ready(function () {
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
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        });

        $("#frm_senddocs").on('submit', (function (e) {
            e.preventDefault();
            var $this = $('.submitdocs');
            $this.button('loading');

            var frmdata = new FormData(this);

            $.ajax({
                url: "<?php echo site_url("student/Upload_MultiDocs") ?>",
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
                    console.log(res);
                    if (res.status == "fail") {

                        var message = "";
                        $.each(res.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);

                    } else {
                        successMsg(res.message);
                        window.location.reload(true);
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

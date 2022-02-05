<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>backend/dist/css/zoom_addon.css">
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i><?php echo $this->lang->line('live_class'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i><?php echo $this->lang->line('live_class'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('live_classes', 'can_add')) { ?>

                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-online-timetable"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?> </button>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?>
                            <?php echo $this->session->flashdata('msg') ?>
                        <?php } ?>
                        <div class="table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('live_class'); ?></div>
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('class') . ' ' . $this->lang->line('title'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('api_used'); ?></th>
                                        <th><?php echo $this->lang->line('created_by'); ?> </th>
                                        <th><?php echo $this->lang->line('created_for'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($conferences)) {
                                        ?>

                                        <?php
                                    } else {
                                        foreach ($conferences as $conference_key => $conference_value) {

                                            $return_response = json_decode($conference_value->return_response);
                                            ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <a href="#" data-toggle="popover" class="detail_popover"><?php echo $conference_value->title; ?></a>

                                                    <div class="fee_detail_popover displaynone">
                                                        <?php
                                                        if ($conference_value->description == "") {
                                                            ?>
                                                            <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <p class="text text-info"><?php echo $conference_value->description; ?></p>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </td>

                                                <td class="mailbox-name">

                                                    <?php echo $this->customlib->dateyyyymmddToDateTimeformat($conference_value->date); ?></td>
                                                <td class="mailbox-name">

                                                    <?php echo $this->lang->line($conference_value->api_type); ?>

                                                </td>

                                                <td class="mailbox-name">

                                                    <?php
                                                    if ($conference_value->created_id == $logged_staff_id) {
                                                        echo $this->lang->line('self');
                                                    } else {
                                                        $name = ($conference_value->create_by_surname == "") ? $conference_value->create_by_name : $conference_value->create_by_name . " " . $conference_value->create_by_surname;
                                                        echo $name;
                                                    }
                                                    ?></td>

                                                <td class="mailbox-name">
                                                    <?php
                                                    $name = ($conference_value->create_for_surname == "") ? $conference_value->create_for_name : $conference_value->create_for_name . " " . $conference_value->create_for_surname;
                                                    echo $name;
                                                    ?>
                                                </td>

                                                <td class="mailbox-name">
                                                    <?php echo $conference_value->class . " (" . $conference_value->section . ")";
                                                    ?>

                                                </td>
                                                <td class="mailbox-name">
                                                    <form class="chgstatus_form" method="POST" action="<?php echo site_url('admin/conference/chgstatus') ?>">
                                                        <input type="hidden" name="conference_id" value="<?php echo $conference_value->id; ?>">
                                                        <select class="form-control chgstatus_dropdown" name="chg_status">
                                                            <option value="0" <?php if ($conference_value->status == 0) echo "selected='selected'" ?>><?php echo $this->lang->line('awaited'); ?></option>
                                                            <option value="1" <?php if ($conference_value->status == 1) echo "selected='selected'" ?>><?php echo $this->lang->line('cancelled'); ?> </option>
                                                            <option value="2" <?php if ($conference_value->status == 2) echo "selected='selected'" ?>><?php echo $this->lang->line('finished'); ?> </option>
                                                        </select>
                                                    </form>
                                                </td>
                                                <td class="mailbox-date pull-right">
                                                    <?php
                                                    if ($conference_value->status == 0) {
                                                        ?>
                                                        <a data-placement="left" href="<?php echo $return_response->start_url; ?>" class="btn btn-default btn-xs"  target="_blank" >
                                                            <i class="fa fa-sign-in"></i> <?php echo $this->lang->line('start') . ' ' . $this->lang->line('class'); ?> 
                                                        </a>
                                                        <?php
                                                    }
                                                    ?>

                                                    <?php
                                                    if ($conference_value->api_type != 'self') {
                                                        ?>
                                                        <?php
                                                        if ($this->rbac->hasPrivilege('live_classes', 'can_delete')) {
                                                            ?>
                                                            <a data-placement="left" href="<?php echo base_url(); ?>admin/conference/delete/<?php echo $conference_value->id . "/" . $return_response->id; ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                <i class="fa fa-remove"></i>
                                                            </a>
                                                            <?php
                                                        }
                                                        ?>

                                                        <?php
                                                    }
                                                    ?>

                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modal-online-timetable">
    <div class="modal-dialog">
        <form id="form-addconference" action="<?php echo site_url('admin/conference/addByOther'); ?>" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $this->lang->line('add_live_class'); ?></h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" id="password" name="password">
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <label for="title"><?php echo $this->lang->line('class_title'); ?><small class="req"> *</small></label>
                            <input type="text" class="form-control" id="title" name="title">
                            <span class="text text-danger" id="title_error"></span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-sm-6 col-md-6 col-lg-6">
                            <label for="date"><?php echo $this->lang->line('class_date'); ?><small class="req"> *</small></label>
                            <div class='input-group' id='meeting_date'>
                                <input type='text' class="form-control" name="date" readonly="readonly"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            <span class="text text-danger" id="title_error"></span>
                        </div>
                        <div class="form-group col-sm-6 col-md-6 col-lg-6">
                            <label for="duration"><?php echo $this->lang->line('class_duration_minutes'); ?><small class="req"> *</small></label>
                            <input type="number" class="form-control" id="duration" name="duration">
                            <span class="text text-danger" id="title_error"></span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <label for="class"><?php echo $this->lang->line('role'); ?> <small class="req"> *</small></label>
                            <select  id="role_id" name="role_id" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php
                                foreach ($roles as $role) {
                                    ?>
                                    <option value="<?php echo $role['name'] ?>"><?php echo $role['name'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <span class="text text-danger" id="class_error"></span>
                        </div>
                        <div class="clearfix"></div>
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
<script type="text/javascript">
    (function ($) {
        "use strict";
        var datetime_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'DD', 'm' => 'MM', 'Y' => 'YYYY']) ?>';
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

        $('#meeting_date').datetimepicker({
            format: datetime_format + " HH:mm",
            showTodayButton: true,
            ignoreReadonly: true
        });
        $('#modal-online-timetable').on('shown.bs.modal', function (e) {
            $("#class_id").prop("selectedIndex", 0);
            $("#section_id").find('option:not(:first)').remove();
            var password = makeid(5);
            $('#password').val("").val(password);
        })
        $("form#form-addconference").submit(function (event) {
            event.preventDefault();
            var $form = $(this),
                    url = $form.attr('action');
            var $button = $form.find("button[type=submit]:focus");
            $.ajax({
                type: "POST",
                url: url,
                data: $form.serialize(),
                dataType: "JSON",
                beforeSend: function () {
                    $button.button('loading');
                },
                success: function (data) {
                    if (data.status == 0) {
                        var message = "";
                        $.each(data.error, function (index, value) {

                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        $('#modal-online-timetable').modal('hide');
                        successMsg(data.message);
                        window.location.reload(true);
                    }
                    $button.button('reset');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $button.button('reset');
                },
                complete: function (data) {
                    $button.button('reset');
                }
            });
        })
        $('#modal-online-timetable').on('hidden.bs.modal', function () {

            $(this).find("input,textarea,select").not("input[type=radio]")
                    .val('')
                    .end();
            $(this).find("input[type=checkbox], input[type=radio]")
                    .prop('checked', false);
            $('input:radio[name="host_video"][value="1"]').prop('checked', true);
            $('input:radio[name="client_video"][value="1"]').prop('checked', true);
        });
        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            getSectionByClass(class_id, 0);
        });
        $(document).on('change', '#role_id', function (e) {
            $('#staff_id').html("");
            var role_id = $(this).val();
            getEmployeeName(role_id)
        });
        $(document).on('change', '.chgstatus_dropdown', function () {
            $(this).parent('form.chgstatus_form').submit()
        });
        $("form.chgstatus_form").submit(function (e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                dataType: "JSON",
                success: function (data)
                {
                    if (data.status == 0) {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        window.location.reload(true);
                    }
                }
            });
        });
    })(jQuery);
    function makeid(length) {
        var result = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }
    function getSectionByClass(class_id, section_id) {

        if (class_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
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

    function getEmployeeName(role) {
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

        $.ajax({
            type: "POST",
            url: base_url + "admin/staff/getEmployeeByRole",
            data: {'role': role},
            dataType: "JSON",
            beforeSend: function () {

                $('#staff_id').html("");
                $('#staff_id').addClass('dropdownloading');
            },
            success: function (data) {
                console.log(data);
                $.each(data, function (i, obj)
                {
                    div_data += "<option value='" + obj.id + "'>" + obj.name + " " + obj.surname + "</option>";
                });
                $('#staff_id').append(div_data);
            },
            complete: function () {
                $('#staff_id').removeClass('dropdownloading');
            }
        });
    }
</script>
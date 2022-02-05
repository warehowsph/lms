<link rel="stylesheet" href="<?php echo $resources.'fullcalendar/packages/core/main.css' ?>">
<link rel="stylesheet" href="<?php echo $resources.'fullcalendar/packages/daygrid/main.css' ?>">
<link rel="stylesheet" href="<?php echo $resources.'fullcalendar/packages/timegrid/main.css' ?>">
<link rel="stylesheet" href="<?php echo $resources.'fullcalendar/packages/list/main.css' ?>">
<script type="text/javascript" src="<?php echo $resources.'fullcalendar/packages/core/main.js' ?>"></script>
<script type="text/javascript" src="<?php echo $resources.'fullcalendar/packages/interaction/main.js' ?>"></script>
<script type="text/javascript" src="<?php echo $resources.'fullcalendar/packages/daygrid/main.js' ?>"></script>
<script type="text/javascript" src="<?php echo $resources.'fullcalendar/packages/timegrid/main.js' ?>"></script>
<script type="text/javascript" src="<?php echo $resources.'fullcalendar/packages/list/main.js' ?>"></script>
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
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">LMS Lesson Schedule</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <div id='kalendaryo'></div>
                </div>

            </div><!--/.col (right) -->

        </div>
        <div id="edit_modal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <form id="edit_event">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Details</h4>
                              </div>
                              <div class="modal-body">
                                <table class="edit_table table">
                                    <tr>
                                        <td>Topic</td>
                                        <td style="font-weight: bold" id="topic_label"></td>
                                    </tr>
                                    <tr>
                                        <td>Start : </td>
                                        <td id="start_date"></td>
                                    </tr>
                                    <tr>
                                        <td>End : </td>
                                        <td id="end_date"></td>
                                    </tr>
                                    <tr>
                                        <td>Sections : </td>
                                        <td id="assigned_sections">
                                            
                                        </td>
                                    </tr>
                                </table>
                                <input type="hidden" id="edit_selected_section" name="">
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                
                                
                              </div>
                            </div>
                        </form>

                    </div>
                </div>
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->


<script>
    var calendarEl = document.getElementById('kalendaryo');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        eventRender: function(info) {
            
        },
        defaultDate: '<?php echo date('Y-m-d'); ?>',
        navLinks: true, // can click day/week names to navigate views
        defaultView: 'dayGridMonth',
        weekNumbers: true,
        weekNumbersWithinDays: true,
        weekNumberCalculation: 'ISO',
        minTime: "05:00:00",
        maxTime: "18:00:00",
        // hiddenDays: [0,6],
        selectable: false,
        selectMirror: true,
        select: function(arg) {
            $('#modal').modal('show');
            $("#topic").val("");
            $("#sections").val("");
            current_arg = arg;

        },
        eventClick: function(info) {
            
            $('#edit_modal').modal('show');
            var custom_data = info.event.extendedProps;
            var edit_section = custom_data.section;
            var edit_section_id = custom_data.section_id;
            var edit_topic = custom_data.topic;
            var edit_color = info.event.backgroundColor;
            console.log(info.event);
            var start_date = info.event.start.toLocaleString();
            var end_date = info.event.end.toLocaleString();
            $("#topic_label").text(info.event.extendedProps.topic);
            $("#start_date").text(start_date);
            $("#end_date").text(end_date);
            if(info.event.extendedProps.sections){
                $("#assigned_sections").text(info.event.extendedProps.sections);
            }else{
                $("#assigned_sections").text("Unassigned");
            }
            

            console.log(info.event);
            $("#edit_color").css("background-color",edit_color);
            $("#edit_selected_section").val(edit_section_id);
            current_arg = info;
        },

        editable: false,
        eventLimit: true, // allow "more" link when too many events
        events: <?php echo $lesson_schedule; ?>,
    });
    calendar.render();
</script>
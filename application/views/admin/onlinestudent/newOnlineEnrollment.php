<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Online Enrollment Version 2</h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <div class="mailbox-messages">
                            <?php
                            $host = $_SERVER['HTTP_HOST'];

                            // if (str_contains($host, 'campuscloudph'))
                            //     $enrollmentUrl = "https://admission." . str_replace('www.', '', $host) . "/staff/login/";
                            // else
                            $enrollmentUrl = "https://admission." . str_replace('www.', '', $host) . "/staff/login/";

                            // $enrollmentUrl = "https://admission." . $host . ".s3-website.us-east-2.amazonaws.com/staff/login/";

                            //"http://admission.aphsrizal.com.s3-website.us-east-2.amazonaws.com"; 
                            ?>
                            <!-- <p>You will redirected to a new page for the new version of online enrollment.</p><a href="http://lms.campuscloudph.com/staff/login/<?php //echo $id . "/" . $access_key 
                                                                                                                                                                        ?>" target="_blank">Please click here to continue</a> -->
                            <p>You will redirected to a new page for the new version of online enrollment.</p><a href="<?php echo $enrollmentUrl . $id . "/" . $access_key ?>" target="_blank">Please click here to continue</a>
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div>
            <!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- <style type="text/css">
    body {
        overflow: auto;
    }
</style>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body table-responsive">
                        <div class="mailbox-messages">
                            <iframe id="external" src="http://lms.campuscloudph.com/staff/login/<?php //echo $id . "/" . $access_key 
                                                                                                ?>"" style=" position:block; top:0; left:0; bottom:0; right:0; width:70vw; height:80vh; border:none; margin:0; padding:0; overflow:auto; z-index:999999;">
                                Your browser doesn't support iframes
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
    $(document).ready(function() {
        SetIframeSize();
    });
    $(window).on('resize', function() {
        SetIframeSize();
    });

    function SetIframeSize() {
        $("#external").width($(window).width() - 18);
        $("#external").height($(window).height() - 35);
    }
</script> -->
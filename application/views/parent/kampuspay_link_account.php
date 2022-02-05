<style type="text/css">
    body {
        overflow: hidden;
    }
</style>

<iframe id="external" src="<?php echo $linking_page ?>" style="position:fixed; top:0; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;">
    Your browser doesn't support iframes
</iframe>

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
</script>
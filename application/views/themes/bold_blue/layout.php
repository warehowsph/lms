<!DOCTYPE html>
<html dir="<?php echo ($front_setting->is_active_rtl) ? "rtl" : "ltr"; ?>" lang="<?php echo ($front_setting->is_active_rtl) ? "ar" : "en"; ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $page['title']; ?></title>
        <meta name="title" content="<?php echo $page['meta_title']; ?>">
        <meta name="keywords" content="<?php echo $page['meta_keyword']; ?>">
        <meta name="description" content="<?php echo $page['meta_description']; ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="<?php echo base_url($front_setting->fav_icon); ?>" type="image/x-icon">

        <link href="<?php echo $base_assets_url; ?>css/font-awesome.min.css" rel="stylesheet">
        <link href="<?php echo $base_assets_url; ?>css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo $base_assets_url; ?>css/owl.carousel.css" rel="stylesheet">
        <link href="<?php echo $base_assets_url; ?>css/style.css" rel="stylesheet">  
        <link rel="stylesheet" href="<?php echo $base_assets_url; ?>datepicker/bootstrap-datepicker3.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
        <script src="<?php echo $base_assets_url; ?>js/jquery.min.js"></script>
        <script type="text/javascript">
            var base_url = "<?php echo base_url() ?>";
            // When the user scrolls the page, execute myFunction
            window.onscroll = function() { myFunction() };

            $(window).resize(function(){
                myFunction();
            });

            function get_browser() {
                var ua=navigator.userAgent,tem,M=ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || []; 
                if(/trident/i.test(M[1])){
                    tem=/\brv[ :]+(\d+)/g.exec(ua) || []; 
                    return {name:'IE',version:(tem[1]||'')};
                    }   
                if(M[1]==='Chrome'){
                    tem=ua.match(/\bOPR|Edge\/(\d+)/)
                    if(tem!=null)   {return {name:'Opera', version:tem[1]};}
                    }   
                M=M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
                if((tem=ua.match(/version\/(\d+)/i))!=null) {M.splice(1,1,tem[1]);}
                return {
                name: M[0],
                version: M[1]
                };
            }

            var browser = get_browser();
            console.log(browser);

            // Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
            function myFunction() {
                // if (browser.name !== 'Chrome' && browser.name !== 'Firefox' && browser.name !== 'Opera') {
                if ($('#dialog').css('display') == 'block') {
                    $("#dialog").dialog({		
                        position: {my: 'right bottom', at: 'right bottom', of: window}
                    });
                }                    
                // }
            }

            $( function() {	
                // if (browser.name !== 'Chrome' && browser.name !== 'Firefox' && browser.name !== 'Opera') {
                    $( "#dialog" ).dialog({		
                        position: {my: 'right bottom', at: 'right bottom', of: window}
                    });
                // }

                // $( "#dialog" ).on( "dialogclose", function( event, ui ) { $("#dialog").attr('style', 'display:none') } );
            });

            $(document).on('click','.ui-dialog-titlebar-close',function(){
                $("#dialog").attr('style', 'display:none');
            });
        </script>
        
        <?php if ($front_setting->is_active_rtl) { ?>
            <link href="<?php echo $base_assets_url; ?>rtl/bootstrap-rtl.min.css" rel="stylesheet">
            <link href="<?php echo $base_assets_url; ?>rtl/style-rtl.css" rel="stylesheet">
            <?php
        }
        
        echo $front_setting->google_analytics; ?>  
    </head>
    <body>
        <!-- <div id="dialog" style="display:none;" title="Browser Compatibility Alert">
            <p>This Application is best viewed in any of the following browsers:</p>
            <ul>
            <li><a target="_blank" href="https://www.google.com/chrome/thank-you.html?brand=CHBD&statcb=1&installdataindex=empty&defaultbrowser=0"><img src="<?php echo base_url('snoci/chrome.jpg') ?>" alt="" width="16" height="16">&nbsp;Google Chrome</a></li>
            <li><a target="_blank" href="https://www.mozilla.org/en-US/firefox/download/thanks/"><img src="<?php echo base_url('snoci/firefox.jpg') ?>" alt="" width="16" height="16">&nbsp;Firefox</a></li>
            <li><a target="_blank" href="https://www.opera.com/computer/thanks?ni=stable&os=windows"><img src="<?php echo base_url('snoci/opera.jpg') ?>" alt="" width="16" height="16">&nbsp;Opera</a></li>
            </ul>
            <p>Please make sure that you have installed the <b style="color:blue">latest version</b> of the browsers mentioned.</p>
        </div> -->
    <section class="toparea"> 
      <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="newscontent">
                    <?php
                    if (in_array('news', json_decode($front_setting->sidebar_options))) {
                        ?>
                        <div class="newstab">Latest News</div>
                        <div class="newscontent">
                            <marquee class="" behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">
                                <ul id="" class="" >
                                    <?php
                                    if (!empty($banner_notices)) {

                                        foreach ($banner_notices as $banner_notice_key => $banner_notice_value) {
                                            ?>
                                            <li><a href="<?php echo site_url('read/' . $banner_notice_value['slug']) ?>">
                                                <div class="date">
                                                    <?php echo date('d F Y', strtotime($banner_notice_value['date'])); ?>
                                                    <span>
                                                   
                                                        
                                                    </span>
                                                    </div><?php echo $banner_notice_value['title']; ?>
                                                </a></li>
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>

                            </marquee>
                        </div><!--./newscontent-->

                        <?php
                    }
                    ?>




                </div><!--./sidebar-->  

            </div><!--./col-md-12--> 
        </div>
    </div>
</section>  
 

        <?php echo $header; ?>

        <?php echo $slider; ?>

        <?php if (isset($featured_image) && $featured_image != "") {
            ?>
            <?php
        }
        ?> 

        <div class="container">
            <div class="row"> 
                <?php
                $page_colomn = "col-md-12";

                if ($page_side_bar) {

                    $page_colomn = "col-md-12 col-sm-12";
                }
                ?>
                <div class="<?php echo $page_colomn; ?>">
                    <?php echo $content; ?> 
                </div>  
                <?php
                if ($page_side_bar) {
                    ?>

                   
                    <?php
                }
                ?>


            </div><!--./row-->
        </div><!--./container-->  

        <?php echo $footer; ?>
        
        <script src="<?php echo $base_assets_url; ?>js/bootstrap.min.js"></script>
         <script type="text/javascript" src="<?php echo $base_assets_url; ?>js/jquery.waypoints.min.js"></script>
        <script type="text/javascript" src="<?php echo $base_assets_url; ?>js/jquery.counterup.min.js"></script>
        <script src="<?php echo $base_assets_url; ?>js/owl.carousel.min.js"></script>
        <script src="<?php echo $base_assets_url; ?>js/ss-lightbox.js"></script>
        <script src="<?php echo $base_assets_url; ?>js/custom.js"></script>
        <script type="text/javascript" src="<?php echo $base_assets_url; ?>datepicker/bootstrap-datepicker.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <script type="text/javascript">
            $(function(){
    jQuery('img.svg').each(function(){
        var $img = jQuery(this);
        var imgID = $img.attr('id');
        var imgClass = $img.attr('class');
        var imgURL = $img.attr('src');
    
        jQuery.get(imgURL, function(data) {
            // Get the SVG tag, ignore the rest
            var $svg = jQuery(data).find('svg');
    
            // Add replaced image's ID to the new SVG
            if(typeof imgID !== 'undefined') {
                $svg = $svg.attr('id', imgID);
            }
            // Add replaced image's classes to the new SVG
            if(typeof imgClass !== 'undefined') {
                $svg = $svg.attr('class', imgClass+' replaced-svg');
            }
    
            // Remove any invalid XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr('xmlns:a');
            
            // Check if the viewport is set, else we gonna set it if we can.
            if(!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
                $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
            }
    
            // Replace image with new SVG
            $img.replaceWith($svg);
    
        }, 'xml');
    
    });
});

        </script>
    </body>
</html>
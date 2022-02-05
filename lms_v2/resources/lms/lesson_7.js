$(document).ready(function(){

    var stored_lesson_data = '';
    var result_pool = [];
    var content_pool = [];
    var active_content = "";
    var active_content_data = {};
    var content_order = [];
    var lesson_data = {};
    var active_portal = "youtube";
    var url = $("#url").val();
    var blackboard_id = $("#blackboard_id").val();
    var lesson_id = $("#lesson_id").val();
    var main_url = $("#main_url").val();
    var assigned = $("#assigned").val();
    var education_level = $("#education_level").val();
    var image_resources = $("#image_resources").val();
    var account_id = $("#account_id").val();
    var start_url = $("#start_url").val();
    var google_meet = $("#google_meet").val();
    var checked_ids = [];
    var role = $("#role").val();
    var role2 = $("#role").val();
    var folders = "#folder_1,#folder_2,#folder_3,#folder_4,#folder_5";
    var folder_names = "Engage,Explore,Explain,Explain,Explore";
    var youtube_looper = 0;
    var google_looper = 0;

    if(education_level=="tertiary"){
        folder_names = "Introduction,Lesson Proper,Examination";
        folders = "#folder_1,#folder_2,#folder_3";
    }
    var the_learning_plan = tinymce.init({
        selector: '.tinymce',
        plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak paste',
        toolbar_mode: 'floating',
        paste_data_images: true,
        height : "700",
    });
    var jstree = $('#jstree_demo_div').jstree({
        "checkbox" : {
          "keep_selected_style" : false
        },
        "plugins" : [ "checkbox" ]
    });
    $(".notification_control").hide();
    $("select").on("click" , function() {
  
        $(this).parent(".select-box").toggleClass("open");
      
    });

    $(document).mouseup(function (e)
    {
        var container = $(".select-box");

        if (container.has(e.target).length === 0)
        {
            container.removeClass("open");
        }
    });


    $("select").on("change" , function() {
        
        var selection = $(this).find("option:selected").text(),
            labelFor = $(this).attr("id"),
            label = $("[for='" + labelFor + "']");

        label.find(".label-desc").html(selection);
        change_detected();
    });

    if($("#lesson_type").val()=="classroom"){
        $(".notification_control").hide();

    }else if($("#lesson_type").val()=="virtual"){
        $(".notification_control").show();
        $(".start_class").find("span").text("Start Meet");
        $(".start_class").find("img").attr("src",image_resources+"google_meet.png");
        $(".virtual_link").attr("href",google_meet);
    }else if($("#lesson_type").val()=="zoom"){
        $(".notification_control").show();
        var zoom_link = main_url+"lms/lesson/zoom_checker/"+lesson_id;
        $(".start_class").find("span").text("Start Zoom");
        $(".start_class").find("img").attr("src",image_resources+"zoom.png");
        $(".virtual_link").attr("href",zoom_link);
    }

    $.each($(".select"),function(key,value){
        var selection = $(this).find("option:selected").text(),
            labelFor = $(this).attr("id"),
            label = $("[for='" + labelFor + "']");
        label.find(".label-desc").html(selection);
    });

    if(assigned){

        $.each(assigned.split(","),function(key,value){
            checked_ids.push("student_"+value);
        });
        $.jstree.reference('#jstree_demo_div').select_node(checked_ids);
    }

    function unescapeHtml(safe) {
        return safe.replace(/&amp;/g, '&')
            .replace(/&lt;/g, '<')
            .replace(/&gt;/g, '>')
            .replace(/&quot;/g, '"')
            .replace(/&#039;/g, "'");
    }

    var view_text = new Quill('#view_text', {
        theme: 'snow',
        "modules": {
            "toolbar": false
        }
    });
    $("#view_text").hide();


    
    // deploy_stored_data(stored_lesson_data);


    adjust_iframe();

    $(".instruction").hide();
    $(".instructions").show();
    $(".upload_actions").hide();

    $("loader").show();

    $(document).ready(function(){

        $(".loader").hide();
    });

    var key = "AIzaSyDPVDhRW5LcKc7giRwsZtyHSDE_3O2TXsI";
    var cx = "005829641482717962768:2e59rdva9xk";

    var youtube_keys = [
        "AIzaSyCQABaeip2nXZiL5sr1aTf0Oq3VbfPK_-k",
        "AIzaSyDPVDhRW5LcKc7giRwsZtyHSDE_3O2TXsI",
        "AIzaSyDsB_WGyzL6VpZcoxoCRGTclvh5nkWixJc",
        "AIzaSyA3pDViRGgZvU1n7GcvTlbs533Nkf5z4co",
        "AIzaSyBpB251vsnGdn7P0t2EOuBX7AtW05bYYws",
        "AIzaSyAvtCp9gxcaFC5WvOdioqLH47r_lacdyCs",
        "AIzaSyA3eZ1yVlYplPkiwNJUhbqrDG-GSm8NcyE",
        "AIzaSyDb3Ct7i14iJuTnIVitiE5zAV3WpYEQOZU",
        "AIzaSyB9TfPawPi4983afD7iS4T6tLk3IX1DQRU",
        "AIzaSyBCBJ4M9e8UwwheDswtzeEzQet2NqpNqXk",
        "AIzaSyCgCYgXFwxGQDXXJ5HQLT1-y3RHQgFeR6A",
        "AIzaSyBdGeCpF6CKj-MV7u4Y7Y0vpPN734kEQ7Q",
        "AIzaSyDjQcoNgTCMQ-3UNZKyKlUjvu_rTLMiGrk",
        "AIzaSyD5XJk6zChsrNOAkNAFq03Uimzd6snZmZU",
        "AIzaSyDprs4aO50BEfUw9JnQtCLEk2A2dwjUNU8",
        "AIzaSyCqUeIDK547rVKmvRAqGJ9DHBGsI43geFg",
        "AIzaSyAQXo8CIgqtXrrdPZP-JjNXHCHCwRiL5hU",
        "AIzaSyClbNhvbQU9A8zdtXAOhOzdaQfWxG77Dpg",
        "AIzaSyB6qupMjROOkodPGKk8cztd_I06HNP63Cc",
        "AIzaSyBdGeCpF6CKjMV7u4Y7Y0vpPN734kEQ7Q",
    ];

    var google_keys = [
        "AIzaSyCa2SdElivdp3iAh3d25YGP5mhkYyTXgxs",
        "AIzaSyDPVDhRW5LcKc7giRwsZtyHSDE_3O2TXsI",
        "AIzaSyDixpRH3jO6vOJir4JnDA1U_8DYGfOtvHo",
        "AIzaSyCKCH9YY-tNPS_hCfjdwv2gQkbVuFL67f4",
    ];
    // window.addEventListener("resize", adjust_iframe());
    function youtube_search(query,maxResults = 5){
        var youtube_api = "https://www.googleapis.com/youtube/v3/search?part=snippet&q="+query+"&type=video&maxResults="+maxResults+"&key="+youtube_keys[youtube_looper];
        $.ajax({
            url: youtube_api,
            context: document.body
        }).done(function(data) {

            console.log(google_keys[google_looper]+" Worked");
            var processed_data = process_data(data,"youtube");
            result_pool = processed_data;
            populate_search_content(processed_data);

        }).error(function(){
            console.log(youtube_keys[youtube_looper]+" Did not work!");
            youtube_looper++;
            youtube_search();

        });
    }

    function google_search(query,maxResults = 5){
        var api = "https://www.googleapis.com/customsearch/v1?key="+google_keys[google_looper]+"&cx="+cx+"&q="+query;
        $.ajax({
            url: api,
            context: document.body
        }).done(function(data) {
            console.log(google_keys[google_looper]+" Worked");
            var processed_data = process_data(data,"google");
            result_pool = processed_data;
            populate_search_content(processed_data);
        }).error(function(){

            console.log(google_keys[google_looper]+" Did not work!");
            google_looper++;
            google_search();
        });
    }

    function google_image_search(query,maxResults = 10){
        var api = "https://www.googleapis.com/customsearch/v1?key="+google_keys[google_looper]+"&cx="+cx+"&searchType=image&q="+query;
        $.ajax({
            url: api,
            context: document.body
        }).done(function(data) {
            var processed_data = process_data(data,"google_image");
            result_pool = processed_data;
            populate_search_content(processed_data);
        }).error(function(){

            console.log(google_keys[google_looper]+" Did not work!");
            google_looper++;
            google_search();
        });
    }
    function my_resources_search(query){
        var api = url+"my_resources/"+query;

        $.ajax({
            url: api,
            context: document.body
        }).done(function(data) {
            var processed_data = process_data(data,"my_resources");
            result_pool = processed_data;
            populate_search_content(processed_data);
        });
    }

    function cms_resources_search(query){
        var api = url+"cms_resources/"+query;

        $.ajax({
            url: api,
            context: document.body
        }).done(function(data) {

            var processed_data = process_data(data,"cms_resources");
            result_pool = processed_data;
            populate_search_content(processed_data);
        });
    }

    function reset_result_pool(){
        result_pool = {};
    }

    function portal_change(portal_name){
        active_portal = portal_name;
        $(".instruction").hide();
        $(".upload_actions").hide();
        if(active_portal=="my_resources"){
            $(".my_resources_instructions").show();
            $(".upload_actions").show();
        }else if(active_portal=="cms_resources"){
            $(".cms_resources_instructions").show();

        }else{
            $(".instructions").show();
        }
    }

    function reset_population(){
        $(".content_result").remove();
        reset_result_pool();
    }

    function generate_id(){
        var n = new Date().getTime();
        return n;
    }

    function sort_content_order(){
        content_order = [];
        $.each($(".folder_contents"),function(key,ul){

            $.each($(ul).find("li"),function(li_key,li_value){
                content_order.push($(li_value).attr("result_id"));
            });
        });
        change_detected();
    }

    function process_data(data,type){
        var data_population = [];
        switch(type) {
            case "youtube":
                data.items.forEach(function(item, index, arr){
                    data_population[index] = {
                        result_id:generate_id()+"_"+index,
                        title:item.snippet.title,
                        description:item.snippet.description,
                        image:encodeURIComponent(item.snippet.thumbnails.high.url),
                        type:"youtube",
                        source: encodeURIComponent("https://www.youtube.com/embed/"+item.id.videoId),
                    };

                });
                
                return data_population;
            break;
            case "google":
                data.items.forEach(function(item, index, arr){
                    var has_pagemap = item.hasOwnProperty("pagemap");
                    var has_image = false;
                    if(has_pagemap){
                        has_image = item.pagemap.hasOwnProperty("cse_image");
                    }
                    
                    data_population[index] = {
                        result_id:generate_id()+"_"+index,
                        title:item.title,
                        description:item.snippet,
                        image:$("#lms_link").val()+"images/website.png",
                        type:"website",
                        source:encodeURIComponent(item.link),
                    };
                    
                    if(has_image){
                        data_population[index].image = encodeURIComponent(item.pagemap.cse_image[0].src);
                    }

                });
                return data_population;
            break;
            case "google_image":
                data.items.forEach(function(item, index, arr){

                    data_population[index] = {
                        result_id:generate_id()+"_"+index,
                        title:item.title,
                        description:item.snippet,
                        image:encodeURIComponent(item.link),
                        type:"image",
                        source:encodeURIComponent(item.link),
                    };

                });
                return data_population;
            break;
            case "my_resources":
                
                data = JSON.parse(data);
                if(data){
                    $(".instruction").hide();
                    data.forEach(function(item, index, arr){
                        
                        data_population[index] = {
                            result_id:generate_id()+"_"+index,
                            title:item.name,
                            description:item.description,
                            text_value:item.text_value,
                            type:item.type,
                            source:encodeURIComponent(main_url+'uploads/lms_my_resources/'+item.link),
                        };
                        if(item.type=="csv"||item.type=="xlsx"||item.type=="xls"){
                            data_population[index].image = encodeURIComponent(main_url+'backend/lms/images/excel.png');
                        }else if(item.type=="pptx"||item.type=="ppt"){
                            data_population[index].image = encodeURIComponent(main_url+'backend/lms/images/powerpoint.png');
                        }else if(item.type=="docx"||item.type=="doc"){
                            data_population[index].image = encodeURIComponent(main_url+'backend/lms/images/word.svg');
                        }else if(item.type=="image"){
                            data_population[index].image = encodeURIComponent(main_url+'uploads/lms_my_resources/'+item.link);
                        }else if(item.type=="video"){
                            data_population[index].image = encodeURIComponent(main_url+'backend/lms/images/video.svg');
                        }else if(item.type=="text"){
                            data_population[index].image = encodeURIComponent(main_url+'backend/lms/images/text.png');
                        }else if(item.type=="youtube"){
                            if(item.image){
                                data_population[index].image = encodeURIComponent(item.image);
                            }else{
                                data_population[index].image = encodeURIComponent(main_url+'backend/lms/images/video.svg');
                            }
                            

                            
                            data_population[index].source = encodeURIComponent(item.link);
                        }
                        else if(item.type=="website"){
                            
                            data_population[index].image = encodeURIComponent(main_url+'backend/lms/images/video.svg');
                            
                            data_population[index].source = encodeURIComponent(item.link);
                        }
                        

                    });
                }else{
                    $(".instruction").show();
                }
                return data_population;
            break;
            case "cms_resources":

                data = JSON.parse(data);

                if(data){

                    data.forEach(function(item, index, arr){
                        
                        data_population[index] = {
                            result_id:generate_id()+"_"+index,
                            title:item.name,
                            description:item.description,
                            image:encodeURIComponent(item.link),
                            type:item.type,
                            source:main_url+'uploads/lms_cms_resources/'+item.filename,

                        };
                        
                    });

                    console.log(data_population);
                }
                
                return data_population;
            break;
            default:
                return null;
        }
    }
    function update_active_content(data){
        active_content = data;
    }

    function get_content_data(result_id) {
        var return_data;
        content_pool.forEach(function(item, index, arr){
            if(result_id == item.content.result_id){
                return_data = item;
            }
        });
        return return_data;
    }

    function adjust_iframe(){
        switch (screen.height) {
            case 720:
                // $(".student_view_content_iframe").css("height","537px");
            break;
            case 900:
                // $(".student_view_content_iframe").css("height","717px");
            break;
        }
    }

    function render_student_view(){
        
        $(".content_type").hide();
        $(".video_content").attr("src","");
        if(active_content_data){
            $(".student_view_title").text(active_content_data.content.title);
            switch (active_content_data.content.type){
                case "youtube":
                    $(".student_view_content_iframe").show();
                    $(".student_view_content_iframe").attr("src",decodeURIComponent(active_content_data.content.source));
                break;
                case "image":

                    $(".image_content").show();
                    $(".image_content").css("height",screen.height);
                    $(".image_content").attr("src",decodeURIComponent(active_content_data.content.source));
                    $(".image_content").attr("data-src",decodeURIComponent(active_content_data.content.source));
                    $(document).find('[data-magnify=gallery]').magnify();
                break;
                case "video":
                    $(".video_content").show();
                    $(".video_content").css("height",screen.height-180);
                    $(".video_content").attr("src",decodeURIComponent(active_content_data.content.source));
                    $(".video_content").bind('contextmenu',function() { return false; });
                break;
                case "website":
                    $(".student_view_content_iframe").show();
                    $(".student_view_content_iframe").css("height",screen.height-180);
                    $(".student_view_content_iframe").attr("src",decodeURIComponent(active_content_data.content.source));
                break;
                case "pdf":

                    $(".student_view_content_iframe").show();
                    $(".student_view_content_iframe").css("height",screen.height-180);
                    $(".student_view_content_iframe").attr("src",$("#pdfjs").val()+active_content_data.content.source);
                break;
                case "text":
                    $(".html_content").show();
                    $(".html_content").css("height",screen.height-180);
                    $(".html_content").css("background-color","#d2cece");
                    view_text.setContents(JSON.parse(unescapeHtml(active_content_data.content.text_value)));
                    var rendered_text = view_text.container.innerHTML;
                    $(rendered_text).find("div[contenteditable*='true']").attr("contenteditable","false");
                    $(".html_content").html($(rendered_text).html());
                break;
                default:
                    $(".student_view_content_iframe").show();
                    $(".student_view_content_iframe").css("height",screen.height-180);
                    $(".student_view_content_iframe").attr("src",'https://view.officeapps.live.com/op/view.aspx?src='+decodeURIComponent(active_content_data.content.source)+'&embedded=true');
                
                break;
            }
            $(".student_view_title").text(active_content_data.content.title);
        }else{

        }
    }

    function reset_student_view(){
        $(".student_view_content_iframe").attr("src","");
    }
    function get_current_order(){
        var return_key = 0;
        $.each(content_order,function(key,value){

            if(active_content_data.content.result_id==value){
                
                return_key = key;
            }
        });
        return return_key;
    }

    function get_slides(order){
        active_content_data = get_content_data(content_order[order]);
        active_content = content_order[order];
        render_student_view();
    }

    function get_next(){
        var end_order = content_order.length-1;
        var current_order = get_current_order();
        
        if(current_order<end_order){
            var next_order = current_order+1;
            active_content_data = get_content_data(content_order[next_order]);
            active_content = content_order[next_order];
            render_student_view();
        }else{
            $(".next").attr("disabled","disabled");
        }
    }
    function get_previous(){
        var current_order = get_current_order();
        
        if(current_order>0){
            var next_order = current_order-1;
            active_content_data = get_content_data(content_order[next_order]);
            active_content = content_order[next_order];
            render_student_view();
        }
    }

    function populate_slides(){
        $(".actual_slide").remove();
        $.each(content_order,function(key,value){
            var new_slide = $(".slide_clone").clone();
            new_slide.css("display","inline-block");
            new_slide.addClass("actual_slide");
            new_slide.removeClass("slide_clone");
            var new_slide_data = decodeURIComponent(get_content_data(value).content.image);
            new_slide.find("img").attr("src",new_slide_data);
            $(".student_view_slides").append(new_slide);
        });
        check_active_thumbnail();
    }

    function check_active_thumbnail(){
        $(document).find(".slide_active").removeClass("slide_active");
        $.each(content_order,function(key,value){
            if(active_content == value){
                $(".actual_slide").eq(key).addClass("slide_active");
            }
        });
    }

    function remove_content(result_id){
        content_order = $.grep(content_order, function(value) {
            return value != result_id;
        });

        $.each(content_pool,function(key,value){
            
            if(value){

                if($.inArray(value.content.result_id, content_order)<0){
                    delete content_pool[key];
                }

            }
            // if(value.content.result_id == result_id){
            //     content_pool.splice(key,1);
            // }
        });
        var reiterate_content_pool = [];
        $.each(content_pool,function(key,value){
            if(value){
                reiterate_content_pool.push(value);
            }
            
        });
        content_pool = reiterate_content_pool;


    }

    function download(content, fileName, contentType) {
        var a = document.createElement("a");
        var file = new Blob([content], {type: contentType});
        a.href = URL.createObjectURL(file);
        a.download = fileName;
        a.click();
    }

    function change_detected(){

        var title = $(".title").val();
        var update_url = $("#site_url").val();
        var id = $("#lesson_id").val();
        var lesson_type = $("#lesson_type").val();
        var email_notification = $("#email_notification").prop("checked");
        var allow_view = $("#allow_view").prop("checked");
        var start_date = $(".date_range").data('daterangepicker').startDate.toDate();
        var end_date = $(".date_range").data('daterangepicker').endDate.toDate();
        // var learning_plan_text = JSON.stringify(learning_plan.getContents());
        var subject_id = $("#subject").val();
        var grade_id = $("#grade").val();
        education_level = $("#education_level").val();
        var term = $("#term").val();
        var shared = $("#shared").val();
        start_date = moment(start_date).format("YYYY-MM-DD HH:mm:ss");
        end_date = moment(end_date).format("YYYY-MM-DD HH:mm:ss");
        
        if(email_notification){
            email_notification = "1";
        }else{
            email_notification = "0";
        }
        if(allow_view){
            allow_view = "1";
        }else{
            allow_view = "0";
        }

        var student_ids = [];
        $.each(jstree.jstree("get_checked",null,true),function(key,value){
            
            if(value.includes('student')){
                student_id = value.replace('student_','');
                
                student_ids.push(student_id);
            }
        });
        lesson_data = {
            id:id,
            title:title,
            content_order:content_order,
            content_pool:content_pool,
            lesson_type:lesson_type,
            email_notification:email_notification,
            start_date:start_date,
            end_date:end_date,
            allow_view:allow_view,
            learning_plan:tinymce.get('the_learning_plan').getContent(),
            assigned:student_ids.join(','),
            folder_names:"Engage,Explore,Explain,Extend,LAS",
            subject_id:subject_id,
            grade_id:grade_id,
            education_level:education_level,
            term:term,
            shared:shared,
        };


        $.ajax({
            url: update_url,
            method:"POST",
            data: lesson_data,
        }).done(function(data) {
            // console.log(data);
        });

    }

    function deploy_stored_data(){
        var id = $("#lesson_id").val();
        var get_url = $("#url").val()+"get/"+id;
        //jstree
        var checked_ids = [];
        if(assigned){

            $.each(assigned.split(","),function(key,value){
                checked_ids.push("student_"+value);
            });
            $.jstree.reference('#jstree_demo_div').select_node(checked_ids);
        }
        //jstree
        $.ajax({
            url: get_url,
            method:"POST",
        }).done(function(data) {

            var parsed_data = JSON.parse(data);
            content_order = JSON.parse(parsed_data.content_order);

            if(JSON.parse(parsed_data.content_pool)){
                content_pool = JSON.parse(parsed_data.content_pool);
            }else{
                content_pool = [];
            }
            
            folder_names = parsed_data.folder_names;

            populate_content(content_pool);
        });

    }



    $( function() {
        $(folders+",#result_container" ).sortable({
            connectWith: ".connectedSortable",
            receive: function(event,ui) {

                if (this === ui.item.parent()[0]) {
                    if(ui.item.parent().attr("id")!="result_container"){

                        $(ui.item[0]).removeClass('content_result');
                        $(ui.item[0]).addClass('content_already');
                        var result_id = $(ui.item[0]).attr("result_id");
                        
                        result_pool.forEach(function(item, index, arr){
                            if(result_id==item.result_id){
                                item.status = "fresh";
                                item.state = "online";
                                item.blackboard_id = blackboard_id;
                                var folder_id = ui.item.parent().attr("id");
                                var content = {folder_id:folder_id,content:item}
                                content_pool.push(content);
                            }
                        });

                    } 
                    
                }
                sort_content_order();
                
            },
            stop:function(event,ui){
                sort_content_order();
            },
            beforeStop: function(ev, ui) {
                if ($(ui.item).hasClass('content_already') && $(ui.placeholder).parent()[0] != this) {
                    $(this).sortable('cancel');
                }
            }
        }).disableSelection();
    });

    $("#learning_plan_save").click(function(){
        change_detected();
        alert("Learning Plan successfully saved");
    });

    $(".extremeright_icon").click(function(){
        var portal_name = $(this).attr("portal");
        $(".extremeright_icon").removeClass("icon_active");
        $(this).addClass("icon_active");
        portal_change(portal_name);
        reset_population();
        $(".submit_button").click();
    });
    $('.trigger').click(function() {
        // $('.slider').toggleClass('close');
        var trigger_id = $(this).attr("id");
        
        if($('.'+trigger_id+'_slider').hasClass('close')){
            
            $(".trigger").removeClass('active_trigger');
            $('.slider').addClass('close');
            $(this).addClass('active_trigger');
            $('.'+trigger_id+'_slider').removeClass('close');
        }else{
            $(this).removeClass('active_trigger');
            $('.'+trigger_id+'_slider').addClass('close');
        }
        

    });

    $(".folder_container").hide();
    $(".folder_container").eq(0).show();
    $(".folder").click(function(){
        var folder_index = $(this).index();
        $('.folder').removeClass('folder_active');
        $(this).addClass('folder_active');
        $(".folder_container").hide();
        $(".folder_container").eq(folder_index).show();

    });

    

    function populate_search_content(data){
        var populous = $(".content_hidden").clone();
        
        data.forEach(function(item, index, arr){
            populous = $(".content_hidden").clone();
            $(populous).removeClass("content_hidden");
            $(populous).addClass("content_result");
            $(populous).show();
            switch(item.type) {
                case "youtube":
                    $(populous).find(".theme").css("background-color","rgb(255, 68, 68)");
                break;
                case "website":
                    $(populous).find(".theme").css("background-color","rgb(0, 84, 169)");
                break;
                case "image":
                    $(populous).find(".theme").css("background-color","rgb(56, 177, 55)");
                break;
                case "csv"||"xls"||"xlsx":
                    $(populous).find(".theme").css("background-color","rgb(33, 115, 70)");
                break;
                case "text":
                    $(populous).find(".theme").css("background-color","rgb(33, 115, 70)");
                break;
                case "docx":
                    $(populous).find(".theme").css("background-color","rgb(42, 86, 153)");
                break;
                case "epub":
                    $(populous).find(".theme").css("background-color","rgb(56, 177, 55)");
                break;
                case "pptx":
                    $(populous).find(".theme").css("background-color","rgb(56, 177, 55)");
                break;

            }
            if(item.type!="text"){
                $(populous).attr("result_id",item.result_id);
                $(populous).find(".content_header").find("span").text(item.title);
                $(populous).find(".content_body").find("img").attr("src",decodeURIComponent(item.image));
                $(populous).find(".content_footer").find("textarea").text(item.description);
                $(".search_content").last().after($(populous));
            }else{
                $(populous).attr("result_id",item.result_id);
                $(populous).find(".content_header").find("span").text(item.title);
                $(populous).find(".content_body").find("img").after("<div id='"+item.result_id+"' class='text_content'></div>");
                $(populous).find(".content_body").find("img").remove();
                view_text.setContents(JSON.parse(unescapeHtml(item.text_value)));
                $(populous).find(".content_body").find(".text_content").html(view_text.container.innerHTML);
                $(populous).find(".content_footer").find("textarea").text(item.description);
                $(".search_content").last().after($(populous));
            }
            
            
        });
    }

    function populate_content(data){
        var populous = $(".content_hidden").clone();
        var sorted_data = [];

        if(content_order){
            content_order.forEach(function(content_id, index_order, arr_order){

            
                data.forEach(function(item, index, arr){

                    if(item.content.result_id==content_id){
                        sorted_data.push(item);
                    }

                });

            });
            
            sorted_data.forEach(function(item, index, arr){
                populous = $(".content_hidden").clone();
                $(populous).removeClass("content_hidden");
                $(populous).show();
                switch(item.content.type) {
                    case "youtube":
                        $(populous).find('.theme').css("background-color","rgb(255, 68, 68)");
                    break;
                    case "video":
                        $(populous).find('.theme').css("background-color","rgb(255, 68, 68)");
                    break;
                    case "website":
                        $(populous).find('.theme').css("background-color","rgb(0, 84, 169)");
                    break;
                    case "pdf":
                        $(populous).find('.theme').css("background-color","rgb(0, 84, 169)");
                    break;
                    case "image":
                        $(populous).find('.theme').css("background-color","rgb(56, 177, 55)");
                    break;
                    case "text":
                        $(populous).find('.theme').css("background-color","rgb(33, 115, 70)");
                    break;
                }


                if(item.content.type!="text"){
                    $(populous).attr("result_id",item.content.result_id);
                    $(populous).find(".content_header").find("span").text(item.content.title);

                    $(populous).find(".content_body").find("img").attr("src",decodeURIComponent(item.content.image));
                    $(populous).find(".content_footer").find("textarea").text(item.content.description);
                    $(populous).removeClass('content_result');
                    $(populous).addClass('content_already');
                }else{

                    $(populous).attr("result_id",item.content.result_id);
                    $(populous).find(".content_header").find("span").text(item.content.title);
                    $(populous).find(".content_body").find("img").after("<div id='"+item.content.result_id+"' class='text_content'></div>");
                    $(populous).find(".content_body").find("img").remove();
                    $(populous).addClass('content_already');

                    view_text.setContents(JSON.parse(unescapeHtml(item.content.text_value)));

                    $(populous).find(".content_body").find(".text_content").html(view_text.container.innerHTML);
                    $(populous).find(".content_footer").find("textarea").text(item.content.description);
                    $(".search_content").last().after($(populous));
                }
                $("#"+item.folder_id).append($(populous));

                
            });
        }else{
            console.log("No content");
        }
        
    }
    $("#search_portal").on("keyup",function(event){
        if (event.keyCode === 13) {
            event.preventDefault();
            reset_population();
            $(".submit_button").click();
        }
    });

    $(".submit_button").on("click",function(){
        var search = $("#search_portal").val();
        switch(active_portal) {
            case "youtube":
               youtube_search(search);
            break;
            case "google":
               google_search(search);
            break;
            case "google_image":
               google_image_search(search);
            break;
            case "my_resources":
               my_resources_search(search);
            break;
            case "cms_resources":
               cms_resources_search(search);
            break;
            default:
                return null;
        }

        
    });
    
    $(".slideshow_action").click(function(){
        
        $(".edit_area").toggleClass("close_edit");
        $(".student_view").toggleClass("student_view_close");


        active_content_data = get_content_data(content_order[0]);
        active_content = content_order[0];

        adjust_iframe();
        render_student_view();
        populate_slides();
    });
    
    $(document).on("dblclick",".content_already",function(){

        var result_id = $(this).attr("result_id");
        update_active_content(result_id);
        active_content_data = get_content_data(result_id);
        $(".edit_area").toggleClass("close_edit");
        $(".student_view").toggleClass("student_view_close");
        render_student_view();
    });
    $(".student_view-title").click(function(){

        $(".student_view-slideshows").toggleClass("slideshows_close");

    });
    $(".next").click(function(){
        get_next();
        check_active_thumbnail();
    });
    $(".previous").click(function(){
        get_previous();
        check_active_thumbnail();
    });
    $('.student_view_slides').hide();
    $(".student_view_title").click(function(){
        
        $('.student_view_slides').slideToggle("fast");
    });

    $('.student_view_slides').mousewheel(function(e, delta) {
        this.scrollLeft -= (delta * 40);
        e.preventDefault();
    });
    $(".slide").click(function(){
        
        $(".slide").removeClass("slide_active");
        $(this).addClass("slide_active");

    });
    
    $(document).on("dblclick",".content_already",function(){
        populate_slides();
    });
    $(document).on("click",".actual_slide",function(){
        var the_index = $(this).index()-1;
        get_slides(the_index);
        check_active_thumbnail();
    });
    $(document).on("mouseover",".content_already",function(){
        $(this).find(".content_close").show();
    });
    $(document).on("mouseout",".content_already",function(){
        $(this).find(".content_close").hide();
    });
    $(document).on("click",".content_close",function(){
        var delete_li = $(this).parent().parent();
        var delete_result_id = $(this).parent().parent().attr("result_id");
        remove_content(delete_result_id);
        $(delete_li).remove();
        change_detected();
    });
    $(document).on("click",".content_footer",function(){
        // $(this).parent().find(".content_body").find("img").addClass("hide_img");
        // $(this).parent().find(".content_footer").find("span").addClass("description_display");
        // $(this).parent().find(".content_footer").find("textarea").addClass("description_display");
        
    });

    function check_offline_files(){
        console.log("Check Offline Files Running...");
        $(document).find(".content_already");
        var check_data = [];
        $.each(content_pool,function(key,value){
            check_data.push({key:value.content.result_id,data:value});
        });
        $.ajax({
            url: url+'check_offline_files',
            method:"POST",
            data: {data:check_data,blackboard_id:blackboard_id},
        }).done(function(data) {

            if(data!="empty"){

                var parsed_data = JSON.parse(data);
                
                if(parsed_data.length){

                    $.each(parsed_data,function(parsed_data_key,parsed_data_value){
                        

                        $.each(content_pool,function(content_pool_key,content_pool_value){

                            if(content_pool_value.content.result_id == parsed_data_value.key){
                                content_pool[parsed_data_key].content.source = parsed_data_value.source;
                                content_pool[parsed_data_key].content.type = parsed_data_value.type;
                                content_pool[parsed_data_key].content.status = parsed_data_value.status;
                                var the_li = $("li[result_id='"+content_pool[parsed_data_key].content.result_id+"']");
                                if(parsed_data_value.status == "checking"){
                                    the_li.find(".download_status_container").css("background-color","#427fed");
                                    the_li.find(".download_status_container").find("span").text("Checking...");
                                }else if(parsed_data_value.status == "completed"){
                                    the_li.find(".download_status_container").css("background-color","green");
                                    the_li.find(".download_status_container").find("span").text("O2O Available");
                                }else if(parsed_data_value.status == "downloading"){
                                    the_li.find(".download_status_container").css("background-color","#e228ba");
                                    the_li.find(".download_status_container").find("span").text("O2O In Progress");
                                }else if(parsed_data_value.status == "not_downloadable"){
                                    the_li.find(".download_status_container").css("background-color","#427fed");
                                    the_li.find(".download_status_container").find("span").text("Available Online");
                                }
                            }
                        });
                        

                        
                    });
                    console.log("File Check Complete");
                    
                }else{

                }
            }else{
                console.log("Empty");
            }
            
        });
    }

    function check_thumbnails(){
        console.log("Check thumbnails running...");
        var check_data = [];
        $.each(content_pool,function(key,value){
            check_data.push({key:key,result_id:value.content.result_id,image:value.content.image,type:value.content.type});
        });

        $.ajax({
            url: url+'check_thumbnail',
            method:"POST",
            data: {data:check_data,blackboard_id:blackboard_id},
        }).done(function(data) {

            if(data!="empty"){
                var parsed_data = JSON.parse(data);
                if(parsed_data.length){
                    
                    $.each(parsed_data,function(parsed_data_key,parsed_data_value){
                        $.each(content_pool,function(content_pool_key,content_pool_value){
                            if(parsed_data_value.key == content_pool_value.content.result_id){
                                content_pool[parsed_data_key].content.image = encodeURIComponent(parsed_data_value.offline_thumbnail);
                            }

                        });
                    });
                    // change_detected();
                }else{

                }
            }else{

                console.log("No content found");
            }
            
            
            
            
        });
    }

    $(document).ready(function(){
        
        deploy_stored_data();
        
        
    });
    
    $(".title").change(function(){
        change_detected();
    });
    
    $("#lesson_type").change(function(){

        var the_val = $(this).val();
        if(the_val=="classroom"){
            $(".notification_control").hide();
            // $(".start_class").hide("slide", {direction: "right"}, 1000);
            $(".start_class").animate({width:"0"},400,function(){
                $(".start_class").hide();
                $(".actions").not(".start_class").animate({width:"16.5%"});
            });
            
            
        }else if(the_val=="virtual"){
            $(".notification_control").show();
            $(".actions").not(".start_class").animate({width:"14%"},400,function(){
                $(".start_class").show();
                $(".start_class").animate({width:"14%"});

            });

            $(".start_class").find("span").text("Start Meet");
            $(".start_class").find("img").attr("src",image_resources+"google_meet.png");
            $(".virtual_link").attr("href",google_meet);
        }else if(the_val=="zoom"){
            // $(".zoom_modal_container").show("slow","linear");
            // check_zoom_schedule();
            $(".notification_control").show();
            $(".actions").not(".start_class").animate({width:"14%"},400,function(){
                $(".start_class").show();
                $(".start_class").animate({width:"14%"});

            });
            var zoom_link = main_url+"lms/lesson/zoom_checker/"+lesson_id;
            console.log(zoom_link);
            $("#start_class").find("span").text("Start Zoom");
            $("#start_class").find("img").attr("src",$("#image_resources").val()+"zoom.png");
            $(".virtual_link").attr("href",zoom_link);
            
        }else{
            $(".start_class").animate({width:"0"},400,function(){
                $(".start_class").hide();
                $(".actions").not(".start_class").animate({width:"16.5%"});
            });
        }

    });
    $(".my_upload_button").click(function(){
        $(".upload_input").click();
    });
    $(".upload_input").change(function(){
        $(".upload_form").submit();
    });
    $(".upload_form").on("submit",function(e){
        e.preventDefault();
        var upload_url = url+"upload/my_resources/"+lesson_id;
        $.ajax({
            url: upload_url,
            type: "POST",
            data:  new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
       
            success: function(data)
            {
               console.log(data);
               $("div[portal='my_resources']").click();
            },
            error: function(e){
                alert("error");
            }
        });
    });

    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("add_text_close")[0];

    // When the user clicks on the button, open the modal
    btn.onclick = function() {
      modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
      modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }

    // Get the modal
    var vimeo_modal = document.getElementById("vimeo_modal");

    // Get the button that opens the modal
    var vimeo_btn = document.getElementById("vimeo_btn");

    // Get the <span> element that closes the modal
    var vimeo_span = document.getElementsByClassName("vimeo_modal_close")[0];

    // When the user clicks on the button, open the modal
    vimeo_btn.onclick = function() {
      vimeo_modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    vimeo_span.onclick = function() {
      vimeo_modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == vimeo_modal) {
        vimeo_modal.style.display = "none";
      }
    }

    
    var learning_plan = new Quill('#learning_plan_text', {
        theme: 'snow',
        modules: {
            table: true,
        }
    });
    var objective = new Quill('#objective_text', {
        theme: 'snow',
    });
    var add_text = new Quill('#add_text', {
        theme: 'snow',
    });


    // add_text.setHtml('<div class="ql-editor" data-gramm="false" contenteditable="true"><p>asdfasdfasfd</p></div><div class="ql-clipboard" contenteditable="true" tabindex="-1"></div><div class="ql-tooltip ql-hidden"><a class="ql-preview" target="_blank" href="about:blank"></a><input type="text" data-formula="e=mc^2" data-link="https://quilljs.com" data-video="Embed URL"><a class="ql-action"></a><a class="ql-remove"></a></div>');

    $(".add_text_done").click(function(){
        var text_title = $("#text_title").val();
        var text_value = JSON.stringify(add_text.getContents());
        var add_text_url = url+"upload/add_text/"+lesson_id;
        console.log(text_value);
        $.ajax({
            url: add_text_url,
            type: "POST",
            data: {title:text_title,text_value:text_value},
       
            success: function(data)
            {
               console.log(data);
               $("div[portal='my_resources']").click();
            },
            error: function(e){
                alert("Something Went Wrong");
            }
        });

    });

    $(".vimeo_modal_done").click(function(){
        var link = $("#vimeo_url").val();
        var name = $("#vimeo_title").val();
        var description = $("#vimeo_description").val();
        var type = "youtube";
        var vimeo_url = url+"upload/vimeo/"+lesson_id;
        $.ajax({
            url: vimeo_url,
            type: "POST",
            data: {link:link,name:name,type:type,description:description},
       
            success: function(data)
            {
                console.log(data);
               // console.log(data);
               $("div[portal='my_resources']").click();
            },
            error: function(e){
                alert("Something Went Wrong");
            }
        });

    });

    $(".close_student_view").click(function(){
        $(".video_content").attr("src","");
        $(".edit_area").toggleClass("close_edit");
        $(".student_view").toggleClass("student_view_close");
        reset_student_view();
    });

    if(role2!="admin"){
        $(".close_student_view").click(function(){
            window.location.replace(url+"index");
        });
        setTimeout(function(){
            $(".slideshow_action").click();
        },1500);
        $(".edit_area").hide();
    }

    $(".assign_save").click(function(){
        change_detected();
        send_email_notification();
        alert("Lesson has been assigned successfully");
    });

    $(".date_range").change(function(e){
        change_detected();
        // if($("#lesson_type").val()=="zoom"){
        //     if(confirm("Changing the schedule will remove you from the current slot. Are you sure you want to change the schedule?")){
                
        //         check_zoom_schedule();
        //     }
        // }
    });

    function send_emails_now(){
        var send_email_notification_url = $("#url").val()+"send_email_notification_godaddy";
        var student_ids = [];
        $.each(jstree.jstree("get_checked",null,true),function(key,value){
            
            if(value.includes('student')){
                student_id = value.replace('student_','');
                
                student_ids.push(student_id);
            }
        });
        
        var student_ids = encodeURIComponent(student_ids.join(','));
        var lesson_id = $("#lesson_id").val();
        var email_notification = $("#email_notification").prop("checked");

        // window.open(send_email_notification_url+"/"+lesson_id+"/"+email_notification+"/"+student_ids,"_blank");
        $.ajax({
            url: send_email_notification_url+"/"+lesson_id+"/"+email_notification+"/"+student_ids,
            type: "GET",
            // data: {
            //     student_ids:student_ids,
            //     lesson_id:$("#lesson_id").val(),
            //     email_notification:$("#email_notification").prop("checked"),
            // },
       
            success: function(data)
            {
                console.log(data);
                var the_data = JSON.parse(data);
               
            },
            error: function(e){

            }
        });

    }
    $("#send_emails_now").click(function(){
        send_emails_now();
    });
    function send_email_notification(){
        var send_email_notification_url = $("#url").val()+"send_email_notification_godaddy";
        var student_ids = [];
        $.each(jstree.jstree("get_checked",null,true),function(key,value){
            
            if(value.includes('student')){
                student_id = value.replace('student_','');
                
                student_ids.push(student_id);
            }
        });
        $.ajax({
            url: send_email_notification_url,
            type: "POST",
            data: {
                student_ids:student_ids,
                lesson_id:$("#lesson_id").val(),
                email_notification:$("#email_notification").prop("checked"),
            },
       
            success: function(data)
            {
                console.log(data);
                var the_data = JSON.parse(data);
               
            },
            error: function(e){

            }
        });
    }
});

if($(".start_date").val()){
    $('.date_range').daterangepicker({
        timePicker: true,
        startDate: moment($(".start_date").val()),
        endDate: moment($(".end_date").val()),
        locale: {
          format: 'MMMM DD hh:mm A'
        }
    });
}else{

    $('.date_range').daterangepicker({
        timePicker: true,
        startDate: moment().startOf('hour').add(1, 'minute'),
        endDate: moment().startOf('hour').add(1, 'hour'),
        locale: {
          format: 'MMMM DD hh:mm A'
        }
    });
}



function fetch_chat(){

    var fetch_chat_url = $(url).val()+"fetch_chat";
    var chat_lesson_id = $(lesson_id).val();

    $.ajax({
        url: fetch_chat_url,
        type: "POST",
        data: {lesson_id:chat_lesson_id},
   
        success: function(data)
        {
            var chats = JSON.parse(data);
            var chat_html = '';
            var user_type = '';
            $(".dicussion_container").empty();
            $.each(chats,function(key,value){

                if(value.account_type=="admin"){

                    user_type = value.firstname+" "+value.lastname;
                }else{
                    user_type = value.firstname+" "+value.lastname;
                }
                chat_html = '<div class="chat_container">';
                chat_html += '<div class="the_chat">';           
                chat_html += '<span class="user_type">'+user_type+': </span><span class="chat_content">'+value.content+'</span>';           
                chat_html += '</div>';
                chat_html += '</div>';
                // $(chat_clone).find(".chat_content");
                // $(chat_html).find(".chat_content").text("asdasd");
                $(".dicussion_container").append(chat_html);
            });


        },
        error: function(e){

        }
    });

}



fetch_chat();
// setInterval(function(){
//     fetch_chat();
// },5000);

function send_chat(student_chat){
    var chat_url = $(url).val()+"send_chat";
    if(student_chat){
        var chat_content = student_chat;
    }else{
        var chat_content = $(".chat_text").eq(0).val();
    }
    
    var chat_lesson_id = $(lesson_id).val();
    

    $.ajax({
        url: chat_url,
        type: "POST",
        data: {content:chat_content,lesson_id:chat_lesson_id},
   
        success: function(data)
        {
           console.log(data);
           $(".chat_text").val('');
           fetch_chat();
        },
        error: function(e){

        }
    });
}
function check_zoom_schedule(){
    var check_zoom_schedule_url = $(url).val()+"check_zoom_schedule";
    var start_date = $(".date_range").data('daterangepicker').startDate.toDate();
    var end_date = $(".date_range").data('daterangepicker').endDate.toDate();

    start_date = moment(start_date).format("YYYY-MM-DD HH:mm:ss");
    end_date = moment(end_date).format("YYYY-MM-DD HH:mm:ss");
    $.ajax({
        url: check_zoom_schedule_url,
        type: "POST",
        data: {
            start_date:start_date,
            end_date:end_date,
            lesson_id:$(lesson_id).val(),
            account_id:$(account_id).val(),
        },
   
        success: function(data)
        {
            var the_data = JSON.parse(data);
            $(".zoom_label").text(the_data.message);
            if(the_data.status=="success"){
                $(".zoom_email_used").text("("+the_data.zoom_email+")");
                $(".notification_control").show();
                $(".actions").not(".start_class").animate({width:"14%"},400,function(){
                    $(".start_class").show();
                    $(".start_class").animate({width:"14%"});

                });

                $(".start_class").find("span").text("Start Zoom");
                $(".start_class").find("img").attr("src",$(image_resources).val()+"zoom.png");
                $(".virtual_link").attr("href",the_data.start_url);
            }else{

            }
            $(".zoom_modal_container").hide("slow","linear");
        },
        error: function(e){

        }
    });
}

$(".teacher_tools_button").click(function(){
    $(".teacher_tools").toggle();
    var width = document.getElementById('teacher_tools').offsetWidth;
    $(".student_view_slides").hide();
    $(".student_view_content").toggle();
    $("#classroomscreen").css("width",width+70);
});

$(".formula_board_button").click(function(){
    $(".formula_board").toggle();
    // var width = document.getElementById('formula_board').offsetWidth;
    $(".student_view_slides").hide();
    $(".student_view_content").toggle();
    // $("#classroomscreen").css("width",width+70);
});

$(".annotate_button").click(function(){
    var canvas = document.getElementById('canvas'),
            context = canvas.getContext('2d');
            
    if($(this).hasClass("annotate_active")){

        $(".annotate").css("display","none");
        $(this).removeClass("annotate_active");

        
            canvas.width = 0;
            canvas.height = 0;

    }else{
        $(".annotate").css("display","block");
        $(this).addClass("annotate_active");
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }
    // var width = document.getElementById('formula_board').offsetWidth;
    // $(".student_view_slides").hide();
    // $(".student_view_content").toggle();
    // $("#classroomscreen").css("width",width+70);
});
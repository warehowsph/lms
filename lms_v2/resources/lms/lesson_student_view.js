$(document).ready(function(){
  var content_pool;

  function deploy_stored_data(){
      var id = $("#lesson_id").val();
      var get_url = $("#url").val()+"api_get_lesson/"+id;

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
          active_content_data = get_content_data(content_order[0]);
          active_content = content_order[0];
          populate_slides();
          render_student_view();
      });

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

  function get_content_data(result_id) {
      var return_data;
      content_pool.forEach(function(item, index, arr){
          if(result_id == item.content.result_id){
              return_data = item;
          }
      });
      return return_data;
  }

  function check_active_thumbnail(){
      $(document).find(".slide_active").removeClass("slide_active");
      $.each(content_order,function(key,value){
          if(active_content == value){
              $(".actual_slide").eq(key).addClass("slide_active");
          }
      });
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

  function get_current_order(){
      var return_key = 0;
      $.each(content_order,function(key,value){

          if(active_content_data.content.result_id==value){

              return_key = key;
          }
      });
      return return_key;
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
                  $(".student_view_content_iframe").attr("src",$("#pdfjs").val()+active_content_data.content.source + "&embedded=true");
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

  function get_slides(order){
      active_content_data = get_content_data(content_order[order]);
      active_content = content_order[order];
      render_student_view();
  }

  deploy_stored_data();










  $(".student_view_title").click(function(){

      $('.student_view_slides').slideToggle("fast");
  });

  $(".next").click(function(){
      get_next();
      check_active_thumbnail();
  });
  $(".previous").click(function(){
      get_previous();
      check_active_thumbnail();
  });

  $(document).on("click",".actual_slide",function(){
      var the_index = $(this).index()-1;
      get_slides(the_index);
      check_active_thumbnail();
  });

});

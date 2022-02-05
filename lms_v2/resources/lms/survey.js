var url = $("#url").val();
var stored_json = $("#stored_json").val();
var final_json = {};
var letters_array = ["A","B","C","D"];

$(".sortable").sortable({
	stop:function(event,ui){
		renumbering();
	}
});
$(".option-container-clonable").hide();

function isEmpty(obj) {
  for(var prop in obj) {
    if(obj.hasOwnProperty(prop)) {
      return false;
    }
  }

  return JSON.stringify(obj) === JSON.stringify({});
}

function populate_key(option_type,data={}){
	var option_clone = $(".option-container-clonable").clone();
	
	switch (option_type){
		case "multiple_choice":
			option_clone.removeClass("option-container-clonable");
			option_clone.addClass("option-container-actual");
			option_clone.addClass("multiple_choice");
			option_clone.attr("option_type","multiple_choice");
			option_clone.show();
			
			if(!isEmpty(data)){
				
			}

			$(".sortable").append(option_clone);
		break;
		case "multiple_answer":
			option_clone.removeClass("option-container-clonable");
			option_clone.addClass("option-container-actual");
			option_clone.addClass("multiple_choice");
			option_clone.attr("option_type","multiple_answer");
			option_clone.show();
			option_clone.find(".option_type").find("input").attr("type","checkbox");
			$(".sortable").append(option_clone);
		break;
		case "short_answer":
			option_clone.removeClass("option-container-clonable");
			option_clone.addClass("option-container-actual");
			option_clone.addClass("short_answer");
			option_clone.show();
			option_clone.attr("option_type","short_answer");
			option_clone.find(".option_type").find("input").attr("type","text");
			option_clone.find(".option_type").find("input").css("width","100%");
			option_clone.find(".option_label_input").find("input").remove();
			option_clone.find(".add_option").remove();
			$(".sortable").append(option_clone);
		break;
		case "long_answer":
			option_clone.removeClass("option-container-clonable");
			option_clone.addClass("option-container-actual");
			option_clone.addClass("short_answer");
			option_clone.show();
			option_clone.attr("option_type","long_answer");
			option_clone.find(".option_type").empty();
			option_clone.find(".option_type").html('<textarea class="form-control"></textarea>');
			option_clone.find(".option_type").find("textarea").css("width","100%");
			option_clone.find(".option_label_input").find("input").remove();
			option_clone.find(".add_option").remove();
			$(".sortable").append(option_clone);
		break;
	}
}

function renumbering(){
	var total_number = $(".option-container-actual");
	$.each(total_number,function(key,value){
		$(value).find(".numbering_option").text(key+1);
		$(value).find(".option_type").find("input").attr("name","option_"+key+1);
	});
}
$(document).ready(function(){

	if(stored_json){

		$.each(JSON.parse(stored_json),function(key,value){
			populate_key(value.type);
			$.each(value.option_labels.split(","),function(split_key,split_value){
				var last_option = $(".option-container-actual").eq(key).find(".option").length;
				var option_clone = $(".option-container-actual").eq(key).find(".option").eq(last_option-1).clone();
				$(".option-container-actual").eq(key).find(".option").eq(last_option-1).after(option_clone);
			});
			var the_last = $(".option-container-actual").eq(key).find(".option").length;
			$.each(value.option_labels.split(","),function(value_key,value_value){
				$(".option-container-actual").eq(key).find(".option").eq(value_key).find(".option_label_input").find("input").val(value_value);
				
			});
			$(".option-container-actual").eq(key).find(".option").eq(the_last-1).remove();


			
		});
		renumbering();
	}
	

});
$(document).on("click",".remove_option",function(){
	$(this).parent().remove();
	renumbering();

});
$(".info-key").click(function(){
	var option_type = $(this).attr("option_type");
	populate_key(option_type);
	
	renumbering();
});
$(document).on("click",".add_option",function(){
	var last_option = $(this).parent().find(".option").length;
	var option_clone = $(this).siblings(".option").eq(last_option-1).clone();
	$(this).parent().find(".option").eq(last_option-1).after(option_clone);

});
$(".save").click(function(){
	var json = [];
	var options = $(".option-container-actual");
	$.each(options,function(key,value){
		var the_option_type = $(value).attr("option_type");
		
		if(the_option_type=="multiple_choice"||the_option_type=="multiple_answer"){
			var option_val = [];
			$.each($(value).find(".option"),function(option_key,option_value){
				 option_val.push($(option_value).find(".option_label_input").find("input").val());
			});
			option_json = {
				"type":the_option_type,
				"option_labels":option_val.join(","),
			};
		}else{
			option_json = {
				"type":the_option_type,
				"option_labels":"",
			};
		}
		json.push(option_json);

		
		
	});
	final_json = {id:$("#assessment_id").val(),sheet:JSON.stringify(json)};
	$.ajax({
	    url: url,
	    type: "POST",
	    data: final_json,
	    // contentType: "application/json",
	    complete: function(response){
	    	alert("Sucessfully Saved!");
	    }
	});
});
$('.file').hide();
$(".upload").click(function(){
	$('.file').click();
});
$(".file").change(function(){
	$("#upload_form").submit();
});
$(document).on("click",".remove_choice",function(){
	$(this).parent().parent().remove();
	console.log();
});
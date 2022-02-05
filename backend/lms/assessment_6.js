var url = $("#url").val();
var base_url = $("#base_url").val();
var assessment_id = $("#assessment_id").val();
var final_json = {};
var letters_array = ["A","B","C","D"];
var assigned = $("#assigned").val();

var duration = $(".duration").val();
var percentage = $(".percentage").val();
var attempts = $(".attempts").val();
var start_date = $(".start_date").val();
var end_date = $(".end_date").val();



$(".sortable").sortable({
	stop:function(event,ui){
		renumbering();
		save_no_notif();
	}
});


$(".option-container-clonable").hide();

var jstree = $('#jstree_demo_div').jstree({
    "checkbox" : {
      "keep_selected_style" : false
    },
    "plugins" : [ "checkbox" ]
});

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
			// option_clone.find(".option_type").find("input").val(data.correct.split(",").join(" or "));
			option_clone.find(".option_type").find("input").css("width","100%");
			option_clone.find(".option_label_input").find("input").remove();
			option_clone.find(".remove_choice").remove();
			option_clone.find(".add_option").remove();
			$(".sortable").append(option_clone);
		break;
		case "long_answer":
			
			option_clone.removeClass("option-container-clonable");
			option_clone.addClass("option-container-actual");
			option_clone.addClass("long_answer");
			option_clone.show();
			option_clone.attr("option_type","long_answer");
			option_clone.find(".option_type").empty();
			option_clone.find(".add_option").remove();
			option_clone.find(".option_label_input").find("input").remove();
			option_clone.find(".remove_choice").remove();
			option_clone.find(".option_type").html('<textarea class="form-control"></textarea>');
			
			option_clone.find(".option_type").find("textarea").css("width","100%");
			$(".sortable").append(option_clone);
		break;
		case "section":
			
			option_clone.removeClass("option-container-clonable");
			option_clone.addClass("option-container-actual");
			option_clone.addClass("option-container-section");
			option_clone.addClass("section");
			option_clone.show();
			option_clone.attr("option_type","section");
			option_clone.css("background-color","rgb(251, 210, 127)");
			option_clone.find(".option_type").empty();
			option_clone.find(".add_option").remove();
			option_clone.find(".option_label_input").find("input").remove();
			option_clone.find(".remove_choice").remove();
			option_clone.find(".option_type").html('<textarea class="form-control"></textarea>');
			option_clone.find(".score_class").remove();
			
			option_clone.find(".option_type").find("textarea").css("width","100%");
			$(".sortable").append(option_clone);
		break;
	}
}

function renumbering(){
	var total_number = $(".option-container-actual");
	var total_section = $(".option-container-section");
	var section_number = 1;
	var option_number = 1;
	$.each(total_number,function(key,value){

		if($(value).hasClass('option-container-section')){
			$(value).find(".numbering_option").text("Section "+section_number);
			section_number++;
			option_number = 0;
		}else{
			$(value).find(".numbering_option").text("#"+option_number);
			$(value).find(".option_type").find("input").attr("name","option_"+section_number+"_"+(option_number));
		}
		
		option_number++;
	});
	
}

function escape_comma(value){
	if(!value){
		var return_value = value;
	}else{
		if(value.includes(",")){
			var return_value = value.replace(/,/g,"|comma|");
		}else{
			var return_value = value.trim();
		}
	}
	
	

	return return_value;
}
function unescape_comma(value){
	if(!value){
		var return_value = value;
	}else{
		if(value.includes("|comma|")&&value!=""){
			var return_value = value.replace(/\|comma\|/g,",");
		}else{
			var return_value = value;
		}
		return return_value.trim();
	}
}

function save(){
	var json = [];
	var options = $(".option-container-actual");
	$(".save_status").text("Saving...");
	$(".save_status").css("background-color","rgb(50,50,50)");

	$.each(options,function(key,value){
		var the_option_type = $(value).attr("option_type");
		var points_val = $(value).find(".points").val();
		if(the_option_type=="multiple_choice"||the_option_type=="multiple_answer"){
			var option_val = [];
			var answer_val = [];

			$.each($(value).find(".option"),function(option_key,option_value){
				var escaped_comma = escape_comma($(option_value).find(".option_label_input").find("input").val());
				option_val.push(escaped_comma);

				if($(option_value).find(".option_type").find("input").eq(0).is(':checked')){
					answer_val.push("1");
				}else{
					answer_val.push("0");
				}
			});

			option_json = {
				"type":the_option_type,
				"correct":answer_val.join(","),
				"points":points_val,
				"option_labels":option_val.join(","),
			};

		}else if(the_option_type=="short_answer"){
			
			var short_answer_val = escape_comma($(value).find(".option").find("input").eq(0).val()).toLowerCase().split(" or ");

			
			$.each(short_answer_val,function(key,value){
				short_answer_val[key] = value.trim();
			});

			option_json = {
				"type":the_option_type,
				"correct": short_answer_val.join(","),
				"points":points_val,
				"option_labels":"",
			};
		}else if(the_option_type=="section"){
			
			var instruction = $(value).find(".option").find("textarea").eq(0).val();

			option_json = {
				"type":the_option_type,
				"correct": instruction,
				"option_labels":"",
			};
		}else{
			option_json = {
				"type":the_option_type,
				"points":points_val,
				"option_labels":"",
			};
		}
		json.push(option_json);
		

	});

	var student_ids = [];
	$.each(jstree.jstree("get_checked",null,true),function(key,value){
		
		if(value.includes('student')){
			student_id = value.replace('student_','');
			
			student_ids.push(student_id);
		}
	});

	attempts = $(".attempts").val();
	duration = $(".duration").val();
	percentage = $(".percentage").val();
	start_date = $(".date_range").data('daterangepicker').startDate.toDate();
	end_date = $(".date_range").data('daterangepicker').endDate.toDate();
	start_date = moment(start_date).format("YYYY-MM-DD HH:mm:ss");
    end_date = moment(end_date).format("YYYY-MM-DD HH:mm:ss");
    var email_notification = $("#email_notification").prop('checked');
    var allow_result_viewing = $("#allow_result_viewing").prop('checked');
    var enable_timer = $("#enable_timer").prop('checked');
    if(email_notification){
    	email_notification = 1;
    }else{
    	email_notification = 0;
    }
    if(allow_result_viewing){
    	allow_result_viewing = 1;
    }else{
    	allow_result_viewing = 0;
    }
    if(enable_timer){
    	enable_timer = 1;
    }else{
    	enable_timer = 0;
    }

	final_json = {
		id:$("#assessment_id").val(),
		sheet:JSON.stringify(json),
		assigned:student_ids.join(','),
		duration: duration,
		percentage: percentage,
		attempts: attempts,
		start_date: start_date,
		end_date: end_date,
		email_notification: email_notification,
		allow_result_viewing: allow_result_viewing,
		enable_timer: enable_timer,
	};

	console.log(url);
	$.ajax({
	    url: url,
	    type: "POST",
	    data: final_json,
	    // contentType: "application/json",
	    complete: function(response){
	    	console.log(response.responseText);
	    	alert("Quiz has been saved successfully!");
	    	$(".save_status").text("Saved");
			$(".save_status").css("background-color","green");
	    }
	});
}

function save_no_notif(){
	var json = [];
	var options = $(".option-container-actual");
	$(".save_status").text("Saving...");
	$(".save_status").css("background-color","rgb(50,50,50)");
	$.each(options,function(key,value){
		var the_option_type = $(value).attr("option_type");
		
		if(the_option_type=="multiple_choice"||the_option_type=="multiple_answer"){
			var option_val = [];
			var answer_val = [];
			$.each($(value).find(".option"),function(option_key,option_value){
				 var escaped_comma = escape_comma($(option_value).find(".option_label_input").find("input").val());
					option_val.push(escaped_comma);
					console.log(option_val);
				 if($(option_value).find(".option_type").find("input").eq(0).is(':checked')){
				 	answer_val.push("1");
				 }else{
				 	answer_val.push("0");
				 }
			});

			option_json = {
				"type":the_option_type,
				"correct":answer_val.join(","),
				"option_labels":option_val.join(","),
			};

		}else if(the_option_type=="short_answer"){
			
			var short_answer_val = $(value).find(".option").find("input").eq(0).val().toLowerCase().split(" or ");
			
			option_json = {
				"type":the_option_type,
				"correct": short_answer_val.join(","),
				"option_labels":"",
			};
		}else if(the_option_type=="section"){
			
			var instruction = $(value).find(".option").find("textarea").eq(0).val();

			option_json = {
				"type":the_option_type,
				"correct": instruction,
				"option_labels":"",
			};
		}else{
			option_json = {
				"type":the_option_type,
				"option_labels":"",
			};
		}
		json.push(option_json);
		

	});

	var student_ids = [];
	$.each(jstree.jstree("get_checked",null,true),function(key,value){
		
		if(value.includes('student')){
			student_id = value.replace('student_','');
			
			student_ids.push(student_id);
		}
	});

	attempts = $(".attempts").val();
	duration = $(".duration").val();
	percentage = $(".percentage").val();
	start_date = $(".date_range").data('daterangepicker').startDate.toDate();
	end_date = $(".date_range").data('daterangepicker').endDate.toDate();
	start_date = moment(start_date).format("YYYY-MM-DD HH:mm:ss");
    end_date = moment(end_date).format("YYYY-MM-DD HH:mm:ss");
    var email_notification = $("#email_notification").prop('checked');
    if(email_notification){
    	email_notification = 1;
    }else{
    	email_notification = 0;
    }

	final_json = {
		id:$("#assessment_id").val(),
		sheet:JSON.stringify(json),
		assigned:student_ids.join(','),
		duration: duration,
		percentage: percentage,
		attempts: attempts,
		start_date: start_date,
		end_date: end_date,
		email_notification: email_notification,
	};

	$.ajax({
	    url: url,
	    type: "POST",
	    data: final_json,
	    // contentType: "application/json",
	    complete: function(response){
	    	console.log(response.responseText);

	    	$(".save_status").text("Saved");
			$(".save_status").css("background-color","green");
	    }
	});
}


$(document).ready(function(){
	$.ajax({
	    url: base_url+'/stored_json',
	    type: "POST",
	    data: {assessment_id:assessment_id},
	    // contentType: "application/json",
	    complete: function(response){

	    	var stored_json = response.responseText;


	    	if(stored_json){
				
				$.each(JSON.parse(stored_json),function(key,value){
					populate_key(value.type,value);
					
					var checked_ids = [];
					if(assigned){

						$.each(assigned.split(","),function(key,value){
							checked_ids.push("student_"+value);
						});
						$.jstree.reference('#jstree_demo_div').select_node(checked_ids);
					}

					if(("points" in value)){
						$(".option-container-actual").eq(key).find(".points").val(value.points);
					}else{
						$(".option-container-actual").eq(key).find(".points").val("1");
					}

					if(value.type=="short_answer"){

						
						$(".option-container-actual").eq(key).find(".option_type").find("input").val(unescape_comma(value.correct.split(",").join(" or ")));
						// option_clone.find(".option_type").find("input").val(data.correct.split(",").join(" or "));
						
						// console.log(value);
					}
					if(value.type=="section"){
						$(".option-container-actual").eq(key).find(".option_type").find("textarea").val(value.correct);
					}
					$.each(value.option_labels.split(","),function(split_key,split_value){
						
						var last_option = $(".option-container-actual").eq(key).find(".option").length;
						var option_clone = $(".option-container-actual").eq(key).find(".option").eq(last_option-1).clone();
						$(".option-container-actual").eq(key).find(".option").eq(last_option-1).after(option_clone);

					});

					if(value.type=="multiple_choice"||value.type=="multiple_answer"){

						$.each(value.correct.split(","),function(correct_key,correct_value){

							if(value.type=="multiple_choice"||value.type=="multiple_answer"){
								// $( "#x" ).prop( "checked", true );
								if(correct_value=='1'){

									$(".option-container-actual").eq(key).find(".option_type").eq(correct_key).find("input").prop("checked",true);
								}else{
									// $(".option-container-actual").eq(key).find(".option_type").find("input").prop("checked",false);
								}

							}
							

						});
					}
					
					var the_last = $(".option-container-actual").eq(key).find(".option").length;
					$.each(value.option_labels.split(","),function(value_key,value_value){
						var unescaped_comma = unescape_comma(value_value);
						$(".option-container-actual").eq(key).find(".option").eq(value_key).find(".option_label_input").find("input").val(unescaped_comma);
						
					});
					$(".option-container-actual").eq(key).find(".option").eq(the_last-1).remove();

					
				});
				renumbering();
			}

	    }
	});
	
	

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
$(".true_save").click(function(){
	save();
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

});
$(".assign_panel").hide();
$(".assign").click(function(){
	$(".assign_panel").toggle();
	$(".sortable").toggle();
});

$('.date_range').daterangepicker({
	timePicker: true,
	startDate: moment().startOf('hour'),
	endDate: moment().startOf('hour').add(24, 'hour'),
	locale: {
	  format: 'MMMM DD hh:mm A'
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
        startDate: moment().startOf('hour'),
        endDate: moment().startOf('hour').add(24, 'hour'),
        locale: {
          format: 'MMMM DD hh:mm A'
        }
    });
}
var url = $("#url").val();
var stored_json = $("#stored_json").val();
var answer = $("#answer").val();
var final_json = {};
var letters_array = ["A","B","C","D"];
var assigned = $("#assigned").val();
var score = 0;
var total_score = 0;

// $(".sortable").sortable({
// 	stop:function(event,ui){
// 		renumbering();
// 	}
// });
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
			$(value).find(".numbering_option").text("No. "+option_number);
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

$(document).ready(function(){
	
	if(stored_json){

		$.each(JSON.parse(stored_json),function(key,value){
			populate_key(value.type,value);
			
			
			$.each(value.option_labels.split(","),function(split_key,split_value){
				
				var last_option = $(".option-container-actual").eq(key).find(".option").length;
				var option_clone = $(".option-container-actual").eq(key).find(".option").eq(last_option-1).clone();
				$(".option-container-actual").eq(key).find(".option").eq(last_option-1).after(option_clone);

			});

			if(value.type=="section"){
				$(".option-container-actual").eq(key).find(".option_type").find("textarea").val(value.correct);
				$(".option-container-actual").eq(key).find(".option_type").find("textarea").attr("readonly","readonly");
			}
			
			var the_last = $(".option-container-actual").eq(key).find(".option").length;
			$.each(value.option_labels.split(","),function(value_key,value_value){
				var unescaped_comma = unescape_comma(value_value);
				$(".option-container-actual").eq(key).find(".option").eq(value_key).find(".option_label_input").find("input").val(unescaped_comma);
				
			});
			$(".option-container-actual").eq(key).find(".option").eq(the_last-1).remove();


			
		});
		renumbering();
		$(document).find(".option_label_input").find("input").attr("readonly","readonly");
		$(document).find(".option_type").find("input").attr("readonly","readonly");
		$(document).find(".option_type").find("textarea").attr("readonly","readonly");
	}
	

});
//fill answers
$(document).ready(function(){
	// #52b152
	if(answer){
		answer = JSON.parse(answer);
		stored_json_parsed = JSON.parse(stored_json);
		$.each(answer,function(key,value){

			if(value.type=="multiple_choice"||value.type=="multiple_answer"){
				var correct_answer = stored_json_parsed[key].correct.split(",");
				var student_answer = value.answer.split(",");
				var the_options = $(".option-container-actual").eq(key).find(".option");
				var correct_label = [];
				total_score += parseInt(stored_json_parsed[key].points);
				$.each(the_options,function(the_option_key,the_option_value){
					// console.log(student_answer[the_option_key]);
					if(student_answer[the_option_key] == "1"){
						$(the_option_value).find(".option_type").find("input").prop("checked",true);
					}
					if(correct_answer[the_option_key] == "1"){

						correct_label.push(stored_json_parsed[key].option_labels.split(",")[the_option_key]);
					}
				});

				if(value.answer == stored_json_parsed[key].correct){
					score += parseInt(stored_json_parsed[key].points);
					$(".option-container-actual").eq(key).css("background-color","rgb(114, 196, 114)");
				}else{
					$(".option-container-actual").eq(key).css("background-color","rgb(255, 108, 108)");
					$(the_options).last().after("<div>Correct : "+unescape_comma(correct_label.join(" , "))+"</div>");
				}
				
			}else if(value.type=="short_answer"){
				var short_answer_correct_array = stored_json_parsed[key].correct.toLowerCase().trim().split(",");
				total_score += parseInt(stored_json_parsed[key].points);

				var the_options = $(".option-container-actual").eq(key).find(".option");
				$(the_options).find(".option_type").find("input").val(value.answer);
				var student_short_answer = escape_comma(value.answer.toLowerCase().trim());
				if(short_answer_correct_array.indexOf(student_short_answer) != -1){
					score += parseInt(stored_json_parsed[key].points);
					$(".option-container-actual").eq(key).css("background-color","rgb(114, 196, 114)");
				}else{
					$(".option-container-actual").eq(key).css("background-color","rgb(255, 108, 108)");
					$(the_options).last().after("<div>Correct : "+unescape_comma(short_answer_correct_array.join(" or "))+"</div>");
				}
				
				

			}else if(value.type=="long_answer"){
				total_score += parseInt(stored_json_parsed[key].points);
				var essay_score = "No Score yet";

				if('score' in value){
					score += parseInt(value.score);
					$(".option-container-actual").eq(key).css("background-color","rgb(114, 196, 114)");
					essay_score = value.score;
				}else{
					score += 0;
				}
				var the_options = $(".option-container-actual").eq(key).find(".option");
				$(the_options).find(".option_type").find("textarea").text(value.answer);
				$(the_options).find(".option_type").find("textarea").after("<p>Essay score: "+essay_score+"</p>");
			}	
			

			

		});

	}
	console.log(score);
	$(".score").text(score+"/"+total_score);
	$(document).find(".option_label_input").find("input").attr("readonly","readonly");
		$(document).find(".option_type").find("input").attr("disabled","disabled");
		$(document).find(".option_type").find("textarea").attr("readonly","readonly");
	

});

//fill answers

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
	var json = [];
	var options = $(".option-container-actual");
	$.each(options,function(key,value){
		var the_option_type = $(value).attr("option_type");
		
		if(the_option_type=="multiple_choice"||the_option_type=="multiple_answer"){
			var option_val = [];
			var answer_val = [];
			$.each($(value).find(".option"),function(option_key,option_value){
				 option_val.push($(option_value).find(".option_label_input").find("input").val());
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
			
			var short_answer_val = $(value).find(".option").find("input").eq(0).val().split(" or ");

			option_json = {
				"type":the_option_type,
				"correct": short_answer_val.join(","),
				"option_labels":"",
			};
		}else{
			option_json = {
				"type":the_option_type,
				"option_labels":"",
			};
		}
		json.push(option_json);

		
		console.log(json);
	});

	var student_ids = [];
	

	final_json = {id:$("#assessment_id").val(),sheet:JSON.stringify(json),assigned:student_ids.join(',')};
	
	$.ajax({
	    url: url,
	    type: "POST",
	    data: final_json,
	    // contentType: "application/json",
	    complete: function(response){
	    	// console.log(response.responseText);
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

});
$(".assign_panel").hide();
$(".assign").click(function(){
	$(".assign_panel").toggle();
	$(".sortable").toggle();
});

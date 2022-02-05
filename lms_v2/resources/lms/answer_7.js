var url = $("#url").val();
var site_url = $("#site_url").val();
var base_url = $("#base_url").val();
var old_url = $("#old_url").val();
var assessment_id = $("#assessment_id").val();
var assessment_sheet_id = $("#assessment_sheet_id").val();
var enable_timer = $("#enable_timer").val();
var account_id = $("#account_id").val();
var answer = $("#answer").val();
var assessment_id = $("#assessment_id").val();
var final_json = {};
var letters_array = ["A","B","C","D"];
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
			var return_value = value.replace(",","|comma|");
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
			var return_value = value.replace("|comma|",",");
		}else{
			var return_value = value;
		}
		return return_value.trim();
	}
}

$(document).ready(function(){


	$.ajax({
	    url: base_url+'stored_json',
	    type: "POST",
	    data: {assessment_id:assessment_id},
	    // contentType: "application/json",
	    complete: function(response){

	    	var stored_json = response.responseText;

	    	if(stored_json){

				$.each(JSON.parse(stored_json),function(key,value){
					populate_key(value.type,value);
					// console.log(value.correct);
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
			}

			$.ajax({
			    url: base_url+'stored_answer',
			    type: "POST",
			    data: {assessment_sheet_id:assessment_sheet_id},
			    // contentType: "application/json",
			    complete: function(stored_answer){

			    	var answer = stored_answer.responseText;
			    	$(document).find("input").attr("autocomplete","off");
					if(answer){
						answer = JSON.parse(answer);
						stored_json_parsed = JSON.parse(stored_json);
						$.each(answer,function(key,value){

							if(value.type=="multiple_choice"||value.type=="multiple_answer"){
								var student_answer = value.answer.split(",");
								var the_options = $(".option-container-actual").eq(key).find(".option");

								$.each(the_options,function(the_option_key,the_option_value){
									if(student_answer[the_option_key] == "1"){
										$(the_option_value).find(".option_type").find("input").prop("checked",true);
									}

								});


							}else if(value.type=="short_answer"){
								var short_answer_correct_array = stored_json_parsed[key].correct.split(",");

								var the_options = $(".option-container-actual").eq(key).find(".option");
								$(the_options).find(".option_type").find("input").val(value.answer);


							}else if(value.type=="long_answer"){

								var the_options = $(".option-container-actual").eq(key).find(".option");
								$(the_options).find(".option_type").find("textarea").text(value.answer);
							}




						});

					}
				}
			});

	    },
	});



});

//fill answers


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



function auto_save(){
	var json = [];
		var options = $(".option-container-actual");
		$.each(options,function(key,value){
			var the_option_type = $(value).attr("option_type");

			if(the_option_type=="multiple_choice"||the_option_type=="multiple_answer"){
				var answer_val = [];
				$.each($(value).find(".option"),function(option_key,option_value){

					 if($(option_value).find(".option_type").find("input").eq(0).is(':checked')){
					 	answer_val.push("1");
					 }else{
					 	answer_val.push("0");
					 }
				});

				option_json = {
					"type":the_option_type,
					"answer":answer_val.join(","),
				};

			}else if(the_option_type=="short_answer"){

				var short_answer_val = $(value).find(".option").find("input").eq(0).val();

				option_json = {
					"type":the_option_type,
					"answer": short_answer_val,
				};
			}else{
				var answer_val = $(value).find(".option").find("textarea").eq(0).val();
				option_json = {
					"type":the_option_type,
					"answer":answer_val,
				};
			}
			json.push(option_json);



		});
		final_json = {id:assessment_sheet_id,assessment_id:assessment_id,answer:JSON.stringify(json)};


		$.ajax({
		    url: site_url+'auto_save',
		    type: "POST",
		    data: final_json,
		    complete: function(response){
          console.log("auto saved!");
		    }
		});
}
$(".submit").click(function(){
	if(confirm("Are you sure you want to submit this quiz?")){
		var json = [];
		var options = $(".option-container-actual");
		$.each(options,function(key,value){
			var the_option_type = $(value).attr("option_type");

			if(the_option_type=="multiple_choice"||the_option_type=="multiple_answer"){
				var answer_val = [];
				$.each($(value).find(".option"),function(option_key,option_value){

					 if($(option_value).find(".option_type").find("input").eq(0).is(':checked')){
					 	answer_val.push("1");
					 }else{
					 	answer_val.push("0");
					 }
				});

				option_json = {
					"type":the_option_type,
					"answer":answer_val.join(","),
				};

			}else if(the_option_type=="short_answer"){

				var short_answer_val = $(value).find(".option").find("input").eq(0).val();

				option_json = {
					"type":the_option_type,
					"answer": short_answer_val,
				};
			}else{
				var answer_val = $(value).find(".option").find("textarea").eq(0).val();
				option_json = {
					"type":the_option_type,
					"answer":answer_val,
				};
			}
			json.push(option_json);



		});
		final_json = {id:assessment_sheet_id,assessment_id:assessment_id,answer:JSON.stringify(json)};


		$.ajax({
		    url: site_url+'answer_submit',
		    type: "POST",
		    data: final_json,
		    complete: function(response){
		    	// console.log(response.responseText);
		    	alert("Quiz has been successfully submitted!");
		    	window.location.replace(old_url+'review/'+assessment_id);
		    }
		});
	}
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

$(document).ready(function(){




});
var expiration_date = $("#expiration_value").val();

var time_now = parseInt($("#time_now").val());
// console.log(expiration_date);
// console.log(time_now);
$(document).ready(function(){
	console.log(enable_timer);
	if(enable_timer==1){
		startTime();

	}else{
		document.getElementById("time").innerHTML = "No Time Limit";
		$("#time").css("color","green");
	}
	
	
  	$(document).on("change","input",function(){
    	auto_save();
  	});
  	$(document).on("change","textarea",function(){
    	auto_save();
  	});
});



function startTime() {
	// Set the date we're counting down to
	var countDownDate = new Date(expiration_date).getTime();

	// Update the count down every 1 second
	var x = setInterval(function() {

		// Get todays date and time
		var now = new Date().getTime();

		// Find the distance between now and the count down date
		var distance = countDownDate - now;

		// Time calculations for days, hours, minutes and seconds
		var days = Math.floor(distance / (1000 * 60 * 60 * 24));
		var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		if(hours<10){
			hours = '0'+hours;
		}
		var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));

		if(minutes<10){
			minutes = '0'+minutes;
		}
		var seconds = Math.floor((distance % (1000 * 60)) / 1000);
		if(seconds<10){
			seconds = '0'+seconds;
		}
		// Display the result in the element with id="demo"
		document.getElementById("time").innerHTML = hours + ":"
		+ minutes + ":" + seconds;

		// If the count down is finished, write some text
		if (distance < 0) {
			clearInterval(x);
			document.getElementById("time").innerHTML = "Time's Up!";
			alert("Time's Up!");
			$(".time_up").click();
		}
	}, 1000);
}

var color = $(".selected").css("background-color");
var $canvas = $("canvas");
var context = $canvas[0].getContext("2d");
var lastEvent;
var mouseDown = false;


// show border on clicked color
$("#controls").on("click", "li", function(){
	$(this).siblings().removeClass("selected");
	$(this).addClass("selected");
	
	
	color = $(this).css("background-color");
});

// show color making option
$("#revealColorSelect").click(function(){
	changeSpanColor();

	$("#colorSelect").toggle();
});

// making color by mixing red green blue
function changeSpanColor(){
	var r = $("#red").val();
	var g = $("#green").val();
	var b = $("#blue").val();

	$("#newColor").css("background-color", "rgb(" + r + "," + g + "," + b + ")");
}

// changing input range
$('input[type="range"]').change(changeSpanColor);

// adding the new mixed color to our color lists
$("#addNewColor").click(function(){
	var $newColor = $("<li></li>");
	
	$newColor.css("background-color", $("#newColor").css("background-color"));
	
	$("#controls ul").append($newColor);
	
	$newColor.click();
});

// painting with mouse events
$canvas.mousedown(function(e){
	lastEvent = e;
	mouseDown = true;
	
}).mousemove(function(e){

	if(mouseDown){
		context.beginPath();
		context.moveTo(lastEvent.offsetX, lastEvent.offsetY);
		context.lineTo(e.offsetX, e.offsetY);
		context.strokeStyle = "yellow";
		context.lineWidth = 5;
		context.stroke();
		lastEvent = e;
	}
	
}).mouseup(function(){
		mouseDown = false;
}).mouseleave(function(){
		$canvas.mouseup();
});

(function() {
    var canvas = document.getElementById('canvas'),
            context = canvas.getContext('2d');

    // resize the canvas to fill browser window dynamically
    window.addEventListener('resize', resizeCanvas, false);

    function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;

            /**
             * Your drawings need to be inside this function otherwise they will be reset when 
             * you resize the browser window and the canvas goes will be cleared.
             */
            drawStuff(); 
    }
    resizeCanvas();

    function drawStuff() {
            // do your drawing stuff here
    }
})();
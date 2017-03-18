$("input[class='touchspin-control']").TouchSpin({
  verticalbuttons: true
});

$( ".naCzysto" ).click(function() {
  var cost = $(this).attr("amount");
  var id = $(this).attr("target");

  $("input[id=cost#"+id+"]").val(cost+" zł");
});

$(".touchspin-control").on("touchspin.on.stopspin", function() {
  	var id = $(this).attr("id");
  	var cost = $(this).val() * 9.0;

  	$("input[id=cost#"+id+"]").val(cost+" zł");
});
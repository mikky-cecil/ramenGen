
$(document).ready(function(){
	//submitting recipe button
	$("#submitrecipe").click(function(){
		// $("#recipeform").submit();
		var flavor = $("#recipeform>input[name=flavor]:checked").val();
		var nut = $("#recipeform>input[name=nut]:checked").val();
		var cheap = $("#recipeform>input[name=cheap]:checked").val();
		var ingredients = [];
		var i;

		//go until no more ingredients
		for (i = 0;
			($("#recipeform>.textoptions>input[name=ingredient" + i + "]").val() != undefined
				&& $("#recipeform>.textoptions>input[name=ingredient" + i + "]").val() != "");
			i++){
			ingredients[i] = $("#recipeform>.textoptions>input[name=ingredient" + i + "]").val();
		}

		$.ajax({
			url: "addrecipe.php",
			data: {
				"flavor": flavor,
				"nut": nut,
				"cheap": cheap,
				"ingredients": ingredients
			},
			method: "post",
			dataType: "json",
			success: function(data, info){
				if (!data.success){
					$(".content").text(data.data);
					return 1;
				}else{
					$(".content").text("Thanks for your recipe!");
				}
			}, error: function(data, info){
				$(".content").text("Something went wrong. " + info);
			}
		});
	});

	//add a text box on a form
	var textboxcount = 1;
	$(".addtextbox").click(function(){
		$(".textoptions").append("<input type=\"text\" name=\"ingredient" + textboxcount + "\"></input><br>");
		textboxcount++;
	});
});

$(document).ready(function(){
	//button clicking animation
	$(".button").mousedown(function(){
		this.style.borderStyle = "inset";
		this.style.backgroundColor = "#3F6915";
	});
	//button clicking animation
	$(".button").mouseup(function(){
		this.style.borderStyle = "outset";
		this.style.backgroundColor = "#63A621";
	});

	//facebook share click
	$("#fbshare").click(function(){
		window.open("http://www.facebook.com/share.php?u=" + $(this).attr("url"));
	});

	//generating a recipe button
	$("#generate").click(function(){
		//find number of recipes
		$.ajax({url:"getnumrecipes.php",
			success:function(data, info){
				if (data.num_recipes){
					num_recipes = data.num_recipes; //global

					//make sure it's a different recipe every time.
					do{
						var recipe_id = Math.floor(Math.random() * num_recipes) + 1;
	                }while($.cookie("lastrecipe") == recipe_id);
	                //get random recipe. repeat until we get a new one.
					$.ajax({url:"getrecipe.php", data:{"id":recipe_id},
						success:function(data, info){
							if (!data.success){
								$(".recipe").text("error connecting to database.");
								return 1;
							}

							var recipe = data.data;
							var sentence = "Put some " + recipe.ingredients + " in your " + recipe.flavor + " flavored ramen!";
							$(".recipe").html("<img src=\"https://harukafujihira.files.wordpress.com/2013/09/03_keyword_07.jpg\" class=\"ramen\"><br><br>" + sentence);

			                //save cookie: they have already seen this one.
			                $.cookie("lastrecipe", recipe_id);
						}, error:function(){
							$(".recipe").text("error connecting to server.");
						}, dataType:"json"
					});

					//modify buttons
					$("#generate").text("Not good enough!");
					$("#favorite").show();
					$("#fbshare").show();
					$("#twshare").show();
				}else{
					$(".recipe").text("error connecting to database.");
					return 1;
				}
			}, error:function(){
				$(".recipe").text("error connecting to server.");
				return 1;
			}, dataType:"json"
		});
	});

	//login button
	$("#submitlogin").click(function(){
		$("#loginform").submit();
	});

	//submitting recipe button
	$("#submitrecipe").click(function(){
		$("#recipeform").submit();
	});

	//submitting sign up button
	$("#submitsignup").click(function(){
		$("#signupform").submit();
	});

	//add a text box on a form
	var textboxcount = 1;
	$(".addtextbox").click(function(){
		$(".textoptions").append("<input type=\"text\" name=\"ingredient" + textboxcount + "\"></input><br>");
		textboxcount++;
	});
});

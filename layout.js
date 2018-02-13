function hide_restricted_pages(){
	//hide add recipe page
	if (window.location.pathname.replace("/~cecilma/WebDev/project/", "") == "ramenform.html"){
		$(".content").text("You must be logged in to submit a recipe.");
	}
}

function navbar_showaccount(accountroot){
	var patharray = (window.location.pathname.replace("/~cecilma/WebDev/project/", "")).split('/');
	var loggedin = false;
	var accountroot;
	var root;
	if (patharray.length == 2){
		accountroot = "";
		root = "../";
	}else{
		accountroot = "account/";
		root = "";
	}

	//show account if logged in
	$.ajax({url: accountroot + "showaccount.php",
		success: function(data, info){
			//if logged in, find and show username
			if (data.success){
				loggedin = true;
				$("span#loginsignup").html("Hello " + data.user + "! | <a id=\"logout\" href=\"#\">Log out</a>");
				//show logout link
				$("#logout").click(function(e){
					e.preventDefault();
					$.ajax({
						url: accountroot + "logout.php",
						dataType: "json",
						success: function(){
							$("span#loginsignup").html("Logged out successfully. | <a href=\"" + accountroot + "signup.html\">Sign Up</a> | <a href=\"" + accountroot + "login.html\">Log In</a>");
							$("span#logo").html("<a href=\"" + root + "ramengen.html\">Home</a>");
							hide_restricted_pages();
						}
					});
				});
				//show submit recipe link
				$("span#logo").append(" | <a href=\"" + root + "ramenform.html\">Submit a Recipe</a>");
			}else{
				hide_restricted_pages();
			}
		},
		dataType: "json"
	});
}

$(document).ready(function(){
	//button clicking animation
	$(".button").mousedown(function(){
		this.style.borderStyle = "inset";
		if(this.attr("id") == "fbshare"){
			this.style.backgroundColor = "#3B5998";
		}else{
			this.style.backgroundColor = "#3F6915";
		}
	});
	//button clicking animation
	$(".button").mouseup(function(){
		this.style.borderStyle = "outset";
		if(this.attr("id") == "fbshare"){
			this.style.backgroundColor = "#3B5998";
		}else{
			this.style.backgroundColor = "#63A621";
		}
	});

	//show account in navbar
	navbar_showaccount();
	
});

//setup facebook sdk
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=435803406582064&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

//setup twitter sdk

window.twttr=(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],t=window.twttr||{};if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);t._e=[];t.ready=function(f){t._e.push(f);};return t;}(document,"script","twitter-wjs"));

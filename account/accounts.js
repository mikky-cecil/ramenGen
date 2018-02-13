function validate_loginform(user, pwd){
	return true;
}

function validate_signinform(user, pwd, email){
	return true;
}

$(document).ready(function(){
	//login button
	$("#submitlogin").click(function(){
		var user = $("#loginform>input[name=user]").val();
		var pwd = $("#loginform>input[name=pwd]").val();

		if(!validate_loginform(user, pwd)){
			alert("Something is not valid, yo...");
			return 1;
		}	

		$.ajax({
			url: "login.php",
			data: {"user": user, "pwd": pwd},
			method: "post",
			dataType: "json",
			success: function(data, info){
				if (!data.success){
					$("#logincontainer").text(data.data);
					return 1;
				}
				$("#logincontainer").text("You are now logged in!");
				navbar_showaccount();
			}
		});
	});

	//submitting sign up button
	$("#submitsignup").click(function(){
		var user = $("#signupform>input[name=user]").val();
		var pwd = $("#signupform>input[name=pwd]").val();
		var email = $("#signupform>input[name=email]").val();

		if(!validate_signinform(user, pwd, email)){
			alert("Something is not valid, yo...");
			return 1;
		}

		$.ajax({
			url: "adduser.php",
			data: {"user": user, "pwd": pwd, "email": email},
			method: "post",
			dataType: "json",
			success: function(data, info){
				if (!data.success){
					$("#logincontainer").text(data.data);
					return 1;
				}
				//now sign them in
				$.ajax({
					url: "login.php",
					data: {"user": user, "pwd": pwd},
					method: "post",
					dataType: "json",
					success: function(){
						$("#logincontainer").text("Your account has been created! You are now logged in.");
						navbar_showaccount();
					}, error: function(){
						$("#logincontainer").text("Your account was created successfully, but something went wrong logging you in.");
					}
				});
			}, error: function(){
				$("#logincontainer").text("Something went wrong with the server.");
			}
		});
	});
});

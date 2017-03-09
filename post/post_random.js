$("#play-quiz-button").on('click', function() {
  

   //if($("#play-quiz-button").attr('data-in-progress') == 1) return;

    //$("#play-quiz-button").attr('data-in-progress', 1);
	//$("#login-button-progress").show();
    //$("#login-button-loaded").hide();
	
    FB.login(function(response) {
        FacebookLoginBozhidar(response);
    }, {scope: 'public_profile,email'});
});

function FacebookLoginBozhidar(response) {
    if(response.authResponse) {
        $.ajax({
            type: 'POST',
            url: LOCATION_SITE + 'ajax/generate-result.php',
            cache: false,
            data: { access_token: response.authResponse.accessToken, post_id: QUIZ_DETAILS.post_id },
            dataType: 'JSON',
            success: function(login_response) {
				
				window.location.href = login_response.redirect_url;
				/*
                if($("#quiz-container-without-login-container").is(":visible")) {
                    document.location.reload();
                    return;
                }

                $("#loggedin-user-info img").attr('src', login_response.user_picture_url);
                $("#loggedin-user-info span").text(login_response.user_full_name);
                
                if(login_response.user_premium == 0)
                    $("#loggedin-user-credits-menu").hide();
                else if(login_response.user_premium == 1)
                    $("#loggedin-user-credits-menu").show();
                
                $("#loggedin-user-info").show();
                $("#login-button").attr('data-in-progress', 0).hide();
                $("#login-button-progress").hide();
                $("#login-button-loaded").show();
				*/
            },
            error: function(error_response) {
                $("#login-button").attr('data-in-progress', 0);
                $("#login-button-progress").hide();
                $("#login-button-loaded").show();
            }
        });
    }
    else {
        $("#login-button").attr('data-in-progress', 0);
        $("#login-button-progress").hide();
        $("#login-button-loaded").show();
    }
}
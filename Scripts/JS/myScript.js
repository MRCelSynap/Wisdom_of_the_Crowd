var debug = true;
var loggedIn = false;
var errorMessage = "";

$(document).ready(function () {
    // Check for existing login via session variables
    $.post("Scripts/PHP/HandleRequest.php", { request: "CheckSessionLoggedIn" }, function (result) {
        //document.write(result);
        var receivedData = JSON.parse(result);
        debugConsole(receivedData);
        loggedIn = receivedData.isLoggedIn;
        userLoggedIn(loggedIn, receivedData.email, receivedData.log);
    }).fail(function () {
        debugConsole("CheckSessionLoggedIn Request could not be handled.");
    }); // CheckSessionLoggedIn

    

    ///Setup the registartion toggle event
    $("#registrationToggle").change(function () {
        if ($("#registrationToggle").is(":checked")) {
            $("#submitButton").val("Register");
            $(".registrationForm").show();
        } else {
            $("#submitButton").val("Login");
            $(".registrationForm").hide();
        }
        $("#email").focus();
    }); // OnChage for the Registration checkbox

    $("#submitButton").click(function () {
        if ($("#registrationToggle").is(":checked")) {
            registerNewUser();
        } else {
            login();
        }
        return false;
    }); // on form submit

}); // onPageLoad


function registerNewUser() {
    $.post("Scripts/PHP/HandleRequest.php", { request: "RegisterNewUser", email: $("#email").val(), password: $("#password").val(), passwordRepeat: $("#passwordRepeat").val(), firstName: $("#firstName").val(), lastName: $("#lastName").val(), birthDate: $("#birthDate").val()}, function (result) {
        //document.write(result);
        var receivedData = JSON.parse(result);
        debugConsole(receivedData);
        displayErrors(receivedData.log)
        if(receivedData.newUserRegistered) {
            login();
        } else {
            $("#email").focus();
        }
    });
}

function login() {
    $.post("Scripts/PHP/HandleRequest.php", { request: "Login", email: $("#email").val(), password: $("#password").val() }, function (result) {
        //document.write(result);
        var receivedData = JSON.parse(result);
        debugConsole(receivedData);
        loggedIn = receivedData.isLoggedIn;

        displayErrors(receivedData.log);

        userLoggedIn(loggedIn, receivedData.email, receivedData.log);
    }).fail(function () {
        debugConsole("Login Request could not be handled.");
    }); // Login

}

function logout() {
    $.post("Scripts/PHP/HandleRequest.php", { request: "Logout"}, function (result) {
        //document.write(result);
        var receivedData = JSON.parse(result);
        debugConsole(receivedData);
        loggedIn = receivedData.isLoggedIn;
        displayErrors(receivedData.log);
        userLoggedIn(loggedIn, "", receivedData.log);

    });
}

function userLoggedIn(isLoggedIn, email) {
    if (!isLoggedIn) {
        displayLoginForm(true);
        displayUserDetail(false, "");
    } else {
        displayLoginForm(false);
        clearRegistrationData();
        displayUserDetail(true, email);
    }
}

function displayLoginForm(toDisplay) {
    if (toDisplay) {
        $("#submissionForm").show();
        $("#email").focus();
        debugConsole("Show submissionForm");
    } else {
        $("#submissionForm").hide();
        debugConsole("Hide submissionForm");
    }
}

function displayErrors(msg) {
    errorMessage = "";
    var errorCount = 0;
    var warningCount = 0;
    $("#loginFeedback").html("").hide();
    for (var i=0; i < msg.length; i++) {
        if (msg[i]==="EmptyEmail") { errorCount++; errorMessage += "Please enter an email address to login.</br>"; }
        if (msg[i]==="InvalidEmail") { errorCount++; errorMessage += "Please enter a valid email address to login.</br>"; }
        if (msg[i]==="InvalidEmailRegister") { errorCount++; errorMessage += "Please enter a valid email address to register.</br>"; }
        if (msg[i]==="EmptyPassword") { errorCount++; errorMessage += "Please enter a password to login.</br>"; }
        if (msg[i]==="EmptyPasswordRegister") { errorCount++; errorMessage += "Please enter a password to register.</br>"; }
        if (msg[i]==="PasswordsMismatch") { errorCount++; errorMessage += "Passwords do not match.</br>"; }
        if (msg[i]==="InvalidEmailPassword") { errorCount++; errorMessage += "Invalid username and password combination.</br>"; }
        if (msg[i]==="UserAlreadyExists") { errorCount++; errorMessage += "Username already exists.</br>"; }
        if (msg[i]==="InvalidDateFormat") { errorCount++; errorMessage += "The birthday entered is not a valid date.</br>"; }
        if (msg[i]==="InvalidFirstName") { errorCount++; errorMessage += "The first name entered contains invalid characters.</br>"; }
        if (msg[i]==="InvalidLastName") { errorCount++; errorMessage += "The last name entered contains invalid characters.</br>"; }
    }
    if (errorCount > 0) { $("#loginFeedback").html(errorMessage).css("background-color", "#FFBABA").show();}
    for(var i=0; i<msg.length; i++) {
        if (msg[i]==="UserLoggedOut") { warningCount++; errorMessage = "You have been logged out"; }
    }
    if (warningCount > 0) { $("#loginFeedback").html(errorMessage).css("background-color", "#FFFFE0").show().delay(1000).fadeOut("slow");}
}

function displayUserDetail(toDisplay, email) {
    if(toDisplay) {

        $("#userDetail").html("<input type='button' id='logoutButton' value='Logout' class='right-align'><span class='right-align'>" + email + "</span>").show();
        $("#logoutButton").click(logout);
    } else {
        $("#userDetail").html("").hide();
    }
}

function clearRegistrationData() {
    $(".registerDetail").val("");
    $("#submitButton").val("Login");
}

function debugConsole(value) {
    if (debug === true) { console.log(value); };
}
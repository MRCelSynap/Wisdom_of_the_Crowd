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
        userLoggedIn(loggedIn);
    }).fail(function () {
        debugConsole("CheckSessionLoggedIn Request could not be handled.");
    }); // CheckSessionLoggedIn

    

    ///Setup the registartion toggle event
    $("#registrationToggle").change(function () {
        if ($("#registrationToggle").is(":checked")) {
            $("#submitButton").prop("value", "Register");
            $(".registrationForm").show();
        } else {
            $("#submitButton").prop("value", "Login");
            $(".registrationForm").hide();
        }
    }); // OnChage for the Registration checkbox

    $("#submitButton").click(function () {
        if ($("#registrationToggle").is(":checked")) {
            registerNewUser();
        } else {
            login();
        }
    }); // on form submit

}); // onPageLoad


function registerNewUser() {
    $.post("Scripts/PHP/HandleRequest.php", { request: "RegisterNewUser", email: $("#email").val() }, function (result) {

    });
}

function login() {
    $.post("Scripts/PHP/HandleRequest.php", { request: "Login", email: $("#email").val(), password: $("#password").val() }, function (result) {
        document.write(result);
        var receivedData = JSON.parse(result);
        debugConsole(receivedData);
        loggedIn = receivedData.isLoggedIn;

        displayLoginErrors(receivedData.log);

        userLoggedIn(loggedIn);
    }).fail(function () {
        debugConsole("Login Request could not be handled.");
    }); // Login

}

function userLoggedIn(isLoggedIn, msg) {
    if (!isLoggedIn) {
        displayLoginForm(true, msg);
    } else {
        displayLoginForm(false, msg);
    }
}

function displayLoginForm(toDisplay, msg) {
    if (toDisplay === true) {
        $("#submissionForm").show();
        debugConsole("Show submissionForm");
    } else if (toDisplay === false) {
        $("#submissionForm").hide();
        debugConsole("Hide submissionForm");
    }
}
function displayLoginErrors(msg) {
    errorMessage = "";
    var i;
    for (i=0; i < msg.length ; i++) {
        if (msg[i]==="EmptyEmail") { errorMessage += "Please enter an email address to login.</br>"; }
        if (msg[i]==="InvalidEmail") { errorMessage += "Please enter a valid email address to login.</br>"; }
        if (msg[i]==="EmptyPassword") { errorMessage += "Please enter a password to login.</br>"; }
        if (msg[i]==="InvalidEmailPassword") { errorMessage += "Invalid Username and Password combination.</br>"; }
        console.log(errorMessage);
    }
    if (errorMessage != "") { $("#loginFeedback").html(errorMessage).css("background-color", "#FFBABA").show();}
}

function debugConsole(value) {
    if (debug === true) { console.log(value); };
}
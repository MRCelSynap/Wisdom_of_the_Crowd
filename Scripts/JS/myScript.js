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
        userLoggedIn(loggedIn, receivedData.log);
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
    $.post("Scripts/PHP/HandleRequest.php", { request: "RegisterNewUser", email: $("#email").val(), password: $("#password").val(), passwordRepeat: $("#passwordRepeat").val(), firstName: $("#firstName").val(), lastName: $("#lastName").val(), birthDate: $("#birthDate").val() }, function (result) {
        //document.write(result);
        var receivedData = JSON.parse(result);
        debugConsole(receivedData);
        displayErrors(receivedData.log)
        if (receivedData.newUserRegistered) {
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

        userLoggedIn(loggedIn, receivedData.log);
    }).fail(function () {
        debugConsole("Login Request could not be handled.");
    }); // Login

}

function logout() {
    $.post("Scripts/PHP/HandleRequest.php", { request: "Logout" }, function (result) {
        //document.write(result);
        var receivedData = JSON.parse(result);
        debugConsole(receivedData);
        loggedIn = receivedData.isLoggedIn;
        displayErrors(receivedData.log);
        userLoggedIn(loggedIn, receivedData.log);

    });
}

function userLoggedIn(isLoggedIn) {
    if (!isLoggedIn) {
        displayLoginForm(true);
        displayUserDetail(false, "");
    } else {
        displayLoginForm(false);
        clearRegistrationData();
        $.post("Scripts/PHP/HandleRequest.php", { request: "GetUserDetails" }, function (result) {
            //document.write(result);
            var receivedData = JSON.parse(result);
            debugConsole(receivedData);
            displayUserDetail(true, receivedData.users);
        });
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
    for (var i = 0; i < msg.length; i++) {
        if (msg[i] === "EmptyEmail") { errorCount++; errorMessage += "Please enter an email address to login.</br>"; }
        if (msg[i] === "InvalidEmail") { errorCount++; errorMessage += "Please enter a valid email address to login.</br>"; }
        if (msg[i] === "InvalidEmailRegister") { errorCount++; errorMessage += "Please enter a valid email address to register.</br>"; }
        if (msg[i] === "EmptyPassword") { errorCount++; errorMessage += "Please enter a password to login.</br>"; }
        if (msg[i] === "PasswordTooShort") { errorCount++; errorMessage += "Password is too short, minimum 8 characters long.</br>"; }
        if (msg[i] === "PasswordsMismatch") { errorCount++; errorMessage += "Passwords do not match.</br>"; }
        if (msg[i] === "InvalidEmailPassword") { errorCount++; errorMessage += "Invalid username and password combination.</br>"; }
        if (msg[i] === "UserAlreadyExists") { errorCount++; errorMessage += "Username already exists.</br>"; }
        if (msg[i] === "InvalidDateFormat") { errorCount++; errorMessage += "The birthday entered is not a valid date.</br>"; }
        if (msg[i] === "InvalidFirstName") { errorCount++; errorMessage += "The first name entered contains invalid characters.</br>"; }
        if (msg[i] === "InvalidLastName") { errorCount++; errorMessage += "The last name entered contains invalid characters.</br>"; }
    }
    if (errorCount > 0) { $("#loginFeedback").html(errorMessage).css("background-color", "#FFBABA").show(); }
    for (var i = 0; i < msg.length; i++) {
        if (msg[i] === "UserLoggedOut") { warningCount++; errorMessage = "You have been logged out"; }
    }
    if (warningCount > 0) { $("#loginFeedback").html(errorMessage).css("background-color", "#FFFFE0").show().delay(1000).fadeOut("slow"); }
}

function displayUserDetail(toDisplay, users) {
    if (toDisplay) {
        var innerHTML = "<input type='button' id='logoutButton' value='Logout' class='right-align'></input>";
        innerHTML += "<table class='full-span output'>";
            for(var i = 0; i < users.length; i++) {
                innerHTML += "<tr id='User" + users[i].userID + "' ";
                if(i==0) {
                    innerHTML += "class='thick-border'";
                }
                innerHTML += "><td class='picture-column'><img src='";
                innerHTML += users[i].profilePicture;
                innerHTML += "' alt='UserPortrait(" + users[i].userID +")' class='display-picture'></td><td><table class='inner'><tr>";
                innerHTML += "<td>First Name:</td><td>" + users[i].firstName + "</td></tr>";
                innerHTML += "<td>Last Name:</td><td>" + users[i].lastName + "</td></tr>";
                innerHTML += "<td>Email:</td><td>" + users[i].email + "</td></tr>";
                innerHTML += "<td>Birth Date:</td><td>" + users[i].birthDate + "</td></tr>";
                innerHTML += "</td></tr></table></td></tr>";
            }
        innerHTML += "</table>";

        $("#userDetail").html(innerHTML).show();
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
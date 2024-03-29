<?php
include_once("../.PHP/ErrorLog.php");
include_once("../.PHP/SessionCheck.php");
include_once("../.PHP/Login.php");
include_once("../.PHP/Logout.php");
include_once("../.PHP/RegisterNewUser.php");
include_once("../.PHP/GetUserDetails.php");

$log = new ErrorLog();
session_start();

switch($_POST["request"]) {
    case "CheckSessionLoggedIn": {
        $response = checkSession($log);
        echo(json_encode(["isLoggedIn" => $response, "log" => $log->getMessages()]));
        break;
    }
    case "GetUserDetails": {
        $users = [];
        $response = getUserDetails($users, $log);
        echo(json_encode(["GetUserDetails" => $response, "users" => $users, "log" => $log->getMessages()]));
        break;
    }
    case "RegisterNewUser": {
        $response = registerNewUser($log);
        echo(json_encode(["newUserRegistered" => $response, "log" => $log->getMessages()]));
        break;
    }
    case "Login": {
        $response = login($log);
        echo(json_encode(["isLoggedIn" => $response, "log" => $log->getMessages()]));
        break;
    }
    case "Logout": {
        $response = logout($log);
        echo(json_encode(["isLoggedIn" => $response, "log" => $log->getMessages()]));
        break;
    }
    default: {
        echo(json_encode([ "request" => $_POST["request"], "response" => "Invalid Request"]));
    }
};
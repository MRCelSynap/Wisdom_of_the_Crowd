<?php
include_once("../.PHP/ErrorLog.php");
include_once("../.PHP/SessionCheck.php");
include_once("../.PHP/Login.php");

$log = new ErrorLog();

switch($_POST["request"]) {
    case "CheckSessionLoggedIn": {
        $response = checkSession($log);
        echo(json_encode(["isLoggedIn" => $response, "log" => $log->getMessages()]));
        break;
    }
    case "RegisterNewUser": {
        echo(json_encode(["newUserRegistered" => $response, "log" => $log->getMessages()]));
        break;
    }
    case "Login": {
        $response = login($log);
        echo(json_encode(["isLoggedIn" => $response, "log" => $log->getMessages()]));
        break;
    }
    default: {
        echo(json_encode([ "request" => $_POST["request"], "response" => "Invalid Request"]));
    }
};
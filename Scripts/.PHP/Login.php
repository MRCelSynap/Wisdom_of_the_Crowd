<?php
include_once("../.PHP/ErrorLog.php");
include_once("../.PHP/DatabaseAccess.php");
include_once("../.PHP/ValidateEmail.php");

function login(ErrorLog &$log) : bool {
    $loggedIn = false;
    $validLogin = true;
    if(empty($_POST["email"])) { $validLogin = false; $log->addMessage("EmptyEmail");}
    else if (!validateEmail($_POST["email"])) { $validLogin = false; $log->addMessage("InvalidEmail"); }
    if(empty($_POST["password"])) { $validLogin = false;  $log->addMessage("EmptyPassword");}
    if($validLogin) {
        $dba = new DatabaseAccess($log);
        $loggedIn = $dba->verifyPassword($_POST["email"], $_POST["password"], $log);
    }

    if($loggedIn) {
        $dba = new DatabaseAccess($log);

        $sessionID = hash("sha512", $_POST["email"] . date('Y-m-d'));

        $dba->setActiveSession($_POST["email"], $sessionID, $log);
        $_SESSION["Session_ID"] = $sessionID;
        $_SESSION["User_Login"] = strtolower($_POST["email"]);
    }

    return $loggedIn;
}
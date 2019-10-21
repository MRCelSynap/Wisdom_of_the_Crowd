<?php
include_once("../.PHP/ErrorLog.php");
include_once("../.PHP/DatabaseAccess.php");

function login(ErrorLog &$log) : bool {
    $loggedIn = false;
    if(empty($_POST["email"])) { $log->addMessage("EmptyEmail");}
    if(empty($_POST["password"])) { $log->addMessage("EmptyPassword");}
    $dba = new DatabaseAccess($log);
    if(!empty($_POST["email"]) && !empty($_POST["password"])) {
        $loggedIn = $dba->verifyPassword($_POST["email"], $_POST["password"], $log);
    }

    if($loggedIn) {
        session_start();
        $dba = new DatabaseAccess($log);

        $sessionID = hash("sha512", $_POST["email"] . date('Y-m-d'));

        $dba->setActiveSession($_POST["email"], $sessionID, $log);
        $_SESSION["Session_ID"] = $sessionID;
        $_SESSION["User_Login"] = strtolower($_POST["email"]);
    }

    return $loggedIn;
}
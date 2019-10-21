<?php
include_once("../.PHP/ErrorLog.php");
include_once("../.PHP/DatabaseAccess.php");

function checkSession(ErrorLog &$log) : bool {
    session_start();
    if(isset($_SESSION["Session_ID"]) && isset($_SESSION["User_Login"])) {
        if(!empty($_SESSION["Session_ID"]) && !empty($_SESSION["User_Login"])) {
            $log->addMessage("Your session ID is: " . $_SESSION["Session_ID"]);
            $log->addMessage("Your user Login is: " . $_SESSION["User_Login"]);
            $dba = new DatabaseAccess($log);
            $dba->isActiveSession($_SESSION["User_Login"], $_SESSION["Session_ID"], $log);
            return true;
        } else { return false; }
    } else { return false; }
}

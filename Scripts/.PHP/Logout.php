<?php
include_once("../.PHP/ErrorLog.php");
include_once("../.PHP/DatabaseAccess.php");

function logout(ErrorLog &$log) : bool {

    $dba = new DatabaseAccess($log);
    $dba->clearActiveSession($_SESSION["User_Login"], $_SESSION["Session_ID"], $log);

    $_SESSION["User_Login"] = "";
    $_SESSION["Session_ID"] = "";
    $log->addMessage("UserLoggedOut");
    return false;
}
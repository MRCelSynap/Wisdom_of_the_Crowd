<?php
include_once("../.PHP/ErrorLog.php");
include_once("../.PHP/DatabaseAccess.php");

function checkSession(ErrorLog &$log) : bool {
    if(isset($_SESSION["Session_ID"]) && isset($_SESSION["User_Login"])) {
        if(!empty($_SESSION["Session_ID"]) && !empty($_SESSION["User_Login"])) {
            $dba = new DatabaseAccess($log);
            $isSessionActive = $dba->isActiveSession($_SESSION["User_Login"], $_SESSION["Session_ID"], $log);
            if($isSessionActive) {
                return true;
            } else {
                $_SESSION["User_Login"] = "";
                $_SESSION["Session_ID"] = "";
                $log->addMessage("InvalidSessionID");
                return false;
            }
        } else { return false; }
    } else { return false; }
}

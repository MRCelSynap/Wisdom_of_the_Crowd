<?php
include_once("../.PHP/ErrorLog.php");
include_once("../.PHP/DatabaseAccess.php");
include_once("../.PHP/User.php");

function getUserDetails(&$users, ErrorLog &$log) : bool {
    $dba = new DatabaseAccess($log);
    $gatheredData = $dba->getUserData($_SESSION["User_Login"], $_SESSION["Session_ID"], $users, $log);
    $gatheredData = $dba->getUserData($_SESSION["User_Login"], $_SESSION["Session_ID"], $users, $log, true);
    return $gatheredData;
}
<?php
include_once("../.PHP/ErrorLog.php");

function validateEmail($email) : bool {
    return ( filter_var($email, FILTER_VALIDATE_EMAIL));
}

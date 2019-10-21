<?php

include_once("../.PHP/DatabaseAccess.php");
include_once("../.PHP/ErrorLog.php");
include_once("../.PHP/User.php");
include_once("../.PHP/ValidateEmail.php");
include_once("../.PHP/ValidateDate.php");
include_once("../.PHP/ValidateName.php");

function registerNewUser(ErrorLog &$log) : bool {
    $newUserAdded = false;
    $validRequest = true;
    $validEmail = validateEmail($_POST["email"]);
    if(!$validEmail) { $log->addMessage("InvalidEmailRegister"); $validRequest = false; }
    if(empty($_POST["password"]) || strlen($_POST["password"]) < 8) { $log->addMessage("PasswordTooShort"); $validRequest = false; }
    $validPassword = ($_POST["password"] === $_POST["passwordRepeat"]);
    if(!$validPassword) { $log->addMessage("PasswordsMismatch"); $validRequest = false;}
    $validDate = validateDate($_POST["birthDate"]);
    if(!$validDate) { $log->addMessage("InvalidDateFormat"); $validRequest = false; }
    $firstName = ltrim($_POST["firstName"]);
    $validFirstName = validateName($firstName) && strlen($firstName) > 0;
    if(!$validFirstName) { $log->addMessage("InvalidFirstName"); $validREquest = false; }
    $lastName = ltrim($_POST["lastName"]);
    $validLastName = validateName($lastName) && strlen($lastName) > 0;
    if(!$validLastName) { $log->addMessage("InvalidLastName"); $validREquest = false; }

    if($validRequest) {
        $user = new User(strtolower($_POST["email"]), $firstName, $lastName, $_POST["birthDate"]); 
        $dba = new DatabaseAccess($log);
        $newUserAdded = $dba->addUser($user->email, $_POST["password"], $user->firstName, $user->lastName, $user->birthDate, $log);
    }
    return $newUserAdded;
}
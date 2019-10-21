<?php

include_once("../.PHP/DatabaseAccess.php");

class User {
    public $userID;
    public $email;
    public $firstName;
    public $lastName;
    public $birthDate;
    public $profilePicture;

    function __construct($email = null, $firstName = null, $lastName = null, $birthDate = null, $userID = null, $profilePicture = null) {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthDate = $birthDate;
        $this->userID = $userID;
        $this->profilePicture = $profilePicture;
    }
}
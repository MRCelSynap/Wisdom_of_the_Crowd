<?php
include_once("../.PHP/ErrorLog.php");

class DatabaseAccess {

    private $link;

    function __construct(ErrorLog &$log) {
        $this->link = mysqli_connect("localhost", "Admin", "admin", "wotc");
        if(!$this->link) {
            $log->addEntry("Error: Unab le to connect to MySQL." . PHP_EOL .
            "Debugging errno: " . mysqli_connect_errno() . PHP_EOL .
            "Debugging error: " . mysqli_connect_error() . PHP_EOL);
            exit;
        } else {
        }
    } // __construct

    function __destruct() {
        mysqli_close($this->link);
    } // __destruct

    function addUser($email, $password, ErrorLog &$log) : bool {
        $query = "INSERT INTO users (Email, PasswordHash, FirstName, LastName, BirthDate) VALUES('" .  strtolower(mysqli_real_escape_string($this->link, $email)) . "','" . password_hash($password, PASSWORD_DEFAULT) . "','Test','User','1970-01-01')";
        $result = mysqli_query($this->link, $query);
        if(!$result) {
            $log->addEntry("User already exists.");
            return false; // User already exists
        } else {
            return true; // User added to the table
        }
    } // addUser

    function verifyPassword($email, $password, ErrorLog &$log) : bool {
        $query = "SELECT PasswordHash FROM users WHERE Email='" . strtolower(mysqli_real_escape_string($this->link, $email)) . "'";
        $result = mysqli_query($this->link, $query);
        if($result->num_rows > 0) {
            $passwordHash = $result->fetch_assoc()["PasswordHash"];
            if(password_verify($password, $passwordHash)) {
                return true;
            } else { 
                $log->addMessage("InvalidEmailPassword");
                /*
                TODO: add brute force prevention
                */
                return false;
            }
        } else {
            $log->addMessage("InvalidEmailPassword");
            return false;
        }
    } // verifyPassword

    function setActiveSession($email, $sessionID, ErrorLog&$log) : bool {
        $query = "UPDATE users SET SessionID='$sessionID' WHERE Email='$email'";
        $result = mysqli_query($this->link, $query);
        if ($result < 1) {
            $log->addMessage("Something went wrong");
            return false;
        }
        return true;
    }

    function isActiveSession($email, $sessionID, ErrorLog &$log) : bool{
        $query = "SELECT Email FROM users WHERE Email='" . mysqli_real_escape_string($this->link, $email) . "' AND SessionID='$sessionID'";
        $result = mysqli_query($this->link, $query);
        if ($result->num_rows > 0) {
            $log->addMessage("Active session found.");
            return true;
        } else { 
            $log->addMessage("No active session found.");
            return false;
        }
    }
};
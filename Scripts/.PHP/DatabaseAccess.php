<?php
include_once("../.PHP/ErrorLog.php");
include_once("../.PHP/User.php");

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

    function addUser($email, $password, $firstName, $lastName, $birthDate, ErrorLog &$log) : bool {
        $query = "INSERT INTO users (Email, PasswordHash, FirstName, LastName, BirthDate) VALUES('" .  strtolower(mysqli_real_escape_string($this->link, $email)) . "','" . password_hash($password, PASSWORD_DEFAULT) . "','" . mysqli_real_escape_string($this->link, $firstName) . "','" . mysqli_real_escape_string($this->link, $lastName) . "','" . mysqli_real_escape_string($this->link, $birthDate) . "')";
        $result = mysqli_query($this->link, $query);
        if(!$result) {
            $log->addMessage("UserAlreadyExists");
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
        if($result->num_rows > 0) {
            return true;
        } else { 
            return false;
        }
    }

    function clearActiveSession($email, $sessionID, ErrorLog &$log) : bool {
        $query = "UPDATE users SET SessionID=NULL WHERE Email='$email' AND SessionID='$sessionID'";
        $result = mysqli_query($this->link, $query);
        if($result < 1) {
            return true;
        } else {
            return false;
        }
    }

    function getUserData($email, $sessionID, &$userList, ErrorLog &$log, $all = false) : bool {
        ///UserID, Email, FirstName, LastName, BirthDate, ProfilePicture
        $query = "SELECT UserID, Email, FirstName, LastName, BirthDate, ProfilePicture FROM users WHERE Email = '$email' AND SessionID = '$sessionID'";
        $result = mysqli_query($this->link, $query);
        if($result->num_rows > 0) {
            if($all) {
                mysqli_free_result($result);
                $query = "SELECT UserID, Email, FirstName, LastName, BirthDate, ProfilePicture FROM users ORDER BY BirthDate ASC";
                $result = mysqli_query($this->link, $query);
                while($data = $result->fetch_assoc()) {
                    $user = new User($data["Email"], $data["FirstName"], $data["LastName"], $data["BirthDate"], $data["UserID"], $data["ProfilePicture"]);
                    array_push($userList, $user);
                    unset($user);
                }
                return true;
            } else {
                $data = $result->fetch_assoc();
                array_push($userList, new User($data["Email"], $data["FirstName"], $data["LastName"], $data["BirthDate"], $data["UserID"], $data["ProfilePicture"]));
                return true;
            }
        }
        return false;
    }
};
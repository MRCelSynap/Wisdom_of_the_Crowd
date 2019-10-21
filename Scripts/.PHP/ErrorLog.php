<?php
class ErrorLog {
    private $log = [];

    function __construct() {
    } // __construct

    function __destruct() {
        $this->log = 0;
    } // __destruct

    public function addMessage($msg) {
        array_push($this->log, $msg);
    } // addMessage

    public function getMessages() {
        return($this->log);
    } // getMessages

}

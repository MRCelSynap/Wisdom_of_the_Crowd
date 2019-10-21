<?php

function validateName($name) : bool {
    // RegEx from https://salesforce.stackexchange.com/questions/41153/best-regex-for-first-last-name-validation
    //^[^±!@£$%^&*_+§¡€#¢§¶•ªº«\\/<>?:;|=.,]{1,20}$
    return preg_match('/^[^±!@£$%^&*_+§¡€#¢§¶•ªº«\/\\<>?:;|=.,]{1,20}$/', $name)>0;
}

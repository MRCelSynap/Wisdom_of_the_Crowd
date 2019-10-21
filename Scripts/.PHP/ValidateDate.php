<?php
function validateDate($date, $format = "Y-m-d") : bool {
    $dateFormatted = DateTime::createFromFormat($format, $date);
    $validDate = $dateFormatted && $dateFormatted->format($format);
    if ($date > date($format)) { $validDate = false; }
    return $validDate;
}
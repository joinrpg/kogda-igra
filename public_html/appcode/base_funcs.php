<?php
date_default_timezone_set ("Europe/Moscow");

function get_current_year ()
{
    $year = date("Y");
    if (date("m") > 10)
    {
        $year++;
    }
    return $year;
}
?>
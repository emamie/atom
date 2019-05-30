<?php

use farhadi\IntlDateTime as DateTime;

function atomGregorianToPersian($date, $output_format = "yyyy/MM/dd")
{
    $date = new DateTime($date, null, 'gregorian');

    $date->setCalendar('persian');

    return $date->format($output_format);

}

function atomPersianToGregorian($date, $output_format = "yyyy/MM/dd")
{
    $date = new DateTime($date, null, 'persian');

    $date->setCalendar('gregorian');

    return $date->format($output_format);
}
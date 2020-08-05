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

function atomNumToEN($string)
{
    $fa = preg_split('//u', '۰۱۲۳۴۵۶۷۸۹', -1, PREG_SPLIT_NO_EMPTY);
    $ar = preg_split('//u', '٠١٢٣٤٥٦٧٨٩', -1, PREG_SPLIT_NO_EMPTY);
    $en = preg_split('//u', '0123456789', -1, PREG_SPLIT_NO_EMPTY);

    $arr1 = array_merge($fa, $ar);
    $arr2 = array_merge($en, $en);

    return str_replace($arr1, $arr2, $string);
}

function atomIsNationalId($code) {
    if (!preg_match('/^[0-9]{10}$/', $code)) {
        return false;
    }

    for ($i = 0; $i < 10; $i++) {
        if (preg_match('/^' . $i . '{10}$/', $code)) {
            return false;
        }
    }

    for ($i = 0, $sum = 0; $i < 9; $i++) {
        $sum += ((10 - $i) * intval(substr($code, $i, 1)));
    }
    $ret = $sum % 11;
    $parity = intval(substr($code, 9, 1));
    if (($ret < 2 && $ret == $parity) || ($ret >= 2 && $ret == 11 - $parity)) {
        return true;
    }

    return false;
}


function atomRplcCharAr2Fa($text){
    $arabic = array("ي", "ك");
    $persian = array("ی", "ک");

    return str_replace($arabic,$persian,$text);

}


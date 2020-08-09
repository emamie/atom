<?php

use Carbon\Carbon;
use Morilog\Jalali\Jalalian;
use Morilog\Jalali\CalendarUtils;

function atomGregorianToPersian($date, $output_format = "Y/m/d")
{
    $carbon = new Carbon($date);

    $jdate = Jalalian::fromCarbon($carbon);

    return $jdate->format($output_format);

}

function atomPersianToGregorian($date, $input_format = "Y/m/d",$output_format = "Y/m/d")
{
    try {
        $carbon = CalendarUtils::createCarbonFromFormat($input_format, $date);
    } catch(\Exception $e){
        $carbon = CalendarUtils::createCarbonFromFormat(str_replace('/', '-', $input_format), $date);
    }
    return $carbon->format($output_format);
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


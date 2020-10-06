<?php

use Carbon\Carbon;

function atomGregorianToPersian($date, $output_format = "Y/m/d")
{

    if(class_exists('Morilog\\Jalali\\Jalalian')) {

        $carbon = new Carbon($date);

        $jdate = \Morilog\Jalali\Jalalian::fromCarbon($carbon);

        return $jdate->format($output_format);

    } else {

        // @deprecated way : farhadi\IntlDateTime have bug in convert 1357/1/1

        if($output_format == "Y/m/d"){
            $output_format = "yyyy/MM/dd";
        }

        $date = new \farhadi\IntlDateTime($date, null, 'gregorian');

        $date->setCalendar('persian');

        return $date->format($output_format);
    }

}

function atomPersianToGregorian($date, $input_format = "Y/m/d",$output_format = "Y/m/d")
{

    if(class_exists('Morilog\\Jalali\\Jalalian')) {

        try {
            $carbon = \Morilog\Jalali\CalendarUtils::createCarbonFromFormat($input_format, $date);
        } catch (\Exception $e) {
            $carbon = \Morilog\Jalali\CalendarUtils::createCarbonFromFormat(str_replace('/', '-', $input_format), $date);
        }
        return $carbon->format($output_format);

    } else {

        // @deprecated way : farhadi\IntlDateTime have bug in convert 1357/1/1

        $output_format = $input_format;
        if($output_format == "Y/m/d"){
            $output_format = "yyyy/MM/dd";
        }

        $date = new \farhadi\IntlDateTime($date, null, 'persian');

        $date->setCalendar('gregorian');

        return $date->format($output_format);
    }
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

/**
 * @TODO reimplementation as service
 */
function atomSendSMS($mobile, $text, $package='default', $id=0){
    $input_sms = [
        'table_name' => $package,
        'pid' => $id,
        'text' => $text,
        'mobile' => $mobile,
        'status_send' => 0
    ];
    \Razavi\Salamat\Model\Sms::firstOrCreate($input_sms);
}


function atomIsExistUrl($url) {


  $file_headers = @get_headers($url);
  if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
    return false;
  }
  else {


    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($httpcode >= 200 && $httpcode < 300) {
      $status = TRUE;
    }
    else {
      $status = FALSE;
    }

    return $status;
  }
}


<?php
namespace Emamie\Atom\Model;

use Emamie\IntlDateTime;

trait DateTimeTrait
{
    protected static function boot()
    {
        parent::boot();

        self::saving(function($charge){

            $charge->date_exp = $charge->toGregorianDate("$charge->date_exp");
        });

    }

    public function toArray()
    {
        $arr = parent::toArray();

        foreach($this->getDates() as $key){
            $arr[$key] = $this->toPersianDate($this->$key);
        }

        return $arr;
    }

    public function toPersianDate($date)
    {
        $intl_date = new IntlDateTime($date, null, 'gregorian');
        $intl_date->setCalendar('persian');
        return $result = $intl_date->format("yyyy-MM-dd");
    }

    public function toGregorianDate($date)
    {
        $date = str_replace('-', '/', $date);
        $intl_date = new IntlDateTime($date, null, 'persian');
        $intl_date->setCalendar('gregorian');
        $result = $intl_date->format("yyyy-MM-dd");
        return $result;
    }
}
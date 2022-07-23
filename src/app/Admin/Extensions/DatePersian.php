<?php

namespace Emamie\Atom\Admin\Extensions;


use Encore\Admin\Form\Field\Text;

class DatePersian extends Text
{
    protected static $css = [
        '/vendor/atom/date_picker_persian/persian-datepicker.css',
    ];

    protected static $js = [
        '/vendor/atom/date_picker_persian/persian-date.min.js',
        '/vendor/atom/date_picker_persian/persian-datepicker.js',

    ];


    protected $format = 'YYYY/MM/DD';
    protected $minDate = null;
    protected $maxDate = null;

    protected $view = 'atom::form.date_persian';


    public function format($format)
    {
        $this->format = $format;
        return $this;
    }

    // $date persian-date
    public function minDate($date)
    {
        $d_date = atomPersianToGregorian($date);
        $this->minDate = strtotime($d_date);
        return $this;
    }

    public function maxDate($date)
    {
        $d_date = atomPersianToGregorian($date);
        $this->maxDate = strtotime($d_date);
        return $this;
    }

    public function prepare($value)
    {
        if ($value === '') {
            return null;
        }

        return atomPersianToGregorian(atomNumToEN($value));
    }

    public function render()
    {
        $str_min_max_date = "";
        if(!empty($this->minDate)){
            $str_min_max_date =$str_min_max_date .  " minDate : " . $this->minDate . ",";
        }
        if(!empty($this->maxDate)){
            $str_min_max_date =$str_min_max_date.  " maxDate : " . $this->maxDate ;
        }

        $this->script = "$(document).ready(function() {
                            $('{$this->getElementClassSelector()}:not(.pwt-datepicker-input-element)').persianDatepicker({
                                format: '" . $this->format . "',
                                Placement: 'bottom',
                                autoClose: true
                            });
                        });";


        $this->prepend('<i class="fa fa-calendar fa-fw date-persian" ></i>')
            ->defaultAttribute('style', 'width: 110px')
            ->defaultAttribute('type', 'text')
            ->defaultAttribute('name', $this->elementName ?: $this->formatName($this->column))
            ->defaultAttribute('value', old($this->column, $this->value()))
            ->defaultAttribute('class', 'form-control date-persian ' . $this->getElementClassString())
            ->defaultAttribute('placeholder', $this->getPlaceholder());
        return parent::render();


    }
}

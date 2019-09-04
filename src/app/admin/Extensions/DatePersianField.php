<?php
namespace Emamie\Atom\admin\Extensions;


use Encore\Admin\Form\Field\Text;

class DatePersianField extends Text
{
//    protected static $css = [
//        '/datepicker/css/jquery.calendars.picker.css',
//    ];

//    protected static $js = [
//        '/datepicker/js/jquery.calendars.js',
//        '/datepicker/js/jquery.calendars.plus.js',
//        '/datepicker/js/jquery.plugin.js',
//        '/datepicker/js/jquery.calendars.picker.js',
//        '/datepicker/js/jquery.calendars.persian.js',
//        '/datepicker/js/jquery.calendars.picker-fa.js',
//    ];


    protected $format = 'YYYY/MM/DD';

    protected $view = 'atom::date_persian';


    public function format($format)
    {
        $this->format = $format;

        return $this;
    }

    public function prepare($value)
    {
        if ($value === '') {
            $value = null;
        }

        return $value;
    }

    public function render()
    {

        $this->prepend('<i class="fa fa-calendar fa-fw " ></i>')
            ->defaultAttribute('style', 'width: 110px')
            ->defaultAttribute('type', 'text')
            ->defaultAttribute('id', $this->id)
            ->defaultAttribute('name', $this->elementName ?: $this->formatName($this->column))
            ->defaultAttribute('value', old($this->column, $this->value()))
            ->defaultAttribute('class', 'form-control date-persian-mask '.$this->getElementClassString())
            ->defaultAttribute('placeholder', $this->getPlaceholder());

        return parent::render();


    }
}

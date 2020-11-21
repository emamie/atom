<?php
namespace Emamie\Atom\Admin\Extensions;


use Encore\Admin\Form\Field;

class CKEditor extends Field
{
    public static $js = [
        '/vendor/atom/ckeditor/ckeditor.js',
        '/vendor/atom/ckeditor/adapters/jquery.js',
    ];

    protected $view = 'atom::form.ckeditor';

    public function render()
    {
        $this->script = "$('textarea.{$this->getElementClassString()}').ckeditor();";

        return parent::render();
    }
}

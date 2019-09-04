<?php
namespace Emamie\Atom\admin\Extensions;


use Encore\Admin\Form\Field;

class CKEditor extends Field
{
    public static $js = [
        '/atom/ckeditor/ckeditor.js',
        '/atom/ckeditor/adapters/jquery.js',
    ];

    protected $view = 'atom::ckeditor';

    public function render()
    {
        $this->script = "$('textarea.{$this->getElementClassString()}').ckeditor();";

        return parent::render();
    }
}
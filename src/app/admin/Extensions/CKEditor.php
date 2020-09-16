<?php
namespace Emamie\Atom\admin\Extensions;


use Encore\Admin\Form\Field;

class CKEditor extends Field
{
    public static $js = [
        '/vendor/atom/lib/ckeditor/ckeditor.js',
        '/vendor/atom/lib/ckeditor/adapters/jquery.js',
    ];

    protected $view = 'atom::ckeditor';

    public function render()
    {
        $this->script = "$('textarea.{$this->getElementClassString()}').ckeditor();";

        return parent::render();
    }
}

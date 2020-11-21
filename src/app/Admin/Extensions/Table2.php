<?php
namespace Emamie\Atom\Admin\Extensions;


use Encore\Admin\Facades\Admin;
use Encore\Admin\Form\Field\HasMany;
use Illuminate\Support\Str;

class Table2 extends HasMany
{
    protected $viewMode = 'table';

    public function render()
    {
        return $this->renderTable();
    }
}

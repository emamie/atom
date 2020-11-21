<?php
namespace Emamie\Atom\Admin\Extensions;

class Mobile2 extends Number2
{
    public function render()
    {
        $this->options['mask'] = '09DDDDDDDDD';

        $this->inputmask($this->options);

        $this->prepend('<i class="fa fa-mobile fa-fw"></i>')
            ->defaultAttribute('style', 'width: 130px;text-align:left;direction:ltr');

        return parent::render();
    }

    public function prepare($value)
    {
        $value = parent::prepare($value);
        if (strlen($value) < 3) {
            return 0;
        }
    }

}

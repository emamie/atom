<?php
namespace Emamie\Atom\Admin\Extensions;

class Phone2 extends Number2
{
    public function render()
    {
        $this->options['mask'] = '0DDDDDDDDDD';

        $this->inputmask($this->options);

        $this->prepend('<i class="fa fa-phone fa-fw"></i>')
            ->defaultAttribute('style', 'width: 130px;text-align:left;direction:ltr');

        return parent::render();
    }

    public function prepare($value)
    {
        $value = parent::prepare($value);
        if (strlen($value) < 3) {
            return 0;
        }

        return $value;
    }
}

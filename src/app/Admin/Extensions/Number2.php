<?php
namespace Emamie\Atom\Admin\Extensions;


use Encore\Admin\Form\Field\Text;

class Number2 extends Text
{

    protected $withoutIcon = true;

    public static $js = [
        '/vendor/atom/mask/jquery.mask.min.js',
    ];

    /**
     * @see https://igorescobar.github.io/jQuery-Mask-Plugin/docs.html
     *
     * @var array
     */
    protected $options = [
        'mask' => 'DDDDDDDDDDD',
        'translation' => [
            'D' => ['pattern' => '[٠١٢٣٤٥٦٧٨٩۰۱۲۳۴۵۶۷۸۹0123456789*]'],
            '0' => ['pattern' => '[٠۰0]'],
            '1' => ['pattern' => '[١۱1]'],
            '2' => ['pattern' => '[٢۲2]'],
            '3' => ['pattern' => '[٣۳3]'],
            '4' => ['pattern' => '[٤۴4]'],
            '5' => ['pattern' => '[٥۵5]'],
            '6' => ['pattern' => '[٦۶6]'],
            '7' => ['pattern' => '[٧۷7]'],
            '8' => ['pattern' => '[٨۸8]'],
            '9' => ['pattern' => '[٩۹9]'],
        ],
    ];

    /**
     * Add inputmask to an elements.
     *
     * @param array $options
     *
     * @return $this
     */
    public function inputmask($options)
    {
        $mask = $options['mask'];

        unset($options['mask']);

        $options = json_encode($options,JSON_UNESCAPED_UNICODE);

        $this->script = "$('{$this->getElementClassSelector()}').mask('{$mask}',{$options});";

        return $this;
    }

    public function render()
    {
        $this->inputmask($this->options);

        $this->defaultAttribute('style', 'width: 110px;text-align:center;direction:ltr');

        return parent::render();
    }

    public function prepare($value)
    {
        if ($value === '') {
            return 0;
        }

        return atomNumToEN($value);
    }
}

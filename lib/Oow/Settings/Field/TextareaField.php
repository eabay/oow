<?php

namespace Oow\Settings\Field;

class TextareaField extends AbstractField
{
    protected $defaultAttribs = array(
        'class' => 'regular-text code',
        'rows' => 10,
        'cols' => 50
    );

    public function render()
    {
        $html = sprintf('<textarea %s>%s</textarea> %s',
            $this->getAttribs(),
            $this->value,
            $this->getDescriptionHtml()
        );

        return $html;
    }
}
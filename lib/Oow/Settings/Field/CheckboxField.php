<?php

namespace Oow\Settings\Field;

class CheckboxField extends AbstractField
{
    protected $forcedAttribs = array(
        'type' => 'checkbox'
    );

    public function render()
    {
        if ($this->value) {
            $this->attr['checked'] = 'checked';
        }

        $html = sprintf('<label><input %1$s> <span class="description">%2$s</span></label>',
            $this->getAttribs(),
            $this->description
        );

        return $html;
    }
}
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

        $html = sprintf('<label for="%1$s_%2$s"><input id="%1$s_%2$s" name="%1$s[%2$s]" %3$s> <span class="description">%4$s</span></label>',
            $this->optionName,
            $this->id,
            $this->getAttribs(),
            $this->description
        );

        return $html;
    }
}
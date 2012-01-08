<?php

namespace Hwm\WordPress\Settings\Field;

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
        
        $html = sprintf('<input id="%1$s_%2$s" name="%1$s[%2$s]" %3$s>',
            $this->optionName,
            $this->id,
            $this->getAttribs()
        );
        
        return $html;
    }
}
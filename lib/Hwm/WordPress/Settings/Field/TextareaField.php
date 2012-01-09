<?php

namespace Hwm\WordPress\Settings\Field;

class TextareaField extends AbstractField
{
    protected $defaultAttribs = array(
        'class' => 'regular-text code',
        'rows' => 10,
        'cols' => 50
    );
    
    public function render()
    {
        $html = sprintf('<textarea id="%1$s_%2$s" name="%1$s[%2$s]" %3$s>%4$s</textarea>',
            $this->optionName,
            $this->id,
            $this->getAttribs(),
            $this->value
        );
        
        return $html;
    }
}
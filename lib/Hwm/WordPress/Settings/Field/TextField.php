<?php

namespace Hwm\WordPress\Settings\Field;

class TextField extends AbstractField
{
    protected $defaultAttribs = array(
        'class' => 'regular-text'
    );
    
    protected $forcedAttribs = array(
        'type' => 'text'
    );
    
    public function render()
    {
        $this->attr['value'] = $this->value;
        
        $html = sprintf('<input id="%1$s_%2$s" name="%1$s[%2$s]" %3$s>',
            $this->optionName,
            $this->id,
            $this->getAttribs()
        );
        
        return $html;
    }
}
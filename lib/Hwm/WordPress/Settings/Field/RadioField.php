<?php

namespace Hwm\WordPress\Settings\Field;

class RadioField extends SelectField
{
    protected $forcedAttribs = array(
        'type' => 'radio'
    );
    
    public function render()
    {
        $html = '';
        
        foreach($this->options as $value => $label) {
            $this->attr['value'] = $value;
            
            $checked = $this->value === $value ? 'checked' : '';
            
            $html .= sprintf('<label><input id="%1$s_%2$s" name="%1$s[%2$s]" %3$s %5$s>%4$s</label> ',
                $this->optionName,
                $this->id,
                $this->getAttribs(),
                $label,
                $checked
            );
        }
        
        return $html;
    }
}
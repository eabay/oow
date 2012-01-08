<?php

namespace Hwm\WordPress\Settings\Field;

class SelectField extends AbstractField
{
    /**
     * @var array
     */
    protected $options = array();
    
    public function __construct($key, $title, array $options, array $attr = array())
    {
        parent::__construct($key, $title, $attr);
        
        $this->options = $options;
    }
    
    public function render()
    {
        $html = sprintf('<select id="%1$s_%2$s" name="%1$s[%2$s]" %3$s>',
            $this->optionName,
            $this->id,
            $this->getAttribs()
        );
        
        foreach($this->options as $value => $label) {
            $selected = $this->value === $value ? 'selected' : '';
            
            $html .= sprintf('<option value="%s" %s>%s</option>', $value, $selected, $label);
        }
        
        $html .= '</select>';
        
        return $html;
    }
}
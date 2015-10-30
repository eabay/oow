<?php

namespace Oow\Settings\Field;

class CheckboxField extends AbstractField
{
    protected $forcedAttribs = array(
        'type' => 'checkbox'
    );

    protected $label;

    public function __construct($key, $title, array $attr = array(), $label = null)
    {
        parent::__construct($key, $title, $attr);

        $this->label = $label;
    }

    public function render()
    {
        if ($this->value) {
            $this->attr['checked'] = 'checked';
        }

        $html = "<label><input {$this->getAttribs()}>";

        if ($this->label) {
            $html .= $this->label;
            $html .= '</label>';
            $html .= "<p class=\"description\">{$this->description}</p>";
        } else {
            $html .= $this->description;
            $html .= '</label>';
        }

        return $html;
    }
}
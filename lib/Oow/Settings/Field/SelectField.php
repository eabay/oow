<?php

namespace Oow\Settings\Field;

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
        if (array_key_exists('multiple', $this->attr)) {
            $this->attr['name'] .= '[]';
        }

        $html = sprintf('<select %s>',
            $this->getAttribs()
        );

        foreach($this->options as $value => $label) {
            $selected = ($this->value == $value) || (is_array($this->value) && in_array($value, $this->value)) ? 'selected' : '';

            $html .= sprintf('<option value="%s" %s>%s</option>', $value, $selected, $label);
        }

        $html .= '</select>';
        $html .= $this->getDescriptionHtml();

        return $html;
    }
}
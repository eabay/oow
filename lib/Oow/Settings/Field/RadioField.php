<?php

namespace Oow\Settings\Field;

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

            $html .= sprintf('<label><input %s %s>%s</label>',
                $this->getAttribs(),
                $checked,
                $label
            );
        }

        $html .= $this->getDescriptionHtml();

        return $html;
    }

    public function getAttribs()
    {
        unset($this->attr['id']);

        return parent::getAttribs();
    }
}
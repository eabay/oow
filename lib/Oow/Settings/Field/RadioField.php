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

            $html .= sprintf('<label><input name="%1$s[%2$s]" %3$s %5$s>%4$s</label>',
                $this->optionName,
                $this->id,
                $this->getAttribs(),
                $label,
                $checked
            );
        }

        $html .= $this->getDescriptionHtml();

        return $html;
    }
}
<?php

namespace Oow\Settings\Field;

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

        $html = sprintf('<input %s> %s', $this->getAttribs(), $this->getDescriptionHtml());

        return $html;
    }
}
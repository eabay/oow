<?php

namespace Oow\Settings\Field;

class FieldGroup extends AbstractField
{
    protected $fields = array();

    public function __construct($key, $title, array $fields = array())
    {
        parent::__construct($key, $title);

        foreach($fields as $field) {
            $this->addField($field);
        }
    }

    public function addField(Field $field)
    {
        $this->fields[] = $field;
    }

    public function render()
    {
        $html   = '';

        foreach($this->fields as $field) {
            /* @var $field Field */
            $field->setOptionName($this->optionName);
            $field->setDescription($field->getTitle());
            $field->setValue($this->value[$field->getId()]);
            $field->setId($this->getId(). '.' . $field->getId());

            $html .= $field->render();
        }

        return $html;
    }
}
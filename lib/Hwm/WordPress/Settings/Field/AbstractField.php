<?php

namespace Hwm\WordPress\Settings\Field;

use Hwm\WordPress\Settings;
use Hwm\WordPress\Settings\Section;

abstract class AbstractField implements Field
{
    /**
     * @var string
     */
    protected $id;
    
    /**
     * @var string
     */
    protected $title;
    
    /**
     * @var mixed
     */
    protected $value;
    
    /**
     * @var mixed
     */
    protected $optionName;
    
    /**
     * @var Hwm\WordPress\Settings\Section
     */
    protected $section;
    
    /**
     * @var array
     */
    protected $attr = array();
    
    /**
     * @var array
     */
    protected $defaultAttribs = array();
    
    /**
     * @var array
     */
    protected $forcedAttribs = array();
    
    public function __construct($key, $title, array $attr = array())
    {
        $this->id = $key;
        $this->title = $title;
        
        $this->attr = array_merge($this->defaultAttribs, $attr, $this->forcedAttribs);
    }
    
    public function setOptionName($name)
    {
        $this->optionName = $name;
        
        return $this;
    }
    
    public function setSection(Section $section)
    {
        $this->section = $section;
        
        return $this;
    }
    
    public function setValue($value)
    {
        $this->value = $value;
        
        return $this;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function getCallback()
    {
        $self = $this;
        
        return function() use ($self) {
            echo $self->render();
        };
    }
    
    public function getSection()
    {
        return $this->section;
    }
    
    public function getAttribs()
    {
        $attribs = '';
        
        foreach ($this->attr as $name => $value) {
            $attribs .= ' ' . $name . '="' . $value.'"';
        }
        
        return $attribs;
    }
}
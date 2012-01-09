<?php

namespace Hwm\WordPress\Settings\Field;

use Hwm\WordPress\Settings;
use Hwm\WordPress\Settings\Section;

interface Field
{
    /**
     * @param Hwm\WordPress\Settings\Section $section
     * 
     * @return Field
     */
    public function setSection(Section $section);
    
    /**
     * @return Field
     */
    public function setValue($value);
    
    /**
     * @return Field
     */
    public function setOptionName($name);
    
    /**
     * @return string;
     */
    public function getId();
    
    /**
     * @return string;
     */
    public function getTitle();
    
    /**
     * @return \Closure;
     */
    public function getCallback();
    
    /**
     * 
     * @return Hwm\WordPress\Settings\Section
     */
    public function getSection();
    
    /**
     * Returns HTML rendered field
     * 
     * @return string
     */
    public function render();
}
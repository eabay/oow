<?php

namespace Oow\Settings\Field;

use Oow\Settings;
use Oow\Settings\Section;

interface Field
{
    /**
     * @param Section $section
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
    public function setId($value);

    /**
     * @return Field
     */
    public function setOptionName($name);

    /**
     *
     * @return Section
     */
    public function setDescription($value);

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
     * @return Section
     */
    public function getSection();

    /**
     * Returns HTML rendered field
     *
     * @return string
     */
    public function render();
}
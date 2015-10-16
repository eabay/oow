<?php

namespace Oow\Settings;

use Oow\Settings\Section;
use Oow\Settings\Field\Field;

/** @Plugin */
class SettingsPage
{
    /**
     * @var array
     */
    protected $registry = array();

    /**
     * @var Section
     */
    protected $currentSection;

    public function __construct(array $options = array())
    {
        $options['field_values'] = get_option($options['option_name']);

        $options = array_merge(array(
            'sanitize_callback' => '',
            'sections'          => array(),
            'fields'            => array(),
            'parent_slug'       => 'options-general.php',
            'icon_url'          => '',
            'position'          => null,
        ), $options);

        $this->registry = $options;
    }

    /**
     * Adds a new section
     *
     * @param Section $section
     * @return SettingsPage
     */
    public function addSection(Section $section)
    {
        $this->registry['sections'][] = $section;

        $this->currentSection = $section;

        return $this;
    }

    /**
     * Adds a field
     *
     * @param Field $field
     * @return SettingsPage
     */
    public function addField(Field $field)
    {
        $field
            ->setSection($this->currentSection)
            ->setOptionName($this->registry['option_name'])
        ;

        if (isset($this->registry['field_values'][$field->getId()])) {
            $field->setValue($this->registry['field_values'][$field->getId()]);
        }

        $this->registry['fields'][] = $field;

        return $this;
    }

    /**
     * Renders settings page
     */
    public function render()
    {
        $reg = $this->registry;
        ?>
        <div class="wrap">
            <h1><?php echo $reg['page_title']; ?></h1>
            <form action="options.php" method="post">
                <?php settings_fields($reg['option_name']); ?>
                <?php do_settings_sections($reg['option_name']); ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /** @Hook(tag="admin_init") */
    public function initialize()
    {
        $reg = $this->registry;

        register_setting($reg['option_group'], $reg['option_name'], $reg['sanitize_callback']);

        foreach ($reg['sections'] as $section) {
            /* @var $section Section */
            add_settings_section($section->getId(), $section->getTitle(), $section->getCallback(), $reg['option_name']);
        }

        foreach ($reg['fields'] as $field) {
            /* @var $field Field */
            add_settings_field($field->getId(), $field->getTitle(), $field->getCallback(), $reg['option_name'], $field->getSection()->getId());
        }
    }

    /** @Hook(tag="admin_menu") */
    public function setMenu()
    {
        $reg = $this->registry;

        if ($reg['parent_slug'] == 'own') {

            add_menu_page(
                $reg['page_title'],
                $reg['menu_title'],
                $reg['capability'],
                $reg['option_name'],
                array($this, 'render'),
                $reg['icon_url'],
                $reg['position']
            );
        } else {
            add_submenu_page(
                $reg['parent_slug'],
                $reg['page_title'],
                $reg['menu_title'],
                $reg['capability'],
                $reg['option_name'],
                array($this, 'render')
            );
        }
    }
}
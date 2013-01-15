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
            'fields'            => array()
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
            <div class="icon32" id="icon-options-general"><br></div>
            <h2><?php echo $reg['page_title']; ?></h2>
            <form action="options.php" method="post">
            <?php settings_fields($reg['option_name']); ?>
            <?php do_settings_sections($reg['option_name']); ?>
            <p class="submit">
                <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
                <input name="Reset" type="reset" class="button" value="<?php esc_attr_e('Reset'); ?>" />
            </p>
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

        add_options_page(
            $reg['page_title'],
            $reg['menu_title'],
            $reg['capability'],
            $reg['option_name'],
            array($this, 'render')
        );
    }
}
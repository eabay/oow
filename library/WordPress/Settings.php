<?php

class WordPress_Settings
{
	/**
	 * 
	 * @var array
	 */
	protected $_registry;
	
	/**
	 * Option Name
	 * 
	 * @var string
	 */
	protected $_optionName;
	
	/**
	 * Option values
	 * 
	 * @var array
	 */
	protected $_values;
	
	protected $_latestSection = 'default';
	
	
	public function __construct(array $options = array())
	{
		$options = array_merge(array(
			'sanitize_callback' => '',
			'sections' => array(),
			'fields' => array()
		), $options);
		
		$this->_registry = $options;
		
		$this->_values = get_option($this->_registry['option_name']);
	}
	
	public function init()
	{
		register_setting($this->_registry['option_group'], $this->_registry['option_name'], $this->_registry['sanitize_callback']);
		
		foreach ($this->_registry['sections'] as $section) {
			add_settings_section($section['id'], $section['title'], $section['callback'], $this->_registry['option_name']);
		}
		
		foreach ($this->_registry['fields'] as $field) {
			add_settings_field($field['id'], $field['title'], $field['callback'], $this->_registry['option_name'], $field['section']);
		}
	}
	
	/**
	 * Adds a new section
	 * 
	 * @param array $options
	 * @return WordPress_Settings
	 */
	public function addSection(array $options)
	{
		if (!function_exists($options['callback'])) {
			$options['callback'] = $this->fnEcho($options['callback']);
		}
		
		$this->_registry['sections'][] = $options;
		
		$this->_latestSection = $options['id'];
		
		return $this;
	}
	
	/**
	 * Adds a field
	 * 
	 * @param array $options
	 * @return WordPress_Settings
	 */
	public function addField(array $options)
	{
		$options = array_merge(array(
			'type' => 'text',
			'section' => null
		), $options);
		
		if (in_array($options['type'], array('radio', 'select'))) {
			$options['callback'] = $this->{"field".ucfirst($options['type'])}($options['key'], $options['options']);
		} else {
			$options['callback'] = $this->{"field".ucfirst($options['type'])}($options['key']);
		}
		
		$options['id'] = "{$this->_registry['option_name']}_{$options['key']}";
		$options['section'] = $options['section'] ? $options['section'] : $this->_latestSection;
		
		$this->_registry['fields'][] = $options;
		
		$this->_latestSection = $options['section'];
		
		return $this;
	}
	
	public function fnEcho($text)
	{
		return create_function('', "echo '$text';");
	}
	
	public function fieldText($key)
	{
		$html = sprintf('<input id="%s_%s" name="%s[%s]" size="40" type="text" value="%s" />', $this->_registry['option_name'], $key, $this->_registry['option_name'], $key, $this->_values[$key]);
		
		return $this->fnEcho($html);
	}
	
	public function fieldPassword($key)
	{
		$html = sprintf('<input id="%s_%s" name="%s[%s]" size="40" type="password" value="%s" />', $this->_registry['option_name'], $key, $this->_registry['option_name'], $key, $this->_values[$key]);
		
		return $this->fnEcho($html);
	}
	
	public function fieldTextarea($key)
	{
		$html = sprintf('<textarea id="%s_%s" name="%s[%s]" rows="7" cols="50" type="textarea">%s</textarea>', $this->_registry['option_name'], $key, $this->_registry['option_name'], $key, $this->_values[$key]);
		
		return $this->fnEcho($html);
	}
	
	public function fieldCheckbox($key)
	{
		$checked = $this->_values[$key] ? 'checked="checked"' : '';
		
		$html = sprintf('<input id="%s_%s" name="%s[%s]" type="checkbox" %s />', $this->_registry['option_name'], $key, $this->_registry['option_name'], $key, $checked);
		
		return $this->fnEcho($html);
	}
	
	public function fieldRadio($key, $options)
	{
		$html = '';
		
		foreach($options as $option => $label) {
			$checked = isset($this->_values[$key]) && $this->_values[$key] == $option ? 'checked="checked"' : '';
			
			$html .= sprintf('<label><input name="%s[%s]" type="radio" value="%s" %s />%s</label><br />', $this->_registry['option_name'], $key, $option, $checked, $label);
		}
		
		return $this->fnEcho($html);
	}
	
	public function fieldSelect($key, $options)
	{
		$html = sprintf('<select id="%s_%s" name="%s[%s]" style="width: 250px">', $this->_registry['option_name'], $key, $this->_registry['option_name'], $key);
		
		foreach($options as $option => $label) {
			$selected = isset($this->_values[$key]) && $this->_values[$key] == $option ? 'selected="selected"' : '';
			
			$html .= sprintf('<option value="%s" %s />%s</option>', $option, $selected, $label);
		}
		
		$html .= '</select>';
		
		return $this->fnEcho($html);
	}
	
	public function addPage()
	{
		add_options_page(
			$this->_registry['page_title'],
			$this->_registry['menu_title'],
			$this->_registry['capability'],
			$this->_registry['option_name'],
			array($this, 'page')
		);
	}
	
	public function page() {
	?>
		<div class="wrap">
			<div class="icon32" id="icon-options-general"><br></div>
			<h2><?php echo $this->_registry['page_title']; ?></h2>
			<form action="options.php" method="post">
			<?php settings_fields($this->_registry['option_name']); ?>
			<?php do_settings_sections($this->_registry['option_name']); ?>
			<p class="submit">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
			</p>
			</form>
		</div>
	<?php
	}
}
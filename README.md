Object-Oriented approach to the [WordPress Settings API](http://codex.wordpress.org/Settings_API)

Usage
=====
<pre>
$options = new WordPress_Settings(array(
  'option_group' => 'sample_options',
	'option_name' => 'sample_options',
	'page_title' => 'vBulletin Connect Options',
	'menu_title' => 'vBulletin Connect',
	'capability' => 'administrator'
));

$options
	->addSection(array('id' => 'general', 'title' => 'General Settings', 'callback' => ''))
	->addField(array('key' => 'forum_path', 'title' => 'Forum Path (Relative)'))
	->addSection(array('id' => 'authentication', 'title' => 'Authentication Settings', 'callback' => '<p>Authentication integration settings.</p>'))
	->addField(array('key' => 'authentication', 'title' => 'Enable vBulletin Login', 'type' => 'checkbox'))
	->addSection(array('id' => 'publishing', 'title' => 'Content Publish Settings', 'callback' => '<p>Posts are published to desired forum with the user of your choice.</p>'))
	->addField(array('key' => 'publishing', 'title' => 'Enable Content Publishing', 'type' => 'checkbox'))
	->addField(array('key' => 'publishing_forum_id', 'title' => 'Forum ID'))
	->addField(array('key' => 'publishing_user_id', 'title' => 'User ID'))
	->addField(array('key' => 'publishing_content_tpl', 'title' => 'Content Template', 'type' => 'textarea'))
;

add_action('admin_init', array($options, 'init'));
add_action('admin_menu', array($options, 'addPage'));
</pre>
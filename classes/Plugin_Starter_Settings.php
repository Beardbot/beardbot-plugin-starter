<?php

namespace Beardbot;

class Plugin_Starter_Settings extends Plugin_Starter {

	protected $option_name = 'beardbot_plugin_starter';

	protected $option_group = 'beardbot_plugin_starter_group';

	protected $options = [];

	function __construct() {
		add_action( 'admin_menu', [$this, 'add_admin_menu'] );
		add_action( 'admin_init', [$this, 'settings_init'] );

		$this->options = get_option( $this->option_name );
	}

	function get_option_name() {
		return $this->option_name;
	}


	// Register option page
	function add_admin_menu() {
		add_options_page( 'Beardbot Plugin Starter', 'Beardbot Plugin Starter', 'manage_options', $this->option_name, [$this, 'options_page_render'] );
	}

	// Initialise options page
	function settings_init() {

		if ( ! current_user_can( 'activate_plugins' ) ) return;

		register_setting( $this->option_group, $this->option_name );

		// Register a settings group
		add_settings_section(
			'beardbot_plugin_starter_section',
			__( 'Settings', $this->option_name ),
			'',
			$this->option_group
		);

		// Register a custom field
		add_settings_field(
			'beardbot_plugin_starter_custom_field',
			__( 'Custom Field', $this->option_name ),
			[$this, 'custom_field_render'],
			$this->option_group,
			'beardbot_plugin_starter_section'
		);
	}

	// Render custom field
	function custom_field_render() {
		$value = $this->options['custom_field'] ?? '';
	  ?>
		<input type="text" id="custom-field" name="beardbot_plugin_starter[custom_field]" value="<?php echo $value; ?>" placeholder="Custom field" size="30">
		<p class="description">
			Custom field help text
		</p>
		<?php
	}

	// Render options page
	function options_page_render() {
		?>
		<div class="wrap">
	    <h1>Beardbot Plugin Starter</h1>
	    <form action='options.php' method='post'>
				<?php
				settings_fields( $this->option_group );
				do_settings_sections( $this->option_group );
				submit_button();
				?>
			</form>
	  </div>
		<?php
	}
}

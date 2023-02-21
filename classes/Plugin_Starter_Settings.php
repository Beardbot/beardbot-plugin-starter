<?php

namespace Beardbot;

class Plugin_Starter_Settings extends Plugin_Starter {

	private const SECRET_KEY = 'M4btDbgy7`%XLXwf';

  private const SECRET_IV = 'DpPdUf5Apw$`T7KH';

	protected $key;

	protected $iv;

	protected $option_name = 'beardbot_plugin_starter';

	protected $option_group = 'beardbot_plugin_starter_group';

	protected $options = [];

	function __construct() {
		add_action('admin_menu', [$this, 'add_admin_menu']);
		add_action('admin_init', [$this, 'settings_init']);
		add_filter('pre_update_option', [$this, 'pre_update_option'], 10, 3);

		$this->key = hash('sha256', $this::SECRET_KEY);
		$this->iv = substr(hash('sha256', $this::SECRET_IV), 0, 16);

		$this->options = get_option($this->option_name);
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
			'beardbot_plugin_starter_github_api',
			__( 'GitHub API Token', $this->option_name ),
			[$this, 'github_api_render'],
			$this->option_group,
			'beardbot_plugin_starter_section'
		);
	}

	// Render custom field
	function github_api_render() {
		$value = $this->options['github_api'] ?? '';
		// Decrypt the API key before outputting
		$api_key = $this->decrypt_text($value);
	  ?>
		<input type="text" id="github-api" name="beardbot_plugin_starter[github_api]" value="<?php echo $api_key; ?>" placeholder="Enter your GitHub API Token" size="30">
		<p class="description">
			Create your <a href="https://github.com/settings/tokens" target="_blank" rel="noopener">GitHub API Token</a>
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

	function pre_update_option($value, $option, $old_value) {
		if($option === $this->option_name) {
			// Encrypt the API key before saving to the database
			$value['github_api'] = $this->encrypt_text($value['github_api']);
		}
		return $value;
	}
}

<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Beardbot Plugin Starter
 * Plugin URI:        https://github.com/Beardbot/beardbot-plugin-starter
 * Description:       A starter template for a custom WordPress plugin.
 * Version:           1.2
 * Author:            Beardbot
 * Author URI:        https://beardbot.com.au/
 * Requires at least: 5.9
 * Tested up to:      6.0
 * Requires PHP:      7.4
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       beardbot-plugin-starter
 *
 * Beardbot PLUGINs
 */

namespace Beardbot;

// Prevent direct access
defined( 'ABSPATH' ) || exit;

/**
 * Autoload classes
 */
require( 'autoload.php' );

class Plugin_Starter {

  protected $file;

  protected $path;
  
  protected $dir;

  protected $slug;
  
  protected $settings;

  protected $token; // GitHub API access token

  protected $repo = 'https://github.com/Beardbot/beardbot-plugin-starter'; // GitHub repository URL

  protected $branch = 'main'; // GitHub branch that contains the latest release

  function __construct() {

    $this->file = __FILE__;

    $this->path = plugin_dir_path( $this->file );

    $this->dir = plugins_url( '', $this->file );

    $this->slug = basename( __DIR__ );

    $this->load();
  }

  protected function load() {

    $this->settings = new Plugin_Starter_Settings();

    $this->token = $this->generate_token();

    $this->run_update_checker();

    add_action('init', [$this, 'init'], 10, 2);
  }

  function init() {
    // initialise plugin
  }

  private function generate_token() {
    // Get the plugin options
    $options = get_option($this->settings->option_name);
    // Decrypt the token value
    $this->token = $this->decrypt_text($options['github_api']);
  }

  private function run_update_checker() {
    // Initialise the update checker
    require 'plugin-update-checker/plugin-update-checker.php';
    $myUpdateChecker = \Puc_v4_Factory::buildUpdateChecker(
      $this->repo,
      $this->file,
      $this->slug
    );

    // Set the branch that contains the latest release
    $myUpdateChecker->setBranch($this->branch);

    // Set the GitHub Access Token
    $myUpdateChecker->setAuthentication($this->token);
  }

  function encrypt_text($text) {
		return base64_encode(openssl_encrypt($text, "AES-256-CBC", $this->settings->key, 0, $this->settings->iv));
	}

	function decrypt_text($text) {
		return openssl_decrypt(base64_decode($text), "AES-256-CBC" ,$this->settings->key, 0 ,$this->settings->iv);
	}
}

new Plugin_Starter();

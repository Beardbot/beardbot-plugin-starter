<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Beardbot Plugin Starter
 * Plugin URI:        https://github.com/Beardbot/beardbot-plugin-starter
 * Description:       A starter template for a custom WordPress plugin.
 * Version:           1.0.1
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

  protected $file = __FILE__;

  protected $path;
  
  protected $dir;

  protected $slug;

  protected $branch = 'main'; // GitHub branch that contains the latest release

  protected $token = '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'; // GitHub API access token

  protected $repo = 'https://github.com/Beardbot/beardbot-plugin-starter'; // GitHub repository URL

  function __construct() {

    $this->file = __FILE__;

    $this->path = plugin_dir_path( $this->file );

    $this->dir = plugins_url( '', $this->file );

    $this->slug = basename( __DIR__ );

    $this->load();
  }

  protected function load() {

    $this->run_update_checker();

    new Plugin_Starter_Settings();

    add_action('init', [$this, 'init'], 10, 2);
  }

  function init() {
    // initialise plugin
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
}

new Plugin_Starter();

<?php

namespace Beardbot;

class Plugin_Starter_Shortcodes extends Plugin_Starter {

  function __construct() {
    add_shortcode('custom_shortcode', [$this, 'render_custom_shortcode']);
  }

  function render_custom_shortcode($atts, $content = '', $name) {
    return '<p>This is a custom plugin shortcode for Beardbot Plugin Starter.</p>';
  }
}

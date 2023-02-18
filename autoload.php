<?php

spl_autoload_register( function ( $class ) {
  $plugin_path = plugin_dir_path( __FILE__ );
  $class_parts = explode('\\', $class);
  $class_name = end($class_parts);
  if( !file_exists( $plugin_path."/classes/{$class_name}.php" ) ) return;
  require_once( $plugin_path."/classes/{$class_name}.php" );
} );

<?php
/**
 * Plugin Name: GS Debug Tools (MU)
 * Description: Dev helper: ensures wp-content/debug.log exists and captures PHP fatals into it (even if WP_DEBUG is false).
 * Version: 0.2.3
 */
if (!defined('ABSPATH')) { exit; }

$gs_log = WP_CONTENT_DIR . '/debug.log';

/**
 * Ensure debug.log exists (so you can tail it immediately).
 * Note: If filesystem permissions prevent creation, we fall back to PHP default error log.
 */
if (!file_exists($gs_log)) {
  @file_put_contents($gs_log, '[' . date('c') . "] GS Debug Tools loaded\n", FILE_APPEND);
  @chmod($gs_log, 0640);
}

/**
 * Force PHP error logging into wp-content/debug.log (independent of WP_DEBUG constants).
 */
@ini_set('display_errors', '0');
@ini_set('log_errors', '1');
@ini_set('error_log', $gs_log);
@ini_set('ignore_repeated_errors', '0');
@ini_set('html_errors', '0');
error_reporting(E_ALL);

/**
 * Capture fatal shutdown errors into debug.log.
 */
register_shutdown_function(function() use ($gs_log) {
  $e = error_get_last();
  if (!$e) return;

  $fatal = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR];
  if (!in_array($e['type'], $fatal, true)) return;

  $msg = '[' . date('c') . '] FATAL ' . $e['type'] . ': ' . $e['message'] . ' in ' . $e['file'] . ':' . $e['line'] . "\n";
  @file_put_contents($gs_log, $msg, FILE_APPEND);
});

/**
 * Optional: enable WP debug logging if it's not defined in wp-config.php.
 * (If WP_DEBUG is defined there, we can't override constants.)
 */
if (!defined('WP_DEBUG')) define('WP_DEBUG', true);
if (!defined('WP_DEBUG_LOG')) define('WP_DEBUG_LOG', true);
if (!defined('WP_DEBUG_DISPLAY')) define('WP_DEBUG_DISPLAY', false);

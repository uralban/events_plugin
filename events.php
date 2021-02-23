<?php
/**
 * Plugin Name:       Events
 * Plugin URI:        https://github.com/uralban/events_plugin
 * Description:       Creating events.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Alex Simzikov
 * Author URI:        https://simzikov.com/
 * Text Domain:       events
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')){
    die;
}

require __DIR__ . '/includes/functions.php';

register_activation_hook(__FILE__,'events_create_db_table');
register_activation_hook(__FILE__,'events_create_eventList_page');
register_activation_hook(__FILE__,'events_create_singleEvent_page');

if ( is_admin() ) {
    require_once __DIR__ . '/admin/admin.php';
} else {
    require_once __DIR__ . '/public/public.php';
}



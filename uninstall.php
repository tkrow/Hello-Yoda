<?php
if (!defined('WP_UNINSTALL_PLUGIN')){
    die;
}

global $wpdb;
$wpdb->query("DROP TABLE {$wpdb->prefix}hello_yoda_quotes");
?>
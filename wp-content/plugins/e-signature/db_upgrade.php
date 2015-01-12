<?php
global $wpdb;

$table_prefix = $wpdb->prefix . "esign_";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

// UPgrade Documents Table
   
   $sql ="ALTER TABLE " . $table_prefix . "documents MODIFY COLUMN document_type ENUM('stand_alone','normal','esig_template') NOT NULL DEFAULT 'normal';";
 
  $wpdb->query($sql);

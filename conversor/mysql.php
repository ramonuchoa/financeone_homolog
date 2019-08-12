<?php

require '/home/admin/www/financeone/wp-load.php';

function conv_key_exists($key)
{
  if (!$key) return false;

  global $wpdb; 
  $key      = mysql_escape_string(trim($key));
  $user_id  =$wpdb->get_var("SELECT user_id FROM wp_usermeta WHERE meta_key = 'conv_key' AND meta_value = '{$key}'"); 
  
  return $user_id;
}
?>
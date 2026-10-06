<?php
$db_host="localhost"; $db_user="root"; $db_pass=""; $db_name="ricknetworks";
$conn = new mysqli($db_host,$db_user,$db_pass,$db_name);

// MPESA DARAJA - Put your keys from developer.safaricom.co.ke
$mpesa_consumer_key = "YOUR_CONSUMER_KEY";
$mpesa_consumer_secret = "YOUR_CONSUMER_SECRET";
$mpesa_shortcode = "174379"; // For testing. Change to yours when live
$mpesa_passkey = "YOUR_PASSKEY";
$mpesa_env = "sandbox"; // Change to "live" when going production

// MIKROTIK
$mikrotik_host = "192.168.88.1";
$mikrotik_user = "admin";
$mikrotik_pass = "your_mikrotik_password";
?>

<?php
require '../config.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
$phone = $data['phone'];
$amount = $data['amount'];
$package_id = $data['package_id'];

$phone = preg_replace('/^0/','254',$phone);
$phone = preg_replace('/^\+/','',$phone);

$conn->query("INSERT INTO payments (phone,amount,package_id,status) VALUES ('$phone',$amount,$package_id,'PENDING')");

// DARAJA AUTH
$url = ($mpesa_env=='live'?'https://api.safaricom.co.ke':'https://sandbox.safaricom.co.ke').'/oauth/v1/generate?grant_type=client_credentials';
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_HTTPHEADER, ['Authorization: Basic '.base64_encode($mpesa_consumer_key.':'.$mpesa_consumer_secret)]);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$token = json_decode(curl_exec($curl))->access_token;

// STK PUSH
$timestamp = date('YmdHis');
$password = base64_encode($mpesa_shortcode.$mpesa_passkey.$timestamp);
$curl = curl_init(($mpesa_env=='live'?'https://api.safaricom.co.ke':'https://sandbox.safaricom.co.ke').'/mpesa/stkpush/v1/processquery');
curl_setopt($curl, CURLOPT_HTTPHEADER, ['Authorization: Bearer '.$token,'Content-Type: application/json']);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
 'BusinessShortCode'=>$mpesa_shortcode,
 'Password'=>$password,
 'Timestamp'=>$timestamp,
 'TransactionType'=>'CustomerPayBillOnline',
 'Amount'=>$amount,
 'PartyA'=>$phone,
 'PartyB'=>$mpesa_shortcode,
 'PhoneNumber'=>$phone,
 'CallBackURL'=>'https://'. $_SERVER['HTTP_HOST'] .'/api/callback.php',
 'AccountReference'=>'RickNetworks',
 'TransactionDesc'=>'WiFi '.$amount.'bob'
]));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
echo $response;
?>

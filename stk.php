  <?php
// 1. Get phone from website
$phone = $_POST['phone'] ?? '';
$phone = str_replace([" ", "+"], "", $phone);
if(substr($phone,0,1) == "0"){
  $phone = "254".substr($phone,1);
}

// 2. YOUR KEYS - Sandbox
$consumerKey = "NVvMFh8A11b0cTeRGGmIns5GNLU4OorUC2IhLEPnZanA22yv";
$consumerSecret = "ffoG0Y1gUYVPxOAihBMDIOIG9H1oWJu1HAnXQ6krphwgcMuKBDprdvgPIM8VGSne";
$shortcode = "1752847";
$passkey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
//https://ricknetworks.com
$callbackUrl = "https://ricknetworks.com/callback.php";

// 3. Get Token
$credentials = base64_encode($consumerKey . ":" . $consumerSecret);
$ch = curl_init("https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials");
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Basic ".$credentials]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
curl_close($ch);
$token = json_decode($result)->access_token;

if(!$token){
  die("Failed to get token: " . $result);
}

// 4. STK Push
$timestamp = date("YmdHis");
$password = base64_encode($shortcode.$passkey.$timestamp);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest");
curl_setopt($curl, CURLOPT_HTTPHEADER, [
  "Authorization: Bearer ".$token,
  "Content-Type: application/json"
]);

$data = [
  "BusinessShortCode" => $shortcode,
  "Password" => $password,
  "Timestamp" => $timestamp,
  "TransactionType" => "CustomerPayBillOnline",
  "Amount" => 1,
  "PartyA" => $phone,
  "PartyB" => $shortcode,
  "PhoneNumber" => $phone,
  "CallBackURL" => $callbackUrl,
  "AccountReference" => "WebsitePay",
  "TransactionDesc" => "Payment"
];

curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($curl);
curl_close($curl);

echo $response;
?> 

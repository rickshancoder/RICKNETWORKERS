<?php
require '../config.php';
$data = file_get_contents('php://input');
file_put_contents('mpesa_log.txt', $data.PHP_EOL, FILE_APPEND);
$log = json_decode($data, true);
if(isset($log['Body']['stkCallback']['ResultCode']) && $log['Body']['stkCallback']['ResultCode']==0){
  $items=$log['Body']['stkCallback']['CallbackMetadata']['Item'];
  $amount=0;$code="";$phone="";
  foreach($items as $it){
    if($it['Name']=='Amount') $amount=$it['Value'];
    if($it['Name']=='MpesaReceiptNumber') $code=$it['Value'];
    if($it['Name']=='PhoneNumber') $phone=$it['Value'];
  }
  $pkg=$conn->query("SELECT * FROM packages WHERE price=$amount LIMIT 1")->fetch_assoc();
  if($pkg){
    $user="RK".rand(1000,9999); 
    $pass=rand(1000,9999);
    $exp=date('Y-m-d H:i:s', strtotime("+".$pkg['duration_minutes']." minutes"));
    $conn->query("INSERT INTO vouchers (username,password,package_id,phone,expiry) VALUES ('$user','$pass',{$pkg['id']},'$phone','$exp')");
    $conn->query("UPDATE payments SET status='PAID',mpesa_code='$code' WHERE phone='$phone' ORDER BY id DESC LIMIT 1");
  }
}
?>

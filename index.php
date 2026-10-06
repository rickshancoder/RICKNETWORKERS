<!DOCTYPE html>
<html>
<head>
<title>RickNetworks WiFi</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body{font-family:Arial;background:#0f172a;color:white;text-align:center;padding:15px}
.card{background:white;color:black;border-radius:15px;padding:15px;margin:10px 0;box-shadow:0 4px 10px rgba(0,0,0,0.3)}
.price{font-size:24px;font-weight:bold;color:#2563eb}
button{background:#2563eb;color:white;border:none;padding:12px 25px;border-radius:25px;width:100%;font-size:16px;font-weight:bold}
input{width:90%;padding:12px;margin:8px;border-radius:10px;border:1px solid #ccc}
.logo{font-size:28px;font-weight:bold;color:#38bdf8}
</style>
</head>
<body>
<div class="logo">⚡ RickNetworks</div>
<p>Fast & Affordable WiFi in Eldoret</p>
<input type="text" id="phone" placeholder="M-Pesa Phone e.g 0712345678">

<?php
require 'config.php';
$q = $conn->query("SELECT * FROM packages");
while($row=$q->fetch_assoc()){
echo "<div class='card'>
<h3>{$row['name']}</h3>
<div class='price'>{$row['price']} BOB</div>
<button onclick='buy({$row['price']},{$row['id']})'>BUY NOW - Lipa na M-Pesa</button>
</div>";
}
?>

<script>
function buy(amount, package_id){
 let phone=document.getElementById('phone').value;
 if(phone.length<10){alert('Weka namba sahihi ya M-Pesa');return;}
 alert('Tuma STK kwa '+phone+' - '+amount+' bob. Tafadhali ingiza PIN yako.');
 fetch('api/stk_push.php',{
  method:'POST',
  headers:{'Content-Type':'application/json'},
  body:JSON.stringify({phone:phone, amount:amount, package_id:package_id})
 }).then(r=>r.json()).then(d=>{alert(d.message||'Angalia simu yako kwa STK prompt');});
}
</script>
</body>
</html>

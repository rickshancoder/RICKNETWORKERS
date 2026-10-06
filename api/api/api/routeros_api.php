<?php
class RouterosAPI {
 var $socket; var $error_no; var $error_str;
 function connect($ip,$login,$password){
  $this->socket=@fsockopen($ip,8728,$this->error_no,$this->error_str,3);
  if(!$this->socket) return false;
  $this->write('/login'); $r=$this->read();
  $this->write('/login',false); $this->write('=name='.$login,false); $this->write('=response=00'.md5(chr(0).$password.pack('H*',$r[1])),false); $this->read();
  return true;
 }
 function write($cmd,$end=true){ $l=strlen($cmd); fwrite($this->socket,chr($l).$cmd); if($end) fwrite($this->socket,chr(0));}
 function read(){ $res=[]; while(true){$b=ord(fread($this->socket,1)); if($b==0) break; $d=''; for($i=0;$i<$b;$i++) $d.=fread($this->socket,1); $res[]=$d; } return $res;}
 function comm($com,$arr=[]){ $this->write($com,false); foreach($arr as $k=>$v){$this->write($k.'='.$v,false);} $this->write('',true); return $this->read();}
}
?>

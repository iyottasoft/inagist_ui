<div id="wait">
<? 
  global $domain;
  if(isset($disabled)) { ?>
  <big>oops, it seems like your account is not active yet. </big>
<? }else{ ?>
  <big>Thank you for registering with us</big><br/>
  please wait while we activate your account. This might take a few minutes.<br/>
<? } ?>
  Please check back in a few minutes.<br/>
  <br/>
  <a href="http://<?=$domain?>/all"><strong>in the meantime please browse the channels</strong></a>
</div>

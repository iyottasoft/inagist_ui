<div id="backbt"><a href="?"><big>&#171;</big> back to site</a></div>
<div id="loginBox">
<? if(isset($user)) { ?>
  logged in as <span class="user"><?=$user?></span> | <a href="?r=admin/logout">logout</a>
<? }else{ ?>
  <a href="?r=admin/login">login</a>
<? } ?>
</div>

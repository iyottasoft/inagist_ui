<div id="logo" class="left">
  <a title="In-A-Gist" href="http://<?=$domain?>/">
    <img src="<?=$cdn_base?>/images/logo.png" border="0" alt="inagist" />
  </a>  
</div>
<!-- div class="tagline">all the trends in the world. [<a title="About Us" href="http://<?=$domain?>/about">info</a>] </div-->
		<div id="searchwrapper">
		<form method="get" action="/search" onsubmit="return formSubmit();">
		<?php if ($user!='') {?>
			<input type="hidden" name="user" value="<?=$user?>"/>
		<?php }?>
		<?php if ($_REQUEST['q']!=''){?>	 
			<input type="text" class="searchbox" name="q" value="<?=strip_tags($_REQUEST['q'])?>" size="25" id="q"/>
		<?php }	else {?>
			<input type="text" class="searchbox" name="q" value="Search ..." size="25" id="q" onclick="document.forms[0].q.value ='';"/>
		<?php }?>	
			<input type="image" src="/netroy/images/search-lens.png" class="searchbox_submit" value="" />
		</form>
		</div>
		
<div id="login" class="right">
  <? if(isset($_SESSION['user_id'])) {
      $url = "http://$domain/".$_SESSION['user_id'];
      $name = (isset($_SESSION['name']))?$_SESSION['name']:$_SESSION['user_id'];
  ?>
    <div id="loggedin" class="right">
    <? if(isset($_SESSION['profile_image_url'])){ ?>
      <div class="right">
        <a href="<?=$url?>"><img src="<?=$_SESSION['profile_image_url']?>" /></a>
      </div>
    <? } ?>
      <div class="text right">
        logged in as
        <a href="<?=$url?>"><b><?=$name?></b></a>
        <br/>
        <!--<a href="http://twitter.com/<?=$_SESSION['user_id']?>" target="_blank" rel="nofollow">twitter</a>
        &#8226;-->
        <a href="http://<?=$domain?>/settings">settings</a> | <a href="http://<?=$domain?>/logout">logout</a>
      </div>
      <script type="text/javascript">window.loggedinuser="<?=$_SESSION['user_id']?>";</script>
    </div>
  <? }else{?>
    <div id="notloggedin">
      <a title="Login with Twitter" href="http://<?=$domain?>/login">
        <span id="loginBt"></span>
      </a>
    </div>
  <? } ?>
</div>
<div class="clear"> </div>

<script language="javascript">
function formSubmit() {
	if (document.getElementById("q").value!='')
    	window.location = "/search?q=" + encodeURIComponent(document.getElementById("q").value);
    return false;
}
</script>

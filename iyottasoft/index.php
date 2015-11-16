<?
$file = (isset($_GET['view']) && file_exists("partials/".$_GET['view'].".php"))?$_GET['view']:"home";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="language" content="en" />
  <meta name="keywords" content="twitter,inagist" />
  <meta name="description" content="" />
  <title>&#953;yotta Software Private Limited</title>
  <link rel="shortcut icon" href="/favicon.ico" />
  <link rel="stylesheet" type="text/css" href="/css/reset.css" />
  <link rel="stylesheet" type="text/css" href="/css/main.css" />
</head>
<body>
<div class="wrap">
  <div id="leftOuter">
    <div id="leftInner">
    <? include ("partials/$file.php"); ?>
    </div>
  </div>
  <div id="rightOuter">
    <div id="rightInner">
      <a href="/" class="logo">
        <img src="/images/iyottasoft.png" alt="iyotta software private limited" />
        <span class="georgia"><big>&#953;</big>yotta Software<br/>Pvt. Ltd.</span>
      </a>
      <hr class='hbr' />
      <div class="menu georgia">
        <a href="/">home</a>
        <hr class='hbr' />
        <a href="/team">team</a>
        <hr class='hbr' />
        <a href="mailto:jobs@iyottasoft.com">jobs</a>
        <hr class='hbr' />
        <a href="mailto:info@iyottasoft.com">contact</a>
      </div>
      <hr class='hbr' />
      <br/><br/><br/><br/>
      <div class='copy georgia'>
      &#169; 2010 &#953;yotta software private limited, india
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"> 
  /*var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16423946-1']);
  _gaq.push(['_trackPageview']); 
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();*/ 
</script> 
</body>
</html>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
  <meta name="language" content="en" />
  <meta name="description" content="inagist" /> 
  <title><?=$title?></title>
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/reset.css" />
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/admin.css" />
  <script type="text/javascript" src="<?=$cdn_base?>js/jquery.min.js"></script>
  <script type="text/javascript">window.api_base="<?=$api_base?>";window.baseurl="<?=$baseurl?>";</script>
</head>
<body>
<div id="wrapper">
  <div id="header"><div id="headerInner"><?=$header?></div></div>
  <? if(isset($notice)) {?>
  <div id="notice"><?=$notice?></div>
  <? } ?>
  <div id="container">
    <div id="contentwrapper">
      <div id="content"><?=$content?></div>
    </div>
    <div id="menuwrapper">
      <div id="menu"><?=$menu?></div>
    </div>
    <div class="clear"> </div>
  </div>
  <div id="scriptContainer">
    <script type="text/javascript" src="<?=$cdn_base?>js/admin.min.js"></script>
  </div>
  <div id="footer"><div id="footerInner"><?=$footer?></div></div>
</div>
</body>
</html>

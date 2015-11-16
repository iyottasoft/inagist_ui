<?
  $expireAge = 300;
	header("Cache-Control: max-age=$expireAge"); // HTTP/1.1
  header("Expires: " . gmdate("D, d M Y H:i:s", time() + $expireAge) . " GMT");
	header("Content-Type: application/xml; charset=UTF-8");
  include("../netroy/controllers/Controller.php");
  include("../netroy/controllers/DBController.php");
  include("../netroy/controllers/Portal.php");
	include("RSS.class.php");
	$rss = new RSS();
	$params['user']=$_GET["user"];
	$params['limit']=$_GET["limit"];
	$params['hours']=$_GET["hours"];
	ob_start();
	$rss->GetFeed($params);
	ob_end_flush();
?>

<?php
global $portal_tweets,$user_map,$portal_map;
$categoryPortal = array();
$languagArray = array(
"hi.inagist.com" => array("lang"=>"Hindi"),
"ta.inagist.com" => array("lang"=>"Tamil"),
"ml.inagist.com" => array("lang"=>"Malayalam"),
"kn.inagist.com" => array("lang"=>"Kannada"),
"te.inagist.com" => array("lang"=>"Telugu"),
"gu.inagist.com" => array("lang"=>"Gujarati"),
"ar.inagist.com" => array("lang"=>"Arabic"),
"bn.inagist.com" => array("lang"=>"Bangla"),
"bo.inagist.com" => array("lang"=>"Tibetan"),
"el.inagist.com" => array("lang"=>"Greek"),
"worldnews.inagist.com" => array("lang"=>"English"),
"he.inagist.com" => array("lang"=>"Hebrew"),
"jp.inagist.com" => array("lang"=>"Japanese"),
"ka.inagist.com" => array("lang"=>"Georgian"),
"ko.inagist.com" => array("lang"=>"Korean"),
"pa.inagist.com" => array("lang"=>"Punjabi"),
"ru.inagist.com" => array("lang"=>"Russian"),
"si.inagist.com" => array("lang"=>"Sinhala"),
"th.inagist.com" => array("lang"=>"Thai"),
"zh.inagist.com" => array("lang"=>"Chinese")
);
foreach ($languagArray as $domain => $language){
?>
		<div class='channeltitle' style="width:200px; font-size:16px; float:left; border:0px;"><a href='http://<?=$domain?>' style='color:#FFD800;'>
			<?=$language['lang']?></a>
		</div>
<?php 
}
?>
<br style="clear:both;"/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
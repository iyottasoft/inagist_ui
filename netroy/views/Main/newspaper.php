<?php
//echo "<pre>";
//print_r($usertrends);
$i=0;
foreach ($usertrends as $trend)	
{
	if ($i<15){
	?>
	<div class="trend_box round-corner">
		<div class="trend_title round-corner"><?=$trend["phrase"]?></div>
		<div class="trend_body round-corner" id="trendbody<?=$i?>">
			<?=Utils::linkify($trend['desc'])?>
		</div>
	</div>
	<?php 
	}
	$i++;
}
?>

<div style="clear:both;"></div>
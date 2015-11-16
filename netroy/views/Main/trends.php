<?php
  global $domain,$cdn_base;
  $trends_flag=false;
?>
	
	<div class="trending" style="border-bottom: 1px  solid #333; width:100%; float:left;" id="trends_tick">
  	<h2 style="color:#fff; font-size: 16px; float:left; padding:10px 10px 4px 0px; margin:0px; font-weight:normal;">Trends ></h2>
		<div id="scrollingText" class="inner" style="width:85%;">
			<ul style="left: 0px; margin-top:10px; padding-left:0px;">		
 			<?php
 			foreach ($trenddata as $domain => $usertrends){
 			foreach ($usertrends as $trend)	
			{
				$trends_flag=true;		 
				?>
					<li style="float:left; font-size:14px;"><a href="http://<?=$domain?>/trends?t=<?=urlencode(strtolower($trend["phrase"]))?>" target="_blank" ><?=$trend["phrase"]?></a></li>
			<?php 
			}}	 
 			?>
 			</ul> 
		</div>
	</div>
	<div class="clear"></div>
	<?php
	if (!$trends_flag)
	{
		?>
		<script language="javascript">
			document.getElementById("trendingstories").style.display='none';
		</script>
		<?php 
	} 
	?>	

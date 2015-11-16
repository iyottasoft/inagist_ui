<?php
  global $portal_map,$domain;
  
  if ($nolinks=='' || $nolinks==null)
  {
?>

  <script language="javascript">
  $(function(){
  	$("a.word").click(function(e) {  		
  		e.preventDefault();
  		$("a.selected").removeClass("selected");  		
  		$(this).addClass("selected");
  		$("#top-portal").html("");
  		$("#portalloading").show();  		
  		var user=$(this).attr('user');
  		<?php
  			if ($top){			 
  		?>
  		var params = {"r":"main/topportals","user":user};
  		<?php
  		 }else{ 
  		?>
  		var params = {"r":"main/allportals","user":user};
  		<?php }?>
		$.get(window.makeurl(params),function(data){
			$("#portalloading").hide();
			$("#top-portal").html(data);			  
		},"html");	
	    return false;
	});
  });
  </script>
<?php
  }
$style='';
if ($nolinks)
{
	$style = "style ='width:96%;'";
} 

?>  
<div id="tag-cloud" <?=$style?>>
<?
	foreach ($data['mycloud'] as $cloudArray) {
  		echo ' &nbsp; <a href="'.$cloudArray['url'].'" class="word size'.$cloudArray['range'].$cloudArray['selected'].'" user="'.$cloudArray['portaluser'].'">'.$cloudArray['word'].'</a> &nbsp;';
	}
?>
</div>
<div id="portalloading">
Loading<br/>
<img src="netroy/images/ajax-loader.gif"/></div>
<div id="top-portal">
<?=$data['defaultportal']?>
</div>
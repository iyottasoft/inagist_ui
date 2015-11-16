<?php

if(!empty($_POST))
{
	//print_r($_POST);
	$title=$_POST['wd_title'];
	$twtuser=$_POST['wd_user'];
	$width=$_POST['wd_width'];	
	$height=$_POST['wd_height'];
	$bgcolor=str_replace("#","",$_POST['wd_bg_color']);
	$tcolor=str_replace("#","",$_POST['wd_tweet_color']);
	$bcolor=str_replace("#","",$_POST['wd_border_color']);
	$lcolor=str_replace("#","",$_POST['wd_link_color']);	
	$keywords=$_POST['wd_keyw'];
	$customcss=$_POST['wd_css'];
	$twtcnt=$_POST['wd_twtcnt'];
	$enablereply=$_POST['wd_reply'];
}
else
{
	$title="Related Tweets";
	$twtuser="";
	$width="300";	
	$height="500";
	$bgcolor="FFFFFF";
	$tcolor="222222";
	$bcolor="eeeeee";
	$lcolor="0000AA";
	$keywords='';
	$customcss="";
	$twtcnt="20";
	$enablereply=0;
}
?>
<script src="/netroy/js/color.js" language="javascript" ></script>
<script src="/netroy/js/shCore.js" language="javascript" ></script>
<script src="/netroy/js/shBrushJScript.js" language="javascript" ></script>
<link href="/netroy/css/SyntaxHighlighter.css" rel="stylesheet" type="text/css" />

<div id="tweetlabel">
Create Related Tweets Widget
</div>

<form method="post" action="">    
<table width="100%" cellspacing="10" cellpadding="10" border="0" style="border-collapse: separate; border-spacing:10px; ">
	<tbody>
		<tr>
			<td width="250" valign="top"  class="fieldLabelRight">Widget Title </td>
			<td class="fieldInputStyle"><input type="text"
				style="text-align: left;" value="<?=$title?>" maxlength="255" size="25"
				name="wd_title"/> <br/>
			</td>
		</tr>
		<tr>
			<td width="250" valign="top"  class="fieldLabelRight">Twitter User </td>
			<td class="fieldInputStyle"><input type="text"
				style="text-align: left;" value="<?=$twtuser?>" maxlength="255" size="25"
				name="wd_user"/> <br/>
			</td>
		</tr>
		<tr>
			<td width="250" valign="top"  class="fieldLabelRight">Widget Width </td>
			<td class="fieldInputStyle"><input type="text"
				style="text-align: left;" value="<?=$width?>" maxlength="4" size="7"
				name="wd_width"/> <br/>
			</td>
		</tr>
		<tr>
			<td width="250" valign="top"  class="fieldLabelRight">Widget Height </td>
			<td class="fieldInputStyle"><input type="text"
				style="text-align: left;" value="<?=$height?>" maxlength="4" size="7"
				name="wd_height"/> <br/>
			</td>
		</tr>
		
		<tr>
			<td width="250" valign="top"  class="fieldLabelRight">Background
			Colour</td>
			<td class="fieldInputStyle"><input type="text"
				onblur="JavaScript:document.getElementById('wd_bg_color4').style.backgroundColor = this.value;"
				value="<?='#'.$bgcolor?>" maxlength="7" id="wd_bg_color" size="8"
				name="wd_bg_color"/>&nbsp;<input type="text"
				style="background-color: <?='#'.$bgcolor?>;" id="wd_bg_color4"
				name="wd_bg_color4" size="1"/>&nbsp;<a href="#"
				onclick="showColorGrid3('wd_bg_color','wd_bg_color4');" name="pick2"
				id="pick2">Pick</a> <br/>

			</td>
		</tr>
		<tr>
			<td width="250" valign="top"  class="fieldLabelRight">Tweet
			Colour</td>
			<td class="fieldInputStyle"><input type="text"
				onblur="JavaScript:document.getElementById('wd_tweet_color4').style.backgroundColor = this.value;"
				value="<?='#'.$tcolor?>" maxlength="7" id="wd_tweet_color" size="8"
				name="wd_tweet_color"/>&nbsp;<input type="text"
				style="background-color: <?='#'.$tcolor?>;" id="wd_tweet_color4"
				name="wd_tweet_color4" size="1"/>&nbsp;<a href="#"
				onclick="showColorGrid3('wd_tweet_color','wd_tweet_color4');"
				name="pick2" id="pick2">Pick</a> <br/>

			</td>
		</tr>
		<tr>
			<td width="250" valign="top"  class="fieldLabelRight">Border
			Colour</td>
			<td class="fieldInputStyle"><input type="text"
				onblur="JavaScript:document.getElementById('wd_border_color4').style.backgroundColor = this.value;"
				value="<?='#'.$bcolor?>" maxlength="7" id="wd_border_color" size="8"
				name="wd_border_color"/>&nbsp;<input type="text"
				style="background-color: <?='#'.$bcolor?>;" id="wd_border_color4"
				name="wd_border_color4" size="1"/>&nbsp;<a href="#"
				onclick="showColorGrid3('wd_border_color','wd_border_color4');"
				name="pick2" id="pick2">Pick</a> <br/>

			</td>
		</tr>
		<tr>
			<td width="250" valign="top"  class="fieldLabelRight">Links Colour
			</td>
			<td class="fieldInputStyle"><input type="text"
				onblur="JavaScript:document.getElementById('wd_link_color4').style.backgroundColor = this.value;"
				value="<?='#'.$lcolor?>" maxlength="7" id="wd_link_color" size="8"
				name="wd_link_color"/>&nbsp;<input type="text"
				style="background-color: <?='#'.$lcolor?>;" id="wd_link_color4"
				name="wd_link_color4" size="1"/>&nbsp;<a href="#"
				onclick="showColorGrid3('wd_link_color','wd_link_color4');"
				name="pick2" id="pick2">Pick</a> <br/>

			</td>
		</tr>
		<tr>
			<td width="250" valign="top"  class="fieldLabelRight">Keywords </td>
			<td class="fieldInputStyle"><input type="text"
				style="text-align: left;" value="<?=$keywords?>" maxlength="255" size="25"
				name="wd_keyw"/> <br/>
			</td>
		</tr>
		<tr>
			<td width="250" valign="top"  class="fieldLabelRight">Custom css file path</td>
			<td class="fieldInputStyle"><input type="text"
				style="text-align: left;" value="<?=$customcss?>" maxlength="255" size="25"
				name="wd_css"/> <br/>
			</td>
		</tr>
		<tr>
			<td width="250" valign="top"  class="fieldLabelRight">Maximum number of tweets to show </td>
			<td class="fieldInputStyle">
			<select name="wd_twtcnt">
			<?php
			for($i=1;$i<=50;++$i)
				echo "<option value='$i'" . ( $twtcnt == $i ? "selected='selected'" : '' ) . ">$i</option>"; 
			?>
			</select>
			<br/>
			</td>
		</tr>
		<tr>
			<td width="250" valign="top"  class="fieldLabelRight">Enable Replies </td>
			<td class="fieldInputStyle">
			<input type="checkbox" style="text-align: left;" <?php if ($enablereply) echo " checked=checked ";?>" value="1" name="wd_reply"/> <br/>
			</td>
		</tr>
		<tr>
			<td align="left" colspan="2">
			<input type="submit" class="stdButton" value="Preview" name="Submit"/> 
			<input type="submit" class="stdButton" value="Get Code" name="Submit"/>
			<div class="colorpicker301" id="colorpicker301" style="position: absolute; top:30%; left:40%;"></div>
			</td>
		</tr>

	</tbody>
</table>
            
            <?php            
            if ($_POST['Submit']=='Preview')
            {	
            	?>
            	<div class="widget_preview">
            	<script language="javascript">
					var inagist_ch_client = "<?=$twtuser?>";
					var inagist_ch_bgcolor = "<?=$bgcolor?>";
					var inagist_ch_tcolor = "<?=$tcolor?>";
					var inagist_ch_lcolor = "<?=$lcolor?>";
					var inagist_ch_bcolor = "<?=$bcolor?>";
					var inagist_ch_width="<?=$width?>";
					var inagist_ch_height="<?=$height?>";
					var inagist_ch_user="<?=$twtuser?>";
					var inagist_ch_title="<?=$title?>";
					var inagist_ch_keywords="<?=$keywords?>";
					var inagist_ch_css="<?=$customcss?>";
					var inagist_ch_reply="<?=$enablereply?>";
					var inagist_ch_twtcnt="<?=$twtcnt?>";
				</script>
				<script type="text/javascript" src="http://inagist.com/netroy/js/show_channel.js"></script>
				</div>
            	<?php 
            }
            else if ($_POST['Submit']=='Get Code'){
            	?>
            	<div class="widget_getcode">
            	<pre name="code" class="js">
				&lt;script language="javascript"&gt;
					var inagist_ch_client = "<?=$twtuser?>";
					var inagist_ch_bgcolor = "<?=$bgcolor?>";
					var inagist_ch_tcolor = "<?=$tcolor?>";
					var inagist_ch_lcolor = "<?=$lcolor?>";
					var inagist_ch_bcolor = "<?=$bcolor?>";
					var inagist_ch_width="<?=$width?>";
					var inagist_ch_height="<?=$height?>";
					var inagist_ch_user="<?=$twtuser?>";
					var inagist_ch_title="<?=$title?>";
					var inagist_ch_keywords="<?=$keywords?>";
					var inagist_ch_css="<?=$customcss?>";
					var inagist_ch_reply="<?=$enablereply?>";
					var inagist_ch_twtcnt="<?=$twtcnt?>";
				&lt;/script&gt;
				&lt;script type="text/javascript" src="http://inagist.com/netroy/js/show_channel.js"&gt;&lt;/script&gt;
				</div>
            	<?php
            } 
            ?>
</form>
<div class="clear"></div>
<script language="javascript">
dp.SyntaxHighlighter.ClipboardSwf = 'flash/clipboard.swf';
dp.SyntaxHighlighter.HighlightAll('code');
</script>

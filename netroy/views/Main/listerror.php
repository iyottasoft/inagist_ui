<div class="status round-corner" style="margin-top: 46px; margin-left: 10px;">
	<div class="error-text">	
		<?=$message?>
	</div>	

<?php if (isset($key)) { ?>
	<div class="error-text">	
		Try a <a href="http://search.twitter.com/search?q=<?=$key?>">Twitter search</a> for the same.
	</div>	
<?php } ?>

</div>


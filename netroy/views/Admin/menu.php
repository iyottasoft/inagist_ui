<h3>Admin Menu</h3>
<ul>
<? foreach($menu as $text=>$link) { ?>
  <li class="<?=$text?>"><a href="?r=<?=$link?>"><?=$text?></a></li>
<? } ?>
</ul>

<ul id="archive_selector">
<?
  $archives = array(
    "current"=>0,
    "last 12 hours"=>12,
    "Today"=>24,
    "This week"=>168
  );

  foreach($archives as $label=>$hours){ ?>
  <li>
    <a href="<?=$hours?>"><?=$label?></a>
  </li>
  <? } ?>
</ul>

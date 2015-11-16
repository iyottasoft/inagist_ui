var arTrending="";
for (var i=0;i<=1;i++){
	if (i==0)
		arTrending=$("#trends_tick");
	else
		arTrending=$("#channel_tick");
	if(arTrending.length>0)
	{
		arTrending.each(function(i){var $objList=arTrending.find("ul");
			$objList.animate({left:0},{duration:600});
			var $objInner=arTrending.find(".inner");
			var $arItems=$objList.find("li");
			var intItem=-1;
			var $nextItem,$intNext,tempId;
			var boolPause=false;
			function fGallery()
			{
				if(boolPause===true)
				{
					return false
				}
				clearInterval(tempId);
				if(intItem==($arItems.length-1))
				{
					intItem=0
				}
				else
				{
					intItem=intItem+1
				}
				$nextItem=$($arItems[intItem]);
				$intNext=$nextItem.width();
				$objList.animate({left:-$intNext+"px"},{duration:1000,complete:function(){$objList.css("left","0px");
																			$nextItem.remove().appendTo($objList);
																			tempId=setInterval(fGallery,3000)}})
			}
			$objInner.bind("mouseover",function(event){boolPause=true});
			$objInner.bind("mouseout",function(event){boolPause=false});
			tempId=setInterval(fGallery,3000)
		})
	}
}	

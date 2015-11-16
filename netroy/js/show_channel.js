(
 function()
{
	var siteurl = "http://inagist.com/?r=Main/widget";	  
	var url = "";
	if(inagist_ch_client)
	{
		url = escape(inagist_ch_client);
		siteurl += "&client=" + url;
	}
	siteurl += "&w=" + inagist_ch_width;
	siteurl += "&h=" + inagist_ch_height;
	siteurl += "&user=" + inagist_ch_user;

	if(undefined != window.inagist_ch_keywords)
		siteurl += "&keyw=" + inagist_ch_keywords;
	if(undefined != window.inagist_ch_google)
		siteurl += "&gaid=" + inagist_ch_google;
	if(undefined != window.inagist_ch_custom)
		siteurl += "&top_trends=" + inagist_ch_custom;
	if(undefined != window.inagist_ch_channel)
		siteurl += "&cuser=" + inagist_ch_channel;
	if(undefined != window.inagist_ch_css)
		siteurl += "&css=" + escape(inagist_ch_css);
	if(undefined != window.inagist_ch_reply)
		siteurl += "&reply=" + inagist_ch_reply;
	if(undefined != window.inagist_ch_twtcnt)
		siteurl += "&twtcnt=" + inagist_ch_twtcnt;
	if(undefined != window.inagist_ch_bgcolor)
		siteurl += "&tbgcolor=" + inagist_ch_bgcolor;
	if(undefined != window.inagist_ch_tcolor)
		siteurl += "&ttcolor=" + inagist_ch_tcolor;
	if(undefined != window.inagist_ch_lcolor)
		siteurl += "&tlcolor=" + inagist_ch_lcolor;
	if(undefined != window.inagist_ch_bcolor)
		siteurl += "&tbcolor=" + inagist_ch_bcolor;
	if(undefined != window.inagist_ch_limit)
		siteurl += "&limit=" + inagist_ch_limit;	
	if(undefined != window.inagist_ch_count)
		siteurl += "&count=" + inagist_ch_count;
	if(undefined != window.inagist_ch_title)
		siteurl += "&title=" + inagist_ch_title;
	if(undefined != window.inagist_ch_list)
		siteurl += "&list=" + inagist_ch_list;
	
	var temp_frame = 'iframe' + ' name="Inagist_IFrame" id="inagist_ch_Frame"' + ' src="'+ siteurl + '"' + ' width=' + inagist_ch_width + ' height='+inagist_ch_height + ' marginwidth="0" ' + ' marginheight="0" ' + ' vspace="0" ' + ' hspace="0" ' + ' frameborder=0 ' + ' allowtransparency="true" ' + ' scrolling="no" ';
	document.write('<' + temp_frame + '>');
	document.write('</'+ 'iframe'+'>');
})()


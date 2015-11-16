<?php
class Crawler
{

	var $ch; /// going to used to hold our cURL instance
	var $html; /// used to hold resultant html data
	var $binary; /// used for binary transfers
	var $url; /// used to hold the url to be downloaded


	function wSpider()
	{
		$this->html = "";
		$this->binary = 0;
		$this->url = "";
	}

	function fetchPage($url)
	{
		$this->url = $url;
		if (isset($this->url)) 
		{
			$this->ch = curl_init (); /// open a cURL instance
			curl_setopt ($this->ch, CURLOPT_RETURNTRANSFER, 1); // tell cURL to return the data
			curl_setopt ($this->ch, CURLOPT_URL, $this->url); /// set the URL to download
			curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true); /// Follow any redirects
			curl_setopt($this->ch, CURLOPT_USERAGENT, "InAGist URL Resolver (http://inagist.com)"); // setting User Agent
			curl_setopt($this->ch, CURLOPT_BINARYTRANSFER, $this->binary); /// tells cURL if the data is binary data or not
			$this->html = curl_exec($this->ch); // pulls the webpage from the internet
			curl_close ($this->ch); /// closes the connection
		}
	}
	
	function parse_array($beg_tag, $close_tag)
	{
		preg_match_all("($beg_tag.*$close_tag)siU", $this->html, $matching_data);
		return $matching_data[0];
	}
	
	function extract_summary()
	{
		$summary=array();
		$titlearr = $this->parse_array("<title>", "</title>");
		$summary['title']=strip_tags($titlearr[0]);
		$summary['url']=$this->url;
		
		preg_match_all('/<[\s]*meta[\s]*name="?' . '([^>"]*)"?[\s]*' . 'content="?([^>"]*)"?[\s]*[\/]?[\s]*>/si', $this->html, $out,PREG_PATTERN_ORDER);
		//preg_match_all('|<meta[^>]+name=\\"([^"]*)\\"[^>]+content="([^\\"]*)"[^>]+>|i',  $this->html, $out,PREG_PATTERN_ORDER);
	    for ($i=0;$i < count($out[1]);$i++) {
	        // loop through the meta data - add your own tags here if you need
	        if (strtolower($out[1][$i]) == "description") 
	        	$summary['description'] = strip_tags($out[2][$i]);
	    }
	    		
	    preg_match_all('|<link[^>]+rel=\\"([^"]*)\\"[^>]+href="([^\\"]*)"[^>]+>|i',  $this->html, $imageMatch,PREG_PATTERN_ORDER);
	    //echo "<pre>";print_r($imageMatch);exit;
		for ($i=0;$i < count($imageMatch[1]);$i++) {
	        if (strtolower($imageMatch[1][$i]) == "image_src") 
	        	$summary['image_src'] = $imageMatch[2][$i];
	    }
	    
		return $summary;
	}
	
}
/*$mySpider = new wSpider(); //// creates a new instance of the wSpider
$mySpider->fetchPage("http://tcrn.ch/i0fCNi"); /// fetches the home page of msn.com
//echo $mySpider->html; /// prints out the html to the scre
print_r($mySpider->extract_summary());*/
?>
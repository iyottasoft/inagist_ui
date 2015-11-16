<?php
header("Content-Type: text/plain");
$available_portals = array(
  "inagist.com", "worldnews.inagist.com", "worldbiz.inagist.com", "india.inagist.com", "tech.inagist.com",
  "uk.inagist.com", "france.inagist.com", "nyc.inagist.com", "bayarea.inagist.com", "pinoy.inagist.com",
  "linux.inagist.com", "itgist.inagist.com", "london.inagist.com", "geek.inagist.com", 
  "australia.inagist.com", "malaysia.inagist.com", "indonesia.inagist.com", "singapore.inagist.com");
if (in_array($_SERVER["SERVER_NAME"], $available_portals)) {
?>
User-Agent: Googlebot
Disallow: /api/ 
Disallow: /js/
Disallow: /status/ 
Disallow: /search 
Disallow: /a$
Disallow: /a?

User-agent: Mediapartners-Google
Disallow: /api/

User-agent: msnbot
Disallow: /api/
Disallow: /js/
Disallow: /status/ 
Disallow: /search 

User-agent: Yahoo! Slurp
Disallow: /api/
Disallow: /js/
Disallow: /status/ 
Disallow: /search 

User-Agent: ia_archiver
Disallow: /api/
Disallow: /js/
Disallow: /status/ 
Disallow: /search 

User-Agent: Rome Client
Disallow: /api/
Disallow: /js/
Disallow: /status/ 
Disallow: /search 

User-Agent: Baiduspider
Disallow: /api/
Disallow: /js/
Disallow: /status/ 
Disallow: /search 

Sitemap: http://inagist.com/netroy/sitemap.xml
<?php } ?>

User-Agent: *
Disallow: /

<?

DEFINE ('DB_HOST', 'mysql_master');
DEFINE ('DB_USER', 'inagist');
DEFINE ('DB_PASSWORD', 'inagist');
DEFINE ('DB_NAME', 'inagist');

// Make the connnection and then select the database.
$dbc = @mysql_connect (DB_HOST, DB_USER, DB_PASSWORD) OR die ('Could not connect to MySQL: ' . mysql_error() );
mysql_select_db (DB_NAME) OR die ('Could not select the database: ' . mysql_error() );

?>

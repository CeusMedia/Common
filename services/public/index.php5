<?php
$pointPath	= "public/";
$classPath	= "";

//  --  DO NOT CHANGE BELOW THIS LINE  --  //
try
{
	$pointPath	.= preg_match( '@/$@', $pointPath ) ? "" : "/";
	require_once( "../../../useClasses.php5" );
	import( 'de.ceus-media.net.service.Server' );
	import( '-classes.Public' );
	import( '-classes.Files' );
	new Net_Service_Server( "", $pointPath );
}
catch( Exception $e )
{
	die( $e->getMessage() );
}
?>

<?php
function import( $classKey )
{
	if( preg_match( "/ceus-media/", $classKey ) )
		return;
	$fileName	= str_replace( ".", "/", $classKey ).".php5";
	require_once( $fileName );
}
$func	= create_function( '$className','
	$path		= dirname( __FILE__ )."/src/";
	$fileName	= str_replace( "_","/", $className );
	$fileName	= $path.$fileName.".php5";
	if( !file_exists( $fileName ) )
		return FALSE;
	require_once( $fileName );' );
spl_autoload_register( $func );
new UI_DevOutput;
?>
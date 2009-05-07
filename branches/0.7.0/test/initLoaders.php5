<?php
//  --  LOADERS  --  //
/*function __autoload( $classname )
{
	$filename	= "classes/".str_replace( "_", "/", $classname ).".php5";
	require_once( $filename );
}
*/
function import( $class )
{
	while( preg_match( "@^-@", $class ) )
		$class	= preg_replace( "@^(-)*-@", "\\1../", $class ); 
    $filename   = str_replace( ".", "/", $class ).".php5";
	if( !file_exists( $filename ) )
		throw new InvalidArgumentException( 'Class "'.$filename.'" is not existing.' );
    include_once( $filename );
}
import( 'de.ceus-media.ui.DevOutput' );
import( 'de.ceus-media.throwException' );
require_once( 'Tests/MockAntiProtection.php' );
?>
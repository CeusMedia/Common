<?php
/**
 *	Java-like import of Classes.
 *	@access			public
 *	@param			string		$classPath		Java-formated URI of Class
 *	@return			void
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.06.2005
 *	@version		0.5
 */
function import( $classPath )
{
	$fileName = str_replace( IMPORT_SEPARATOR, FOLDER_SEPARATOR, $classPath).".".PHP_EXTENSION;
	while( preg_match( "@^-@", $fileName ) )
		$fileName	= preg_replace( "@^(-*)-@", "\\1../", $fileName ); 
	try
	{
		if( !in_array( $fileName, $GLOBALS['imported'] ) )
		{
			if( !include_once( $fileName ) )
				throw new Exception( 'Class "'.$fileName.'" could not be loaded.' );
			$GLOBALS['imported'][] = $fileName;
		}
	}
	catch( Exception $e )
	{	
		$t	= $e->getTrace();
		$message = $t[0]['file']."[".$t[0]['line']."] ".$e->getMessage();
		die( $message );
	}
}

$GLOBALS['imported'] = array ();

if( !defined ("CLASS_CACHE" ) )
	define ("CLASS_CACHE", false );
if( !defined ( "PATH_SEPARATOR" ) )
	define ( "PATH_SEPARATOR", ( substr( PHP_OS, 0, 3 ) == 'WIN' ) ? ";" : ":" );
if( !defined ("FOLDER_SEPARATOR" ) )
	define( "FOLDER_SEPARATOR", "/" );
if( !defined( "IMPORT_SEPARATOR" ) )
	define( "IMPORT_SEPARATOR", "." );
if( !defined( "PHP_EXTENSION" ) )
	define( "PHP_EXTENSION", "php5" );

?>

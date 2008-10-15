<?php
/**
 *	Java-like import of Classes.
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.06.2005
 *	@version		0.6
 */
$constants	= array(																			//  Array of Constants to be set
	'CM_CLASSES_IMPORT_SEPARATOR'	=> '.',														//  Separator in Import Path (default '.')
	'CM_CLASSES_PHP_EXTENSION'		=> 'php5',													//  Extension of PHP Classes (default 'php5')
	'CM_CLASSES_VERSION'			=> '0.6',													//  Version of Class Container
	'CM_CLASSES_EXTENSIONS'			=> TRUE,													//  Flag: use Exception Extension
);
foreach( $constants as $key => $value )															//  iterate Constants
	if( !defined( $key ) )																		//  if not defined, yet
		define( $key, $value );																	//  define Constant

/**
 *	Tries to loads PHP Classes in current Path or from Class Container with Java-like Path Syntax.
 *	@param		string		$classPath			Class Path, e.g 'xml.dom.Parser' or 'your.path.in.project.YourClass'
 *	@param		bool		$supressWarnings	Flag:  supress Warnings on including Class File
 *	@return		bool
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		16.06.2005
 *	@version	0.6.3
 */
function import( $classPath, $supressWarnings = FALSE )
{
	$fileName	= str_replace( CM_CLASSES_IMPORT_SEPARATOR, "/", $classPath);
	$fileName	.= ".".CM_CLASSES_PHP_EXTENSION;
	while( preg_match( "@^-@", $fileName ) )
		$fileName	= preg_replace( "@^(-*)-@", "\\1../", $fileName ); 
	try
	{
		if( !in_array( $fileName, $GLOBALS['imported'] ) )
		{
			if( $supressWarnings )
			{
				$errorLevel	= error_reporting();
				error_reporting( 5 );
			}
			if( !include_once $fileName )
				throw new Exception( 'Class "'.$fileName.'" is not existing or invalid.' );
			if( $supressWarnings )
				error_reporting( $errorLevel );
			$GLOBALS['imported'][$classPath] = $fileName;
		}
	}
	catch( Exception $e )
	{
		$trace		= $e->getTrace();
		$message	= $trace[0]['file']."[".$trace[0]['line']."]: ".$e->getMessage();
		die( $message );
	}
	return TRUE;
}
$GLOBALS['imported'] = array ();
import( 'de.ceus-media.throwException' );
?>

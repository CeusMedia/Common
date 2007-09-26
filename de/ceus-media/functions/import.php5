<?php
/**
 *	Java-like import of Classes.
 *	@access		public
 *	@param		string	classPath		Java-formated URI of Class / Folder with classes
 *	@param		bool		report		Switches reports
 *	@return		void
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		16.06.2005
 *	@version		0.4
 */
function ___getFileFromClass( $classname )
{
	$filename = str_replace(IMPORT_SEPARATOR, FOLDER_SEPARATOR, $classname) . ".php5";
	while( preg_match( "@^-@", $filename ) )
		$filename	= preg_replace( "@^(-)*-@", "\\1../", $filename ); 
	return $filename;	
}

function import( $classpath )
{
	$report	= defined( "CM_CLASS_REPORT" ) && CM_CLASS_REPORT;
	$filename	= ___getFileFromClass( $classpath );
	if( !in_array( $filename, $GLOBALS['imported'] ) )
	{
		$i = new ClassImport;
		$i->import( $classpath, $report );
	}
	else if( $report )
		echo "<br>functions/import: <b>skipping:</b> ".$classpath;
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

?>

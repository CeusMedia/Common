<?php
/**
 *	Script to append class path to PHP include path.
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */

define( 'CM_CLASS_PATH',	"/var/www/cmClasses/0.6/" );									//  EDIT THIS PATH FOR YOUR INSTALLATION

ini_set( 'include_path', CM_CLASS_PATH.PATH_SEPARATOR.ini_get( "include_path" ) );
if( !@include_once( "import.php5" ) )
	die( 'Path to "cmClasses" is not set correctly in Script "'.__FILE__.'".' );
?>
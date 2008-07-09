<?php
/**
 *	Script to build Documentations.
 *	@package	uvl
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		30.11.2005
 *	@version		1.0
 */
error_reporting( 1 );
set_time_limit( 0 );
$path_current	= getCwd();
$path_phpdoc	= "c:\\.mirror\\packages\\";
chdir( $path_phpdoc );

$title	= " -ti cmClasses";
$source	= " -d c:/.mirror/cmClasses/de/ceus-media/";
$target	= " -t c:/.mirror/docs/cmClasses/api/";
$output	= " -o HTML:Smarty:PHP";
$ignore	= " -i doc.php,mirror.lnk,Tests\";
$quiet	= "";
passthru( "php phpdocumentor-1.3.2/phpdoc".$source.$target.$title.$output.$ignore.$quiet );
chdir( $path_current );
?>

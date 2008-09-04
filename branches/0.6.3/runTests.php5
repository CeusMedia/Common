<?php
if( ini_get( 'xdebug.profiler_enable' ) )							//  XDebug Profiler is enabled
	ini_set( 'xdebug.profiler_enable', "0" );						//  disable Profiler
$config		= parse_ini_file( "cmClasses.ini", TRUE );
$command	= "@phpunit";
foreach( $config['unittest.options'] as $key => $value )
	$command	.= " --".$key." ".$value;
$command	.= " Tests_AllTests";
passthru( $command );
?>
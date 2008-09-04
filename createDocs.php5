<?php
echo "
cmClasses Documentation Creator
Syntax: php createDocs.php5 <quite>\n
creating documentations will take a while...";

if( ini_get( 'xdebug.profiler_enable' ) )							//  XDebug Profiler is enabled
	ini_set( 'xdebug.profiler_enable', "0" );						//  disable Profiler
$configFile	= "cmClasses.ini";										//  Configuration File
$reportFile	= "createDocs.report";									//  phpDocumentor Report File
$command	= "phpdoc -c ".$configFile;								//  Shell Command to run phpDocumentor
if( isset( $argv[1] ) && $argv[1] )									//  Quite Mode is activated
{
	$command	.= " > ".$reportFile;								//  redirect Output into Report File
	@unlink( $reportFile );											//  remove old Report File
}
passthru( $command );												//  run phpDocumentor
?>

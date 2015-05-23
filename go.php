#!/usr/bin/env php
<?php
$url		= "http://cmtools.googlecode.com/svn/trunk/Go/";
$fileApp	= "Go/Application.php";
$cwd		= getCwd();											//  current working directory
chDir( dirname( realpath( __FILE__ ) ) );						//  tool working directory
if( !file_exists( $fileApp ) )
{
	remark( "Go not installed." );
	@exec( "svn", $results, $return );
	if( $return !== 1 )
		throw new RuntimeException( "SVN seems to be not installed." );
	passthru( "svn co ".$url." Go" );
}
require_once( $fileApp );

date_default_timezone_set( "Europe/Berlin" );					//  set default time zone

new Go_Application;
chDir( $cwd );
?>

<?php
$url		= "http://cmtools.googlecode.com/svn/branches/0.7.0/Go/";
$fileApp	= "Go/Application.php5";
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
new Go_Application;
chDir( $cwd );
?>

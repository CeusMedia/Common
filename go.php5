<?php
$url		= "http://cmclasses.googlecode.com/svn/trunk/.go/";
$fileApp	= ".go/Application.php5";
$cwd		= getCwd();											//  current working directory
chDir( dirname( realpath( __FILE__ ) );							//  tool working directory
if( !file_exists( $fileApp ) )
{
	exec( "svn", $return );
	if( !$return )
		throw new RuntimeException( "SVN seems to be not installed." );
	passthru( "svn co ".$url." .go" );
}
require_once( $fileApp );
new Go_Application;
chDir( $cwd );
?>
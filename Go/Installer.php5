<?php
require_once( dirname( __FILE__ ).'/Library.php5' );
require_once( dirname( __FILE__ ).'/Configurator.php5' );
class Go_Installer
{
	public function __construct( $arguments )
	{
		Go_Library::ensureSvnSupport();
		
		$repos	= "http://cmclasses.googlecode.com/svn/";
		if( count( $arguments ) < 2 )
			throw new InvalidArgumentException( 'No install path set.' );
		if( count( $arguments ) < 1 )
			throw new InvalidArgumentException( 'No version to install set.' );

		$command	= "co ".$repos.$arguments[0]." ".$arguments[1];
		Go_Library::runSvn( $command );
		chDir( $arguments[1] );
		new Go_Configurator();
	}
}
?>
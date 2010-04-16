<?php
require_once dirname( __FILE__ ).'/Library.php5';
class Go_Updater
{
	public function __construct( $arguments )
	{
		$revision	= $arguments ? $arguments[0] : "";
		$path		= dirname( dirname( realpath( __FILE__ ) ) );
		$command	= "update ".$path." ".$revision;

		Go_Library::ensureSvnSupport();
		Go_Library::runSvn( $command );
	}
}
?>

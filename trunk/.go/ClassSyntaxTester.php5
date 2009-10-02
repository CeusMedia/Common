<?php
require_once( dirname( __FILE__ )."/Library.php5" );
class Go_ClassSyntaxTester
{
	public function __construct( $arguments )
	{
		remark( "GO Class File Test\n" );

		$path	= dirname( dirname( __FILE__ ) )."/de/";
		$data	= Go_Library::listClasses( $path );
		
		remark( "found ".$data['count']." class files\n" );
		Go_Library::testSyntax( $data['files'] );
		Go_Library::testImports( $data['files'] );
		Go_Library::showMemoryUsage();
	}
}
?>
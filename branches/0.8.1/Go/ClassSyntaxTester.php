<?php
require_once dirname( __FILE__ ).'/Library.php';
class Go_ClassSyntaxTester
{
	public function __construct( $arguments )
	{
		remark( "GO Class File Syntax Test\n" );

		$path	= dirname( dirname( __FILE__ ) )."/src";
		$data	= Go_Library::listClasses( $path );
		
		remark( "found ".$data['count']." class files\n" );
		Go_Library::testSyntax( $data['files'] );
	}
}
?>

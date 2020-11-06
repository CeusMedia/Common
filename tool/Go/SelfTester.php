<?php
require_once dirname( __FILE__ ).'/Library.php';
class Go_SelfTester
{
	public function __construct( $arguments )
	{
		$lib	= new Go_Library();

		remark( "testing GO itself\n" );

		$path	= dirname( __FILE__ );
		$data	= Go_Library::listClasses( $path );

		Go_Library::testSyntax( $data['files'] );
		Go_Library::testImports( $data['files'] );

		remark( "" );
	}
}

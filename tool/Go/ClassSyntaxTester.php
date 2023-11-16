<?php

namespace CeusMedia\CommonTool\Go;

/**
 *	@deorecated use php test/syntax.php instead!
 */
require_once dirname( __FILE__ ).'/Library.php';
class ClassSyntaxTester
{
	public function __construct( $arguments )
	{
		remark( "GO Class File Syntax Test\n" );

		$path	= dirname( __FILE__, 3 )."/src";
		$data	= Library::listClasses( $path );

		remark( "found ".$data['count']." class files\n" );
		Library::testSyntax( $data['files'] );
	}
}

<?php

namespace CeusMedia\CommonTool\Go;

use CeusMedia\Common\Alg\Randomizer;

/**
 *	@deorecated not needed once Go is gone
 */
require_once dirname( __FILE__ ).'/Library.php';

class SelfTester
{
	public function __construct( $arguments )
	{
		$lib	= new Library();

		remark( "testing GO itself\n" );

		$path	= dirname( __FILE__ );
		$data	= Library::listClasses( $path );

		Library::testSyntax( $data['files'] );
		Library::testImports( $data['files'] );

		remark( "create random numbers with 3 digits: " );

		$randomizer	= new Randomizer();
		$randomizer->useLarges	= FALSE;
		$randomizer->useSmalls	= FALSE;
		$randomizer->useSigns	= FALSE;
		$c	= $randomizer->get( 1 ) + 2;
		for( $i=0; $i<$c; $i++ )
			print( $randomizer->get( 3 )." " );

		remark( "" );
	}
}

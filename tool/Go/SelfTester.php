<?php
/**
 *	@deorecated not needed once Go is gone
 */
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

		remark( "create random numbers with 3 digits: " );

		$randomizer	= new Alg_Randomizer();
		$randomizer->useLarges	= FALSE;
		$randomizer->useSmalls	= FALSE;
		$randomizer->useSigns	= FALSE;
		$c	= $randomizer->get( 1 ) + 2;
		for( $i=0; $i<$c; $i++ )
			print( $randomizer->get( 3 )." " );

		remark( "roman date: " );
		$year	= date( "Y" );
		print( $year. " is ".Alg_Math_RomanNumbers::convertToRoman( $year ) );

		remark( "" );
	}
}

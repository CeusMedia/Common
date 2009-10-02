<?php
require_once( ".go/Library.php5" );
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
		import( 'de.ceus-media.alg.Randomizer' );
		import( 'de.ceus-media.math.RomanNumbers' );

		$randomizer	= new Alg_Randomizer();
		$randomizer->useLarges	= FALSE;
		$randomizer->useSmalls	= FALSE;
		$randomizer->useSigns	= FALSE;
		$c	= $randomizer->get( 1 ) + 2;
		for( $i=0; $i<$c; $i++ )
			print( $randomizer->get( 3 )." " );

		remark( "roman date: " );
		$year	= date( "Y" );
		print( $year. " is ".Math_RomanNumbers::convertToRoman( $year ) );

		remark( "" );
	}
}
?>
<?php
class Go_ClassUnitTestCreator
{
	public function __construct( $arguments )
	{
		require_once( "useClasses.php5" );
		import( 'de.ceus-media.alg.TestCaseCreator' );
		import( 'de.ceus-media.console.ArgumentParser' );
		import( 'de.ceus-media.ui.DevOutput' );

		$force	= in_array( "-f", $arguments ) || in_array( "--force", $arguments );
		if( in_array( "-f", $arguments ) )
			unset( $arguments[array_search( "-f", $arguments )] );
		if( in_array( "--force", $arguments ) )
			unset( $arguments[array_search( "--force", $arguments )] );
		if( !$arguments )
			throw new InvalidArgumentException( 'No class name given to create test class for.' );
		$class	= array_shift( $arguments );
		$creator	= new Alg_TestCaseCreator();
		$creator->createForFile( $class, $force );
		remark( 'Created test class "Tests_'.$class.'Test".' );
	}
}
?>
<?php
class LibraryUnitTestCreator
{
	public function __construct( array $arguments )
	{
		require_once dirname( __DIR__ ).'/autoload.php';

		$force	= in_array( "-f", $arguments ) || in_array( "--force", $arguments );
		if( in_array( "-f", $arguments ) )
			unset( $arguments[array_search( "-f", $arguments )] );
		if( in_array( "--force", $arguments ) )
			unset( $arguments[array_search( "--force", $arguments )] );
		if( 0 === count( $arguments ) )
			throw new InvalidArgumentException( 'No class name given to create test class for.' );
		$class		= array_shift( $arguments );
		$creator	= new FS_File_PHP_Test_Creator();
		$creator->createForFile( $class, $force );
		remark( 'Created test class "Test_'.$class.'Test".'."\n" );
	}
}
try{
	new LibraryUnitTestCreator( array_slice( $_SERVER['argv'], 1 ) );
} catch( Exception $e ){
	die( 'Error: '.$e->getMessage().PHP_EOL );
}

<?php
class Go_UnitTestCreator
{
	public function __construct( $arguments )
	{
		require_once dirname( dirname( __FILE__ ) ).'/autoload.php';

		$force	= in_array( "-f", $arguments ) || in_array( "--force", $arguments );
		if( in_array( "-f", $arguments ) )
			unset( $arguments[array_search( "-f", $arguments )] );
		if( in_array( "--force", $arguments ) )
			unset( $arguments[array_search( "--force", $arguments )] );
		if( !$arguments )
			throw new InvalidArgumentException( 'No class name given to create test class for.' );
		$class	= array_shift( $arguments );
		$creator	= new File_PHP_Test_Creator();
		$creator->createForFile( $class, $force );
		remark( 'Created test class "Test_'.$class.'Test".'."\n" );
	}
}
?>

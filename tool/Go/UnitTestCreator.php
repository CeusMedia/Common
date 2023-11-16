<?php

namespace CeusMedia\CommonTool\Go;

use CeusMedia\Common\FS\File\PHP\Test\Creator as TestCreator;
use InvalidArgumentException;

/**
 *	@deorecated use php test/create.php [CLASS_NAME] instead!
 */
class UnitTestCreator
{
	public function __construct( $arguments )
	{
		require_once dirname( __DIR__ ).'/autoload.php';

		$force	= in_array( "-f", $arguments ) || in_array( "--force", $arguments );
		if( in_array( "-f", $arguments ) )
			unset( $arguments[array_search( "-f", $arguments )] );
		if( in_array( "--force", $arguments ) )
			unset( $arguments[array_search( "--force", $arguments )] );
		if( !$arguments )
			throw new InvalidArgumentException( 'No class name given to create test class for.' );
		$class	= array_shift( $arguments );
		$creator	= new TestCreator();
		$creator->createForFile( $class, $force );
		remark( 'Created test class "Test_'.$class.'Test".'."\n" );
	}
}

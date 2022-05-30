<?php

namespace CeusMedia\Common\Test;

use CeusMedia\Common\Alg\Obj\Factory as ObjectFactory;
use CeusMedia\Common\UI\Template;
use InvalidArgumentException;

class MockAntiProtection
{
	public static function createMockClass( $className )
	{
		if( class_exists( '\\CeusMedia\\Common\\Test\\'.$className.'MockAntiProtection' ) )
			return;
		if( !class_exists( $className ) )
			throw new InvalidArgumentException( 'Class "'.$className.'" is not existing' );
		$codeFile	= dirname( __FILE__ ).'/MockAntiProtection.tmpl';
		$codeClass	= Template::render( $codeFile, array( 'className' => $className ) );
		eval( $codeClass );
	}

	public static function getInstance( $className )
	{
		self::createMockClass( $className );
		$mockClass	= "\\CeusMedia\\Common\\Test\\".$className."MockAntiProtection";
		$arguments	= array_slice( func_get_args(), 1 );
		$mockObject	= ObjectFactory::createObject( $mockClass, $arguments );
		return $mockObject;
	}
}

<?php

namespace CeusMedia\Common\Test;

use CeusMedia\Common\Alg\Obj\Factory as ObjectFactory;
use CeusMedia\Common\UI\Template;
use InvalidArgumentException;

class MockAntiProtection
{
	public static function createMockClass( $originalClass )
	{
		if( !class_exists( $originalClass ) )
			throw new InvalidArgumentException( 'Class "'.$originalClass.'" is not existing' );

		$mockClass	= str_replace( 'Common\\', 'Common\\Test\\', $originalClass );
		$mockClass	= $mockClass.'MockAntiProtection';
		if( class_exists( '\\'.$mockClass ) )
			return;
		$parts		= explode( '\\', $mockClass );
		$className	= array_pop( $parts );
		$namespace	= implode( '\\', $parts );

		$codeFile	= __DIR__.'/MockAntiProtection.tmpl';
		$codeClass	= Template::render( $codeFile, [
			'namespace' => $namespace,
			'originalClassName' => '\\'.$originalClass,
			'mockClassName' => $className,
		] );
//		xmp( $codeClass );die;
		eval( $codeClass );
	}

	public static function getInstance( $originalClass )
	{
		self::createMockClass( $originalClass );

		$mockClass	= str_replace( 'Common\\', 'Common\\Test\\', $originalClass );
		$mockClass	= '\\'.$mockClass.'MockAntiProtection';
		$arguments	= array_slice( func_get_args(), 1 );
		return ObjectFactory::createObject( $mockClass, $arguments );
	}
}

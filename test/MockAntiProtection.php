<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest;

use CeusMedia\Common\Alg\Obj\Factory as ObjectFactory;
use CeusMedia\Common\UI\Template;
use InvalidArgumentException;

class MockAntiProtection
{
	public static function createMockClass( string $originalClass ): void
	{
		if( !class_exists( $originalClass ) )
			throw new InvalidArgumentException( 'Class "'.$originalClass.'" is not existing' );

		$mockClass	= self::getMockClassFromOriginalClass( $originalClass );
		if( class_exists( $mockClass ) )
			return;

		$parts		= explode( '\\', $mockClass );
		$className	= array_pop( $parts );
		$namespace	= implode( '\\', $parts );

		$codeClass	= self::renderClassTemplate( $namespace, $originalClass, $className );
//		xmp( $codeClass );die;
		eval( $codeClass );
	}

	public static function getInstance( string $originalClass ): object
	{
		self::createMockClass( $originalClass );
		$mockClass	= self::getMockClassFromOriginalClass( $originalClass );
		$arguments	= array_slice( func_get_args(), 1 );
		return ObjectFactory::createObject( $mockClass, $arguments );
	}

	protected static function getMockClassFromOriginalClass( string $originalClass ): string
	{
		return '\\'.str_replace( 'Common\\', 'CommonTest\\', $originalClass ).'MockAntiProtection';
	}

	protected static function renderClassTemplate( string $namespace, string $originalClass, string $className ): string
	{
		$string	= file_get_contents( __DIR__.'/MockAntiProtection.tmpl' );
		return str_replace(
			['<%namespace%>', '<%originalClassName%>', '<%mockClassName%>'],
			[ltrim( $namespace, '\\' ), '\\'.$originalClass, $className],
			$string
		);
	}
}

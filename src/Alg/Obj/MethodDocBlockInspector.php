<?php
declare(strict_types=1);

namespace CeusMedia\Common\Alg\Obj;

use ReflectionException;
use ReflectionMethod;

class MethodDocBlockInspector
{
	/**
	 *	Uses reflection on class method to get doc block lines.
	 *	@param		string		$className		Class name
	 *	@param		string		$methodName		Method name
	 *	@return		array
	 *	@throws		ReflectionException
	 */
	public static function get( string $className, string $methodName ): array
	{
		$method		= new ReflectionMethod( $className, $methodName );
		$block		= $method->getDocComment();
		if( FALSE === $block || '' === trim( $block ) )
			return [];

		/** @var array $lines */
		$lines	= preg_split( '/\r?\n/', trim( $block ) );
		if( 0 === count( $lines ) )
			return [];

		$list	= [];
		foreach( $lines as $line ){
			if( preg_match( '/^\s*\/\*{1,2}/', $line ) )
				continue;
			if( preg_match( '/^\s*\*{1,2}\//', $line ) )
				continue;
			$list[]	= preg_replace( "/^\s*\*\s?/", '', $line );
		}
		return $list;
	}
}
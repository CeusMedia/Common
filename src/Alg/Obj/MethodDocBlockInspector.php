<?php

declare(strict_types=1);

/**
 *	Uses reflection on class method to get doc block lines.
 *
 *	Copyright (c) 2010-2025 Christian Würker (ceusmedia.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Object
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Obj;

use ReflectionException;
use ReflectionMethod;

/**
 *	CUses reflection on class method to get doc block lines.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Object
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
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
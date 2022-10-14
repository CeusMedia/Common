<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	Base Class for other Classes to inherit.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *
 */

namespace CeusMedia\Common\ADT;

/**
 *	Base Class for other Classes to inherit.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Object_
{
	/**
	 *	Returns Class Name of current Object.
	 *	@access		public
	 *	@return		string
	 */
	public function getClass(): string
	{
		return get_class( $this );
	}

	/**
	 *	Returns all Methods of current Object.
	 *	@access		public
	 *	@return		array
	 */
	public function getMethods(): array
	{
		return get_class_methods( $this );
	}

	/**
	 *	Returns an Array with Information about current Object.
	 *	@access		public
	 *	@return		array
	 */
	public function getObjectInfo(): array
	{
		return [
			'name'		=> $this->getClass(),
			'parent'	=> $this->getParent(),
			'methods'	=> $this->getMethods(),
			'vars'		=> $this->getVars(),
		];
	}

	/**
	 *	Returns Class Name of Parent Class of current Object.
	 *	@access		public
	 *	@return		string
	 */
	public function getParent(): ?string
	{
		/** @var string|FALSE $parentClass */
		$parentClass = get_parent_class( $this );
		if( $parentClass !== FALSE )
			return $parentClass;
		return NULL;

//		if( static::class == self::class ){
//			/** @var string $parentClass */
//			$parentClass = get_parent_class( $this );
//			if( $parentClass !== self::class )
//				return $parentClass;
//		}
//		return NULL;
	}

	/**
	 *	Returns all Members of current Object.
	 *	@access		public
	 *	@return		array
	 */
	public function getVars(): array
	{
		return get_object_vars( $this );
	}

	/**
	 *	Indicates whether a Method is existing within current Object.
	 *	@access		public
	 *	@param		string		$methodName		Name of Method to check
	 *	@param		bool		$callableOnly	Flag: also check if Method is callable
	 *	@return		bool
	 */
	public function hasMethod( string $methodName, bool $callableOnly = TRUE ): bool
	{
		if( $callableOnly )
			return method_exists( $this, $methodName ) && is_callable( [$this, $methodName] );
		else
			return method_exists( $this, $methodName );
	}

	/**
	 *	Indicates whether current Object is an Instance or Inheritance of a Class.
	 *	@access		public
	 *	@param		string		$className		Name of Class
	 *	@return		bool
	 */
	public function isInstanceOf( string $className ): bool
	{
		return is_a( $this, $className );
	}

	/**
	 *	Indicates whether current Object has a Class as one of its parent Classes.
	 *	@access		public
	 *	@param		string		$className		Name of parent Class
	 *	@return		bool
	 */
	public function isSubclassOf( string $className ): bool
	{
		return is_subclass_of( $this, $className );
	}

	/**
	 *	Returns a String Representation of current Object.
	 *	@access		public
	 *	@return		string
	 */
	public function serialize(): string
	{
		return serialize( $this );
	}
}

<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *
 *	Copyright (c) 2011-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_ADT_CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT\CSS;

use Exception;
use OutOfRangeException;

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Rule
{
	public $selector	= '';

	public $properties	= [];

	public function __construct( string $selector, array $properties = [] )
	{
		$this->setSelector( $selector );
		foreach( $properties as $property )
			$this->setProperty( $property );
	}

	public function getProperties(): array
	{
		return $this->properties;
	}

	public function getPropertyByIndex( int $index ): Property
	{
		if( !isset( $this->properties[$index] ) )
			throw new OutOfRangeException( 'Invalid property index' );
		return $this->properties[$index];
	}

	public function getPropertyByKey( string $key ): Property
	{
		foreach( $this->properties as $property )
			if( $key == $property->getKey() )
				return $property;
		throw new OutOfRangeException( 'Invalid property key' );
	}

	public function getSelector(): string
	{
		return $this->selector;
	}

	public function hasProperty( Property $property ): bool
	{
		return $this->hasPropertyByKey( $property->getKey() );
	}

	public function hasPropertyByKey( string $key ): bool
	{
		foreach( $this->properties as $property )
			if( $key == $property->getKey() )
				return TRUE;
		return FALSE;
	}

	public function removeProperty( Property $property ): bool
	{
		return $this->removePropertyByKey( $property->getKey() );
	}

	public function removePropertyByKey( string $key ): bool
	{
		foreach( $this->properties as $nr => $property ){
			if( $key == $property->getKey() ){
				unset( $this->properties[$nr] );
				return TRUE;
			}
		}
		return FALSE;
	}

	public function setProperty( Property $property ): bool
	{
		return $this->setPropertyByKey( $property->getKey(), $property->getValue() );
	}

	public function setPropertyByKey( string $key, $value = NULL ): bool
	{
		if( $value === NULL || !strlen( $value ) )
			return $this->removePropertyByKey( $key );
		try{
			$property	= $this->getPropertyByKey( $key );
			$property->setValue( $value );
		}
		catch( Exception $e ){
			$this->properties[]	= new Property( $key, $value );
		}
		return TRUE;
	}

	public function setSelector( string $selector ): self
	{
		$this->selector	= $selector;
		return $this;
	}
}

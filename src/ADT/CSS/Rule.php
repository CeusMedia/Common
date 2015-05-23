<?php
/**
 *	...
 *
 *	Copyright (c) 2011-2012 Christian Würker (ceusmedia.com)
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
 *	@category		cmClasses
 *	@package		ADT.CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.5
 *	@version		$Id$
 */
/**
 *	...
 *
 *	@category		cmClasses
 *	@package		ADT.CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.5
 *	@version		$Id$
 */
class ADT_CSS_Rule{

	public $selector	= NULL;

	public $properties	= array();

	public function __construct( $selector, $properties = array() ){
		$this->setSelector( $selector );
		foreach( $properties as $property )
			$this->setProperty( $property );
	}

	public function getPropertyByIndex( $index ){
		if( !isset( $this->properties[$index] ) )
			throw new OutOfRangeException( 'Invalid property index' );
		return $this->properties[$index];
	}

	public function getPropertyByKey( $key ){
		foreach( $this->properties as $nr => $property )
			if( $key == $property->getKey() )
				return $property;
		return NULL;
	}

	public function getProperties(){
		return $this->properties;
	}

	public function getSelector(){
		return $this->selector;
	}

	public function hasProperty( ADT_CSS_Property $property ){
		return $this->hasPropertyByKey( $property->getKey() );
	}

	public function hasPropertyByKey( $key ){
		foreach( $this->properties as $nr => $property )
			if( $key == $property->getKey() )
				return TRUE;
		return FALSE;
	}

	public function removeProperty( ADT_CSS_Property $property ){
		return $this->removePropertyByKey( $property->getKey() );
	}

	public function removePropertyByKey( $key ){
		foreach( $this->properties as $nr => $property ){
			if( $key == $property->getKey() ){
				unset( $this->properties[$nr] );
				return TRUE;
			}
		}
		return FALSE;
	}

	public function setProperty( ADT_CSS_Property $property ){
		return $this->setPropertyByKey( $property->getKey(), $property->getValue() );				//
	}

	public function setPropertyByKey( $key, $value = NULL ){
		if( $value === NULL || !strlen( $value ) )
			return $this->removePropertyByKey( $key );
		$property	= $this->getPropertyByKey( $key );
		if( $property )
			return $property->setValue( $value );
		$this->properties[]	= new ADT_CSS_Property( $key, $value );
		return TRUE;
	}

	public function setSelector( $selector ){
		$this->selector	= $selector;
	}
}
?>

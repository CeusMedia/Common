<?php
/**
 *	...
 *
 *	Copyright (c) 2010 Christian Würker (ceus-media.de)
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
 *	@package		Net.Mail.Header
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.1
 *	@version		$Id$
 */
/**
 *	...
 *
 *	@category		cmClasses
 *	@package		Net.Mail.Header
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.1
 *	@version		$Id$
 */
class Net_Mail_Header_Section
{
	protected $fields	= array();

	public function addField( Net_Mail_Header_Field $field )
	{
		return $this->setField( $field, FALSE );
	}

	public function addFieldPair( $name, $value )
	{
		$field	= new Net_Mail_Header_Field( $name, $value );
		$this->addField( $field );
	}

	public function addFields( $fields )
	{
		foreach( $fields as $field )
			$this->addField( $field );
	}

	public function getField( $name )
	{
		if( !$this->hasField( $name ) )
			return NULL;
		return array_shift( $this->getFieldsByName( $name ) );
	}

	public function getFields()
	{
		$list	= array();
		foreach( $this->fields as $name => $fields )
				if( count( $fields ) )
					foreach( $fields as $field )
						$list[]	= $field;
		return $list;
	}

	public function getFieldsByName( $name )
	{
		$name	= strtolower( $name );
		if( isset( $this->fields[$name] ) )
			return $this->fields[$name];
		return array();
	}

	public function hasField( $name )
	{
		$name	= strtolower( $name );
		if( isset( $this->fields[$name] ) )
			return (bool) count( $this->fields[$name] );
	}

	public function setField( Net_Mail_Header_Field $field, $emptyBefore = TRUE )
	{
		$name	= strtolower( $field->getName() );
		if( $emptyBefore || !array_key_exists( $name, $this->fields ) )
			$this->fields[$name]	= array();
		$this->fields[$name][]	= $field;
	}

	public function setFieldPair( $name, $value, $emptyBefore = TRUE )
	{
		return $this->setField( new Net_Mail_Header_Field( $name, $value ), $emptyBefore );
	}

	public function toArray()
	{
		$list	= array();
		foreach( $this->fields as $name => $fields )
			foreach( $fields as $field )
				$list[]	= $field->toString();
		return $list;
	}

	public function toString()
	{
		$list	= $this->toArray();
		$list	= implode( "\r\n", $list )."\r\n";
		return $list;
	}
}
?>
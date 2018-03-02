<?php
/**
 *	Container for Mail Header Fields.
 *
 *	Copyright (c) 2010-2018 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Net_Mail_Header
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
/**
 *	Container for Mail Header Fields.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_Mail_Header
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://tools.ietf.org/html/rfc5322#section-3.3
 *	@deprecated		Please use CeusMedia/Mail (https://packagist.org/packages/ceus-media/mail) instead
 *	@todo			remove in version 1.0
 */
class Net_Mail_Header_Section
{
	protected $fields			= array();

	public function __construct(){
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.9' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/Mail"',
			 	'https://packagist.org/packages/ceus-media/mail'
			) );
	}

	/**
	 *	Add a Header Field Object.
	 *	@access		public
	 *	@param		Net_Mail_Header_Field	$field		Header Field Object
	 *	@return		void
	 */
	public function addField( Net_Mail_Header_Field $field )
	{
		return $this->setField( $field, FALSE );
	}

	/**
	 *	Add a Header Field by pair.
	 *	@access		public
	 *	@param		string		$name		Header Field Name
	 *	@param		string		$value		Header Field Value
	 *	@return		void
	 */
	public function addFieldPair( $name, $value )
	{
		$field	= new Net_Mail_Header_Field( $name, $value );
		$this->addField( $field );
	}

	/**
	 *	Add several Header Field Objects.
	 *	@access		public
	 *	@param		array		$fields		List of Header Field Objects
	 *	@return		void
	 */
	public function addFields( $fields )
	{
		foreach( $fields as $field )
			$this->addField( $field );
	}

	/**
	 *	Returns a Header Field Object by its Name if set.
	 *	@access		public
	 *	@param		string		$name		Header Field Name
	 *	@return		Net_Mail_Header_Field
	 */
	public function getField( $name )
	{
		if( !$this->hasField( $name ) )
			return NULL;
		$values	= $this->getFieldsByName( $name );
		return array_shift( $values );
	}

	/**
	 *	Returns a List of all set Header Field Objects.
	 *	@access		public
	 *	@return		array
	 */
	public function getFields()
	{
		$list	= array();
		foreach( $this->fields as $name => $fields )
				if( count( $fields ) )
					foreach( $fields as $field )
						$list[]	= $field;
		return $list;
	}

	/**
	 *	Returns a List of set Header Field Objects for a Header Field Name.
	 *	@access		public
	 *	@param		string		$name		Header Field Name
	 *	@return		array
	 */
	public function getFieldsByName( $name )
	{
		$name	= strtolower( $name );
		if( isset( $this->fields[$name] ) )
			return $this->fields[$name];
		return array();
	}

	/**
	 *	Indicates whether a Header Field is set by its Name.
	 *	@access		public
	 *	@param		string		$name		Header Field Name
	 *	@return		boolean
	 */
	public function hasField( $name )
	{
		$name	= strtolower( $name );
		if( isset( $this->fields[$name] ) )
			return (bool) count( $this->fields[$name] );
	}

	/**
	 *	Sets an Header Field Object.
	 *	@access		public
	 *	@param		Net_Mail_Header_Field	$field			Header Field Object to set
	 *	@param		boolean					$emptyBefore	Flag: TRUE - set | FALSE - append
	 *	@return		void
	 */
	public function setField( Net_Mail_Header_Field $field, $emptyBefore = TRUE )
	{
		$name	= strtolower( $field->getName() );
		if( $emptyBefore || !array_key_exists( $name, $this->fields ) )
			$this->fields[$name]	= array();
		$this->fields[$name][]	= $field;
	}

	/**
	 *	Sets an Header Field by Name and Value.
	 *	@access		public
	 *	@param		string		$name			Header Field Name
	 *	@param		string		$value			Header Field Value
	 *	@param		boolean		$emptyBefore	Flag: TRUE - set | FALSE - append
	 *	@return		void
	 */
	public function setFieldPair( $name, $value, $emptyBefore = TRUE )
	{
		return $this->setField( new Net_Mail_Header_Field( $name, $value ), $emptyBefore );
	}

	/**
	 *	Returns all Header Fields as List.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray()
	{
		$list	= array();
		foreach( $this->fields as $name => $fields )
			foreach( $fields as $field )
				$list[]	= $field->toString();
		return $list;
	}

	/**
	 *	Returns all set Header Fields as String.
	 *	@access		public
	 *	@return		string
	 */
	public function toString()
	{
		$list	= $this->toArray();
		if( $list )
			return implode( Net_Mail::$delimiter, $list )/*.Net_Mail::$delimiter*/;
		return "";
	}
}
?>

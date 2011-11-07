<?php
/**
 *	Class Member Data Class.
 *
 *	Copyright (c) 2008-2010 Christian Würker (ceus-media.de)
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
 *	@package		ADT.PHP
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@version		$Id$
 *	@since			0.3
 */
/**
 *	Class Member Data Class.
 *	@category		cmClasses
 *	@package		ADT.PHP
 *	@extends		ADT_PHP_Variable
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@version		$Id$
 *	@since			0.3
 */
class ADT_PHP_Member extends ADT_PHP_Variable
{
	protected $access		= NULL;
	protected $static		= FALSE;
	protected $default		= NULL;
	
	/**
	 *	Returns member access.
	 *	@access		public
	 *	@return		string
	 */
	public function getAccess()
	{
		return $this->access;
	}

	/**
	 *	Returns member default value.
	 *	@access		public
	 *	@return		string
	 */
	public function getDefault()
	{
		return $this->default;
	}

	/**
	 *	Returns parent Class or Interface Data Object.
	 *	@access		public
	 *	@return		ADT_PHP_Interface	Parent Class or Interface Data Object
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 *	Indicates whether member is static.
	 *	@access		public
	 *	@return		bool
	 */
	public function isStatic()
	{
		return (bool) $this->static;
	}

	public function merge( ADT_PHP_Variable $member )
	{
		parent::merge( $member );
#		remark( 'merging member: '.$member->getName() );
		if( $this->name != $member->getName() )
			throw new Exception( 'Not mergable' );
		if( $member->getAccess() )
			$this->setAccess( $member->getAccess() );
		if( $member->getDefault() )
			$this->setDefault( $member->getDefault() );
		if( $member->isStatic() )
			$this->setAbstract( $member->isStatic() );
	}
	
	/**
	 *	Sets member access.
	 *	@access		public
	 *	@param		string			$string			Member access
	 *	@return		void
	 */
	public function setAccess( $string = 'public' )
	{
		$this->access	= $string;
	}
	
	/**
	 *	Sets member default value.
	 *	@access		public
	 *	@param		string			$string			Member default value
	 *	@return		void
	 */
	public function setDefault( $string )
	{
		$this->default	= $string;
	}
	
	/**
	 *	Sets parent Class or Interface Data Object.
	 *	@access		public
	 *	@param		ADT_PHP_Class	$parent			Parent Class Data Object
	 *	@return		void
	 */
	public function setParent( $parent )
	{
		if( !( $parent instanceof ADT_PHP_Class ) )
			throw new InvalidArgumentException( 'Parent must be of ADT_PHP_Class' );
		$this->parent	= $parent;
	}

	/**
	 *	Sets if member is static.
	 *	@access		public
	 *	@param		bool			$isStatic		Flag: member is static
	 *	@return		void
	 */
	public function setStatic( $isStatic = TRUE )
	{
		$this->static	= (bool) $isStatic;
	}
}
?>
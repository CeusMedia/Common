<?php
/**
 *	Class Method Data Class.
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
import( 'de.ceus-media.adt.php.Function' );
/**
 *	Class Method Data Class.
 *	@category		cmClasses
 *	@package		ADT.PHP
 *	@extends		ADT_PHP_Function
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@version		$Id$
 *	@since			0.3
 */
class ADT_PHP_Method extends ADT_PHP_Function
{
	protected $abstract		= FALSE;
	protected $access		= NULL;
	protected $final		= FALSE;
	protected $static		= FALSE;

	/**
	 *	Returns method access.
	 *	@access		public
	 *	@return		string
	 */
	public function getAccess( )
	{
		return $this->access;
	}
	
	public function getParent()
	{
		if( !is_object( $this->parent ) )
			throw new RuntimeException( 'Method has no related class. Parser Error' );
		return parent::getParent();
	}
	
	/**
	 *	Indicates whether method is abstract.
	 *	@access		public
	 *	@return		bool
	 */
	public function isAbstract()
	{
		return (bool) $this->abstract;
	}
	
	/**
	 *	Indicates whether method is final.
	 *	@access		public
	 *	@return		bool
	 */
	public function isFinal()
	{
		return (bool) $this->final;
	}
	
	/**
	 *	Indicates whether method is static.
	 *	@access		public
	 *	@return		bool
	 */
	public function isStatic()
	{
		return (bool) $this->static;
	}
	
	public function merge( ADT_PHP_Method $method )
	{
		if( $this->name != $method->getName() )
			throw new Exception( 'Not mergable' );
		if( $method->getAccess() )
			$this->setAccess( $method->getAccess() );
		if( $method->getParent() )
			$this->setParent( $method->getParent() );
		if( $method->isAbstract() )
			$this->setAbstract( $method->isAbstract() );
		if( $method->isFinal() )
			$this->setFinal( $method->isFinal() );
		if( $method->isStatic() )
			$this->setStatic( $method->isStatic() );
	}

	/**
	 *	Sets if method is abstract.
	 *	@access		public
	 *	@param		bool		$isAbstract		Flag: method is abstract
	 *	@return		void
	 */
	public function setAbstract( $isAbstract = TRUE )
	{
		$this->abstract	= (bool) $isAbstract;
	}
	
	/**
	 *	Sets method access.
	 *	@access		public
	 *	@param		string		$string			Method access
	 *	@return		void
	 */
	public function setAccess( $string = 'public' )
	{
		$this->access	= $string;
	}
	
	/**
	 *	Sets if method is final.
	 *	@access		public
	 *	@param		bool		$isFinal		Flag: method is final
	 *	@return		void
	 */
	public function setFinal( $isFinal = TRUE )
	{
		$this->final	= (bool) $isFinal;
	}
	
	public function setParent( ADT_PHP_Interface $classOrInterface )
	{
		$this->parent	= $classOrInterface;
	}
	
	/**
	 *	Sets if method is static.
	 *	@access		public
	 *	@param		bool		$isStatic		Flag: method is static
	 *	@return		void
	 */
	public function setStatic( $isStatic = TRUE )
	{
		$this->static	= (bool) $isStatic;
	}
}
?>
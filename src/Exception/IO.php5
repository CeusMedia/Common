<?php
/**
 *	Exception for Input/Output Errors.
 *	Stores an additional resource and is serializable.
 *
 *	Copyright (c) 2007-2011 Christian Würker (ceusmedia.com)
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
 *	@package		Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2011 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.03.2007
 *	@version		$Id$
 */
/**
 *	Exception for Input/Output Errors.
 *	Stores an additional resource and is serializable.
 *	@category		cmClasses
 *	@package		Exception
 *	@extends		Exception_Runtime
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2011 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.03.2007
 *	@version		$Id$
 */
class Exception_IO extends Exception_Runtime
{
	/**	@var		string		$resource		Name or Value of resource which was not fully accessible */
	protected $resource			= "";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$message		Error Message
	 *	@param		integer		$code			Error Code
	 *	@param		string		$sourceUri		Name or Value of unavailable Resource
	 *	@return		void
	 */
	public function __construct( $message = null, $code = 0, $resource = "" )
	{
		parent::__construct( $message, $code );
		$this->resource	= $resource;
	}
	
	/**
	 *	Returns Name of Source which was not fully accessible.
	 *	@access		public
	 *	@return		string
	 */
	public function getResource()
	{
		return $this->resource;	
	}

	/**
	 *	Returns serial of exception.
	 *	@access		public
	 *	@return		string
	 */
	public function serialize()
	{
		return serialize( array( $this->message, $this->code, $this->file, $this->line, $this->resource ) );
	}

	/**
	 *	Recreates an exception from its serial.
	 *	@access		public
	 *	@param		string		$serial			Serial string of an serialized exception
	 *	@return		void
	 */
	public function unserialize( $serial )
	{
		list( $this->message, $this->code, $this->file, $this->line, $this->resource ) = unserialize( $serial );
	}
}
?>

<?php
/**
 *	Exception for Input/Output Errors.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.03.2007
 *	@version		$Id$
 */
/**
 *	Exception for Input/Output Errors.
 *	@category		cmClasses
 *	@package		Exception
 *	@extends		RuntimeException
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.03.2007
 *	@version		$Id$
 */
class Exception_IO extends RuntimeException
{
	/**	@var		string		$sourceUri		Name of Source which was not fully accessible */
	private $sourceUri			= "";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$message		Error Message
	 *	@param		string		$sourceUri		Error Code
	 *	@return		void
	 */
	public function __construct( $message = null, $sourceUri = "" )
	{
		parent::__construct( $message );
		$this->sourceUri	= $sourceUri;
	}
	
	/**
	 *	Returns Name of Source which was not fully accessible.
	 *	@access		public
	 *	@return		string
	 */
	public function getSourceUri()
	{
		return $this->sourceUri;	
	}
}
?>

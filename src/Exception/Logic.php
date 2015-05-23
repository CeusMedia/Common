<?php
/**
 *	Exception for Logic Errors, which can be serialized e.G. for NetServices.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			22.02.2007
 *	@version		$Id$
 */
/**
 *	Exception for Logic Errors, which can be serialized e.G. for NetServices.
 *	@category		cmClasses
 *	@package		Exception
 *	@extends		Exception_Runtime
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			22.02.2007
 *	@version		$Id$
 */
class Exception_Logic extends Exception_Runtime
{
	/**	@var		string		$subject		Subject on which this logic exception happened */
	protected $subject	= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$message		Exception message
	 *	@param		string		$subject		Subject on which this logic exception happened
	 *	@param		integer		$code			Exception code
	 *	@return		void
	 */
	public function __construct( $message, $subject = "", $code = 0 )
	{
		parent::__construct( $message, $code );
		$this->subject	= $subject;
	}

	/**
	 *	Returns subject on which this logic exception happened if set.
	 *	@access		public
	 *	@return		string
	 */
	public function getSubject()
	{
		return $this->subject;
	}
}
?>
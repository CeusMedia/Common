<?php
/**
 *	Exception for Input Validations.
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
 *	@extends		RuntimeException
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			09.03.2007
 *	@version		$Id$
 */
/**
 *	Exception for Input Validations.
 *	@category		cmClasses
 *	@package		Exception
 *	@extends		RuntimeException
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			09.03.2007
 *	@version		$Id$
 */
class Exception_Validation extends RuntimeException
{
	/**	@var		array		$errors			List of Validation Errors */
	protected $errors	= array();
	/**	@var		string		$form			Name Form in Validation File */
	protected $form		= "";
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$message		Error Message
	 *	@param		string		$errors			List of Validation Errors
	 *	@return		void
	 */
	public function __construct( $message = null, $errors = array(), $form = "" )
	{
		parent::__construct( $message );
		$this->errors	= $errors;
		$this->form		= $form;
	}
	
	/**
	 *	Returns List of Validation Errors.
	 *	@access		public
	 *	@return		array
	 */
	public function getErrors()
	{
		return $this->errors;
	}
	
	/**
	 *	Returns Name of Form in Validation File.
	 *	@access		public
	 *	@return		string
	 */
	public function getForm()
	{
		return $this->form;
	}
}
?>
<?php
/**
 *	Logic Exception with Message Key for Language Support.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		exception
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			22.02.2007
 *	@version		0.1
 */
/**
 *	Logic Error.
 *	@package		exception
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			22.02.2007
 *	@version		0.1
 *	@deprecated		not working since Exception::getMessage() is final
 *	@todo			to be deleted in 0.6.7
 */
class Exception_Logic extends Exception
{
	/**	@var		string		$messageKey		Message Key */
	public $messageKey;
	/**	@var		string		$subjectValue	Subject Value to be filled in */
	public $subjectValue;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$messageKey		Message Key
	 *	@param		string		$subjectValue	Subject Value to be filled in
	 *	@return		void
	 */
	public function __construct( $messageKey, $subjectValue = "" )
	{
		parent::__construct( $messageKey );
		$this->messageKey	= $messageKey;
		$this->subjectValue	= $subjectValue;
	}

	/**
	 *	Builds Message from given Exception Messages and sets in Subject Value.
	 *	@access		public
	 *	@param		array		$errorMessages	Array of Exception Messages
	 *	@return		string
	 */
	public function getMessage( $errorMessages )
	{
		if( !array_key_exists( $this->messageKey, $errorMessages ) )
			return $this->messageKey;
		$message	= $errorMessages[$this->messageKey];
		$message	= sprintf( $message, $this->subjectValue );
		return $message;
	}
		
	/**
	 *	Returns Name of Source which was not fully accessible.
	 *	@access		public
	 *	@return		string
	 */
	public function getMessageKey()
	{
		return $this->messageKey;	
	}

	/**
	 *	Returns Name of Source which was not fully accessible.
	 *	@access		public
	 *	@return		string
	 */
	public function getSubject()
	{
		return $this->subjectValue;	
	}
}
?>
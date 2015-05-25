<?php
/**
 *	Console input handler.
 *
 *	Copyright (c) 2012 Christian Würker (ceusmedia.com)
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
 *	@package		Console
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.06.2012
 *	@version		$Id$
 */
/**
 *	Console input handler.
 *	@category		cmClasses
 *	@package		Console
 *	@extends		ADT_List_Dictionary
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.06.2012
 *	@version		$Id$
 */
class Console_Prompt {

	/**	@var	resource		$tty		Terminal (or console) input handler */
	protected $tty;

	/**
	 *	Constructor, tries to setup a terminal input resource handler. 
	 *	@access		public
	 *	@return		void
	 *	@throws		RuntimeException	if no terminal resource could by established
	 */
	public function __construct(){
		if( substr(PHP_OS, 0, 3) == "WIN" )
			$this->tty = fopen( "\con", "rb" );
		else if( !($this->tty = fopen( "/dev/tty", "r" ) ) )
			$this->tty = fopen( "php://stdin", "r" );
		else
			throw new RuntimeException( 'Could not create any terminal or console device' );
	}

	/**
	 *	Returns string entered through terminal input resource.
	 *	@access		public
	 *	@param		string		$prompt		Message to show infront of cursor
	 *	@param		integer		$length		Number of bytes to read at most
	 *	@return		string		String entered in terminal
	 */
	public function get( $prompt = "", $length = 1024 ){
		remark( $prompt );
		ob_flush();
		$result = trim( fgets( $this->tty, $length ) );
		return $result;
	}
}
?>

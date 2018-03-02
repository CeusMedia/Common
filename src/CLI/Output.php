<?php
/**
 *	Console Output.
 *
 *	Copyright (c) 2015-2018 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_CLI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.6
 *	@version		$Id$
 */
/**
 *	Console Output.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.6
 *	@version		$Id$
 */
class CLI_Output{

	protected $lastLine	= "";

	/**
	 *	Adds text to current line.
	 *	@access		public
	 *	@param		string		$string		Text to display
	 *	@param		integer		$sleep		Seconds to sleep afterwards
	 *	@return		void
	 */
	public function append( $string = "", $sleep = 0 ){
		$this->sameLine( trim( $this->lastLine ) . $string, $sleep );
	}

	/**
	 *	Display text in new line.
	 *	@access		public
	 *	@param		string		$string		Text to display
	 *	@param		integer		$sleep		Seconds to sleep afterwards
	 *	@return		void
	 */
	public function newLine( $string = "", $sleep = 0 ){
		$string		= Alg_Text_Trimmer::trimCentric( $string, 78 );									//  trim string to <80 columns
		$this->lastLine	= $string;
		print( "\n" . $string );
		if( $sleep )
			sleep( $sleep );
	}

	/**
	 *	Display text in current line.
	 *	@access		public
	 *	@param		string		$string		Text to display
	 *	@param		integer		$sleep		Seconds to sleep afterwards
	 *	@return		void
	 */
	public function sameLine( $string = "", $sleep = 0 ){
		$string		= Alg_Text_Trimmer::trimCentric( $string, 78 );									//  trim string to <80 columns
		$spaces		= max( 0, strlen( $this->lastLine ) - strlen( $string ) );
		$this->lastLine	= $string;
		$fill		= str_repeat( " ", $spaces );
		print( "\r" . $string . $fill );
		if( $sleep )
			sleep( $sleep );
	}
}
?>

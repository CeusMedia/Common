<?php
/**
 *	Console Output.
 *
 *	Copyright (c) 2015-2020 Christian Würker (ceusmedia.de)
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
 *	@copyright		2015-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.6
 */
namespace CeusMedia\Common\CLI;

use CeusMedia\Common\Alg\Text\Trimmer as TextTrimmer;

/**
 *	Console Output.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.6
 */
class Output
{
	protected $lastLine			= '';
	protected $maxLineLength	= 0;

	/**
	 *	Adds text to current line.
	 *	@access		public
	 *	@param		string		$string		Text to display
	 *	@param		integer		$sleep		Seconds to sleep afterwards
	 *	@return		self
	 */
	public function append( string $string = "", $sleep = 0 )
	{
		return $this->sameLine( trim( $this->lastLine ) . $string, $sleep );
	}

	/**
	 *	Display text in new line.
	 *	@access		public
	 *	@param		string		$string		Text to display
	 *	@param		integer		$sleep		Seconds to sleep afterwards
	 *	@return		self
	 */
	public function newLine( string $string = '', $sleep = 0 )
	{
		if( !CLI::checkIsHeadless( FALSE ) ){
			if( $this->maxLineLength )
				//  trim string to <80 columns
				$string		= TextTrimmer::trimCentric( $string, $this->maxLineLength );
			$this->lastLine	= $string;
			print( "\n" . $string );
		}
		return $this;
	}

	/**
	 *	Display text in current line.
	 *	@access		public
	 *	@param		string		$string		Text to display
	 *	@param		integer		$sleep		Seconds to sleep afterwards
	 *	@return		self
	 */
	public function sameLine( string $string = '', $sleep = 0 ): self
	{
		if( !CLI::checkIsHeadless( FALSE ) ){
			if( $this->maxLineLength )
				//  trim string to <80 columns
				$string		= Trimmer::trimCentric( $string, $this->maxLineLength );
			$spaces		= max( 0, strlen( $this->lastLine ) - strlen( $string ) );
			$this->lastLine	= $string;
			$fill		= str_repeat( ' ', $spaces );
			print( "\r" . $string . $fill );
		}
		if( $sleep )
			sleep( $sleep );
		return $this;
	}

	public function setMaxLineLength( int $length ): self
	{
		$this->maxLineLength	= $length;
		return $this;
	}
}

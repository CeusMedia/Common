<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Console input handler.
 *
 *	Copyright (c) 2015-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\CLI;

use RuntimeException;

/**
 *	Console input handler.
 *	@category		Library
 *	@package		CeusMedia_Common_CLI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Prompt
{
	/**	@var	resource		$tty		Terminal (or console) input handler */
	protected $tty;

	/**
	 *	Constructor, tries to set up a terminal input resource handler.
	 *	@access		public
	 *	@return		void
	 *	@throws		RuntimeException	if no terminal resource could be established
	 */
	public function __construct()
	{
		if( str_starts_with(PHP_OS, "WIN" ) )
			$this->tty = fopen( "\con", "rb" );
		else if( !($this->tty = fopen( "/dev/tty", "r" ) ) )
			$this->tty = fopen( "php://stdin", "r" );
		else
			throw new RuntimeException( 'Could not create any terminal or console device' );
	}

	/**
	 *	Returns string entered through terminal input resource.
	 *	@access		public
	 *	@param		string		$prompt		Message to show in front of cursor
	 *	@param		integer		$length		Number of bytes to read at most
	 *	@return		string		String entered in terminal
	 */
	public function get( string $prompt = '', int $length = 1024 ): string
	{
		print( $prompt );
		ob_flush();
		return trim( fgets( $this->tty, $length ) );
	}
}

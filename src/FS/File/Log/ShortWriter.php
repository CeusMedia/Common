<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Writer for short Log Files.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_FS_File_Log
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\Log;

/**
 *	Writer for short Log Files.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Log
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class ShortWriter
{
	/**	@var		array		$patterns	Pattern Array filled with Logging Information */
	protected array $patterns	= [];

	/**	@var		string		$uri		URI of Log File */
	protected string $uri;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$uri		URI of Log File
	 *	@return		void
	 */
	public function __construct( string $uri )
	{
		$this->uri	= $uri;
		$this->setPatterns( [
			time(),
			getEnv( 'REMOTE_ADDR'),
			getEnv( 'REQUEST_URI' ),
			getEnv( 'HTTP_REFERER' ),
			getEnv( 'HTTP_USER_AGENT' )
		] );
	}

	/**
	 *	Adds an entry to the logfile.
	 *	@access		public
	 *	@param		string|array	$line		Entry to add to Log File
	 *	@return		bool
	 */
	public function note( $line ): bool
	{
		if( is_array( $line ) )
			$line	= implode( "|", $line );
		$line	= str_replace( "\n", "\\n", $line );
		$entry	= implode( "|", array_values( $this->patterns ) );
		if( $line )
			$entry = $entry."|".$line;
		$entry	.= "\n";
		$fp = @fopen( $this->uri, "ab" );
		if( $fp ){
			@fwrite( $fp, $entry );
			@fclose( $fp );
			return TRUE;
		}
		return FALSE;
	}

	/**
	 *	Sets Pattern Array filled with Logging Information.
	 *	@access		public
	 *	@param		array		$array		Pattern Array filled with Logging Information
	 *	@return		self
	 */
	public function setPatterns( array $array ): self
	{
		$this->patterns	= $array;
		return $this;
	}
}

<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Reader for short Log Files.
 *
 *	Copyright (c) 2007-2025 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\Log;

use RuntimeException;

/**
 *	Reader for short Log Files.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Log
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Prove File for Existence
 */
class ShortReader
{
	/*	@var		array		$data		Array of Data in Lines */
	protected array $data		= [];

	/*	@var		bool		$isOpen		Status: Log File is read */
	protected bool $isOpen	= FALSE;

	/*	@var		array		$patterns	Pattern Array filled with Logging Information */
	protected array $patterns	= [];

	/**	@var		string		$uri		URI of Log File */
	protected string $uri;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$uri		URI of short Log File
	 *	@return		void
	 */
	public function __construct( string $uri )
	{
		$this->uri	= $uri;
		$patterns	= [
			'timestamp',
			'remote_addr',
			'request_uri',
			'http_referer',
			'http_user_agent'
		];
		$this->setPatterns( $patterns );
	}

	/**
	 *	Returns parsed Log Data as Array.
	 *	@access		public
	 *	@return		array
	 */
	public function getData(): array
	{
		if( !$this->isOpen )
			throw new RuntimeException( "Log File not read" );
		return $this->data;
	}

	/**
	 *	Indicated whether Log File is already opened and read.
	 *	@access		protected
	 *	@return		bool
	 *	@noinspection	PhpUnused
	 */
	public function isOpen(): bool
	{
		return $this->isOpen;
	}

	/**
	 *	Reads Log File.
	 *	@access		public
	 *	@return		bool
	 */
	public function read(): bool
	{
		if( $lines = @file( $this->uri ) ) {
			$this->data = [];
			foreach( $lines as $line )
				$this->data[] = explode( "|", trim( $line ) );
			$this->isOpen	= TRUE;
			return TRUE;
		}
		return FALSE;
	}

	/**
	 *	Sets Pattern Array filled with Logging Information.
	 *
	 *	@access		public
	 *	@param		array		$array		Array of Patterns.
	 *	@return		self
	 */
	public function setPatterns( array $array ): self
	{
		$this->patterns	= $array;
		return $this;
	}
}

<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Checks order of methods within a PHP File.
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
 *	@package		CeusMedia_Common_FS_File_PHP_Check
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\PHP\Check;

use Exception;

/**
 *	Checks order of methods within a PHP File.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_PHP_Check
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class MethodOrder
{
	private string $fileName		= '';
	private array $originalList		= [];
	private array $sortedList		= [];
	private bool $compared			= FALSE;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URL of PHP File
	 *	@return		void
	 */
	public function __construct( string $fileName )
	{
		if( !file_exists( $fileName ) )
			throw new Exception( "File '".$fileName."' is not existing." );
		$this->fileName	= $fileName;
	}

	/**
	 *	Indicates whether all methods are in correct order.
	 *	@access		public
	 *	@return		bool
	 */
	public function compare(): bool
	{
		$this->compared	= TRUE;
		$content	= file_get_contents( $this->fileName );
		$content	= preg_replace( "@/\*.+\*/@sU", "", $content );
		$lines		= explode( "\n", $content );
		foreach( $lines as $line )
		{
			if( preg_match( "@^(#|//)@", trim( $line ) ) )
				continue;
			$matches	= [];
			preg_match_all( "@function\s*([a-z]\S+)\s*\(@i", $line, $matches, PREG_SET_ORDER );
			foreach( $matches as $match )
				$this->originalList[] = $match[1];
		}
		$this->sortedList	= $this->originalList;
		natCaseSort( $this->sortedList );
		return $this->sortedList === $this->originalList;
	}

	/**
	 *	Returns List of methods in original order.
	 *	@access		public
	 *	@return		array
	 */
	public function getOriginalList(): array
	{
		if( !$this->compared )
			throw new Exception( "Not compared yet." );
		return $this->originalList;
	}

	/**
	 *	Returns List of methods in correct order.
	 *	@access		public
	 *	@return		array
	 */
	public function getSortedList(): array
	{
		if( !$this->compared )
			throw new Exception( "Not compared yet." );
		return $this->sortedList;
	}
}

<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Reader for Files with Text Block Contents, named by Section.
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
 *	@package		CeusMedia_Common_FS_File_Block
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\Block;

use CeusMedia\Common\FS\File\Reader as FileReader;

/**
 *	Reader for Files with Text Block Contents, named by Section.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Block
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Reader
{
	protected array $blocks				= [];
	protected string $fileName;
	protected string $patternSection	= "@^\[([a-z][^\]]*)\]$@i";

	/**
	 *	Constructor, reads Block File.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Block File
	 *	@return		void
	 */
	public function __construct( string $fileName )
	{
		$this->fileName	= $fileName;
		$this->readBlocks();
	}

	/**
	 *	Returns Block Content.
	 *	@access		public
	 *	@param		string		$section		Name of Block
	 *	@return		array
	 */
	public function getBlock( string $section ): array
	{
		if( $this->hasBlock( $section ) )
			return $this->blocks[$section];
		return [];
	}

	/**
	 *	Returns Array with Names of all Blocks.
	 *	@access		public
	 *	@return		array
	 */
	public function getBlockNames(): array
	{
		return array_keys( $this->blocks );
	}

	/**
	 *	Returns Array of all Blocks.
	 *	@access		public
	 *	@return		array
	 */
	public function getBlocks(): array
	{
		return $this->blocks;
	}

	/**
	 *	Indicates whether a Block is existing by its Name.
	 *	@access		public
	 *	@param		string		$section		Name of Block
	 *	@return		bool
	 */
	public function hasBlock( string $section ): bool
	{
		$names	= array_keys( $this->blocks );
		$result	= array_search( $section, $names );
		return is_int( $result );
	}

	/**
	 *	Reads Block File.
	 *	@access		protected
	 *	@return		void
	 */
	protected function readBlocks(): void
	{
		$open		= FALSE;
		$section	= NULL;
		$file		= new FileReader( $this->fileName );
		$lines		= $file->readArray();
		foreach( $lines as $line ){
			$line	= trim( $line );
			if( $line ){
				if( preg_match( $this->patternSection, $line ) ){
					$section 	= preg_replace( $this->patternSection, "\\1", $line );
					if( !isset( $this->blocks[$section] ) )
						$this->blocks[$section]	= [];
					$open = TRUE;
				}
				else if( $open && $section ){
					$this->blocks[$section][]	= $line;
				}
			}
		}
		foreach( $this->blocks as $section => $block )
			$this->blocks[$section]	= implode( "\n", $block );
	}
}

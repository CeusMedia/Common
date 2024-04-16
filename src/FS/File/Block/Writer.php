<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Writer for Files with Text Block Contents, named by Section.
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
 *	@package		CeusMedia_Common_FS_File_Block
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\Block;

use CeusMedia\Common\FS\File\Writer as FileWriter;

/**
 *	Writer for Files with Text Block Contents, named by Section.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Block
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Writer
{
	protected string $fileName;

	protected string $patternSection	= "[{#name#}]";

	/**
	 *	Constructor, reads Block File.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Block File
	 *	@return		void
	 */
	public function __construct( string $fileName )
	{
		$this->fileName		= $fileName;
	}

	/**
	 *	Writes Blocks to Block File.
	 *	@access		public
	 *	@param		array		$blocks			Associative Array with Block Names and Contents
	 *	@return		int
	 */
	public function writeBlocks( array $blocks ): int
	{
		$list	= [];
		foreach( $blocks as $name => $content ){
			$list[]	= str_replace( "{#name#}", $name, $this->patternSection );
			$list[]	= $content;
			$list[]	= "";
		}
		$file	= new FileWriter( $this->fileName );
		return $file->writeArray( $list );
	}
}

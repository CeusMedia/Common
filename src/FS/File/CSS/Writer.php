<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Editor for CSS files.
 *
 *	Copyright (c) 2011-2023 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_FS_File_CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\CSS;

use CeusMedia\Common\ADT\CSS\Sheet as CssSheet;
use CeusMedia\Common\FS\File\Writer as FileWriter;
use RuntimeException;

/**
 *	Editor for CSS files.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Writer
{
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string|NULL		$fileName		Relative or absolute file URI
	 *	@return		void
	 */
	public function __construct( ?string $fileName = NULL )
	{
		if( $fileName )
			$this->setFileName( $fileName );
	}

	/**
	 *	Returns name of current CSS File.
	 *	@access		public
	 *	@return		string
	 */
	public function getFileName(): string
	{
		return $this->fileName;
	}

	/**
	 *	Save a sheet structure into a file statically.
	 *	@access		public
	 *	@static
	 *	@param		string			$fileName	Relative or absolute file URI
	 *	@param		CssSheet		$sheet		Sheet structure
	 *	@return		int				Number of written bytes
	 */
	static public function save( string $fileName, CssSheet $sheet ): int
	{
		return FileWriter::save( $fileName, Converter::convertSheetToString( $sheet ) );
	}

	/**
	 *	Set name of CSS file.
	 *	@access		public
	 *	@param		string		$fileName		Relative or absolute file URI
	 *	@return		self
	 */
	public function setFileName( string $fileName ): self
	{
		$this->fileName	= $fileName;
		return $this;
	}

	/**
	 *	Writes a sheet structure to the current CSS file.
	 *	@access		public
	 *	@param		CssSheet	$sheet		Sheet structure
	 *	@return		int						Number of written bytes
	 *	@throws		RuntimeException		if no CSS file is set, yet.
	 */
	public function write( CssSheet $sheet ): int
	{
		if( !$this->fileName )
			throw new RuntimeException( 'No CSS file set yet' );
		return self::save( $this->fileName, $sheet );
	}
}
